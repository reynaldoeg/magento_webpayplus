<?php

namespace ReyEs\WebPayPlus\Controller\Index;

use Magento\Quote\Model\Quote;
use Magento\Checkout\Model\Cart;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;

use ReyEs\WebPayPlus\Helper\AESCrypto;

class Geturl extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;
    protected $resultJsonFactory;
    protected $scopeConfig;
    protected $aescrypto;
    protected $logger;
    protected $cart;
    protected $quote;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        JsonFactory $resultJsonFactory,
        ScopeConfigInterface $scopeConfig,
        AESCrypto $aescrypto,
        Cart $cart,
        Quote $quote,
        \Psr\Log\LoggerInterface $logger
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->scopeConfig = $scopeConfig;
        $this->aescrypto = $aescrypto;
        $this->logger = $logger;
        $this->cart = $cart;
        $this->quote = $quote;
    }

    public function execute()
    {
        $result = $this->resultJsonFactory->create();

        if( $this->getRequest()->getParam('amount') == null )
        {            
            return $result->setData(['status' => 'error']);
        }
            
        try
        {
            //$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            //$cart = $objectManager->get('\Magento\Checkout\Model\Cart');
            $quote = $this->cart->getQuote();
            $quoteId = $quote->getId();
            //$reservedquoteId = $quote->reserveOrderId();

            //var_dump($reservedquoteId);

            # Business
            $id_company = $this->scopeConfig->getValue('payment/webpayplus_gateway/id_company', ScopeInterface::SCOPE_STORE);
            $id_branch = $this->scopeConfig->getValue('payment/webpayplus_gateway/id_branch', ScopeInterface::SCOPE_STORE);
            $user = $this->scopeConfig->getValue('payment/webpayplus_gateway/user', ScopeInterface::SCOPE_STORE);
            $password = $this->scopeConfig->getValue('payment/webpayplus_gateway/password', ScopeInterface::SCOPE_STORE);
            
            # Url
            // $reference = $this->getRequest()->getParam('reference');
            //$reference = uniqid();
            //$reference = $this->cart->getQuote()->getId();
            // $reference = Quote::reserveOrderId;
            $reference = $quoteId;
            $amount = $this->getRequest()->getParam('amount');
            $moneda = $this->getRequest()->getParam('moneda');
            $canal = 'W';
            $omitir_notif_default = $this->scopeConfig->getValue('payment/webpayplus_gateway/omitir_notif_default', ScopeInterface::SCOPE_STORE);
            $promociones = 'C';
            $st_correo = $this->scopeConfig->getValue('payment/webpayplus_gateway/st_correo', ScopeInterface::SCOPE_STORE);
            // $fh_vigencia = '12/07/2019';
            $mail_cliente = $this->getRequest()->getParam('mail_cliente');
            $st_cr = 'A';

            # Cifrando la cadena
            $key = $this->scopeConfig->getValue('payment/webpayplus_gateway/merchant_gateway_key', ScopeInterface::SCOPE_STORE);
            
            # Servicio de Generación
            $endpoint = $this->scopeConfig->getValue('payment/webpayplus_gateway/endpoint', ScopeInterface::SCOPE_STORE);
            $data0 = $this->scopeConfig->getValue('payment/webpayplus_gateway/data0', ScopeInterface::SCOPE_STORE);
            
            $debug = $this->scopeConfig->getValue('payment/webpayplus_gateway/debug', ScopeInterface::SCOPE_STORE);

            $datos_adicionales = $this->getRequest()->getParam('datos_adicionales');

            $datos_adicionales_str = '';
            /*if (is_array($datos_adicionales))
            {
                foreach ($datos_adicionales as $item) {
                    $tmp = "<data id=\"{$item['sku']}\" display=\"true\"><label>Articulo</label><value>{$item['name']}</value></data>";
                    $datos_adicionales_str .= $tmp;
                }
            }*/


            # Paso 1: Cadena XML
            $originalString = <<<EOD
                <?xml version="1.0" encoding="UTF-8"?>
                <P>
                  <business>
                    <id_company>{$id_company}</id_company>
                    <id_branch>{$id_branch}</id_branch>
                    <user>{$user}</user>
                    <pwd>{$password}</pwd>
                  </business>
                  <url>
                    <reference>{$reference}</reference>
                    <amount>{$amount}</amount>
                    <moneda>{$moneda}</moneda>
                    <canal>{$canal}</canal>
                    <omitir_notif_default>{$omitir_notif_default}</omitir_notif_default>
                    <promociones>{$promociones}</promociones>
                    <st_correo>{$st_correo}</st_correo>
                    <mail_cliente>{$mail_cliente}</mail_cliente>
                    <st_cr>{$st_cr}</st_cr>
                    <datos_adicionales>
                      {$datos_adicionales_str}
                    </datos_adicionales>
                  </url>
                </P>
EOD;


            # Paso 2: Cifrando la cadena
            $encryptedString = $this->aescrypto->encriptar($originalString, $key); 

            # Paso 3: Servicio de Generación
            $encodedString = urlencode("<pgs><data0>{$data0}</data0><data>{$encryptedString}</data></pgs>");
            $postfields = "xml={$encodedString}";
            $headers = [
                'Cache-Control: no-cache',
                'Content-Type: application/x-www-form-urlencoded; charset=utf-8'
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$endpoint);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$postfields);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $response = curl_exec ($ch);
            curl_close ($ch);

            # Paso 4: Descifrando la respuesta del Servicio de Generación
            $decryptedString = $this->aescrypto->desencriptar($response, $key);
            $xml_object = simplexml_load_string($decryptedString);

            # Paso 5: Liga de Cobro
            if ($xml_object) {
                $urlwebpay = $xml_object->nb_url;
                $data = [
                    'url' => $urlwebpay
                ];
            } else {
                $data = ['status' => 'error'];
            }
            
            return $result->setData($data);

        } catch(Exception $e) {
            return $result->setData([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
            
    }
}

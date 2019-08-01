<?php
/**
 * Copyright © 2019 ReyEs. All rights reserved.
 * 
 */

namespace ReyEs\WebPayPlus\Controller\Index;

use Magento\Checkout\Model\Cart;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Quote\Model\QuoteManagement;
use Magento\Quote\Api\Data\PaymentInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\CartManagementInterface;

use ReyEs\WebPayPlus\Helper\AESCrypto;

class Response extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;
    protected $resultJsonFactory;
    protected $scopeConfig;
    protected $quoteManagement;
    protected $aescrypto;
    protected $logger;
    protected $cart;
    protected $cartRepositoryInterface;
    protected $cartManagementInterface;


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
        QuoteManagement $quoteManagement,
        CartRepositoryInterface $cartRepositoryInterface,
        CartManagementInterface $cartManagementInterface,
        \Psr\Log\LoggerInterface $logger,
        Cart $cart
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->scopeConfig = $scopeConfig;
        $this->aescrypto = $aescrypto;
        $this->logger = $logger;
        $this->cart = $cart;
        $this->quoteManagement = $quoteManagement;
        $this->cartRepositoryInterface = $cartRepositoryInterface;
        $this->cartManagementInterface = $cartManagementInterface;
    }

    public function execute()
    {
        $result = $this->resultJsonFactory->create();

        $message = 'WebPayPlus - Response';
        $this->logger->info($message);

        // Obtiene respuesta del servidor de WebPayPlus
        $strResponse = $this->getRequest()->getParam('strResponse');

        $key = $this->scopeConfig->getValue('payment/webpayplus_gateway/merchant_gateway_key', ScopeInterface::SCOPE_STORE);

        // Decifra la respuesta
        $decryptedString = $this->aescrypto->desencriptar($strResponse, $key);

        $this->logger->info('decryptedString');
        $this->logger->info($decryptedString);

        $xmlstring = simplexml_load_string($decryptedString);

        $centerofpayments = json_decode(json_encode((array)$xmlstring), TRUE);

        if (!isset($centerofpayments['response']))
        {
            return $result->setData([
                    'status' => 'error',
                    'message' => 'no answer'
                ]);
        }

        $response = $centerofpayments['response'];
        $this->logger->info('response');
        $this->logger->info($response);

        try
        {
            if($response == "error")
            {
                $cd_error = $centerofpayments['cd_error'];

                switch ($cd_error) {
                    case '03':
                        $message = 'Existe un problema con la configuración. Los datos de empresa, sucursal o usuario son incorrectos. Contacte al administrador del sitio (CODE03).';
                        break;
                    case '05':
                        $message = 'Existe un problema con la configuración. La sucursal no está configurada para hacer ese tipo de transacción. Contacte al administrador del sitio (CODE05).';
                        break;
                    case '08':
                        $message = 'El importe es menor al permitido para la promoción a meses seleccionada. Contacte al Administrador del sitio (CODE08).';
                        break;
                    case '09':
                        $message = 'El importe es mayor al permitido. Contacte al Administrador del sitio (CODE09).';
                        break;
                    case '11':
                        $message = 'La transaccion ya fue rechazada durante el día. Intente con otra tarjeta o en caso contrario, intente a partir de mañana.(CODE11)';
                        break;
                    case '16':
                        $message = 'El importe es mayor al permitido. Contacte al Administrador del sitio (CODE16)';
                        break;
                    default:
                        $message = 'Error al procesar el pago';
                        break;
                }

                $this->logger->info('ERROR al procesar el pago:');
                $this->logger->info($message);
                
                return $result->setData([
                    'status' => $response,
                    'message' => $message
                ]);
            } 
            elseif ($response == "denied" )
            {
                return $result->setData(['status' => $response]);
            }

            if($response == "approved")
            {
                $reference = $centerofpayments['reference'];
                $foliocpagos = $centerofpayments['foliocpagos'];
                $auth = $centerofpayments['auth'];
                $cd_response = $centerofpayments['cd_response'];


                //===================================
                $quoteId = $reference; 
                $quote = $this->cartRepositoryInterface->get($quoteId);

                $pay = $quote->getPayment();
                $pay->setMethod('webpayplus_gateway');

                $this->logger->info('Quote id: ' . $quote->getId());

                $retur_id = $this->quoteManagement->placeOrder($quote->getId(), $pay);
                $this->logger->info('Order id: ' . $retur_id);
                //===================================

                $data = [
                    'stauts' => $response,
                    'reference' => $reference,
                    'foliocpagos' => $foliocpagos,
                    'auth' => $auth,
                    'cd_response' => $cd_response
                ];
                
                return $result->setData($data);
            }

        } catch(Exception $e) {
            return $result->setData([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
            
    }
}

<?php

namespace ReyEs\WebPayPlus\Controller\Index;

use Magento\Checkout\Model\Cart;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
//use Magento\Quote\Model\QuoteFactory;
use Magento\Quote\Model\QuoteManagement;
use Magento\Quote\Api\Data\PaymentInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\CartManagementInterface;
//use Magento\Quote\Model\Quote\Payment;


use ReyEs\WebPayPlus\Helper\AESCrypto;

class Response extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;
    protected $resultJsonFactory;
    protected $scopeConfig;
    //protected $quoteFactory;
    protected $quoteManagement;
    protected $aescrypto;
    protected $logger;
    protected $cart;
    protected $cartRepositoryInterface;
    protected $cartManagementInterface;
    // protected $payment;


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
        //QuoteFactory $quoteFactory,
        QuoteManagement $quoteManagement,
        CartRepositoryInterface $cartRepositoryInterface,
        CartManagementInterface $cartManagementInterface,
        \Psr\Log\LoggerInterface $logger,
        // Payment $payment,
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
        //$this->quoteFactory = $quoteFactory;
        $this->quoteManagement = $quoteManagement;
        $this->cartRepositoryInterface = $cartRepositoryInterface;
        $this->cartManagementInterface = $cartManagementInterface;
        //$this->payment = $payment;
    }

    public function execute()
    {
        $result = $this->resultJsonFactory->create();

        $message = 'WebPayPlus - Response';
        $this->logger->info($message);
        

        /*$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $cart = $objectManager->get('\Magento\Checkout\Model\Cart');
        $quote = $cart->getQuote();
        $quoteId = $quote->getId();
        $quoteId = $quote->getReserveOrderId();*/

        /*$quotee = Mage::getModel('sales/quote')->getCollection()
            ->addFieldToFilter('reserved_order_id', $quoteId)
            ->getFirstItem();*/
        


        // $quote->setPaymentMethod('cashondelivery');
        // echo get_class($quote);
        // echo 'Quote id request: ';
        // var_dump($quote->getId());

        //$quote->removeAllItems();
        //$quoteFullObject->delete();
        //$quote->save();

        //$order = $this->cartManagementInterface->submit($quote);
        //var_dump($quote->reserveOrderId());
        //var_dump($quote->getCurrency());



        //$orderId = $this->cartManagementInterface->placeOrder($quote->getId(), 'cashondelivery');

        $strResponse = $this->getRequest()->getParam('strResponse');

        // Aprobado
        //$strResponse = "P3dwwxSG6J3VG1ORL%2FWnL2KL5%2FgaoKKaywv9TfnBmGBRLZOGPEExnQtBzkQizOnR%0D%0AXorlnLSLwUhWIMcdS91vje%2B9iWYxVgBUoYNUkCSryspGXekC3X7ErtPXdTQxqUg2%0D%0Ag1Jz4SEnr7WKvM9iugv5oWr%2BhmlkTOSAZ4%2FFfhLzkeIA2bOno%2BE1yPSiIELLzXGm%0D%0AImSA6c6i3ypCAW0x3L2XBBJ%2BP1g0F3xWrTujRxvxANFsEtbiBaX5lXmEHmG4uJzy%0D%0Anhf3PPT%2BdHKkHJ0aDJe04YHCfNv9wZC3ye%2FRfY9d9N0ziNnpLJzqTT6eDOrcfTDe%0D%0Aa38XyYB917F6wfK44W3bk50f90NHW1GxHRWOnLIGaize04U7nAXYGpr5wKO86JS5%0D%0AzUqpTFEzwoE0HW44FM%2BYuK1NfoAMO4FuNPq6Gmq6KMElbJSlOQAys75BzifnNJ%2Fj%0D%0AIGCVNu9UoZoN9VlngdZTVCSj3YQJd2Ddcl9x%2Fg%2Fq155%2FGoMGZ2puCgQ3vROrvJID%0D%0AbwouOuwap1Viaf8PPn%2B1VIoKyjKuy7AcoJcfFSDmPiDop2AEhVO14aDvOsSwxrAp%0D%0AiWMI45PKpAESc%2BIJFH6%2FilNQqfqiSn28f10g1MU1aegZOQ2dw4QGKUTmlsik7L7E%0D%0AhhHdoBfabd1li2fsPCw9w7ys5Jan8s63ugQcaX8zig69%2FsDTcikdYMtLFI7R71HH%0D%0AFDZQNng93sg3MmreTvIGhBgowVslqfQqQBZhjLFdJtGvSW3aCo9KZQbkNrg9Kcq%2B%0D%0AKEH8Oq%2Frfkej9HtM0Gw0qoO9GiEWrbIx4jPPuO0P7nCFLNQfTBOjlvZbMSQyIL8c%0D%0A6TRgY7EKBvNrT96d784ZjSuZS3hhgV0BXtgeXgyXEZePmYLfi%2FGmtCJGgg%2BhnS1M%0D%0AT6SsNq9U38YyNlTUxMxjeEz837CzstCGatAwSntITSaxMOunmlB0vhokrTuc1%2Fr1%0D%0AoE01Q407mBbmjmAgzG1VxtHtmVRMpLqSDD%2BdDRwhcj20FGQKRUslcMdJ%2BN8D24cB%0D%0A4Ns4wm9iNpWz%2F4QHK4CTyBm0k6V%2BDmDNd7goWcULuB7GlXUjH3eEsIxyzjVsDtUz%0D%0AgPiDnJUr3a%2FpzUqz03w%2BbDbpMqCfJncJEyQzeDlLHelK8Ie%2FcBKb0UUlnBLpwLYK%0D%0ArkiMftPgrENcRHjBcgCR3jqzQ%2Bx%2Bhfm8pgIvD8QFS3byXYc1ZvJlV5sJ8qVaa%2FZm%0D%0A";

        // Denegado por el emisor
        //$strResponse = "tSYkX0f5y6sqdis%2B5fGN15%2BGvqO99jopz%2BsU%2BBfDbyEXEdMBLOAnoTP7EHpQonCh%0D%0AlgTeaAXNoIoXy%2FNYkfSE20%2FF7vfx5e22aWD%2FPAVxqJV5%2B97GKP3nf1aQjELqsAk3%0D%0A1%2BhVMknH9Gf3PawaI%2FGzzPMojUDkmwtt%2BhBOsSoFSdrdqZ4qwQEWc7TSAR%2Fv1JNb%0D%0ATXqcwhpYAvbiW5rq%2BHiEVZTG9kzzwipD%2FXrUr%2F8OHboHMz5CQelChiH9jZNEWR2Q%0D%0AQ3wIrx%2Br0KI8%2FGWVeQRIpqJUaXehRnk97H32wYFkKhS3IIMrK0l2rco3oCDCOADF%0D%0AULwtZ0ZOVH%2F%2BQeY7M%2FNh9fynhPxXpvPjWyHwf0L68InnH5zdykZX7H67Xr4ob8mf%0D%0AOcv1Xqhni6RVASWbOnoH4xUpwDpX45sbJVGVBFFx8Yb1hSAhs9SXtJGvGqPuMYK3%0D%0AvffrNfmjXzqzRGxoGdzHMRYIDnjWbuYAU%2BFzBP857V9vDPu4R94uV1fmNNGPFiEx%0D%0AYJACWPPavBe4tdcRv%2FWdX8dpcH81aQq6%2B05PzgaRrVc94DkZd9BZcZxXOzRP2Peb%0D%0AVKNGa%2BK2d3KOSDfKoSrAfy5%2BGqDTIgKnpHajp7zy3wE1mjbNbXyDgV0Eq%2FRlah9r%0D%0A";

        //Error
        // $strResponse = "Em9G7O4bxzuFaZgGVuXn2s%2BT8jyp068WQXAmr7NZz%2B9Nn8pCUV1H9NU60dPQg6zt%0D%0A0ShZQGa8NUuPitp%2FRzfy4ZBsUpyXtSC%2FeW8FGQyxUz6hjtn9IPt%2BYEzjaBjU0NdU%0D%0Ap6hxFpb3zGctVijc4w4REOjW85rdY4rLpszw4v0%2B3yYGbug%2BiVl1oiEDbhZFdTid%0D%0A3cyoWaxpqk%2B9JZqqi8XxEFfigt%2FZ8KPia0vnGeKAwB8oqOlMOZUTym6AwsLzIABD%0D%0AquzfFXsQZisTBTrQMwezo3Mm%2B3hc6U1iTy4RUJQncvtk3FbkFy90iFzOzOrLjw0q%0D%0Ak%2FbpDMcOWhbBbmknHQqD6GYpkRrXXRV5q7eyBSQLhlttmsGzujVQts5oPBodbvUp%0D%0Av3QG9OsGCv1C1ylfxAAJxrlmokU46BDGtgv003xtpn9DoYm6V7reroQWL6n8JNhX%0D%0AY2YrGqg%2Fdu9etpXdKMhLdGDiKucG5trNR3YEt4qt4ARhzhq%2BnWr1DI2%2BQvwQfcyD%0D%0AuoF5vwWL%2FDfFYE2HfGgXkBvJ2hsbjARog%2FOCJSpr%2FA6kX4N7MLiDBWGig7TiVs8y%0D%0A%2F2IpqEiRB3h0CY6CCaP8N1ptiCSzJHYh%2FKx5G6jF8C4cCElrffPvd%2FbIL0v0gzXm%0D%0A";

        $decodedString = urldecode($strResponse);
        
        $key = $this->scopeConfig->getValue('payment/webpayplus_gateway/merchant_gateway_key', ScopeInterface::SCOPE_STORE);
        $decryptedString = $this->aescrypto->desencriptar($decodedString, $key);

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
        $this->logger->info($response);
        //var_dump($response);


        $reference = $this->cart->getQuote()->getId();
        echo 'Current id: ';
        var_dump($reference);

        //var_dump($centerofpayments);


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
                // $reference = 32;
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

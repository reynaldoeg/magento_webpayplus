<?php

namespace ReyEs\WebPayPlus\Controller\Index;

//use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
//use Magento\Framework\Controller\Result\JsonFactory;
//use Magento\Framework\App\Config\ScopeConfigInterface;

//use ReyEs\WebPayPlus\Helper\AESCrypto;

class Thankyou extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;
    protected $resultJsonFactory;
    protected $scopeConfig;
    protected $aescrypto;
    protected $logger;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Psr\Log\LoggerInterface $logger
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        //$this->resultJsonFactory = $resultJsonFactory;
        //$this->scopeConfig = $scopeConfig;
        //$this->aescrypto = $aescrypto;
        $this->logger = $logger;
    }

    public function execute()
    {
        $resultPageFactory = $this->resultPageFactory->create();
        
        // Add page title
        $resultPageFactory->getConfig()->getTitle()->set(__('Gracias por tu compra'));
        
        return $resultPageFactory; 
    }
}

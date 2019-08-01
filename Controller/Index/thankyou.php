<?php
/**
 * Copyright © 2019 ReyEs. All rights reserved.
 * 
 */

namespace ReyEs\WebPayPlus\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

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
        $this->logger = $logger;
    }

    public function execute()
    {
        $resultPageFactory = $this->resultPageFactory->create();
        
        // Add page title
        $resultPageFactory->getConfig()->getTitle()->set(__('Detalles de tu compra'));
        
        return $resultPageFactory; 
    }
}

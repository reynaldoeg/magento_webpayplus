<?php
/**
 * Copyright © 2019 ReyEs. All rights reserved.
 * 
 */
namespace ReyEs\WebPayPlus\Model\Ui;

use Magento\Store\Model\ScopeInterface;
use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
// use Magento\SamplePaymentGateway\Gateway\Http\Client\ClientMock;

/**
 * Class ConfigProvider
 */
final class ConfigProvider implements ConfigProviderInterface
{
    const CODE = 'webpayplus_gateway';

    protected $scopeConfig;

    /**
     * Payment ConfigProvider constructor.
     * @param \Magento\Payment\Helper\Data $paymentHelper
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Retrieve assoc array of checkout configuration
     *
     * @return array
     */
    public function getConfig()
    {
        return [
            'payment' => [
                self::CODE => [
                    /*'transactionResults' => [
                        ClientMock::SUCCESS => __('Success'),
                        ClientMock::FAILURE => __('Fraud')
                    ],*/
                    'id_company' => $this->scopeConfig->getValue('payment/webpayplus_gateway/id_company', ScopeInterface::SCOPE_STORE),
                    'id_branch' => $this->scopeConfig->getValue('payment/webpayplus_gateway/id_branch', ScopeInterface::SCOPE_STORE),
                    'user' => $this->scopeConfig->getValue('payment/webpayplus_gateway/user', ScopeInterface::SCOPE_STORE),
                ]
            ]
        ];
    }

}

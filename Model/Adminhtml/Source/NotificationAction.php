<?php
/**
 * Copyright © 2019 ReyEs. All rights reserved.
 * 
 */
namespace ReyEs\WebPayPlus\Model\Adminhtml\Source;

use Magento\Payment\Model\Method\AbstractMethod;

/**
 * Class NotificationAction
 */
class NotificationAction implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => '0',
                'label' => __('Send Email')
            ],
            [
                'value' => '1',
                'label' => __('Do not Send Email')
            ]
        ];
    }
}

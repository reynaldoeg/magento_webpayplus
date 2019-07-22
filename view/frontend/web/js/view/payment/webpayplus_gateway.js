/**
 * Copyright © 2019 ReyEs. All rights reserved.
 * 
 */
/*browser:true*/
/*global define*/
define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'webpayplus_gateway',
                component: 'ReyEs_WebPayPlus/js/view/payment/method-renderer/webpayplus_gateway'
            }
        );
        /** Add view logic here if needed */
        return Component.extend({});
    }
);

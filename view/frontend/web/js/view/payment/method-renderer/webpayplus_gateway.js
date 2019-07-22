/**
 * Copyright © 2019 ReyEs. All rights reserved.
 * 
 */
/*browser:true*/
/*global define*/
define(
    [
        'ko',
        'jquery',
        'Magento_Checkout/js/view/payment/default',
        'ReyEs_WebPayPlus/js/action/set-payment-method-action'
    ],
    function (ko, $, Component, setPaymentMethodAction) {
        'use strict';

        return Component.extend({
            defaults: {
                //redirectAfterPlaceOrder: false,
                //transactionResult: '',
                template: 'ReyEs_WebPayPlus/payment/form'
            },

            afterPlaceOrder: function () {
                console.log('afterPlaceOrder');
                
                // setPaymentMethodAction(this.messageContainer);
                return false;
            },

            context: function() {
                return this;
            },
 
            getCode: function() {
                return 'webpayplus_gateway';
            },
 
            isActive: function() {
                return true;
            },

            /**
             * Place order.
             */
            placeOrderx: function () {
                
                setPaymentMethodAction(checkoutConfig);

                return false;
            },


            getWebPayUser: function() {
                console.log(window.checkoutConfig.payment.webpayplus_gateway.user);
                return window.checkoutConfig.payment.webpayplus_gateway.user;
            },

        });
    }
);
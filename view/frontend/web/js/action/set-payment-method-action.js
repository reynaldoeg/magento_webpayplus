/*jshint jquery:true*/
define(
    [
        'jquery',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/url-builder',
        'mage/storage',
        'Magento_Checkout/js/model/error-processor',
        'Magento_Customer/js/model/customer',
        'Magento_Checkout/js/model/full-screen-loader'
    ],
    function ($, quote, urlBuilder, storage, errorProcessor, customer, fullScreenLoader) {
        'use strict';
        return function (checkoutConfig) {

            var checkout_url = checkoutConfig.checkoutUrl;
            var base_url = checkout_url.substr(0, checkout_url.indexOf('checkout'));

            var base_grand_total = checkoutConfig.quoteData.base_grand_total;
            var customer_email = checkoutConfig.quoteData.customer_email;
            var base_currency_code = checkoutConfig.quoteData.base_currency_code;
            var quoteItemData = checkoutConfig.quoteItemData;

            $.ajax({
                showLoader: true,
                method: "POST",
                url: base_url+'webpay/index/geturl',
                data: {
                    reference: "O190613",
                    amount: base_grand_total,
                    mail_cliente: customer_email,
                    moneda: base_currency_code,
                    datos_adicionales: quoteItemData
                },
            }).done(function (data) {
                console.log(data.url[0]);
                $.mage.redirect(data.url[0]);
            });
            //$.mage.redirect('https://wppsandbox.mit.com.mx/i/KY6FHRDK'); //url is your url
        };
    }
);
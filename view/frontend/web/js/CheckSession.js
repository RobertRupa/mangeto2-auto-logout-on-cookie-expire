/**
 * @author Robert Rupa <office@konatsu.pl>
 */
define([
    'jquery',
    'mage/cookies',
    'Magento_Customer/js/customer-data'
], function ($, cookies, customerData) {
    'use strict';

    function checkSession(redirectURL) {
        window.setInterval(function(){
            let customer = customerData.get('customer');
            if( $.cookie('session_lifetime') === null && typeof customer().email !== "undefined") {
                window.location.href = redirectURL;
            }
        }, 5000);
    }

    return function (config) {
        checkSession(config.redirectURL);
        var date = new Date();
        date.setTime(date.getTime()+config.lifeTime*1000);
        $.cookie("session_lifetime", 1, {
            expires : date,
            path    : '/'
        });
    }
});

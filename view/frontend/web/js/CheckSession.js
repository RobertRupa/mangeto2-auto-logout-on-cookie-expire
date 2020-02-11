/**
 * @author Robert Rupa <office@konatsu.pl>
 */
define([
    'jquery',
    'mage/cookies'
], function ($, cookies) {
    'use strict';

    function checkSession(redirectURL) {
        window.setInterval(function(){
            let sessionLifetimeCookie = $.cookie('session_lifetime');
            if( sessionLifetimeCookie === null ) {
                window.location.href = redirectURL;
            }
        }, 5000);
    }

    return function (config) {
        checkSession(config.redirectURL);
    }
});

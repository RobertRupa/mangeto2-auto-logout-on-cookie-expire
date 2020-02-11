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
            if( $.cookie('session_lifetime') === null ) {
                window.location.href = redirectURL;
            } else {
                console.log("valid");
            }
        }, 5000);
    }

    return function (config) {
        checkSession(config.redirectURL);
    }
});
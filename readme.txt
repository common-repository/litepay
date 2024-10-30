=== LITEPAY Crypto Payments plugin for WordPress WooCommerce (NO KYC) ===
Contributors: litepay
Tags: bitcoin payment, bitcoin payment system, crypto payment system, monero, litecoin, cash
Requires at least: 4.3.1
Tested up to: 6.0
Stable tag: 5.6
Requires PHP: 5.3
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

This is a Wordpress Bitcoin Payment module for Wordpress WooCommerce from LITEPAY.ch.

== Description ==

This is a Wordpress Bitcoin Payment module for Wordpress WooCommerce from LITEPAY.ch, which allows your shop to accept bitcoin payments in minutes. It is based on LITEPAY.ch Merchant API, which you can read about at https://litepay.ch/api_merchant_doc. The plugin works with a username named VendorID & a password, named Secret Key. You can register for a VendorID and Secret Key on our website, at https://litepay.ch/merchant/


Key features: 
* low fees, Litepay.ch takes 0.5% percent from the paid amount
* let customers pay with bitcoin / litecoin / bitcoin cash / zcash / monero and in the future, more coins.
* we accept USD, EUR, GBP, AUD, CHF, CAD, HUF, RON, PLN, RUB, HRK, INR 
* No identifying information about transactions are being kept for more than 30 days. NO KYC

== Installation ==

1. Upload plugin directory to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the WooCommerce->Settings->Payments to configure & activate the plugin

== Activation ==

1. In order for the plugin to work, you will need to register for a Secret Key and VendorID on our website. 
   Please use the following link to register for Merchant API https://litepay.ch/merchant/ 

2. Once you have received the Secret Key and VendorID, you will need to go to WooCommerce->Settings->Payments, click manage and paste the Secret Key & VendorID. Everything should be in working condition. Try a test order to be sure it works as it should. 


== Frequently Asked Questions ==

= How does the plugin works? =

  Quite easy, once a order has been created and the user proceeded to checkout, the plugin will redirect to the litepay merchant payment page,
  where the buyer will be directed to pay the invoice. If the invoice has been paid, the buyer will be redirected back to the website where he 
  placed the order, displaying the created order. If the invoice is not paid, it will remain in limbo (30 days) until its paid. 

= The plugin does not work =
  Make sure you have the correct Wordpress/PHP version installed. 
  The VendorID and Secret Key correctly written in the settings page.

  You can contact us with screenshots of the errors to info@litepay.ch and we will provide support.








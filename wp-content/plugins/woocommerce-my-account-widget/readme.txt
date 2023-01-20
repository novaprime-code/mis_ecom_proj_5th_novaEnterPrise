=== WooCommerce My Account Widget ===
Contributors: pepbc
Plugin URI: https://wordpress.org/plugins/woocommerce-my-account-widget/
Author URI: https://pepbc.nl
Tags: woocommerce, file upload
Requires at least: 5.6
Requires PHP: 7.0
Tested up to: 5.7
Stable tag: 0.6.6
WC requires at least: 3.5
WC tested up to: 5.1

License: GPLv2

This plugin adds a widget with customer account information to your WooCommerce shop.
== Description ==
The _WooCommerce My Account Widget_ allows shop managers to display customer information in a widget.

This plugin is compatible with WordPress 5.6 (and higher) and WooCommerce 3.5 (and higher)

**Features**

* Display link to shopping cart
* Display number of items in shopping cart
* Display number of unpaid orders
* Display number of uncompleted orders
* Display number of files left to upload (working with the WP Fortune WooCommerce Uploads plugin)
* Display a log-in form when logged out
* Localisation: English, Dutch, Norwegian, Russian and Persian, Serbo-Croation

**WooCommerce Uploads**

This widgets shows also the number of files the customer has to upload, when you use the WooCommerce Uploads plugin.
More information about that plugin on [WP Fortune.com](http://wpfortune.com/shop/plugins/woocommerce-uploads/).

**WooCommerce Upload My File plugin**

This widgets shows also the number of files the customer has to upload, when you use our WooCommerce Upload My File plugin.
More information about that plugin on [WordPress.org plugin repository](https://wordpress.org/plugins/woocommerce-upload-my-file/).

== Installation ==

1. Install WooCommerce My Account Widget either via the WordPress.org plugin directory or by uploading the files to the '/wp-content/plugins/' directory.
2. Activate the widget through the 'Plugins' menu in WordPress.

== Upgrade Notice ==
Please backup first.

== Screenshots ==

1. WooCommerce My Account Widget settings
2. WooCommerce My Account Widget on the website, in default Twenty Twelve theme
2. WooCommerce My Account Widget on the website, styled with custom CSS (not attached)

== Changelog ==
***WooCommerce My Account Widget***
= 2019.03.19 version 0.6.6 =
* Fixed small php notice on line 67

= 2018.12.21 version 0.6.5 =
* Added basic styling and icons

= 2018.05.14 version 0.6.4 =
* Fixed PHP 7.2 notice

= 2017.11.23 version 0.6.3 =
* Fixed username/email label issue for some themes

= 2017.10.23 version 0.6.2 =
* Fixed WC my account page login error

= 2017.10.12 version 0.6.1 =
* Tested for WC 3.2

= 2017.05.25 version 0.6.0 =
* Updated readme.txt
* Tested and fixed several issues for WooCommerce 3.0

= 2015.08.11 version 0.5.0 =
* Fixed PHP 4 constructor style in widget

= 2015.03.23 - version 0.4.9 =
* Fixed: Bug with translations
* Added: Remember me checkbox

= 2015.01.30 - version 0.4.8 =
* Fixed: Small bug when retreiving orders in WC > 2.2

= 2015.01.08 - version 0.4.7 =
* Fixed: stupid alert message for testing removed

= 2014.12.29 - version 0.4.6 =
* Fixed: wrong text view on some themes for "Username or Email"

= 2014.12.18 - version 0.4.5 =
* Added: French translation
* Added: Alert message when username and/or password are empty

= 2014.11.20 - version 0.4.4 =
* Added: Danish translation

= 2014.09.12 - version 0.4.3 =
* Fixed: Plugin is now working correctly with WooCommerce 2.2

= 2014.08.15 - version 0.4.2 =
* Fixed: The plugin is now compatible with WooCommerce Uploads

= 2014.08.11 - version 0.4.1 =
* Fixed: The plugin is now compatible with WooCommerce 2.2.-bleeding and WordPress 4.0-beta3

= 2014.05.12- version 0.4 =
* Corrected cart link on logged-out version

= 2014.04.06- version 0.3 =
* Added: Login with Email (thanks to pokeraitis for the idea & credits to the developers of the WP Email Login plugin )
* Added: Custom login URL (thanks to rhonn for the idea)
* Fixed: Better support for WPML (not fully supported yet) - Please make sure you update your language strings! - Please see FAQ

= 2014.03.13- version 0.2.9.3 =
* Added: We've missed some languages strings and we've added it now ;-) SORRY!

= 2014.03.12- version 0.2.9.2 =
* Added: Italian languages
* Changed: We've changed the way plural and singular language strings are shown. Please update your languages files if translations are not shown correctly anymore! Sorry for this!
* Fixed: Small bug when not logged in on some hosts(mostly not displayed, but what the hack, let's solve it anyway)

= 2013.11.16- version 0.2.9 =
* Tweak: Better support for WPML translations
* Fixed: show registration link only when registration is enabled.

= 2013.11.14- version 0.2.8 =
* Added: Serbo-Croation languages (thanks to Borisa Djuraskovic)
* Fixed: small bugfix

= 2013.10.18 - version 0.2.7 =
* Added Persion languages (Thanks to Khalil Delavaran)
* Added Russian languages (Thanks to 192kb)

= 2013.06.13 - version 0.2.6 =
* Tweak: Other method to redirect after logout

= 2013.06.11 - version 0.2.5 =
* Fixed: Show logout link option now working

= 2013.05.28 - version 0.2.4 =
* Added: Norwegian translations (Thanks to Jan-Ivar Mellingen)

= 2013.05.23 - version 0.2.3 =
* Changed: After failed login, redirect to same page instead of normal WP login failed.

= 2013.05.21 - version 0.2.2 =
* Fixed: Bug with redirect home after login

= 2013.05.21 - version 0.2.1 =
* Fixed: Bug with redirect home after login / logout (Thanks to: Sebas de Reus)

= 2013.05.21 - version 0.2 =
* Added: logout link
* Added: basic CSS styling
* Changed: some basic layout settings (as less as possible)

= 2013.04.01 - version 0.1 =
* First release
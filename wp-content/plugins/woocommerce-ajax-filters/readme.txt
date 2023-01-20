=== Advanced AJAX Product Filters ===
Plugin Name: Advanced AJAX Product Filters
Contributors: dholovnia, berocket
Donate link: https://berocket.com/woocommerce-ajax-products-filter/?utm_source=wordpress_org&utm_medium=donate&utm_campaign=ajax_filters
Tags: filters, product filters, ajax product filters, ajax filter, ajax filter widget, color filter, size filter, product onsale filter, product preview, product category filter, product reset filter, product sort by filter, stock filter, product tag filter, price range filter, price box filter, advanced product filters, woocommerce filters, woocommerce product filters, woocommerce products filter, woocommerce ajax product filters, widget, plugin, woocommerce item filters, filters plugin, ajax filters plugin, filter woocommerce products, filter woocommerce products plugin, wc filters, wc filters products, wc products filters, wc ajax products filters, wc product filters, wc advanced product filters, woocommerce layered nav, woocommerce layered navigation, ajax filtered nav, ajax filtered navigation, price filter, ajax price filter, woocommerce product sorting, sidebar filter, sidebar ajax filter, taxonomy filter, category filter, attribute filter, attributes filter, woocommerce product sort, ajax products filter plugin for woocommerce, rocket, berocket, berocket woocommerce ajax products filter
Requires at least: 5.0
Tested up to: 6.1
Stable tag: 1.6.3.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WooCommerce AJAX Product Filters - Advanced product filtering ability for your WooCommerce shop. Add unlimited filters with one widget.

== Description ==

WooCommerce AJAX Product Filters - Advanced product filtering ability for your WooCommerce shop. Add unlimited filters with one widget.

= New Feature in version 1.6.3 =

&#9989; Filter by Post Meta (Custom meta field)

= New Feature in version 1.5 =

&#9989; New styles for filters: Checkbox, Select, Slider, Color, Image
&#9989; New slider styles
&#9989; New buttons styles
&#9989; Selected filters area horizontal styles
&#9989; Single selection for check
&#9989; Select and Select2 can be displayed on same time
&#9989; Collapse widget option with different settings
&#9989; Less JavaScript and HTML code for same result.
&#9989; More compatibility with themes and plugins
&#9989; Better compatibility with Divi Builder, Beaver Builder, Elementor Builder
&#9989; Relevanssi compatibility
&#9989; More ways to customize filters and add additional functionality
&#9989; Option to set how hierarchical attribute must be displayed
&#9989; Separate admin title and frontend title
&#9989; Back button in browser on AJAX
&#9989; All JavaScript in one minified file
&#9989; All CSS Styles in one minified file
&#9989; Checked style for image element style

= Features: =

&#9989; AJAX Filters, Pagination and Sorting!
&#9989; Filter by Price
&#9989; Filter by Product Category
&#9989; Filter by Attribute
&#9989; Unlimited Filters
&#9989; Multiple User Interface Elements
&#9989; Great support for custom/premium themes
&#9989; SEO Friendly Urls ( with HTML5 PushState )
&#9989; Filter Visibility By Product Category And Globals.
&#9989; Accessible through shortcode
&#9989; Filter box height limit with scroll themes
&#9989; Working great with custom widget area
&#9989; Drag and Drop Filter Building
&#9989; Select2 for dropdown menu
&#9989; And More...

= Additional Features in Paid Plugin: =

&#9989; Filter by Custom Taxonomy, Price ranges, Sale status, Sub-categories, Date and Availability( in stock | out of stock | any )
&#9989; Nice URLs for SEO Friendly URLs
&#9989; Slider can use strings as a value
&#9989; Price as checkbox with min and max values
&#9989; Enhancements of the free features
&#9989; Show amount of products before update with "Update button" widget
&#9989; Search box widget
&#9989; Cache for Widgets
&#9989; Display only selected attribute values or hide selected attribute values


= Plugin Links: =
[Paid Plugin](https://berocket.com/woocommerce-ajax-products-filter/?utm_source=wordpress_org&utm_medium=plugin_links&utm_campaign=ajax_filters)
[Demo](https://woocommerce-products-filter.berocket.com/shop?utm_source=wordpress_org&utm_medium=plugin_links&utm_campaign=ajax_filters)
[Docs](https://docs.berocket.com/plugin/woocommerce-ajax-products-filter?utm_source=wordpress_org&utm_medium=plugin_links&utm_campaign=ajax_filters)

= &#127852; Wanna try admin side? =
[Admin Demo](https://berocket.com/woocommerce-ajax-products-filter/?utm_source=wordpress_org&utm_medium=admin_demo&utm_campaign=ajax_filters#try-admin) - Get access to this plugin's admin and try it from inside. Change things and watch how they work.

= Premium plugin video =
[youtube https://youtu.be/PQTXzp9Tpbc]
[youtube https://youtu.be/Ltz82Zs5pl0]
[youtube https://youtu.be/GA3O1F6YVNE]
[youtube https://youtu.be/GPA77L0XBxM]
*we don't have video with free plugin right now but we are working on it*

= Compatibility with WooCommerce plugins =
Advanced AJAX Product Filters has been tested and compatibility is certain with the following WooCommerce plugins that you can add to your site:

&#128312; [**Advanced Product Labels for WooCommerce**](https://wordpress.org/plugins/advanced-product-labels-for-woocommerce/)
&#128312; [**Load More Products for WooCommerce**](https://wordpress.org/plugins/load-more-products-for-woocommerce/)
&#128312; [**Brands for WooCommerce**](https://wordpress.org/plugins/brands-for-woocommerce/)
&#128312; [**Grid/List View for WooCommerce**](https://wordpress.org/plugins/gridlist-view-for-woocommerce/)
&#128312; [**Product Preview for WooCommerce**](https://wordpress.org/plugins/product-preview-for-woocommerce/)
&#128312; [**Products Compare for WooCommerce**](https://wordpress.org/plugins/products-compare-for-woocommerce/)
&#128312; [**Wishlist and Waitlist for WooCommerce**](https://wordpress.org/plugins/wish-wait-list-for-woocommerce/)

= Shortcode: =
* In editor `[br_filters attribute=price type=slider title="Price Filter"]`
* In PHP `do_shortcode('[br_filters attribute=price type=slider title="Price Filter"]');`

= Shortcode Options: =
* `attribute`(required) - product attribute, eg price or length. Don't forget that woocommerce adding pa_ suffix for created attributes.
 So if you create new attribute `jump` its name is `pa_jump`
* `type`(required) - checkbox, radio, slider or select
* `operator` - OR or AND
* `title` - whatever you want to see as title. Can be empty
* `product_cat` - parent category id
* `cat_propagation` - should we propagate this filter to child categories? set 1 to turn this on
* `height` - max filter box height. When height is met scroll will be added
* `scroll_theme` - pretty clear name, scroll theme. Will be used if height is set and real height of box is more


= Advanced Settings (Widget area): =

* Product Category - if you want to pin your filter to category of the product this is good place to do it.
 Eg. You selling Phones and Cases for them. If user choose Category "Phones" filter "Have Wi-Fi" will appear
 but if user will choose "Cases" it will not be there as Admin set that "Have Wi-Fi" filter will be visible only on
 "Phones" category.
* Filter Box Height - if your filter have too much options it is nice to limit height of the filter to not prolong
 the page too much. Scroll will appear.
* Scroll theme - if "Filter Box Height" is set and box length is more than "Filter Box Height" scroll appear and
 how it looks depends on the theme you choose.


= Advanced Settings (Plugin Settings): =
* Plugin settings can be found in admin area, WooCommerce -> Product Filters
* "No Products" message - Text that will be shown if no products found
* "No Products" class - Add class and use it to style "No Products" box
* Products selector - Selector for tag that is holding products
* Sorting control - Take control over WooCommerce's sorting selectbox
* SEO friendly urls - url will be changed when filter is selected/changed
* Turn all filters off - If you want to hide filters without losing current configuration just turn them off



== Installation ==

= Step 1: =
* First you need to add attributes to the products ( WooCommerce plugin should be installed and activated already )
* Go to Admin area -> Products -> Attributes and add attributes your products will have, add them all
* Click attribute's name where type is select and add values to it. Predefine product options
* Go to your products and add attributes to each of them

= Step 2: =
* Install and activate plugin
* First of all go to Admin area -> WooCommerce -> Product Filter and check what global options you can manage
* After that go to Admin area -> Appearance -> Widgets
* In Available Widgets ( left side of the screen ) find AJAX Product Filters
* Drag it to Sidebar you choose
* Enter title, choose attribute that will be used for filtering products, choose filter type,
 choose operator( whether product should have all selected values (AND) or one of them (OR) ),
* Click save and go to your shop to check how it work.
* That's it =)


== Frequently Asked Questions ==

= Is it compatible with all WordPress themes? =
Compatibility with all themes is impossible, because they are too many, but generally if themes are developed according to WordPress and WooCommerce guidelines, BeRocket plugins are compatible with them.

= How can I get support if my WooCommerce plugin is not working? =
If you have problems with our plugins or something is not working as it should, first follow this preliminary steps:

* Test the plugin with a WordPress default theme, to be sure that the error is not caused by the theme you are currently using.
* Deactivate all plugins you are using and check if the problem is still occurring.
* Ensure that your plugin version, your theme version and your WordPress and WooCommerce version (if required) are updated and that the problem you are experiencing has not already been solved in a later plugin update.

If none of the previous listed actions helps you solve the problem, then, submit a ticket in the forum and describe your problem accurately, specify WordPress and WooCommerce versions you are using and any other information that might help us solve your problem as quickly as possible. Thanks!


= How can I get more features for my WooCommerce plugin? =
You can get more features with the premium version of Advanced AJAX Product Filters, available on [BeRocket page](https://berocket.com/woocommerce-ajax-products-filter/?utm_source=wordpress_org&utm_medium=faq&utm_campaign=ajax_filters). Here you can read more about the premium features of the plugin and make it give it its best shot!


= How can I try the full-featured plugin? =
You can try this plugin's admin side [here](https://berocket.com/woocommerce-ajax-products-filter/?utm_source=wordpress_org&utm_medium=faq&utm_campaign=ajax_filters#try-admin). Configure plugin the way you need to check the results.

---

== Screenshots ==
1. General settings
2. JavaScript settings
3. Widget

---

== Changelog ==

= 1.6.3.4 =
* Enhancement - Additional tables generation to not change collation
* Enhancement - Additional tables check is tables exist
* Enhancement - Additional tables clear tables instead remove
* Fix - Barn2 Product table new check

= 1.6.3.3 =
* Enhancement - Compatibility version: Wordpress 6.1 and WooCommerce 7.1
* Fix - Some plugin links to match new BeRocket Site

= 1.6.3.2 =
* Enhancement - Option to fix pagination position after filter page without pagination
* Enhancement - Regenerate additional tables if it was removed
* Enhancement - Compatibility version: WooCommerce 7.0
* Enhancement - Remove some PHP 8.1 notices

= 1.6.3.1 =
* Fix - Post meta not displayed in filter by list

= 1.6.3 =
* Enhancement - Compatibility version: Wordpress 6.0 and WooCommerce 6.7
* Enhancement - POST META FILTERING ADD-ON
* Enhancement - Hierarchical view for taxonomies list
* Fix - Color/Image select with polylang
* Fix - Additional tables generation for some site
* Fix - Currency exchange compatibility
* Fix - Module for Divi theme
* Fix - Style of admin elements

= 1.6.2 =
* Enhancement - Compatibility version: WooCommerce 6.4
* Enhancement - Hierarchical view for color/image pick
* Enhancement - Compatibility for non latin slug for attributes
* Fix - Get collation from other tables

= 1.6.1.5 =
* Fix - XSS Vulnerability

= 1.6.1.4 =
* Enhancement - Compatibility version: WordPress 5.9
* Fix - Empty hook issue
* Fix - Link like WooCommerce with some optimization plugin
* Fix - Not exist attribute cause PHP errors

= 1.6.1.3 =
* Fix - Relevanssi Compatibility

= 1.6.1.2 =
* Enhancement - Compatibility version: WooCommerce 6.1
* Enhancement - Compatibility with WP Search WooCommerce
* Fix - Compatibility with Product Table

= 1.6.1.1 =
* Fix - Compatibility with Product Table plugin
* Fix - URL decoding option with Product Table plugin
* Fix - Link generation for price filters

= 1.6.1 =
* Fix - Compatibility filtering with WPML and Polylang
* Fix - Compatibility with WPML taxonomy translation
* Fix - Price filtering for variable products
* Fix - Attribute values with numeric slug

= 1.6.0.2 =
* Fix - Selected filters options do not work
* Fix - Link like WooCommerce add-on work incorrect in some cases
* Fix - Support query with product variations and other post types

= 1.6.0.1 =
* Fix - Incorrect query when used not default 'wp_' database prefix

= 1.6 =
* Enhancement - Less database queries
* Enhancement - Optimization for database queries. Speed up request to database
* Enhancement - Updated Additional tables for optimized requests and more correct filtering
* Enhancement - Possibility to filter any products request on the page with help of shortcode [brapf_next_shortcode_apply]
* Enhancement - Support for some page builders products elements with shortcode [brapf_next_shortcode_apply]
* Enhancement - Hide reset products button on page load with help of CSS code
* Enhancement - (DEV) New data for filtered page to get more control on selected elements
* Enhancement - Removed Deprecated Filters Add-on
* Fix - Multiple blocks with products in Divi Page Builder, when only single block must be filtered
* Fix - Stock status "On Backorder" work as "In stock" for filtering

= 1.5.5.4 =
* Enhancement - Added notice for deprecated filters add-on
* Enhancement - Prepare for new update

= 1.5.5.4 =
* Enhancement - Compatibility version: WooCommerce 5.7
* Enhancement - Compatibility with Aelia Currency Switcher global exchange rate
* Fix - On some theme plugin use incorrect query for filters

= 1.5.5.3 =
* Enhancement - Option to load products again when some filters not exist after filtering
* Fix - Divi Module include not existed style file
* Fix - Custom CSS style for "Selected value style" do not work for image type
* Fix - Images do not show on selection for image type

= 1.5.5.2 =
* Fix - Divi Builder modules for latest version Divi theme

= 1.5.5.1 =
* Enhancement - Compatibility version: Wordpress 5.8 and WooCommerce 5.5
* Enhancement - Speed up recount functionality for hierarchical taxonomy
* Fix - Advanced section empty

= 1.5.5 =
* Enhancement - Compatibility version: Wordpress 5.8 and WooCommerce 5.5

= 1.5.4.7 =
* Fix - XSS vulnerability
* Fix - Additional tables check attribute for terms count
* Fix - Link like WooCommerce add-on fix
* Fix - Check Widget type and styles on frontend. Display error for admin

= 1.5.4.6 =
* Enhancement - Compatibility version: WooCommerce 5.4
* Enhancement - Price slider with multiple taxonomy page
* Enhancement - Speed up Additional Tables generation
* Enhancement - Use WordPress cron for Additional Tables generation
* Enhancement - Generate more data with single request for Additional Tables generation
* Fix - Get templates when plugin settings open
* Fix - Hide selected filters area on page load

= 1.5.4.5 =
* Enhancement - Remove not needed property from terms cache
* Fix - Filters can be hidden on custom page with WooCommerce shortcodes

= 1.5.4.4 =
* Fix - Price slider with older MySQL

= 1.5.4.3 =
* Enhancement - Compatibility version: WooCommerce 5.3
* Fix - Price slider with decimal product price
* Fix - Filter custom scroll work incorrect with some themes
* Fix - Stock status recount with Additional tables

= 1.5.4.2 =
* Fix - Issue with query for hierarchical taxonomies

= 1.5.4.1 =
* Enhancement - Faster table generation for variable products on product save
* Enhancement - New Checkbox styles
* Fix - WP Rocket compatibility issue

= 1.5.4 =
* Enhancement - Flatsome theme compatibility
* Enhancement - JetWooBuilder compatibility
* Enhancement - Compatibility version: WooCommerce 5.2
* Fix - Relevanssi compatibility with price filters
* Fix - The7 latest version compatibility

= 1.5.3 =
* Fix - SECURITY ISSUE! Sanitize HTML tags for all settings. Custom JavaScript can be changed only by admin(Super admin for multisite).
* Enhancement - New hook to change additional table generation products per call
* Enhancement - Copy filter/group from other do not required 
* Fix - Price filter query issue with some plugins/themes
* Fix - Remove "Limit filter values by products from the selected category" option from filter edit page

= 1.5.2.11 =
* Enhancement - Compatibility version: Wordpress 5.7 and WooCommerce 5.1
* Fix - RTL filters align
* Fix - Disable Auto complete when link changes disabled
* Fix - Image/Color aria-label values.
* Fix - Nested filters display without filtering with Update Products button
* Fix - Price based on country compatibility

= 1.5.2.10 =
* Fix - Error on old WooCommerce
* Fix - Text align for some themes
* Fix - Link like WooCommerce on search page and other page with GET parameters

= 1.5.2.9 =
* Enhancement - Compatibility version: WooCommerce 5.0
* Fix - Issues with some optimization plugins
* Fix - Admin bar error duplicates

= 1.5.2.8 =
* Enhancement - Remove empty header
* Enhancement - Move some options to another tabs
* Enhancement - Add explanation for some options
* Enhancement - More information in admin bar panel
* Fix - Some symbols in URL
* Fix - Other fixes

= 1.5.2.7 =
* Enhancement - Compatibility version: PHP 8 and WooCommerce 4.9
* Fix - Price slider with custom values
* Fix - Custom style for checked elements of image/color styles

= 1.5.2.6 =
* fix - updating sanitization functionality

= 1.5.2.5 =
* HOTFIX - JavaScript error after filtering
* HOTFIX - PHP error in recount for attributes

= 1.5.2.4 =
* Enhancement - Remove PHP session from plugin by default.
* Fix - Incompatibility with Divi Theme and some plugins that uses WP_Query inside products loop
* Fix - Filters/Groups hidden on some devices can work incorrect after filtering
* Fix - "Add more classes" PHP notice

= 1.5.2.3 =
* Fix - Issue with Divi Builder

= 1.5.2.2 =
* Fix - Incompatibility with WordPress 5.6
* Fix - Issue with filters disappearing

= 1.5.2.1 =
* Fix - Issue with pagination
* Fix - Categories not displayed in conditions
* Fix - Error with Elementor Popup

= 1.5.2 =
* Enhancement - Better compatibility with other plugins and themes
* Enhancement - Possibility to translate some text in URL with WPML
* Enhancement - Option to disable panel in admin bar
* Fix - Remove inline JavaScript that cause error with some caching plugins
* Fix - Change variable to disable AJAX pagination. Prevent disabling on some sites
* Fix - Use default rewrite values, when wp_rewrite not exist
* Fix - Add JavaScript variable only once for page
* Fix - Incompatibility with latest version of product table

= 1.5.1.7 =
* Enhancement - Option to filter products by variation price
* Enhancement - Compatibility with premmerce-multicurrency
* Enhancement - Admin bar information for themes, that displayed admin bar in header
* Fix - Database cache related to taxonomies count

= 1.5.1.6 =
* Enhancement - Compatibility with Yoast SEO for canonical option
* Enhancement - Option to use variation price for price slider
* Fix - Hierarchical taxonomy sorting
* Fix - Select2 check duplicates
* Fix - Show child elements if it is selected

= 1.5.1.5 =
* Fix - Issue with some query
* Fix - Some categories can be remove from conditions or displaying
* Fix - Other small issues

= 1.5.1.4 =
* Fix - Image upload issue
* Fix - Multiple filters with "Select" style for same taxonomy

= 1.5.1.3 =
* Enhancement - WP-Rocket compatibility
* Enhancement - FacetWP compatibility
* Fix - Incompatibility with latest jQuery
* Fix - Price slider option "Use custom values"
* Fix - Shop page with categories instead products

= 1.5.1.2 =
* Enhancement - Compatibility with Elementor Pro
* Enhancement - Compatibility with some themes
* Fix - Shortcode [brapf_next_shortcode_apply] do not work without parameters
* Fix - Move option from free to paid(checkbox displayed, but functionality only in paid version)

= 1.5.1.1 =
* Fix - New Divi theme incompatibility

= 1.5.1 =
* Enhancement - Compatibility version: Wordpress 5.5 and WooCommerce 4.4
* Enhancement - [Dev] New Javascript trigger
* Fix - Filters replace with WPML and Polylang
* Fix - Deprecated shortcode do not work without filter ID
* Fix - Some additional hooks and triggers

= 1.5.0.9 =
* Enhancement - New SEO Meta Title style
* Enhancement - [Dev] New option to display attribute values without products after recount
* Enhancement - [Dev] New classes to create filter styles in themes or plugins
* Fix - Issue with jQuery UI styles on other elements in some themes

= 1.5.0.8 =
* Fix - Products search selected filters
* Fix - Deprecated Filters popup close
* Fix - Shortcode PHP error

= 1.5.0.7 =
* Fix - Filters not displayed after disable Deprecated Filters add-on
* Fix - Empty sidebar not displayed
* Fix - Hierarchical sorting prevent error on frontend and save error to database

= 1.5.0.6 =
* Enhancement - Sub-attribute for Nested filters add-on
* Fix - Replacement for user custom CSS
* Fix - Text style with image/color
* Fix - Slash for canonical URL

= 1.5.0.5 =
* Enhancement - Deprecated Filters: Custom Class option for buttons
* Fix - Deprecated Filters: Price Slider
* Fix - PHP Warning for older PHP

= 1.5.0.4 =
* Enhancement - Additional JavaScript hook to override functions to hide/show filters
* Fix - Deprecated Filters: Design styles in incorrect place
* Fix - Autoptimize incompatibility
* Fix - Incompatibility with iubenda – Cookie and Consent Solution
* Fix - Compatibility with other JavaScript/CSS minify plugins

= 1.5.0.3 =
* Fix - Hierarchical style
* Fix - CSS Minify script incompatibility
* Fix - Deprecated Filters: Loading overlay and image
* Fix - Deprecated Filters: Current page detection
* Fix - Deprecated Filters: Design tab

= 1.5.0.2 =
* Fix - Link like WooCommerce add-on
* Fix - Relevanssi script on page where it is not required
* Fix - Max-Height instead Height for filter height limitation

= 1.5.0.1 =
* Enhancement - Change Cursor for some Elements
* Fix - Collapse Icon and Description Icon Size
* Fix - Remove error for deprecated price filter

= 1.5 =
* Enhancement - New styles for filters: Checkbox, Select, Slider, Color, Image
* Enhancement - New slider styles
* Enhancement - New buttons styles
* Enhancement - Selected filters area horizontal styles
* Enhancement - Single selection for check
* Enhancement - Select and Select2 can be displayed on same time
* Enhancement - Collapse widget option with different settings
* Enhancement - Less JavaScript and HTML code for same result.
* Enhancement - More compatibility with themes and plugins
* Enhancement - Better compatibility with Divi Builder, Beaver Builder, Elementor Builder
* Enhancement - Relevanssi compatibility
* Enhancement - More ways to customize filters and add additional functionality
* Enhancement - Option to set how hierarchical attribute must be displayed
* Enhancement - Separate admin title and frontend title
* Enhancement - Back button in browser on AJAX
* Enhancement - All JavaScript in one minified file
* Enhancement - All CSS Styles in one minified file
* Enhancement - Checked style for image element style
* Fix - Incompatibility with some themes
* Fix - Some incompatibility with Windows server
* Fix - Some incompatibility with MariaDB
* Fix - Some issues with Nested Filters
* Fix - A lot of small fixes


= 1.4.2.3 =
* Enhancement - Compatibility version: Wordpress 5.4.1 and WooCommerce 4.1

= 1.4.2.2 =
* Fix - Critical error with older WooCommerce Product Table

= 1.4.2.1 =
* Enhancement - Compatibility WooCommerce Product Table 2.6
* Fix - Fatal error with some plugins that call hooks incorrect

= 1.4.2 =
* Enhancement - Compatibility version: Wordpress 5.4 and WooCommerce 4.0
* Enhancement - Compatibility with Math Rank SEO
* Fix - PHP Notice on some sites

= 1.4.1.9 =
* Fix - Nested filters load
* Fix - Rewrite rules save error
* Fix - Work incorrect post__in variable
* Fix - Media library uses on every admin page

= 1.4.1.8 =
* Enhancement - Remove some queries
* Fix - Hide out of stock variations with price slider do not work
* Fix - Hide out of stock variations work incorrect on some sites

= 1.4.1.7 =
* Fix - Avada 6.2.0 incompatibility. Products not editable

= 1.4.1.6 =
* Fix - PHP Warning/Notice on some site
* Fix - Sorting in WooCommerce shortcodes works incorrect
* Fix - Some styles do not affect option "Display styles only for pages with filters"

= 1.4.1.5 =
* Enhancement - Additional Tables compatibility with Polylang
* Enhancement - Better compatibility with Load More and Grid/List plugins
* Fix - Session start error for some admin pages
* Fix - Nice URL do not work after first save
* Fix - JavaScript errors in WordPress customization
* Fix - Multiple category/attribute values in WooCommerce shortcode
* Fix - Use unique class for color picker script
* Fix - Remove title from HTML5 PushState
* Fix - Group simple create from widget do not work
* Fix - Variable products with out of stock variation still visible if query uses post__in parameter

= 1.4.1.4 =
* Fix - Correct count for attribute values with some WooCommerce Shortcodes
* Fix - Option text not correct
* Fix - Some variable products not excluded with disabled Additional table add-on

= 1.4.1.3 =
* Fix - "Hide variations that are out of stock" option do not work without "Additional table" add-on
* Fix - WPML: "Hide variations that are out of stock" option work only on base language
* Fix - WPML: "Additional table" add-on compatibility

= 1.4.1.2 =
* Fix - Categories work incorrect with WPML and Additional table addon
* Fix - Nice URL do not work with WPML

= 1.4.1.1 =
* Fix - Price replacements do not work
* Fix - Divi theme compatibility replace with another module
* Fix - PHP notices for some sorting

= 1.4.1 =
* Enhancement - New recount options
* Enhancement - Rename some options to be more correct
* Enhancement - Group some options in advanced settings
* Enhancement - Get attribute values with WordPress default functionality
* Enhancement - Better sorting for hierarchical taxonomy (product categories)
* Enhancement - Better sorting with slider by atribute
* Enhancement - Some options work faster
* Fix - Get correct price with recount option
* Fix - Hide some options in filter settings

= 1.4.0.5 =
* Enhancement - Compatibility version: Wordpress 5.3 and WooCommerce 3.8
* Fix - Additional tables: stuck and do not generate tables
* Fix - Additional tables: slow generating of the data for categories
* Fix - Product Category Value Limitation do not work

= 1.4.0.4 =
* Fix - Additional tables generation errors
* Fix - Additional tables incorrect data to hide products
* Fix - Custom filtering permalink with Product Table plugin

= 1.4.0.3 =
* Enhancement - Template optimizations

= 1.4.0.2 =
* Enhancement - Additional table generation withour WP-Cron
* Enhancement - Option to disable AJAX Pagination
* Enhancement - Write table generation errors and status

= 1.4.0.1 =
* Fix - Compatibility with other BeRocket plugins on activation

= 1.4 =
* Enhancement - Additional table for taxonomies (Product categories work faster with it)
* Enhancement - Additional table for variable products
* Enhancement - Better Compatibility with BodyCommerce
* Enhancement - Better Compatibility with WooJetPack product visibility by user role option
* Enhancement - "Hide out of stock variable" option also remove variable products, that do not have variation with selected attributes
* Fix - Generating Additional tables on large amount of data

= 1.3.7 =
* Critical Update! Sanitize all settings. Can break some custom CSS/JavaScript.
* Fix - Show notification about security problem

= 1.3.6.1 =
* Fix - preg_replace warning in the main.php

= 1.3.6 =
* Critical Update! Vulnerability found! Please update the plugin to the version 1.3.6

= 1.3.5 =
* Fix - hot fix for the issue with custom styles

= 1.3.4.2 =
* Fix - WPML incompatibility with new recount script
* Fix - Purge cache after update

= 1.3.4.1 =
* Fix - WordPress database prefix was incorrect for some tables

= 1.3.4 =
* Enhancement - More correct filters recount
* Enhancement - More elemnts for translation
* Fix - Order by element always displayed
* Fix - Rewrite rules override every load
* Fix - Some filters products count

= 1.3.3.2 =
* Fix - Attribute value recount on attribute and taxonomy pages

= 1.3.3.1 =
* Fix - WooCommerce Shortcode with multiple categories
* Fix - Incorrect recount terms on some sites

= 1.3.3 =
* Enhancement - Filtering speed optimization
* Enhancement - Multiple WooCommerce shortcode can work correct
* Enhancement - Database query optimization and less query count

= 1.3.2.8 =
* Enhancement - Price slider speed woocommerce 3.6
* Enhancement - WooCommerce shortcode add no products message
* Fix - PHP notices
* Fix - Remove some files

= 1.3.2.7 =
* Fix - Links to BeRocket
* Fix - Compatibility with other BeRocket plugins
* Fix - Categories default sorting is not working
* Fix - Remove some php notices
* Fix - Reset in above products position break other filters
* Fix - Tax rates option for price

= 1.3.2.6 =
* Fix - Values Order - Default/Numeric, Order Type was not working with multigobyte values
* Fix - PHP notices/errors

= 1.3.2.5 =
* Enhancement - Option to use Standard tax rates for price filter
* Enhancement - Separate Query Vars addon option to set Default operator for URLs
* Enhancement - Separate Query Vars addon lower case operator text in URLs
* Enhancement - Option to set devices where scroll to the top will be used
* Fix - Attribute slider
* Fix - PHP notices/errors

= 1.3.2.4 =
* Fix - Incorrect description
* Fix - Selected filters area Attribute name
* Fix - Selected filters with Filters Conditions
* Fix - Tags cloud

= 1.3.2.3 =
* Fix - Variation limitation with custom database prefix
* Fix - Scrollbar update

= 1.3.2.2 =
* Fix - Notice map_meta_cap for BeRocket plugins

= 1.3.2.1 =
* Fix - Font-Awesome 5 in admin settings
* Fix - Framework version

= 1.3.2 =
* Enhancement - Option to turn off multiple select for images and colors
* Enhancement - Do not close widget if it is selected
* Fix - Internet Explorer issue with colors
* Fix - Attributes WooCommerce sorting in WooCommerce 3.6
* Fix - Remove out of stock variations, when attribute slug in not Latin
* Fix - Variations options with latest version of MySQL
* Fix - Variations with post status trash in query

= 1.3.1.8 =
* Enhancement - Compatibility with Elementor and other plugins
* Enhancement - Compatibility with other BeRocket plugins
* Enhancement - Code Security

= 1.3.1.7 =
* Enhancement - Code Security

= 1.3.1.6 =
* Enhancement - Code Security

= 1.3.1.5 =
* Enhancement - Code Security

= 1.3.1.4 =
* Enhancement - Code Security
* Fix - Categories order Default

= 1.3.1.3 =
* Enhancement - Code Security
* Enhancement - Added Purge cache button

= 1.3.1.2 =
* Fix - Loading Icon
* Fix - Creation filters/groups in customizer
* Fix - Multiple sorting form
* Fix - Better compatibility with Flatsome theme
* Fix - Security problem
* Fix - Code optimization
* Fix - Addon Filtering Conditions

= 1.3.1.1 =
* Fix - Categories order

= 1.3.1 =
* Enhancement - Update BeRocket plugin framework 2.1
* Fix - Hide widgets without values
* Fix - Hide on mobile/tablet/desktop

= 1.3.0.2 =
* Enhancement - Features tab added
* Fix - Font Awesome 5 on some themes
* Fix - Customizer do not save WooCommerce settings
* Fix - Hook to add filtering on some theme

= 1.3.0.1 =
* Enhancement - Category condition in Filters ans Groups
* Fix - Replace widget with limitation by Categories

= 1.3 =
* Enhancement - New admin settings design
* Enhancement - New filters post type and group with filters
* Enhancement - Possibility to filter WooCommerce shortcode
* Enhancement - Display filters on any pages(filters will work only on pages with products)
* Enhancement - Condition to select pages where filters/groups must be displayed
* Enhancement - Option to hide filters on mobile, tablet or desktop
* Enhancement - Filter by Product Tags
* Enhancement - Better work with variations
* Enhancement - Select2 for dropdown menu
* Enhancement - Hierarchical category tree
* Enhancement - Color and image type of filters
* Enhancement - SEO Title, Description and Header with added filters
* Enhancement - Customization for text, checkbox, radio, slider and other elements
* Enhancement - Selected Filters Area
* Enhancement - Description can be added to the filter
* Enhancement - Reset button widget
* Enhancement - Filters can be collapsed by clicking on title, option to collapse filter on start
* Enhancement - Price Filter Custom Min and Max values
* Enhancement - Add custom CSS on admin settings page
* Enhancement - Show icons before/after widget title and/or before/after values
* Enhancement - More functionality and Enhancements
* Fix - A lot of fixes from paid version

= 1.2.8 =
* Fix - Subscribe
* Fix - Feature request send

= 1.2.7 =
* Enhancement - Auto-selectors has "Stop" button
* Enhancement - Feature request box
* Enhancement - Feedback box
* Fix - Reset button

= 1.2.6 =
* Upgrade - better plugin menu items location
* Upgrade - Categories filter
* Upgrade - Reset filters button
* Upgrade - Values order
* Upgrade - Custom CSS
* Upgrade - Option to hide widget on mobile

= 1.2.5 =
* Enhancement - Product categories filtering
* Enhancement - Select2 script for dropdown menu in filters
* Enhancement - Option to use GET request instead POST request(for better compatibility with some caching)
* Enhancement - Setup Wizard
* Enhancement - Auto selectors
* Fix - Displaying categories and subcategories
* Fix - Compatibility with new version of Load More plugin
* Fix - Other fixes

= 1.2.4 =
* Upgrade - WordPress 4.9 compatibility

= 1.2.3 =
* Upgrade - more useful subscribe
* Fix - updater fix

= 1.2.2 =
* Upgrade - Font Awesome
* Upgrade - New admin notices

= 1.2.1 =
* Upgrade - Option to subscribe
* Upgrade - Better advertisement

= 1.2.0 =
* Premium Feature - best support for the themes moved from premium version of the plugin. If you have any issues ith theme update and set Template ajax load fix to jQuery
* Enhancement - No Products message
* Enhancement - No Products class
* Enhancement - Count Results holder
* Enhancement - Pagination holder
* Fix - no errors if terms found
* Fix - remove notices

= 1.1.8.1 =
* Fix - WooCommerce 3.0.1 issues
* Fix - Premium plugin link on settings page.

= 1.1.8 =
* Fix - Better compatibility with WPML

= 1.1.7 =
* Fix - Remove notices on PHP 7 and newer
* Fix - Fix fo Currency Exchange plugin
* Fix - Styles for admin panel
* Fix - Remove sliders from all filters

= 1.1.7 =
* Fix - Remove notices on PHP 7 and newer
* Fix - Fix fo Currency Exchange plugin
* Fix - Styles for admin panel
* Fix - Remove sliders from all filters

= 1.1.6 =
* Fix - Price for currency exchange
* Fix - Optimization for price widget
* Fix - Custom JavaScript errors

= 1.1.5 =
* Fix - Shortcode doesn't work
* Fix - Optimization for price filters
* Fix - Filters work incorrect on search page
* Fix - Some strings is not translated with WPML
* Fix - Optimization for hiding attribute values without products

= 1.1.4 =
* Enhancement - Russian translation
* Fix - Translation
* Fix - Network activation
* Fix - Displaying of filter with price
* Fix - Get normal min/max prices for filter with price
* Fix - Widgets displays incorrect with some themes
* Fix - Not filtering with some plugins
* Fix - Scrollbar displays incorrect with some themes

= 1.1.3 =
* Enhancement - load only products from last AJAX request
* Enhancement - Uses HTML for widgets from theme
* Enhancement/Fix - Attributes page support
* Fix - Hash links didn't works with plugin
* Fix - Widgets don't display on page with latest version of WooCommerce
* Fix - Remove PHP errors

= 1.1.0.7 =
* Enhancement - Option to hide selected values and/or without products. Add at the bottom button to show them
* Enhancement - Filters are using product variations now
* Enhancement - translation( WPML ) support
* Enhancement/Fix - radio-box had issues and there was no chance to remove selection
* Fix - Pagination has issues with link building
* Fix - Jump to first page wasn't working correctly and jump each time even when user want to change page

= 1.1.0.6 =
* Enhancement - Scroll to the top
* Enhancement/Fix - Hash for old browsers added for better support
* Enhancement/Fix - Sort by default WooCommerce value
* Fix - out-of-stock filter working correctly

= 1.1.0.5 =
* Enhancement - Option to add text before and after price input fields
* Enhancement - Jump to first page when filter changed
* Fix - Now only used values must be shown, not all
* Fix - Products are limited by category we are in
* Fix - Products amount on the first page is correct now

= 1.1.0.4 =
* Minor fix

= 1.1.0.3 =
* Enhancement - Custom CSS class can be added per widget/filter
* Enhancement - Update button. If added products will be updated only when user click Update button
* Enhancement - Radio-box can be unselected by clicking it again
* Enhancement/Fix - Urls are shortened using better structure to save filters. `~` symbol is not used now
* Fix - issue with shortened tags for shortcode.
* Fix - on widgets page widget now has subcategories(hierarchy)
* Fix - all categories are visible, not only that have products inside(popular)
* Minor fixes

= 1.1.0.2 =
* Fix - another js issue that stops plugin from work
* Fix - order by name, name_numeric and attribute ID wasn't working

= 1.1.0.1 =
* Fix - js issue that stops plugin from work

= 1.1.0 =
* Enhancement - Show all values - on plugin settings page you can enable option to show all values no matter if they are used or not
* Enhancement - Values order - you can set values order when editing attribute. You can set how to order (by id, name or custom). If
you set to order `by custom` you can drag&amp;drop values up and down and set your own order.
* Small fixes

= 1.0.4.5 =
* Enhancement - values order added. Now order of values can be controlled through attribute options
* Enhancement/Fix - Better support for for category pages
* Other small fixes

= 1.0.4.4 =
* Enhancement - adding callback for before_update, on_update, after_update events.
* Other small fixes

= 1.0.4.3 =
* Enhancement - shortcode added
* Critical/Fix - If slider match none its values wasn't counted
* Enhancement/Fix - Changing attribute data location from url to action-element, providing more flexibility for template
* Enhancement/Templating - Using full products loop instead of including product content template
* Fix - Pagination with SEO url issue

= 1.0.4.2 =
* Enhancement/Fix - Better support for SEO urls with permalinks on/off
* Fix - Critical bug that was returning incorrect products.

= 1.0.4.1 =
* Enhancement - Adding AJAX for pagination.
* Enhancement - Adding PushState for pagination.
* Enhancement/Fix - Pagination wasn't updating when filters used.
* Enhancement/Fix - Text with amount of results (Eg "Showing all 2 results") wasn't updating after filters applied
* Enhancement/Fix - When choosing Slider in admin Operator became hidden
* Fix - All sliders except price wasn't working with SEO url
* Fix - When changing attribute to/from price in admin all filters jumping
* Fix - After filter applied all products was showed. Even those with Draft status.

= 1.0.4 =
* Enhancement - SEO friendly urls with possibility for users to share/bookmark their search. Will be shortened in future
* Enhancement - Option added to turn SEO friendly urls on/off. Off by default as this is first version of this feature
* Enhancement - Option to turn filters on/off globally
* Enhancement - Option to take control over (default) sorting function, make it AJAXy and work with filters
* Fix - Sorting remain correct after using filters. Sorting wasn't counted before
* Fix - If there are 2 or more sliders they are not working correctly.
* Fix - Values in slider was converted to float even when value ia not a price.
* Fix - If there are 2 or more values for attribute it was not validated when used in slider.

= 1.0.3.6 =
* Fix - Removed actions that provide warning messages
* Enhancement - Actions and filters inside plugin

= 1.0.3.3 =
* Enhancement/Fix - Showing products and options now depending on woocommerce_hide_out_of_stock_items option
* Enhancement/Fix - If not enough data available( quantity of options < 2 ) filters will not be shown.
* Fix - If in category, only products/options from this category will be shown

= 1.0.3.2 =
* Fix - wrong path was committed in previous version that killed plugin

= 1.0.3 =
* Enhancement - CSS and JavaScript files minimized
* Enhancement - Settings page added
* Enhancement - "No Products" message and it's class can be changed through admin
* Enhancement - Option added that can enable control over sorting( if visible )
* Enhancement - User can select several categories instead of one. Now you don't need to create several same filters
  for different categories.
* Enhancement - Added option "include subcats?". if selected filter will be shown in selected categories and their
  subcategories
* Fix - Adding support to themes that require product div to have "product" class
* Fix - Slider in categories wasn't initialized
* Fix - Subcategories wasn't working. Only Main categories were showing filters
* Templating - return woocommerce/theme default structure for product
* Templating - html parts moved to separate files in templates folder. You can overwrite them by creating folder
  "woocommerce-filters" and file with same name as in plugin templates folder.

= 1.0.2 =
* Fix - better support for older PHP versions

= 1.0.1 =
* First public version

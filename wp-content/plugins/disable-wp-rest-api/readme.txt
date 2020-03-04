=== Disable WP REST API ===

Plugin Name: Disable WP REST API
Plugin URI: https://perishablepress.com/disable-wp-rest-api/
Description: Disables the WP REST API for visitors not logged into WordPress.
Tags: rest, rest-api, api, json, disable, head, header, link, http
Author: Jeff Starr
Author URI: https://plugin-planet.com/
Donate link: https://monzillamedia.com/donate.html
Contributors: specialk
Requires at least: 4.4
Tested up to: 5.3
Stable tag: 1.7
Version: 1.7
Requires PHP: 5.6.20
Text Domain: disable-wp-rest-api
Domain Path: /languages
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Disables the WP REST API for visitors not logged into WordPress.



== Description ==

This plugin does one thing: disables the WP REST API for visitors who are not logged into WordPress. No configuration required.

This plugin works with only 22 short lines of code (less than 2KB). So it is _super lightweight, fast, and effective_.


**Features**

* Disable REST/JSON for visitors (not logged in)
* Disables REST header in HTTP response for all users
* Disables REST links in HTML head for all users
* 100% plug-and-play, set-it-and-forget solution

_The fast, simple way to prevent abuse of your site's REST/JSON API_

How does it work? That depends on which version of WordPress you are using..


**WordPress v4.7 and beyond**

For WordPress 4.7 and better, this plugin completely disables the WP REST API _unless_ the user is logged into WordPress. 

* For logged-in users, WP REST API works normally
* For logged-out users, WP REST API is disabled

What happens if logged-out visitor makes a JSON/REST request? They will get only a simple message:

"rest_login_required: REST API restricted to authenticated users."

This message may customized via the filter hook, `disable_wp_rest_api_error`. Check out [this post](https://wordpress.org/support/topic/not-entirely-for-non-techies/#post-12014965) for an example of how to do it.


**Older versions of WordPress**

For WordPress versions less than 4.7, this plugin simply disables all REST API functionality for all users.

More information available below in the FAQs section.


**Privacy**

This plugin does not collect or store any user data. It does not set any cookies, and it does not connect to any third-party locations. Thus, this plugin does not affect user privacy in any way.

> Works perfectly with or without Gutenberg Block Editor



== Screenshots ==

1. What non-logged visitors see when requesting JSON/REST API
2. What logged-in users see when requesting JSON/REST API



== Installation ==

**How to Install**

1. Upload the plugin to your blog and activate
2. Done! No further configuration is required.

[More info on installing WP plugins](https://codex.wordpress.org/Managing_Plugins#Installing_Plugins)


**Testing**

To test that the plugin is working, log out of WordPress and then request `https://example.com/wp-json/` in a browser. See FAQs for more infos.


**Like the plugin?**

If you like Disable WP REST API, please take a moment to [give a 5-star rating](https://wordpress.org/support/plugin/disable-wp-rest-api/reviews/?rate=5#new-post). It helps to keep development and support going strong. Thank you!



== Upgrade Notice ==

To upgrade this plugin, remove the old version and replace with the new version. Or just click "Update" from the Plugins screen and let WordPress do it for you automatically.

Note: this plugin does not add anything to your WP database.



== Frequently Asked Questions ==

**Why would anyone want to disable the REST API?**

Technically this plugin only disables REST API for visitors who are not logged into WordPress. With that in mind, here are some good reasons why someone would want to disable REST API for non-logged users:

* The REST API may not be needed for non-logged users
* Disabling the REST API conserves server resources
* Disabling the REST API minimizes potential attack vectors
* Disabling the REST API prevents content scraping and plagiarism

I'm sure there are [other valid reasons](https://digwp.com/2018/08/secure-wp-rest-api/), but you get the idea :)


**There already is another "Disable REST" plugin?**

Yep, actually there are two other "Disable REST" plugins:

* [Disable REST API](https://wordpress.org/plugins/disable-json-api/)
* [Disable REST API](https://wordpress.org/plugins/disable-rest-api/)

The first of those plugins is awesome and provides a LOT more features and functionality than is required to simply disable REST. And the second plugin was shut down due to lack of use. I wrote my disable-REST plugin because I wanted something super lightweight, fast, and effective. If you are looking for more options and features, then check out the first of those two listed alternatives.


**How do I test that REST is disabled?**

Testing is easy:

1. Log out of WordPress
2. Using a browser, request `https://example.com/wp-json/`

If you see the following message, REST is disabled:

"rest_login_required: REST API restricted to authenticated users."

Then if you log back in and make a new request for `https://example.com/wp-json/`, you will see that REST is working normally.


**Does this plugin disable REST functionality added by other plugins?**

Yes, if the REST endpoints are registered with the WP REST API.



== Changelog ==

If you like Disable WP REST API, please take a moment to [give a 5-star rating](https://wordpress.org/support/plugin/disable-wp-rest-api/reviews/?rate=5#new-post). It helps to keep development and support going strong. Thank you!


= 1.7 =

* Tests on WordPress 5.3

= 1.6 =

* Updates some links to https
* Tests on WordPress 5.3 (alpha)

= 1.5 =

* Bumps [minimum PHP version](https://codex.wordpress.org/Template:Server_requirements) to 5.6.20
* Tests on WordPress 5.2

= 1.4 =

* Tests on WordPress 5.1 and 5.2 (alpha)

= 1.3 =

* Tests on WordPress 5.1

= 1.2 =

* Adds homepage link to Plugins screen
* Updates default translation template
* Tests on WordPress 5.0

= 1.1 =

* Updates GDPR blurb and donate link
* Adds "rate plugin" link to Plugins screen
* Adds icons for the WordPress Plugin Directory
* Generates default translation template
* Further tests on WP versions 4.9 and 5.0 (alpha)

= 1.0 =

* Initial release

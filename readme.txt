=== Social Impact Widget ===
Contributors: ppfeufer
Donate link:
Tags: Socialmedia,  Facebook, Google+, Googleplus, RSS, Feedburner, Twitter
Requires at least: 3.2
Tested up to: 3.8-alpha
Stable tag: 1.6.2

Displaying your social impact.

== Description ==

Add a sidebarwidget to display the count of your twitterfollowers, your googleplus circles, fans of your facebook-fanpage, and your feedreaders (from feedburner) in your sidebar.

**Features**

* Easy install
* Panel for easy configuration
* No external scripts
* Cleaning up database on uninstall

**Languages**

* German
* English

== Installation ==

You can use the built in installer or you can install the plugin manually.

**Installation via Wordpress**

1. Go to the menu 'Plugins' -> 'Install' and search for 'Social Impact Widget'
1. Click 'install'

**Manual Installation**

1. Upload folder `social-impact-widget` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Configure plugin in your widget-settings

== Screenshots ==
1. Widget Settings
2. Sidebarwidget in frontend

== Changelog ==

= 1.6.2 =
* (02. November 2012)
* Tested up to WordPress 3.8-alpha

= 1.6.1 =
* (06. 08. 2013)
* Fix: Formatting twitter followers.

= 1.6 =
* (03. 08. 2013)
* Changing the Twitter function to get the follower without oAuth thanks to ([Guido Mühlwitz](http://www.guido-muehlwitz.de/))

= 1.5.1 =
* (05. 04. 2013)
* Changing the query for Google+

= 1.5 =
* (15. 03. 2013)
* Added App.net to the list of networks.
* Completely removed Feedburner from codebase.

= 1.4 =
* (29. 10. 2012)
* Disabled Feedburner. Google disabled the API.

= 1.3 =
* (16. 10. 2012)
* Switched to the new Tiwtter-API.

= 1.2 =
* (21. 09. 2012)
* Fixed errormessage if a service is not used.

= 1.1 =
* (23. 08. 2012)
* Google changed the response from the API. Now the plugin gets the circles correct again.

= 1.0.2 =
* (09. 08. 2012)
* Added failover, if an API not responses correctly.

= 1.0.1 =
* (06. 08. 2012)
* Fix: Facebook API call (sorry)
* Secured all API calls

= 1.0 =
* (30. 07. 2012)
* Designfix: changed the CSS to prevent that themes can embed background images to the plugin.
* Code cleanup
* Changed the facebook-API

= 0.6.2 =
* (18. 05. 2012)
* Fixed the regex to get the G+ circles.

= 0.6.1 =
* (05. 05. 2012)
* Designfix: changed the CSS to prevent that the backgroundimage can be overwritten by other CSS-rules.

= 0.6 =
* (28. 03. 2012)
* Ready for WordPress 3.4

= 0.5.1 =
* (03. 03. 2012)
* Removed: rel="author" from google+ link to prevent pushing wrong author-information to google on sites with more than one author.

= 0.5 =
* (22. 02. 2012)
* Changed cachingtime to 1800 seconds
* Escaping outgoing profile links.

= 0.4.3 =
* (12. 02. 2012)
* CSS for IE - again -.-

= 0.4.2 =
* (12. 02. 2012)
* removed get_plugin_data, some systems couldn't find this function ....

= 0.4.1 =
* (11. 02. 2012)
* No longer minifying the CSS. The IE can't work with it -.-

= 0.4 =
* (11. 02. 2012)
* Checking if the returned count values are not empty to prevent killing the values in database.
* Adding CSS-Hacks for fu***ng IE.

= 0.3 =
* (10. 02. 2012)
* removed the die-function. Why no one hailed me for this ? :-)

= 0.2 =
* (09. 02. 2012)
* Added a clear cache checkbox to the widget settings
* added a check if we can use file_get_contents or cURL
* Title is now really empty if not set :-)

= 0.1 =
* (08. 02. 2012)
* Initial Release

== Frequently Asked Questions ==

Please read the FAQ under http://blog.ppfeufer.de/wordpress-plugin-social-impact-widget/

== Upgrade Notice ==

Just upgrade via Wordpress.

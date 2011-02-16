=== PiwigoMedia ===
Contributors: johnnyfive
Donate link: http://joaoubaldo.com
Tags: gallery, piwigo, integration, media
Requires at least: 3.0.4
Tested up to: 3.0.4
Stable tag: 0.8

This plugin links images from a Piwigo gallery in WordPress posts.

== Description ==

PiwigoMedia is a WordPress plugin that allows easy insertion (linking) of images found in a Piwigo gallery into WordPress posts, using the TinyMCE editor. 
Interactions between PiwigoMedia and Piwigo are accomplished using Piwigo's own web service.


== Installation ==

1. Unpack PiwigoMedia's zip file inside `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure PiwigoMedia. Go to 'Settings/PiwigoMedia' page in WordPress.
4. Set the parameter "Piwigo webservice URL" to point to Piwigo's ws.php 
script.
5. Save changes.


== Frequently Asked Questions ==

= How do I add images from Piwigo to a post? =

1. Go to WordPress Post editor.
2. Press the PiwigoMedia's button, available in Visual editing mode.
3. Navigate to the desired Category.
4. Make your image selection.
5. Insert selection into post.

= What version of Piwigo is supported? =

As of now, the latest stable version is 2.1.6 and thats the only tested version.
It is very likely that future versions of Piwigo will be supported as long as 
its webservice data struture doesn't change.

== Screenshots ==

1. PiwigoMedia's main window

== Changelog ==
= 0.8 =
* update: UI updated (a little more compact)
* update: code cleanup
* new: initial error handling.
* new: Piwigo Webservice URL setting has been split into two fields: Piwigo URL + Web service script

= 0.7 =
* Better navigation, using breadcrumb for Categories and pages numbers for Images
* Small improvements to javascript
* Little cleaner code
* New name (PiwigoMedia) and new icon for TinyMCE UI

= 0.6 =
* Reasonable stable version. This is the first import into WordPress SVN.

== Upgrade Notice ==


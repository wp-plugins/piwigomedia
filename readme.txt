=== PiwigoMedia ===
Contributors: johnnyfive
Donate link: http://joaoubaldo.com
Tags: gallery, piwigo, integration, media
Requires at least: 3.0.1
Tested up to: 3.0.5
Stable tag: 0.9

This plugins allows media from a Piwigo site to be inserted into WordPress posts.

== Description ==

PiwigoMedia is a WordPress plugin that allows easy insertion and linking of images hosted in a Piwigo site, into WordPress posts, using the TinyMCE editor.
The main advantages of PiwigoMedia are:
* Simplicity. Simple to use and configure
* No duplicated media. Images are linked from Piwigo's site, nothing is imported into WordPress
* Independency. WordPress and Piwigo don't have to be installed on the same server since PiwigoMedia uses Piwigo's webapi


== Installation ==

1. Unpack PiwigoMedia's zip file inside `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure PiwigoMedia. Go to 'Settings/PiwigoMedia' page in WordPress administration.
4. Set the parameter "Piwigo URL" to point to your Piwigo site.
5. Save changes.


== Frequently Asked Questions ==

= How do I add images to a post? =

1. Go to WordPress Post editor.
2. Press the PiwigoMedia's button, available in Visual editing mode.
3. Navigate to the desired Category.
4. Make your image selection.
5. Insert image selection into the post.

= Which versions of Piwigo are supported? =

As of now, the latest stable version is 2.1.6 and thats the only tested version.
It is very likely that future versions of Piwigo will be supported as long as 
its webapi data struture doesn't change.

== Screenshots ==

1. PiwigoMedia's main window

== Changelog ==
= 0.9 =
* new: i18n support added.
* new: POT template for translators.
* update: reorganization of tinymce/.
* update: unused files cleaned.
* update: user capabilities checks added to popup.

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


# Layers Pro Changelog

=======
##1.6.8
### 04 October 2016

* **Fix** - Fixed always-on header parallax bug. *MP*
* **Enhancement** - Added a Smooth Scroll toggle. *MP*
* **Enhancement** - Added support for self-hosted videos via the featured media panel in widgets. *MP*
* **Enhancement** - Added classes to allow for hiding of content on different screensizes. *DP*
* **Enhancement** - Removed black bars for third-party background videos. *MP*

=======
##1.6.7
### 13 September 2016

* **Fix** - Fixed social media buttons in menus. *MP*
* **Fix** - Fix repeater titles (Accordion, Tabs, etc) so they dynamically display and dynamically update. *SOB*
* **Enhancement** - Removed JS which moves the CTA in the Post Carousel widget to the bottom of the page. *MP*

=======
##1.6.6
### 16 August 2016

* **Enhancement** - Added support for the Layers Showcase widgets and customizer settings. *SOB*

=======
##1.6.5
### 15 July 2016

* **Fix** - Prevent erroneous display of the Menu Social Icons in the Customizer in old versions of WP *SOB*
* **Tweak** - Change away from registering the slider script and css, to enqueue the scripts pre-registered by Layers *SOB*

=======
##1.6.4
### 22 June 2016

* **Enhancement** - Added selective refresh for widgets. *SOB*
* **Fix** - Fixed social network buttons in `Appearance > Menus`. *DP*

=======
##1.6.3
### 10 April 2016

* **Fix** - Fixed php error notice for themes that do not support parallax classes. *MP*
* **Fix** - Fixed social icons breaking in the Appearance > Menu section. *DP*

=======
##1.6.2
### 29 April 2016

* **Fix** - Fixed php error notice on certain configurations. *SOB*

=======
##1.6
### 22 April 2016

* **Fix** - Fixed widget HTML to cope with Grid 3.0. *DP*
* **Fix** - Widget title correction - They originally all had "Content" in the widget title. *DP*
* **Fix** - Fixed the full-width option in the CTA widget. *DP*
* **Fix** - Fixed text coloring for the Tab and Accordian Widgets. *MP*
* **Fix** - Fixed Text Transform option in the Social Icons widget. *MP*
* **Enhancement** - Added support for the new Custom Logo function in WordPress 4.5. *MP*
* **Enhancement** - Added the parallax option to standard and Pro Layers Widgets. *MP*

=======
##1.5
### 25 February 2016

* **Fix** - Fixed the Vine social icon label. *MP*
* **Enhancement** - Added WPTouch Compatability. *MP*
* **Enhancement** - Added FOUC fix to widgets. *MP*
* **Enhancement** - Added new linking interface to the CTA widget. *MP*

=======
##1.4
### 04 February 2016

* **Fix** - Colors not applying on the front end. *MP*

=======
##1.3
### February 2016

* **Fix** - Undefined background warning when adding the tab widget now fixed
* **Fix** - Undefined background warning when adding the accordian widget now fixed
* **Fix** - Added Mobile plugin and Social Commerce compatability. *MP*
* **Fix** - Brought back the Site Accent color which was missing in the last update. *MP*
* **Tweak** - Removed the onboarding process which referenced ColorKit. *MP*
* **Tweak** - Widget interface clean up, removed 'push-top-small' and 'group' classes from design bar menu drop downs. *DP*
* **Enhancement** - Added to Social Networks: Last.fm, Spotify, Apple iTunes. *SOB*

=======
##1.2
### 10 Dec 2015

* **Fix** - Tab widget now applies .invert class when needed. *MP*
* **Tweak** - Formatted search overlay css and tweaked animations. *DP*
* **Enhancement** - Added search icon functionality to the site header. *SOB*
* **Enhancement** - Social Network Widget default copy. *DP*
* **Enhancement** - Added menu hover customization. *MP*
* **Enhancement** - Added the ability to apply video backgrounds to slides `Requires Layers 1.2.10`. *MP*

=======
##1.1
### 19 Nov 2015

* **Fix** - Accordion widget was throwing a `wp_kses()` warning. *MP*
* **Fix** - Fixed the Accordion widget default sizes. *MP*
* **Fix** - Accordion dummy content has been changed to generic copy. *DP*
* **Fix** - Call to Action buttons now align correctly depending on text-align settings. *DP*
* **Tweak** - Removed .push-top and .push-bottom from the Accordion widget container. *MP*
* **Tweak** - All widgets have received better, more contextual default copy. *MP*
* **Tweak** - All widgets received `layers_the_content()` wrapper functions around their excerpts. *MP*
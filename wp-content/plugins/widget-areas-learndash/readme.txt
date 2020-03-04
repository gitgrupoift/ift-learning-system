=== Widget Areas for LearnDash ===
Contributors: escapecreative, davewarfel
Donate link: https://www.paypal.me/escapecreative/10
Tags: learndash, lms, learning management system, online courses, widgets, sidebars
Requires at least: 4.6
Tested up to: 5.3.1
Requires PHP: 5.6
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Adds multiple widget areas throughout the LearnDash interface. This plugin enables you to add unlimited widgets to several areas of Focus Mode & LearnDash course pages.

== Description ==

Widget Areas for LearnDash simply gives you more places to add custom content in LearnDash. By utilizing built-in LearnDash action hooks, we assign widget areas (also known as "sidebars") to various locations throughout your LearnDash pages.

https://www.youtube.com/watch?v=WoR177xks_k

### Available LearnDash Widget Areas

* Focus Mode: Sidebar: Above Navigation
* Focus Mode: Sidebar: Below Navigation
* Focus Mode: Content: Start
* Focus Mode: Below Content
* Course Page: Content: Start
* Course Page: Content: End

Requires the "LearnDash 3.0" Active Template. Tested with LearnDash 3.1.1.1.

### How to Use

Upon activating the plugin, 6 new widget areas will appear on the **Appearance > Widgets** screen in your WordPress admin area. You can also access them via the Customizer.

Simply add the widget(s) you'd like to insert into the corresponding widget areas. Be sure to click the "Save" button at the bottom.

Navigate to a page that contains that widget area, refresh, and your new widgets will be there.

### Types of Content to Add

WordPress comes with a set of default widgets, several of which might be useful.

* Add a **Text** widget with some basic content, a list, a few links, etc.
* Add an **Image** or **Video** widget for more visual content
* Add a **Navigation Menu** widget to insert a custom menu you've created
* Add your own **Custom HTML** widget to embed an `<iframe>` or write your own code

**TIP:** If you're using a **Text** or **Custom HTML** widget, you can use LearnDash's `[student]` & `[visitor]` shortcodes to display a widget's contents only to enrolled students or unenrolled visitors.

#### LearnDash Widgets

LearnDash comes prepackaged with a few widgets, but there's probably only one that makes sense in these widget areas.

* Add a **Course Progress Bar** to the top or bottom of your Focus Mode sidebar

Feel free to experiment with the other [LearnDash widgets](https://www.learndash.com/support/docs/core/widgets/).

#### Elementor Templates

https://www.youtube.com/watch?v=5x3Uzcs2Oz4

If you're using Elementor Pro, you can create a custom section and embed it anywhere on your site, including in your new LearnDash widget areas. There are two ways to do this:

1. When you go to insert a widget, select the **Elementor Library** widget. Choose a template from the dropdown menu. Click "Save."

2. Navigate to **Templates > Saved Templates**. Copy the shortcode next to the template you want to insert. Now you'll insert a **Text** widget into the widget area, and paste the shortcode.

For more information, see the article in [Elementor's knowledge base](https://docs.elementor.com/article/97-embed-templates).

### Widget Area CSS Class Names

If you need to target the widget areas to apply custom styles using CSS, you can use the following class names.

All widget areas have the `.ldx-widget-area` class.

* Focus Mode: Sidebar: Above Navigation - `.ldx-widget-area.fm-nav-before`
* Focus Mode: Sidebar: After Navigation - `.ldx-widget-area.fm-nav-after`
* Focus Mode: Content: Start - `.ldx-widget-area.fm-content-start`
* Focus Mode: Below Content - `.ldx-widget-area.fm-content-bottom`
* Course Page: Content: Start - `.ldx-widget-area.course-content-start`
* Course Page: Content: End - `.ldx-widget-area.course-content-end`

Example Usage:

`.ldx-widget-area.fm-nav-before {
	margin: 1em;
}`

This would add 1em of spacing around the widget area that appears above the navigation in the Focus Mode sidebar.

In addition, all widgets placed inside of a widget area have a class of `.ldx-widget`.

### Show/Hide Widgets on Certain Devices

https://www.youtube.com/watch?v=X-5I_JqB3NU

While not a direct feature of this plugin, you can show/hide widgets on different devices using another free plugin.

1. Install & activate the [Widget Options](https://wordpress.org/plugins/widget-options/) plugin
2. Navigate to the widget that you'd like to adjust the visibility on
3. Scroll to the bottom and click on the tab with the mobile phone
4. Choose to either show or hide on the checked devices
5. Check the appropriate devices
6. Click "Save"

### Show/Hide Widgets on Specific Course/Lesson/Topic Pages

Another feature of the free Widget Options plugin (mentioned above) is to only show widgets on specific course, lesson, topic or quiz pages.

1. Install & activate the [Widget Options](https://wordpress.org/plugins/widget-options/) plugin
2. Navigate to the widget that you'd like to adjust the visibility on
3. Scroll to the bottom and click on the settings cog icon
4. Click on the **Logic** tab
5. In the text box, you can use the `is_single()` conditional tag to only display that widget on a specific page
6. Use the ID of the course, lesson, topic or quiz

[👉 How to find the ID of LearnDash content](https://ldx.design/find-learndash-course-id/)

Example:

This would only display the widget on the page with an ID of `7`:

`is_single( '7' )`

If you wanted to display a widget on multiple pages (with IDs of 1, 2 and 3), your code would look like this:

`is_single( array( 1, 2, 3 ) )`

There are many more [WordPress conditional tags](https://codex.wordpress.org/Conditional_Tags) you can use.

== Installation ==

=== From within WordPress ===

1. Visit "Plugins > Add New"
1. Search for "Widget Areas for LearnDash"
1. Click the "Install" button
1. Click the "Activate" button
1. Navigate to "Appearance > Widgets" or open the Customizer to start adding widgets to your new widget areas

== Frequently Asked Questions ==

= How do I add widgets? =

There are two ways you can add widgets to the new widget areas that this plugin creates.

1. Navigate to "Appearance > Customize" and click on "Widgets"
1. Make sure you're previewing a page that contains the widget areas
1. Click on the widget area in which you'd like to add widgets
1. Click the "Add Widget" button
1. Select the widget you want to add
1. Customize the widget to your liking

-or-

1. Navigate to "Appearance > Widgets"
1. Locate the widget area you want to add widgets to
1. Find the widget you want to add
1. Click, hold & drag the widget inside the widget area
1. Customize the widget to your liking

= Will this work with my theme? =

Yes. Widget Areas for LearnDash should work with almost all themes. We use standard LearnDash action hooks, so as long as your theme is not modifying this LearnDash code (they shouldn't be), then it should work.

You might experience some spacing irregularities, depending on how your theme styles its default widgets. Please reach out in the [support forum](https://wordpress.org/support/plugin/widget-areas-learndash/) so we can help you adjust spacing.

**Incompatible Themes:**

* BuddyBoss (**LD Course: Content: End** works, but the other widget areas do not)
* eLumine (The Focus Mode widget areas work, but not the ones on course pages)

= The Focus Mode sidebar widgets are touching the edge. How do I fix this? =

If you're already using the [Design Upgrade for LearnDash](https://wordpress.org/plugins/design-upgrade-learndash/) plugin (free or pro), the spacing is automatically added for you. Not only does it add spacing, but it upgrades many other aspects of the LearnDash design.

If you'd like to write your own CSS, add the following code to the `style.css` file of your child theme, or the "Additional CSS" area in the Customizer.

`.ldx-widget-area.fm-nav-before,
.ldx-widget-area.fm-nav-after {
	margin: 1em;
}`

This will add `1em` of margin around the widget areas in the Focus Mode sidebar. Feel free to adjust the value to achieve the spacing you want.

= Can I add a background color to a widget area? =

Sure thing. Add the following code to the `style.css` file of your child theme, or the "Additional CSS" area in the Customizer. You'll need to adjust it for the specific widget area you'd like to target (see class names in description above).

`.ldx-widget-area.fm-content-start {
	padding: 0.75em;
	border-radius: 5px;
	background: #000;
	color: #fff;
}`

This would change the **Focus Mode: Content: Start** widget area to have a black background and white text. The padding is there so the text doesn't run up against the edge of the box. Feel free to adjust the `border-radius` to match your site's style, or just delete it.

= The widget area I want isn't listed. =

That's probably because we haven't added it. There are 50+ different areas we could've added widgets to, but that would clutter your WordPress admin area, and we didn't want to do that to you.

We carefully selected the areas we thought course creators would want most. However, it's possible we got this wrong. If there's enough demand for a particular widget area, and LearnDash allows us to target it, we'll consider adding it.

== Screenshots ==

1. New LearnDash widget areas shown on the **Appearance > Widgets** screen
2. Visual location of the Focus Mode sidebar before & after navigation widget areas (highlighted in yellow)
3. Visual location of the Focus Mode content start & below content widget areas (highlighted in yellow)
4. Visual location of the course content start & course content end widget areas (highlighted in yellow)
5. Use LearnDash's `[student]` & `[visitor]` shortcodes to display widget content to enrolled or unenrolled users

== Changelog ==

= 1.0 - December 18, 2019 =

- Initial Release
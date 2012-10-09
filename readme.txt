=== Slider ===
Contributors: Mesut Ozkal 
Tags: Image Slider, Jquery Slider, Photo display, Photo Slider, image, slideshow, photo, plugin, wordpress, gallery, slide, jquery
Requires at least: 2.0
Tested up to: 3.4
Stable tag: 4.3


Slider Wordpress Plugin adds a powerful and customisable slider to your wordpress website.

== Description ==

WP Top Slider is a powerful and customisable jquery cycle plugin.

WP Top Slider uses a custom post type and taxonomy to create a slider, with unlimited options and support for multiple sliders on any page and with full templating support, the options are unlimited.

WP Top Slider uses a Template tag:

`<?php if( function_exists( 'WP_Top_Slider' ) ){ WP_Top_Slider( 'slider-slug' ); } ?>`

And A Shortcode:

`[TopSlider name="slider-slug"]`


== Installation ==

1.Upload The wp-top-slider folder to the /wp-content/plugins/ directory
2.Activate the plugin through the 'Plugins' menu in WordPress
3.Iinsert the Template Tag `<?php if( function_exists( 'WP_Top_Slider' ) ){ WP_Top_Slider( 'slider-slug' ); } ?>` Or The Shortcode `[TopSlider name="slider-slug"]` where you want the slider to display.


== Templating ==

WP Top Slider 1.0+ has full support for templating, If are not comfortable working on minor edits to the code in your site, this feature will not be for you.

To activate templating, simply create a folder called "wpts" in your theme folder.

There are 4 types of slides available, these are fully individually templateable, you can even edit the output for a slide type of a specific slide. 

To activate templating:

1. Create a file in your theme "wpts"
2. copy the files from wp-top-slider/templates/ to the new theme folder.
3. Edit Away!

You can edit the templates for an individual slider using the following guide:

1. copy the files from wp-top-slider/templates/ to the wpts folder.
2. Rename The required file to: {$slug}_{$type}.php. EG: If you have a slider called cool_slider and want to edit the output for the Full Width Image type,the filename would be: cool_slider_full-width-image.php.
3. Edit Away.

=== ACF Page Builder Field ===
Contributors: pekz0r, moelleer, viktorfroberg, lindstromer, angrycreative
Tags: acf, page builder, site origin page builder, flexible content, flexible fields, acf flexible content
Requires at least: 4.0
Tested up to: 4.7.3
Stable tag: 1.0.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin will add a Page Builder field type in Advanced Custom Fields.

== Description ==

This plugin will add a page builder field type in Advanced custom fields.
The field works just like any other ACF field type and you can use it however you want. `get_field()` returns the generated HTML and the CSS is outputed in the footer. We are using it together with ACFs flexible content layouts where you want to build more advanced layouts inside the ACF sections. This makes the content in ACFs flexible content truly flexible!

Building a great CMS-experience for editors, designers and developers is really hard. They all have very different requirements and ideas about that a great CMS consists of and how it should work. How do you give the editors the tools they need to edit all the content, the designer the freedom they want about how things should look, and empower the developer with tools to provide this easily and efficently?
We think we have found a sweet spot when ut comes to the balance between freedom for for editors and designers and ease for developers to implement and maintain a beatifull site.

= The editor =

The editor can easily edit all the content and maintain a good look and feel of the website. The editor has the right amunt of freedom to be able to express themselfts, but enough structure to prevent them from going wild and ruin the page layout and design.

= The designer =

The designer can be creative and has the freedom they need express themselfs without making the life for the developers hard.

= The developer =

The developer have the tools to easily create blocks/modules that fits good together.


= Requirements =

* Advanced Custom Fields 5+ (ACF 4 is not supported)
* Page Builder by SiteOrigin 2.5 or newer
* PHP 5.3+

= Theme integration =

We recommend using this plugin together with ACF Flexible Content for building beautiful landing pages.
This is an example of a template using ACF Flexible Content:

`
if( have_rows('flexible_content_field_name') ) :

    while ( have_rows('flexible_content_field_name') ) : the_row();

        switch( get_row_layout() ) {

            case 'page_builder_layout':

                if( get_sub_field( 'page_builder_field' ) ) {
                    echo get_sub_field( 'page_builder_field' );
                }

                break;
            case 'other_layout':

                the_sub_field('field1');
                the_sub_field('field2');

                break;
        }

    endwhile;

endif;
`

For more information, read about [flexible content on advancedcustomfields.com](http://www.advancedcustomfields.com/resources/flexible-content/).

We also recommend using one of the latest versions of WordPress, Advanced Custom Fields and Page Builder by SiteOrigin at all times for best compatibility.

= Known issues and limitations =

* Use of ACF Page Builder field in widget areas. We aim to fix this in a comming release.
* Does not work on ACF Option pages. We aim to fix this in a comming release.
* Some issues with Page Builder content in `the_content()`(normal post content) if an ACF Page Builder field is rendered before `the_content()`. This is due to the way the page builder works and we can't fix this in a good way. We recommend using ACF Page Builder fields instead and removing the normal post content completely on ACF-pages in WP-Admin as a workaround.

== Screenshots ==

1. This shows the field in WP-Admin. The page builder button opens up a Page Builder, just like the normal one from Site Origin.

== Installation ==

1. Copy the `acf-page-builder-field` folder into your `wp-content/plugins` folder.
2. Activate the ACF Page Builder Field plugin via the plugins admin page.
3. Create a new field via ACF and select the Page Builder Field type.
4. Display the field in your theme's templates with `get_field()` or `get_sub_field()` as usual.
5. Enjoy your improved CMS experience!

== Changelog ==

= 1.0.3 =
* Fix for custom ID
* Fix for custom cell classes.
* Fix for full width rows
* Fix for row customizations without custom row class...

= 1.0.2 =
* Fix PHP warning
* Fix missing widget customizations

= 1.0.1 =
* Fix for Page Builder 2.5

= 1.0.0 =
* First stable release.

= 1.0.0-rc.3 =
* Support for version 2.4.9 of the Page Builder by Siteorigin plugin.

= 0.1.0 =
* Initial Beta Release.


== Upgrade Notice ==

= 1.0.0 =
* This is a stable version. Please upgrade! Please note that you might need to reactivate the plugin after updating.

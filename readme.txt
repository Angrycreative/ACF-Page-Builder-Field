=== Advanced Custom Fields: ACF Page Builder Field Field ===
Contributors: pekz0r, moelleer, angrycreative
Tags: acf, page builder, flexible content, flexibale fields, acf flexible content
Requires at least: 4.0
Tested up to: 4.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html


== Description ==

This plugin will add a page builder field type in Advanced custom fields.
This is primarily for usage in ACFs flexible content layouts where you want to build more advanced layouts inside the ACF sections.

= Note: this plugin is currently in beta - expect bugs. =

= Requirements =

This plugin requires:

* Advanced Custom Fields 5+
* Page Builder by SiteOrigin
* PHP 5.3+

= Theme integration =

This is an example of a template using ACF Flexible Content.

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


We recommend using the latest version of WordPress, Advanced Custom Fields and Page Builder by SiteOrigin

== Installation ==

1. Copy the `acf-page-builder-field` folder into your `wp-content/plugins` folder
=======



== Installation ==

1. Copy the `acf-so-page-builder-field` folder into your `wp-content/plugins` folder
2. Activate the ACF Page Builder Field plugin via the plugins admin page
3. Create a new field via ACF and select the Page Builder Field type
4. Please refer to the description for more info regarding the field type settings

== Changelog ==

= 0.1.0 =
* Initial Beta Release.


== Upgrade Notice ==

= 1.0.0 =
* Stable version


# ACF SiteOrigin Page Builder Field

Currently in Beta. We will release the first stable version (1.0.0) in Q2 of 2016.

Adds a "Page Builder Field" for the [Advanced Custom Fields](http://wordpress.org/extend/plugins/advanced-custom-fields/) WordPress plugin.

### Plugin requirements

 - The plugins require the following plugins: 
    - [Advanced Custom Fields 5+ (ACF Pro)](http://www.advancedcustomfields.com/). Support ACF version 4 coming soon. [See issue for updates](https://github.com/Angrycreative/ACF-Page-Builder-Field/issues/3).
    - [SiteOrigin Page Builder](https://sv.wordpress.org/plugins/siteorigin-panels/)
 - PHP 5.3+

### How to use the plugin

More documentation coming soon.

### Installation

1. Copy the `acf-page-builder-field` folder into your `wp-content/plugins` folder
2. Activate the plugin via the plugins admin page (and make sure the required plugins are already activated)
3. Create a new field via ACF and select the Page Builder Field type


### Known issues and limitations

 - Use of ACF Page Builder field in widget areas. We aim to fix this in a comming release.
 - Some issues with Page Builder content in `the_content()`(normal post content) if an ACF Page Builder field is rendered before `the_content()`. We recommend using ACF Page Builder fields instead and removing the normal post content completly on ACF-pages in WP-Admin. 


### Integrate into your ACF fields.

A blog post will be published soon about how we use this plugin to make an awesome CMS experience for our customers. 


### Output in theme

```php
if( get_field( 'page_builder_field' ) ) {
    echo get_field( 'page_builder_field' );
}
```


### Changelog
Please see `readme.txt` for changelog

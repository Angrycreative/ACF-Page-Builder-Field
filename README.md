
# ACF SiteOrigin Page Builder Field

We are currently in the release candidate stage. We want to as many as possible to try the plugin in their configurations and setups and report any problems back to us. The first stable version (1.0.0) will be released as soon as we have collected enough feedback and fixed the issues that have been reported. We currently use it in production without any issues ourselfs and it should be safe to use this your current developemnt projects, or production enviornments if you test it thoroughly.

Adds a "Page Builder Field" for the [Advanced Custom Fields](http://wordpress.org/extend/plugins/advanced-custom-fields/) WordPress plugin.

### Plugin requirements

 - The plugins require the following plugins: 
    - [Advanced Custom Fields 5+ (ACF Pro)](http://www.advancedcustomfields.com/). Support ACF version 4 coming soon. [See issue for updates](https://github.com/Angrycreative/ACF-Page-Builder-Field/issues/3).
    - [SiteOrigin Page Builder](https://sv.wordpress.org/plugins/siteorigin-panels/)
 - PHP 5.3+

### How to use the plugin

The page builder field can be used as any other ACF field. `get_field` will output the HTML for the page builder layout and the needed CSS will be generated on the page footer.

More documentation coming soon.

### Installation

1. Copy the `acf-page-builder-field` folder into your `wp-content/plugins` folder
2. Activate the plugin via the plugins admin page (and make sure the required plugins are already activated)
3. Create a new field via ACF and select the Page Builder Field type


### Known issues and limitations

 - Use of ACF Page Builder field in widget areas. We aim to fix this in a comming release.
 - Does not work on ACF Option pages. We aim to fix this in a comming release.
 - Some issues with Page Builder content in `the_content()`(normal post content) if an ACF Page Builder field is rendered before `the_content()`. This is due to the way the page builder works and we can't fix this in a good way. We recommend using ACF Page Builder fields instead and removing the normal post content completely on ACF-pages in WP-Admin as a workaround.

### Integrate into your ACF fields.

A blog post will be published soon about how we use this plugin to make an awesome CMS experience for our customers. 


### Output in theme

```php
if( function_exists( 'get_field' ) && get_field( 'page_builder_field' ) ) {
    echo get_field( 'page_builder_field' );
}
```


### Changelog
Please see `readme.txt` or [WordPress.org](https://wordpress.org/plugins/acf-page-builder-field/changelog/) for changelog

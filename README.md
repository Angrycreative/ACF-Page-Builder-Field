
# ACF SiteOrigin Page Builder Field

Currently in Beta. We will release the first public version in beginning of 2016.

Adds a 'Page Builder Field' for the [Advanced Custom Fields](http://wordpress.org/extend/plugins/advanced-custom-fields/) WordPress plugin.

### Plugin requirements
 - The plugins require the following plugins: [Advanced custom fields 5+ (ACF Pro)](http://www.advancedcustomfields.com/), [SiteOrigin Page Builder](https://sv.wordpress.org/plugins/siteorigin-panels/)
 - PHP 5.3+
 
Note: The plugin does not support the free version of Advanced Custom Fields(version 4). We consider adding support later if we feel that there is sufficent interest.

### How to use the plugin

More documentation coming soon.

### Installation

1. Copy the `acf-page-builder-field` folder into your `wp-content/plugins` folder
2. Activate the plugin via the plugins admin page (and make sure the required plugins are already activated)
3. Create a new field via ACF and select the Page Builder Field type


### Load the plugin

This plugin conflicts with the normal page builder pages. Becase of that we need to prevent the plugin form loading on pure page builder pages. The plugin tries to autodetect whether the current page is a p ure page builder page or not, but there are cases where this is not 100% acurate. Therefore we recomend that you use the `acfpbf_use_on_templates`-filter to specify the templates where you use the page builder field. 

Use it like this:

```php
add_filter('acfpbf_use_on_templates', function(){
    return array( 'page-with-acf.php', 'page-acf-flexible-content' );
});
```

If you want more control you can use the `acfpbf_use_on_current_page`-filter to write your own logic for when to activate the field.

```php
add_filter('acfpbf_use_on_current_page', function( $use_on_current_page, $template_name ){
	
	if( $template_name == 'my-custom-template.php' )
	{
		return true;
	}
	
	return $use_on_current_page;
});
```

### Integrate into your ACF fields.




### Output in theme

```php
    if( get_field( 'page_builder_field' ) )
    {
    	echo get_field( 'page_builder_field' );
    }
```


### Changelog
Please see `readme.txt` for changelog
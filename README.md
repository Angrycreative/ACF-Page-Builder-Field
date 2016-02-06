
# ACF SiteOrigin Page Builder Field

Currently in Beta. We will release the first public version in beginning of 2016.

## Plugin requirements
 - The plugins require the following plugins: [Advanced custom fields 5+ (ACF Pro)](http://www.advancedcustomfields.com/), [SiteOrigin Page Builder](https://sv.wordpress.org/plugins/siteorigin-panels/)
 - PHP 5.3+
 
Note: The plugin does not support the free version of Advanced Custom Fields(version 4). We consider adding support later if we feel that there is sufficent interest.

## How to use the plugin

More documentation coming soon.

### Integrate into your ACF fields.




### Output in theme

```
    if( function_exists( 'get_page_builder_field' ))
    {
        echo get_page_builder_field( 'page_builder_data' );
    }
```



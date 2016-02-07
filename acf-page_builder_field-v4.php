<?php

class acf_field_page_builder_field extends acf_field {

    // vars
    var $settings, // will hold info such as dir / path
        $defaults; // will hold default field options


    /*
    *  __construct
    *
    *  Set name / label needed for actions / filters
    *
    *  @since	3.6
    *  @date	23/01/13
    */

    function __construct()
    {
        // vars
        $this->name = 'page_builder_field';
        $this->label = __('Page Builder Field', 'acf-page_builder_field');
        $this->category = __("Content", 'acf'); // Basic, Content, Choice, etc
        $this->defaults = array(
            // add default here to merge into your field.
            // This makes life easy when creating the field options as you don't need to use any if( isset('') ) logic. eg:
            //'preview_size' => 'thumbnail'
        );


        // do not delete!
        parent::__construct();


        // settings
        $this->settings = array(
            'path' => apply_filters('acf/helpers/get_path', __FILE__),
            'dir' => apply_filters('acf/helpers/get_dir', __FILE__),
            'version' => '1.0.0'
        );

    }

    /*
    *  create_field()
    *
    *  Create the HTML interface for your field
    *
    *  @param	$field - an array holding all the field's data
    *
    *  @type	action
    *  @since	3.6
    *  @date	23/01/13
    */

    function create_field( $field )
    {
        var_dump($field);

        ACFPB()->render_admin_field( $field, 4 );
    }


    /*
    *  input_admin_enqueue_scripts()
    *
    *  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
    *  Use this action to add CSS + JavaScript to assist your create_field() action.
    *
    *  $info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
    *  @type	action
    *  @since	3.6
    *  @date	23/01/13
    */

    function input_admin_enqueue_scripts()
    {
        // Note: This function can be removed if not used


        // register ACF scripts
        wp_register_script( 'acf-input-page_builder_field', $this->settings['dir'] . 'js/input.js', array('acf-input'), $this->settings['version'] );
        wp_register_style( 'acf-input-page_builder_field', $this->settings['dir'] . 'css/input.css', array('acf-input'), $this->settings['version'] );


        // scripts
        wp_enqueue_script(array(
            'acf-input-page_builder_field',
        ));

        // styles
        wp_enqueue_style(array(
            'acf-input-page_builder_field',
        ));


    }

    /*
    *  load_value()
    *
        *  This filter is applied to the $value after it is loaded from the db
    *
    *  @type	filter
    *  @since	3.6
    *  @date	23/01/13
    *
    *  @param	$value - the value found in the database
    *  @param	$post_id - the $post_id from which the value was loaded
    *  @param	$field - the field array holding all the field options
    *
    *  @return	$value - the value to be saved in the database
    */

    function load_value( $value, $post_id, $field )
    {
        // Note: This function can be removed if not used
        return $value;
    }


    /*
    *  update_value()
    *
    *  This filter is applied to the $value before it is updated in the db
    *
    *  @type	filter
    *  @since	3.6
    *  @date	23/01/13
    *
    *  @param	$value - the value which will be saved in the database
    *  @param	$post_id - the $post_id of which the value will be saved
    *  @param	$field - the field array holding all the field options
    *
    *  @return	$value - the modified value
    */

    function update_value( $value, $post_id, $field )
    {
        // Note: This function can be removed if not used
        return $value;
    }


    /*
    *  format_value()
    *
    *  This filter is applied to the $value after it is loaded from the db and before it is passed to the create_field action
    *
    *  @type	filter
    *  @since	3.6
    *  @date	23/01/13
    *
    *  @param	$value	- the value which was loaded from the database
    *  @param	$post_id - the $post_id from which the value was loaded
    *  @param	$field	- the field array holding all the field options
    *
    *  @return	$value	- the modified value
    */

    function format_value( $value, $post_id, $field )
    {
        // defaults?
        /*
        $field = array_merge($this->defaults, $field);
        */

        // perhaps use $field['preview_size'] to alter the $value?


        // Note: This function can be removed if not used
        return $value;
    }


    /*
    *  format_value_for_api()
    *
    *  This filter is applied to the $value after it is loaded from the db and before it is passed back to the API functions such as the_field
    *
    *  @type	filter
    *  @since	3.6
    *  @date	23/01/13
    *
    *  @param	$value	- the value which was loaded from the database
    *  @param	$post_id - the $post_id from which the value was loaded
    *  @param	$field	- the field array holding all the field options
    *
    *  @return	$value	- the modified value
    */

    function format_value_for_api( $value, $post_id, $field )
    {
        // defaults?
        /*
        $field = array_merge($this->defaults, $field);
        */

        // perhaps use $field['preview_size'] to alter the $value?


        // Note: This function can be removed if not used
        return $value;
    }


    /*
    *  load_field()
    *
    *  This filter is applied to the $field after it is loaded from the database
    *
    *  @type	filter
    *  @since	3.6
    *  @date	23/01/13
    *
    *  @param	$field - the field array holding all the field options
    *
    *  @return	$field - the field array holding all the field options
    */

    function load_field( $field )
    {
        // Note: This function can be removed if not used
        return $field;
    }


    /*
    *  update_field()
    *
    *  This filter is applied to the $field before it is saved to the database
    *
    *  @type	filter
    *  @since	3.6
    *  @date	23/01/13
    *
    *  @param	$field - the field array holding all the field options
    *  @param	$post_id - the field group ID (post_type = acf)
    *
    *  @return	$field - the modified field
    */

    function update_field( $field, $post_id )
    {
        // Note: This function can be removed if not used
        return $field;
    }


}


// create field
new acf_field_page_builder_field();

?>

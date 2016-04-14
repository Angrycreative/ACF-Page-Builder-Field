<?php
/*
Plugin Name: Advanced Custom Fields: Page Builder Field
Plugin URI: https://wordpress.org/plugins/acf-page-builder-field/
Description: This plugin will add a page builder field in Advanced custom fields
Version: 1.0.0-rc.2
Author: Peter Elmered, Johan Möller, Angry Creative
Author URI: https://angrycreative.se/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

register_activation_hook( __FILE__, array( 'ACF_Page_Builder', 'check_required_plugins' ) );

class ACF_Page_Builder {
    
    /**
     * Variable that tells us if the plugin has added the needed filters
     *
     * @var boolean
     */
    protected $activated = false;

    /**
     * Variable that holds the CSS/styles for the current page
     *
     * @var string
     */
    protected $page_styles = '';

    /**
     * Keeps track of if we should activate the plugin on current page
     *
     * @var array - Array of page templates
     */
    protected $use_on_current_page = null;

    /**
     * Holds the instance of this class
     *
     * @var null
     */
    protected static $instance = null;


    /**
     * Return an instance of this class. Singleton pattern.
     *
     * @since     0.1.0
     *
     * @return    object    A single instance of this class.
     */
    public static function get_instance() {
        // If the single instance hasn't been set, set it now.
        if (null == self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    private function __construct() {
        // add_action('template_redirect', array($this, 'init'), 20);

        // Generate HTML for get_field()
        add_filter('acf/format_value/type=page_builder_field', array( $this, 'render_page_builder_field' ), 10, 3);
        $this->check_required_plugins( true );
    }

    /**
     * Add the needed filters for correct output
     */
    function init() {

        // Only add the filters once
        if( $this->activated ) {
            return true;
        }

        // Prefix the panel id to make it unique for every field on a page
        add_filter( 'siteorigin_panels_row_attributes', array( $this, 'siteorigin_panels_attributes' ), 10, 2 );
        add_filter( 'siteorigin_panels_row_cell_attributes', array( $this, 'siteorigin_panels_attributes' ), 10, 2 );
        add_filter( 'siteorigin_panels_layout_attributes', array( $this, 'siteorigin_panels_attributes' ), 10, 2 );
        
        // The styles should outputted once per page, in the footer
        add_action( 'wp_footer', array( $this, 'output_styles' ) );

        $this->activated = true;

    }

    /**
     * Check if required plugins are activated. If not deactivate this plugin to avoid problems and show an error
     * message when you try to activate it.
     *
     * @param $already_activated
     * @return bool
     */
    static function check_required_plugins( $already_activated ) {
        // Check if SiteOrigin Page Builder and ACF is installed and active
        if( function_exists( 'siteorigin_panels_render' ) && class_exists( 'acf' ) ) {
            return true;
        } else if ( $already_activated ) {
            deactivate_plugins( plugin_basename( __FILE__ ) );
        } else {
            wp_die(
                '<p>This plugin can not be activated because it requires Advances Custom Fields and Site Origin Page Builder to be active. </p> <a href="' . admin_url( 'plugins.php' ) . '">' . __( 'Back to plugins page', 'my_plugin' ) . '</a>'
            );
        }
    }

    /**
     * Generate HTML for a field
     *
     * @param $value
     * @param $post_id
     * @param $field
     * @return string
     */
    function render_page_builder_field( $value, $post_id, $field ) {

        $uid = uniqid( true );
        $len = strlen($uid);

        $field_id = 'acfpbf_'.substr($uid, $len - 6, 6);

        $acf_page_builder_content = $this->acf_siteorigin_panels_render( $field_id, $value );

        $output = '';

        if( $acf_page_builder_content ) {

            $this->init();

            $output .= '<div id="acf_page_builder_field_id_'.$field_id.'" class="acf-page-builder-field">';

            $output .= $acf_page_builder_content;

            $output .= '</div>';

        }

        return $output;
    }

    /**
     * Prefixes the panel id to make it unique for every field on a page.
     * Hooked in on:
     *  - siteorigin_panels_row_attributes
     *  - siteorigin_panels_row_cell_attributes
     *  - siteorigin_panels_layout_attributes
     *
     * @param $attr
     * @param $panels_data
     * @return mixed
     */
    function siteorigin_panels_attributes( $attr, $panels_data )
    {
        global $panel_id;

        $panel_id = $GLOBALS['panel_id'];

        $attr['id'] = $panel_id.'-'.$attr['id'];

        return $attr;
    }

    /**
     * Enqueues the scripts that makes the ACF field work in the admin views
     */
    function acf_field_page_builder_field_admin_enqueue_scripts()
    {
        wp_enqueue_script('acf-input-page_builder_field', plugin_dir_url( __FILE__ ).'js/input.js', array('jquery','so-panels-admin'), '1.0', true);
    }

    /**
     * This is a rewrite of siteorigin_panels_render function in the SiteOrigin Page Builder plugin
     *
     * @return String - HTML of page builder field
     */
    function acf_siteorigin_panels_render( $panel_id, $panels_data ) {

        $GLOBALS['panel_id'] = $panel_id;

        if( is_string( $panels_data ) )
        {
            $panels_data = json_decode( $panels_data, true );
        }

        if( empty($post_id) ) $post_id = get_the_ID();

        $panels_data = apply_filters( 'siteorigin_panels_data', $panels_data, $post_id, $panel_id );

        if( empty( $panels_data ) || empty( $panels_data['grids'] ) ) return '';

        // Filter the widgets to add indexes
        if ( !empty( $panels_data['widgets'] ) ) {
            $last_gi = 0;
            $last_ci = 0;
            $last_wi = 0;
            foreach ( $panels_data['widgets'] as $wid => &$widget_info ) {

                if ( $widget_info['panels_info']['grid'] != $last_gi ) {
                    $last_gi = $widget_info['panels_info']['grid'];
                    $last_ci = 0;
                    $last_wi = 0;
                }
                elseif ( $widget_info['panels_info']['cell'] != $last_ci ) {
                    $last_ci = $widget_info['panels_info']['cell'];
                    $last_wi = 0;
                }
                $widget_info['panels_info']['cell_index'] = $last_wi++;
            }
        }

        if( is_rtl() ) $panels_data = siteorigin_panels_make_rtl( $panels_data );

        // Create the skeleton of the grids
        $grids = array();
        if( !empty( $panels_data['grids'] ) && !empty( $panels_data['grids'] ) ) {
            foreach ( $panels_data['grids'] as $gi => $grid ) {
                $gi = intval( $gi );
                $grids[$gi] = array();
                for ( $i = 0; $i < $grid['cells']; $i++ ) {
                    $grids[$gi][$i] = array();
                }
            }
        }

        // We need this to migrate from the old $panels_data that put widget meta into the "info" key instead of "panels_info"
        if( !empty( $panels_data['widgets'] ) && is_array($panels_data['widgets']) ) {
            foreach ( $panels_data['widgets'] as $i => $widget ) {
                if( empty( $panels_data['widgets'][$i]['panels_info'] ) ) {
                    $panels_data['widgets'][$i]['panels_info'] = $panels_data['widgets'][$i]['info'];
                    unset($panels_data['widgets'][$i]['info']);
                }
            }
        }

        if( !empty( $panels_data['widgets'] ) && is_array($panels_data['widgets']) ){
            foreach ( $panels_data['widgets'] as $widget ) {
                // Put the widgets in the grids
                $grids[ intval( $widget['panels_info']['grid']) ][ intval( $widget['panels_info']['cell'] ) ][] = $widget;
            }
        }

        ob_start();

        // Add the panel layout wrapper
        $panel_layout_classes = apply_filters( 'siteorigin_panels_layout_classes', array(), $post_id, $panels_data, $panel_id );
        $panel_layout_attributes = apply_filters( 'siteorigin_panels_layout_attributes', array(
            'class' => implode( ' ', $panel_layout_classes ),
            'id' => 'pl-' . $post_id
        ),  $post_id, $panels_data );
        echo '<div';
        foreach ( $panel_layout_attributes as $name => $value ) {
            if ($value) {
                echo ' ' . $name . '="' . esc_attr($value) . '"';
            }
        }
        echo '>';

        global $acf_siteorigin_panels_inline_css;
        if( empty($acf_siteorigin_panels_inline_css) ) $acf_siteorigin_panels_inline_css = array();

        if( !isset($acf_siteorigin_panels_inline_css[$post_id]) ) {
            wp_enqueue_style('siteorigin-panels-front');

            $acf_siteorigin_panels_inline_css[$panel_id] = siteorigin_panels_generate_css($post_id, $panels_data);
        }

        $this->page_styles = $acf_siteorigin_panels_inline_css;

        echo apply_filters( 'siteorigin_panels_before_content', '', $panels_data, $post_id );

        foreach ( $grids as $gi => $cells ) {

            $grid_classes = apply_filters( 'siteorigin_panels_row_classes', array('panel-grid'), $panels_data['grids'][$gi] );
            $grid_attributes = apply_filters( 'siteorigin_panels_row_attributes', array(
                'class' => implode( ' ', $grid_classes ),
                'id' => 'pg-' . $post_id . '-' . $gi
            ), $panels_data['grids'][$gi] );

            // This allows other themes and plugins to add html before the row
            echo apply_filters( 'siteorigin_panels_before_row', '', $panels_data['grids'][$gi], $grid_attributes );

            echo '<div ';
            foreach ( $grid_attributes as $name => $value ) {
                echo $name.'="'.esc_attr($value).'" ';
            }
            echo '>';

            $style_attributes = array();
            if( !empty( $panels_data['grids'][$gi]['style']['class'] ) ) {
                $style_attributes['class'] = array('panel-row-style-'.$panels_data['grids'][$gi]['style']['class']);
            }

            // Themes can add their own attributes to the style wrapper
            $row_style_wrapper = siteorigin_panels_start_style_wrapper( 'row', $style_attributes, !empty($panels_data['grids'][$gi]['style']) ? $panels_data['grids'][$gi]['style'] : array() );
            if( !empty($row_style_wrapper) ) echo $row_style_wrapper;

            foreach ( $cells as $ci => $widgets ) {
                // Themes can add their own styles to cells
                $cell_classes = apply_filters( 'siteorigin_panels_row_cell_classes', array('panel-grid-cell'), $panels_data );
                $cell_attributes = apply_filters( 'siteorigin_panels_row_cell_attributes', array(
                    'class' => implode( ' ', $cell_classes ),
                    'id' => 'pgc-' . $post_id . '-' . $gi  . '-' . $ci
                ), $panels_data );

                echo '<div ';
                foreach ( $cell_attributes as $name => $value ) {
                    echo $name.'="'.esc_attr($value).'" ';
                }
                echo '>';

                $cell_style_wrapper = siteorigin_panels_start_style_wrapper( 'cell', array(), !empty($panels_data['grids'][$gi]['style']) ? $panels_data['grids'][$gi]['style'] : array() );
                if( !empty($cell_style_wrapper) ) echo $cell_style_wrapper;

                $widget_index = 0;
                foreach ( $widgets as $pi => $widget_info ) {
                    $widget_info['panels_info']['widget_index'] = $widget_index;
                    $widget_index += 1;
                    // TODO this wrapper should go in the before/after widget arguments
                    $widget_style_wrapper = siteorigin_panels_start_style_wrapper( 'widget', array(), !empty( $widget_info['panels_info']['style'] ) ? $widget_info['panels_info']['style'] : array() );
                    siteorigin_panels_the_widget( $widget_info['panels_info'], $widget_info, $gi, $ci, $pi, $pi == 0, $pi == count( $widgets ) - 1, $post_id, $widget_style_wrapper );
                }
                if ( empty( $widgets ) ) echo '&nbsp;';

                if( !empty($cell_style_wrapper) ) echo '</div>';
                echo '</div>';
            }

            echo '</div>';

            // Close the
            if( !empty($row_style_wrapper) ) echo '</div>';

            // This allows other themes and plugins to add html after the row
            echo apply_filters( 'siteorigin_panels_after_row', '', $panels_data['grids'][$gi], $grid_attributes );
        }

        echo apply_filters( 'siteorigin_panels_after_content', '', $panels_data, $post_id );

        echo '</div>';

        $html = ob_get_clean();

        return apply_filters( 'siteorigin_panels_render', $html, $post_id, !empty($post) ? $post : null );
    }


    /**
     * Output all the styles for the current page(all ACF fields in one chunk)
     */
    function output_styles() {

        global $acf_siteorigin_panels_inline_css;

        // If we don't have any styles queued, bail out
        if( empty( $acf_siteorigin_panels_inline_css ) || !is_array( $acf_siteorigin_panels_inline_css ) )
        {
            return;
        }

        $styles = '';

        // Set unique IDs on the styles so we can target each field with CSS separately
        foreach( $acf_siteorigin_panels_inline_css AS $style_key => $style_data )
        {
            $search = array( '#pgc', '#pg', '#pl' );
            $replace = array( '#'.$style_key.'-pgc', '#'.$style_key.'-pg', '#'.$style_key.'-pl' );

            $styles .= str_replace($search, $replace, $style_data);
        }

        echo '<style>'.$styles.'</style>';
    }

}

/**
 * Function to access the plugin object ( usage: ACFPB()->method() )
 *
 * @return object
 */
function ACFPB()
{
    return ACF_Page_Builder::get_instance();
}


/**
 * Enqueue admin scripts to make the page builder field work in the admin views
 */
add_action('init', function() {

    if( is_admin() )
    {
        add_action( 'admin_print_scripts-post-new.php', array( ACFPB(), 'acf_field_page_builder_field_admin_enqueue_scripts' ) , 999 );
        add_action( 'admin_print_scripts-post.php', array( ACFPB(), 'acf_field_page_builder_field_admin_enqueue_scripts' ), 999 );
    }

} );


/**
 * Register ACF field and instantiate the plugin
 */
add_action('acf/include_field_types', 'include_field_types_page_builder_field' );

// $acf_version = 5 and can be ignored until ACF6 exists
function include_field_types_page_builder_field( $acf_version ) {

    ACFPB();
    include_once('acf-page-builder-field-v5.php');

}

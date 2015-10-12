<?php
/*
Plugin Name: Advanced Custom Fields: Page Builder Field
Plugin URI: https://bitbucket.org/angrycreative/acf-so-page-builder-field
Description: SHORT_DESCRIPTION
Version: 1.0.0
Author: Angry Creative
Author URI: https://angrycreative.se/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/



class ACF_Page_Builder {


    protected $page_styles = '';


    protected static $instance = null;

    public $use_on = array( 'page-with-sections.php' );

    /**
     * Return an instance of this class.
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


    function __construct()
    {
        add_action('template_redirect', array($this, 'init'), 20);

        //$this->init();
    }

    function init() {

        // 1. set text domain
        // Reference: https://codex.wordpress.org/Function_Reference/load_plugin_textdomain
        load_plugin_textdomain( 'acf-page_builder_field', false, dirname( plugin_basename(__FILE__) ) . '/lang/' );

        if( in_array( basename( get_page_template() ), $this->use_on ))
        {
            add_filter( 'siteorigin_panels_row_attributes', array( $this, 'siteorigin_panels_attributes' ), 10, 2 );
            add_filter( 'siteorigin_panels_row_cell_attributes', array( $this, 'siteorigin_panels_attributes' ), 10, 2 );
            add_filter( 'siteorigin_panels_layout_attributes', array( $this, 'siteorigin_panels_attributes' ), 10, 2 );

            add_action( 'wp_footer', array( $this, 'output_styles' ) );
        }

    }





    function get_page_builder_field( $field_key )
    {
        $uid = uniqid( true );
        $len = strlen($uid);

        $field_id = 'acfpbf_'.substr($uid, $len - 6, 6);

        $output = '<div id="acf_page_builder_field_id_'.$field_id.'" >';

        $panels_data = get_sub_field( $field_key );

        $output .= $this->acf_siteorigin_panels_render( $field_id, $panels_data );

        $output .= '</div>';

        return $output;
    }







    function siteorigin_panels_attributes( $attr, $panels_data )
    {
        global $panel_id;

        if( basename( get_page_template() ) == 'page-with-sections.php')
        {
            global $panel_id;

            $panel_id = $GLOBALS['panel_id'];

            $attr['id'] = $panel_id.'-'.$attr['id'];

            return $attr;
        }

        return $attr;
    }




    function acf_field_page_builder_field_admin_enqueue_scripts()
    {
        $dir = plugin_dir_url( __FILE__ );

        //wp_register_script( 'acf-input-page_builder_field', "{$dir}js/input.js" );
        wp_enqueue_script('acf-input-page_builder_field', "{$dir}js/input.js", array('jquery','acf-page-builder-field-init','so-panels-admin','so-panels-admin-live-editor'), '1.0', true);

        wp_register_script('acf-page-builder-field-init', plugin_dir_url( __FILE__ ) . 'js/init.js', array('jquery','so-panels-admin','so-panels-admin-live-editor'), '1.0', true);
        //wp_register_script('acf-page-builder-field-init', plugin_dir_url( __FILE__ ) . 'js/init.js', array('so-panels-admin'));

        //die(plugin_dir_url( __FILE__ ) . 'js/init.js');

        // scripts
        wp_enqueue_script(array('acf-page-builder-field-init'));
    }

    function acf_siteorigin_panels_render( $panel_id, $panels_data, $enqueue_css = true ) {

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

        if( $enqueue_css && !isset($acf_siteorigin_panels_inline_css[$post_id]) ) {
            wp_enqueue_style('siteorigin-panels-front');

            //var_dump($panels_data);

            $acf_siteorigin_panels_inline_css[$panel_id] = siteorigin_panels_generate_css($post_id, $panels_data);
            /*
            var_dump($acf_siteorigin_panels_inline_css);
            die();
            */
        }

        $this->page_styles = $acf_siteorigin_panels_inline_css;

        /*
        echo '<style >';
        foreach( $acf_siteorigin_panels_inline_css AS $style_key => $style )
        {
            $search = array( '#pgc', '#pg', '#pl' );
            $replace = array( '#'.$style_key.'-pgc', '#'.$style_key.'-pg', '#'.$style_key.'-pl' );

            $style = str_replace($search, $replace, $style);

            echo $style;
        }
        echo '</style>';
        */

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

                foreach ( $widgets as $pi => $widget_info ) {
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

        // Reset the current post
        //$siteorigin_panels_current_post = $old_current_post;

        return apply_filters( 'siteorigin_panels_render', $html, $post_id, !empty($post) ? $post : null );
    }


    function output_styles() {

        global $acf_siteorigin_panels_inline_css;

        $styles = '';

        foreach( $acf_siteorigin_panels_inline_css AS $style_key => $style_data )
        {
            $search = array( '#pgc', '#pg', '#pl' );
            $replace = array( '#'.$style_key.'-pgc', '#'.$style_key.'-pg', '#'.$style_key.'-pl' );

            $styles .= str_replace($search, $replace, $style_data);
        }

        echo '<style>'.$styles.'</style>';
    }

}


function ACFPB()
{
    return ACF_Page_Builder::get_instance();
}


add_action( 'admin_print_scripts-post-new.php', array( ACFPB(), 'acf_field_page_builder_field_admin_enqueue_scripts' ) , 999 );
add_action( 'admin_print_scripts-post.php', array( ACFPB(), 'acf_field_page_builder_field_admin_enqueue_scripts' ), 999 );



add_action('acf/include_field_types', 'include_field_types_page_builder_field' );
// 2. Include field type for ACF5
// $version = 5 and can be ignored until ACF6 exists
function include_field_types_page_builder_field( $version ) {

    ACFPB();
    include_once('acf-page_builder_field-v5.php');
}

if( !function_exists( 'get_page_builder_field' ) )
{
    function get_page_builder_field( $field )
    {
        return ACFPB()->get_page_builder_field( $field );
    }
}


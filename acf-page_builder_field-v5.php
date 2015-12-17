<?php



class acf_field_page_builder_field extends acf_field {
	
	
	/*
	*  __construct
	*
	*  This function will setup the field type data
	*
	*  @type	function
	*  @date	5/03/2014
	*  @since	5.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/
	
	function __construct() {
		
		/*
		*  name (string) Single word, no spaces. Underscores allowed
		*/
		
		$this->name = 'page_builder_field';
		
		
		/*
		*  label (string) Multiple words, can include spaces, visible when selecting a field type
		*/
		
		$this->label = __('Page Builder Field', 'acf-page_builder_field');
		
		
		/*
		*  category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
		*/
		
		$this->category = 'basic';
		
		
		/*
		*  defaults (array) Array of default settings which are merged into the field object. These are used later in settings
		*/
		
		$this->defaults = array(
			'page_builder_data'	=> $this->get_default_layout()
		);
		
		
		/*
		*  l10n (array) Array of strings that are used in JavaScript. This allows JS strings to be translated in PHP and loaded via:
		*  var message = acf._e('page_builder_field', 'error');
		*/
		
		$this->l10n = array(
			'error'	=> __('Error! Please enter a higher value', 'acf-page_builder_field'),
		);
		
				
		// do not delete!
    	parent::__construct();
    	
	}

	function get_default_layout()
	{
		// Load the default layout
		$layouts = apply_filters('siteorigin_panels_prebuilt_layouts', array());
		//$prebuilt_id = siteorigin_panels_setting('home-page-default') ? siteorigin_panels_setting('home-page-default') : 'home';

		//$panels_data = !empty($layouts[$prebuilt_id]) ? $layouts[$prebuilt_id] : current($layouts);

		$panels_data = array();

		return $panels_data;
	}
	
	
	/*
	*  render_field_settings()
	*
	*  Create extra settings for your field. These are visible when editing a field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/
	
	function render_field_settings( $field ) {
		
		/*
		*  acf_render_field_setting
		*
		*  This function will create a setting for your field. Simply pass the $field parameter and an array of field settings.
		*  The array of settings does not require a `value` or `prefix`; These settings are found from the $field array.
		*
		*  More than one setting can be added by copy/paste the above code.
		*  Please note that you must also have a matching $defaults value for the field name (font_size)
		*/
		/*
		acf_render_field_setting( $field, array(
			'label'			=> __('Font Size','acf-page_builder_field'),
			'instructions'	=> __('Customise the input font size','acf-page_builder_field'),
			'type'			=> 'number',
			'name'			=> 'font_size',
			'prepend'		=> 'px',
		));
		*/

	}

	/*
	*  render_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field (array) the $field being rendered
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/
	
	function render_field( $field ) {

		$builder_id = uniqid();
		$panels_data = $field['value'];

		if( empty( $panels_data ))
		{
			$panels_data = json_encode(array());
		}

		if( !is_string( $panels_data ) )
		{
			$panels_data = json_encode( $panels_data );
		}

		?>
		<div class="siteorigin-page-builder-field siteorigin-panels-builder-field" id="siteorigin-page-builder-widget-<?php echo esc_attr( $builder_id ) ?>" data-builder-id="<?php echo esc_attr( $builder_id ) ?>" data-type="layout_widget">

			<p>
				<a href="#" class="button-secondary siteorigin-panels-display-builder-field" id="open-builder-button-<?php echo esc_attr( $builder_id ) ?>" ><?php _e('Open Builder', 'siteorigin-panels') ?></a>
			</p>

			<input type="hidden" data-panels-filter="json_parse" value="<?php echo esc_attr($panels_data); ?>" class="acf-panels-data" name="<?php echo $field['name'] ?>" id="panels_data_<?php echo $builder_id ?>" />
			<script type="text/javascript">
				document.getElementById('panels_data_<?php echo $builder_id ?>').value = decodeURIComponent("<?php echo rawurlencode( $panels_data ); ?>");
			</script>

			<?php /*
			<input type="hidden" value="<?php echo esc_attr( $instance['builder_id'] ) ?>" name="<?php echo $this->get_field_name('builder_id') ?>" />
			*/ ?>
		</div>

		<?php

	}
	
		
	/*
	*  input_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
	*  Use this action to add CSS + JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_enqueue_scripts)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/


	/*
	function input_admin_enqueue_scripts() {
		
		$dir = plugin_dir_url( __FILE__ );
		
		
		// register & include JS
		//wp_register_script( 'acf-input-page_builder_field', "{$dir}js/input.js" );
		wp_enqueue_script('acf-input-page_builder_field', "{$dir}js/input.js", array('so-panels-admin'), '1.0');
		

		// register & include CSS
		wp_register_style( 'acf-input-page_builder_field', "{$dir}css/input.css" );
		wp_enqueue_style('acf-input-page_builder_field');
		
	}
	*/
	

	
	
	/*
	*  input_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is created.
	*  Use this action to add CSS and JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_head)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/


		
	function input_admin_head() {

		
	}
	

	
	
	/*
   	*  input_form_data()
   	*
   	*  This function is called once on the 'input' page between the head and footer
   	*  There are 2 situations where ACF did not load during the 'acf/input_admin_enqueue_scripts' and 
   	*  'acf/input_admin_head' actions because ACF did not know it was going to be used. These situations are
   	*  seen on comments / user edit forms on the front end. This function will always be called, and includes
   	*  $args that related to the current screen such as $args['post_id']
   	*
   	*  @type	function
   	*  @date	6/03/2014
   	*  @since	5.0.0
   	*
   	*  @param	$args (array)
   	*  @return	n/a
   	*/
   	
   	/*
   	
   	function input_form_data( $args ) {
	   	
		
	
   	}
   	
   	*/
	
	
	/*
	*  input_admin_footer()
	*
	*  This action is called in the admin_footer action on the edit screen where your field is created.
	*  Use this action to add CSS and JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_footer)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

/*
		
	function input_admin_footer() {

		?>
		<script type="text/javascript">


			(function ( $ ) {

				var panels = window.siteoriginPanels;
				console.log('panels');
				console.log(panels);

				$.fn.soPanelsSetupBuilderWidget2 = function () {

					console.log('inside22');
					console.log(this);
					console.log(this);



					return this.each(function(){
						var $$ = $(this);

						console.log($(this));

						var widgetId = $$.closest('form').find('.widget-id').val();

						console.log('widgetid');
						console.log(widgetId);
						// Exit if this isn't a real widget
						if( typeof widgetId !== 'undefined' && widgetId.indexOf('__i__') > -1 ) {
							return;
						}

						// Create the main builder model
						var builderModel = new panels.model.builder();

						// Now for the view to display the builder
						var builderView = new panels.view.builder( {
							model: builderModel
						} );

						// Save panels data when we close the dialog, if we're in a dialog
						var dialog = $$.closest('.so-panels-dialog-wrapper').data('view');
						if( typeof dialog !== 'undefined' ) {
							dialog.on('close_dialog', function(){
								builderModel.refreshPanelsData();
							} );

							dialog.on('open_dialog_complete', function(){
								// Make sure the new layout widget is always properly setup
								builderView.trigger('builder_resize');
							});

							dialog.model.on('destroy', function(){
								// Destroy the builder
								builderModel.emptyRows().destroy();
							} );

							// Set the parent for all the sub dialogs
							builderView.setDialogParents(soPanelsOptions.loc.layout_widget, dialog);
						}

						console.log('obj');
						console.log($$);

						// Basic setup for the builder
						var isWidget = Boolean( $$.closest('.widget-content').length );
						builderView
							.render()
							.attach( {
								container: $$,
								dialog: isWidget,
								type: $$.data('type')
							} )
							.setDataField( $$.find('input.panels_data') );

						if( isWidget ) {
							// Set up the dialog opening
							builderView.setDialogParents(soPanelsOptions.loc.layout_widget, builderView.dialog);
							$$.find( '.siteorigin-panels-display-builder').click(function(){
								builderView.dialog.openDialog();
							});
						}
						else {
							// Remove the dialog opener button, this is already being displayed in a page builder dialog.
							$$.find( '.siteorigin-panels-display-builder').parent().remove();
						}

						// Trigger a global jQuery event after we've setup the builder view
						$(document).trigger( 'panels_setup', builderView );
					});
				};


				/*
				 // Setup new widgets when they're added in the standard widget interface
				 $(document).on( 'widget-added', function(e, widget) {
				 $(widget).find('.siteorigin-page-builder-widget').soPanelsSetupBuilderWidget();
				 } );

				 // Setup existing widgets on the page (for the widgets interface)
				 if( !$('body').hasClass( 'wp-customizer' ) ) {
				 $( function(){
				 $('.siteorigin-page-builder-widget').soPanelsSetupBuilderWidget();
				 } );
				 }
				 * /

				if(typeof jQuery.fn.soPanelsSetupBuilderWidget2 != 'undefined' && !jQuery('body').hasClass('wp-customizer')) {
					console.log('lloool');
					jQuery( ".siteorigin-page-builder-field").soPanelsSetupBuilderWidget2();
				}
			})( jQuery );


		</script>

		<?php
		
	}
*/

	
	
	/*
	*  field_group_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is edited.
	*  Use this action to add CSS + JavaScript to assist your render_field_options() action.
	*
	*  @type	action (admin_enqueue_scripts)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*
	
	function field_group_admin_enqueue_scripts() {
		
	}
	
	*/

	
	/*
	*  field_group_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is edited.
	*  Use this action to add CSS and JavaScript to assist your render_field_options() action.
	*
	*  @type	action (admin_head)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*
	
	function field_group_admin_head() {
	
	}
	
	*/


	/*
	*  load_value()
	*
	*  This filter is applied to the $value after it is loaded from the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value found in the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*  @return	$value
	*/
	
	/*
	
	function load_value( $value, $post_id, $field ) {
		
		return $value;
		
	}
	
	*/
	
	
	/*
	*  update_value()
	*
	*  This filter is applied to the $value before it is saved in the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value found in the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*  @return	$value
	*/
	
	/*
	
	function update_value( $value, $post_id, $field ) {
		
		return $value;
		
	}
	
	*/
	
	
	/*
	*  format_value()
	*
	*  This filter is appied to the $value after it is loaded from the db and before it is returned to the template
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value which was loaded from the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*
	*  @return	$value (mixed) the modified value
	*/
		
	/*
	
	function format_value( $value, $post_id, $field ) {
		
		// bail early if no value
		if( empty($value) ) {
		
			return $value;
			
		}
		
		
		// apply setting
		if( $field['font_size'] > 12 ) { 
			
			// format the value
			// $value = 'something';
		
		}
		
		
		// return
		return $value;
	}
	
	*/
	
	
	/*
	*  validate_value()
	*
	*  This filter is used to perform validation on the value prior to saving.
	*  All values are validated regardless of the field's required setting. This allows you to validate and return
	*  messages to the user if the value is not correct
	*
	*  @type	filter
	*  @date	11/02/2014
	*  @since	5.0.0
	*
	*  @param	$valid (boolean) validation status based on the value and the field's required setting
	*  @param	$value (mixed) the $_POST value
	*  @param	$field (array) the field array holding all the field options
	*  @param	$input (string) the corresponding input name for $_POST value
	*  @return	$valid
	*/
	
	/*
	
	function validate_value( $valid, $value, $field, $input ){
		
		// Basic usage
		if( $value < $field['custom_minimum_setting'] )
		{
			$valid = false;
		}
		
		
		// Advanced usage
		if( $value < $field['custom_minimum_setting'] )
		{
			$valid = __('The value is too little!','acf-page_builder_field'),
		}
		
		
		// return
		return $valid;
		
	}
	
	*/
	
	
	/*
	*  delete_value()
	*
	*  This action is fired after a value has been deleted from the db.
	*  Please note that saving a blank value is treated as an update, not a delete
	*
	*  @type	action
	*  @date	6/03/2014
	*  @since	5.0.0
	*
	*  @param	$post_id (mixed) the $post_id from which the value was deleted
	*  @param	$key (string) the $meta_key which the value was deleted
	*  @return	n/a
	*/
	
	/*
	
	function delete_value( $post_id, $key ) {
		
		
		
	}
	
	*/
	
	
	/*
	*  load_field()
	*
	*  This filter is applied to the $field after it is loaded from the database
	*
	*  @type	filter
	*  @date	23/01/2013
	*  @since	3.6.0	
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	$field
	*/
	
	/*
	
	function load_field( $field ) {
		
		return $field;
		
	}	
	
	*/
	
	
	/*
	*  update_field()
	*
	*  This filter is applied to the $field before it is saved to the database
	*
	*  @type	filter
	*  @date	23/01/2013
	*  @since	3.6.0
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	$field
	*/
	
	/*
	
	function update_field( $field ) {
		
		return $field;
		
	}	
	
	*/
	
	
	/*
	*  delete_field()
	*
	*  This action is fired after a field is deleted from the database
	*
	*  @type	action
	*  @date	11/02/2014
	*  @since	5.0.0
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	n/a
	*/
	
	/*
	
	function delete_field( $field ) {
		
		
		
	}	
	
	*/
	
	
}


// create field
new acf_field_page_builder_field();

?>

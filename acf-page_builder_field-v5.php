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
		$this->category = 'content';

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

		$panels_data = array();

		return $panels_data;
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
        ACFPB()->render_admin_field( $field, 5 );
	}
	
}

// create field
new acf_field_page_builder_field();

?>

jQuery(function($){


    var cur_panel;

    $.fn.soPanelsSetupBuilderField2 = function ( panels ) {

        //className: "siteorigin-page-builder-field siteorigin-panels-builder-field"

        var fields = $('.siteorigin-page-builder-field');

        return this.each(function() {
            var $$ = $(this);

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
                builderView.setDialogParents(panelsOptions.loc.layout_widget, dialog);
            }

            if( $$.find('input.acf-panels-data').val() == "null" )
            {
                return ;
            }
            // Basic setup for the builder
            builderView
                .render()
                .attach( {
                    container: $$,
                    dialog: true,
                    type: $$.data('type')
                } )
                .setDataField( $$.find('input.acf-panels-data') );

            $$.data( 'view-id', builderView.cid );

            // Set up the dialog opening
            builderView.setDialogParents(panelsOptions.loc.layout_widget, builderView.dialog);

            $builder_id = $$.data('builder-id');

            $(this).find('.siteorigin-panels-display-builder-field').on( 'click', function(){
                //$('#siteorigin-page-builder-widget-' + $builder_id).on( 'click', function(){
                //$$.find( '.siteorigin-panels-display-builder-field').click(function(){

                $$.closest('.acf-field-page-builder-field').data( 'view-id', builderView.cid );

                builderView.dialog.openDialog();
            });

            // Trigger a global jQuery event after we've setup the builder view
            $(document).trigger( 'panels_setup', builderView );
        });
    };

    var panels = window.siteoriginPanels;

    if(typeof jQuery.fn.soPanelsSetupBuilderField2 != 'undefined') {
        //console.log('running');
        jQuery( ".siteorigin-page-builder-field").soPanelsSetupBuilderField2( panels );
    }




	function initialize_field( $el ) {
		var panels = window.siteoriginPanels;
		if(typeof jQuery.fn.soPanelsSetupBuilderField2 != 'undefined') {
			jQuery( ".siteorigin-page-builder-field").soPanelsSetupBuilderField2( panels );
		}
	}

	if( typeof acf.add_action !== 'undefined' ) {

		/*
		*  ready append (ACF5)
		*
		*  These are 2 events which are fired during the page load
		*  ready = on page load similar to $(document).ready()
		*  append = on new DOM elements appended via repeater field
		*
		*  @type	event
		*  @date	20/07/13
		*
		*  @param	$el (jQuery selection) the jQuery element which contains the ACF fields
		*  @return	n/a
		*/
		acf.add_action('ready append', function( $el ){
			// search $el for fields of type 'page_builder_field'
			acf.get_fields({ type : 'page_builder_field'}, $el).each(function(){
				initialize_field( $(this) );
			});
		});

	} else {

		/*
		*  acf/setup_fields (ACF4)
		*
		*  This event is triggered when ACF adds any new elements to the DOM.
		*
		*  @type	function
		*  @since	1.0.0
		*  @date	01/01/12
		*
		*  @param	event		e: an event object. This can be ignored
		*  @param	Element		postbox: An element which contains the new HTML
		*
		*  @return	n/a
		*/
		$(document).on('acf/setup_fields', function(e, postbox){
			$(postbox).find('.field[data-field_type="page_builder_field"]').each(function(){
				initialize_field( $(this) );
			});
		});

	}

});

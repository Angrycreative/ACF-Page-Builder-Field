/**
 * This script initializes the page builder in the admin
 */


jQuery(function(){

    $ = jQuery;

    $.fn.soPanelsSetupBuilderField2 = function ( panels ) {

        /*
        console.log('panels');
        console.log(panels);
        console.log('inside22');
        console.log(this);
        */
        return this.each(function() {
            var $$ = $(this);

            //alert('loop');
            //console.log($(this));

            /*
             var widgetId = $$.closest('form').find('.widget-id').val();

             console.log('widgetid');
             console.log(widgetId);
             // Exit if this isn't a real widget
             if( typeof widgetId !== 'undefined' && widgetId.indexOf('__i__') > -1 ) {
             return;
             }
             */

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

            /*
            console.log('obj');
            console.log($$);
            console.log($$.find('input.acf-panels-data'));
            console.log($$.find('input.acf-panels-data').val());
            */

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

            /*
            console.log('soPanelsOptions');
            console.log(soPanelsOptions);
            */

            // Set up the dialog opening
            builderView.setDialogParents(soPanelsOptions.loc.layout_widget, builderView.dialog);

            /*
            console.log('register click');
            console.log($$.find('.siteorigin-panels-display-builder-field'));
            console.log($$.find('.siteorigin-panels-display-builder-field'));
            */
            $builder_id = $$.data('builder-id');
            /*
            console.log($builder_id);
            console.log('#siteorigin-page-builder-widget-' + $builder_id);
            console.log($('#siteorigin-page-builder-widget-' + $builder_id));

            //alert($builder_id);
            */

            $('#siteorigin-page-builder-widget-' + $builder_id).on( 'click', function(){
                //$$.find( '.siteorigin-panels-display-builder-field').click(function(){
                /*
                console.log('click + open');

                console.log(this);
                console.log($(this));
                console.log(builderView);
                */
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

});
<?php
/** @var string $textarea_id */

use WebXID\BotsMaster\Tpl;

wp_enqueue_script('ckeditor', Tpl::pluginUrl( 'assets/admin/js/ckeditor5.min.js'));
wp_add_inline_script( 'ckeditor', "
    ClassicEditor
        .create( document.querySelector( '#{$textarea_id}' ), {
            toolbar: [ 'removeFormat'],
            toolbar: ['bold', 'italic', 'underline', 'strikethrough', '|', 'code', 'codeBlock', '|', 'SourceEditing', '|', 'undo', 'redo'],
            removePlugins: ['list'],
            autoParagraph: false,
            codeBlock: {
                languages: [
                    {language: 'code', label: 'Code'},
                ]
            }
        })
        .then( editor => {
            editor.model.schema.setAttributeProperties( 'linkHref', {
                isFormatting: true
            } );

            editor.editing.view.document.on( 'enter', ( evt, data ) => {
                if ( data.isSoft ) {
                    editor.execute( 'enter' );
                } else {
                    editor.execute( 'shiftEnter' );
                }

                data.preventDefault();
                evt.stop();
                editor.editing.view.scrollToTheSelection();
            }, { priority: 'high' } );
        } )
        .catch( error => {
            console.error( error );
        } );
");


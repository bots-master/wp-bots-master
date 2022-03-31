import ClassicEditor from '@ckeditor/ckeditor5-editor-classic/src/classiceditor';
import Bold from '@ckeditor/ckeditor5-basic-styles/src/bold';
import Code from '@ckeditor/ckeditor5-basic-styles/src/code';
import CodeBlock from '@ckeditor/ckeditor5-code-block/src/codeblock';
import Essentials from '@ckeditor/ckeditor5-essentials/src/essentials';
import Italic from '@ckeditor/ckeditor5-basic-styles/src/italic';
import Paragraph from '@ckeditor/ckeditor5-paragraph/src/paragraph';
import Strikethrough from '@ckeditor/ckeditor5-basic-styles/src/strikethrough';
import Underline from '@ckeditor/ckeditor5-basic-styles/src/underline';


document.querySelectorAll( '.wx-bots-master-ckeditor' ).forEach(function (el) {
    ClassicEditor
        .create( el, {
            plugins: [
                Bold,
                Code,
                CodeBlock,
                Essentials,
                Italic,
                Paragraph,
                Strikethrough,
                Underline
            ],
            toolbar: ['bold', 'italic', 'underline', 'strikethrough', '|', 'code', 'codeBlock', '|', 'undo', 'redo'],
            removePlugins: ['list'],
            autoParagraph: false,
            codeBlock: {
                languages: [
                    {language: 'code', label: 'Code'},
                ]
            }
        } )
        .then(editor => {
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

            editor.editing.view.document.on( 'clipboardInput', ( evt, data ) => {
                let str = data.dataTransfer.getData('text/plain');
                str = str.replace(/(?:\r\n|\r|\n)/g, '<br>')

                data.content = editor.data.htmlProcessor.toView(str);
            }, { priority: 'high' } );

            editor.model.schema.setAttributeProperties( 'clipboardInput', {
                isFormatting: true
            }, { priority: 'high' } );
        })
        .catch( error => {
            console.error( error.stack );
        } );
});

<?php
/** @var string $textarea_id */

wp_enqueue_script('ckeditor', plugins_url( '/bots-master/assets/admin/js/ckeditor/ckeditor.js'));
wp_add_inline_script( 'ckeditor', "
    var editor = CKEDITOR.replace('{$textarea_id}', {
        extraPlugins: 'codeTag',
        toolbar: [
            {
                name: 'basicstyles',
                items: [
                    'Bold',
                    'Italic'
                ]
            },
            {
                name: 'links',
                items: [
                    'Link',
                    'Unlink'
                ]
            },
            {
                name: 'code',
                items: [
                    'Code'
                ]
            }
        ],
        allowedContent: {
            'b i strong em code pre': {
                attributes: ''
            },
            a: {
                attributes: '!href'
            }
        },
        enterMode: CKEDITOR.ENTER_BR,
        shiftEnterMode: CKEDITOR.ENTER_BR,
        on: {
            dialogDefinition: function (evt) {
                var dialogName = evt.data.name;
                var dialogDefinition = evt.data.definition;

                dialogDefinition.resizable = CKEDITOR.DIALOG_RESIZE_NONE;

                if (dialogName == 'link') {
                    var infoTab = dialogDefinition.getContents('info');
                    var urlOptions = infoTab.get('urlOptions');

                    dialogDefinition.minHeight = 130;

                    var linkType = infoTab.get('linkType');
                    linkType.style = 'display:none;';
                }
            },
            change: function (evt) {
                return;
                if (!window.in_processing) {
                    window.in_processing = true;

                    var \$selection = this.getSelection().getCommonAncestor();

                    if (\$selection.type != CKEDITOR.NODE_TEXT && !\$selection.is('body')) {
                        var \$selection_parent = \$selection.getParent();
                        var range = this.createRange();

                        if (!\$selection_parent.is('body')) {
                            if (\$selection_parent.getChildCount() == 1) {
                                \$selection.remove(true);

                                range.selectNodeContents(\$selection_parent);
                            } else {
                                /* TODO: fix the known bug */
                                \$selection.breakParent(\$selection_parent);

                                range.selectNodeContents(\$selection);
                            }

                            this.getSelection().selectRanges([range]);
                        } else if (\$selection.getChildCount() > 1) {
                            var \$selection_children = \$selection.getChildren();

                            for (var i = 0; i < \$selection_children.count(); i++) {
                                var \$child = \$selection_children.getItem(i);

                                if (\$child.type != CKEDITOR.NODE_TEXT && !\$child.is('br')) {
                                    \$child.remove(true);
                                }
                            }

                            range.selectNodeContents(\$selection);
                            this.getSelection().selectRanges([range]);
                        }
                    }

                    window.in_processing = false;
                }
            }
        }
    });
");


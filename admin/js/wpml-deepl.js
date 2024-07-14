(function( $ ) {
	'use strict';

	window.DeeplWpml = {
        init: function () {

			jQuery('#icl_tm_editor').find( '.postbox' ).find( '.wpml-form-row' ).each( function() {
                
                const currentItem = jQuery( this );
                const deeplWrap = '<div  style="float:right;margin-top:5px;"><a href="javascript:;" class="deepl-trigger">Translate using DeepL</a></div>';

                currentItem.find( '.field_translation_complete' ).append( deeplWrap );
            });

            jQuery( 'body' ).on( 'click', 'a.deepl-trigger',  async function() {

                let originalValue = jQuery( this ).closest('.wpml-form-row').find('.original_value').find( 'textarea' ).val();
                const targetLang = jQuery( 'body' ).find( 'input[name="target_lang"]').val();

                let inputType = 'wysiwyg';

                if ( ! originalValue ) {

                    if ( jQuery( this ).closest('.wpml-form-row').find('textarea.original_value').length > 0 ) {
                        originalValue = jQuery( this ).closest('.wpml-form-row').find('textarea.original_value').val();
                        inputType = 'textarea';
                    } else if ( jQuery( this ).closest('.wpml-form-row').find('input.original_value').length > 0 ) {
                        originalValue = jQuery( this ).closest('.wpml-form-row').find('input.original_value').val();
                        inputType = 'input';
                    }

                    
                }

                if ( originalValue ) {

                    jQuery( this ).prepend( `<img src="${window.location.origin}/wp-admin/images/loading.gif" />`);

                    const response = await fetch(
                        `${deepl_ajax_data.rest_root}deeplwpml/v1/getdeepltranslations/?orig_text=${encodeURIComponent(originalValue)}&target_lang=${targetLang}`,
                        {
                        method: "GET",
                        headers: {
                            "X-WP-Nonce": deepl_ajax_data.rest_nonce
                        }
                        }
                    );
                    const deeplResponse = await response.json();

                    if ( deeplResponse && deeplResponse.data ) {

                        if ( deeplResponse.data.result && deeplResponse.data.result.text ) {

                            if ( inputType === 'wysiwyg' ) {
                                jQuery( this ).closest('.wpml-form-row').find('.translated_value').find( 'textarea' ).val( deeplResponse.data.result.text );
                            } else if ( inputType === 'textarea' ) {
                                jQuery( this ).closest('.wpml-form-row').find('textarea.translated_value').val( deeplResponse.data.result.text );
                            } else if ( inputType === 'input' ) {
                                jQuery( this ).closest('.wpml-form-row').find('input.translated_value').val( deeplResponse.data.result.text );
                            }

                            
                        }
                    }

                    jQuery( this ).find( 'img' ).remove();

                }
                
                //jQuery( this ).closest('.wpml-form-row').find('.translated_value').find( 'textarea' ).val('Translated text from DeepL');

            });

        },

    };

	jQuery( document ).ready( function ( ) {

        setTimeout(function(){
            DeeplWpml.init();
        }, 1000);
        
    } );

	

})( jQuery );

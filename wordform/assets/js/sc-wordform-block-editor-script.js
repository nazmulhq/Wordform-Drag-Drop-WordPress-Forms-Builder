jQuery(document).ready(function($) {	
	console.log('SC-WORDFORM-BLOCK-EDITOR-OBJECT');
	console.log(sc_wordform_block_editor_misc_script_obj);	
	
	
	
	$('body').on("change", ".sc-wordform-block-editor-select-form", function() {
		let scBlockSelectFormID	=	$(this).val();
		console.log(scBlockSelectFormID);			
		$.ajax({
		  type : "POST",		  
		  url  : sc_wordform_block_editor_misc_script_obj.ajax_url,
		  data : {			    
				action 	 			: 'sc_wordform_render_selected_form_in_block_editor',
				security 			: sc_wordform_block_editor_misc_script_obj.nonce,
			    WordFormID			: scBlockSelectFormID,			    
				},		  
		  success: function(data) { 
			 console.log(data); 
			 jsonData	= JSON.parse( data );			 
			 console.log(jsonData ); 
			 $('.sc-wordform-rendered-created-form-wrapper').html( jsonData.builtForm ); 			 
			},
		  error: function( xhr, status, error ) { 
			 console.log(xhr); 
			 console.log(status); 
			 console.log(error); 				 
			}
		});							
									
	});
	
	
	
	
	
	
}); // $(document)
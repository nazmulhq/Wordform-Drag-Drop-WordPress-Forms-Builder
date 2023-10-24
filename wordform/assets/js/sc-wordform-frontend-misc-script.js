jQuery(document).ready(function($) {	
	console.log('SC-WORDFORM-FRONTEND-OBJECT');
	console.log(sc_wordform_frontend_misc_script_obj);	
	
	
	
	$('.sc-wordform-built-created-form-frontend-display-form').on("submit", function() {
		// Keep disabled submit button
		let scWordformWrapper				= $(this);
		scWordformWrapper.find('button[type="submit"]').attr('disabled', true );
		
		let scWordFormOuterMostDivWrapper	=  $('.sc-wordform-built-form-frontend-outermost-div-wrapper');
		let scWordFormID					=  $(this).prop('id');
		console.log(scWordFormID);
		let scWordFormData					=  $(this).serialize();
		console.log(scWordFormData);
		let scWordFormName					=  $(this).data('scwordform-form-name');	
		console.log(scWordFormName);				
		
		// Remove form submission report messages if exist
		$('.sc-wordform-submission-success-msg-div-wrapper,.sc-wordform-submisson-fail-msg-div-wrapper').remove();
		
		$.ajax({
		  type : "POST",		  
		  url  : sc_wordform_frontend_misc_script_obj.ajax_url,
		  data : {			    
				action 	 			: 'sc_wordform_created_form_submission',
				security 			: sc_wordform_frontend_misc_script_obj.nonce,
			    WordFormName		: scWordFormName,			    
			    WordFormID			: scWordFormID,			    
			    scWordFormPostData 	: scWordFormData
				},		  
		  success: function(data) { 
			 console.log(data); 
			 jsonData	= JSON.parse( data );			 
			 //console.log(jsonData ); 
			  // Fail
			 if ( jsonData.status == 'fail' ) {
				 if ( jsonData.validationErrorData ) {
				 $.each( jsonData.validationErrorData, function( key, val ) {
					 console.log(val.validationMsgClass );
					 $('.'+val.validationMsgClass).text(val.validationMsg);
				 });
				 }
				 else {
					 scWordFormOuterMostDivWrapper.prepend(jsonData.failMsg);
					 scWordFormOuterMostDivWrapper[0].scrollIntoView();
				 }
				 scWordformWrapper.find('button[type="submit"]').removeAttr('disabled');
			 } 
			 // Success
			 else {
				 $(this).slideUp(500).promise().done(function() {
					 scWordFormOuterMostDivWrapper.html(jsonData.successMsg);
					 scWordFormOuterMostDivWrapper[0].scrollIntoView();
					 scWordformWrapper.find('button[type="submit"]').removeAttr('disabled');
				 });
			 } 
			},
		  error: function( xhr, status, error ) { 
			 console.log(xhr); 
			 console.log(status); 
			 console.log(error); 				 
			}
		});						
		//console.log('JSONDATA: '+jsonData);
							
		return false;
	});
	
	
	
	
	
	
}); // $(document)
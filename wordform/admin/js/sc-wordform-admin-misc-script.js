
jQuery(document).ready(function($) {				
	console.log('Localized WordForm Data');	
	console.log( sc_wordai_metabox_script_obj );	
	console.log('jQuery Version: '+ $().jquery );
	
	// Drag Drop Sortable Elements
	$('#wordform-sortable').sortable();
	
	// Dragggable Config
	$('.draggable-element').draggable({
		helper: "clone",
		cursor: "crosshair",
		cancel: false,		
	});
	
	
	// Dropped elements
	$( ".wordform-dropzone-div-wrapper" ).droppable({
	  drop: function( event, ui ) {
		//alert( "dropped" );
		console.log(event);
		console.log(ui);  
		console.log( ui.draggable.attr("id") );  
		let draggableElementClasses	=	ui.draggable.attr("class"); 
		console.log( draggableElementClasses );
		  
		let classesArr  = draggableElementClasses.split(" ");
		console.log(classesArr);	
		  
		// Remove Dropzone text
		 $('.sc-wordform-dropzone-watermark-text').remove(); 
				  		  
		  
		if ( draggableElementClasses.match("wordform-type-singletext") ) {
			console.log('Text');
			createSingleLineText();
		}
		if ( draggableElementClasses.match("wordform-type-singlenumber") ) {
			console.log('Number');
			createInputNumber();
		}		  
		if ( draggableElementClasses.match("wordform-type-multitext") ) {
			console.log('Textarea');
			createTextarea();
		}
		if ( draggableElementClasses.match("wordform-type-select") ) {
			console.log('Select');
			createDropdownSelect();
		}				  
		if ( draggableElementClasses.match("wordform-type-checkbox") ) {
			console.log('Checkbox');
			createInputCheckbox();
		}
		if ( draggableElementClasses.match("wordform-type-radio") ) {
			console.log('Radio');
			createInputRadio();
		}
		  
		  
	    //wordformSubmitButton();	  
	  } // function( event, ui )
		
	});	
	
	
	
	// Create Element Index Number
	function createElementNumber() {
		let WordFormBuilderElementNum	= parseInt( $('.wordform-form-builder-element-number').val() );
		let elementNumber				= 0;
		if ( $.trim( WordFormBuilderElementNum ).length != 0 ) {
			elementNumber               = parseInt( WordFormBuilderElementNum+1 );
			$('.wordform-form-builder-element-number').val( elementNumber );
		}
		else {
			elementNumber				= DroppabaleElementWrapper.children().length;
			elementNumber				= parseInt(elementNumber+1);
			$('.wordform-form-builder-element-number').val(elementNumber);
		}
		return elementNumber;
	}
		
	
	/*** Create Standard Elements ***/
	
	// Text: Create Input Text - Single Line Text
	function createSingleLineText() {
		let DroppabaleElementWrapper	= $('.wordform-dropzone-div-wrapper');
		let elementNumber				=	createElementNumber();
		console.log( elementNumber );
		let AttrID						= 'wordformElement-'+ elementNumber;
		let elementOptionsWrapperID		= 'wordformElementOptions-'+ elementNumber;
		DroppabaleElementWrapper.append('<div id="'+AttrID+'" class="wordform-input-element-div-wrapper" data-input-element-type="text" data-element-index="'+elementNumber+'"> <span class="wordform-element-delete dashicons dashicons-trash" title="Remove"></span> <div class="wordform-dropped-text-label">Text Label</div> <ul class="wordform-dropped-element-text-field-options"><li><input class="wordform-dropped-text-name" name="wordform-dropped-text-name" type="text" readonly /> </li></ul>  </div>');
		renderElementOptions('text', elementOptionsWrapperID, elementNumber );   						
	}
	
	// Number: Create Input Number
	function createInputNumber() {
		let DroppabaleElementWrapper	= $('.wordform-dropzone-div-wrapper');
		let elementNumber				=	createElementNumber();
		console.log( elementNumber );
		let AttrID						= 'wordformElement-'+ elementNumber;
		let elementOptionsWrapperID		= 'wordformElementOptions-'+ elementNumber;
		DroppabaleElementWrapper.append('<div id="'+AttrID+'" class="wordform-input-element-div-wrapper" data-input-element-type="number" data-element-index="'+elementNumber+'"> <span class="wordform-element-delete dashicons dashicons-trash" title="Remove"></span> <div class="wordform-dropped-number-label">Number Label</div> <ul class="wordform-dropped-element-number-field-options"><li><input class="wordform-dropped-number-name" name="wordform-dropped-number-name" type="number" readonly /> </li></ul>  </div>');
		renderElementOptions('number', elementOptionsWrapperID, elementNumber );   						
	}
	
	
	// Textarea: Create Input Textarea - Multi Line Text
	function createTextarea() {
		let DroppabaleElementWrapper	= $('.wordform-dropzone-div-wrapper');
		let elementNumber				=	createElementNumber();
		console.log( elementNumber );
		let AttrID						= 'wordformElement-'+ elementNumber;
		let elementOptionsWrapperID		= 'wordformElementOptions-'+ elementNumber;
		DroppabaleElementWrapper.append('<div id="'+AttrID+'" class="wordform-input-element-div-wrapper" data-input-element-type="textarea" data-element-index="'+elementNumber+'"> <span class="wordform-element-delete dashicons dashicons-trash" title="Remove"></span> <div class="wordform-dropped-textarea-label">Textarea Label</div> <ul class="wordform-dropped-element-textarea-field-options"><li> <textarea class="wordform-dropped-textarea-name" name="wordform-dropped-textarea-name" readonly></textarea> </li></ul>  </div>');
		renderElementOptions('textarea', elementOptionsWrapperID, elementNumber );   						
	}
	
	
	// Radio: Create Input Radio
	function createInputRadio() {
		let DroppabaleElementWrapper	= $('.wordform-dropzone-div-wrapper');
		let elementNumber				= createElementNumber();
		console.log( elementNumber );
		let AttrID						= 'wordformElement-'+ elementNumber;
		let elementOptionsWrapperID		= 'wordformElementOptions-'+ elementNumber;
		DroppabaleElementWrapper.append('<div id="'+AttrID+'" class="wordform-input-element-div-wrapper" data-input-element-type="radio" data-element-index="'+elementNumber+'"> <span class="wordform-element-delete dashicons dashicons-trash" title="Remove"></span> <div class="wordform-dropped-radio-label">Multiple Options Label</div> <ul class="wordform-dropped-element-radio-field-options"><li><input class="wordform-dropped-radio-name" name="wordform-dropped-radio-name-'+elementNumber+'" type="radio" disabled="disabled" /><span>Option Text</span> </li></ul>  </div>');
		renderElementOptions('radio', elementOptionsWrapperID, elementNumber );   				
	}
	
	// Select: Create Dropdown Select
	function createDropdownSelect() {
		let DroppabaleElementWrapper	= $('.wordform-dropzone-div-wrapper');
		let elementNumber				= createElementNumber();
		console.log( elementNumber );
		let AttrID						= 'wordformElement-'+ elementNumber;
		let elementOptionsWrapperID		= 'wordformElementOptions-'+ elementNumber;
		DroppabaleElementWrapper.append('<div id="'+AttrID+'" class="wordform-input-element-div-wrapper" data-input-element-type="select" data-element-index="'+elementNumber+'"> <span class="wordform-element-delete dashicons dashicons-trash" title="Remove"></span> <div class="wordform-dropped-select-label">Dropdown Label</div> <ul class="wordform-dropped-element-select-field-options"><li> <select class="wordform-dropped-select-name" name="wordform-dropped-select-name" disabled="disabled"><option value="Select Text">Select Text</option></select> </li></ul>  </div>');
		renderElementOptions('select', elementOptionsWrapperID, elementNumber );   				
	}
	
	// Checkbox: Create Input Checkbox
	function createInputCheckbox() {
		let DroppabaleElementWrapper	= $('.wordform-dropzone-div-wrapper');
		let elementNumber				= createElementNumber();
		console.log( elementNumber );
		let AttrID						= 'wordformElement-'+ elementNumber;
		let elementOptionsWrapperID		= 'wordformElementOptions-'+ elementNumber;
		DroppabaleElementWrapper.append('<div id="'+AttrID+'" class="wordform-input-element-div-wrapper" data-input-element-type="checkbox" data-element-index="'+elementNumber+'"> <span class="wordform-element-delete dashicons dashicons-trash" title="Remove"></span> <div class="wordform-dropped-checkbox-label">Checkbox Label</div> <ul class="wordform-dropped-element-checkbox-field-options"><li><input class="wordform-dropped-checkbox-name" name="wordform-dropped-checkbox-name" type="checkbox" disabled="disabled" /><span>Checkbox Text</span> </li></ul>  </div>');
		renderElementOptions('checkbox', elementOptionsWrapperID, elementNumber );   				
	}
	
	
	/** All Common ***/
	
	// All: Render element options update template
	function renderElementOptions( elementType, elementOptionsWrapperID, elementIndex ) {
		let actionName			= 'sc_wordform_render_element_options_type_'+elementType;
		console.log(actionName);
		let jsonData			= '';
		$.ajax({
		  type : "POST",		  
		  url  : sc_wordform_metabox_script_obj.adminajax_url,
		  data : {			    
				action 	 			: actionName,
				security 			: sc_wordform_metabox_script_obj.nonce,
			    elementWrapperID 	: elementOptionsWrapperID
				},		  
		  success: function(data) { 
			 console.log(data); 
			 jsonData	= JSON.parse( data );			 
			 console.log(jsonData ); 
			 switch( elementType ) {
				 case 'radio':
					 $('.wordform-builder-form-options-div-wrapper').append(jsonData.fieldEditOptionsHtml); 
					 showHideRadioOptions( elementOptionsWrapperID );
					 setSelectedDroppedElementBackground( elementIndex );
					 break;
				 case 'checkbox':
					 $('.wordform-builder-form-options-div-wrapper').append(jsonData.fieldEditOptionsHtml); 
					 showHideCheckboxOptions( elementOptionsWrapperID );
					 setSelectedDroppedElementBackground( elementIndex );
					 break;					 
				 case 'select':
					 $('.wordform-builder-form-options-div-wrapper').append(jsonData.fieldEditOptionsHtml); 
					 showHideSelectOptions( elementOptionsWrapperID );
					 setSelectedDroppedElementBackground( elementIndex );
					 break;					 
				 case 'text':
					 $('.wordform-builder-form-options-div-wrapper').append(jsonData.fieldEditOptionsHtml); 
					 showHideTextOptions( elementOptionsWrapperID );	
					 setSelectedDroppedElementBackground( elementIndex );
					 break;
				 case 'number':
					 $('.wordform-builder-form-options-div-wrapper').append(jsonData.fieldEditOptionsHtml); 
					 showHideNumberOptions( elementOptionsWrapperID );	
					 setSelectedDroppedElementBackground( elementIndex );
					 break;					 
				 case 'textarea':
					 $('.wordform-builder-form-options-div-wrapper').append(jsonData.fieldEditOptionsHtml); 
					 showHideTextareaOptions( elementOptionsWrapperID );	
					 setSelectedDroppedElementBackground( elementIndex );
					 break;
					 
			 } 
			},
		  error: function( xhr, status, error ) { 
			 console.log(xhr); 
			 console.log(status); 
			 console.log(error); 				 
			}
		});				
		//return jsonData;
		console.log('JSONDATA: '+jsonData);
	}
	
	// All: set Checkbox attr on change
	$('body').on('change', 'input[type="checkbox"]', function() {
		if ( $(this).is(":checked") ) {
			$(this).attr('checked', true );			
		}
		else {
			$(this).attr('checked', false );		
		}
	});
	
	// All: set all Text attr on change
	$('body').on('input', 'input[type="text"]', function() {
		$(this).attr('value', $(this).val() );
	});

	
	// All: set all elected element backgorund
	function setSelectedDroppedElementBackground( elementIndex ) {
		$('.wordform-input-element-div-wrapper').css('background-color', '');
		$('.wordform-input-element-div-wrapper').promise().done(function() {
			$('#wordformElement-'+elementIndex).css('background-color', 'aliceblue');	
		});		
	}		
	
	// All: On Click / Select Dropped Element - Show-Hide corresponding edit options data
	$('body').on("click", ".wordform-input-element-div-wrapper", function() {
		let elementIndex				= $(this).data('element-index');
		let elementType					= $(this).data('input-element-type');
		let elementOptionsWrapperID		= 'wordformElementOptions-'+elementIndex;
		switch ( elementType ) {
			case 'radio':
				showHideRadioOptions( elementOptionsWrapperID );
				break;
			case 'checkbox':
				showHideCheckboxOptions( elementOptionsWrapperID );
				break;				
			case 'text':
				showHideTextOptions( elementOptionsWrapperID );
				break;
			case 'number':
				showHideNumberOptions( elementOptionsWrapperID );
				break;				
			case 'number':
				showHideNumberOptions( elementOptionsWrapperID );
				break;				
			case 'textarea':
				showHideTextOptions( elementOptionsWrapperID );
				break;
			case 'select':
				showHideSelectOptions( elementOptionsWrapperID );
				break;				
				
		}
		// All: Common on click select dropped element
		setSelectedDroppedElementBackground(elementIndex);		
	});
	
	
	// All: Remove Dropped Input Element
	$('body').on("click", ".wordform-element-delete", function() {
		let confirmMsg	= confirm('Remove the element?');
		if ( confirmMsg ) {			
			let elementIndex	= $(this).parent().prop("id").split("-")[1];
			$('#wordformElementOptions-'+elementIndex).remove();
			$(this).parent().remove();
		}
	});
	
	
	
	
	
	
	/*** Text ***/
	
	// Text: Hide all text options wrapper - then show only the working one
	function showHideTextOptions( elementOptionsWrapperID ) {
		 $('.show-hide-common-class-all-options-wrapper-element').css('display', 'none');
		 $('.show-hide-common-class-all-options-wrapper-element').promise().done(function() {			 
			 $('#'+elementOptionsWrapperID).fadeIn();
		 }); 		
	}
	
	// Text: EditText Label Text
	$('body').on("input", ".wordform-text-label-name", function() {
		let labelName		= $(this).val();
		let elementIndex	= $(this).closest('.wordform-text-field-options-wrapper').prop("id").split("-")[1];														
		$('#wordformElement-'+elementIndex+' .wordform-dropped-text-label').html(labelName);		
	});
	
	/*** Number ***/
	
	// Number: Hide all number options wrapper - then show only the working one
	function showHideNumberOptions( elementOptionsWrapperID ) {
		 $('.show-hide-common-class-all-options-wrapper-element').css('display', 'none');
		 $('.show-hide-common-class-all-options-wrapper-element').promise().done(function() {			 
			 $('#'+elementOptionsWrapperID).fadeIn();
		 }); 		
	}
	
	// Number: Edit Number Label Text
	$('body').on("input", ".wordform-number-label-name", function() {
		let labelName		= $(this).val();
		let elementIndex	= $(this).closest('.wordform-number-field-options-wrapper').prop("id").split("-")[1];														
		$('#wordformElement-'+elementIndex+' .wordform-dropped-number-label').html(labelName);		
	});
	
		
	
	/*** Textarea ***/
	// Textarea: Hide all textarea options wrapper - then show only the working one
	function showHideTextareaOptions( elementOptionsWrapperID ) {
		 $('.show-hide-common-class-all-options-wrapper-element').css('display', 'none');
		 $('.show-hide-common-class-all-options-wrapper-element').promise().done(function() {			 
			 $('#'+elementOptionsWrapperID).fadeIn();
		 }); 		
	}
	
	// Text: EdiText Label Text
	$('body').on("input", ".wordform-textarea-label-name", function() {
		let labelName		= $(this).val();
		let elementIndex	= $(this).closest('.wordform-textarea-field-options-wrapper').prop("id").split("-")[1];														
		$('#wordformElement-'+elementIndex+' .wordform-dropped-textarea-label').html(labelName);		
	});
	
	
	
	/*** Radio ***/
	
	// Radio: Hide Radio element options wrapper - then show only the working one
	function showHideRadioOptions( elementOptionsWrapperID ) {
		 $('.show-hide-common-class-all-options-wrapper-element').css('display', 'none');
		 $('.show-hide-common-class-all-options-wrapper-element').promise().done(function() {			 
			 $('#'+elementOptionsWrapperID).fadeIn();
		 }); 		
	}
			
	// Radio: Add more Radio Option
	$('body').on("click", ".wordform-add-radio-option", function() {
		let elementIndex	= $(this).closest('.wordform-radio-field-options-wrapper').prop("id").split("-")[1];												
		
		// Add to field options
		$(this).parent().prev().after('<li> <input class="wordform-edit-radio-name" name="wordform-edit-radio-name-'+elementIndex+'" type="radio" /><input class="wordform-input-radio-label-name" type="text" placeholder="Option Text" /> <span class="wordform-radio-field-remove dashicons dashicons-no" title="Remove"></span> </li> ');
				
		// Add to dropped zone element		
		$('#wordformElement-'+elementIndex+ ' .wordform-dropped-element-radio-field-options').append('<li><input class="wordform-dropped-radio-name" name="wordform-dropped-radio-name-'+elementIndex+'" type="radio" disabled="disabled" /><span>Option Text</span> </li>');		
	});
	
	// Radio: On change/ Edit update radio options label text
	$('body').on("input", ".wordform-input-radio-label-name", function() {
		let elementIndex	= $(this).closest('.wordform-radio-field-options-wrapper').prop("id").split("-")[1];				
		let liElementnth	= $(this).parent().index();
		let labelName		= $(this).val();		
		//$('.wordform-dropped-element-radio-field-options li:eq('+liElementnth+') span').html(labelName);
		$('#wordformElement-'+elementIndex+' li:eq('+liElementnth+') span').html(labelName);
	});
	
	// Radio: Remove Radio Option Field
	$('body').on("click", ".wordform-radio-field-remove", function() {
		let parentULLength		= $(this).closest('.wordform-field-options').find('li').length;
		console.log('UL Length:'+ parentULLength );
		if ( parentULLength == 2 ) {
			alert('Minimum 1 Option Required!');
		}
		else {		
			let liElementnth	= $(this).parent().index();		
			$('.wordform-dropped-element-radio-field-options li:eq('+liElementnth+')').remove();
			$(this).parent().remove();
		}
	})
	
	// Radio: Click/Select radio box
	$('body').on("click", ".wordform-edit-radio-name", function() {
		let liElementnth	= $(this).parent().index();
		let elementIndex	= $(this).closest('.wordform-radio-field-options-wrapper').prop("id").split("-")[1];
		
		// Find Radio group name by name attribute
		let editOptionsGroupName		= $(this).prop('name');
		let droppedElementGroupName		= $('#wordformElement-'+elementIndex+' .wordform-dropped-element-radio-field-options li input').prop('name');
		console.log(editOptionsGroupName);
		console.log(droppedElementGroupName);
		// Element options
		$('input[name="'+ editOptionsGroupName +'"]').each(function(index, elem ) {
			console.log('Element Index: '+ index);
			if ( $(this).is(":checked") ) {
				$(this).attr('checked', true);
				$('#wordformElement-'+elementIndex+' .wordform-dropped-element-radio-field-options li:eq('+liElementnth+') input').attr("checked", true );
			}
			else {
				$(this).attr('checked', false );
			}
		});
		// Dropped Radio element options
		$('input[name="'+ droppedElementGroupName +'"]').each(function(index, elem ) {
			if ( $(this).is(":checked") ) {
				$(this).attr('checked', true);
			}						
			else {
				$(this).attr('checked', false);
			}
		});
		
		//if ( $(this).is(":checked") ) {
			//$('#wordformElement-'+elementIndex+' .wordform-dropped-element-radio-field-options li:eq('+liElementnth+') input').prop("checked", true );
			//$('#wordformElement-'+elementIndex+' .wordform-dropped-element-radio-field-options li:eq('+liElementnth+') input').attr("checked", true );
		//}
	});
	
	// Radio: Edit Radio Label Text
	$('body').on("input", ".wordform-radio-label-name", function() {
		let labelName		= $(this).val();
		let elementIndex	= $(this).closest('.wordform-radio-field-options-wrapper').prop("id").split("-")[1];														
		$('#wordformElement-'+elementIndex+' .wordform-dropped-radio-label').html(labelName);		
	});
	
	
	/*** Checkbox ***/
	
	// Checkbox: Hide Checkbox element options wrapper - then show only the working one
	function showHideCheckboxOptions( elementOptionsWrapperID ) {
		 $('.show-hide-common-class-all-options-wrapper-element').css('display', 'none');
		 $('.show-hide-common-class-all-options-wrapper-element').promise().done(function() {			 
			 $('#'+elementOptionsWrapperID).fadeIn();
		 }); 		
	}
	
	// Checkbox: Edit Checkbox Label Text
	$('body').on("input", ".wordform-checkbox-label-name", function() {
		let labelName		= $(this).val();
		let elementIndex	= $(this).closest('.wordform-checkbox-field-options-wrapper').prop("id").split("-")[1];														
		$('#wordformElement-'+elementIndex+' .wordform-dropped-checkbox-label').html(labelName);		
	});
	
	// Checkbox: On change/ Edit update checkbox options label text
	$('body').on("input", ".wordform-input-checkbox-label-name", function() {
		let elementIndex	= $(this).closest('.wordform-checkbox-field-options-wrapper').prop("id").split("-")[1];				
		let liElementnth	= $(this).parent().index();
		let labelName		= $(this).val();		
		//$('.wordform-dropped-element-radio-field-options li:eq('+liElementnth+') span').html(labelName);
		$('#wordformElement-'+elementIndex+' li:eq('+liElementnth+') span').html(labelName);
	});
	
	// Checkbox: Clicked/Checked checkbox
	$('body').on("click", ".wordform-edit-checkbox-name", function() {
		let liElementnth	= $(this).parent().index();
		let elementIndex	= $(this).closest('.wordform-checkbox-field-options-wrapper').prop("id").split("-")[1];
		if ( $(this).is(":checked") ) {
			$(this).attr('checked', true );
			//$('#wordformElement-'+elementIndex+' .wordform-dropped-element-checkbox-field-options li:eq('+liElementnth+') input').prop("checked", true );
			$('#wordformElement-'+elementIndex+' .wordform-dropped-element-checkbox-field-options li:eq('+liElementnth+') input').attr("checked", true );
		}
		else {
			$(this).attr('checked', false );
			//$('#wordformElement-'+elementIndex+' .wordform-dropped-element-checkbox-field-options li:eq('+liElementnth+') input').prop("checked", false );
			$('#wordformElement-'+elementIndex+' .wordform-dropped-element-checkbox-field-options li:eq('+liElementnth+') input').attr("checked", false );
		}
	});
	
	// Checkbox: Remove Checkbox Option Field
	$('body').on("click", ".wordform-checkbox-field-remove", function() {		
		let parentULLength		= $(this).closest('.wordform-field-options').find('li').length;
		console.log('UL Length:'+ parentULLength );
		if ( parentULLength == 2 ) {
			alert('Minimum 1 Option Required!');
		}
		else {
			let liElementnth	= $(this).parent().index();		
			$('.wordform-dropped-element-checkbox-field-options li:eq('+liElementnth+')').remove();
			$(this).parent().remove();
		}
	})
	
	// Checkbox: Add more Checkbox Option
	$('body').on("click", ".wordform-add-checkbox-option", function() {
		// Add to field options
		$(this).parent().prev().after('<li> <input class="wordform-edit-checkbox-name" name="wordform-edit-checkbox-name" type="checkbox" /><input class="wordform-input-checkbox-label-name" type="text" placeholder="Checkbox Text" /> <span class="wordform-checkbox-field-remove dashicons dashicons-no" title="Remove"></span> </li> ');
				
		// Add to dropped zone element
		let elementIndex	= $(this).closest('.wordform-checkbox-field-options-wrapper').prop("id").split("-")[1];												
		$('#wordformElement-'+elementIndex+ ' .wordform-dropped-element-checkbox-field-options').append('<li><input class="wordform-dropped-checkbox-name" name="wordform-dropped-checkbox-name" type="checkbox" disabled="disabled" /><span>Checkbox Text</span> </li>');		
	});
	
	
	/*** Select ***/
	
	// Select: Hide all select options wrapper - then show only the working one
	function showHideSelectOptions( elementOptionsWrapperID ) {
		 $('.show-hide-common-class-all-options-wrapper-element').css('display', 'none');
		 $('.show-hide-common-class-all-options-wrapper-element').promise().done(function() {			 
			 $('#'+elementOptionsWrapperID).fadeIn();
		 }); 		
	}
	
	// Select: EdiT Dropdown Label Text
	$('body').on("input", ".wordform-select-label-name", function() {
		let labelName		= $(this).val();
		let elementIndex	= $(this).closest('.wordform-select-field-options-wrapper').prop("id").split("-")[1];														
		$('#wordformElement-'+elementIndex+' .wordform-dropped-select-label').html(labelName);		
	});
		
	// Select: On change/ Edit update Select options text
	$('body').on("input", ".wordform-input-select-label-name", function() {
		let elementIndex	= $(this).closest('.wordform-select-field-options-wrapper').prop("id").split("-")[1];				
		let liElementnth	= $(this).parent().index();
		let selectText		= $(this).val();				
		$('#wordformElement-'+elementIndex+' option:eq('+liElementnth+')' ).html(selectText);
		$('#wordformElement-'+elementIndex+' option:eq('+liElementnth+')' ).val(selectText);
	});

	// Select: On Click Select option texts to set as default selected in dropdown
	$('body').on("click", ".wordform-edit-select-name", function() {		
		let elementIndex	= $(this).closest('.wordform-select-field-options-wrapper').prop("id").split("-")[1];						
		let selectText		= $(this).next().val();							
		//$('#wordformElement-'+elementIndex+' .wordform-dropped-select-name' ).val(selectText);
		
		// Find Select checkbox options group name by name attribute
		let editSelectCheckboxOptionsGroupName		= $(this).prop('name');				
		console.log(editSelectCheckboxOptionsGroupName);
		// Element options
		$('input[name="'+ editSelectCheckboxOptionsGroupName +'"]').each(function(index, elem ) {
			console.log('Element Index: '+ index);
			if ( $(this).is(":checked") ) {
				$(this).attr('checked', true);
				$('#wordformElement-'+elementIndex+' .wordform-dropped-select-name option' ).attr('selected', false ).promise().done(function() {
					$('#wordformElement-'+elementIndex+' .wordform-dropped-select-name option[value="'+selectText+'"]' ).attr('selected', true );	
				});				
			}
			else {
				$(this).attr('checked', false );
			}
		});		
		
		
	});
		
	
	// Select: Add more Select Option
	$('body').on("click", ".wordform-add-select-option", function() {
		let elementIndex	= $(this).closest('.wordform-select-field-options-wrapper').prop("id").split("-")[1];												
		
		// Add to field options
		$(this).parent().prev().after('<li> <input class="wordform-edit-select-name" name="wordform-edit-select-name-'+elementIndex+'" type="radio" /><input class="wordform-input-select-label-name" type="text" placeholder="Select Text" /> <span class="wordform-select-field-remove dashicons dashicons-no" title="Remove"></span> </li> ');
				
		// Add to dropped zone element
		//let elementIndex	= $(this).closest('.wordform-select-field-options-wrapper').prop("id").split("-")[1];												
		$('#wordformElement-'+elementIndex+ ' .wordform-dropped-select-name').append('<option value="Select Text">Select Text</option>');		
	});
	
	// Select: Remove Select Text Field
	$('body').on("click", ".wordform-select-field-remove", function() {
		let parentULLength		= $(this).closest('.wordform-field-options').find('li').length;
		console.log('UL Length:'+ parentULLength );
		if ( parentULLength == 2 ) {
			alert('Minimum 1 Option Required!');
		}
		else {		
			let liElementnth	= $(this).parent().index();		
			$('.wordform-dropped-element-select-field-options .wordform-dropped-select-name option:eq('+liElementnth+')').remove();
			$(this).parent().remove();
		}
	})
	
	    
	/*** Submit-button Element ***/
	
	// Submit-button: Hide all options wrapper - then show only the working one
	function showHideSubmitButtonOptions( elementOptionsWrapperID ) {
		 $('.show-hide-common-class-all-options-wrapper-element').css('display', 'none');
		 $('.show-hide-common-class-all-options-wrapper-element').promise().done(function() {			 
			 $('#'+elementOptionsWrapperID).fadeIn();
		 }); 		
	}
	
	// Submit-button: Wordform create form submit button element options display
	$('body').on("click", ".wordform-input-element-div-wrapper-submit-button", function() {		
		let elementID					= $(this).prop("id");
		let elementIndex				= elementID.split("-")[1];		
		let elementOptionsWrapperID		= 'wordformElementOptions-'+ elementIndex;
		showHideSubmitButtonOptions( elementOptionsWrapperID );	
		setSelectedDroppedElementBackground( elementIndex );				
	});
	
	// Submit-button: Submit button Label Text
	$('body').on("input", ".wordform-submit-button-label-input", function() {
		let labelName		= $(this).val();
		let elementIndex	= $(this).closest('.wordform-submit-button-field-options-wrapper').prop("id").split("-")[1];														
		$('#wordformElement-'+elementIndex+' button').text(labelName);		
	});
	
	
	
	/*** Form-Name Element ***/
	
	// Form-name: Hide all options wrapper - then show only the working one
	function showHideFormNameOptions( elementOptionsWrapperID ) {
		 $('.show-hide-common-class-all-options-wrapper-element').css('display', 'none');
		 $('.show-hide-common-class-all-options-wrapper-element').promise().done(function() {			 
			 $('#'+elementOptionsWrapperID).fadeIn();
		 }); 		
	}
	
	// Form-name: Wordform create form form name element options display
	$('body').on("click", ".wordform-input-element-div-wrapper-form-name", function() {		
		let elementID					= $(this).prop("id");
		let elementIndex				= elementID.split("-")[1];		
		let elementOptionsWrapperID		= 'wordformElementOptions-'+ elementIndex;
		showHideFormNameOptions( elementOptionsWrapperID );	
		setSelectedDroppedElementBackground( elementIndex );				
	});
	
	// Form-name: Form Name Label Text
	$('body').on("input", ".wordform-form-name-label-input", function() {
		let labelName		= $(this).val();		
		let elementIndex	= $(this).closest('.wordform-form-name-field-options-wrapper').prop("id").split("-")[1];														
		$('#wordformElement-'+elementIndex+' span').html(labelName);	
	});
		
	
	
	
	// Input Field Label Name
	$('body').on("input", ".wordform-input-label-name", function() {
		let labelName	=	$(this).val();
		console.log(labelName);
		let parentID	= $(this).closest('.wordform-input-element-div-wrapper').prop("id");
		console.log(parentID);
		$('#'+parentID+ ' .input-label').text(labelName);
	});
	
	
	// Save: Wordform Save Button - Save created form to use later
	$('body').on("click", ".wordform-save-btn", function() {
		let inputElementArr				=	[];
		let formSaveMeta				= 	{};
		formSaveMeta.formSaved			=   $('.wordform-form-saved').val();
		formSaveMeta.createdFormID		=   $('.wordform-created-form-id').val();
		formSaveMeta.formName			=   $('.wordform-form-name-label-input').val();		
		let messageWrapper				=   $('.wordform-message-info');		
		
		$('.wordform-input-element-div-wrapper').each(function() {
			let elementObj				=	{};
			let multipleOptions			= 	[];
			let options					=	{};
			let elementIndex			=	$(this).prop("id").split('-')[1];
			let elementType				=   $(this).data('input-element-type');
			let optionsWrapperID		=   '#wordformElementOptions-'+elementIndex;
			//console.log( elementType );			
			//console.log(elementIndex);
			switch ( elementType ) {
				case 'text':
					elementObj					=	{};
					elementObj.type				=   elementType;
					elementObj.required			=   ( $(optionsWrapperID).find('.required-checkbox').is(':checked') )? true : false;
					elementObj.label			=   ( $(optionsWrapperID).find('.wordform-text-label-name').val().length == 0 )? 'Text Label' : $(optionsWrapperID).find('.wordform-text-label-name').val();
					elementObj.charLimit		=   '';
					elementObj.elementIndex		=   elementIndex;
					inputElementArr.push( elementObj);
					break;
					
				case 'number':
					elementObj					=	{};
					elementObj.type				=   elementType;
					elementObj.required			=   ( $(optionsWrapperID).find('.required-checkbox').is(':checked') )? true : false;
					elementObj.label			=   ( $(optionsWrapperID).find('.wordform-number-label-name').val().length == 0 )? 'Number Label' : $(optionsWrapperID).find('.wordform-number-label-name').val();	
					elementObj.elementIndex		=   elementIndex;
					inputElementArr.push( elementObj);
					break;
					
				case 'textarea':
					elementObj					=	{};
					elementObj.type				=   elementType;
					elementObj.required			=   ( $(optionsWrapperID).find('.required-checkbox').is(':checked') )? true : false;
					elementObj.label			=   ( $(optionsWrapperID).find('.wordform-textarea-label-name').val().length == 0 )? 'Textarea Label' : $(optionsWrapperID).find('.wordform-textarea-label-name').val();	
					elementObj.elementIndex		=   elementIndex;
					inputElementArr.push( elementObj);
					break;
					
				case 'radio':
					elementObj					=	{};
					elementObj.type				=   elementType;
					elementObj.required			=   ( $(optionsWrapperID).find('.required-checkbox').is(':checked') )? true : false;
					elementObj.label			=   ( $(optionsWrapperID).find('.wordform-radio-label-name').val().length == 0 )? 'Multiple Options Label' : $(optionsWrapperID).find('.wordform-radio-label-name').val();	
					elementObj.elementIndex		=   elementIndex;
					
					
					multipleOptions				= 	[];
					$(optionsWrapperID).find('.wordform-field-options input[type="radio"]').each(function() {						
						options					=	{};		
						options.checkStatus		=	( $(this).is(':checked') )? true : false;
						options.optionText		=	( $(this).next().val().length == 0 )? 'Option Text' : $(this).next().val();
						multipleOptions.push( options );						
					});
					//console.log(multipleOptions);
					elementObj.multiOption		= multipleOptions;
					
					inputElementArr.push( elementObj);
					break;
					
				case 'checkbox':
					elementObj					=	{};
					elementObj.type				=   elementType;
					elementObj.required			=   ( $(optionsWrapperID).find('.required-checkbox').is(':checked') )? true : false;
					elementObj.label			=   ( $(optionsWrapperID).find('.wordform-checkbox-label-name').val().length == 0 )? 'Checkbox Label' : $(optionsWrapperID).find('.wordform-checkbox-label-name').val();	
					elementObj.elementIndex		=   elementIndex;
					
					
					multipleOptions				= 	[];
					$(optionsWrapperID).find('.wordform-field-options input[type="checkbox"]').each(function() {						
						options					=	{};		
						options.checkStatus		=	( $(this).is(':checked') )? true : false;
						options.optionText		=	( $(this).next().val().length == 0 )? 'Checkbox Text' : $(this).next().val();
						multipleOptions.push( options );						
					});
					//console.log(multipleOptions);
					elementObj.multiOption		= multipleOptions;					
					
					inputElementArr.push( elementObj);
					break;
					
				case 'select':
					elementObj					=	{};
					elementObj.type				=   elementType;
					elementObj.required			=   ( $(optionsWrapperID).find('.required-checkbox').is(':checked') )? true : false;
					elementObj.label			=   ( $(optionsWrapperID).find('.wordform-select-label-name').val().length == 0 )? 'Dropdown Label' : $(optionsWrapperID).find('.wordform-select-label-name').val();	
					elementObj.elementIndex		=   elementIndex;
					
					
					multipleOptions				= 	[];
					$(optionsWrapperID).find('.wordform-field-options input[type="radio"]').each(function() {						
						options					=	{};		
						options.checkStatus		=	( $(this).is(':checked') )? true : false;
						options.optionText		=	( $(this).next().val().length == 0 )? 'Select Text' : $(this).next().val();
						multipleOptions.push( options );						
					});
					//console.log(multipleOptions);
					elementObj.multiOption		= multipleOptions;					
					
					inputElementArr.push( elementObj);
					break;		
					
					
				case 'form-name':
					elementObj					=	{};
					elementObj.type				=   elementType;
					elementObj.hide				=   ( $(optionsWrapperID).find('.wordform-form-name-display-checkbox').is(':checked') )? true : false;
					elementObj.label			=   $(optionsWrapperID).find('.wordform-form-name-label-input').val();	
					elementObj.elementIndex		=   elementIndex;
					inputElementArr.push( elementObj);
					break;
					
				case 'submit-button':
					elementObj					=	{};
					elementObj.type				=   elementType;					
					elementObj.label			=   $(optionsWrapperID).find('.wordform-submit-button-label-input').val();	
					elementObj.elementIndex		=   elementIndex;
					inputElementArr.push( elementObj);
					break;
					
										
			} // switch			
			
		}); // each(function)
		console.log(inputElementArr);
		messageWrapper.html('');
		messageWrapper.removeClass('text-error text-success text-info text-warning');
		let scWordFormName	= $('.wordform-form-name-label-input').val();	
		
		// If no element dropped yet!
		let scWordFormDroppedElementLength  = $('.wordform-dropzone-div-wrapper').children().length;
		console.log( scWordFormDroppedElementLength );
		if ( scWordFormDroppedElementLength > 0 ) {		
			$.ajax({
			  type:"POST",		  
			  url: sc_wordform_metabox_script_obj.adminajax_url,
			  data : {			    
					action 	 : 'sc_wordform_save',
					security : sc_wordform_metabox_script_obj.nonce,				
					params   : inputElementArr,
					saveMeta : formSaveMeta			    
					},		  
			  success: function(data) { 
				 console.log(data); 
				 let jsonData	= JSON.parse( data );
				  if ( jsonData.status == 'success' ) {
					  $('.wordform-form-saved').val(jsonData.formSaved);
					  $('.wordform-created-form-id').val(jsonData.savedFormID);
					  messageWrapper.html(jsonData.reason ).addClass('text-success');
					  $('.wordform-preview-btn').prop( 'href', jsonData.previewURL );
					  $('.wordform-preview-btn').removeClass('disabled');

					  if ( jsonData.savedFormID ) {
						  let builtFormMeta					=  {};
						  builtFormMeta.builtForm			=  $('.wordform-builder-div-wrapper').clone().html();					  
						  builtFormMeta.builtFormOptions	=  $('.wordform-builder-form-options-div-wrapper').clone().html();						  
						  builtFormMeta.formID				=  jsonData.savedFormID;
						  //console.log(builtFormMeta.builtForm);					  					  
						  //console.log(builtFormMeta.builtFormOptions);

						  // Save edit form elements html data
						  wordFormBuiltDataStore( builtFormMeta );
					  }
				  }
				  else if ( jsonData.status == 'fail' ) {					  
					  messageWrapper.html(jsonData.reason ).addClass('text-error');
				  }
				  else {
					  messageWrapper.html( 'Something went wrong.' ).addClass('text-error');
				  }
				},
			  error: function( xhr, status, error ) { 
				 console.log(xhr); 
				 console.log(status); 
				 console.log(error); 				 
				}
			});
		}
		// No element dropped yet!
		else {
			messageWrapper.html(sc_wordform_metabox_script_obj.noElementDroppedMsg).addClass('text-info');
		}
		
	});
	
	
	
	// Save: Store partial built form data
	function wordFormBuiltDataStore( builtFormMeta ) {
		//console.log(builtFormMeta);
		$.ajax({
		  type:"POST",		  
		  url: sc_wordform_metabox_script_obj.adminajax_url,
		  data : {			    
				action 	 : 'sc_wordform_built_form_data_save',
				security : sc_wordform_metabox_script_obj.nonce,				
			    params   : builtFormMeta			    		    
				},		  
		  success: function(data) { 
			 console.log(data); 
			 let jsonData	= JSON.parse( data );
			  if ( jsonData.status == 'success' ) {
			  }
			  else if ( jsonData.status == 'fail' ) {					  				  
			  }
			  else {				  
			  }
			},
		  error: function( xhr, status, error ) { 
			 console.log(xhr); 
			 console.log(status); 
			 console.log(error); 				 
			}
		});		
	}
	
	
	
	
	// Settings: Validation tab
	$('body').on('submit', '#scWordformSettingsValidationTabForm', function() {		
		let formData			= $(this).serialize();
		let scWordformMsgInfo	= $('.sc-wordform-validation-message-settings-info');
		console.log(formData);
				
		scWordformMsgInfo.removeClass('msg-success msg-error').text('');
		$(this).find('button[type="submit"]').attr('disabled', true );
		$.ajax({
		  type:"POST",		  
		  url: sc_wordform_metabox_script_obj.adminajax_url,
		  data : {			    
				action 	 : 'sc_wordform_settings_menu_validation_tab_data_save',
				security : sc_wordform_metabox_script_obj.nonce,				
			    formData :formData			    		    
				},		  
		  success: function(data) { 
			 console.log(data); 
			 let jsonData	= JSON.parse( data );
			  if ( jsonData.status == 'success' ) {
				  scWordformMsgInfo.text(jsonData.Msg ).addClass('msg-success');
			  }
			  else if ( jsonData.status == 'fail' ) {		
				  scWordformMsgInfo.text(jsonData.Msg ).addClass('msg-error');
			  }
			  
			  $(this).find('button[type="submit"]').removeAttr('disabled');
			},
		  error: function( xhr, status, error ) { 
			 console.log(xhr); 
			 console.log(status); 
			 console.log(error); 				 
			 $(this).find('button[type="submit"]').removeAttr('disabled'); 
			}
		});			
				
		$(this).find('button[type="submit"]').removeAttr('disabled'); 
		return false;
	});
	
	
	// Settings: Validation Tab - On Select Form
	$('body').on('change', '#scWordformSelectValidationMsgsFor', function() {
		let scSelectWordformName				= $(this).find('option:selected').text();
		let scSelectWordformID					= $(this).val();
		let scWordformValidationMsgDisplayTbl	= $('.sc-wordform-validation-messages-display-table tbody');
		let scWordformMsgInfo					= $('.sc-wordform-validation-message-settings-info');
		scWordformMsgInfo.removeClass('msg-success msg-error').text('');
		console.log(scSelectWordformName);
		console.log(scSelectWordformID);
		$('.sc-wordform-validation-tab-selected-form-name').val(scSelectWordformName);
		$('.sc-wordform-validation-tab-selected-form-id').val(scSelectWordformID);
		
		let params						= {};
		params.FormName					= scSelectWordformName;
		params.FormID					= scSelectWordformID;
		scWordformValidationMsgDisplayTbl.css('opacity', 0.30 );
		$.ajax({
		  type:"POST",		  
		  url: sc_wordform_metabox_script_obj.adminajax_url,
		  data : {			    
				action 	  : 'sc_wordform_settings_menu_validation_tab_selected_form_data_save',
				security  : sc_wordform_metabox_script_obj.nonce,				
			    params 	  : params
				},		  
		  success: function(data) { 
			 console.log(data); 
			 let jsonData	= JSON.parse( data );
			  if ( jsonData.status == 'success' ) {
				  scWordformValidationMsgDisplayTbl.html(jsonData.htmlTemplate).css('opacity', 1 );				  
			  }
			  else if ( jsonData.status == 'fail' ) {		
				  scWordformValidationMsgDisplayTbl.css('opacity', 1 );
			  }
			  
			  
			},
		  error: function( xhr, status, error ) { 
			 console.log(xhr); 
			 console.log(status); 
			 console.log(error); 				 
			 scWordformValidationMsgDisplayTbl.css('opacity', 1 );
			}
		});			
		
	});
	
	
	
	// All Forms Page: Delete form
	$('body').on('click', '.sc-wordform-delete-btn', function() {
		let scWordFormID	= $(this).data('sc-wordform-id');
		let scWordFormName	= $(this).data('sc-wordform-name');		
		let scWordFormTR	= $(this).closest('tr');
		console.log(scWordFormID);
		console.log(scWordFormName);
		let params			= {};
		params.wordformID	= scWordFormID;
		params.wordformName	= scWordFormName;		
		let confirmMsg		= confirm('Do you want to delete '+ scWordFormName +' [ '+ scWordFormID +' ] permanently?');
		if ( confirmMsg ) {
			scWordFormTR.css('background-color', 'darksalmon');
			$.ajax({
			  type:"POST",		  
			  url: sc_wordform_metabox_script_obj.adminajax_url,
			  data : {			    
					action 	    : 'sc_wordform_all_forms_page_delete_form',
					security    : sc_wordform_metabox_script_obj.nonce,				
					params 		: params
					},		  
			  success: function(data) { 
				 console.log(data); 
				 let jsonData	= JSON.parse( data );
				  if ( jsonData.status == 'success' ) {
					  scWordFormTR.fadeOut(300);
				  }
				  else if ( jsonData.status == 'fail' ) {		
					  scWordFormTR.css('background-color', '');
					  alert('Something went wrong, failed to delete. Please try again.')
				  }

				},
			  error: function( xhr, status, error ) { 
				 console.log(xhr); 
				 console.log(status); 
				 console.log(error); 				 

				}
			});	
		}
		
	});
	
	
	
	// Settings: General Tab
	$('#scWordformSettingsGeneralTabForm').on('submit', function() {
		    let updateInfoEle	= $('.sc-wordform-general-tab-update-info');
			let postData		= $(this).serialize();
			console.log(postData);
		    let params			= {};
		    params.formData		= postData;
		    updateInfoEle.html('').removeClass('text-success text-error');
			$.ajax({
			  type:"POST",		  
			  url: sc_wordform_metabox_script_obj.adminajax_url,
			  data : {			    
					action 	    : 'sc_wordform_settings_general_tab_form',
					security    : sc_wordform_metabox_script_obj.nonce,				
					params 		: params
					},		  
			  success: function(data) { 
				 console.log(data); 
				 let jsonData	= JSON.parse( data );
				  if ( jsonData.status == 'success' ) {		
					  updateInfoEle.html(jsonData.successMsg).addClass('text-success');
				  }
				  else if ( jsonData.status == 'fail' ) {			
					  updateInfoEle.html(jsonData.failMsg).addClass('text-error');
				  }

				},
			  error: function( xhr, status, error ) { 
				 console.log(xhr); 
				 console.log(status); 
				 console.log(error); 				 

				}
			});			
		
		return false;
	});
	
	
	// Settings : General Tab Reset
	$('body').on('click', '.sc-wordform-settings-general-tab-reset', function() {
		let backgroundColor			=  '#2271b1';
		let backgroundHoverColor	=  '#006ba1';
		let buttonFontColor			=  '#ffffff';
		let buttonTextSize			=  '16';
		let paddingTopBottom		=  '8';
		let paddingLeftRight		=  '25';
		
		let confirmMsg				= confirm('Do you want to set default settings?');
		
		if ( confirmMsg ) {
			// Send Email checkbox
			$('.sc-wordform-form-submission-send-email').removeAttr('checked');

			// Button background color
			$('.sc-wordform-form-submit-button-background-color').val( backgroundColor).promise().done(function() {					
				$('.sc-wordform-form-submit-button-background-color').closest('div.wp-picker-container').find('button[type="button"]').css("background-color", backgroundColor).promise().done(function() {
				});				
			});

            // Button background color (hover)
			$('.sc-wordform-form-submit-button-background-hover-color').val( backgroundHoverColor ).promise().done(function() {					
				$('.sc-wordform-form-submit-button-background-hover-color').closest('div.wp-picker-container').find('button[type="button"]').css("background-color", backgroundHoverColor).promise().done(function() {			
				});
			});

            // Button font color
			$('.sc-wordform-form-submit-button-font-color').val( buttonFontColor ).promise().done(function() {
				$('.sc-wordform-form-submit-button-font-color').closest('div.wp-picker-container').find('button[type="button"]').css("background-color", buttonFontColor).promise().done(function() {
				});
			});

			// Button font-size 
			$('.sc-wordform-form-submit-button-text-size').val( buttonTextSize );	
			
			// Padding (Top-Bottom)
			$('.sc-wordform-form-submit-button-padding-top-bottom').val( paddingTopBottom );		

			// Padding (Left-Right)
			$('.sc-wordform-form-submit-button-padding-left-right').val( paddingLeftRight );		
			
			
			// Button font-weight
			$('input[name="sc-wordform-form-submit-button-font-weight"]').removeAttr('checked').promise().done(function() {
				$('input[name="sc-wordform-form-submit-button-font-weight"]:first').attr('checked', true ).click();
			});

			// Trigger Submit form
			$('#scWordformSettingsGeneralTabForm').trigger('submit');
		}
	});
	
	
	
	
	
	
	
}(jQuery));
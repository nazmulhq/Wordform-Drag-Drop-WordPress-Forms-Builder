<div id="<?php echo $element_wrapper_id;?>" class="wordform-radio-field-options-wrapper show-hide-common-class-all-options-wrapper-element" data-wrapper-element-type = "radio" data-element-index-val="<?php echo $element_index;?>" >	

	<h4>Field Required:</h4>
	<ul class="wordform-required-ul">
		<li>
			<input class="required-checkbox" type="checkbox" />Required
			<small>(Check if Required)</small>
		</li>
	</ul>
	<hr/>

	<h4>Multiple Options Label:</h4>
	<ul class="wordform-input-label">	
		<li>				    
			<input class="wordform-radio-label-name" type="text" placeholder="Multiple Options Label" />
		</li>
	</ul>	
	<hr/>

	<h4>Multiple Options:<br/><small>(Check to show as default)</small></h4>		
	<ul class="wordform-field-options">				
		<li> 
			<input type="radio" class="wordform-edit-radio-name" name="wordform-edit-radio-name-<?php echo $element_index;?>" /><input class="wordform-input-radio-label-name" type="text" placeholder="Option Text" />
			<span class="wordform-radio-field-remove dashicons dashicons-no" title="Remove"></span>					 
		</li> 
		<li><span class="wordform-add-radio-option dashicons dashicons-plus" title="Add Radio Option"></span></li> 
	</ul>

</div><!-- /.wordform-radio-field-options-wrapper -->

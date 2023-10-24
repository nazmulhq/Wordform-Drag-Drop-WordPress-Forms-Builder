import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText } from '@wordpress/block-editor';
import { TextControl, SelectControl } from '@wordpress/components';
import apiFetch from '@wordpress/api-fetch';
import { useState, useEffect } from '@wordpress/element';
import metadata from './block.json';
import './editor.scss';

export default function Edit({attributes})  {	
	const blockProps	= useBlockProps();	
	
	const [ selectdata, setSelectdata ]	= useState("");
	const [ formdata, setFormdata ]	 	= useState("");
	const [ formid, setFormid ]  	    = useState("");
	const [ formidprev, setFormidprev ] = useState("");
	
	console.log('Attributes: ');
	console.log(attributes);
		
	const getSelectedFormID	= (event) => {
		let selectedFormID	=	 event.target.value;		
		console.log('on Change Current Form ID: '+selectedFormID);		
		console.log('Previous Form ID: ' + formid );
		setFormidprev(formid);
		setFormid(selectedFormID);					
	}
	
	const getFormsdata = () => {
		useEffect(() => {
		let postID = wp.data.select("core/editor").getCurrentPostId();					
			
		apiFetch( {
			path: 'wordform/v1/all-form-list',
			method: 'POST',	
			data: { postID: postID },
		} ).then( ( res ) => {		   
			console.log( res );
			const jData = JSON.parse(res);
			console.log(jData);
			if ( jData.status == 'success' ) {
				setSelectdata(jData.formList);				
				if ( jData.formID[0] ) {
					console.log('Previous Attached Form ID: '+ jData.formID[0] );
					setFormid(jData.formID[0]);
				}
			}
		} );		
			
		},[]);
	}
	
				
	useEffect(() => {		
		console.log('PreviousFormID:'+ formidprev );
		console.log('CurrentFormdID:'+ formid );
		let postID = wp.data.select("core/editor").getCurrentPostId();
		console.log( postID );

		apiFetch( {
			path: 'wordform/v1/render-selected-form',
			method: 'POST',
			data: { PreviousWordFormID: formidprev, WordFormID: formid, postID: postID },
		} ).then( ( res ) => {		   
			console.log( res );
			const jData = JSON.parse(res);
			console.log(jData);
			if ( jData.status == 'success' ) {
				setFormdata(jData.formdata);		
				attributes.createdformdata = jData.formdata;
				console.log(attributes.createdformdata);
			}
		} );		

	}, [formid]);			
							
    return (				
		<div {...blockProps}>		
        	{ getFormsdata() }		           
		    <select onChange={getSelectedFormID} className="sc-wordform-block-editor-form-selector-select">
		       <option value="">Select Form</option>
			   {
			    selectdata && selectdata.map( (item) => ( <option key={item.ID} value={item.formID} selected={item.formID == formid}>{item.formName}</option> ))
               }																
			</select>
		   
	        <div dangerouslySetInnerHTML={ { __html: attributes.createdformdata } }></div>
        						  
	    </div>
		
    );
}

import { useBlockProps } from '@wordpress/block-editor';

export default function save({attributes}) {	
    const blockProps = useBlockProps.save();
	console.log('SAVE: ');
	console.log(attributes.createdformdata);
    return <div className='sc-wordform-block-editor-div-wrapper' dangerouslySetInnerHTML={ { __html: attributes.createdformdata } }></div> 	    			 		 	
}
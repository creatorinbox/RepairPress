<?php

/*
 *  RepairPress Our Services Template for Visual Composer
 */

add_action( 'vc_load_default_templates_action','repairpress_our_services_template_for_vc' );

function repairpress_our_services_template_for_vc() {
	$data               = array();
	$data['name']       = _x( 'RepairPress: Our Services', 'backend' , 'repairpress-pt' );
	$data['image_path'] = preg_replace( '/\s/', '%20', get_template_directory_uri() . '/vendor/proteusthemes/visual-composer-elements/assets/images/pt.svg' );
	$data['custom_class'] = 'repairpress_our_services_template_for_vc_custom_template';
	$data['content']    = <<<CONTENT
		[vc_row full_width="" parallax="" parallax_image=""][vc_column width="1/3"][pt_vc_featured_page page="177" layout="block" read_more_text="Read more"][/vc_column][vc_column width="1/3"][pt_vc_featured_page page="187" layout="block" read_more_text="Read more"][/vc_column][vc_column width="1/3"][pt_vc_featured_page page="190" layout="block" read_more_text="Read more"][/vc_column][/vc_row][vc_row full_width="" parallax="" parallax_image="" el_id=""][vc_column width="1/3"][pt_vc_featured_page page="206" layout="block" read_more_text="Read more"][/vc_column][vc_column width="1/3"][pt_vc_featured_page page="198" layout="block" read_more_text="Read more"][/vc_column][vc_column width="1/3"][pt_vc_featured_page page="209" layout="block" read_more_text="Read more"][/vc_column][/vc_row]
CONTENT;

	vc_add_default_templates( $data );
}
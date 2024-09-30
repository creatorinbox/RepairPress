<?php

/*
 *  RepairPress About us Template for Visual Composer
 */

add_action( 'vc_load_default_templates_action','repairpress_about_us_template_for_vc' );

function repairpress_about_us_template_for_vc() {
	$data               = array();
	$data['name']       = _x( 'RepairPress: About Us', 'backend' , 'repairpress-pt' );
	$data['image_path'] = preg_replace( '/\s/', '%20', get_template_directory_uri() . '/vendor/proteusthemes/visual-composer-elements/assets/images/pt.svg' );
	$data['custom_class'] = 'repairpress_about_us_template_for_vc_custom_template';
	$data['content']    = <<<CONTENT
		[vc_row css=".vc_custom_1441969620013{margin-bottom: 35px !important;}"][vc_column width="1/1"][vc_column_text]
<div class="widget-title--big">
<h3 class="widget-title"><span class="widget-title__inline">Our Team</span></h3>
</div>
[/vc_column_text][/vc_column][/vc_row][vc_row full_width="" parallax="" parallax_image=""][vc_column width="1/3"][pt_vc_person_profile name="Jeff Lopez" title="CEO and Founder" image_url="http://xml-io.proteusthemes.com/legalpress/wp-content/uploads/sites/26/2015/06/110.jpg" introduction="I was always tech savvy and being from an electronic engineering background, I wanted to experience the best in electronics, be it a phone or my PC. One day many years ago, I had to immediately rush to repair." social_links="https://www.facebook.com/ProteusThemes|fa-facebook-square
https://www.linkedin.com|fa-linkedin-square
https://twitter.com/proteusthemes|fa-twitter-square
https://www.youtube.com/user/ProteusNetCompany/|fa-youtube-square" new_tab=""][/vc_column][vc_column width="1/3"][pt_vc_person_profile name="Stephanie Adams" title="CTO" image_url="http://xml-io.proteusthemes.com/legalpress/wp-content/uploads/sites/26/2015/06/110.jpg" introduction="I was always tech savvy and being from an electronic engineering background, I wanted to experience the best in electronics, be it a phone or my PC. One day many years ago, I had to immediately rush to repair." social_links="https://www.facebook.com/ProteusThemes|fa-facebook-square
https://www.linkedin.com|fa-linkedin-square
https://twitter.com/proteusthemes|fa-twitter-square
https://www.youtube.com/user/ProteusNetCompany/|fa-youtube-square" new_tab=""][/vc_column][vc_column width="1/3"][pt_vc_person_profile name="Jose Contreras" title="Chief Repair Man" image_url="http://xml-io.proteusthemes.com/legalpress/wp-content/uploads/sites/26/2015/06/110.jpg" introduction="I was always tech savvy and being from an electronic engineering background, I wanted to experience the best in electronics, be it a phone or my PC. One day many years ago, I had to immediately rush to repair." social_links="https://www.facebook.com/ProteusThemes|fa-facebook-square
https://www.linkedin.com|fa-linkedin-square
https://twitter.com/proteusthemes|fa-twitter-square
https://www.youtube.com/user/ProteusNetCompany/|fa-youtube-square" new_tab=""][/vc_column][/vc_row][vc_row full_width="" parallax="" parallax_image="" el_id=""][vc_column width="1/3"][pt_vc_person_profile name="Bryan Lewis" title="Repair Man" image_url="http://xml-io.proteusthemes.com/legalpress/wp-content/uploads/sites/26/2015/06/110.jpg" introduction="I was always tech savvy and being from an electronic engineering background, I wanted to experience the best in electronics, be it a phone or my PC. One day many years ago, I had to immediately rush to repair." social_links="https://www.facebook.com/ProteusThemes|fa-facebook-square
https://www.linkedin.com|fa-linkedin-square
https://twitter.com/proteusthemes|fa-twitter-square
https://www.youtube.com/user/ProteusNetCompany/|fa-youtube-square" new_tab=""][/vc_column][vc_column width="1/3"][pt_vc_person_profile name="Rebecca Garza" title="Repair Woman" image_url="http://xml-io.proteusthemes.com/legalpress/wp-content/uploads/sites/26/2015/06/110.jpg" introduction="I was always tech savvy and being from an electronic engineering background, I wanted to experience the best in electronics, be it a phone or my PC. One day many years ago, I had to immediately rush to repair." social_links="https://www.facebook.com/ProteusThemes|fa-facebook-square
https://www.linkedin.com|fa-linkedin-square
https://twitter.com/proteusthemes|fa-twitter-square
https://www.youtube.com/user/ProteusNetCompany/|fa-youtube-square" new_tab=""][/vc_column][vc_column width="1/3"][pt_vc_person_profile name="Kimberly Chavez" title="Software Engineer" image_url="http://xml-io.proteusthemes.com/legalpress/wp-content/uploads/sites/26/2015/06/110.jpg" introduction="I was always tech savvy and being from an electronic engineering background, I wanted to experience the best in electronics, be it a phone or my PC. One day many years ago, I had to immediately rush to repair." social_links="https://www.facebook.com/ProteusThemes|fa-facebook-square
https://www.linkedin.com|fa-linkedin-square
https://twitter.com/proteusthemes|fa-twitter-square
https://www.youtube.com/user/ProteusNetCompany/|fa-youtube-square" new_tab=""][/vc_column][/vc_row]
CONTENT;

	vc_add_default_templates( $data );
}
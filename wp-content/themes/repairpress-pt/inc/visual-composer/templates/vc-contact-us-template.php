<?php

/*
 *  RepairPress Contact Us Template for Visual Composer
 */

add_action( 'vc_load_default_templates_action','repairpress_contact_us_template_for_vc' );

function repairpress_contact_us_template_for_vc() {
	$data               = array();
	$data['name']       = _x( 'RepairPress: Contact Us', 'backend' , 'repairpress-pt' );
	$data['image_path'] = preg_replace( '/\s/', '%20', get_template_directory_uri() . '/vendor/proteusthemes/visual-composer-elements/assets/images/pt.svg' );
	$data['custom_class'] = 'repairpress_contact_us_template_for_vc_custom_template';
	$data['content']    = <<<CONTENT
		[vc_row full_width="" parallax="" parallax_image=""][vc_column width="1/4"][vc_column_text]<h3 class="widget-title"><span class="widget-title__inline">Contact Info</span></h3>
[/vc_column_text][vc_column_text el_class="featured-widget"]
<div style="font-size: 14px;"><strong>[fa icon="fa-map-marker" color="#3baed4"] RepairPress inc.</strong>
227 Marion Street Avenue
Columbia, SC 29201
United Kingdom</div>

<hr />

[fa icon="fa-phone" color="#3baed4"] <span style="font-size: 14px;">1-888-123-4567</span>

<hr />

[fa icon="fa-envelope" color="#3baed4"] <span style="font-size: 14px;">info@repairpress.com</span>

<hr />

[fa icon="fa-globe" color="#3baed4"] <span style="font-size: 14px;">www.repairpress.com</span>[/vc_column_text][/vc_column][vc_column width="3/4"][vc_column_text]
<h3 class="widget-title"><span class="widget-title__inline">Where You Can Find Us</span></h3>
[/vc_column_text][pt_vc_container_google_map lat_long="51.507331,-0.127668" zoom="12" type="roadmap" style="RepairPress" height="380"][pt_vc_location title="London" locationlatlng="51.507331,-0.127668" custompinimage="http://xml-io.proteusthemes.com/repairpress/wp-content/uploads/sites/27/2015/09/pin.png"][/pt_vc_container_google_map][/vc_column][/vc_row][vc_row full_width="" parallax="" parallax_image="" el_id=""][vc_column width="1/4"][vc_column_text]<h3 class="widget-title"><span class="widget-title__inline">Opening Times</span></h3>
[/vc_column_text][pt_vc_opening_time days_hours="opened|8:00|16:00
opened|8:00|16:00
opened|8:00|16:00
opened|8:00|16:00
opened|8:00|16:00
opened|8:00|16:00
closed" separator=" - " closed="CLOSED"][/vc_column][vc_column width="3/4"][vc_column_text]
<h3 class="widget-title"><span class="widget-title__inline">Write Us a Message</span></h3>
[contact-form-7 id="28" title="Contact Us"][/vc_column_text][vc_column_text]
[/vc_column_text][/vc_column][/vc_row]
CONTENT;

	vc_add_default_templates( $data );
}
<?php
/**
 * Search form
 *
 * @package RepairPress
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text"><?php esc_html_e( 'Search for:', 'repairpress-pt' ); ?></span>
		<input type="search" class="search-field" placeholder="<?php esc_html_e( 'Search ...', 'repairpress-pt' ); ?>" value="" name="s">
	</label>
	<button type="submit" class="search-submit"><i class="fa  fa-lg  fa-search"></i></button>
</form>
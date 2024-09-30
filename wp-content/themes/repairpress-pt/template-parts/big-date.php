<?php
if ( 'big' === get_theme_mod( 'blog_date_layout', 'big' ) ) :
	$repairpress_date = get_the_date( 'j M' );
	$repairpress_date = explode( ' ', $repairpress_date );
?>

	<time datetime="<?php the_time( 'c' ); ?>" class="published  hentry__date">
		<span class="hentry__date-day">
			<?php echo esc_html( $repairpress_date[0] ); ?>
		</span>
		<span class="hentry__date-month">
			<?php echo esc_html( $repairpress_date[1] ); ?>
		</span>
	</time>

	<time class="hidden  updated">
		<?php the_modified_date(); ?>
	</time>

<?php endif; ?>
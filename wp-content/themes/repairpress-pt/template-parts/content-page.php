<?php
/**
 * The template used for displaying page content in page.php
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'clearfix' ); ?>>

	<!-- Solve Microformats error -->
	<span class="hidden  entry-title"><?php the_title(); ?></span>
	<span class="hidden  vcard  author">
		<span class="fn"><?php the_author(); ?></span>
	</span>
	<time datetime="<?php the_time( 'c' ); ?>" class="hidden  published"><?php echo get_the_date(); ?></time>
	<time class="hidden  updated"><?php the_modified_date(); ?></time>

	<div class="entry-content">
		<?php the_content(); ?>
			<!-- Multi Page in One Post -->
			<?php
				$repairpress_args = array(
					'before'      => '<div class="multi-page  clearfix">' . /* translators: after that comes pagination like 1, 2, 3 ... 10 */ esc_html__( 'Pages:', 'repairpress-pt' ) . ' &nbsp; ',
					'after'       => '</div>',
					'link_before' => '<span class="btn  btn-info">',
					'link_after'  => '</span>',
					'echo'        => 1,
				);
				wp_link_pages( $repairpress_args );
			?>
	</div><!-- .entry-content -->
</article><!-- #post-## -->
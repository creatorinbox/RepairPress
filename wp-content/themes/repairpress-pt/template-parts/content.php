<?php
/**
 * Template part for displaying posts.
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'clearfix' ); ?>>
	<header class="hentry__header">
		<?php if ( has_post_thumbnail() ) : ?>
			<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail( 'post-thumbnail', array( 'class' => 'img-responsive' ) ); ?>
			</a>
		<?php endif; ?>

		<?php if ( 'post' === get_post_type() ) : ?>
			<?php get_template_part( 'template-parts/meta' ); ?>
		<?php endif; ?>

		<div class="hentry__title-row">
			<?php if ( 'post' === get_post_type() ) : ?>
				<?php get_template_part( 'template-parts/big-date' ); ?>
			<?php endif; ?>
			<?php the_title( sprintf( '<h2 class="hentry__title  entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
		</div>

	</header><!-- .hentry__header -->

	<div class="entry-content">
		<?php
		$repairpress_is_excerpt = ( 1 === (int) get_option( 'rss_use_excerpt', 0 ) );
		if ( $repairpress_is_excerpt ) {
			the_excerpt();
		}
		else {
			/* translators: %s: Name of current post */
			the_content( sprintf(
				wp_kses( __( 'Read more %s', 'repairpress-pt' ), array( 'span' => array( 'class' => array() ) ) ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			) );
		}
		?>
	</div><!-- .entry-content -->
</article><!-- #post-## -->
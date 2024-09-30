<?php
/**
 * The template for displaying all single posts.
 */

get_header();

$repairpress_sidebar = get_field( 'sidebar', (int) get_option( 'page_for_posts' ) );

if ( ! $repairpress_sidebar ) {
	$repairpress_sidebar = 'left';
}

if ( ! is_active_sidebar( 'blog-sidebar' ) ) {
	$repairpress_sidebar = 'none';
}

get_template_part( 'template-parts/main-title' );
get_template_part( 'template-parts/breadcrumbs' );

?>

	<div id="primary" class="content-area  container">
		<div class="row">
			<main id="main" class="site-main  col-xs-12<?php echo 'left' === $repairpress_sidebar ? '  col-md-9  col-md-push-3' : ''; echo 'right' === $repairpress_sidebar ? '  col-md-9' : ''; ?>" role="main">

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'template-parts/content', 'single' ); ?>

					<?php
					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
					?>

				<?php endwhile; // End of the loop. ?>

			</main><!-- #main -->

			<?php if ( 'none' !== $repairpress_sidebar ) : ?>
				<div class="col-xs-12  col-md-3<?php echo 'left' === $repairpress_sidebar ? '  col-md-pull-9' : ''; ?>">
					<div class="sidebar" role="complementary">
						<?php
						if ( is_active_sidebar( 'blog-sidebar' ) ) {
							dynamic_sidebar( apply_filters( 'repairpress_blog_sidebar', 'blog-sidebar', get_the_ID() ) );
						}
						?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div><!-- #primary -->

<?php get_footer(); ?>

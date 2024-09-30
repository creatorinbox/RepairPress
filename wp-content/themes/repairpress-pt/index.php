<?php
/**
 * The main template file.
 *
 * Main blog page
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
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
				<?php if ( have_posts() ) : ?>

					<?php /* Start the Loop */ ?>
					<?php while ( have_posts() ) : the_post(); ?>

						<?php
							/*
							 * Include the Post-Format-specific template for the content.
							 * If you want to override this in a child theme, then include a file
							 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
							 */
							get_template_part( 'template-parts/content', get_post_format() );
						?>

					<?php endwhile; ?>

					<?php
						the_posts_pagination( array(
							'prev_text' => '<i class="fa fa-caret-left"></i>',
							'next_text' => '<i class="fa fa-caret-right"></i>',
						) );
					?>

				<?php else : ?>

					<?php get_template_part( 'template-parts/content', 'none' ); ?>

				<?php endif; ?>
			</main>

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
	</div>

<?php get_footer(); ?>

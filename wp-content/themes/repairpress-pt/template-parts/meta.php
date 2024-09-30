<div class="hentry__meta  meta">
	<?php if ( 'small' === get_theme_mod( 'blog_date_layout', 'big' ) ) :?>
		<time datetime="<?php the_time( 'c' ); ?>" class="published  meta__date"><?php echo get_the_date(); ?></time>
		<time class="hidden  updated"><?php the_modified_date(); ?></time>
		<span class="meta__separator">/</span>
	<?php endif; ?>
	<span class="vcard  author">
		<span class="meta__author"><?php esc_html_e( 'By ' , 'repairpress-pt' );?><span class="fn"><?php the_author(); ?></span></span>
	</span>
	<?php if ( has_category() ) { ?>
		<span class="meta__separator">/</span>
		<span class="meta__categories"><?php esc_html_e( '' , 'repairpress-pt' ); ?> <?php the_category( ' &bull; ' ); ?></span>
	<?php } ?>
	<?php if ( has_tag() ) { ?>
		<span class="meta__separator">/</span>
		<span class="meta__tags"><?php esc_html_e( '' , 'repairpress-pt' ); ?> <?php the_tags( '', ' &bull; ' ); ?></span>
	<?php } ?>
	<span class="meta__separator">/</span>
	<span class="meta__comments"><a href="<?php comments_link(); ?>"><?php RepairPressHelpers::pretty_comments_number(); ?></a></span>
</div>
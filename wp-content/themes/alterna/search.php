<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @since alterna 8.0
 */
get_header();
global $wp_query;
if($wp_query->found_posts > 6) {
    $have_more = true; 
}

// get page layout
$layout = alterna_get_page_layout('global'); 
$layout_class = alterna_get_page_layout_class('global');
?>
<div id="main" class="container">
	<div class="row">
        <section class="<?php echo $layout == 1 ? 'col-md-12 col-sm-12' : 'alterna-col col-lg-9 col-md-8 col-sm-8 alterna-'.$layout_class; ?>">

			<div class="home_post_list" id="search_result">
                <?php 
                if (have_posts()) {
                    ?>
                    <div class="row">
                        <?php
                    while(have_posts()) {
                            the_post();
                         ?>
                        <div class="col-md-4 col-sm-12 col-lg-3 post_list_item">
                            <a href="<?php echo the_permalink();?>" class="post_list_link">
                            <div class="post_list_img">
                                <?php echo the_post_thumbnail( 'medium', '' );?>
                            </div>
                            <div class="post_list_content">
                                <p class="post_list_title"><?php echo mb_substr(get_the_title(), 0, 18);?></p>
                                <li class="fa fa-calendar" style="margin-right: 3px;"> </li><span><?php echo limit_string(get_the_excerpt(), 30); ?></span>
                            </div>
                            </a>
                        </div>
                    <?php 
                    }
                    ?>
                    </div>
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-light load_more <?php echo $have_more ? '': 'd-none'?>">
                            More
                        </button>
                    </div>   
                    <?php
                    wp_reset_postdata();
                
                ?>
            </div>
            <?php
					alterna_content_pagination('nav-bottom' , 'pagination-centered');
				}else{ 
			?>
                <article id="post-0" class="entry-post no-results not-found">
					<header class="entry-header">
						<h1 class="entry-title"><?php _e( 'Nothing Found', 'alterna' ); ?></h1>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'alterna' ); ?></p>
						<?php get_search_form(); ?>
					</div><!-- .entry-content -->
				</article><!-- #post-0 -->
            <?php } ?>
        </section>
        <?php if($layout != 1) { ?> 
        <aside class="alterna-col col-lg-3 col-md-4 col-sm-4 alterna-<?php echo $layout_class;?>"><?php generated_dynamic_sidebar(); ?></aside>
        <?php } ?>
    </div>
</div>
<?php get_footer(); ?>
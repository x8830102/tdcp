<?php
/**
 * Template Name: histore event list
 *
 */
get_header();
global $paged;
$per_page_num = 10;
$page_cats = '最新活動';
$date = !empty($_GET['date']) ? $_GET['date'] : date('Y-m') ;
if($date) {
    $year = explode('-', $date)[0];
    $month = !empty(explode('-', $date)[1]) ? explode('-', $date)[1] : '';
    if (!empty($month)) {
        $date_query = array(
            'year' => $year,
            'month' => $month
        );
    } else {
        $date_query = array(
            'year' => $year,
        );
    }
    
}
?>
<div id="main" class="container">
    <div class="row">
        <section class="col-lg-9 col-md-8 col-sm-8">
            <?php 
                
                if($paged == 0){
                    $paged = 1;
                }
                
                $args = array(  'post_type' => 'post',
                                'post_status' => 'publish',
                                'paged' => $paged,
                                'posts_per_page'=> $per_page_num,
                                'category_name' => $page_cats
                             );
                $args['date_query'] = $date_query;

                // The Query
                query_posts($args);
                
                // blog posts
                if ( have_posts() ) {
                    while ( have_posts() ) { 
                        the_post();
                        get_template_part( 'template/blog/content', get_post_format() );
                    }
                    alterna_content_pagination('nav-bottom' , 'pagination-centered');
                }else{
                     get_template_part( 'template/blog/content', 'none' );
                }
                
                wp_reset_postdata();
            ?>
        </section>
        <aside class="alterna-col col-lg-3 col-md-4 col-sm-4?>"><?php generated_dynamic_sidebar(); ?></aside>

    </div>
</div>
<?php get_footer(); ?>
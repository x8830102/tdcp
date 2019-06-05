<?php
/**
 * Template Name: histore event list
 *
 */
get_header();
global $paged;
$per_page_num = 10;
$page_cats = '最新活動';
$searh_date = '';
$date = !empty($_GET['date']) ? $_GET['date'] : date('Y-m', strtotime('-1 year', date('Y-m')) ;
if($date) {
    $year = explode('-', $date)[0];
    $month = !empty(explode('-', $date)[1]) ? explode('-', $date)[1] : '';
    if (!empty($month)) {
        $searh_date = ($year-1).'-'.$month;
    } else {
        $searh_date = $year;
    }
}
?>
<div id="main" class="container">
    <div class="row">
        <section class="col-lg-9 col-md-8 col-sm-8 histore-list">
            <?php 
                
                if($paged == 0){
                    $paged = 1;
                }
                
                $args = array(  'post_type' => 'post',
                                'post_status' => 'publish',
                                'paged' => $paged,
                                'posts_per_page'=> $per_page_num,
                                'category_name' => $page_cats,
                                'meta_query' => array(
                                    array(
                                          'key'   => 'event_start_date',
                                          'compare' => 'LIKE',
                                          'value'   => $searh_date
                                      ),
                                    )
                             );
                // $args['date_query'] = $date_query;

                // The Query
                query_posts($args);
                
                // blog posts
                
                if ( have_posts() ) {
                    while ( have_posts() ) { 
                        the_post(); 
            ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('post-entry search-item'); ?> itemscope itemtype="http://schema.org/Article">
                    <?php if(has_post_thumbnail(get_the_ID())) { ?>
                    <aside class="post-thumbnail">
                            <div class="post-img">
                                <?php
                                echo get_the_post_thumbnail(get_the_ID(), array('150', '150') , array('alt' => get_the_title(),'title' => ''));
                                ?>
                            </div>
                    </aside>
                    <?php } ?>
                    <section class="post-content">
                        <header class="entry-header">
                        <?php the_title( '<h3 class="entry-title" itemprop="name"><a href="' . esc_url( get_permalink() ) . '" itemprop="url">', '</a></h3>' ); ?>
                            <div class="entry-meta">
                                
                                <span class="entry-date"><i class="fa fa-clock-o"></i><a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark"><time class="entry-date updated" itemprop="datePublished" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( date('Y-m-d',strtotime(get_field('event_start_date', get_the_ID()))) ); ?></time></a></span>
                                <span class="author vcard"> <i class="fa fa-user"></i><a class="url fn n" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author"><span itemprop="author" itemscope itemtype="http://schema.org/Person"><span itemprop="name"><?php echo get_the_author(); ?></span></span></a></span>
                            </div>
                        </header>
                        <div class="entry-summary" itemprop="articleSection">
                        <?php echo limit_string(get_the_excerpt(), 44); ?>
                        </div>
                    </section>
                </article>
            <?php   
                    }
                    alterna_content_pagination('nav-bottom' , 'pagination-centered');
                }else{ 

                     get_template_part( 'template/blog/content', 'none' );
                }
                
                wp_reset_postdata();
            ?>
        </section>
        <aside class="alterna-col col-lg-3 col-md-4 col-sm-4 histore-sidebar"><?php generated_dynamic_sidebar(); ?></aside>

    </div>
</div>
<?php get_footer(); ?>
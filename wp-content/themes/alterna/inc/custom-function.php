<?php

add_action( 'get_post_list', 'get_post_list_func', 10, 2 );

function get_post_list_func( $term_name, $posts_per_page, $offset=0) {

    $arg = array(
        'category_name' => $term_name,
        'posts_per_page' => $posts_per_page,
        'offset'  => $offset
    );
    $query = new WP_Query($arg);

    ?>
    <div class="home_post_list">
        <?php 
        if ($query->have_posts()) {
            ?>
            <div class="row">
                <?php
            while($query->have_posts()) {
                $query->the_post();
                 ?>
                <div class="col-md-4 col-sm-12 col-lg-3 post_list_item">
                    <a href="<?php echo the_permalink();?>" class="post_list_link">
                    <div class="post_list_img">
                        <?php echo the_post_thumbnail( 'post-thumbnail', '' );?>
                    </div>
                    <div class="post_list_content">
                        <p class="post_list_title"><?php echo the_title();?></p>
                        <li class="fa fa-calendar" style="margin-right: 3px;"> </li><span><?php echo get_the_excerpt(); ?></span>
                    </div>
                    </a>
                </div>
            <?php 
            }
            ?>
            </div>
            <div class="col-md-12 text-center">
                <button type="button" class="btn btn-light load_more" data-term_name="園區動態" data-active_tab="home">
                    More
                </button>
            </div>   
            <?php
            wp_reset_postdata();
        }
        ?>
    </div>
    <?php
}

//add custom field to rest api
add_action( 'rest_api_init', 'create_api_posts_field' );
function create_api_posts_field() {
 
    // register_rest_field ( 'name-of-post-type', 'name-of-field-to-return', array-of-callbacks-and-schema() )
    register_rest_field( 'post', 'post_img', array(
           'get_callback'    => 'get_post_thumbnail_for_api',
           'schema'          => null,
        )
    );
}
function get_post_thumbnail_for_api( $object ) {
    //get the id of the post object array
    $post_id = $object['id'];
 
    //return the post meta
    return  get_the_post_thumbnail_url($post_id,'medium');
}

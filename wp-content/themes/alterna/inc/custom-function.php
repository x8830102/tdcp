<?php

add_action( 'get_post_list', 'get_post_list_func', 10 );

function get_post_list_func() {

    $arg = array(
        'category_name' => '園區動態',
        'posts_per_page' => 9
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
            <?php
            wp_reset_postdata();
        }
        ?>
    </div>
    <?php
    

    // return $output;
}
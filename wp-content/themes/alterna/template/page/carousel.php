<?php
/**
 * Home Page Carousel
 * 
 * @since tdcp 1.0
 */
?>
    <div class="row">
        <div class="col-md-12 slider_wrapper">
            <?php 
                $slider_content = get_field('slider_content');
            ?>
            <div style="display: none;">
                <?php 
                    // print_r($slider_content);
                ?>
            </div>
            <div id="carousel-main" class="carousel slide" data-ride="carousel">
                    <!-- Indicators -->
                    <ol class="carousel-indicators">
                        <?php
                        foreach ( $slider_content as $key => $value ) {
                            // print_r( $value );
                            if ( 'attachment' === $value->post_type ) {
                                $slider_id[ $key ] = $value->ID;
                                $slider_url[ $key ] = $value->post_content;
                                $slider_img[ $key ] = $value->guid;
                                $slider_excerpt[ $key ] = $value->post_excerpt;
                                $slider_date[ $key ] = $value->post_date;
                            } else {
                                $slider_img[ $key ] =  get_the_post_thumbnail_url(  $value->ID , 'large' );
                                $slider_id[ $key ] = $value->ID;
                                $slider_url[ $key ] = $value->guid;
                                $slider_title[ $key] = $value->post_title;
                                $slider_excerpt[ $key ] = $value->post_excerpt;
                                
                            }
                            
                            if ( 0 === $key ) {
                                $active = ' active';
                            } else {
                                $active = '';
                            }
                        ?>
                            <li data-target="#carousel-main" data-slide-to="<?php echo $key; ?>" class="<?php echo $active; ?>"></li>
                        <?php
                        }
                        ?>
                    </ol>

                    <!-- Wrapper for slides -->
                    <div class="carousel-inner" role="listbox">
                        <?php
                        foreach ( $slider_content as $key => $value ) {
                            if ( isset( $slider_id[ $key ] ) ) {
                                if ( 0 === $key ) {
                                    $active = ' active';
                                } else {
                                    $active = '';
                                }
                        ?>
                                <div class="item <?php echo $active; ?>">
                                    <a href="<?php echo $slider_url[ $key ]; ?>">
                                        <div class="item-img" style="background-image:url('<?php echo $slider_img[ $key ]; ?>')"></div>
                                        <!-- <img src="<?php echo $slider_img[ $key ]; ?>" alt="<?php echo $slider_title[ $key ]; ?>">
  -->
                                        <div class="item_shadow">
                                            <div class="carousel-caption col-md-8 hidden-xs">
                                                <h3 class="title">
                                                    <?php echo $slider_title[ $key ]; ?>
                                                </h3>
                                                <div class="excerpt">
                                                    <?php echo $slider_excerpt[ $key ]; ?>
                                                </div>
                                            </div>
                                            <div class="carousel-caption col-md-12 hidden-lg">
                                                <h3 class="title">
                                                    <?php echo $slider_title[ $key ]; ?>
                                                </h3>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
        </div>
    </div>

<?php
wp_register_sidebar_widget(
    'event_post_date',      // wpdocs unique widget id
    'Event Post Date',        // widget name
    'event_post_date',    // callback function
    array(              // options
        ''
    )
);
 
/**
 * Display the wpdocs widget
 */
function event_post_date($args) {
   echo $args['before_widget'];
   echo $args['before_title'] . '歷年活動' .  $args['after_title'];
   echo $args['after_widget'];
   // Print some HTML for the widget to display here.
   for( $year= 2000; $year<=(int)date('Y'); $year++ ) {
       $arg = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'category_name' => '最新活動',
            'meta_query' => array(
            array(
                  'key'   => 'event_start_date',
                  'compare' => 'LIKE',
                  'value'   => $year,
              ),
            
            )
        );
        $query = new WP_Query($arg);
        if ( $query->have_posts() ) {
            ?>
            <div>
            <ul>
                <li><a href="?date=<?php echo $year;?>"><?php echo $year;?></a></li>
            <?php
            while ($query->have_posts() ) {
                $query->the_post();
                ?>
                    <ul>
                        <li><a href="?date=<?php echo $year.'-'.get_the_date('m');?>"><?php echo get_the_date('M');?></a></li>
                    </ul>
                <?php
            }
            ?>
            </ul>
            </div>
            <?php
            wp_reset_postdata();
        }
    }
}
add_action( 'get_post_list', 'get_post_list_func', 10, 3 );

function get_post_list_func( $term_name, $posts_per_page, $offset=0) {

    $arg = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'category_name' => $term_name,
        'posts_per_page' => $posts_per_page,
        'offset'  => $offset
    );
    $query = new WP_Query($arg);

    ?>
    <div class="home_post_list container">
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
                        <li class="fa fa-calendar" style="margin-right: 3px;"> </li><span><?php echo wp_trim_words(get_the_excerpt(),95,'[...]'); ?></span>
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
    register_rest_field( 'post', 'trim_excerpt', array(
           'get_callback'    => 'get_post_trim_excerpt_for_api',
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
function get_post_trim_excerpt_for_api( $object ) {
    //get the id of the post object array
    $post_id = $object['id'];

    //return the post meta
    return  wp_trim_words(get_the_excerpt($post_id), 95, '[...]');
}
//根據活動日期取得當月份所有文章
add_action( 'wp_ajax_nopriv_get_post_list_by_date', 'get_post_list_by_date_func', 10, 2 );
add_action( 'wp_ajax_get_post_list_by_date', 'get_post_list_by_date_func', 10, 2 );
function get_post_list_by_date_func(){
    //$term_name,$posts_per_page, $offset=0, $date
    $term_name = sanitize_text_field($_POST['term_name']) ?  sanitize_text_field($_POST['term_name']) : '最新活動';
    $posts_per_page = absint( $_POST['posts_per_page'] ) ? absint( $_POST['posts_per_page'] ) : 6;
    $offset = absint($_POST['offset']) ? absint($_POST['offset']) : 0;
    $month = !empty( $_POST['event_date'] ) ? sanitize_text_field( $_POST['event_date'] ) : date('m');

    $args = array (
        'post_type' => 'post',
        'post_status' => 'publish',
        'category_name' => $term_name,
        'posts_per_page' => $posts_per_page,
        'offset'  => $offset,
        'meta_query' => array(
        array(
              'key'   => 'event_start_date',
              'compare' => 'LIKE',
              'value'   => date('Y').$month,
          ),
        
        ),
    );
    // get posts
    $posts['posts'] = get_posts($args);
    foreach ($posts['posts'] as $key => $value) {
        $posts['posts'][$key]->post_img = get_the_post_thumbnail_url($value->ID, 'medium');
    }

    $current_month_daycount = date("t", strtotime(date("Y-").$month."-1"));
    $calendar ='';
    for( $i=1;$i<=$current_month_daycount;$i++) {
        $color = '#b9a4c7';
        $day = str_pad($i, 2, '0', STR_PAD_LEFT);
        $week = date("D", strtotime(date("Y").$month.$day));
        //取得當月每日的文章
        $args = array (
            'post_type' => 'post',
            'post_status' => 'publish',
            'category_name' => $term_name,
            'posts_per_page' => $posts_per_page,
            'offset'  => $offset,
            'meta_query' => array(
            array(
                  'key'   => 'event_start_date',
                  'compare' => 'LIKE',
                  'value'   => date('Y').$month.$day,
              ),
            
            ),
        );
        $daliy_post = get_posts($args);
        // 取得當月每日的文章 End

        // Mobile HTML 
        if(!empty($daliy_post)) {
            $calendar_mobile = '
            <div class="calendar_mobile_column">
                <div class="calendar_mobile_column_head">
                    <span class="month_mobile_text">' . date('F',strtotime(date("Y-").$month."-1")) . '</span>
                </div>
                <div class="calendar_mobile_column_content">
                    <ul style="color: #d06670;border-bottom: 3px solid #d06670;">
                        <li class="mobile_event_date_item">日期</li>
                        <li class="mobile_event_name_item">活動名稱</li>
                    </ul>
                    <ul style="color: #000;">';
            foreach ($daliy_post as $key => $value) {
                $calendar_mobile .= '<a href="' .$value->guid. '" class="d-flex" style="width:100%;"><li class="mobile_event_date_item"><span>' . $month.'/'.str_pad($i,2,'0',STR_PAD_LEFT) . '</span></li>';
                $calendar_mobile .= '<li class="mobile_event_name_item"><span>' . $value->post_title . '</span></li></a>';
            }
            $calendar_mobile .='
                    </ul>
                </div>
            </div>';
        }
        // Mobile HTML End

        // PC HTML
        if ($i==1 || $i==11 || $i==21) {
            $calendar .= '<div class="calendar_column">';
            $calendar .= '<ul>';
        }
        if($week == 'Sat' || $week == 'Sun'){
            $color ='#b0add0';
        }
            $calendar .= '<a href="' .$daliy_post[0]->guid. '"><li>';
            $calendar .= '<div class="calendar_month" style="background-color: '.$color.';">'.$i.'</div>';
            $calendar .= '<div class="calendar_week" style="color: '.$color.';">'.$week.'</div>';
            $calendar .= '<div class="calendar_event" style="background-color: '.$color.';">'.$daliy_post[0]->post_title.'</div>';
            $calendar .= '</li></a>';
        if($i == 10 || $i==20 || $i==$current_month_daycount){
            $calendar .= '</ul>';
            $calendar .= '</div>';
        }
    }
    $calendar .= '<div class="calendar_column d-flex flex-column justify-content-around">';
    $calendar .= '<span class="month_text">' . date('F',strtotime(date("Y-").$month."-1")) . '</span>';
    $calendar .= '<img src="' . get_template_directory_uri(). '/img/custom/calendar_img.png" alt="">';
    $calendar .= '</div>';
    // PC HTML
             
    $posts['calendar_html'] = $calendar;
    $posts['calendar_mobile_html'] = $calendar_mobile;
    echo json_encode($posts);
    die();
}
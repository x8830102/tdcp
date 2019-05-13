<?php
/**
 * Template Name: custom home page
 *
 */

get_header();
// $today = date('Ymd');
// $args = array (
//     'post_type' => 'post',
//     'meta_query' => array(
//     array(
//           'key'   => 'event_start_date',
//           'compare' => 'LIKE',
//           'value'   => 201904,
//       ),
    
//     ),
// );

// // get posts
// $posts = get_posts($args);
// print_r($posts);
?>
<?php get_template_part('template/page/carousel');?>

<div class="container">
    <div class="row">
        <div class="col-md-12 social_link d-flex">
            <a href="https://www.facebook.com/PunPlace" class="social_icon"><img src="<?php echo get_template_directory_uri();?>/img/fb_icon.png" alt="fb_icon"></a>
            <a href="http://pse.ee/5TBVX" class="social_icon"><img src="<?php echo get_template_directory_uri();?>/img/line_icon.png" alt="line_icon"></a>
            <a href="https://www.instagram.com/fablabtainan/" class="social_icon"><img src="<?php echo get_template_directory_uri();?>/img/ig_icon.png" alt="ig_icon"></a>
            <a href="https://www.youtube.com/channel/UCxROwE5Wni7VFQsBOE79HKA" class="social_icon"><img src="<?php echo get_template_directory_uri();?>/img/yb_icon.png" alt="yb_icon"></a>
            <?php get_search_form();?>
        </div>
        <ul class="nav nav-tabs col-md-12 flex-row flex-sm-row justify-content-end" id="homeTab" role="tablist">
          
          <li class="nav-item">
            <a class="nav-link active" id="event-tab" data-toggle="tab" href="#event" role="tab" aria-controls="event" aria-selected="true">最新活動</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="false">園區動態</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="news-tab" data-toggle="tab" href="#news" role="tab" aria-controls="news" aria-selected="false">自造新聞</a>
          </li>
        </ul>
    </div>
</div>
<div calss="container-fluid" style="background-color: #fff">
  <div class="container" style="background-color: #f7e4e4 ">
    <div class="tab-content">

      <div class="tab-pane fade " id="home" role="tabpanel" aria-labelledby="home-tab">
        <?php do_action('get_post_list', '園區動態', '6', 0);?>
      </div>

      <div class="tab-pane fade show active" id="event" role="tabpanel" aria-labelledby="profile-tab">
        <!-- 月份Tabs -->
        <ul class="nav nav-pills home_pills" role="tablist">
            <a href="歷史活動" class="nav-link">歷史活動</a>
        <?php 
        for($i=1;$i<=12;$i++){
          if($i<10){
            $date = str_pad($i,2,"0",STR_PAD_LEFT);
          } else {
            $date = $i;
          }
          
          $args = array (
              'post_type' => 'post',
              'category_name' => '最新活動',
              'posts_per_page' => 6,
              'meta_query' => array(
                array(
                      'key'   => 'event_start_date',
                      'compare' => 'LIKE',
                      'value'   => date('Y').$date
                  ),
                )
          );
          // get posts
          $posts = get_posts($args);
          if(!empty($posts)){
            ?>
            <li class="nav-item">
              <a href="" data-toggle="pill" data-event_date="<?php echo $date;?>" role="tab" class="nav-link event_nav_link"><?php echo $i.'月';?></a>
            </li>
            <?php
          }
        }
      ?>
        </ul>
        <!-- 月份Tabs End  -->
        <div class="home_post_list">
          <!--活動行事曆-->
          <div class="calendar_container">
            <div class="col-lg-12 calendar_background">
              <div class="calendar_content d-none d-lg-flex fade show">

              </div>
              <div class="calendar_mobile_content d-lg-none fade show">

              </div>
            </div>
          </div>
          <!--活動行事曆 End-->
          <div class="row">
          </div>
        </div>
        <?php 
          //do_action('get_post_list_by_date', '最新活動', '6', 0, 201904);
        ?>
      </div>

      <div class="tab-pane fade" id="news" role="tabpanel" aria-labelledby="contact-tab">
        <ul class="nav nav-pills home_pills" role="tablist">
          <li class="nav-item">
            <a href="#new_knowledge" data-toggle="pill" role="tab" class="nav-link active">南創新知</a>
          </li>
          <li class="nav-item">
            <a href="#documentary" data-toggle="pill" role="tab" class="nav-link">南創紀實</a>
          </li>
          <li class="nav-item">
            <a href="#story" data-toggle="pill" role="tab" class="nav-link">南創故事</a>
          </li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane fade show active" role="tabpanel" id="new_knowledge">
            <?php do_action('get_post_list', '南創新知', '6', 0);?>
          </div>
          <div class="tab-pane fade" role="tabpanel" id="documentary">
            <?php do_action('get_post_list', '南創紀實', '6', 0);?>
          </div>
          <div class="tab-pane fade" role="tabpanel" id="story">
            <?php do_action('get_post_list', '南創故事', '6', 0);?>
          </div>
        </div>
      </div>
    </div>
    
  </div>
</div>


    
<?php get_footer(); ?> 
<?php
/**
 * Template Name: custom home page
 *
 */

get_header();

?>
<?php get_template_part('template/page/carousel');?>
<div class="container">
    <div class="row">
        <div class="col-md-12 social_link">
            <a href="#" class="social_icon" ><img src="<?php echo get_template_directory_uri();?>/img/fb_icon.png" alt="fb_icon"></a>
            <a href="#" class="social_icon"><img src="<?php echo get_template_directory_uri();?>/img/line_icon.png" alt="line_icon"></a>
            <a href="#" class="social_icon"><img src="<?php echo get_template_directory_uri();?>/img/ig_icon.png" alt="ig_icon"></a>
            <a href="#" class="social_icon"><img src="<?php echo get_template_directory_uri();?>/img/yb_icon.png" alt="yb_icon"></a>
        </div>
        <ul class="nav nav-tabs col-md-12 flex-column flex-sm-row" id="homeTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">園區動態</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#event" role="tab" aria-controls="event" aria-selected="false">最新活動</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#news" role="tab" aria-controls="news" aria-selected="false">自造新聞</a>
          </li>
        </ul>

        <!-- <div class="col-md-12 d-md-none">
            <ul class="nav nav-tabs">
              <li role="presentation" class="active"><a href="#home" data-toggle="tab">動態</a></li>
              <li role="presentation" ><a href="#event" data-toggle="tab">活動</a></li>
              <li role="presentation"><a href="#news" data-toggle="tab">新聞</a></li>
            </ul>
        </div> -->
    </div>
</div>
<div calss="container-fluid" style="background-color: #fff">
    <div class="container" style="background-color: #f7e4e4 ">
      <div class="tab-content">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
          <?php do_action('get_post_list', '園區動態', '6', 0);?>
          </div>
        <div class="tab-pane fade" id="event" role="tabpanel" aria-labelledby="profile-tab">
          <?php do_action('get_post_list', '最新活動', '6', 0);?>
        </div>
        <div class="tab-pane fade" id="news" role="tabpanel" aria-labelledby="contact-tab">
          <?php do_action('get_post_list', '最新活動', '6', 0);?>
        </div>
      </div>
    </div>
</div>
<?php get_footer(); ?> 
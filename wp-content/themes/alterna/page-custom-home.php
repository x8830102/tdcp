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
        <div class="col-md-12 hidden-xs ">
            <ul class="nav nav-tabs">
              <li role="presentation" class="active"><a href="#home" data-toggle="tab">園區動態</a></li>
              <li role="presentation" ><a href="#event" data-toggle="tab">最新活動</a></li>
              <li role="presentation"><a href="#news" data-toggle="tab">自造新聞</a></li>
            </ul>
        </div>
        <div class="col-md-12 hidden-lg hidden-md hidden-sm">
            <ul class="nav nav-tabs">
              <li role="presentation" class="active"><a href="#home" data-toggle="tab">動態</a></li>
              <li role="presentation" ><a href="#event" data-toggle="tab">活動</a></li>
              <li role="presentation"><a href="#news" data-toggle="tab">新聞</a></li>
            </ul>
        </div>
    </div>
</div>
<div calss="container-fluid" style="background-color: #fff">
    <div class="container" style="background-color: #f7e4e4 ">
        <div class="tab-content clearfix">
                <div class="tab-pane active" id="home">
                  <h3>Standard </h3>
                </div>
                <div class="tab-pane" id="event">
                  <h3>Standard tab panel created </h3>
                </div>
                <div class="tab-pane" id="news">
                  <h3>Standard tab panel created on bootstrap using nav-tabs</h3>
                </div>
            </div>
    </div>
</div>

<?php get_footer(); ?> 
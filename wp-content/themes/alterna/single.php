<?php
/**
 * Single Post Content
 *
 * @since alterna 8.0
 */
get_header();
// get page layout 

$layout 		= alterna_get_page_layout(); 
$layout_class 	= alterna_get_page_layout_class();
$sidebar_name	= penguin_get_post_meta_key('sidebar-type');
// get blog default sidebar
if($sidebar_name == '' || $sidebar_name == "Global Sidebar"){
	$blog_page_id = get_option('page_for_posts');
	if(intval($blog_page_id) != 0){
		$sidebar_name = penguin_get_post_meta_key('sidebar-type', $blog_page_id);
	}
}
?>
<div class="container-fuild page_top_banner">
    <div class="row top_banner p-5 justify-content-center">
        <a href="<?php echo get_field('page_top_banner_link', 2);?>"><img src="<?php echo get_field('page_top_banner_img', 2);?>" alt=""></a>
    </div>
</div>
<?php
$categories = get_the_category();
$is_event_post = false;
foreach ($categories as $key => $value) {
    if($value->name == '最新活動'){
        $is_event_post = true;
    }
}
if(!$is_event_post) {

    ?>
    <div class="container post_image_container">
    <?php
    if(has_post_thumbnail(get_the_ID())){
        $attachment_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
    ?>
    <div class="single_img" style="background-image: url(<?php echo esc_url($attachment_image[0]); ?>)"></div>
   <!--  <img src="<?php echo esc_url($attachment_image[0]); ?>" alt="<?php echo get_the_title(); ?>" /> -->
        <!-- <div class="post_image_content" style="background-image: url(<?php echo esc_url($attachment_image[0]); ?>)">
            <img src="<?php echo esc_url($attachment_image[0]); ?>" alt="<?php echo get_the_title(); ?>" />
            
            
        </div> -->
    <div class="post_category_container">
        <span class="post_category"><?php echo $categories[0]->name;?></span>
    </div>
    <?php
    }
    ?>
    </div>
    <?php
}
?>
<div id="main" class="container single_page_container">
	<section class="<?php echo $layout == 1 ? 'col-md-12 col-sm-12' : 'alterna-col col-lg-9 col-md-8 col-sm-8 alterna-'.$layout_class; ?>">
    	<?php
		 if ( have_posts() ) {
			while ( have_posts() ){
				the_post(); 
				?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('entry-post');?> itemscope itemtype="http://schema.org/Article">
					<?php
                    if(get_post_format() == "image"){
                        if(has_post_thumbnail(get_the_ID())){
                            $attachment_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'post-thumbnail');
                            $full_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full'); 
                    ?>
                    <div class="post-element-content">
                    <a href="<?php echo esc_url($full_image[0]); ?>" class="fancyBox">
                        <div class="post-img">
                            <img src="<?php echo esc_url($attachment_image[0]); ?>" alt="<?php echo get_the_title(); ?>" />
                        </div>
                     </a>
                     </div>
                    <?php
                        }
                    }else if(get_post_format() == "gallery"){ 
                    ?>
                    <div class="post-element-content">
                    <div class="flexslider alterna-fl post-gallery">
                        <ul class="slides">
                            <?php $attachment_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'post-thumbnail'); ?>
                            <?php $full_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full'); ?>
                            <li>
                                <a href="<?php echo esc_url($full_image[0]); ?>" class="fancybox-thumb" rel="fancybox-thumb[<?php echo get_the_ID(); ?>]"><img src="<?php echo esc_url($attachment_image[0]); ?>" alt="" ></a>
                            </li>
                            <?php echo alterna_get_gallery_list(get_the_ID() ,'post-thumbnail'); ?>
                        </ul>
                    </div>
                    </div>
                    <?php
                    }else if(get_post_format() == "video") {
                    ?>
                    <div class="post-element-content">
                    <?php
                        $video_type 	= penguin_get_post_meta_key('video-type');
                        $video_content 	= penguin_get_post_meta_key('video-content');
                            if($video_content && $video_content != ''){
                            if(intval($video_type) == 0){
                                echo do_shortcode('[youtube id="'.$video_content.'" width="100%" height="300"]');
                            }else if(intval($video_type) == 1){
                                echo do_shortcode('[vimeo id="'.$video_content.'" width="100%" height="300"]');
                            }else{
                               echo $video_content;
                            }
                        }
                    ?>
                    </div>
                    <?php
                    }else if(get_post_format() == "audio"){
                    ?>
                    <div class="post-element-content">
                    <?php
                        $audio_type 	= penguin_get_post_meta_key('audio-type');
                        $audio_content 	= penguin_get_post_meta_key('audio-content');
                        if($audio_content && $audio_content != ''){
                           if(intval($audio_type) == 0){
                             echo do_shortcode('[soundcloud url="'.$audio_content.'"]');
                           }else{
                               echo $audio_content;
                           }
                        }
                    ?>
                    </div>
                    <?php
                    }else if(get_post_format() == "quote") {
                    ?>
                    <div class="post-element-content">
                    <?php
                        echo '<div class="post-quote-entry"><div class="post-quote-icon"></div>'.get_the_content().'</div>';
                    ?>
                    </div>
                    <?php
                    }
                    ?>
                    <!-- <header class="entry-header">
                        <?php the_title( '<h3 class="entry-title" itemprop="name"><a href="' . esc_url( get_permalink() ) . '" itemprop="url">', '</a></h3>' ); ?>
                        <div class="post-meta">
                            <div class="post-date"><i class="fa fa-calendar"></i><span class="entry-date updated" itemprop="datePublished"><?php echo get_the_date(); ?></span></div>
                            <div class="post-author"><i class="fa fa-user"></i><?php _e('by','alterna'); ?> <span itemprop="author"><?php if(intval(penguin_get_options_key('blog-author-link')) == 0) { the_author_link(); }else{ the_author_posts_link(); }?></span></div>
                            <div class="cat-links"><i class="fa fa-folder-open"></i><span itemprop="genre"><?php 	
                                $categories = get_the_category();
                                $seperator = ' , ';
                                $output = '';
                                if($categories){
                                    foreach($categories as $category) {
                                        $output .= '<a href="'.get_category_link($category->term_id ).'" title="' . esc_attr( sprintf( __( "View all posts in %s",'alterna'), $category->name ) ) . '">'.$category->cat_name.'</a>'.$seperator;
                                    }
                                echo trim($output, $seperator);
                            }
                            ?></span></div>
                            <?php if (  penguin_get_options_key('blog-comment-hide') == 'on' && ! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments' ) ){
                    
                    }else{ ?>
                            <div class="post-comments"><i class="fa fa-comments"></i><a href="<?php echo get_permalink(get_the_ID()).'#comments'; ?>" itemprop="interactionCount"><?php comments_number(__('No Comment','alterna'),__('1 Comment','alterna'),__('% Comments','alterna')); ?></a></div>
                            <?php } ?>
                            <?php edit_post_link(__('Edit', 'alterna'), '<div class="post-edit"><i class="fa fa-edit"></i>', '</div>'); ?>
                        </div>
                    </header> -->
                    
                    <?php if(get_post_format() != "quote") { ?>
                    <?php if(!$is_event_post) { ?>
                    <h2 class="post_title"><?php the_title();?></h2>
                    <div class="post_date">
                        <span><li class="fa fa-calendar" style="margin-right: 3px;"></li>發佈時間 :<?php echo get_the_date('Y/m/d');?></span>
                    </div>
                    <?php }?>
                    <div class="entry-content" itemprop="articleBody">
                        <?php the_content(); ?>
                        <?php wp_link_pages(); ?>
                        <div class="share_btn_container">
                            <div class="share_text">分享</div>
                            <!-- <div class="shareaholic-canvas" data-app="share_buttons" data-app-id-name="post_below_content">
                                
                            </div> -->
                            <a href="javascript:void(0);" class="fb share">
                                <li class="share-item"><img src="<?php echo get_template_directory_uri();?>/img/fb_icon.png"></li>
                            </a>
                            <a href="javascript:void(0);" class="fb send">
                                <i class="fab fa-facebook-messenger"></i>
                                <li class="share-item"><img src="<?php echo get_template_directory_uri();?>/img/messenger_icon.png"></li>
                            </a>
                            <a href='javascript: void(window.open(&apos;https://lineit.line.me/share/ui?url=&apos; .concat(encodeURIComponent(location.href+"?utm_campaign=tdcp&utm_medium=event&utm_source=line")) ));' title='分享給 LINE 好友'>
                                <li class="share-item"><img src="<?php echo get_template_directory_uri();?>/img/line_icon.png"></li>
                            </a>
                            <?php 
                                $tilte = urlencode(get_the_title());
                                $start_date =  date('Ymd', strtotime(get_field('event_start_date', get_the_ID()))) . 'T'.
                                    date('His', strtotime('-8 hours',strtotime(get_field('event_start_date', get_the_ID())))). 'Z';
                                $end_date = date('Ymd', strtotime(get_field('event_end_date', get_the_ID()))) . 'T'.
                                    date('His', strtotime('-8 hours',strtotime(get_field('event_end_date', get_the_ID())))). 'Z';
                                $dates = $start_date . '/' . $end_date;
                                $details = urlencode(get_field('google_calendar_description', get_the_ID()));
                                $location = !empty(get_field('google_calendar_location', get_the_ID())) ? urlencode(get_field('google_calendar_location', get_the_ID())) : urlencode('台南市中西區南門路21號');
                            ?>
                            <?php if($is_event_post) { ?>
                                <a href="https://www.google.com/calendar/render?action=TEMPLATE&text=<?php echo $tilte; ?>&dates=<?php echo $dates; ?>&details=<?php echo $details; ?>&location=<?php echo $location; ?>&sf=true&output=xml" target="_blank" rel="nofollow">
                                    <li>
                                        <img src="<?php echo get_template_directory_uri();?>/img/calendar_icon.png">
                                    </li>
                                </a>
                            <?php }?>
                            
                        </div>

                        
                        
                        
                    </div>

                    <?php } ?>
                    
                    <?php
                    $tags = get_the_tags(); 
                    if($tags && count($tags) > 0) {
                    ?>
                    <div class="entry-tags"><div class="post-tags-icon"><i class="fa fa-tags"></i></div><?php _e('Tagged: ', 'alterna' ); ?> <span itemprop="keywords"><?php the_tags('',', ','');?></span></div>
                    <?php } ?>
                    
                    <?php if(penguin_get_options_key('blog-enable-share') == "on") { ?>
                    <div class="post-share">
                        <div class="alterna-title">
                            <h3><?php _e('Share This Story!' , 'alterna'); ?></h3>
                            <div class="line"></div>
                        </div>
                        <div class="post-share-code">
                            <?php echo	penguin_get_options_key('blog-share-code'); ?>
                         </div>
                    </div>
                    <?php } ?>
                    
                    <?php if(penguin_get_options_key('blog-enable-author') == "on") { ?>
                    <div class="post-about-author">
                        <div class="alterna-title">
                            <h3><?php _e('About Author' , 'alterna'); ?></h3>
                            <div class="line"></div>
                        </div>
                    
                        <?php $author_info = get_userdata($post->post_author);?>
                        <div class="post-author-details">
                            <div class="gravatar">
                                <?php echo get_avatar($author_info->ID, 80 ); ?>
                            </div>
                            <div class="author-meta">
                                <span class="author-name"><?php if(intval(penguin_get_options_key('blog-author-link')) == 0) { ?>
                                <a href="<?php echo $author_info->user_url;?>"><?php the_author();?></a>
                            <?php }else{
                                the_author_posts_link();
                            }?></span>
                                <div class="author-desc">
                                <?php echo $author_info->user_description; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    
                    <?php if(penguin_get_options_key('blog-related-enable') == "on") { ?>
                    <div class="post-related">
                        <div class="alterna-title">
                            <h3><?php _e('You may also like' , 'alterna'); ?></h3>
                            <div class="line"></div>
                        </div>
                        <?php
                        $related_style = intval(penguin_get_post_meta_key('related-items-style'));
                        $show_number = intval(penguin_get_options_key('blog-related-num'));
                        if($related_style == 0){
                            $related_style = intval(penguin_get_options_key('blog-related-style')) + 1;
                        }
                        if($show_number == 0){
                            $show_number = 3;
                        }
                        $categories = get_the_category();
                        $slugs = '';
                        if($categories){
                            foreach($categories as $category) {
                                if($slugs != ''){ $slugs .= ',';}
                                $slugs .= $category->term_id;
                            }
                        }
                        echo do_shortcode('[blog_list columns="3" number="'.esc_attr($show_number).'" style="'.esc_attr($related_style).'" type="related" cat__in="'.esc_attr($slugs).'" post__not_in="'.get_the_ID().'"]'); ?>
                    </div>
                    <?php } ?>
                    
                    <?php //comments_template(); ?>
                    
                    <?php //alterna_single_content_nav('single-nav-bottom'); ?>
                </article>
        <?php
			}
		 }else{
			 get_template_part( 'content', 'none' );
		 }
		?>
	</section>
    <?php if($layout != 1) { ?> 
    <aside class="alterna-col col-lg-3 col-md-4 col-sm-4 alterna-<?php echo $layout_class;?>"><?php generated_dynamic_sidebar($sidebar_name); ?></aside>
    <?php } ?>
</div>
<?php get_footer(); ?>
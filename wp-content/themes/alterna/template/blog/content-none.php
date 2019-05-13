<?php
/**
 * No Post
 * 
 * @since alterna 7.0
 */
 ?>
 <style>
html,body,.wrapper{
    height: 100%;
}
.footer-wrap {
    position: absolute;
    width: 100%;
    bottom: 0;
}
</style>
<article id="post-0" <?php post_class('entry-post');?> >
	<h3 class="entry-title"><?php _e( 'Nothing Found', 'alterna' ); ?></h3>
	<p><?php _e('Sorry, this page does not exist.' , 'alterna' ); ?></p>
</article>
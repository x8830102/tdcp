<?php
/**
 * The Footer for our theme.
 *
 * @since alterna 7.0
 */
?>
            </div><!-- end content-wrap -->

            <div class="footer-wrap">   
                <footer class="footer-content">
                    <div class="container">
                            <div class="col-md-8 col-sm-12 col-xs-12" style="display: flex;justify-content: space-around;padding: 0;">
                                <a href="#" class="footer_link">園區介紹</a>
                                <a href="#" class="footer_link">園區導覽</a>
                                <a href="#" class="footer_link">服務內容</a>
                                <a href="#" class="footer_link">聯絡我們</a>
                            </div>
                    </div>
                    <?php get_template_part( 'template/footer/widgets' );?>
                    <div class="footer-bottom-content">
                        <?php get_template_part( 'template/footer/copyrights' );?>
                    </div>
                </footer>
                <?php get_template_part( 'template/footer/banner' );?>
            </div><!-- end footer-wrap -->
        </div><!-- end wrapper -->
        <?php wp_footer() ?>
    </body>
</html>
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
                                <a href="園區介紹" class="footer_link"><span>園區介紹</span></a>
                                <a href="園區導覽" class="footer_link"><span>園區導覽</span></a>
                                <a href="服務內容" class="footer_link"><span>服務內容</span></a>
                                <a href="聯絡我們" class="footer_link"><span>聯絡我們</span></a>
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
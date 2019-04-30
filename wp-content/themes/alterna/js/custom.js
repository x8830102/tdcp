// custom js
(function($, document) {
    "use strict";
    $(window).load(function(){
        // flexslide
        $('#slider').flexslider({
            slideshow: false ,
            animation: "slide",
            controlNav: false,
            animationLoop: false,
            sync: "#carousel",
            start: function(){
            }
        });
        $('#carousel').flexslider({
            animation: "slide",
            controlNav: false,
            animationLoop: false,
            slideshow: false,
            itemWidth: 210,
            itemMargin: 5,
            asNavFor: '#slider',
            start: function(){
            }
        })
        $('.rtbs_menu ').on('click', function(){
            $('#slider').resize();
            $('#carousel').resize();
        })
    })
    $(document).ready(function(){
        
        const get_event_post_list = function(e,type=''){

            let event_date = ''
            if(type = 'click') {
                event_date = $(e.currentTarget).data('event_date')
            } else {
                event_date = e.data('event_date')
            }
            let arg = {
                'action' : 'get_post_list_by_date',
                'event_date'  : event_date
            }
            // console.log(arg)
            $.ajax({
                url: wp_ajax_obj.ajax_url,
                type: 'POST',
                dataType: 'json',
                data: arg,
                success: function(result){
                    let post_list_item =''
                    console.log(result)
                    result.posts.forEach(function(item, index, array){
                        post_list_item +=`
                                <div class="col-md-5 col-sm-8 col-lg-3 post_list_item">
                                    <a href="${item.guid}" class="post_list_link">
                                    <div class="post_list_img">
                                        <img src="${item.post_img}">
                                    </div>
                                    <div class="post_list_content">
                                        <p class="post_list_title">${item.post_title}</p>
                                        <li class="fa fa-calendar" style="margin-right: 3px;"> </li><span>${item.post_excerpt}</span>
                                    </div>
                                    </a>
                                </div>`
                    })
                    $('#event .home_post_list .row').html(post_list_item)
                    $('.calendar_content').html(result.calendar_html)
                    $('.calendar_mobile_content').html(result.calendar_mobile_html)



                },
                error: function(result){
                    console.log(result)
                }
            })
        }
        get_event_post_list($('#event-tab'))
        const get_home_post_list = function(e) {
            let data = {}
            let term_id = 0;
            let current_tab = $(e.currentTarget).data('active_tab')
            let post_count = $('#'+current_tab+' .post_list_item').length
            let term_name = $(e.currentTarget).data('term_name')
            $.ajax({
                url: 'wp-json/wp/v2/categories',
                type: 'GET',
                async: false,
                data: {'search': term_name},
                success: function(result){
                    term_id = result[0].id
                }
            })
            data = {
                'categories' : term_id,
                'per_page'  : 6,
                'offset' : post_count
            }
            $.ajax({
                url: 'wp-json/wp/v2/posts',
                type: 'GET',
                data: data,
                success: function(result){
                    result.forEach(function(item, index, array){
                        console.log(item)
                        $('#'+current_tab+' .home_post_list .row').append(`
                        <div class="col-md-4 col-sm-12 col-lg-3 post_list_item fade">
                            <a href="${item.link}" class="post_list_link">
                            <div class="post_list_img">
                                <img src="${item.post_img}">
                            </div>
                            <div class="post_list_content">
                                <p class="post_list_title">${item.trim_title}</p>
                                <li class="fa fa-calendar" style="margin-right: 3px;"> </li><span>${item.trim_excerpt}</span>
                            </div>
                            </a>
                        </div>`)
                        
                    })
                    if(result.length < 6) {
                        $(e.currentTarget).hide()
                    }
                },
                error: function(error) {
                    console.log(error)
                },
                complete: function() {
                    $('#'+current_tab+' .home_post_list .row .post_list_item').addClass('show')
                }
            })
        }
        $('.event_nav_link').on('click', function(e){
            get_event_post_list(e, 'click')
        })

        $('.nav-link').on('click', function(){
            let active_tab = $(this).attr('aria-controls')
            $('.load_more').data('active_tab', active_tab);
            $('.load_more').data('term_name', $(this).text())
        })
        $('#home .load_more').on('click', function(e){
            get_home_post_list(e)
        })
        $('.order .show').on('click', function(e){
            e.preventDefault();
            $(this).toggle();
            $(this).next().toggle();
            $(this).parent().parent().children('ul').slideDown();
        })
        $('.order .close').on('click', function(e){
            e.preventDefault();
            $(this).toggle();
            $(this).prev().toggle();
            $(this).parent().parent().children('ul').slideUp();
        })
        const options = {
            // 如果 root 不給值，或是給 null，root 就會是你的 viewport，超讚！
            root: null,
            // 我希望它即將出現在 viewport 之前就觸發 callback，這邊設定它往下滑動時進入 viewport 之前 100px 就觸發去拉圖片
            rootMargin: '0px 0px 100px 0px',
            threshold: 1.0,
        };
        const $imgList = document.querySelectorAll('img');
        const callback = function(entries, observer) {
            entries.forEach(function($img) {
                if ($img.isisIntersecting) {
                    // 假設圖片真正的 src 放在它的 data-src 裡
                    $img.target.src = $img.target.dataset.src;
                    // 已經換上真正的 src，不用再監控了
                    observer.unobserve($img.target);
                }
            });
        };
        const observer = new IntersectionObserver(callback, options);
        // 註冊監控所有的圖片
        $imgList.forEach($img => observer.observe($img));
        
    })
})(jQuery, document)
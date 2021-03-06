// custom js
(function($, document) {
    "use strict";
    $(document).ready(function(){
        const init = function() {
            // fb init
            
            $('html').css('min-height',$(window).height())
            $('body').css('min-height',$(window).height())
            let content_heigth = $(window).height() - ($('.header-wrap').height() + $('.footer-wrap').height())
            $('.content-wrap').css('min-height',content_heigth);
        }
        
        const get_event_post_list = function(e,type=''){

            let event_date = ''
            if(type == 'click') {
                event_date = $(e.currentTarget).data('event_date')
            } else {
                event_date = e.data('event_date')
            }
            
            let arg = {
                'action' : 'get_post_list_by_date',
                'event_date'  : event_date,
                
            }
            if( type == 'load_more') {
                arg.offset = $('#event .post_list_item').length
            }

            // console.log(arg)
            $.ajax({
                url: wp_ajax_obj.ajax_url,
                type: 'POST',
                dataType: 'json',
                data: arg,
                success: function(result){
                    let post_list_item =''
                    let load_more = ''
                    result.posts.forEach(function(item, index, array){
                        post_list_item +=`
                            <div class="col-md-5 col-sm-8 col-lg-3 post_list_item">
                                <a href="${item.guid}" class="post_list_link" >
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
                    if( type == 'load_more') {
                        $('#event .home_post_list .row').append(post_list_item)
                    } else {
                        $('#event .home_post_list .row').html(post_list_item)
                        $('.calendar_content').html(result.calendar_html)
                        $('.calendar_mobile_content').html(result.calendar_mobile_html)
                    }
                    if(result.have_more != true) {
                        $('#event .load_more').hide();
                    } else {
                        $('#event .load_more').show();
                    }
                    
                    
                },
                error: function(result){
                    console.log(result)
                }
            })
        }
        get_event_post_list($('#event-tab'))
        $('.event_nav_link').on('click', function(e){
            get_event_post_list(e, 'click')
            $('.load_more').attr('data-event_date', $(this).data('event_date'))
        })

        $('#home_page .load_more').on('click', function(e){
            let active_tab = $(this).attr('data-active_tab')
            // console.log(active_tab)
            if(active_tab == 'event') {
                get_event_post_list($(this), 'load_more')
            } else {
                get_home_post_list(e)
            }
            
        })

        const tab_data_process = function() {
            $('#event .nav-link').on('click', function(){
                $('#event .load_more').attr('data-event_date', $(this).data('event_date'))
            })
            $('#home-tab').on('click', function(){
                let term_name = $(this).text()
                let active_tab = $(this).attr('aria-controls')
                $('#home .load_more').attr('data-active_tab', active_tab);
                $('#home .load_more').attr('data-term_name', term_name)
            })
            $('#news-tab').on('click', function(){
                let term_name = $(this).text() == "自造新聞" ? "南創新知" : $(this).text()
                let active_tab = $(this).text() == "自造新聞" ? 'new_knowledge': $(this).attr('aria-controls')
                $('#news .load_more').attr('data-active_tab', active_tab);
                $('#news .load_more').attr('data-term_name', term_name)
                
            })
            $('#new_knowledge-tab').on('click', function() {
                let term_name = $(this).text()
                $('#new_knowledge .load_more').attr('data-active_tab', 'new_knowledge');
                $('#new_knowledge .load_more').attr('data-term_name', term_name)
            })
            $('#documentary-tab').on('click', function() {
                let term_name = $(this).text()
                $('#documentary .load_more').attr('data-active_tab', 'documentary');
                $('#documentary .load_more').attr('data-term_name', term_name)
            })
            $('#story-tab').on('click', function() {
                let term_name = $(this).text()
                $('#story .load_more').attr('data-active_tab', 'story');
                $('#story .load_more').attr('data-term_name', term_name)
            })

        }

        const get_home_post_list = function(e) {
            let data = {}
            let term_id = 0;
            let current_tab = $(e.currentTarget).data('active_tab')
            let post_count = $('#'+current_tab+' .post_list_item').length
            let term_name = $(e.currentTarget).data('term_name')
            // console.log(post_count)
            $.ajax({
                url: 'wp-json/wp/v2/categories',
                type: 'GET',
                async: false,
                data: {'search': term_name},
                success: function(result){
                    // console.log(result)
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
                    // console.log(result)
                    result.forEach(function(item, index, array){
                        // console.log(item)
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
        if( location.search  ) {
            let date = location.search.split('=')
            
            if( date[0] === "?date") {
                $('#'+date[1]).parent().parent().children('ul').slideDown()
                let split_date = date[1].split('-')
                // console.log(split_date.length )
                if(split_date.length >= 2) {

                    $('#'+split_date[0]).parent().parent().children('ul').slideDown()
                }
                
            }
           
        }
        init();
        tab_data_process();
    })
})(jQuery, document)
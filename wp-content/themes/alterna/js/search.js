(function($, document) {
    "use strict";
    $(document).ready(function(){
        $('#search_result .load_more').on('click', function(e){
            let arg = {
                'action' : 'search_post',
                's' : $(this).data('result'),
                'post_type' : 'post',
                'offset' : $('.post_list_item').length
            }
            
            $.ajax({
                url: wp_ajax_obj.ajax_url,
                type: 'POST',
                data: arg,
                success: function(result){
                    let post_list_item =''
                    let load_more = ''
                    result = JSON.parse(result);
                    result.forEach(function(item, index, array){
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

                    let found_posts = $('#search_result .load_more').data('found_posts') ;
                    $('.home_post_list .row').append(post_list_item)
                    if( arg.offset == found_posts || (found_posts - arg.offset) < 6 ) {
                        $('#search_result .load_more').hide();
                    } else {
                        $('#search_result .load_more').show();
                    }
                    
                    
                },
                error: function(result){
                    console.log(result)
                }
            })
        })
    })

})(jQuery, document)
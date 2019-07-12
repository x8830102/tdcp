// custom js
(function($, document) {
    "use strict";
    $(window).load(function(){
        // flexslide
        $('.rtbs_menu li').on('click', function(){
            // $('#slider').resize();
            // $('#carousel').resize();
            $('#slider').flexslider({
                slideshow: false ,
                animation: "slide",
                controlNav: false,
                animationLoop: false,
                sync: "#carousel",
                after: function(slider){
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
                after: function(slider){
                }
            })
            // $(window).resize()
        })
        
    })
    $(document).ready(function(){
        window.fbAsyncInit = function() {
            FB.init({
            appId            : '354868454881906',
            autoLogAppEvents : true,
            xfbml            : true,
            version          : 'v2.9'
            });
            FB.AppEvents.logPageView();
        };
        (function(d, s, id){
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {return;}
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_US/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));

        // fb share
        $(document).on('click', '.fb.share', function(){
            FB.ui({
                method: 'share',
                mobile_iframe: true,
                href: location.href+'?utm_campaign=tdcp&utm_medium=social&utm_source=facebook'
            }, function(response){});
        });
        $(document).on('click', '.fb.send', function(){
            FB.ui({
                method: 'send',
                mobile_iframe: true,
                link:location.href+'?utm_campaign=tdcp&utm_medium=social&utm_source=facebook'
            }, function(response){});
        });
        if($('.rtbs_menu ul li').length == 2) {
            $('<style>.rtbs_full .rtbs_menu ul::after{content:""}</style>').appendTo('head');
        }

        $('#slider a').on('click',function(){
            $(this).data("toggle","modal")
            $(this).data("target","#myModal")
            let active_img_src = $('.flex-active-slide a img').attr('src')
            console.log(active_img_src)
            $("#myModal img").attr("src",active_img_src);
            $("#myModal").css('top','unset')
            // $("#myModal").css('align-items','center')
        })

    })
})(jQuery,document)
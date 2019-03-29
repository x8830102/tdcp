// custom js
(function($) {
    "use strict";

    function bootstrap_carousel() {
       $('.carousel').carousel({
            pause: "hover"
        }) 
    }
    

    $(document).ready(function(){
        bootstrap_carousel();
    })
})(jQuery)
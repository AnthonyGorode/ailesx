$(window).on('resize',function(){
    $(".bloc_login").fadeIn("slow");
    let size = $(window).height();
    size = size - 122;
    $(".page_login").attr('style',`position:relative; height: ${size}px;`);
    $(".height_screen").html(`height : ${size}`);        
});
$(document).ready(function(){$(".bloc_login").fadeIn("slow");});
let size = $(window).height();
size = size-122;
$(".page_login").attr('style',`position:relative; height: ${size}px;`);
$(".height_screen").html(`height : ${size}`);
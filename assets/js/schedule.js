$(document).ready(function() {
    $('.select').css('color','gray');
    $('.select').change(function() {
        var current = $('.select').val();
        if (current != 'null') {
            $('.select').css('color','black');
        } else {
            $('.select').css('color','gray');
        }
     }); 
});

$("#btn_search").click(function(){
    $(".form_coord_schedule").css("display","block");
    $(".article_schedule").css("display","none");
    $('html, body').animate( { scrollTop: $(".form_coord_schedule").offset().top }, 1000 );
});
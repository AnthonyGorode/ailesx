$(window).resize(function(){
    let taille = $(window).width();
    // $(".screen").html(`width : ${taille}`);

    if (taille < 1204){
        $("#navbarColor01").attr('class','collapse navbar-collapse');
    }else if (taille > 1204){
        $("#navbarColor01").attr('class','offset-lg-1 collapse navbar-collapse');
    }
});

let flagSize=0;
let taille = $(window).width();
// $(".screen").html(`width : ${taille}`);

if (taille < 1204){
    $("#navbarColor01").attr('class','collapse navbar-collapse');
}else if (taille > 1204){
    $("#navbarColor01").attr('class','offset-lg-1 collapse navbar-collapse');
}
let num = 0;
        let numReturn = 0;
        $(document).ready(function(){
            $(".bloc_title_animation").fadeIn("slow");
            $('html, body').animate( { scrollTop: $(".bloc_nav").offset().top }, 200 );
        })
        $(document).keyup(function(touche){
            let appui = touche.which || touche.keyCode;

            if(appui === 39){ // si l'utilisateur appui sur la flèche de droite
                if (num === 0){
                    num++; 
                    $(".title_bloc_experience").toggle("slow");
                    $(`#first_experience_video${num}`).toggle("slow");
                    $(`#first_experience_image${num}`).toggle("slow");
                    $('html, body').animate( { scrollTop: $(".bloc_animation").offset().top }, 2000 );
                }
                else{
                    if (num === 9){                       
                           $('#last_experience_video').toggle(400);               
                    }
                    $(`#first_experience_video${num}`).toggle(2000);
                    $(`#first_experience_image${num}`).toggle(2000);
                    num++; 
                    $(`#first_experience_video${num}`).toggle(2000);     
                    $(`#first_experience_image${num}`).toggle(2000); 
                } 

                if (num === 13){
                    $('html, body').animate( { scrollTop: $(".send_coordonnees").offset().top }, 2000 ); 
                }
                
            }

            if(appui === 37){ // si l'utilisateur appui sur la flèche de gauche
                if (num === 9){                       
                    $('#last_experience_video').toggle(400);               
                }
                $(`#first_experience_video${num}`).toggle('fast');
                $(`#first_experience_image${num}`).toggle('fast');
                num--;
                $(`#first_experience_video${num}`).toggle('fast');
                $(`#first_experience_image${num}`).toggle('fast');
            }
        });
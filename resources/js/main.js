$(function() {
  const url = "http://proyecto-laravel.com.devel";

  $(window).on('load', function() {
    $('.like').css('cursor', 'pointer');
    $('.dislike').css('cursor', 'pointer');
    
    function like() {
      //? Botón de like
      $('.like').unbind('click').click(function() {
        $(this).addClass('dislike').removeClass('like');
        $(this).attr('src', url+'/img/red-heart.png');

        //! Con data('id') seleccionamos en atributo data-id="" que le hemos puesto a la imagen del like que tiene dentro el id de la imagen
        $.ajax({
          type: "GET",
          url: url+'/like/'+$(this).data('id'),
          success: function(response) {
            if(response.like) {
              console.log('Has dado like');
            } else {
              console.log('Error al dar like');
            }
          }
        });

        dislike();
      });
    }

    like();

    function dislike() {
      //? Botón de dislike
      $('.dislike').unbind('click').click(function() {
        $(this).addClass('like').removeClass('dislike');
        $(this).attr('src', url+'/img/gray-heart.png');

        $.ajax({
          type: "GET",
          url: url+'/dislike/'+$(this).data('id'),
          success: function(response) {
            if(response.dislike) {
              console.log('Has dado dislike');
            } else {
              console.log('Error al dar like');
            }
          }
        });

        like();
      });
    }

    dislike();

    $('#profile-picture').mouseenter(function() { 
      $(this).addClass('shadow-lg bg-body');
    });

    $('#profile-picture').mouseleave(function() { 
      $(this).removeClass('shadow-lg bg-body');
    });
  });
})
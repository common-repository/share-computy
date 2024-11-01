jQuery(document).ready(function($){

    $.fn.extend({
/*функция для включения нужного класса на время*/
        addTemporaryClass: function(className, duration) {
            let elements = this;
            setTimeout(function() {
                elements.removeClass(className);
            }, duration);

            return this.each(function() {
                $(this).addClass(className);
            });
        }
    });

    function go(addr) {
        window.open(addr,"MyWin", "menubar=yes, width=860, height=470, top="+((screen.height-470)/2)+",left="+((screen.width-860)/2)+", resizable=yes, scrollbars=no, status=yes");
    }

    $('.share-item').click(function(e) {

        $(this).addClass('cliick');

        $(this).addTemporaryClass("disabled_computy", 15000);
        $('.share_computy').addClass('blednost');
       // e.preventDefault();
        let znach = $(this).children(".value");
        let oldsimble = znach.text();
if(oldsimble===''){oldsimble=0;}
        let sesid = $('.sesid').val();
        let postid =  $('.postid').val();
        let voteid = $(this).attr('data-type');

        //проверка есть ли id сессии для этой записи
        $.ajax({
            type: "POST",
            url: window.wp_data.ajax_url,
            data: {
                action : 'get_share_computy_value',
                sesid: sesid, postid: postid,voteid:voteid

                //вставить данные о сессии и о id поста
            },
            success: function (response) {
               // console.log(response);
                $('.cliick').removeClass('cliick');
                $('.share_computy').removeClass('blednost');

                if(response === 'voteadd0'){

                    //голос добавлен
                    let newsible = parseFloat(oldsimble)+1;
                    znach.text(newsible);
                //  $(this+ '.value').data('value', newsible);
                    znach.attr('data-value', newsible);
                }

            }
        });




    });






});

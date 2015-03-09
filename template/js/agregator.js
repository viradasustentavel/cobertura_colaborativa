(function(){
    var $ = jQuery;


    var index = -1;
    var count = 0;


    $(document).ready(function(){
        $("a.fancybox").fancybox({
            tpl: {
                closeBtn : '<a title="Close" class="fancybox-item fancybox-close" href="javascript:;" style="background: #000;width: 90px;text-align: center;color: #FFF;padding: 5px;">Fechar x</a>',
                next     : '<a title="Next" class="fancybox-nav fancybox-next" href="javascript:;"><span></span></a><div class="Contador">jidshcjksadhcljkashlkjdashlkj</div>'
            },
            afterShow: function(){
                var total = $('.masonry-brick').length - 2;

                if (index == -1) {
                    index = parseInt($('.fancy-box-active').parent().attr('rel'), 10);
                }

                $('.Contador').html(index + ' de ' + total);

            },
            afterClose: function() {
                //alert('aqui');
                index = -1;
            }
        });
        var $container = $('.containerAgregador');

        $container.imagesLoaded( function(){
            $container.masonry({
              itemSelector : '.box',
              columnWidth: 12
            });
        });
        var offset = 9;
        $('.btnLoading').click(function() {
            $('.loaderImg').fadeIn();
            $.ajax({
                url: ajax_trigger,
                type: 'post',
                data: {
                    'action': 'qdi_ajax_get_more_social_posts',
                    'offset': offset
                },
                success: function(dados) {
                    $('.loaderImg').fadeOut();
                    offset = offset + dados.limit;

                    $('.containerAgregador').append(dados.itens);
                    $(".containerAgregador").masonry( 'reload' );
                    $(".containerAgregador").masonry( 'layout' );
                }
                })
        });
        var offsetBlog = 1;
        $('.btnLoadingPost').click(function() {
            $('.loaderImg2').fadeIn();
            $.ajax({
                url: ajax_trigger,
                type: 'post',
                data: {
                    'action': 'qdi_ajax_get_more_blog',
                    'offset': offsetBlog
                },
                success: function(dados) {
                    $('.loaderImg2').fadeOut();
                    offsetBlog = offsetBlog + dados.offset;
                    $('.dadosBlog').html($('.dadosBlog').html() + dados.itens);
                }
                })
        });


    });


    $('body').on('click', '.fancybox-next', function(e){
        index++;
        var total = $('.masonry-brick').length - 2;

        if (index > total) {
            index = 1;
        }

        $('.Contador').html(index + ' de ' + total);
    });

    $('body').on('click', '.fancybox-prev', function(e){
        index--;
        var total = $('.masonry-brick').length - 2;
        if (index < 1) {
            index = total;
        }
        $('.Contador').html(index + ' de ' + total);
    });

    $('body').on('click', '.fancybox', function(e){
        $('.fancy-box-active').addClass('fancy-box-active');
        $(this).addClass('fancy-box-active');
    });


})();

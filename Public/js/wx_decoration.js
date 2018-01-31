$(function(){
    //当前选择的组件
    var _active_sub_ground = null;

    // 使内容可拖动
    $( ".wx_content" ).sortable();
    $( ".wx_content" ).disableSelection();

    // 初始绑定wx_title鼠标事件
    on_mouseenter($('.wx_title'))
    on_mouseleave($('.wx_title'))
    on_mouseClick($('.wx_title'))

    on_mouseenter($('.wx_bottom'))
    on_mouseleave($('.wx_bottom'))
    on_mouseClick($('.wx_bottom'))


    $('body').find('[name="block"]').each(function(index, element){
        element.onclick = function(){
            var continues = true;
            var type = $(element).attr('data-block-type')
            $('.wx_content .widget').each(function(index, element){
                if($(element).attr('data-block-type') == type){
                    alert('已有模块，不能在添加');
                    continues = false
                }
            })


            if(continues){
                $.ajax({
                    url : $(element).attr('data-url'),
                    type : 'post',
                    dataType : 'text',
                    success : function(text){
                        var obj = $('<div/>')
                        var date = new Date()
                        var tab = date.getTime()

                        $(obj).append(text)

                        $(obj).addClass(''+tab)
                        $(obj).attr('name', 'sub_ground');
                        $(obj).css('position', 'relative');

                        $('.wx_content').append(obj)

                        $('.wx_content').on('mouseenter', '.' + tab, function(){
                            $('.' + tab).addClass('move_boder');
                            appendClose($('.' + tab))
                        })
                        $('.wx_content').on('mouseleave', '.' + tab, function(){
                            $('.' + tab).removeClass('move_boder');
                            removeClose($('.' + tab))
                        })
                        $('.wx_content').on('click', '.' + tab, function(){
                            $('body').find('[name="sub_ground"]').removeClass('select_border')


                            $('.' + tab).addClass('select_border');
                            _active_sub_ground = $('.' + tab)
                            $('#color_select').removeAttr('disabled')
                        })
                    }
                })
            }

        }
    })

    // // 请求左边操作栏内容并实现点击事件
    // $.ajax({
    //     url : $('body').attr('data-url'),
    //     type : 'post',
    //     dataType : 'json',
    //     success : function(res){
    //         var buttons = res.data.list;
    //
    //         var str = "";
    //         buttons.forEach(function(element, index){
    //             str += '<div name="block" data-url="'+ element.request_url +'" class="block">'
    //                 + element.name
    //                 + '</div>'
    //         })
    //
    //         $('.oprtation').html(str)
    //
    //
    //
    //
    //     }
    // })

    //鼠标移进去
    function on_mouseenter(target){

        target.mouseenter(function () {
            target.addClass('move_boder');
            appendClose(target)
        })
    }

    //鼠标移出去
    function on_mouseleave(target){
        target.mouseleave(function () {
            target.removeClass('move_boder');
            removeClose($(target))
        })
    }

    //鼠标点击
    function on_mouseClick(target){
        target.click(function () {
            $('body').find('[name="sub_ground"]').removeClass('select_border')
            target.addClass('select_border');
            _active_sub_ground = target
            $('#color_select').removeAttr('disabled')
        })
    }


    function appendClose(target){
        var close = '<span title="删除" class="closes">x</span>'
        target.append(close)

        target.find('.closes').click(function(){
            if(target.attr('data-value') != 'no_delete'){
	        $(".right_oprtation").html('');
                target.remove();
            	if(typeof(target.find(".widget").attr("data-block-id"))!='undefined'){
            		del_block(target.find(".widget").attr("data-block-id"))
            	}

            }

            event.stopPropagation();
        })
    }

    function removeClose(target){
        target.find('.closes').remove();
    }
    
    function del_block(block_id){
    	console.log('Yoper');
        $.ajax({
            url : '/Custom/Decoration/del_page_block',
            data:{page_id:$(".page_list").find("a[class='page-select']").attr("data-page-id"),page_block_id:block_id},
            type : 'post',
            dataType : 'json',
            success : function(result){
            	console.log(result);
            }
        })
        //$(".right_oprtation").html('');
        //$(this).parent().remove();
    }

//    $('div[name="text_color"]').each(function(index, element){

//        $(element).on('click', function(){
 //           $('.wx_title').find('[name="text_input"]').css('color', $(element).attr('data-value'))
 //       })

 //   })



    $('body').find("div[class='page_list']").find("a").each(function(index, element){
       element.onclick = function(){
           var url = $(element).attr('data-request-url')
           $.ajax({
               type: 'POST',
               url: url,
               success: function(res){
            	   $('.wx_content').html('');
                   res.data.page_block_list.forEach(function(element, index){
                       var obj = $('<div/>')
                       var date = new Date()
                       var tab = date.getTime()
                       $(obj).append(element.block_body)

                       $(obj).addClass(''+tab)
                       $(obj).attr('name', 'sub_ground');
                       $(obj).css('position', 'relative');

                       $('.wx_content').append(obj)

                       $('.wx_content').on('mouseenter', '.' + tab, function(){
                           $('.' + tab).addClass('move_boder');
                           appendClose($('.' + tab))
                       })
                       $('.wx_content').on('mouseleave', '.' + tab, function(){
                           $('.' + tab).removeClass('move_boder');
                           removeClose($('.' + tab))
                       })
                       $('.wx_content').on('click', '.' + tab, function(){
                           $('body').find('[name="sub_ground"]').removeClass('select_border')


                           $('.' + tab).addClass('select_border');
                           _active_sub_ground = $('.' + tab)
                           $('#color_select').removeAttr('disabled')
                       })
                   })
               },
               dataType: 'json',
               beforeSend:function(xhr){
            	   $('.wx_content').html('页面渲染中..');
               }
           });
       }
    });


    //当前被点击的模块处于编辑状态
    //兄弟模块节点不可编辑
    $("body").on('click','.widget',function(){
    	$(this).siblings('div').attr('data-block-status','');
    	$(this).attr('data-block-status','activity');
    })
})
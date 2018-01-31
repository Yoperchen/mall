/**
 * Created by Administrator on 2016/10/27.
 */
$(function () {

    /*课程导航*/
    $(".kechengdaohang li").click(function () {
        $(this).addClass('bottombg2').siblings().removeClass();
        var index = $(this).index();
        $('.spdagps .spdagps_in').eq(index).show().siblings().hide();
    });
    /*视频购买定位背景*/
    $(".spgpsbg").click(function () {
        $(".bofangbg").css({display:"block"})
        $(".bofangbg_in").css({display:"block"})
    });
    /*视频购买定位内容*/
    $(".goumaiguanbi").click(function () {
        $(".bofangbg").css({display:"none"})
        $(".bofangbg_in").css({display:"none"})
    });

    /*添加评论事件修改1*/
    $(".tianjiataolun").click(function () {
        $(".shurukuang").stop().animate({bottom:"0"},300);
        $(".shuru").focus();
        $(this).animate({bottom:"-1000"},300);
    });
    $(".shuru").blur(function () {
        $(".tianjiataolun").animate({bottom:"60"},10);
        $(".shurukuang").animate({bottom:"-1000"},10);
    });
    $(".emotion").click(function () {
        $(".tianjiataolun").animate({bottom:"-1000"},0);
        $(".shurukuang").animate({bottom:"150"},0);
    });

    /*$(".sousuoliebiao li").click(function () {
     $(this).addClass('sswenziyanse').siblings().removeClass();
     var index = $(this).index();
     $('.sousuokec1 .sousuokec1_in1').eq(index).show().siblings().hide();
     $(".kechengxueyuan").css({display:"none"})
     });
     $(".sousuoanniu .sskecxy").blur(function () {
     $(".kechengxueyuan").css({display:"block"});
     $(".sousuokec1 .sousuokec1_in1").css({display:"none"});
     });*/
    /*我的消息*/
    $(".xiaoxi_top li").click(function () {
        $(this).addClass('cur').siblings().removeClass();
        var index = $(this).index();
        $('.wodexiaoxin .wodexiaoxin').eq(index).show().siblings().hide();
    });
    /*输入框事件，输入的时候底部隐藏，失去焦点底部显示*/
    $("input").focus(function(){
        $(".scbottom").hide()
    });
    $("input").blur(function(){
        $(".scbottom").show()
    });
    $("textarea").focus(function(){
        $(".scbottom").hide()
    });
    $("textarea").blur(function(){
        $(".scbottom").show()
    });
    //input[type="button"],input[type="submit"],input[type="reset"] { -webkit-appearance:none; appearance:none; outline:none;}
})




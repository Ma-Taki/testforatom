$(document).ready(function(){

  $(".submenu > a").click(function(e) {
    e.preventDefault();
    var $li = $(this).parent("li");
    var $ul = $(this).next("ul");

    if($li.hasClass("open")) {
      $ul.slideUp(350);
      $li.removeClass("open");
    } else {
      $(".nav > li > ul").slideUp(350);
      $(".nav > li").removeClass("open");
      $ul.slideDown(350);
      $li.addClass("open");
    }
  });

  $(".undermenu > a").click(function(e) {
    e.preventDefault();
    var $li = $(this).parent("li");//親
    var $ul = $(this).next("ul");//一つ前の兄弟要素を指定

    if($li.hasClass("open")) {
      $ul.slideUp(350);
      $li.removeClass("open");
    } else {
      $(".submenu > ul > li > ul").slideUp(350);
      $(".submenu > ul > li").removeClass("open");
      $ul.slideDown(350);
      $li.addClass("open");
    }
  });

});

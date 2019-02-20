<footer class="bg">
  <div class="clear"></div>
  <!--無料会員登録メニューの固定と閉じるボタン-->
  <div class="fix_menu_smartphone">
    <div class="add-control">
      <div class="action-close">
      <input id="close" class="checkbox" name="close" type="checkbox"><label class="btn" for="close">×</label>
      <div class="box"> <a href="https://www.engineer-route.com/user/regist" target="_blank"><img src="../../../../front/images/bnrTouroku01SP.png" alt="新規会員登録"></a></div>
    </div>
    </div>
  </div>
<footer class="bg">
  <div id="fb-root"></div>
<!--スマホとpcのバナーを判断させる-->
<script type="text/javascript">
  $(function(){
    if ((navigator.userAgent.indexOf('iPhone') > 0
      && navigator.userAgent.indexOf('iPad') == -1)
      || navigator.userAgent.indexOf('iPod') > 0
      || navigator.userAgent.indexOf('Android') > 0) {
      var bH = $('.fix_menu_smartphone').height();
      $('body').css('margin-bottom',bH+'px');
    }else{
      $('.fix_menu_smartphone').css('display','none');
    }
  });
</script>

  <div class="footer-links__no-margin">
    <ul>
      <li><a class="hover-thin" href="http://solidseed.co.jp/" target="_blank">運営会社</a></li>
    	<li><a class="hover-thin" href="/privacy">プライバシーポリシー</a></li>
    	<li><a class="hover-thin" href="/terms">利用規約</a></li>
    	<li><a class="hover-thin" href="/contact">お問い合わせ</a></li>
    </ul>
  </div>
  <p>&copy; SolidSeed Co.,Ltd.</p>
</footer>
<!-- END .footer -->
<script type="text/javascript" charset="utf-8" src="/front/js/all.js"></script>
<a href="#top" class="page_top"><i class="fa fa-angle-double-up"></i></a>
</body>
</html>

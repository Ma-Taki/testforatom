@extends('front.common.layout')
@section('title', 'エンジニアルートとは | エンジニアルート')
@section('description', 'Engineer-Route（エンジニアルート）では、皆様の夢や目標、可能性をサポートするため、 それぞれの方に合った案件検索から、カウンセリング、プロジェクトの終了まで 全てのプロセスを徹底的にサポート致します。')
@section('canonical', url('/about'))

@section('content')
<div class="wrap">

  <div class="breadcrumbs" itemscope itemtype="http://schema.org/BreadcrumbList">
    <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
      <a class="hover-thin" itemprop="item" href="/">
        <span itemprop="name">エンジニアルート</span>
      </a>
      <meta itemprop="position" content="1" />
    </span>
    <span class="next">></span>
    <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
      <span itemprop="name">エンジニアルートとは</span>
      <meta property="position" content="2">
    </span>
  </div>
  <!-- END .breadcrumbs -->

  <div class="main-content about">
    <div class="main-content-left">
      <h2 class="main-content__title">エンジニアルートとは</h2>
      <hr class="hr-2px-solid-5e8796">
      <div class="main-content__body">
        <div class="content__element">

          <div class="about__image">
            <img src="/front/images/sky.jpg" alt="エンジニアルートとは">
            <p class="about__image-text wsnw">
              エンジニアルートは、プロジェクト終了後まで<br />
              エンジニアの皆様を全力でサポート致します。
            </p>
          </div>

          <div class="about__text">
            <p>
              Engineer-Route（エンジニアルート）では、皆様の夢や目標、可能性をサポートするため、それぞれのご希望に合ったお仕事探しから、カウンセリング、参画後のフォロー、プロジェクト終了まで 全てのプロセスを徹底的にサポートいたします。
            </p>
            <p>
              それぞれの道（ルート）を見定め、そして目標に向かって一歩ずつ進んでいく為に。<br>
              そして私達も、皆様とともに一緒に歩んでいく為に、 一つずつ丁寧にお手伝いしていきます。
            </p>
          </div>

          <hr class="hr-1px-dashed-5e8796">

          <div class="appeal-point">

            <h3>1．取引先を担当している営業がカウンセリング</h3>
            <p>
              コーディネーターなどの専任スタッフが事前にお会いするケースが通常多いですが、エンジニアルートでは、日々飛び回っている営業や役員がカウンセラーとして直接皆様とお会いし、最新でリアルな情報をお伝えいたします。
              また、状況毎に担当が変わることがないため、話も伝わりやすく安心して作業をして頂ける環境です。
            </p>
            <hr class="hr-1px-dashed-5e8796">

            <h3>2．営業力を活かした幅広く豊富な案件</h3>
            <p>
              汎用機系～オープン・WEB系、インフラ・サーバ系や、コンサルティング、PM、PMO、システムエンジニア、プログラマー、ヘルプデスク・サポート系、ディレクター、WEBデザイナーなど幅広い職種や言語にも対応しております。その他、英語や簿記（会計）を活かした求人もございます。役員や営業チームが抱えている案件のほか、万が一ご希望に合わなかった場合は、ヒアリングした内容を元に、新規開拓を行い、新たな案件をご提案いたします。
            </p>
            <hr class="hr-1px-dashed-5e8796">

            <h3>3．サイト上には掲載していない公開NG案件・求人も多数</h3>
            <p>
              WEB上には公開できない案件・求人情報が多く存在しています。その他、特に良い案件はサイトに公開する前に決まってしまうケースも非常に多いため、まずは一度弊社でご登録・カウンセリングをしていただくことをお勧めいたします。
            </p>
            <hr class="hr-1px-dashed-5e8796">

            <h3>4．初めてフリーランスになる方へ</h3>
            <p>
              今まで正社員として働いていたが、初めてフリーランスになろうと考えている方。「自分のスキルで勝負したい」「たくさん稼ぎたい」「やりたい仕事を選びたい」など考えてはいるものの、フリーランスってどうしたら良いのか分からない事も多いと思います。エンジニアルートでは経験豊富なスタッフがお話をお聞きして、アドバイスをいたします。
            </p>
            <hr class="hr-1px-dashed-5e8796">
            <br>
            <br>
            <h3 class="underline">まずは今までのご経験や今後のご希望を私たちにお聞かせください</h3>
            <p>
              ・とにかく今は条件（単価）優先で稼ぎたい<br>
              ・腰を据えて安定している現場で長期にわたって作業したい<br>
              ・通勤時間を極力減らしたい<br>
              ・始業開始時間が遅い現場がいい<br>
              ・完全に禁煙（分煙）なオフィスがいい<br>
              ・最新のツールなどが常に使える環境で働きたい<br>
              ・大手企業の情報システム部などで働いてみたい<br>
            </p>
            <p>など、できる限りご希望に合った案件をご紹介させていただきます。</p>
          </div>
        </div>
      </div>
    </div><!-- END main-content-left -->

    <div class="main-content-right">
      @include('front.common.sideInfo')
    </div><!-- END main-content-right -->
    <div class="clear"></div>
  </div><!-- END main-content -->
</div><!-- END wrap -->
@endsection

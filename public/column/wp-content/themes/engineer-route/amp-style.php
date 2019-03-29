
/* -------------------------------
reset
 * -------------------------- */
body,div,span,object,iframe,h1,h2,h3,h4,h5,h6,p,blockquote,pre,abbr,address,cite,code,del,dfn,em,img,ins,kbd,q,samp,small,strong,sub,sup,var,b,i,dl,dt,dd,ol,ul,li,fieldset,form,label,legend,table,caption,tbody,tfoot,thead,tr,th,td,article,aside,canvas,details,figcaption,figure,footer,header,hgroup,menu,nav,section,summary,time,mark,audio,video{
    margin:0;
    padding:0;
    border:0;
    background:transparent;
    outline:0;
    font-size: 100%;
    vertical-align:baseline;
}

body {
    font-weight: 500;
    line-height: 1.7;
    color: #333;
    font-family: "Montserrat","ＭＳ Ｐゴシック", Avenir , "Helvetica Neue" , Helvetica , Arial , Verdana , Roboto , "游ゴシック" , "Yu Gothic" , "游ゴシック体" , "YuGothic" , "ヒラギノ角ゴ Pro W3" , "Hiragino Kaku Gothic Pro" , "Meiryo UI" , "メイリオ" , Meiryo , "MS PGothic" , sans-serif;
}

input {
	padding:0;
}

article,aside,details,figcaption,figure,footer,header,hgroup,main,menu,nav,section,summary {
	display:block;
}

audio,
canvas,
video {
	display: inline-block;
}

audio:not([controls]) {
	display: none;
	height: 0;
}

[hidden],
template {
	display: none;
}

ol,ul,li {
    list-style: none;
}

blockquote, q {
	quotes:none;
}

blockquote:before,blockquote:after,q:before, q:after {
	content:''; content:none;
}

table {
    border-collapse: collapse;
    border-spacing: 0;
}

input, select {
	vertical-align:middle;
}

* {
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	-o-box-sizing: border-box;
	-ms-box-sizing: border-box;
	box-sizing: border-box;
}

dl,menu,ol,ul {
	margin: 1em 0;
}

ol,ul {
	padding: 0;
}

dd,figure {
    margin: 0;
}

nav ul,nav ol {
	list-style: none;
	list-style-image: none;
}

a {
	margin:0;
	padding:0;
	background:transparent;
	font-size:100%;
	vertical-align:baseline;
	color: #333;
	text-decoration: none;
}

a:link {
    text-decoration: none;
}

a:hover {
	color: #E69B9B;
    transition: 0.25s;
}

a:focus {
	outline: thin dotted;
}

a:active,
a:hover {
	outline: 0;
}

/* -------------------------------
 Link
------------------------------- */
/*画像をマウスオーバーで半透明に */
a:hover img {
	filter: alpha(opacity=70);
	-ms-filter: "alpha(opacity=70)";
	opacity:0.7;
}

a:link, a:visited:link {
	-webkit-tap-highlight-color: rgba(0, 0, 0, 0.3);
}

/* 外部リンク - External Link */
.entry-content a[target="_blank"]:after {
	font-family: 'FontAwesome';
	content: '\f08e';
	margin:0 3px 0 2px;
}

/* 外部リンク画像の場合にアイコンを消すクラス */
.entry-content a.no-icon[target="_blank"]:after {
	content:none;
}

img {
	border: 0;
	width: 100%;
	height: auto;
}

svg:not(:root) {
	overflow: hidden;
}

table {
	border-collapse: collapse;
	border-spacing: 0;
}

/* -------------------------------
 Font
------------------------------- */
h1 {
    font-size: 1.7em;
    line-height: 1.14;
}

h2 {
    font-size: 1.4em;
    line-height: 1.4;
}

h3 {
    font-size: 1.35em;
    line-height: 1.4;
}

h4 {
    font-size: 1.25em;
    line-height: 1.4;
}

h5 {
    font-size: 1.15em;
    line-height: 1.4;
}

h1, h2, h3, h4, h5 {
    text-align: left;
    font-weight: bold;
}

/*--------------------------------------------------------------
 ヘッダー・ナビゲーションメニュー・フッター・サイドバー
--------------------------------------------------------------*/
/*------------------------------------------
 ヘッダー
------------------------------------------*/
.headerInr {
    width: 95%;
    max-width: 1000px;
    margin: 0 auto;
    position: relative;
}

.header__text {
    width: 60%;
    font-size: .8em;
    padding-top: 0.7em;
    font-weight: normal;
    line-height: 1;
}

.headerInr .header__logo a {
    background: url(../../../../../front/images/logo.png)no-repeat;
    background-size: 276px 68px;
    display: block;
    width: 276px;
    height: 68px;
}

/* 検討中件数ボタン・新規登録ボタン・ログインボタン*/
.user {
    position: absolute;
    top: 0;
    right: 0;
}

.user ul {
	margin: 0;
}

.user li {
    display: inline-block;
    background: #333;
    border-bottom-left-radius: 8px;
    border-bottom-right-radius: 8px;
    box-shadow: 0px 4px 8px -3px rgba(0,0,0,0.6);
    -webkit-box-shadow: 0px 4px 8px -3px rgba(0,0,0,0.6);
    -moz-box-shadow: 0px 4px 8px -3px rgba(0,0,0,0.6);
}

.user li:hover {
    background: #4c616a;
    transition: 0.25s;
}

.user li a {
  display:inline-block;
    color: #fff;
    line-height:1;
    padding: 10px;
    font-size:12px;
}

.user li a span {
  display:inline-block;
}

.user li a #considers_length {
  color:gold;
  font-weight:bold;
  display: inline;
}

.user li a #considers_unit {
  display: inline;
}

.user #user-signin {
    background: #D46363;
}

/* キーワード検索 */
header .search {
    position: absolute;
    right: 0;
    bottom: 12px;
}

.searchBox {
    border: #5e8796 1px solid;
    border-radius: 8px;
    padding: 8px;
    margin-right: 4px;
}

.searchBtn,
.side-searchBtn {
    background: url(../../../../../front/images/search.png)no-repeat;
    background-size: 26px 26px;
    width: 26px;
    height: 26px;
    text-indent: 100%;
    cursor: pointer;
    border: none;
    vertical-align: middle;
}

/*------------------------------------------
 ナビゲーションメニュー 
------------------------------------------*/
nav {
    width: 100%;
    background: #5e8796;
}

nav ul {
    max-width: 1000px;
    margin: 0 auto;
    font-size: 0px; /* 隙間対策 */
    text-align: center;
}

nav li {
    text-align: center;
    width:16.6%;
    display: inline-block;
    vertical-align: middle;
}

nav li.small {
    width:10%;
}

nav li a {
    color: #fff;
    font-size: .8rem;
    line-height: 2.8rem;
    padding: 1rem;
}

nav li a:hover {
    color: #4c616a;
}

nav ul .active {
    background: #536C75;
}

.nav-mobile {
    display: none;
}
/*------------------------------------------
 フッター
------------------------------------------*/
footer {
    margin-top: 10rem;
    padding-top: 3rem;
    text-align: center;
}

footer .footer-links {
    margin-top: 214px;
    padding-top: 3rem;
}

footer .footer-links ul {
    border-top: 1px solid #ddd;
}

footer .footer-links ul li,
footer .footer-links__no-margin ul li {
    display: inline-block;
    padding: 2rem 3%;
}

footer .footer-links__no-margin {
    padding-top: 3rem;
}

footer li a {
    font-size: .8em;
    color: #536c75;
}

footer li a:hover {
    color: #ccc;
}

footer p {
    background: #5E8796;
    color: #fff;
    padding: 1.6rem 0;
    font-size: .8em;
}

footer .footer-inner {
    max-width: 1000px;
    margin: 0 auto;
    position: relative;
}

footer .facebook-window {
    position: absolute;
    left: 30px;
}

footer .sitemap {
    position: absolute;
    left: 370px;
    vertical-align: top;
    text-align: left;
    margin-left: 60px;
    margin-right: 30px;
}

footer .sitemap h3 {
    padding: 1rem 0;
}

footer .sitemap ul {
    margin: 0;
    padding: 0;
}

footer .footer-inner .sitemap ul li {
    display: inline-block;
    padding: 0.6rem 0.8rem;
}
/*======================================================================
sp.css ~640px
======================================================================*/
@media screen and (max-width:640px) {
    /*------------------------------------------
    ヘッダー
    ------------------------------------------*/
    header {
        position: fixed;
        width: 100%;
        height: 58px;
        background: white;
        z-index: 999;
    }

    .headerInr {
        padding: 0 16px;
        height: 48px;
    }

    .headerInr .header__logo a {
        margin-top: 8px;
        width: 100%;
        background-size: 160px;
        height: 40px;
    }

    /* 検討中件数ボタン・新規登録ボタン・ログインボタン*/
    .user {
        top: 4px;
        right: 32px;
    }

    .user li {
        background: #5e8796;
        border-radius: 8px;
    }

    .user li a {
        width:38px;
        height:38px;
        padding:0px;
        padding-top:7px;
    }

    .user li a.invisible-sp {
        display:none;
    }

    .user li a span {
        display:block;
        font-size:9px;
        padding:2px 0px;
        text-align: center;
    }

    .user li a span:first-child {
        padding-bottom:1px;
    }

    .user li a span:last-child {
        padding-top:1px;
    }

    /* キーワード検索 */
    .headerInr .signin,
    .search form .searchBox,
    .search form .searchBtn {
        display: none;
    }

    /*------------------------------------------
     ナビゲーションメニュー
    ------------------------------------------*/
    nav {
        position: fixed;
        z-index: 1000;
        background: #5e8796;
    }

    nav ul {
        width: 100%;
    }

    nav ul li {
        width: 100%;
        min-height: 1.4rem;
    }

    nav ul li a {
        width: 100%;
        font-size: 1.2rem;
        padding: 1rem 0;
        line-height: 2rem;
        border: 0;
        border-bottom: 1px solid #fff;
    }

    .nav-mobile {
        display:none; /* Hide from browsers that don't support media queries */
        cursor:pointer;
        position:fixed;
        top:16px;
        right:8px;
        background:url(../../../../../front/images/navOpen.png) no-repeat center center;
        height:24px;
        width:24px;
        background-size: 24px 24px;
        z-index: 1000;
    }

    .nav-list {
        display: none;
        position: fixed;
        top: 58px;
        background: #5e8796;
    }

    .nav-active {
        display: block;
    }

    .nav-mobile {
        display:block;
    }

    .nav-mobile-open {
        background: url(../../../../../front/images/navClose.png)center no-repeat;
        background-size: 20px;
    }

    /*------------------------------------------
     フッター
    ------------------------------------------*/
    footer {
        margin-top: 0;
        padding-top: 0;
        background: #5E8796;
    }

    footer ul {
        letter-spacing: -1em; /* inline-blockの隙間対策 */
    }

    footer .footer-links {
        margin: 0;
        padding: 0;
    }

    footer .footer-links ul {
        border: 0;
    }

    footer .footer-links ul li,
    footer .footer-links__no-margin ul li {
        width: 50%;
        padding: 16px 8px;
        border-right: #9eb7c0 1px solid;
        border-bottom: #9eb7c0 1px solid;
        letter-spacing: normal;
    }

    footer .footer-links__no-margin {
        padding: 0;
    }

    footer li:nth-child(even) {
        border-right: 0;
    }

    footer li a {
        color: #fff;
    }

    footer li a:hover {
        color: #ccc;
    }

    footer p {
        color: #fff;
        padding: 16px 0;
        font-size: .8em;
    }

    footer .footer-links ul {
        margin-top: 0;
        padding-top: 0;
    }
}

/*======================================================================
tablet.css ~1024px
======================================================================*/
@media screen and (max-width: 1024px) {

    /*------------------------------------------
     ナビゲーションメニュー
    ------------------------------------------*/
    nav ul {
        width: 95%;
        display: flex;
    }

    nav li {
        min-height: 4.8rem;
        display: flex;
        justify-content: center;
    }

    nav li.small {
        width:auto;
    }

    nav li a {
        line-height: 1.4rem;
    }
}

.wrap {
    width:100%;
    margin:0 auto;
}

.bg {
    background: url(../../../../../front/images/absurdidad.png)repeat;
}

/* ==========================================================================
layout style
========================================================================== */
#inner-content {
	background: #fff;
	max-width: 1000px;
}

#main {
	background: none;
}

@media only screen and (max-width: 767px) {
	#inner-content {
		width: 100%;
		overflow: hidden;
	}

	#main {
		padding: 1em;
		float: none;
	}

	#sidebar1 {
		padding: 1em;
		float: none;
	}
}

@media only screen and (min-width: 1166px) {
	#inner-content {
		padding: 35px;
		margin-bottom:2em;
	}
	/* デスクトップの時にグリッドシステムを使わない */
	#main {
		margin: -35px;
		padding: 35px;
	}

	#sidebar1 {
		float:right;
		padding-right: 0;
	}
}

@media only screen and (min-width: 768px) and (max-width: 1165px) {
	#inner-content {
		padding: 25px;
	}
	/* タブレットの時にグリッドシステムを使わない */
	#main {
		width:70%;
		margin: -25px;
		padding: 25px;
	}

	#sidebar1 {
		width: 34%;
        float:right;
	}
}

/* ==========================================================================
Typography START
========================================================================== */
/* Googleフォント　※フォントを変更した場合はこちらも変更 */
.gf {
	font-family: 'Concert One', cursive;
	font-weight: normal;
}

abbr[title] {
	border-bottom: 1px dotted;
}

b,strong,.strong {
	font-weight: bold;
}

dfn,em,.em {
	font-style: italic;
	border-bottom: 1px dotted pink;
}

hr {
	margin: 1.8em 0;
	padding: 0;
	border: 0;
	height: 5px;
	background: url(library/images/line01.png) repeat-x 0 0;
}

pre {
	margin: 0;
}

code,
kbd,
pre,
samp {
	font-family: monospace, serif;
	padding: 0.05em 0.4em;
	border-radius: 3px;
	background: #F6F6F6;
	color: #444;
}

pre {
	white-space: pre-wrap;
}

q {
	quotes: "\201C" "\201D" "\2018" "\2019";
}

q:before,
q:after {
	content: '';
	content: none;
}

sub,
sup {
	line-height: 0;
	position: relative;
	vertical-align: baseline;
}

sup {
	top: -0.5em;
}

sub {
	bottom: -0.25em;
}
/* ==========================================================================
Typography END
========================================================================== */
menu {
	padding: 0 0 0 40px;
}

.entry-content ul li {
	position:relative;
	padding-left: 1em;
	margin: 3px 0;
}

.entry-content ul li:before {
	content:" ";
	width: 9px;
	height: 9px;
	background: #3E3E3E;
	box-shadow: 0 0 20px rgba(51, 51, 51, 0.15) inset;
	display:block;
	position:absolute;
	-webkit-border-radius:50%;
	-moz-border-radius:50%;
	border-radius:50%;
	left: 2px;
	top: 7px;
}

.entry-content ul li ul li:before {
	width: 5px;
	height: 5px;
	top: 10px;
	-webkit-border-radius:0;
	-moz-border-radius:0;
	border-radius:0;
}

.entry-content ul li ul li ul li:before {
	width:14px;
	height:1px;
	top:11px;
	left:-2px;
	-webkit-border-radius:0;
	-moz-border-radius:0;
	border-radius:0;
}

.entry-content ol {
	counter-reset: number;
}

.entry-content ol li {
	list-style:none;
	position:relative;
	padding-left: 1.4em;
}

.entry-content ol > li:before {
	counter-increment: number;
	content: counter(number);
	background: #3E3E3E;
	box-shadow: 0 0 5em rgba(51, 51, 51, 0.15) inset;
	color:#fff;
	width:1.5em;
	height:1.5em;
	font-weight:bold;
	font-family: 'Lato', sans-serif;
	display:block;
	text-align:center;
	line-height:1.5em;
	border-radius:50%;
	position:absolute;
	left:0;
	top: 3px;
}

.entry-content li ol li:before {
	background:#999;
	line-height:1.4;
}

.entry-content li li ol li:before {
	background:none;
	border: 1px solid #ccc;
	color:#555;
	width:1.5em;
	height:1.5em;
	border-radius:50%;
	top:3px;
}

.clearfix, 
.cf,
.comment-respond,
.widget ul li {
	zoom: 1;
}

.clearfix:before,
.clearfix:after,
.cf:before,
.comment-respond:before,
.cf:after,
.comment-respond:after
,.widget ul li:before
,.widget ul li:after {
	content: "";
	display: table;
}

.clearfix:after,
.cf:after,
.comment-respond:after,
.widget ul li:after {
	clear: both;
}

/*********************
LAYOUT & GRID STYLES
*********************/
.wrap {
	width: 96%;
	margin: 0 auto;
}

/*********************
HEADER STYLES
*********************/
.site_description {
	text-align: center;
	margin: 0;
	padding: 2px;
}

.site_description:empty {
	display:none;
}

/*********************
NAVIGATION STYLES
*********************/
.nav {
	border-bottom: 0;
}

.nav > li > a {
	padding: 12px .7em 6px;
}

.nav li a span {
	display:block;
	text-align:center;
	width:100%;
	font-weight: normal;
}

.nav li a .gf:empty {
	display:none;
}

.nav li li {
	text-align:left;
}

.nav li li span {
	display:none;
}

.nav li ul.sub-menu li a,
.nav li ul.children li a {
	padding-left: 15px;
}

/* 検索フォーム */
.searchbox .searchform {
	position:relative;
	height: 40px;
	margin-bottom: 1.5em;
}

.searchbox input[type="search"] {
	position:absolute;
	width: 100%;
}

.searchbox button#searchsubmit {
	position:absolute;
	right: 3px;
	top: 10%;
	border:0;
	background: none;
	display: block;
	height: 100%;
	padding:0 3%;
}

/*********************
POSTS & CONTENT STYLES
*********************/
.hentry {
	margin-bottom: 1em;
}

.hentry header {
	padding: 0;
	margin-bottom: 1.5em;
}

.hentry .eyecatch {
	text-align:center;
	overflow:hidden;
	position:relative;
}

.hentry .eyecatch img {
	margin:0;
	height: auto;
}

.attachment-post-thumbnail {
    min-width: 100%;
    max-width: 100%;
}

.article-footer {
    padding: 1em 0;
}

.article-footer p {
    margin: 0;
}

.article-footer .post-categories,
.article-footer .tags {
	margin:0;
	display:inline-block;
}

.article-footer .post-categories li,
.article-footer .tags a {
	display:inline-block;
}

.article-footer .post-categories li a,
.article-footer .tags a {
	display:inline-block;
	padding: .35em .5em;
	margin-right:0.3em;
	margin-bottom: 2px;
	text-decoration:none;
	line-height:1.1;
	font-size: .7em;
	border-radius: .4em;
}

.article-footer .tags a {
	background:none;
	color:#666;
	margin-top:-1px;
}

.article-footer .post-categories a:before,
.article-footer .tags a:before {
	font-family:"fontawesome";
	content: '\f292';
	margin-right:0.2em;
}

.article-footer .post-categories a:before {
	content: '\f114';
}

.article-footer .post-categories li a:hover,
.article-footer .tags a:hover {
	background:#E69B9B;
	color:#fff;
	border-color:#E69B9B;
}

.entry-content h2 {
	position: relative;
	border: none;
	padding: 0.5em 1.1em;
	margin-top: 2.1em;
	margin-bottom: 1em;
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	border-radius: 3px;
	background: #13acca;
	color: #ffffff;
	box-shadow: 0 1px 5px rgba(0, 0, 0, .03);
}

.entry-content h3 {
	border-left: 4px solid #13acca;
	padding: .4em 0 .4em .6em;
	margin-top: 1.8em;
	margin-bottom: 1em;
}

/* ページタイトル */
.single-title,
.entry-title {
	margin: .3em 0;
}

.single-title {
    padding: 0;
}

.single-title:empty {
    display: none;
}

.byline {
	margin: 0;
}

.byline .date {
	font-size: .85em;
}

.byline .date,
.byline .writer {
	filter: alpha(opacity=70);
	-ms-filter: "alpha(opacity=70)";
	opacity: .7;
	margin-right: .6em;
	display: inline-block;
}

.byline .date:before,
.byline .writer:before {
	font-family: "fontawesome";
	content: "\f101";
	margin-right: .3em;
	position: relative;
	top:-1px;
}

.byline .date:before {
	content: "\f274";
}

.byline .date.undo:before {
	content: "\f0e2";
}

.byline .writer:before {
	content: "\f007";
}

.byline .writer,
.byline .writer a {
	font-weight: bold;
	color: inherit;
	text-decoration:none;
	display: none;
}

.byline .cat-name {
	background: #fcee21;
	padding: 0.2em 0.4em;
	margin-top: 0.3em;
	margin-left: 0.9em;
	font-size: 0.7em;
	float:right;
}

.byline .cat-name:before {
	font-family: "fontawesome";
	content: "\f08d";
	display: inline-block;
	margin-right: .5em;
	transform: rotate(-20deg);
	-moz-transform: rotate(-20deg);
	-webkit-transform: rotate(-20deg);
}

/* entry content */
.entry-content {
	padding: 1.5em 0px 1.5em 0px;
	overflow: hidden;
}

.entry-content p {
    font-size: 1.07em;
    margin: 0 0 2em;
}

.entry-content .question{
    font-weight: bold;
    margin: 0 0 0.6em 0;
}

.entry-content dt {
	font-weight: bold;margin-bottom: 2%;
}

.entry-content dd {
	margin-left: 0;
	margin-bottom: 4%;
}

.entry-content img {
	margin: 0 0 1.5em 0;
	max-width: 100%;
	height: auto;
}

.entry-content .size-auto,
.entry-content .size-full,
.entry-content .size-large,
.popular-contents .size-large,
.entry-content .size-medium,
.entry-content .size-thumbnail {
    max-width: 100%;
    height: auto;
    margin: 0;
}

.tags {
	margin: 0;
}

/* simpleタイプ */
.top-post-list .post-list {
	position: relative;
}

.top-post-list .post-list a {
	display:block;
	padding: 1.9em .7em;
	text-decoration:none;
	border-bottom: 1px dotted #ccc;
	border-bottom: 1px dotted rgba(0,0,0,.2);
	position: relative;
}

.top-post-list .post-list:first-child a {
	border-top: 1px dotted #ccc;
	border-top: 1px dotted rgba(0,0,0,.2);
}

.top-post-list .post-list .eyecatch {
	width: 42%;
	float:left;
	max-height: 180px;
	overflow: hidden;
	position:relative;
}

.eyecatch .cat-name {
    text-align: center;
    position:absolute;
    top:0;
    right:0;
    background:#fcee21;
    color:#444;
    font-size:0.7em;
    font-weight:bold;
    padding:0.7em 0.5em;
    min-width:8em;
    opacity: .9;
    filter: alpha(opacity=90);
    -ms-filter: "alpha(opacity=90)";
}

.top-post-list .post-list .entry-content {
	padding: 0 0 0 3%;
	overflow: hidden;
}

.top-post-list .post-list .entry-content .entry-title {
    font-size: 1.4em;
    margin-top: 0;
    margin-bottom: 0.3em;
    line-height: 1.4;
}

.top-post-list .post-list .entry-content p {
	margin-bottom:0.3em;
}

/******************************************************************
ウィジェット
******************************************************************/
.widget a {
	text-decoration:none;
}

.widget a:hover {
	color:#999;
}

.widgettitle {
	background: #5e8796;
	color: #FFF;
	padding: 0;
	border-radius: 3px;
	margin-top: 0;
	margin-bottom: 0.75em;
	
}

.widgettitle span {
    font-size: .9em;
	display:block;
	width: 100%;
	padding: 0.6em 0.8em;
}

.widget {
	margin: 0 0 3em;
}

.widget ul {
	margin:0;
}

/* ウィジェットカテゴリー・新着記事 */
.widget.widget_categories .widgettitle,
.widget.widget_recent_entries .widgettitle,
.widget.widget_hot_articles .widgettitle {
    margin-bottom:0;
}

.widget.widget_categories ul,
.widget.widget_recent_entries ul,
.widget.widget_hot_articles ul {
    margin-top: 0;
}

.widget.widget_categories ul li,
.widget.widget_recent_entries li,
.widget.widget_hot_articles li {
    border-bottom: 1px dotted rgba(125, 125, 125, 0.2);
    margin:0;padding:0;
}

.widget.widget_categories li a,
.widget.widget_recent_entries li a,
.widget.widget_hot_articles li a {
    display:block;
    padding: 0.7em 1.2em 0.7em 0.3em;
    margin:0;
    position:relative;
    line-height: 1.4;
    font-size: .9em;
}

.widget.widget_categories li:last-child,
.widget.widget_recent_entries li:last-child,
.widget.widget_hot_articles li:last-child {
    border-bottom:none;
}

.widget.widget_categories li a:after,
.widget.widget_recent_entries li a:after,
.widget.widget_hot_articles li a:after {
    font-family:"fontawesome";
    content: '\f054';
    position:absolute;
    right: 0.2em;
    top:50%;
    margin-top: -0.5em;
}

.widget li a:hover:after {
	right:2px;
	-webkit-transform: translate(1px, 0);
	-moz-transform: translate(1px, 0);
	transform: translate(1px, 0);
}

/* リスト 下の階層 */
.widget.widget_categories li ul {
	padding:0;
	margin:0;
}

.widget.widget_categories li ul a {
	padding-left:0.8em;
}

.widget.widget_categories li ul ul a {
	padding-left:1.6em;
}

.widget.widget_categories li ul li:last-child {
	border:none;
}

/* ウィジェット検索フォーム */
.widget.widget_search .searchform {
	position:relative;
	height: 40px;
	margin-bottom: 1.5em;
}

.widget.widget_search input[type="search"] {
	position:absolute;
	width: 100%;
	padding: 12px 3%;
}

.widget.widget_search button {
	position:absolute;
	right: 3px;
	border:0;
	background: none;
	display: block;
	height: 100%;
	padding: .8em .8em;
}

/* 新着記事のサムネイル */
.widget.widget_recent_entries li .eyecatch,
.widget.widget_hot_articles li .eyecatch {
	width: 30%;
	max-width: 100px;
	float:left;
	margin-bottom:0.5em;
	margin-right:0.5em;
}

.widget.widget_recent_entries li span,
.widget.widget_hot_articles li span,
.widget li span.date {
	padding:0 0.3em;
	font-size:0.9em;
	opacity: .5;
	filter: alpha(opacity=50);
	-ms-filter: "alpha(opacity=50)";
	display:inline-block;
}

/******************************************************************
ARCHIVE PAGE STYLES
******************************************************************/
.archivettl h1 {
	margin:0 0 1.5em;
	padding:0 0.1em 0.8em;
	border-bottom: 3px solid;
}

.archivettl h1 span {
	display:block;
	line-height:1.3;
	font-weight:normal;
}

.archivettl h1 .author-icon img {
	-webkit-border-radius:50%;
	-moz-border-radius:50%;
	border-radius:50%;
	width:80px;
	margin-bottom:1.3em;
	border:2px solid #fff;
	box-shadow:0 0 10px #ddd;
}

/* Pagination , post Pagination */
.pagination {
    margin: 3em 0 4em;
}

.pagination,
.page-links {
	text-align: center;
}

.pagination ul,
.page-links ul {
	display: block;
	text-align: center;
	margin:0;
	padding:0;
	clear: both;
}

.pagination:empty,
.pagination ul:empty,
.page-links ul:empty {
    display:none;
}

.pagination li,
.page-links li {
	margin: 0.3em;
    padding:0;
	display: inline-block;
}

.entry-content .page-links li:before {
	content:none;
}

.pagination a, .pagination span,
.page-links a , .page-links ul > li > span {
	margin: 0;
	padding: 9px 14px;
	text-decoration: none;
	line-height: 1;
	font-weight: normal;
	color: #90adb7;
	border: 1px solid #5e8796;
}

.pagination span.dots,
.page-links ul > li > span.dots {
    background: none;
	padding-left: 11px;
	padding-right: 11px;
}

.pagination a:hover, 
.pagination a:focus,
.pagination span:hover,
.pagination span:focus,
.page-links a:hover,
.page-links a:focus {
	background-color: #5e8796;
	color: #fff;
}

.pagination .current,
.page-links ul > li > span {
	cursor: default;
	color: #fff;
	background-color: #5e8796;
}

.pagination .current:focus,
.pagination .dots:hover, .pagination .dots:focus {
	color: #111;
}

/*********************
TABLET & SMALLER LAPTOPS
*********************/
@media only screen and (min-width: 768px) {
	/*********************
	NAVIGATION STYLES
	*********************/
	.nav {
		margin: 0;
		border: 0;
	}

	.nav > li {
		float: left;
		position: relative;
		display: table-cell;
		text-align:center;
		vertical-align: middle;
		font-weight: bold;
		margin-bottom: -6px;
	}

	.nav > li > a:after {
		content:"";
		display:block;
		margin:5px auto 0;
		width:0;
		height:1px;
		background:#111;
		transition: .25s ease-out;
		-webkit-transition: .25s ease-out;
		-moz-transition: .25s ease-out;
		-o-transition: .25s ease-out;
		-ms-transition: .25s ease-out;
		opacity: .5;
		filter: alpha(opacity=50);-ms-filter: "alpha(opacity=50)";
	}

	.nav > li > a:hover:after {
		width:100%;
	}

	.nav li a {
		border-bottom: 0;
	}

	.nav ul {
		margin-top: 0;
	}

	.nav li ul.sub-menu,
	.nav li ul.children {
		font-weight: bold;
		margin: 0;
		position: absolute;
		z-index: 8999;
		border-radius: 3px;
	}

	.nav li ul.sub-menu li,
	.nav li ul.children li {
		position: relative;
		overflow: hidden;
		height: 0;
		transition: .2s;
	}

	.nav li:hover > ul.sub-menu > li,
	.nav li:hover > ul.children > li {
		overflow: visible;
		height: 36px;
		border-bottom: 1px solid rgba(255, 255, 255, 0.2);
	}

	.nav li ul.sub-menu li a,
	.nav li ul.children li a {
		display:block;
		width:240px;
	}

	.nav li ul.sub-menu li a:hover,
	.nav li ul.children li a:hover {
		filter: alpha(opacity=80);
		-ms-filter: "alpha(opacity=80)";
		opacity:0.8;
	}

	.nav li ul.sub-menu li:last-child a,
	.nav li ul.children li:last-child a {
		border-bottom: 0;
	}

	.nav li ul.sub-menu li ul,
	.nav li ul.children li ul {
		top: 0;
		left: 100%;
	}

	/*********************
	SIDEBARS & ASIDES
	*********************/
	.widget ul li {
		margin-bottom: 0.75em;
	}

	.widget ul li ul {
		margin-top: 0.75em;
		padding-left: 1em;
	}

	/*********************
	FOOTER STYLES
	*********************/
	.footer-links ul {
		padding:0;
		margin:1em 0;
	}

	.footer-links ul li {
		display:inline;
	}

	.footer-links ul li:after {
		content:'　|　';
	}

	.footer-links ul li:last-child:after {
		content:none;
	}

	.footer-links ul li a {
		text-decoration:none;
	}
}
/*--------------------------------
SNS
---------------------------------*/
.sharewrap {
	margin: 0 0 1em;
}

.share {
	width:101%;
}

.sns {
	margin:0 auto;
	text-align:center;
}

.sns ul {
    margin:0 auto;
    list-style:none;
}

.sns li {
	float:left;
	width: 49%;
	margin: 0 1% 2% 0;
    font-size: .75em;
    height: 44px;
}

.share.short .sns li {
	width: 19%;
	margin:0 1% 0 0;
}

/*-------------------------
  ブログカード
-------------------------*/
.blog-card {
 	background: #fbfaf8;
	border:1px solid #ddd;
	word-wrap:break-word;
	max-width:100%;
	border-radius:5px;
	margin: 10px 0 10px 0;
  /* sp用 ※4 */
}

.blog-card:hover {
	background: #EEEEEE;
}

.blog-card a {
	text-decoration: none;
}

.blog-card-title {
	display: block;
}

.blog-card-thumbnail {
    width: 150px;
    float: left;
    padding: .7em;
}

.blog-card-thumbnail img {
	display: block;
	padding: 0;
	-webkit-transition: 0.3s ease-in-out;
	-moz-transition: 0.3s ease-in-out;
	-o-transition: 0.3s ease-in-out;
	transition: 0.3s ease-in-out;
}

.blog-card-content .blog-card-discription {
    margin: 1em;
    font-size: .9em;
}

.blog-card-content .blog-card-title {
    font-size: 1.1em;
    font-weight: bold;
    line-height: 1.5em;
    margin: .5em;
}

.blog-card-excerpt {
    color:#333;
    margin:0 10px 10px;
}

.blog-card-content .blog-card-excerpt h2 {
	color: #5e8796;
	padding: 0px;
	margin: 0px;
	background-color: transparent;
	box-shadow: none;
}

.blog-card .clear {
	clear: both;
}
/*-------------------------
人気記事表示　プラグインなし
-------------------------*/
.popular-post {
	margin: 0 0 7px 0;
}

.popular-contents {
    width: 24%;
    min-height: 100px;
    margin: 0 1% 1% 0;
    float: left;
}

.popular-eyecatch {
    width: 100%;
    max-height: 180px;
    height: 90px;
    overflow: hidden;
    position: relative;
}

.popular-eyecatch .size-large {
    height: 100%;
}

.popular-eyecatch img {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.popular-eyecatch span.cat-name {
	text-align: center;
    position: absolute;
    top: 0;
    right: 0;
    background: #fcee21;
    color: #444;
    font-size: 0.7em;
    font-weight: bold;
    padding: 0.1em 0.5em;
    min-width: 8em;
    opacity: .9;
}

p.title,
.screen-reader-text {
    font-size: .9em;
    line-height: 1.3;
    font-weight: bold;
}

.description {
    color: #666;
    line-height: 1.4;
}
/* ---------------------------------------------
 Mobile Styles
--------------------------------------------- */
@media only screen and (max-width: 767px) {
	body {
		position: relative;
		overflow-x: hidden;
		left: 0;
	}

	.article-header .eyecatch,
	.cta-wrap {
	margin-left: -1em;
	margin-right: -1em;
	}

	.search_btn {
		right:0;
		left: auto;
	}

	.search_btn:before {
		height: 1.1em;
		font-family: "fontawesome";
		content: '\f0c9';
		width: 100%;
		display: block;
	}

	.search_btn:before {
		content: '\f002';
	}

	.nav li .gf {
		display:none;
	}

	@-webkit-keyframes blink {
		0% {
			opacity:.2;
		}

		100% {
			opacity:.9;
		}
	}
	@-moz-keyframes blink {
		0% {
			opacity:.2;
		}

		100% {
			opacity:.9;
		}
	}
	@keyframes blink {
		0% {
			opacity:.2;
		}

		100% {
			opacity:.9;
		}
	}
	/* archives */
	.top-post-list .post-list .entry-content .description {
		display:none;
	}

	/* footer mobile */
	/* ページトップへ */
	#page-top {
		right: 10px;
	}

	#page-top a {
		background-repeat: no-repeat;
		text-decoration: none;
		width: 42px;
		height: 42px;
		line-height:41px;
	}

	.footer .inner {
		text-align:center;
	}

	.footer-links ul {
		margin:1em 0 0;
	}

	.footer-links li {
		display: inline-block;
		margin-right: .5em;
	}

	.footer-links li a:before {
		font-family: "fontawesome";
		content: '\f0da';
		margin-right: 0.3em;
	}

	.footer-links a {
		text-decoration:none;
		padding: .3em;
		display: block;
	}

	.copyright {
		padding: 1.5em 0;
		margin: 0;
	}

	.related-box li.related-rightlist {
		zoom: 1;
	}

	.related-box li.related-rightlist:after {
		content:"";
		display:table;
		clear:both;
	}

	/* バイラルPOST */
	/* Facebook viralbox */
	.fb-likebtn .fb-button {
		transform: scale(1);
		-webkit-transform: scale(1);
		-moz-transform: scale(1);
	}

	.fb-likebtn .like_text p {
		margin-bottom: 0;
		padding: 0;
	}

	/* Grid System */
	.m-all {
		float: left;
		padding-right: 0.75em;
		width: 100%;
		padding-right: 0;
		margin-bottom: .5em;
	}

	.m-1of2 {
		float: left;
		padding-right: 0.75em;
		width: 50%;
	}

	.m-1of3 {
		float: left;
		padding-right: 0.75em;
		width: 33.33%;
	}

	.m-2of3 {
		float: left;
		padding-right: 0.75em;
		width: 66.66%;
	}

	.m-1of4 {
		float: left;
		padding-right: 0.75em;
		width: 25%;
	}

	.m-3of4 {
		float: left;
		padding-right: 0.75em;
		width: 75%;
	}
}
/* ---------------------------------------------
 SMART PHONE Styles
--------------------------------------------- */
@media only screen and (max-width: 480px) {
	.byline .cat-name:before {
		content: none;
	}

	.post-list-card .post-list {
		width: 100%;
		margin: 3% 0 7%;
		float:none;
		height:auto;
	}

	.post-list-card .post-list .eyecatch {
		max-height: inherit;
		height:auto;
	}

	.post-list-card .post-list .eyecatch img {
		width:100%;
	}

	.top-post-list {
		margin-left: -1em;
		margin-right: -1em;
	}

	.single .entry-content,
	.page .entry-content {
		overflow: inherit;
	}

	/* Post eyecatch height */
	.top-post-list .post-list .eyecatch {
		max-height: 82px;
	}

	.add.more {
		margin-left:-0.9em;
	}

	.top-post-list .post-list a {
		padding:0.7em;
	}

	.pagination li {
        margin-bottom: 1em;
	}

	.pagination a, .pagination span,
	.page-links a , .page-links ul > li > span {
		padding: 8px 10px;
	}

	/* POSTS */
	.entry-content h2 {
		margin-left: -2%;
		margin-right: -2%;
	}

	.entry-content table {
		table-layout: fixed;
	}

	.aligncenter, img.aligncenter {
		margin-right: auto;
		margin-left: auto;
		display: block;
		clear: both;
	}

	.alignleft, img.alignleft {
        max-width:52%;
	}

	.alignright, img.alignright {
        max-width:52%;
	}

	/* 人気記事表示　プラグインなし */
	.popular-contents {
		width: 98%;
	    margin: 0 1% 1% 0;
	    float: left;
	    padding: 8px 0px;
	    border-top: 1px solid #e3e3e3;
	}

	.popular-eyecatch {
        width: 50%;
        max-height: 180px;
        float: left;
        margin-right: 10px;
	}

    .popular-eyecatch img {
        position: absolute;
        min-width: 45vmin;
        width: 45vmin;
        min-height: 90px;
        height: 90px;
        margin: 0;
        object-fit: cover;
    }

	.popular-eyecatch span.cat-name {
		text-align: center;
	    position: absolute;
	    top: 0;
	    right: 0;
	    background: #fcee21;
	    color: #444;
	    font-weight: bold;
	    padding: 0.1em 0.5em;
	    min-width: 8em;
	    opacity: .9;
	}

	div.popular-title {
		float: left;
		width: 46%;
	}
}

/* ---------------------------------------------
 Tablet Styles
--------------------------------------------- */
@media only screen and (min-width: 768px) and (max-width: 1165px) {
    .header .wrap  {
        width: 100%;
    }

	/* Scroll max width */
	#scrollfix.fixed {
		max-width: 253px;
	}

	/* Tablet SNS */
    .sns li {
        width: 32.3%;
        margin: 0 1% 2% 0;
        font-size: .75em;
        height: 44px;
    }

	.t-all {
		float: left;
		padding-right: 0.75em;
		width: 100%;
		padding-right: 0;
	}
}

/* ---------------------------------------------
 Desktop Styles
--------------------------------------------- */
@media only screen and (min-width: 1166px) {
	.wrap {
		width: 1166px;
	}

	.cta-inner {
		padding: 1em 2em;
	}

	.header-info {
	margin-top: -1em;
	}

	/* archives hover animation */
	.top-post-list .post-list:before {
		content: "";
		display: block;
		width: 0%;
		height: 100%;
		background: #111;
		position: absolute;
		top:0;
		left:50%;
		z-index: 0;
		transition:.3s;
		opacity: .05;
		filter: alpha(opacity=5);-ms-filter: "alpha(opacity=5)";
	}

	.top-post-list .post-list:hover:before {
		width: 100%;
		height: 100%;
		left:0;
	}

	/* gnav height */
	.nav > li > a {
		display: block;
		text-decoration: none;
		line-height: 1.3;
		padding: 15px 1.8em 7px;
	}

	/* single post */
	.single .byline {
		position: relative;
	}

	.single .byline .cat-name {
		position:absolute;
		left:-6em;
		top: -2.9em;
		transform: rotate(-4deg);
		padding: .3em .8em .3em .5em;
	}

	/* SNSボタン（PCサイズ） */
	.share.short {
		margin-top:1em;
		margin-bottom: 1.5em;
	}

	.sns ul {
		margin:0 auto;
		list-style:none;
	}

	.sns li {
		width: 32.3%;
		margin: 0 1% 2% 0;
	}

	.sns li a {
		padding: 15px 2px;
	}
    
    /* Grid System */
    .d-2of7 {
        float: left;
        padding-right: 0.75em;
        width: 28.5714286%;
    }
    .d-5of7 {
        float: left;
        padding-right: 0.75em;
        width: 71.4285715%;
    }
}

/*======================================================================
sp.css ~640px
======================================================================*/
@media screen and (max-width:640px) {
    h1 {
        font-size: 1.3em;
    }

    .top-post-list .post-list .entry-content .entry-title {
        font-size: 1.2em;
        margin-top: 0;
        margin-bottom: 0.3em;
        line-height: 1.2;
        font-weight: normal;
    }

    .wrap {
        padding-top: 58px;
    }

    .invisible-sp {
        display: none;
    }

    .content {
        margin: 10% 2.5% 5% 2.5%;
    }

    .bg {
        background: #5e8796;
    }

    .main-content {
        margin: 3rem 0 0 0;
    }

    .data-table .data {
        display: block;
        padding: 1rem;
    }

    h3.data-table__title {
        padding: 1rem 1.5rem;
    }

    .data-table .name {
        width: 100%;
        display: block;
        background-color: #fff;
        padding: 0 0 0 0.8rem;
        line-height: 2rem;
    }

    .data-table .value {
        width: 100%;
        display: block;
        padding: 0 1rem;
        line-height: 2rem;
    }

    /*------------------------------------------
　   会員登録固定ボタン
    ------------------------------------------*/
    .btn {
        color: #fff;
        background: #5e8796;
        padding: .2em .6em;
        border-radius: .1em;
        text-align: center;
        display: table;
        cursor: pinter;
        border-radius: 50%;
        height: 20px;
        width: 20px;
        font-size: 15px;
    }

    .btn:hover {
        cursor: pinter;
    }

    .btn:active {
        background: rgb(127, 194, 239);
    }

    .box {
        border-radius: .1em;
        max-width: 100%;
        height: auto;
        line-height: 0;
    }

    /* close button */
    .action-close {
        position: relative;
        margin-top: 2em;
    }

    .action-close .btn {
        position: absolute;
        right: 1em;
        top: -1em;
    }

    /* checkbox non-display */
    .add-control .checkbox {
        display: none;
    }

    /* close button's control */
    .add-control .action-close #close:checked ~ .btn {
        display: none;
    }

    .add-control .action-close #close:checked ~ .box {
        display: none;
    }

    .fix_menu_smartphone {
        position: fixed;
        bottom: -2px;
        left: 0px;
        z-index: 10000;
        display: block;
        min-width: 100%;
        height: auto;
    }

    .fix_menu_smartphone img {
        max-width: 100%;
        height: auto;
    }
}

/*======================================================================
tablet.css 641px~1024px
======================================================================*/
@media screen and (max-width: 1024px) {
    /*======================================================================
    tablet.css 641px~
    ======================================================================*/
    @media screen and (min-width: 641px) {
        .invisible-tab {
            display: none;
        }
    }
}
/*======================================================================
pc.css 1025px~
======================================================================*/
@media screen and (min-width: 1025px) {
    .invisible-pc {
        display: none;
    }
}

/* コラム */
header,
nav,
footer,
.breadcrumbs {
    -webkit-font-smoothing: subpixel-antialiased;
    -moz-osx-font-smoothing: initial;
}

/* パンくずリスト */
.breadcrumbs {
    width: 100%;
    max-width: 1000px;
    margin: 1rem auto;
    font-size: .5em;
}

.breadcrumbs a {
    color: #5e8796;
}

.breadcrumbs .next {
    padding: 0 4px;
}

/*------------------------------------------
コラム　上へもどるボタン
------------------------------------------*/
.page_top {
    position: fixed;
    bottom: 10px;
    right: 10px;
    padding: 5px 15px;
    color: #becfd5;
    font-size: 20px;
    text-decoration: none;
    background: #536C75;
    border-radius: 30px;
    -webkit-border-radius: 30px;
    -moz-border-radius: 30px;
    z-index: 100;
}

.page_top:hover {
    background: #becfd5;
    color: #536C75;
}

/*======================================================================
tablet.css 641px~1024px
======================================================================*/
@media screen and (max-width: 1024px) {
    .breadcrumbs {
        width: 95%;
    }
}

/*--------------------------------
この記事を書いた人
---------------------------------*/
.st-author-box {
    font-size: .8em;
}

.st-author-box .fa {
    margin-right:4px;
}

ul#st-tab-menu,
.post ul#st-tab-menu {
    margin:0;
    padding:0;
}

ul#st-tab-menu li {
    display: inline-block;
    padding: 5px 10px;
    font-size: .95em;
    font-weight:bold;
}

ul#st-tab-menu li.active {
    background: #000;
    color: #fff;
    margin:0;
}

ul#st-tab-menu li.active::before {
    content:none;
}

/* タブの中身 */
#st-tab-box {
    padding: 15px;
    border: 1px solid #000;
    margin-bottom:20px;
    background:#fff;
}

#st-tab-box p {
    line-height:1.5;
}

.st-author-master #st-tab-box {
    margin-bottom:0;
}

#st-tab-box div {
    display: none;
}

#st-tab-box div.active {
    display: block;
}

#st-tab-box div dt {
    float:left;
    width:80px;
}

#st-tab-box div dd {
    padding-left:100px;
}

#st-tab-box p.st-author-post a,
.post #st-tab-box p.st-author-post a {
    text-decoration:none;
    font-weight:bold;
}

.st-author-nickname {
    font-weight:bold;
    margin-bottom:10px;
    border-bottom:1px dotted #1a1a1a;
}

.avatar {
    border-radius: 50%;
}
/*--------------------------------
AMP用　SNSボタン
---------------------------------*/
amp-social-share[type=pocket] {
    width: 100%;
    background-color: #EF3E55;
    font-weight: 700;
    color: #fff;
    height: 44px;
    text-align: center;
    font-size: 28px;
    border-radius: 2px;
}

amp-social-share[type=feedly] {
    width: 100%;
    background:#87c040;
    font-weight: 700;
    color: #fff;
    min-height: 44px;
    height: 44px;
    text-align: center;
    font-size: 28px;
    border-radius: 2px;
}

amp-social-share[type=hatena_bookmark] {
    min-width: 100%;
    font-family: Verdana;
    background-color: #00A4DE;
    font-weight: 700;
    color: #fff;
    min-height: 44px;
    height: 44px;
    text-align: center;
    font-size: 28px;
    border-radius: 2px;
}

amp-social-share[type=twitter] {
    min-width: 100%;
    min-height: 44px;
    height: 44px;
    border-radius: 2px;
}

amp-social-share[type=facebook] {
    min-width: 100%;
    min-height: 44px;
    height: 44px;
    border-radius: 2px;
}

amp-social-share[type=line] {
    min-width: 100%;
    width: 100%;
    min-height: 44px;
    height: 44px;
    text-align: center;
    font-size: 28px;
    border-radius: 2px;
}

amp-sidebar {
    width: 80vw;
    background-color: #DDDDDD;
}

.close-sidebar {
  font-size: 1.5em;
  padding-left: 5px;
}

.searchfield {
    font-size: 14px;
    width: 85%;
    border: #5e8796 1px solid;
    border-radius: 8px;
    padding: 8px;
    margin-right: 4px;
}

.clear {
    clear: both;
}
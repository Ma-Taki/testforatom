

<div class="recent-post-sidebar main-content-right">
<p class="sidebar-title">最新コラム</p>
<div>
@foreach($posts as $post)
<a href = "{{$post->guid}}">
  <p>{{$post->post_title}}</p>
  <span>{{$post->post_date}}</span>
  <div class="arrow">></div>
</a>
@endforeach
</div>
</div>

<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet">
<style>

  /*サイドバー*/

  .recent-post-sidebar{
    width:200px;
    margin-top:20px;
  }

  .sidebar-title{
    background-color:#5E8796;
    letter-spacing:3px;
    border-radius:5px;
    font-size:15px;
    padding:10px 10px;
    color:white;
    box-sizing: border-box;
  }

  .recent-post-sidebar div a {
    display: block;
    position:relative;
    border-bottom:1px dotted lightgray;
    padding:15px 0px;
    padding-right:20px;
  }

  .recent-post-sidebar div p{
    padding: 0;
    margin-bottom:3px;
  }

  .recent-post-sidebar div a span{
    font-size:10px;
    color:lightgray;
    font-weight:bold;
  }
  .recent-post-sidebar .arrow{
    font-family: "fontawesome";
    content: '\f054';
    position: absolute;
    right: 0.2em;
    font-size: 1em;
    top: 50%;
    margin-top: -0.5em;
  }
  .recent-post-sidebar .arrow:hover{
    right:5px;
  }




</style>

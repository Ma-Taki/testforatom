<?php
    use App\Libraries\BreadcrumbsUtility as BrdcrmbsUtil;
    $breadcrumbs = null;
    if (isset($item)) {
        // 案件詳細
        $breadcrumbs = (new BrdcrmbsUtil())->createBreadcrumbsByItem($item);
    } elseif (isset($itemList)) {
        // 案件一覧
        $breadcrumbs = (new BrdcrmbsUtil())->createBreadcrumbsByUri($_SERVER["REQUEST_URI"]);
    } elseif (isset($item_id) && isset($item_name)) {
        // Ajax処理後の画面遷移
        $breadcrumbs = (new BrdcrmbsUtil())->createBreadcrumbsByItemPiece($item_id, $item_name);
    }
?>
<div class="breadcrumbs" itemscope itemtype="http://schema.org/BreadcrumbList">
  @foreach ($breadcrumbs as $key => $value)
    <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
      @if ($value->get('path'))
        <a class="hover-thin" itemprop="item" href="{{ $value->get('path') }}">
          <span itemprop="name">{{ $value->get('name') }}</span>
        </a>
      @else
        <span itemprop="name">{{ $value->get('name') }}</span>
      @endif
      <meta itemprop="position" content="{{ ++$key }}" />
    </span>
    @if ($value->get('path'))
      <span class="next">></span>
    @endif
  @endforeach
</div>
<!-- END .breadcrumbs -->

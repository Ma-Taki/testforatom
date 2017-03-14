<?php

namespace App\Libraries;

use Illuminate\Http\Request;

use App\Models\Tr_tags;
use App\Models\Tr_search_categories;

/**
 * View用パンくずリスト作成ユーティリティー
 * 案件一覧、案件詳細でのみ使用している。
 * いずれ全画面に対応させたい。
 */
class BreadcrumbsUtility
{
    /**
     * 案件一覧ページのパンくずリストを返却する
     * 渡されるURIは以下の4パターン
     * /item/search?query
     * /item/category/"カテゴリID"
     * /item/tag/"タグID"
     * /item/keyword?query
     */
    public function createBreadcrumbsByUri($uri) {

        $breadcrumbs = $this->getRootByItemList();

        $uri =  parse_url($uri);
        $uri_array = explode('/', $uri['path']);
        $uri_array = array_filter($uri_array, "strlen");
        $uri_array = array_values($uri_array);

        // TODO 今更だけど、DBアクセスしちゃうし、ItemControllerでやった方がパフォーマンス的に良い。
        // タグ検索
        if ($uri_array[1] == "tag") {
            // TODO intじゃなかったら弾く
            $tag = Tr_tags::where('id', $uri_array[2])->get()->first();
            $breadcrumbs->push($this->getElementItemListHasPath());
            if ($tag) {
                $breadcrumbs->push(
                    collect([
                        'name' => 'タグ検索【'.$tag->term.'】',
                        'path' => '',
                    ]));
            }
        // カテゴリー検索
        } elseif ($uri_array[1] == "category") {
            $category = Tr_search_categories::where('id', $uri_array[2])->get()->first();
            $breadcrumbs->push($this->getElementItemListHasPath());
            if ($category) {
                if ($category->parent_id) {
                    $parent = Tr_search_categories::where('id', $category->parent_id)->get()->first();
                    $breadcrumbs->push(
                        collect([
                            'name' => $parent->name,
                            'path' => '/item/category/'.$parent->id,
                        ]));
                }
                $breadcrumbs->push(collect([
                    'name' => $category->name,
                    'path' => '',
                ]));
            }
        // キーワード検索
        } elseif ($uri_array[1] == "keyword") {
            // query文字列の先頭20文字だけ表示
            $query = $_GET['keyword'];
            $suffix = '';
            if (mb_strlen($query) > 20) $suffix = '...';
            $query = mb_substr($query, 0, 20);
            $query = mb_convert_kana($query, 's');
            $breadcrumbs->push($this->getElementItemListHasPath());
            $breadcrumbs->push(
                collect([
                    'name' => 'キーワード検索 【' .$query .$suffix .'】',
                    'path' => '',
                ]));
        // その他の検索
        } elseif ($uri_array[1] == "search") {
            $breadcrumbs->push($this->getElementItemList());
        }

        return $breadcrumbs;
    }

    /**
     * 一つの案件情報を受け取り、パンくずリストを返却する
     */
    public function createBreadcrumbsByItem($item) {

        // 紐付いた親カテゴリーを取得
        $category_parents = null;
        $category_parents = $item->searchCategorys->filter(function($value, $key){
            return $value["parent_id"] == null;
        });

        // 紐付いた子カテゴリーを取得
        $category_childs = null;
        $category_childs = $item->searchCategorys->filter(function($value, $key){
            return $value["parent_id"] != null;
        });

        $category_child  = null;
        $category_parent = null;
        if (!$category_childs->isEmpty()) {
          // エンジニアルート > 案件一覧 > 親カテゴリ > 子カテゴリ > 案件名
          $category_child = $category_childs->values()[0];
          foreach ($category_parents as $key => $value) {
            if ($value->id == $category_child->parent_id) {
                $category_parent = $value;
                break;
            }
          }
        } else if (!$category_parents->isEmpty()) {
          // エンジニアルート > 案件一覧 > 親カテゴリ > 案件名
          $category_parent = $category_parents->values()[0];
        } else {
          // エンジニアルート > 案件一覧 > 案件名
        }

        $breadcrumbs = $this->getRootByItemDetail();

        if ($category_parent) {
            $breadcrumbs->push(collect([
                'name' => $category_parent->name,
                'path' => '/item/category/'.$category_parent->id,
            ]));
        }

        if ($category_child) {
            $breadcrumbs->push(collect([
                'name' => $category_child->name,
                'path' => '/item/category/'.$category_child->id,
            ]));
        }

        // エントリー確認画面から呼ばれた場合
        $uri =  parse_url($_SERVER["REQUEST_URI"]);
        if ($uri["path"] == "/entry") {
            $breadcrumbs->push(collect([
                'name' => $item->name.'【AN' .$item->id .'】',
                'path' => '/item/detail?id='.$item->id,
            ]));
            $breadcrumbs->push(collect([
                'name' => 'エントリー',
                'path' => '',
            ]));

        // 案件詳細画面から呼ばれた場合
        } else {
            $breadcrumbs->push(collect([
                'name' => $item->name.'【AN' .$item->id .'】',
                'path' => '',
            ]));
        }

        return $breadcrumbs;
    }

    private function getRootByItemList() {
        return collect([$this->getElementHome()]);
    }

    private function getRootByItemDetail() {
        return collect([$this->getElementHome(),
                        $this->getElementItemListHasPath()]);
    }

    private function getElementHome() {
        return collect(['name' => 'エンジニアルート',
                        'path' => '/']);
    }

    private function getElementItemListHasPath() {
        return collect(['name' => '案件一覧',
                        'path' => '/item/search']);
    }

    private function getElementItemList() {
        return collect(['name' => '案件一覧',
                        'path' => '']);
    }
}

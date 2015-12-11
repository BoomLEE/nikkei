<?php
include_once 'simple_html_dom.php';

$nikkei_page_list = getNiikeiUrlList();
$result = array();
foreach ($nikkei_page_list as $category => $page_url) {
    $html = file_get_html($page_url);
    $count = 0;
    foreach($html->find('.cmn-article_title') as $element){
        foreach ($element->find('a') as $url){
            $result[$category][$count]['url'] = "http://www.nikkei.com/article".$url->href;
            $result[$category][$count]['title'] = $url->plaintext;
            $count++;
        }
    }
}

$category_name = getCategoryName();
$contents = "本日の重要記事一覧 (".date("Y年m月d日 H:i:s").") \r\n";
foreach ($result as $category => $news_list) {
    $contents.=" [".$category_name[$category]."] ( ".$nikkei_page_list[$category]." )\r\n";
    foreach ($news_list as $no => $news) {
        $contents.=" TITLE : ".$news['title']."(".$news['url'].") \r\n";
    }
}

exit;

function getNiikeiUrlList(){
    $list = array();
    $list['economy'] = "http://www.nikkei.com/news/category/?at=DGXZZO0195164008122009000000";
    $list['internation'] = "http://www.nikkei.com/news/category/?at=DGXZZO0195570008122009000000";
    $list['company'] = "http://www.nikkei.com/news/category/?at=DGXZZO0195165008122009000000";
    $list['social'] = "http://www.nikkei.com/news/category/?at=DGXZZO0195583008122009000000";
    return $list;
}

function getCategoryName(){
    $list = array();
    $list['economy'] = "経済";
    $list['internation'] = "国際";
    $list['company'] = "企業";
    $list['social'] = "社会";
    return $list;
}

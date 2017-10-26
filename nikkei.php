<?php
include_once 'simple_html_dom.php';
/**
* 日経経済新聞から必要な記事をタイトルとサマリを取得して転送する
* php nikkei.phpで動くはず
**/

$nikkei_page_list = getNiikeiUrlList();
$result = array();
foreach ($nikkei_page_list as $category => $page_url) {
    $html = file_get_html($page_url);
    $count = 0;
    foreach($html->find('.cmn-article_title') as $element){
        foreach ($element->find('a') as $url){
            $result[$category][$count]['url'] = "http://www.nikkei.com".$url->href;
            $result[$category][$count]['title'] = $url->plaintext;
            $count++;
        }
    }
}

$category_name = getCategoryName();
$contents = "本日の重要記事一覧 (".date("Y年m月d日 H:i:s").") \r\n";
foreach ($result as $category => $news_list) {
    $contents.="\r\n [".$category_name[$category]."] ( ".$nikkei_page_list[$category]." )\r\n";
    foreach ($news_list as $no => $news) {
        $contents.= $no." : ".$news['title']."(".$news['url'].") \r\n";
    }
}

sendMail($contents);

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

function sendMail($contents){
    
    $mail_list = array("deabum1@gmail.com");
    $to = "";
    foreach ($mail_list as $no => $mail) {
        $to.= $mail." , ";
    }
    $subject = '## 日経新聞くらいは、毎朝、読まないと !!! ###';
    $message = $contents;
    $headers = 'From: db@purppo.com' . "\r\n";
    //$headers .= 'Cc: test@purppo.com' . "\r\n";
    //$headers .= 'Cc: test2@purppo.co,' . "\r\n";
    
    if(mail($to, $subject, $message, $headers) == false){
        echo "失敗しました。";
        //TODO
        exit;
    }
}

<?php
        // put your code here
    include_once './phpquery_helper.php';
    include_once './sovoia_helper.php';
    header("Content-Type: application/json");
    
    $url="http://www.ziplist.com/recipes/discovery/";
    
     if (isset($_REQUEST['discover'])){
        $discover=$_REQUEST['discover'];
    }else
        $discover="popular_recipes";
    
    $url.=$discover."?";
    
    if (isset($_REQUEST['page'])){
        $url.="page=".$_REQUEST['page'];
    }
    if (isset($_REQUEST['search'])){
        $url.="search_string=".$_REQUEST['search'];
    }
    $response=curlCall($url);
    $doc = phpQuery::newDocumentHTML($response[0]);
    phpQuery::selectDocument($doc);
    $mainresult=array();
    $mainresult['discover']=array('trending_recipes','popular_recipes','most_cooked_recipes','random_recipes');
    $mainresult['data']=array();
    foreach (pq('#feed_data .recipe') as $dishes){
        $temp=array();
        $temp['image']=pq($dishes)->find('.recipe-image')->attr('src');
        $temp['title']=pq($dishes)->find('.title h3')->text();
        $temp['id']=pq($dishes)->find('.top-content h3 a')->attr('href');
        $temp['source']=pq($dishes)->find('.top-content h3 a')->attr('title');
        $temp['description']=pq($dishes)->find('.description')->text();
        $temp['stats']=trim(pq($dishes)->find('.stats')->text());
        
        $mainresult['data'][]=$temp;
    }
    
    echo json_encode($mainresult);
?>


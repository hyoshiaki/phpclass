function getRSSData($url,$options=NULL){
  if($options){
    $context = stream_context_create($options);
    $rssdata = simplexml_load_string(file_get_contents($url,false,$context));
  }else{
    $rssdata = simplexml_load_string(file_get_contents($url));
  }

  if($rssdata->channel->item){
    $rssdata = $rssdata->channel;
  }
  if($rssdata->item || $rssdata->entry){
    if($rssdata->item)  $q = $rssdata->item;
    if($rssdata->entry) $q = $rssdata->entry;
  }
  
  $rst = array();
  foreach($q as $key=>$myEntry){
    //公開日取得
    $rssDate = $myEntry->pubDate;
    if(!$rssDate) $rssDate = $myEntry->children("http://purl.org/dc/elements/1.1/")->date;
    if(!$rssDate) $rssDate = $myEntry->published;

    //タイトル取得
    $myTitle = (string)$myEntry->title;
    
    //リンクURL取得
    $myLink = (string)$myEntry->link; 
    
    $rst[$key] = array(
    	          "title"   => $myTitle;
                  "link"    => $myLink;
                  "pubDate" => $rssDate;
                 );
  }
  return $outdata;
}

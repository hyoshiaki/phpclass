<?php
class LineBot{
  public $access_token = "";
  public $reply_token  = "";
  const  APIURL = "https://api.line.me/v2/bot/";

  public function __construct($access_token){
    $this->access_token = $access_token;
  }

  public function parse_message($json_string){
    $obj = json_decode($json_string);
    $rst = array();

    $rst['type'] = $obj->{"events"}[0]->{"message"}->{"type"};
    $rst['text'] = $Obj->{"events"}[0]->{"message"}->{"text"};

    $this->replay_token = $Obj->{"events"}[0]->{"replyToken"}; 
 
    return $rst;
  }

  public function reply_text($mes){
    $response_format_text = [
      "type" => "text",
      "text" => $mes
    ];
    $post_data = [
      "replyToken" => $this->reply_token,
      "messages"   => [$response_format_text]
    ];

    $this->http($post_data,"message/reply");
  }

  public function http($postdata,$endpoint){
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, self::APIURL.$endpoint);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postdata));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json;charser=UTF-8',
      'Authorization: Bearer ' .$this->access_token
    ));
    $result = curl_exec($ch);
    curl_close($ch);
  }
}

  //初期
  $accessToken = "";
  $lineBot     = new LineBot($accessToken);
  
  //ユーザーからのメッセージ取得
  $message = $lineBot->parse_message(file_get_contents('php://input'));

  //処理
  if($message['type'] !== "text") {
    exit;
  }
  $text = $messsage['text'];
  $replyText = "わぁい{$text} あかり{$text}大好き";

  //投稿
  $lineBot->reply_text($replyText);

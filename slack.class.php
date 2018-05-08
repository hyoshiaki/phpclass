<?php
class SlackBot{
  public $channel    = '#general';
  public $username   = 'slackbot';
  public $icon_emoji = ':slack:';
  public $url        = '';

  public function __construct($url = ''){
    $this->set_url($url);
  }

  public function post($message){
    $postdata =  array(
      'payload' => json_encode(array(
        'channel'    => $this->channel,
        'username'   => $this->username,
        'icon_emoji' => $this->icon_emoji,
        'text'       => $message,
      )),
    ); 

    return $this->http($this->url,$postdata);
  }

 protected function http($url,$post_data=null){
    $ch  = curl_init();
    if(defined("CURL_CA_BUNDLE_PATH")) curl_setopt($ch, CURLOPT_CAINFO, CURL_CA_BUNDLE_PATH);

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_HEADER,1);

    if(isset($post_data)) {
      curl_setopt($ch, CURLOPT_POST,1);
      curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);
    }

    $result      = curl_exec($ch);
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $header      = substr($result, 0, $header_size);
    $result      = substr($result, $header_size);
    curl_close($ch);
    return array(
      'Header' => $header,
      'Result' => $result,
     );
  }

  public function set_url($url){$this->url = $url;}
  public function set_channel($channel){$this->channel = $channel;}
  public function set_username($username){$this->username = $username;}
  public function set_icon_emoji($icon_emoji){$this->icon_emoji = $icon_emoji;}
}

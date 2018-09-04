<?php
class MT{
 private $apipath;
 private $basicauthf = 0;
 private $username;
 private $password;
 private $clientid;

 function __construct($apipath,$username,$password,$clientid="hogehoge"){
   $this->apipath = $apipath;
   $this->username = $username;
   $this->password = $password;
   $this->clientid = $clientid;
 }

 function setBasicAuthentication($userid,$pass){
   $this->basicauth  = "{$userid}:{$pass}";
   $this->basicauthf = 1;
 }

 function unsetBasicAuthentication(){
   $this->basicauth  = NULL;
   $this->basicauthf = 0;
 }

 function Authentication(){
   $endpoint = "/v3/authentication";
   $postdata = array(
   'username' => $this->username,
   'password' => $this->password,
   'clientId' => $this->clientid,
   );

   $headers = array(
   'Content-Type: application/x-www-form-urlencoded',
   "Connection:keep-alive",
   );

   $response = $this->getAPIData($endpoint,$postdata,$headers);

   if($response->accessToken){
     $this->accessToken = $response->accessToken;
   }elseif($response->error->code){
     throw new Exception($response->error->code,$response->error->message);
   }else{
     throw new Exception("Authentication Error.");
   }
 }

 function postEntry($siteid,$postdata){
   $endpoint =  "/v3/sites/{$siteid}/entries";

   $headers = array(
   'X-MT-Authorization: MTAuth accessToken=' . $this->accessToken,
   );

   $response = $this->getAPIData($endpoint,$postdata,$headers);

   if(!$response->id){
     throw new Exception("Entry Posted Error.");
   }
   return $response->id;
 }

 function getAPIData($endpoint,$postdata=array(),$headers=array()){
  $api = $this->apipath . $endpoint;

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,$api);
  curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  if($this->basicauthf){
    curl_setopt($ch, CURLOPT_USERPWD,$this->basicauth);
  }
  if(!empty($postdata)){
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postdata));
  }
  if(!empty($headers)){
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  }

  $rst   = curl_exec($ch);
  $info  = curl_getinfo($ch);
  $errno = curl_errno($ch);
  $error = curl_error($ch);
  curl_close($ch);

  if(CURLE_OK !== $errno){
    throw new Exception($error, $errno);
  }

  $response = json_decode($rst);
  return $response;
 }
}
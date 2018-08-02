<?php
function send_mail_attache($from,$to,$subject,$mes,$filepath){
  $boundary            = "__BOUNDARY__";
  $additional_headers  = "Content-Type: multipart/mixed;boundary=\"".$boundary."\"\n";
  $additional_headers .= "From: ".$from;

  $message  = "--".$boundary."\n";
  $message .= "Content-Type: text/plain; charset=\"ISO-2022-JP\"\n\n";
  $message .= $mes."\n";
  $message .= "--".$boundary."\n";
  $message .= "Content-Type: ".mime_content_type($filepath)."; name=\"".basename($filepath)."\"\n";
  $message .= "Content-Disposition: attachment; filename=\"".basename($filepath)."\"\n";
  $message .= "Content-Transfer-Encoding: base64\n";
  $message .= "\n";
  $message .= chunk_split(base64_encode(file_get_contents($filepath)))."\n";
  $message .= "--".$boundary."--";

  mb_send_mail($to,$subject,$message,$additional_headers);
}

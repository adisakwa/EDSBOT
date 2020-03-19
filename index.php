<?php

   $accessToken ="7CpagKQQPjselOrSh9YNG8aHKs0khbDpaNjVLiwav4Gv6gr2kophRKEPGYBDNd7Rhv/m0oI5O+MQ7gbzVM3MxBoUgNXSKw1BmxMraXYEaxD/ayIVVT8KFYSLUGEMqhOhH0mRMG0ToTov0J789ibCfwdB04t89/1O/w1cDnyilFU=";//copy ข้อความ Channel access token ตอนที่ตั้งค่า
   $content = file_get_contents('php://input');
   $content = file_get_contents('php://input');
   $arrayJson = json_decode($content, true);
   $arrayHeader = array();
   $arrayHeader[] = "Content-Type: application/json";
   $arrayHeader[] = "Authorization: Bearer {$accessToken}";
   echo $content;
    $dsn = "pgsql:"
    . "host=ec2-52-200-119-0.compute-1.amazonaws.com;"
    . "dbname=d5tcp5utb7os37;"
    . "user=rxacpwhxykxmti;"
    . "port=5432;"
    . "sslmode=require;"
    . "password=e95786afc8c5344a92d4de562e05d28c40dcc9af7080f08b70bd9422236d49a6";

    $conn = new PDO($dsn);
   //รับข้อความจากผู้ใช้
   $message = $arrayJson['events'][0]['message']['text'];
   //รับ id ของผู้ใช้
   $id = $arrayJson['events'][0]['source']['userId'];
   $getLink = $conn->query("SELECT * FROM customer WHERE  upper(userid) = upper('$message') or  upper(customerid) = upper('$message') or upper(chain) = upper('$message') ");//upper ใช้ค้นหาได้ทั้งตัวเล็กและตัวใหญ่
  
    $getuserNum = $getLink->rowCount();
 
     if($getuserNum == "0"){
		 $arrayPostData['to'] = $id;
		 $arrayPostData['messages'][0]['type'] = "text";
		  $arrayPostData['messages'][0]['text'] = "site $message ไม่มี link EDS";
		   pushMsg($arrayHeader,$arrayPostData);
		} else  $i =1 ;{while  ( $row = $getLink->fetch(PDO::FETCH_ASSOC)){ 
		      $Name = $row['name'];
              $Surname = $row['surname'];
              $CustomerID = $row['userid'];
	          $link = $row['customerid'];
			  $reply['replyToken'] = $arrayJson['events'][0]['replyToken'];
			  $reply['messages'][0]['type'] = "text";
			  $reply['messages'][0]['text'] = "คุณค้นหาด้วย $message มี link EDS  $getuserNum link ";
				   replyMsg($arrayHeader,$reply);
              $arrayPostData['to'] = $id;
              $arrayPostData['messages'][0]['type'] = "text";
              $arrayPostData['messages'][0]['text'] = "site $CustomerID  link  ที่ $i   $Name Lat,Long $Surname  non $link  ";
			   $i++;
			       pushMsg($arrayHeader,$arrayPostData); }
			 
}
 
   function pushMsg($arrayHeader,$arrayPostData){
      $strUrl = "https://api.line.me/v2/bot/message/push";
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL,$strUrl);
      curl_setopt($ch, CURLOPT_HEADER, false);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $arrayHeader);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrayPostData));
      curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      $result = curl_exec($ch);
      curl_close ($ch);
   }


  function replyMsg($arrayHeader,$reply){
      $strUrl = "https://api.line.me/v2/bot/message/reply";
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL,$strUrl);
      curl_setopt($ch, CURLOPT_HEADER, false);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $arrayHeader);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($reply));
      curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      $result = curl_exec($ch);
      curl_close ($ch);
   }
exit;
?>

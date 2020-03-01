<?php
  $LINEData = file_get_contents('php://input');
  $jsonData = json_decode($LINEData,true);

  $replyToken = $jsonData["events"][0]["replyToken"];
  $userID = $jsonData["events"][0]["source"]["userId"];
  //$userID = "Ucd8fefa3e6c4116ec020ae2abb751a";
  $text = $jsonData["events"][0]["message"]["text"];
  $timestamp = $jsonData["events"][0]["timestamp"];


$dsn = "pgsql:"
    . "host=ec2-52-200-119-0.compute-1.amazonaws.com;"
    . "dbname=d5tcp5utb7os37;"
    . "user=rxacpwhxykxmti;"
    . "port=5432;"
    . "sslmode=require;"
    . "password=e95786afc8c5344a92d4de562e05d28c40dcc9af7080f08b70bd9422236d49a6";
   $mysql  = new PDO($dsn);
  

 


 $mysql->query("INSERT INTO `log`(`userid`, `text`, `timestamp`) VALUES ('$userID','$text','$timestamp')");

  $getUser = $mysql->query("SELECT * FROM `customer` WHERE `userid`='$userID'");
 
  $getuserNum = $getUser->num_rows;
  
  $replyText["type"] = "text";
  if ($getuserNum == "0"){
    $replyText["text"] = "สวัสดีคับบบบ";
  } else {
	if(is_array($$getUser)){  
  foreach ($mysql->query($getUser) as $row){
      $Name = $row['name'];
      $Surname = $row['surname'];
      $CustomerID = $row['customerid'];   
}

	}
    $replyText["text"] = "สวัสดีคุณ $Name $Surname (#$CustomerID)";
  }

  $lineData['URL'] = "https://api.line.me/v2/bot/message/reply";
  $lineData['AccessToken'] = "7CpagKQQPjselOrSh9YNG8aHKs0khbDpaNjVLiwav4Gv6gr2kophRKEPGYBDNd7Rhv/m0oI5O+MQ7gbzVM3MxBoUgNXSKw1BmxMraXYEaxD/ayIVVT8KFYSLUGEMqhOhH0mRMG0ToTov0J789ibCfwdB04t89/1O/w1cDnyilFU=";

  $replyJson["replyToken"] = $replyToken;
  $replyJson["messages"][0] = $replyText;

  $encodeJson = json_encode($replyJson);

  $results = sendMessage($encodeJson,$lineData);
  echo $results;
  http_response_code(200);

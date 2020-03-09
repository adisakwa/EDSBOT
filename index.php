<?php
  $LINEData = file_get_contents('php://input');
  $jsonData = json_decode($LINEData,true);

  $replyToken = $jsonData["events"][0]["replyToken"];
  $userID = $jsonData["events"][0]["source"]["userId"];
  
  $text = $jsonData["events"][0]["message"]["text"];
  $timestamp = $jsonData["events"][0]["timestamp"];
  function sendMessage($replyJson, $sendInfo){
          $ch = curl_init($sendInfo["URL"]);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLINFO_HEADER_OUT, true);
          curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch, CURLOPT_HTTPHEADER, array(
              'Content-Type: application/json',
              'Authorization: Bearer ' . $sendInfo["AccessToken"])
              );
          curl_setopt($ch, CURLOPT_POSTFIELDS, $replyJson);
          $result = curl_exec($ch);
          curl_close($ch);
    return $result;
  }
    $dsn = "pgsql:"
    . "host=ec2-52-200-119-0.compute-1.amazonaws.com;"
    . "dbname=d5tcp5utb7os37;"
    . "user=rxacpwhxykxmti;"
    . "port=5432;"
    . "sslmode=require;"
    . "password=e95786afc8c5344a92d4de562e05d28c40dcc9af7080f08b70bd9422236d49a6";

    $conn = new PDO($dsn);
    //$conn->query("INSERT INTO log (userid, text, timestamp "  );
	
  $getUser = $conn->query("SELECT * FROM Customer WHERE upper(UserID) = upper('$text') ");//upper ใช้ค้นหาได้ทั้งตัวเล็กและตัวใหญ่
  
  $getuserNum = $getUser->rowCount();
  
  $replyText["type"] = "text";
  if ($getuserNum == "0"){
    $replyText["text"] = "site code นี้ไม่มี link EDS";
  } else {
while ($row = $getUser->fetch(PDO::FETCH_ASSOC)) {
      $Name = $row['name'];
      $Surname = $row['surname'];
      $CustomerID = $row['userid'];
	  $link = $row['customerid'];
	 
	
	
}
	
$replyText["text"] = "Site $CustomerID มี link EDS  $Name  $Surname $link";	
	
}
 $lineData['URL'] = "https://api.line.me/v2/bot/message/reply";
  $lineData['AccessToken'] = "7CpagKQQPjselOrSh9YNG8aHKs0khbDpaNjVLiwav4Gv6gr2kophRKEPGYBDNd7Rhv/m0oI5O+MQ7gbzVM3MxBoUgNXSKw1BmxMraXYEaxD/ayIVVT8KFYSLUGEMqhOhH0mRMG0ToTov0J789ibCfwdB04t89/1O/w1cDnyilFU=";

  $replyJson["replyToken"] = $replyToken;
  $replyJson["messages"][0] = $replyText;

  $encodeJson = json_encode($replyJson);

  $results = sendMessage($encodeJson,$lineData);
  echo $results;
 
  http_response_code(200);
 
?>



<?php
require 'encrypt.php';
require 'kakaoApi.php'; 

$host = '';
$user = '';
$pw = '';
$dbName = '';
$mysqli = new mysqli($host, $user, $pw, $dbName);

if ($mysqli) {
    echo "MySQL 연결 성공!<br/>";
    $decrypt = '';

    $key = "aaaaaaaaaaaaaaaa"; 
    $find = $_GET['uid'];
    $encryptedUID  = str_replace(' ', '+', $find);

    if (isset($encryptedUID)) {
        $decrypt = decryptMessage($encryptedUID, $key);
    }

    echo "uid = $decrypt";


    $query = "SELECT count FROM users WHERE uid = '$decrypt'";
    $result = mysqli_query($mysqli, $query);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $count = $row['count'];
        // 결과가 존재하는 경우, count 값을 1 증가시킴
        $count--;

        // UPDATE 문을 사용하여 count 값을 증가시킬 수 있음
        $updateQuery = "UPDATE users SET count = $count WHERE uid = '$decrypt'";
        mysqli_query($mysqli, $updateQuery);

        $KakaoAPIService = new KakaoAPIService();
        $data = 'template_object={
            "object_type": "text",
            "text": "count: ' . $count . ' uid: ' . $decrypt . '",
            "link": {
                "web_url": "https://developers.kakao.com",
                "mobile_web_url": "https://developers.kakao.com"
            },
            "button_title": "바로 확인"
        }';
        
        $KakaoAPIService->sendTalk($data);
        echo "success";
    } else {
        echo "We don't have this record";
    }
} else {
    echo "Connect Mysql Fail";
}

mysqli_close($mysqli);


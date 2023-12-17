<?php
require 'encrypt.php'; 
header("Content-Type: text/html;charset=UTF-8");
 
$host = '';
$user = '';
$pw = '';
$dbName = '';
$mysqli = new mysqli($host, $user, $pw, $dbName);
 
    if($mysqli){
	echo "MySQL successfully connected!<br/>";
	$decrypt='';
	

	$key = "aaaaaaaaaaaaaaaa"; 
	$find = $_GET['uid'];
	$encryptedUID  = str_replace(' ', '+', $find );
	$count = 100000;
	if (isset($encryptedUID)) {
		$decrypt = decryptMessage($encryptedUID, $key);
	}
	echo "uid = $decrypt";
	
	$checkQuery = "SELECT * FROM users WHERE uid='$decrypt'";
    $result = mysqli_query($mysqli, $checkQuery);

    if (mysqli_num_rows($result) > 0) {
        // UID already exists
        echo "User already exists!";
    } else {
        // UID doesn't exist, insert into the database
        $insertQuery = "INSERT INTO users (uid, count) VALUES ('$decrypt','$count')";
        mysqli_query($mysqli, $insertQuery);
        echo "User added successfully!";
    }
}
    else{
	echo "MySQL could not be connected";
    }

mysqli_close($mysqli);

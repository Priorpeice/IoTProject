<?php
// 미리 정의된 로그인 자격 증명
$expectedLogin = 'admin';
$expectedPassword = '1234';

// 폼에서 값 가져오기
$login = $_POST['login'];
$password = $_POST['password'];

// 로그인 자격 증명 확인
if ($login === $expectedLogin && $password === $expectedPassword) {
    // 성공 메시지 표시
    echo "로그인 성공!";

    // 데이터베이스 연결 정보
    $host = '';
    $user = '';
    $pw = '';
    $dbName = '';

    // 데이터베이스 연결
    $mysqli = new mysqli($host, $user, $pw, $dbName);

    // 연결 확인
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // 사용자 정보를 가져오기 위한 쿼리
    $sql = "SELECT * FROM users";

    // 쿼리 실행 및 예외 처리
    try {
        $result = $mysqli->query($sql);

        // 쿼리 성공 여부 확인
        if ($result) {
            if ($result->num_rows > 0) {
                echo "<br><br><strong>모든 사용자 정보:</strong><br>";
                while ($row = $result->fetch_assoc()) {
                    echo "사용자 ID: " . $row["uid"] . " - 금액: " . $row["count"] . "<br>";
                }
            } else {
                // 오류 메시지 표시
                echo "데이터베이스에 사용자가 없습니다.";
            }
        } else {
            throw new Exception("쿼리 실행 실패: " . $mysqli->error);
        }
    } catch (Exception $e) {
        // 예외 처리
        echo "예외 발생: " . $e->getMessage();
    }

    // 연결 종료
    $mysqli->close();
} else {
   
    echo "로그인 실패. 아이디 또는 비밀번호를 확인하세요.";
}

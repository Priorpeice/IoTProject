
<?php
require('kakaoMain.php');
require('Kakaot.php');

class KakaoAPIService extends KakaoService
{
    public function __construct($return_type="")
    {
        parent::__construct($return_type);
    }
    
    public function sendTalk($data)
    {
        $refreshToken = ""; 
        $result = refreshAccessToken($refreshToken);
        $accessTokenData = json_decode($result['response'], true);
        $accessToken = $accessTokenData['access_token'];
        $callUrl = "https://kapi.kakao.com/v2/api/talk/memo/default/send";
        $headers = array('Content-type:application/x-www-form-urlencoded;charset=utf-8');
        $headers[] = "Authorization: Bearer " . $accessToken;
        return $this->excuteCurl($callUrl, "POST", $headers, $data);
    }    
}

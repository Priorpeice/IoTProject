<?php
function refreshAccessToken($refreshToken)
    {
        $url = 'https://kauth.kakao.com/oauth/token';
        $params = array(
            'grant_type' => 'refresh_token',
            'client_id' => "",
            'client_secret' => "",
            'refresh_token' => $refreshToken,
        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        return array('response' => $response, 'status_code' => $status_code);
    }
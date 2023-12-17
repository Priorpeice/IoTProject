<?php
class KakaoService 
{
    public $JAVASCRIPT_KEY;
    protected $REST_API_KEY;
    protected $ADMIN_KEY;
    protected $CLIENT_SECRET;
    protected $REDIRECT_URI;
    protected $LOGOUT_REDIRECT_URI;
    protected $RETURN_TYPE;

    public function __construct($return_type)
    {  
        $this->JAVASCRIPT_KEY = ""; 
        $this->REST_API_KEY   = "";
        $this->ADMIN_KEY      = "";
        $this->CLIENT_SECRET  = ""; 
        $this->RETURN_TYPE  = $return_type;

        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://");
        $this->REDIRECT_URI          = urlencode($protocol . $_SERVER['HTTP_HOST'] . "/localhost");  
        $this->LOGOUT_REDIRECT_URI   = urlencode($protocol . $_SERVER['HTTP_HOST'] . "/localhost"); 
    }

    protected function excuteCurl($callUrl, $method, $headers = array(), $data = array())
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $callUrl);
        if ($method == "POST") {
            curl_setopt($ch, CURLOPT_POST, true);
        } else {
            curl_setopt($ch, CURLOPT_POST, false);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return ['response' => json_decode($response), 'status_code' => $status_code];
    }
}

<?php
namespace TyperEJ\LineLogin;

class Curl
{
    protected $token;

    public function __construct($token ='')
    {
        $this->token = $token;
    }

    public function get($url)
    {
        return $this->request($url);
    }

    public function post($url,$params)
    {
        return $this->request($url,$params,true);
    }

    private function request($url,$params = [],$isPost = false)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 28);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);

        if($this->token){
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , "Authorization: Bearer $this->token"));
        }

        if($isPost){
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        }

        $response = curl_exec($ch);

        if($response === false){
            throw new \Exception('Curl error: ' . curl_error($ch));
        }

        curl_close($ch);

        return $response;
    }
}
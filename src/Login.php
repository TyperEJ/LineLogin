<?php
namespace TyperEJ\LineLogin;

class Login {
    protected $clientId;
    protected $clientSecret;
    protected $curl;

    public function __construct($clientId,$clientSecret = null)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->curl = new Curl();
    }

    public function requestToken($code,$redirectUri = null)
    {
        if(!$this->clientSecret)
        {
            throw new \UnexpectedValueException('ClientSecret is required');
        }

        $params = [
            'grant_type' => 'authorization_code',
            'redirect_uri' => $redirectUri ?? $this->getCurrentUrl(),
            'code' => $code,
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
        ];

        $response = $this->curl->post(UrlEnum::TOKEN_URL, $params);

        try {

            return new UserInfo($response);

        } catch (\UnexpectedValueException $e) {

            $error = $e->getMessage() . "::" . json_encode($params);
            throw new \UnexpectedValueException($error);
        }
    }

    private function getCurrentUrl()
    {
        $http = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        $uri = explode('?', $_SERVER['REQUEST_URI'], 2)[0];

        return $http. "://$_SERVER[HTTP_HOST]$uri";
    }

    public function generateLoginUrl($args = ['state' => 'default'])
    {
        $params = [
            'response_type' => 'code',
            'client_id' => $this->clientId,
            'redirect_uri' => $this->getCurrentUrl(),
            'scope' => 'openid profile'
        ];

        $params = array_merge($params,$args);

        return UrlEnum::AUTH_URL.'?'.http_build_query($params,'','&',PHP_QUERY_RFC3986);
    }
}

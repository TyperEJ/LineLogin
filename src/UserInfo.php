<?php

namespace TyperEJ\LineLogin;

class UserInfo
{
    private $accessToken;
    protected $info;

    public function __construct($response)
    {
        if (isset(json_decode($response)->error)) {
            $errorMessage = json_decode($response)->error_description ?? 'Info Response Error';
            throw new \UnexpectedValueException($errorMessage);
        }

        $this->initInfo(json_decode($response));
    }

    private function initInfo($response)
    {
        $parser = new Parser($response->id_token);
        $this->info = $parser->getPayload()->parse();

        $this->accessToken = $response->access_token;
    }

    public function __get($property)
    {
        if(isset($this->info[$property]))
        {
            return $this->info[$property];
        }

        throw new  \Exception('No such information');
    }

    public function getFriendship()
    {
        $curl = new Curl($this->accessToken);

        $response = $curl->get(UrlEnum::STATUS_URL);

        if (!isset(json_decode($response)->friendFlag)) {

            $error = 'Friendship Error::' . json_encode($response);
            throw new \Exception($error);

        }

        return json_decode($response)->friendFlag;
    }

}
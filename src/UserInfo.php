<?php

namespace TyperEJ\LineLogin;

class UserInfo
{
    public $uid;
    public $name;
    public $picture;
    private $accessToken;

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
        $info = $parser->getPayload()->parse();

        $this->uid = $info['sub'];
        $this->name = $info['name'];
        $this->picture = $info['picture'];
        $this->accessToken = $response->access_token;
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
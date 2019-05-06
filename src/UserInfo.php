<?php
namespace TyperEJ\LineLogin;

class UserInfo
{
    public $uid;
    public $name;
    public $picture;
    public $friendship;

    public function __construct($response)
    {
        if(isset(json_decode($response)->error)){
            $errorMessage = json_decode($response)->error_description ?? 'Info Response Error';
            throw new \Exception($errorMessage);
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
        $this->setFriendship($response);
    }

    private function setFriendship($response)
    {
        $curl = new Curl($response->access_token);

        $response = $curl->get(UrlEnum::STATUS_URL);

        if(!isset(json_decode($response)->friendFlag)){
            throw new \Exception('Friendship Error');
        }

        $this->friendship = json_decode($response)->friendFlag;
    }

}
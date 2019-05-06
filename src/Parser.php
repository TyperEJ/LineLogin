<?php
namespace TyperEJ\LineLogin;

class Parser
{
    protected $decoder;
    protected $payload;
    protected $token;

    public function __construct($token)
    {
        $this->decoder = new Decoder();
        $this->token = $token;
    }

    public function parse()
    {
        return (array) $this->decoder->jsonDecode($this->decoder->base64UrlDecode($this->payload));
    }

    public function getPayload()
    {
        $data = explode('.', $this->token);

        if (count($data) != 3) {
            throw new \InvalidArgumentException('The JWT string must have two dots');
        }

        $this->payload = $data[1];

        return $this;
    }
}
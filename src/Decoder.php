<?php
namespace TyperEJ\LineLogin;

class Decoder
{
    public function jsonDecode($json)
    {
        $data = json_decode($json);

        return $data;
    }
    public function base64UrlDecode($data)
    {
        if ($remainder = strlen($data) % 4) {
            $data .= str_repeat('=', 4 - $remainder);
        }

        return base64_decode(strtr($data, '-_', '+/'));
    }
}
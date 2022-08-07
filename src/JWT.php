<?php

namespace Devmatheusguerra\JWT;

use FFI\Exception;
use stdClass;

class JWT 
{
    private $secret_key;
    private $algorithm;

    const FORBIDDEN = 403;
    const UNAUTHORIZED = 401;
    const BAD_REQUEST = 400;
    const SUCCESS = 200;
    const CREATED = 201;

    function __construct()
    {
        try{
            $this->secret_key = SECRET_KEY_JWT;
        }catch(Exception $e){
            throw new Exception("Secret key not found");
        }

        try{
            $this->algorithm = ALGORITHM_JWT;
        }catch(Exception $e){
            throw new Exception("Algorithm not found");
        }
    }
    
    function generate(stdClass|null $data = null): string
    {   
        $header = $this->getHeader();
        $payload = $this->getPayload($data);
        switch($this->algorithm){
            case 'HS256':
                $signature = hash_hmac('sha256', $header . '.' . $payload, $this->secret_key, false);
                break;
            case 'HS384':
                $signature = hash_hmac('sha384', $header . '.' . $payload, $this->secret_key, false);
                break;
            case 'HS512':
                $signature = hash_hmac('sha512', $header . '.' . $payload, $this->secret_key, false);
                break;
            default:
                throw new Exception('Algorithm not supported');
        }
        
        return "$header.$payload.$signature";

    }



    function verify(string $token): bool
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return false;
        }
        $header = $parts[0];
        $payload = $parts[1];
        $signature = $parts[2];
        $data = "$header.$payload";
        switch($this->algorithm){
            case 'HS256':
                $hash = hash_hmac('sha256', $data, $this->secret_key, false);
                break;
            case 'HS384':
                $hash = hash_hmac('sha384', $data, $this->secret_key, false);
                break;
            case 'HS512':
                $hash = hash_hmac('sha512', $data, $this->secret_key, false);
                break;
            default:
                throw new Exception('Algorithm not supported');
        }
        return $hash === $signature;
    }

    public function getClaims(string $token): stdClass
    {
        $parts = explode('.', $token);
        $payload = $parts[1];
        $claims = json_decode(base64_decode($payload));
        return $claims;
    }


    // Private functions
    private function getHeader(): string
    {
        $header = new stdClass();
        $header->alg = $this->algorithm;
        $header->typ = 'JWT';
        $base64UrlHeader = base64_encode(json_encode($header));
        return $base64UrlHeader;
    }

    private function getPayload(stdClass|null $data = null): string
    {
        $payload = new stdClass();
        $payload->iss = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $payload->iat = time();
        $payload->exp = time() + (60 * 60 * 24);

        if($data !== null){
            $payload = (object) array_merge((array)$payload, (array)$data);
        }

        $base64UrlPayload = base64_encode(json_encode($payload));
        return $base64UrlPayload;
    }
}
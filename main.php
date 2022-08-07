<?php
require 'vendor/autoload.php';

use Devmatheusguerra\JWT\JWT;

$jwt = new JWT();

$data = new stdClass();
$data->name = 'Devmatheusguerra';
$data->email = 'teste@gmail.com';

$token = $jwt->generate();

var_dump($token);

var_dump($jwt->verify($token));






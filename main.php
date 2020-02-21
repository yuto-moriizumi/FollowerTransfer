<?php
session_start();

require_once './secret.php';
require_once './autoload.php';

use Abraham\TwitterOAuth\TwitterOAuth;

//セッションに入れておいたさっきの配列
$access_token = $_SESSION['access_token'];

//OAuthトークンとシークレットも使って TwitterOAuth をインスタンス化
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

$method=$_SESSION['method'];

echo 'hi';
echo $method;


echo $hell;
/*
$tweet = "てすとのつぶやきです。".rand(0,99);
$result = $connection->post("statuses/update",["status"=>$tweet]);

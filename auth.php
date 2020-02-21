<?php
session_start();
require_once './common.php';
require_once './autoload.php';

use Abraham\TwitterOAuth\TwitterOAuth;

//TwitterOAuth をインスタンス化 
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
//コールバックURLセット 
$request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => $_SERVER["REQUEST_URI"].'../callback.php'));

//callback.phpで使うのでセッションに入れる
$_SESSION['oauth_token'] = $request_token['oauth_token'];
$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

//Twitter.com 上の認証画面のURLを取得
$url = $connection->url('oauth/authenticate', array('oauth_token' => $request_token['oauth_token']));
//oauth/authenticateで二回目以降の認証画面がスキップされます。アカウント変更等の為毎回認証画面を出したい場合はoauth/authorizeに変更してください

//Twitter.com の認証画面へリダイレクト
header( 'location: '. $url );
?>
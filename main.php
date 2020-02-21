<?php
session_start();

require_once './secret.php';
require_once './autoload.php';

use Abraham\TwitterOAuth\TwitterOAuth;

//セッションに入れておいたさっきの配列
$access_token = $_SESSION['access_token'];

//OAuthトークンとシークレットも使って TwitterOAuth をインスタンス化
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

$myAccountName=$connection->get('account/settings',[])->screen_name;


echo '<h1>移行後：'.$myAccountName.' のフォロワーID＆フォローID一覧</h1>';
$nowFriends=getFollowAndFollowers($connection,$myAccountName);
echo '<p>ユニークな関係：'.count($nowFriends).'</p>';
echo '<div style="display:flex; flex-wrap:wrap;">';
foreach ($nowFriends as $user){
    echo '<p style="margin:0px 5px 0px">'.$user.'</p>';
}
echo '</div>';

$oldAccount=$_SESSION['oldAccount'];
echo '<hr><h1>移行前：'.$oldAccount.' のフォロワーID＆フォローID一覧</h1>';
$oldFriends=getFollowAndFollowers($connection,$oldAccount);
echo '<p>ユニークな関係：'.count($oldFriends).'</p>';
echo '<div style="display:flex; flex-wrap:wrap;">';
foreach ($oldFriends as $user){
    echo '<p style="margin:0px 5px 0px">'.$user.'</p>';
}
echo '</div>';

echo '<hr><h2>差分</h2>';
$diff=array_diff($oldFriends,$nowFriends);
echo '<p>ユニークな関係：'.count($diff).'</p>';
echo '<div style="display:flex; flex-wrap:wrap;">';
if(count($diff)===0){
    echo '差分はありません';
    die;
}
foreach ($diff as $user){
    echo '<p style="margin:0px 5px 0px">'.$user.'</p>';
}
echo '</div>';

$ans=0;
foreach ($diff as $user){
    $result=$connection->post('friendships/create',[
        'user_id'=>$user,
    ]);
    if(isset($result->errors)){
        echo '<p>フォロー上限に達したためフォローできません</p>';
        break;
    }
    $ans++;
}
echo '<p>処理が終了しました。</p><p>フォローした数：'.$ans.'</p>';

function getFollowAndFollowers($connection,$name){
    $followers=$connection->get(
        'followers/ids',
        [
            'screen_name'=>$name,
            'stringify_ids'=>true,
        ]
    )->ids;
    $follows=$connection->get(
        'friends/ids',
        [
            'screen_name'=>$name,
            'stringify_ids'=>true,
        ]
    )->ids;
    return array_unique(array_merge($followers, $followers));
}
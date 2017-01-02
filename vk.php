<?php
$appId = '5802954';
$apiSecret = 'SzQgFBY5MKeaPkwDtVu8';
$vkAccessToken = '';
$vk = new VK\VK($appId, $apiSecret);
$apiSettings = 'offline,wall,nohttps';//битовая маска на доступы
$vk->setApiVersion('5.60');
$urlAuthorize = $vk->getAuthorizeURL($apiSettings, 'http://socpost.local/vk-callback.php');
echo "<pre>";
if (isset($_SESSION['vk_access_token'])) {
    $vkAccessToken = $_SESSION['vk_access_token'];
    $vk = new VK\VK($appId, $apiSecret, $vkAccessToken['access_token']);
//    var_dump($vk->api('wall.post',['message'=>'testLALAAL']));
//    var_dump($vk->api('users.get',array('user_id'=>'376659758','fields'=>'photo_id,verified, sex, bdate, city, country, home_town, has_photo, photo_50, photo_100, photo_200_orig, photo_200, photo_400_orig, photo_max, photo_max_orig, online, lists, domain, has_mobile, contacts, site, education, universities, schools, status, last_seen, followers_count, common_count, occupation, nickname, relatives, relation, personal, connections, exports, wall_comments, activities, interests, music, movies, tv, books, games, about, quotes, can_post, can_see_all_posts, can_see_audio, can_write_private_message, can_send_friend_request, is_favorite, is_hidden_from_feed, timezone, screen_name, maiden_name, crop_photo, is_friend, friend_status, career, military, blacklisted, blacklisted_by_me')));
    echo "Auth? " . $vk->isAuth();
} else {
    //echo '<p><a href="' . $urlAuthorize . '">Log in with VK!</a></p>';
}
echo "</pre>";

/**
<!--    --><?php //if (!empty($vkAccessToken)): ?>
    <!--        <p>Logged in with VK</p>-->
    <!--        <p style='font-size:smaller;'>VK AccessToken: -->
<?php //echo implode(' | ', $vkAccessToken) ?><!--</p>-->
    <!--    --><?php //endif; ?>
 */
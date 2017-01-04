<!DOCTYPE html>
<html>
<head>
    <link rel="shortcut icon" type="image/png" href="favicon.ico"/>
</head>
<body>
<div>
    <?php if (isset($_SESSION['msgToUser'])): ?>
        <p><?php echo $_SESSION['msgToUser']; ?></p>
    <?php endif; ?>

</div>
<div>
    <?php if (!empty($twAccessToken)): ?>
        <p>Logged in with Twitter</p>
        <p style='font-size:smaller;'>TW AccessToken: <?php echo implode(' | ', $twAccessToken) ?></p>
    <?php else: ?>
        <p><a href="<?php echo $twLoginUrl; ?>">Log in with Twitter!</a></p>
    <?php endif; ?>

    <?php if (!empty($fbAccessToken)): ?>
        <p>Logged in with Facebook</p>
        <p style='font-size:smaller;'>FB AccessToken: <?php echo $fbAccessToken ?></p>
    <?php else: ?>
        <p><a href="<?php echo $fbLoginUrl; ?>">Log in with Facebook!</a></p>
    <?php endif; ?>
</div>
<div>
    <?php if (!empty($fbAccessToken)): ?>
        Post to Facebook:
        <form name="postFb" action="index/post" method="GET">
            <input type="text" name="text" value="TestText<?php echo time() ?>">
            <input type="hidden" name="net" value="facebook">
            <br>
            <input type="submit" value="PostFb">
        </form>
    <?php endif; ?>

    <?php if (!empty($twAccessToken)): ?>
        Post to Twitter:
        <form name="postTw" action="index/post" method="GET">
            <input type="text" name="text" value="TestText<?php echo time() ?>">
            <input type="hidden" name="net" value="twitter">
            <br>
            <input type="submit" value="PostTw">
        </form>
    <?php endif; ?>
</div>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <link rel="shortcut icon" type="image/png" href="favicon.ico"/>
</head>
<body>
<content>
    <div>
        <?php if (isset($_SESSION['msgToUser'])): ?>
            <?php foreach ($_SESSION['msgToUser'] as $key => $val): ?>
                <p><?php echo $key ?> | <?php echo $val ?></p>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div>
        <form name="postFb" action="index/post" method="GET">
            <label for="networks"><b>Check social networks for posting:</b></label><br>
            <?php if (!empty($fbAccessToken)): ?>
                <input type="checkbox" name="networks[]" id="networks" value="facebook">Facebook<br>
            <?php else: ?>
                <p><a href="<?php echo $fbLoginUrl; ?>">For posting to Facebook, please logging in.</a></p>
            <?php endif; ?>
            <?php if (!empty($twAccessToken)): ?>
                <input type="checkbox" name="networks[]" id="networks" value="twitter">Twitter<br>
            <?php else: ?>
                <p><a href="<?php echo $twLoginUrl; ?>">For posting to Twitter, please logging in.</a></p>
            <?php endif; ?>
            <textarea rows="5" cols="25" name="text" placeholder="Enter post text here"
                      required style="resize: none;">TestText<?php echo time() ?></textarea><br>
            <input type="submit" value="Post">
        </form>
    </div>
</content>
</body>
<footer>
    <div>
        <?php if (!empty($twAccessToken)): ?>
            <p>Logged in with Twitter</p>
            <p style='font-size:smaller;'>TW AccessToken: <?php echo implode(' | ', $twAccessToken) ?></p>
        <?php endif; ?>

        <?php if (!empty($fbAccessToken)): ?>
            <p>Logged in with Facebook</p>
            <p style='font-size:smaller;'>FB AccessToken: <?php echo $fbAccessToken ?></p>
        <?php endif; ?>
    </div>
</footer>
</html>
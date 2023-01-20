<?php
    $aweber->user->tokenSecret = $_COOKIE['requestTokenSecret'];
    $aweber->user->requestToken = sanitize_text_field($_GET['oauth_token']);
    $aweber->user->verifier = sanitize_text_field($_GET['oauth_verifier']);
    list($accessToken, $accessTokenSecret) = $aweber->getAccessToken();
    echo esc_html($accessToken);
    echo '<br>';
    echo esc_html($accessTokenSecret);
    ?>
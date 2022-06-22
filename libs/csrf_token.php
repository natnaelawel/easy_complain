<?php
// generate csrf token
function csrf_token()
{
    return md5(uniqid(rand(), TRUE));
}

// store token
function create_csrf_token()
{
    $token = csrf_token();
    $_SESSION["csrf_token"] =  $token;
    $_SESSION["csrf_token_time"] = time();
    return $token;
}

// destroy token
function destroy_csrf_token()
{
    $_SESSION["csrf_token"] = null;
    $_SESSION["csrf_token_time"] = null;
    return true;
}

// return html tag for csrf
function csrf_token_tag()
{
    destroy_csrf_token();
    $token = create_csrf_token();
    return "<input type=\"hidden\" name=\"csrf_token\" value=\"" . $token . "\"/>";
}

function csrf_token_is_valid()
{
    if (isset($_POST['csrf_token'])) {
        $user_token = $_POST['csrf_token'];
        $stored_token = $_SESSION['csrf_token'];
        return $user_token == $stored_token;
    } else {
        return false;
    }
}

function die_on_csrf_token_failure()
{
    if (!csrf_token_is_valid()) {
        die("CSRF token validation failed");
    }
}

function csrf_token_is_recent()
{
    $max_elapsed = 60 * 60 * 24;
    if (isset($_SESSION["csrf_token_time"])) {
        $stored_token = $_SESSION["csrf_token_time"];
        return ($stored_token + $max_elapsed) >= time();
    } else {
        destroy_csrf_token();
        return false;
    }
}

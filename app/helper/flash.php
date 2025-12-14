<?php
function setFlash($key, $message)
{
    $_SESSION['flash'][$key] = $message;
}

function getFlash($key)
{
    if (!isset($_SESSION['flash'][$key])) {
        return null;
    }

    $msg = $_SESSION['flash'][$key];
    unset($_SESSION['flash'][$key]); // remove após mostrar

    return $msg;
}

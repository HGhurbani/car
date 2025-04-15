<?php
// includes/functions.php

function redirect($url) {
    header("Location: $url");
    exit();
}

// دوال مساعدة أخرى (مثال):
function isLoggedIn() {
    return isset($_SESSION['user']);
}

function checkLogin() {
    if (!isLoggedIn()) {
        redirect('../login.php');
    }
}
?>

#!/usr/local/bin/php
<?php
session_start();
session_destroy();
header("Location: ../pages/Library_Home_Page.html");
exit;

# logout, redirect to home page

?>

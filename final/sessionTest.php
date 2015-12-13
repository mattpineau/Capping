<?php
    session_start();

    if (isset($_SESSION['currentFirstName'])) {
    	echo 'Welcome '. $_SESSION['currentFirstName'] . ' !';
    }
    else {
    	echo 'something is goofed';
    }
?>
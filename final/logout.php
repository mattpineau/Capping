<?php
    session_start();

    // unset all session variables
    session_unset();

    // destroy the session
    session_destroy();

    // redirect to index.html
    header('Location: /final/index.html');

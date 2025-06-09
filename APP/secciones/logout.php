<?php
session_start();
session_unset();     
session_destroy();    

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    exit;
}

header("Location: /CALENDARIO/APP/LOGIN");
exit;

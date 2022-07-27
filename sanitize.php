<?php

require_once 'chrono-crud.php';
$db = mysqli_connect('localhost', 'root', '', 'test');
echo sanitize($db, '<img src=x onclick=alert(1)>');
<?php

    include '../partials/functions.php';
    include '../Classes/Db.php';
    include '../Classes/User.php';

    $user = new User();
    $user->logout();

?>
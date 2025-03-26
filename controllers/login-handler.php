<?php
    include '../partials/functions.php';
    include '../Classes/Db.php';
    include '../Classes/User.php';

    $user = new User();
    
    if(isset($_POST['email']) && isset($_POST['password'])) {
        $user->login();
    }

?>
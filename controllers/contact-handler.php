<?php
    include '../partials/functions.php';
    include '../Classes/Db.php';
    include '../Classes/Contact.php';

    if(isset($_POST['email']) && isset($_POST['fname']) && isset($_POST['lname'])) {
        // var_dump($_POST);
        $contact = new Contact();
        $contact->create();  
    }
    if(isset($_GET['delete_contact'])) {
        $contact = new Contact();
        $contact->delete($_GET['delete_contact']);  
    }



?>
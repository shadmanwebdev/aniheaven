<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anime</title>

    <link rel="preload" href="./fonts/NunitoSans-Regular.woff" as="font" type="font/woff" crossorigin>
    <link rel="preload" href="./fonts/OpenSans-Regular.woff" as="font" type="font/woff" crossorigin>

    <link rel="preload" as="image" href="./img/anime-55-compressed.jpg?v=1">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="./css/contact-us.css?v=1.8">
    <link rel="stylesheet" href="./css/style.css?v=1.9">
    <link rel="stylesheet" href="./css/login.css?v=1.5">

    <script
    src="https://code.jquery.com/jquery-3.4.1.min.js"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
    crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/bf13f55ede.js" crossorigin="anonymous"></script>
</head>
<body>

<?php

    // var_dump($_SERVER['SERVER_NAME'], $_SERVER['REQUEST_URI']);
    include './partials/functions.php';
    include './Classes/Db.php';
    include './Classes/Contact.php';
    include './Classes/Post.php';
    $post = new Post();
?>
<?php
    include '../partials/functions.php';
    include '../Classes/Db.php';
    include '../Classes/Post.php';
    

    if(isset($_POST['create_post'])) {
        // var_dump($_POST);
        $post = new Post();
        $post->create();
    }
    if(isset($_POST['update_post']) && isset($_POST['post_id'])) {
        $post = new Post();
        $post->update($_POST['post_id']);
    }
    if(isset($_GET['delete_post'])) {
        $post = new Post();
        $post->delete($_GET['delete_post']);
    }
    if(isset($_GET['del']) && isset($_GET['pid'])) {
        $post = new Post();
        $post->del_thumbnail($_GET['del'], $_GET['pid']);
    }
?>
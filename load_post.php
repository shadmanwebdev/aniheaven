<?php

include './partials/functions.php';
// include './Classes/Server.php';
include './Classes/Db.php';
include './Classes/Post.php';


$row = $_POST['row'];
$rowperpage = 1;

$limit = $row.','.$rowperpage;
$post = new Post();
echo $post->load_more('id', 'DESC', $limit);


?>



<?php include './header.php'; ?>
<?php
    include './navigation-admin.php';
?>


<div class="admin_page-wrapper">
    <?php include './admin-sidebar.php'; ?>
    <div class="admin-content">
        <?php
            $post = new Post();
            echo $post->admin_posts('id', 'DESC');        
        ?>
    </div>
</div>




<?php include './footer.php'; ?>
<?php
    include './header.php';
?>
<?php
    include './navigation-admin.php';
?>




<div class="admin_page-wrapper">
    <?php include './admin-sidebar.php'; ?>
    <div class="admin-content">

        <div class='add-post-wrapper'>
            <?php
                $contact = new Contact();
                echo $contact->showContacts();
            ?>
        </div>

    </div>
</div>


<?php
    include './footer.php';
?>

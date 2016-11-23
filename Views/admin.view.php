<div class="col-md-3" id="sidebar" role="navigation" style="padding-top: 20px">
    <?php
    if (isset($_SESSION['user_type'])) {
        if ($_SESSION['user_type'] == 'admin') {
            include('/var/www/marking/Views/layouts/adminpanel.view.php');
        }
    }
    ?>
</div>



<div id="centerdisplay" style="padding-left: 100px; padding-top: 30px;">

    <h1>Admin Stuff</h1>

</div>
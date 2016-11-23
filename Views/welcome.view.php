<div class="col-md-3" id="sidebar" role="navigation" style="padding-top: 20px">
    <?php
    if (isset($_SESSION['user_type'])) {
        if ($_SESSION['user_type'] == 'student') {
            include('/var/www/marking/Views/layouts/studentpanel.view.php');
        }
    }
    ?>
</div>



<div id="centerdisplay" >

    <h1>Student Stuff</h1>

</div>

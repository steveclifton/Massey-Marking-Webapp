<!DOCTYPE html>
<html>
    <head>
        <title>Massey Marking System - <?= $pageTitle; ?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

        <script src="https://code.jquery.com/jquery-3.1.1.js" integrity="sha256-16cdPddA6VdVInumRGo6IbivbERE8p7CQR3HzTBuELA=" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script src="../Views/js/verify.js"></script>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    </head>
    <body>

        <div class="container-fluid">
            <?php include('/var/www/marking/Views/layouts/header.php'); ?>
        </div>

        <div class="page-container">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3" id="sidebar" role="navigation" style="padding-top: 20px">
                        <?php
                        if (isset($_SESSION['user_type'])) {
                            if ($_SESSION['user_type'] == 'admin') {
                                include('/var/www/marking/Views/layouts/adminpanel.view.php');
                            } else {
                                include('/var/www/marking/Views/layouts/studentpanel.view.php');
                            }
                        }
                        ?>
                    </div>


                    <div class="col-md-9" style="padding-top: 20px">
                        <?php include($viewName . '.php'); ?>
                    </div>

                </div>
            </div>
        </div>






<!--        --><?php //include('/var/www/marking/Views/layouts/footer.php')?>

    </body>
</html>

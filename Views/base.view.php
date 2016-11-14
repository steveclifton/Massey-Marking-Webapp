<!DOCTYPE html>
<html>
    <head>
        <title>Massey Marking App - <?= $pageTitle; ?></title>
        <meta charset="utf-8">
        <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" />

        <script src="/Assets/AjaxVerify.js"></script>

        <script src="https://code.jquery.com/jquery-3.1.1.js" integrity="sha256-16cdPddA6VdVInumRGo6IbivbERE8p7CQR3HzTBuELA=" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    </head>
    <body>
        <div class="container">
            <?php include('/var/www/marking/Views/layouts/header.php'); ?>
        </div>

        <div class="container">
            <?php include($viewName . '.php'); ?>
        </div>





<!--        --><?php //include('/var/www/marking/Views/layouts/footer.php')?>

    </body>
</html>

<h1>Assignment <?= $_GET['num']?> Mark so far : <?= $viewData['mark'] ?>/10</h1>
<hr>

<?php include ('Views/layouts/upload.view.php'); ?>

<h1>Feedback</h1>
<hr>

<?= $viewData['feedback'] ?>

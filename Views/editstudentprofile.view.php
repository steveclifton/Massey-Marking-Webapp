<h1 style="text-align: center">Current Semesters Students</h1>
<hr>

<?php if (isset($viewData['students'])) { ?>
<div class="container">
    <div class="table">
        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th style="text-align: center">Student ID</th>
                <th style="text-align: center">First Name</th>
                <th style="text-align: center">Last Name</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($viewData['students'] as $data) { ?>
                <tr>
                    <td class="col-md-1 col-sm-1" style="text-align: center"><a href="/editstudent?id=<?= $data['student_id'] ?>"><?= $data['student_id'] ?></a></td>
                    <td class="col-md-1 col-sm-1" style="text-align: center"> <?= $data['first_name'] ?> </td>
                    <td class="col-md-1 col-sm-1" style="text-align: center"> <?= $data['last_name'] ?> </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<?php } ?>
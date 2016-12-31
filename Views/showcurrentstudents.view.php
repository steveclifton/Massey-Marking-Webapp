<h1 style="text-align: center">Current Semesters Students and Results</h1>
<hr>

<div class="table">
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th style="text-align: center">Semester</th>
                <th style="text-align: center">Student ID</th>
                <th style="text-align: center">First Name</th>
                <th style="text-align: center">Last Name</th>
                <?php for ($i = 1; $i <= $viewData['number_of_assignments']; $i++) { ?>
                    <th style="text-align: center">A<?= $i ?> Mark</th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($viewData['data'] as $data) { ?>
                <tr>
                    <td class="col-md-1 col-sm-1" style="text-align: center"> <?= $data['semester'] ?> </td>
                    <td class="col-md-1 col-sm-1" style="text-align: center"> <?= $data['student_id'] ?> </td>
                    <td class="col-md-1 col-sm-1" style="text-align: center"> <?= $data['first_name'] ?> </td>
                    <td class="col-md-1 col-sm-1" style="text-align: center"> <?= $data['last_name'] ?> </td>
                    <?php for ($i = 1; $i <= $viewData['number_of_assignments']; $i++) { ?>
                        <td class="col-md-1 col-sm-1" style="text-align: center"> <?= isset($data['assignments'][$i]) ? $data['assignments'][$i] : '0' ?> </td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

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
                <th style="text-align: center">A1 Mark</th>
                <th style="text-align: center">A2 Mark</th>
                <th style="text-align: center">A3 Mark</th>
                <th style="text-align: center">A4 Mark</th>
                <th style="text-align: center">A5 Mark</th>
                <th style="text-align: center">A6 Mark</th>
                <th style="text-align: center">A7 Mark</th>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach ($viewData['data'] as $data) { ?>
            <tr>
                <td class="col-md-1 col-sm-1" style="text-align: center"> <?= $data['semester'] ?></td>
                <td class="col-md-1 col-sm-1" style="text-align: center"> <?= $data['student_id'] ?></td>
                <td class="col-md-1 col-sm-1" style="text-align: center"> <?= $data['first_name'] ?></td>
                <td class="col-md-1 col-sm-1" style="text-align: center"> <?= $data['last_name'] ?></td>
                <td class="col-md-1 col-sm-1" style="text-align: center"> <?php if (isset($data['1'])) { echo $data['1']; } else { echo '0';} ?></td>
                <td class="col-md-1 col-sm-1" style="text-align: center"> <?php if (isset($data['2'])) { echo $data['2']; } else { echo '0';} ?></td>
                <td class="col-md-1 col-sm-1" style="text-align: center"> <?php if (isset($data['3'])) { echo $data['3']; } else { echo '0';} ?></td>
                <td class="col-md-1 col-sm-1" style="text-align: center"> <?php if (isset($data['4'])) { echo $data['4']; } else { echo '0';} ?></td>
                <td class="col-md-1 col-sm-1" style="text-align: center"> <?php if (isset($data['5'])) { echo $data['5']; } else { echo '0';} ?></td>
                <td class="col-md-1 col-sm-1" style="text-align: center"> <?php if (isset($data['6'])) { echo $data['6']; } else { echo '0';} ?></td>
                <td class="col-md-1 col-sm-1" style="text-align: center"> <?php if (isset($data['7'])) { echo $data['7']; } else { echo '0';} ?></td>
            </tr>
        <?php } ?>
</tbody>
</table>
</div>

<h2>Welcome <?= $viewData['first_name'] ?></h2>
<h4>Overall Results - Semester <?= $viewData['semester'] ?></h4>
<hr><br>
<div class="table">
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th style="text-align: center">Assignment</th>
                <th style="text-align: center">Mark</th>
<!--                <th>Feedback</th>-->
            </tr>
        </thead>
        <tbody>
        <?php
            foreach ($viewData['marks'] as $data) { ?>
                <tr>
                    <td class="col-md-1 col-sm-1" style="text-align: center"> <?= $data['assignment_number'] ?> </td>
                    <td class="col-md-1 col-sm-1" style="text-align: center"> <?= $data['mark'] ?>/10</td>
    <!--                <td class="col-md-4 col-sm-2"> <pre>--><?//= $data ['feedback'] ?><!--</pre> </td>-->
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

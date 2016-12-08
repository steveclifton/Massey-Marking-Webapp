<h2>Welcome <?= $viewData['first_name'] ?></h2>
<h4>Current Assignment results</h4>
<div class="table-responsive">
    <div class="container">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Assignment</th>
                    <th>Mark (out of 10)</th>
                    <th>Feedback</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 0;
                    foreach ($viewData['marks'] as $data) { ?>
                        <tr>
                            <td> <?= $data['assignment_number'] ?> </td>
                            <td> <?= $data['mark'] ?> </td>
                            <td> None Yet </td>
                        </tr>
                    <?php } ?>
            </tbody>
        </table>
    </div>
</div>

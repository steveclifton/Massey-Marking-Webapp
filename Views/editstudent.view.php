<form class="form-horizontal" action="/editstudent" method="POST">
    <div>
        <legend class="">Student Profile</legend>
    </div>
    <fieldset>
        <div class="control-group">
            <label class="control-label">Database ID (non-edit)</label>
            <div class="controls">
                <input type="text" name="db_id" placeholder="" class="input-xlarge"
                       value="<?= $viewData['id'] ?>" readonly>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Student ID</label>
            <div class="controls">
                <input type="text" name="student_id" placeholder="" class="input-xlarge"
                       value="<?= $viewData['student_id'] ?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Semester</label>
            <div class="controls">
                <input type="text" name="semester" class="input-xlarge"
                       value="<?= $viewData['semester'] ?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">First Name</label>
            <div class="controls">
                <input type="text" name="first_name" placeholder="" class="input-xlarge"
                       value="<?= $viewData['first_name'] ?>" >
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Last Name</label>
            <div class="controls">
                <input type="text" name="last_name" placeholder="" class="input-xlarge"
                       value="<?= $viewData['last_name'] ?>" >
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">User Type</label>
            <div class="controls">
                <input type="text" name="user_type" placeholder="" class="input-xlarge"
                       value="<?= $viewData['user_type'] ?>" >
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Password (optional)</label>
            <div class="controls">
                <input type="text" name="password" placeholder="" class="input-xlarge"
                       value="" >
            </div>
        </div>
        <br>
        <div class="control-group">
            <div class="controls">
                <button class="btn btn-success" id="update">Update</button>
            </div>
        </div>
        <div>
            <h5 style="color: red;"><?php if(isset($viewData['updated'])) { echo "Updated Successfully";} ?></h5>
        </div>
        </fieldset>
</form>

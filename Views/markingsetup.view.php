<form class="form-horizontal" action="#" method="POST">
    <div>
        <legend class="">Marking System Setup</legend>
    </div>
    <fieldset>
        <div class="control-group">
            <label class="control-label">Semester</label>
            <div class="controls">
                <input type="text" id="anz_num" name="anz_num" class="input-xlarge"
                       value="<?= $viewData['semester'] ?>" required>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Number of Assignments</label>
            <div class="controls">
                <input type="text" id="first_name" name="first_name" placeholder="" class="input-xlarge"
                       value="<?= $viewData['numAss'] ?>" disabled>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Number of Tests</label>
            <div class="controls">
                <input type="text" id="last_name" name="last_name" placeholder="" class="input-xlarge"
                       value="<?= $viewData['numTests'] ?>" disabled>
            </div>
        </div>
        <br>
        <div class="control-group">
            <div class="controls">
                <button class="btn btn-success" id="update">Update</button>
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <h2>Here is where other configs will go, assignment specific stuff</h2>
            </div>
        </div>

    </fieldset>
</form>

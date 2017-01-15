<form class="form-horizontal" action="/markingsetup" method="POST">
    <div>
        <legend class="">Marking System Setup</legend>
    </div>
    <fieldset>
        <div class="control-group">
            <label class="control-label">Semester</label>
            <div class="controls">
                <input type="text" id="semester" name="semester" class="input-xlarge"
                       value="<?= $viewData['semester'] ?>" required>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">High Tolerance</label>
            <div class="controls">
                <input type="text" id="hightolerance" name="hightolerance" placeholder="" class="input-xlarge"
                       value="<?= $viewData['high_tolerance'] ?>" >
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Low Tolerance</label>
            <div class="controls">
                <input type="text" id="lowtolerance" name="lowtolerance" placeholder="" class="input-xlarge"
                       value="<?= $viewData['low_tolerance'] ?>" >
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Number of Assignments</label>
            <div class="controls">
                <input type="text" id="numAss" name="numAss" placeholder="" class="input-xlarge"
                       value="<?= $viewData['numAss'] ?>" readonly >
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Number of Tests</label>
            <div class="controls">
                <input type="text" id="numTests" name="numTests" placeholder="" class="input-xlarge"
                       value="<?= $viewData['numTests'] ?>" readonly >
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

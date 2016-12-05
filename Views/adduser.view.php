<form class="form-horizontal" action="/adduser" method="POST">
    <fieldset>
        <div id="legend">
            <legend class="">Add New Student</legend>
        </div>
        <div class="control-group">
            <label class="control-label"  for="student_id">Student ID</label>
            <div class="controls">
                <input type="text" id="student_id" name="student_id" placeholder="" class="input-xlarge" required>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="first_name">First Name</label>
            <div class="controls">
                <input type="text" id="first_name" name="first_name" placeholder="" class="input-xlarge" required>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="last_name">Last Name</label>
            <div class="controls">
                <input type="text" id="last_name" name="last_name" placeholder="" class="input-xlarge" required>
            </div>
        </div>

        <div class="control-group">
            <!-- Password-->
            <label class="control-label" for="password">Password</label>
            <div class="controls">
                <input type="text" id="password" name="password" placeholder="" class="input-xlarge" required>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"  for="username">Tick for Admin</label>
            <div class="controls">
                <input type="checkbox" id="user_type" name="user_type" class="input-xlarge">
            </div>
        </div>
        <br>

        <div class="control-group">
            <div class="controls">
                <button class="btn btn-success">Register</button>
            </div>
        </div>
    </fieldset>
</form>
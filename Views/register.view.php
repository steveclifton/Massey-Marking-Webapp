<div id="center">
    <p class="profile">Create New Profile</p>
    <form action="register" method="post" onsubmit="return check_data(this)">
        <input type="text" name="first_name" placeholder="First Name" required>
        <br>
        <input type="text" name="last_name" placeholder="Last Name" required>
        <br>
        <input type="email" name="email" id="emailfield" placeholder="Email" required>
        <br>
        <span id="email_errormessage"></span>
        <br>
        <input type="text" name="username" id="usernamefield" placeholder="Username" required>
        <br>
        <span id="username_errormessage"></span>
        <br>
        <input type="password" name="password" placeholder="Password" required>
        <br>
        <input type="password" name="password2" placeholder="Confirm Password" required>
        <br><br>
        <input name="submit" type="submit" value="Submit">
    </form>
</div>


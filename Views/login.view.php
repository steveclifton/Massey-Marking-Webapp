<link rel="stylesheet" type="text/css" href="../Views/css/login.css">

<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-login">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form class="validatedForm" action="login" method="post" style="display: block;">
                                <h2>LOGIN</h2>
                                <div class="form-group">
                                    <input type="text" name="student_id" id="student_id" tabindex="1"
                                           class="form-control"
                                           placeholder="Student ID" value="" required>
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" id="password" tabindex="2"
                                           class="form-control" placeholder="Password" required>
                                </div>
                                <div class="col-xs-6 form-group pull-right">
                                    <input type="submit" name="login-submit" id="login-submit" tabindex="4"
                                           class="form-control btn btn-login" value="Log In">
                                </div>
                                <div class="col-xs-6 form-group pull-left checkbox">
                                    <p id="noStudent" style="color: red;">
                                        <?php
                                            if(isset($_SESSION['failedLogin'])) {
                                                echo $_SESSION['failedLogin'];
                                                unset($_SESSION['failedLogin']);
                                            }
                                        ?>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



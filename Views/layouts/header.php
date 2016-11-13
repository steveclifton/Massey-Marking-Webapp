

<div class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/" style="background-color: lightgrey; font-family: Calibri">Massey Marking App</a>
        </div>
            <div class="navbar-collapse collapse" id="navbar-main">
                <ul class="nav navbar-nav">

                    <?php
                        if (isset($_SESSION['ID'])) {
                            echo "
                                <li><a href=\"#\">Results</a></li>
                                <li><a href=\"#\">Contact</a></li>
                            ";
                        }
                    ?>
                </ul>
                <form method="" action="#" class="navbar-form navbar-right">
                    <div class="form-group">
                        <input type="text" class="form-control" name="student_id" placeholder="Student ID" required>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="password" placeholder="Password" required>
                    </div>
                    <button type="submit" class="btn btn-default">Sign In</button>
                </form>
            </div>
    </div>
</div>


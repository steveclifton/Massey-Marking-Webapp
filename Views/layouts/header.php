<div class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/" style="background-color: lightgrey; font-family: Calibri">Assignment Marker</a>
        </div>
        <div class="navbar-collapse collapse" id="navbar-main">
            <ul class="nav navbar-nav navbar-right">
                <?php

//                if(isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'admin') {
//                    echo "<li><a href=\"admin\">Admin</a></li>";
//                }

                    if (isset($_SESSION['id'])) {
                        $studentName = $_SESSION['first_name'] . " " . $_SESSION['last_name'];
                        echo "
                              <li><a>$studentName</a></li>
                              <li><a href='logout'>Logout</a></li>
                              ";
                    }
                ?>
            </ul>
        </div>
    </div>
</div>

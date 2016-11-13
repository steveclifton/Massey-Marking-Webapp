<div id="wrapper1">
    <nav>
        <ul class="menu">
            <li><a href="browse">Browse</a></li>
            <li><a href="search">Search</a></li>
            <li><a href="logout">Logout</a></li>
        </ul>
        <div id="left" >
            <p class="name">Welcome <?= $viewData['first_name'] . " " . $viewData['last_name'] ?> !</p>
        </div>
    </nav>
</div>
<div class = "boxed">
    <nav>
        <p class="top" id="center"><u>Welcome to Toolshed Inc</u></p>
        <br>
        <p class="top">
            To begin using Toolshed Inc's website, please choose from the top two items.
            <br><br>
            <h2><u>Browse</u></h2>
            <p class="topnarrow">
                The Browse feature allows you to instantly filter product types to show only those that are relivant
                to your requirements
            </p>
            <br><br>
            <h2><u>Search</u></h2>
            <p class="topnarrow">
                The Search feature allows you to instantly filter products as you type.
            </p>
        </p>
    </nav>
</div>

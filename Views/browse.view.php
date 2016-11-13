<div id="wrapper1">
    <nav>
        <ul class="menu">
            <li><a href="browse">Browse</a></li>
            <li><a href="search">Search</a></li>
            <li><a href="logout">Logout</a></li>
        </ul>
        <div id="left">
            <p class="name">Welcome <?= $viewData['first_name'] . " " . $viewData['last_name'] ?> !</p>
        </div>
    </nav>
</div>
<div id="container">
    <div id="first">
        <div id="filters">
            <div class="filterblock">
                <input id="check1" type="checkbox" name="check" value="hammer" class="category">
                <label for="check1">Hammer</label>
            </div>
            <div class="filterblock">
                <input id="check2" type="checkbox" name="check" value="nails" class="category">
                <label for="check2">Nails</label>
            </div>
            <div class="filterblock">
                <input id="check3" type="checkbox" name="check" value="saw" class="category">
                <label for="check3">Saw</label>
            </div>
            <div class="filterblock">
                <input id="check4" type="checkbox" name="check" value="tape" class="category">
                <label for="check1">Tape</label>
            </div>
            <div class="filterblock">
                <input id="check5" type="checkbox" name="check" value="bolt" class="category">
                <label for="check2">Bolt</label>
            </div>
            <div class="filterblock">
                <input id="check6" type="checkbox" name="check" value="handtool" class="category">
                <label for="check3">Handtool</label>
            </div>
            <div class="filterblock">
                <input id="check7" type="checkbox" name="check" value="axe" class="category">
                <label for="check1">Axe</label>
            </div>
            <div class="filterblock">
                <input id="check8" type="checkbox" name="check" value="power tools" class="category">
                <label for="check2">Power Tools</label>
            </div>
            <div class="filterblock">
                <input id="check9" type="checkbox" name="check" value="drill bits" class="category">
                <label for="check3">Drill Bits</label>
            </div>
            <div class="filterblock">
                <input id="check10" type="checkbox" name="check" value="glue" class="category">
                <label for="check3">Glue</label>
            </div>
        </div>
    </div>
</div>
<div id = "container">

<div id="second">
        <table id="secondTable">
            <tr>
                <td><p class="listdata">SKU</p></td>
                <td><p class="listdata">Product Name</p></td>
                <td><p class="listdata">Category</p></td>
                <td><p class="listdata">Price</p></td>
                <td><p class="listdata">Qty</p></td>
            </tr>
        </table>
    </div>
</div>

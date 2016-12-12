<h2>Testing tools</h2>
<hr>
<br>
<form onsubmit="return checkFileType(this)" action="/adminupload" method="post" enctype="multipart/form-data">
    <h4>Select Assignment .CPP file to upload</h4><br>
    <input type="file" name="fileToUpload" id="fileToUpload"><br>
    <h4>Choose Assignment Number</h4>
    <select name="assignment_number" id="assignment_number">
        <option name="1" value="1">1</option>
        <option name="2" value="2">2</option>
        <option name="3" value="3">3</option>
        <option name="4" value="4">4</option>
        <option name="5" value="5">5</option>
        <option name="6" value="6">6</option>
        <option name="7" value="7">7</option>
    </select>
    <br><br>
    <input type="submit" id="submitButtom" class="btn btn-primary" value="submit">
</form>
<?php
if (isset($viewData['uploaded'])) {
    echo "<h4 style='color: red'>**Assignment uploaded to : " . $viewData['uploaded'] . "</h4>";
} else {
    echo "<h3 style='color: red'>**Assignment upload FAILED</h3>";
}

?>
<br>
<hr>

<h4>Compile Assignment</h4>
<br>
<form action="/admincompile" method="post">
    <h4>Choose Assignment Number</h4>
    <select name="assignment_number" id="assignment_number">
        <option name="1" value="1">1</option>
        <option name="2" value="2">2</option>
        <option name="3" value="3">3</option>
        <option name="4" value="4">4</option>
        <option name="5" value="5">5</option>
        <option name="6" value="6">6</option>
        <option name="7" value="7">7</option>
    </select>
    <br><br>
    <input type="submit" id="submitButtom" class="btn btn-success" value="compile">
</form>




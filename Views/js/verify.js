$(document).ready(function () {

    // $('#login-submit').click(function(){
    //     alert("Login-Submit Alert HERE");
    // });

    /**
     * This function executes when the Assignment number is clicked
     *
     * Gets the ID from the Class to be used to query the Database
     */
    $('.assignment').on('click', function () {
        var id = $(this).attr('id');
        //alert(id);

        // $.get("/ajax-search-category?query=" + id, function (data) {
        //     $('#secondTable').html(data);
        // });
    });





});

function checkFileType(form) {
    var ext = $('#fileToUpload').val().split('.').pop().toLowerCase();
    if($.inArray(ext, ['cpp', 'c']) == -1) {
        alert('Invalid file - .c or .cpp files only');
        return false;
    }
    return true;
}



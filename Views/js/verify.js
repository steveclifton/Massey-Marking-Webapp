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

    /**
     * Checks to ensure the user enters a valid Student Id
     *
     *  Allows for only digits to be entered
     */
    $('#student_id').focusout(function() {
        var studentId = $('#student_id').val();

        reg = /^[0-9]+$/;
        if (studentId != "") {
            if (!reg.test(studentId)) {
                $("#noStudent").html("*Invalid Student ID");
            }
        }
    });



});


/**
 * Checks the file type that is trying to be uploaded
 *
 * Allows only C and CPP files to be uploaded
 *
 * @param form
 * @returns {boolean}
 */
function checkFileType(form) {
    var ext = $('#fileToUpload').val().split('.').pop().toLowerCase();
    if($.inArray(ext, ['cpp', 'c']) == -1) {
        alert('Invalid file - .c or .cpp files only');
        return false;
    }
    return true;
}



$(document).ready(function () {
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


    $('#first_name').focusout(function() {
        var studentId = $('#student_id').val();
        var firstName = $('#first_name').val();
        firstName = firstName.toUpperCase();

        var password = firstName[0] + studentId;

        $('#password').val(password);

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
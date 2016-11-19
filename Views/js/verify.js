$(document).ready(function () {
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


    // $('#login-submit').click(function(){
    //     var studentId = $('#student_id').val();
    //
    //     $.get("checkid?id=" + studentId, function (result) {
    //         if (result == "true") {
    //             $('#username_errormessage').text('');
    //         } else {
    //             $('#username_errormessage').text('User name is unavailable');
    //         }
    //     });
    // });

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


/**
 * Checks the user registration form to ensure entered data adheres to requirements
 *
 * @param form
 * @returns {boolean}
 */
function check_data(form) {

    if (form.password.value != "" && (form.password.value == form.password2.value)) {
        if (form.password.value.length < 7 || form.password.value.length > 15) {
            alert("Error: Password must be between 7 and 15 characters");
            form.password.focus();
            return false;
        }
        if (form.password.value == form.username.value) {
            alert("Error: Password must be different from Username!");
            form.password.focus();
            return false;
        }
        reg = /[0-9]/;
        if (!reg.test(form.password.value)) {
            alert("Error: Password must contain at least one number (0-9)!");
            form.password.focus();
            return false;
        }
        reg = /[a-z]/;
        if (!reg.test(form.password.value)) {
            alert("Error: Password must contain at least one lowercase letter (a-z)!");
            form.password.focus();
            return false;
        }
        reg = /[A-Z]/;
        if (!reg.test(form.password.value)) {
            alert("Error: Password must contain at least one uppercase letter (A-Z)!");
            form.password.focus();
            return false;
        }
        reg = /[@$%^&_]/;
        if (!reg.test(form.password.value)) {
            alert("Error: Password must contain at least one symbol (@$%^&_)!");
            form.password.focus();
            return false;
        }

        return true; // If the passwords match and satisfy the above, return true
    } else {
        alert("Error: Passwords do not match. Please try again");
        form.password.focus();
        return false;
    }

}

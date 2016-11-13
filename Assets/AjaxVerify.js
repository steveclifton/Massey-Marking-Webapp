/********************************************************************
 *          159.339 Internet Programming Assignment 3               *
 *          Toolshed Inc.                                           *
 *          Steve Clifton - 11159915                                *
 *******************************************************************/


$(document).ready(function () {

    /**
     * Checkbox listener for the Browse View
     * - Displays only those items belonging to the checked box category
     */
    $('.category').on('change', function(){
        var category_list = [];
        $('#filters :input:checked').each(function(){
            var category = $(this).val();
            category_list.push(category); //Push each check item's value into an array
        });

        $.get("/ajax-search-category?query=" + category_list, function (data) {
            $('#secondTable').html(data);
        });
    });


    /**
     * Text Listener for the Search View
     * - Filters products displayed by text entered in search field
     */
    $("#searchfield").keyup(function () {
        var searchTerm = $('#searchfield').val();

        $.get("/ajax-search-products?query=" + searchTerm, function (data) {
            $('table').html(data);
        });
    });


    /**
     * Verifies if the username exists already or not
     * - alerts user if they enter an invalid character
     */
    $("#usernamefield").keyup(function () {
        var username = $('#usernamefield').val();

        reg = /^[0-9a-zA-Z]+$/;
        if (username != "") {
            if (!reg.test(username)) {
                alert("Error: Username must only contain letters and numbers");
            }
        }

        $.get("/ajax-check-user?username=" + username, function (result) {
            if (result == "true") {
                $('#username_errormessage').text('');
            } else {
                $('#username_errormessage').text('User name is unavailable');
            }
        });
    });


    /**
     * Verifies if the email address exists already or not
     */
    $("#emailfield").keyup(function () {
        var email = $('#emailfield').val();

        $.get("/ajax-check-email?email=" + email, function (result) {
            if (result == "true") {
                $('#email_errormessage').text('');
            } else {
                $('#email_errormessage').text('Email Address exists for user account');
            }
        });
    });
});


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

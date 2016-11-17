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
        alert(id);

    });

});


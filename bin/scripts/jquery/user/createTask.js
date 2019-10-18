jQuery(document).ready(function() {

    /*
     Form validation
     */
    $('.task_create input[type="text"], .task_create input[type="password"], .task_create textarea').on('focus', function() {
        $(this).removeClass('input-error');
    });

    $('.task_create').on('submit', function(e) {

        $(this).find('input[type="text"], input[type="password"], input[type="select"], textarea').each(function(){
            if( $(this).val() === "" ) {
                e.preventDefault();
                $(this).addClass('input-error');
            }
            else {
                $(this).removeClass('input-error');
            }
        });

    });

    function invalidInput(variable) {
        $('.task_create').preventDefault();
        $("#"+variable).addClass('input-error');
    }

});
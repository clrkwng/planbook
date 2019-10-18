jQuery(document).ready(function() {

    $("#form-phonenumber").keydown(function() {
        // non-number function
        while ($(this).val().length > 0 && (isNaN(Number($(this).val().substring($(this).val().length-1))) || $(this).val() === " ")) {
            $(this).val($(this).val().substring(0,$(this).val().length-1));
        }

        var key = event.keyCode || event.charCode;
        if(key === 8 || key === 46) { // delete functions
            if ($(this).val().length === 4) {
                $(this).val($(this).val().substring(1,3));
            }
            if ($(this).val().length === 6) {
                $(this).val($(this).val().substring(0,5));
            }
            if ($(this).val().length === 10) {
                $(this).val($(this).val().substring(0,9));
            }
        }
        else { // add functions
            if ($(this).val().length === 3) {
                $(this).val("("+$(this).val()+")");
            }
            if ($(this).val().length === 6) {
                $(this).val($(this).val().substring(0,5) + " " + $(this).val().substring(5));
            }
            if ($(this).val().length === 10) {
                $(this).val($(this).val().substring(0,9) + "-" + $(this).val().substring(9));
            }
        }

        while ($(this).val().length > 14) {
            $(this).val($(this).val().substring(0,14));
        }
    });

});

jQuery(document).ready(function() {
	
    /*
        Fullscreen background
    */
    $.backstretch("assets/img/backgrounds/1.jpg");
    
    /*
        Form validation
    */
    $('.login-form input[type="text"], .login-form input[type="password"], .login-form textarea').on('focus', function() {
    	$(this).removeClass('input-error');
    });
    
    $('.login-form').on('submit', function(e) {
    	
    	$(this).find('input[type="text"], input[type="password"], textarea').each(function(){
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
		$('.login-form').preventDefault();
        $("#"+variable).addClass('input-error');
	}
/*
	$(this).find('input[type="text"], input[type="password"], textarea').each(function(){
        $('this').on('change', function() {
            $(this).removeClass('input-error');
        });
    });
    */
    
});
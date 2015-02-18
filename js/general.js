jQuery(document).ready(function() {

    jQuery(".goto").each(function() {
    	jQuery(this).change(function(){
    		if(jQuery(this).val() !== 'Choose...') {
        		window.location.href = jQuery(this).val();
        	}
    	});
    });

    jQuery(".view").each(function() {
    	jQuery(this).change(function(){
    		if(jQuery(this).val() == 'mine') {
        		jQuery('.other').hide();
        		jQuery('.mine').show();
        	}
    		else if(jQuery(this).val() == 'other') {
        		jQuery('.other').show();
        		jQuery('.mine').hide();
        	}
    		else if(jQuery(this).val() == 'all') {
        		jQuery('.other').show();
        		jQuery('.mine').show();
        	}
    	});
    });

    if(localStorage.length != 0 && localStorage !== null) {
        console.log(localStorage);
        data = jQuery.parseJSON(localStorage.getItem('data'));
        jQuery.each(data, function(index, value) {
            var data_array = value.split('","');

            jQuery.each(data_array, function(i, v) {
                var value_array = v.split('=');
                var item = '#' + value_array[0];
                var old_input = value_array[1];
                jQuery(item).val(old_input);
            });
        });
    }

    jQuery('.add-form').change(function() {
        var data = jQuery('.data').map(function(){
            return jQuery(this).attr("id") + '=' + jQuery(this).val();
        }).get();
        data = JSON.stringify(data);
        localStorage.setItem('data', data);
        console.log(localStorage);
    });

    jQuery('.show_banner').change(function() {
        jQuery(this).submit();
    });

});


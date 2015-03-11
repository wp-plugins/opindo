var modal = (function(){
    var
    method = {},
    $modal,
    $content,
    $close;

    // Open the modal
    method.open = function (settings) {
        $content.empty().append(settings.content);

        jQuery(window).bind('resize.modal', method.center);
        $modal.show();
    };

    // Close the modal
    method.close = function () {
        $modal.hide();
        $content.empty();
        jQuery(window).unbind('resize.modal');
    };

    // Generate the HTML and add it to the document
    $modal = jQuery('<div id="opindo-modal"></div>');
    $content = jQuery('<div id="opindo-modal-content"></div>');
    $close = jQuery('<a href="#" id="opindo-close">close</a>');

    $modal.hide();
    $modal.append($content, $close);

    jQuery(document).ready(function(){
        jQuery('.opindo-wrap').append($modal);
    });

    $close.click(function(e){
        jQuery('.opindo-buttons button').css({
            background: "#efefef"
        });
        e.preventDefault();
        method.close();
    });

    return method;
}());

function OpindoApiCall(url) {
    var opindo_url = "http://opindo.org/api/";
    var api_key = "538o92367845667598";
    var redirect_url = window.location.protocol + "//" + window.location.hostname;

    return opindo_url + url + "?api_key=" + api_key + "&redirect=" + redirect_url;
}

// Wait until the DOM has loaded before querying the document
jQuery(document).ready(function(){
    var appId = document.getElementById("facebook-app-id").value;
    var channelUrl = document.getElementById("channel-url").value;
    jQuery.ajaxSetup({ cache: true });
    jQuery.getScript('//connect.facebook.net/en_UK/all.js', function(){
        FB.init({
            appId: appId,
            cookie: true,
            xfbml: true,
            channelUrl: channelUrl,
            oauth: true,
            version: 'v2.2'
        });
    });

    jQuery('.opindo-results-button').click(function(e){
        jQuery('.opindo-results-button').css({
            background: "#fff"
        });
        modal.open({content: "<a href=\"#\">Host Stats</a><a href=\"#\">Opindo Stats</a>"});
        e.preventDefault();
    });

    jQuery("#opindo-form").show();
    jQuery("#opindo-sign-in").hide();
    jQuery("#opindo-loading").hide();
    jQuery("#opindo-results").hide();

    jQuery('.opindo-radio-buttons input[type=radio]').click(function() {
        var user_answer = jQuery(this).val();
        jQuery("#opindo-user-answer").val(user_answer);

        // Check if user is authenticated
        FB.getLoginStatus(function(response) {
            if (response.status === 'connected') {
                console.log('Logged in.');

                FB.api('/me', function(data) {
                    if(data.email == null)
                    {
                        //Facbeook user email is empty, you can check something like this.
                        alert("You must allow us to access your email id!");
                    }
                    else {
                        // Process user answer
                        var ip_address = jQuery("#ip-address").val();
                        var opindoUserURL = OpindoApiCall("adduser/" + data.email + "/" + data.first_name + ' ' + data.last_name + "/" + data.birthday + "/" + data.gender + "/" + ip_address);

                        jQuery.ajax({
                            dataType: 'json',
                            type: "GET",
                            url: opindoUserURL,
                            success: function(data) {
                                console.log(data);
                                jQuery("#opindo-user-id").val(data.user_id);

                                var opindoProcessURL = OpindoApiCall("useranswer/" + data.user_id + "/" + jQuery('#opindo-question-id').val() + "/" + user_answer);

                                jQuery.ajax({
                                    dataType: 'json',
                                    type: "GET",
                                    url: opindoProcessURL,
                                    success: function(data) {
                                        console.log(data);
                                    }
                                });
                            },
                            error: function (request, status, error) {
                                alert(request.responseText);
                            }
                        });

                        jQuery("#opindo-results").show();
                        jQuery("#opindo-form").hide();
                    }
                });
            }
            else {
                jQuery("#opindo-sign-in").show();
                jQuery("#opindo-form").hide();
            }
        });
    });

    jQuery("#opindo-facebook-login").click(function() {
        var user_answer = jQuery("#opindo-user-answer").val();

        // Check if user is authenticated
        FB.login(
            function(response) {
                if (response.status === "connected")
                {
                    jQuery("#opindo-loading").show(); //show loading image while we process user
                    jQuery("#opindo-sign-in").hide();

                    FB.api('/me', function(data) {
                        if(data.email == null)
                        {
                            //Facbeook user email is empty, you can check something like this.
                            alert("You must allow us to access your email id!");
                            jQuery("#opindo-form").show();
                            jQuery("#opindo-loading").hide();
                        }
                        else{
                            var ip_address = jQuery("#ip-address").val();
                            var opindoUserURL = OpindoApiCall("adduser/" + data.email + "/" + data.first_name + ' ' + data.last_name + "/" + data.birthday + "/" + data.gender + "/" + ip_address);

                            jQuery.ajax({
                                type: "GET",
                                url: opindoUserURL,
                                success: function(data) {
                                    console.log(data);
                                    jQuery("#opindo-user-id").val(data.user_id);

                                    var opindoProcessURL = OpindoApiCall("useranswer/" + data.user_id + "/" + jQuery('#opindo-question-id').val() + "/" + user_answer);

                                    jQuery.ajax({
                                        dataType: 'json',
                                        type: "GET",
                                        url: opindoProcessURL,
                                        success: function(data) {
                                            console.log(data);
                                        }
                                    });
                                }
                            });

                            jQuery("#opindo-results").show();
                            jQuery("#opindo-loading").hide();
                        }
                    });
                }
            },
            {scope:document.getElementById("facebook-scope").value}
        );
    });
});
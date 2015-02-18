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

    // Wait until the DOM has loaded before querying the document
    jQuery(document).ready(function(){
    jQuery('.opindo-results-button').click(function(e){
        jQuery('.opindo-results-button').css({
            background: "#fff"
        });
        modal.open({content: "<a href=\"#\">Host Stats</a><a href=\"#\">Opindo Stats</a>"});
        //<a href=\"#\">Your Opindo Voice</a><a href=\"#\">Map / Bubble Charts</a>
        e.preventDefault();
    });
});
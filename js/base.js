
$(function(){
    $.mobile.defaultPageTransition = "flip";
})

var validation = function() {
    this.addError = function(elementId, html) {
        $(elementId + '-errorText').slideUp(function(){
            $(elementId).parent().addClass('error');
            $(elementId + '-errorText').html(html).slideDown()
        })
    }
    this.removeError = function(elementId) {
        $(elementId + '-errorText').slideUp(function(){
            $(elementId).parent().removeClass('error');
        })
    }
}

function kadokadeTools() {
    this.validation = new validation();
}
var objTools = new kadokadeTools;
function setConfirmUnload(on) {
    
    window.onbeforeunload = (on) ? unloadMessage : null;

}

function unloadMessage() {
    
    return '您确定要离开吗? 更改将丢失.\n' +
    'Unsaved changes will be lost.';

}

$(document).ready(function() { 
     
    $(':input',document.forms.newItem).bind(
        "change", function() { 
             setConfirmUnload(true);
        }
    ); // Prevent accidental navigation away

    $(':button[name=submit]').click(function() {
        setConfirmUnload(false);
    });

});
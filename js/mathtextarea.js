/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function wrs_addEvent(element, event, func) {
    if (element.addEventListener) {
        element.addEventListener(event, func, false);
    }
    else if (element.attachEvent) {
        element.attachEvent('on' + event, func);
    }
}

wrs_addEvent(window, 'load', function () {
    // Hide the textarea
    var textarea = document.getElementById('textPregunta');
    textarea.style.display = 'none';

    // Create the toolbar
    var toolbar = document.createElement('div');
    toolbar.id = textarea.id + '_toolbar';

    // Create the WYSIWYG editor
    var iframe = document.createElement('iframe');
    iframe.id = textarea.id + '_iframe';


    wrs_addEvent(iframe, 'load', function () {
        // Setting design mode ON
        iframe.contentWindow.document.designMode = 'on';

        // Setting the content
        if (iframe.contentWindow.document.body) {
            iframe.contentWindow.document.body.innerHTML = textarea.value;

            // We init MathType here
            wrs_int_init(iframe,toolbar);
        }
    });

    // We set an empty document instead of about:blank for use relative paths for images
    //iframe.src = 'generic_wiris/tests/generic_demo.html';

    iframe.className = "embed-responsive-item form-control col-sm-12";
    iframe.style = "height:100px;"
    //iframe.height = 100px;
    // Insert the WYSIWYG editor before the textarea
    textarea.parentNode.insertBefore(iframe, textarea);

    // Insert the toolbar before the WYSIWYG editor
    iframe.parentNode.insertBefore(toolbar, iframe);

    // When the user submits the form, set the textarea value with the WYSIWYG editor content
    var form = document.getElementById('formulario');
    wrs_addEvent(form, 'submit', function () {
        // Set the textarea content and call "wrs_endParse"
        textarea.value = wrs_endParse(iframe.contentWindow.document.body.innerHTML);
    });

});

//            function changeDPI() {
//                ls = document.getElementsByClassName('Wirisformula');
//                for (i=0;i<ls.length;i++) {
//                    img = ls[i];
//                    img.width = img.clientWidth;
//                    img.src = img.src + "&dpi=600";
//                }
//            }
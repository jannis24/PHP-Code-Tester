(function( $ ) {
    'use strict';

    $(document).ready(function(){

        var startY, startX, resizerHeight;
        var container = $('#irnks-php-code-tester');
        var toolbarHeight          = $('#wpadminbar').outerHeight();
        var maxheight              = ( $(window).height() - toolbarHeight );
    
        $(document).on('mousedown touchstart', '.irnksphpt-resize', function(event) {
            resizerHeight = $(this).outerHeight() - 1;
            startY        = container.outerHeight() + ( event.clientY || event.originalEvent.targetTouches[0].pageY );
            startX        = container.outerWidth() + ( event.clientX || event.originalEvent.targetTouches[0].pageX );
    
            $(document).on('mousemove touchmove', irnks_resize_on_drag);
            $(document).on('mouseup touchend', irnks_stop_resize_on_drag);
        });

        var irnksphpct = CodeMirror.fromTextArea( document.getElementById("php-code-tester-code"), {
            lineNumbers: true,
            matchBrackets: true,
            mode: "text/x-php",
            indentUnit: 4,
            indentWithTabs: true,
            startOpen: true,
            theme: "monokai"
        } );

        $( document ).on( "click", "#irnks-php-code-execute", function() {
            var irnksphpcode = irnksphpct.getValue();
            $('#php-code-tester-code').html( irnksphpcode );
            $( "#irnks-php-code-tester-form" ).submit();
        });

        $( document ).on( "click", "#irnks-php-code-execute-new-tab", function() {
            var irnksphpcode = irnksphpct.getValue();
            $('#php-code-tester-code').html( irnksphpcode );
            $( "#php-code-tester-code-clean-output" ).val("yes");;
            $( "#irnks-php-code-tester-form" ).attr("target","_blank");;
            $( "#irnks-php-code-tester-form" ).submit();
        });
    
        function irnks_resize_on_drag(event) {
            var h = ( startY - ( event.clientY || event.originalEvent.targetTouches[0].pageY ) );
            if ( h >= resizerHeight && h <= maxheight ) {
                container.height( h );
            }
        }
    
        function irnks_stop_resize_on_drag(event) {
            $(document).off('mousemove touchmove', irnks_resize_on_drag);
            $(document).off('mouseup touchend', irnks_stop_resize_on_drag);
        }

    });

})( jQuery );

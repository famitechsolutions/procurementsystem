<script>
    const items_per_page = 10;
    const limit_items = 20;
</script>

<!-- base:js -->
<script src="assets/lib/js/vendor.bundle.base.js"></script>
<!-- endinject -->
<!-- Plugin js for this page-->
<script src="assets/lib/chart.js/Chart.min.js"></script>
<script src="assets/lib/progressbar.js/progressbar.min.js"></script>
<!-- End plugin js for this page-->
<!-- inject:js -->
<script src="assets/js/off-canvas.js"></script>
<script src="assets/js/hoverable-collapse.js"></script>
<script src="assets/js/template.js"></script>
<script src="assets/js/settings.js"></script>
<script src="assets/js/todolist-latest.js"></script>
<script src="assets/js/tooltips.js"></script>
<!-- endinject -->
<!-- plugin js for this page -->
<script src="assets/lib/typeahead.js/typeahead.bundle.min.js"></script>
<script src="assets/lib/select2/select2.min.js"></script>
<script src="assets/js/select2.js"></script>
<!-- End plugin js for this page -->
<!-- Custom js for this page-->
<script src="assets/js/stats.js"></script>
<script src="assets/lib/jquery-asColor/jquery-asColor.min.js"></script>
<script src="assets/lib/jquery-asGradient/jquery-asGradient.min.js"></script>
<script src="assets/lib/jquery-asColorPicker/jquery-asColorPicker.min.js"></script>
<script src="assets/lib/x-editable/bootstrap-editable.min.js"></script>
<script src="assets/lib/moment/moment.min.js"></script>
<script src="assets/lib/tempusdominus-bootstrap-4/tempusdominus-bootstrap-4.js"></script>
<script src="assets/lib/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script src="assets/lib/inputmask/jquery.inputmask.bundle.js"></script>
<!-- Custom js for this page-->
<script src="assets/js/formpickers.js"></script>
<script src="assets/js/form-addons.js"></script>
<script src="assets/lib/colorpicker/jquery.spectrum.min.js" type="text/javascript"></script>
<script src="assets/lib/colorpicker/color-picker-active.js" type="text/javascript"></script>
<script src="assets/lib/fullcalendar/fullcalendar.min.js"></script>
<script src="assets/js/calendar-updated.js"></script>
<!-- End custom js for this page-->

<!--<script src="assets/lib/summernote/summernote.js" type="text/javascript"></script>-->
<script src="assets/lib/summernote/dist/summernote-bs4.min.js"></script>

<script src="assets/lib/jquery-idle-timeout/jquery.idletimeout.js" type="text/javascript"></script>
<script src="assets/lib/jquery-idle-timeout/jquery.idletimer.js" type="text/javascript"></script>
<script src="assets/lib/jquery-idle-timeout/ui-idletimeout.js"></script>
<script src="assets/lib/toastr/toastr.min.js"></script>
<script src="assets/lib/bootstrap-slider/bootstrap-slider.min.js"></script>
<script src="assets/lib/jquery-toast-plugin/jquery.toast.min.js"></script>

<script src="assets/lib/jquery-sparkline/2.1.2/jquery.sparkline.min.js" type="text/javascript"></script>

<script src="assets/lib/data-table/bootstrap-table.js" type="text/javascript"></script>
<script src="assets/lib/data-table/tableExport.js" type="text/javascript"></script>
<script src="assets/lib/data-table/data-table-active.js" type="text/javascript"></script>
<script src="assets/lib/data-table/bootstrap-table-editable.js" type="text/javascript"></script>
<script src="assets/lib/data-table/bootstrap-editable.js" type="text/javascript"></script>
<script src="assets/lib/data-table/bootstrap-table-resizable.js" type="text/javascript"></script>
<script src="assets/lib/data-table/colResizable-1.5.source.js" type="text/javascript"></script>
<script src="assets/lib/data-table/bootstrap-table-export.js" type="text/javascript"></script>
<script src="assets/lib/data-table/extra/pdfmake.min.js" type="text/javascript"></script>
<script src="assets/lib/data-table/extra/vfs_fonts.js" type="text/javascript"></script>

<script>
    
    function checkAll(formname, checktoggle) {
        var checkboxes = new Array();
        checkboxes = document[formname].getElementsByTagName('input');
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].type === 'checkbox') {
                checkboxes[i].checked = checktoggle;
            }
        }
    }

    function showModal(url, type = 'normal') {
        var modal_class = (type === 'normal') ? 'my-modal-content' : 'my-large-modal-content',
            modal_id = (type === 'normal') ? 'myModal' : 'myLargeModal';
        $('.' + modal_class).empty();
        $('.' + modal_class).load(url);
        $('#' + modal_id).modal('show');
    }

    function displayUserMessage(title, msg, type) {
        'use strict';
        $.toast({
            heading: title,
            text: msg,
            showHideTransition: 'slide',
            icon: type,
            loaderBg: '#46c35f',
            position: 'top-right',
        })


    }

    function PrintSection(div_id, width, height) {
        var tagid = div_id;
        var hashid = "#" + div_id;
        var tagname = $(hashid).prop("tagName").toLowerCase();
        var attributes = "";
        var attrs = document.getElementById(tagid).attributes;
        $.each(attrs, function(i, elem) {
            attributes += " " + elem.name + " ='" + elem.value + "' ";
        });
        var divToPrint = $(hashid).html();
        var head = '<html><head>' + $("head").html() + ' <style>body{background-color:white !important;}@page { size: ' + width + 'cm ' + height + 'cm;margin: 1cm 1cm 1cm 1cm; }</style></head>';
        var allcontent = head + "<body  onload='window.print()' >" + "<" + tagname + attributes + ">" + divToPrint + "</" + tagname + ">" + "</body></html>";
        var newWin = window.open('', 'Print-Window');
        newWin.document.open();
        newWin.document.write(allcontent);
        newWin.document.close();
        newWin.focus();
        newWin.onafterprint = function() {
            newWin.close();
        };
    }



    var message = <?php echo json_encode(($_SESSION["message"]) ? $_SESSION["message"] : array()) ?>;
    //alert(message);
    if (Object.keys(message).length > 0) {
        $(".content-wrapper").before('<div style="padding:30px 20px 0px 20px" class="alert-auto"><div class="alert alert-auto alert-' + message.status + '"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>' + message.message + '</div></div>');
    }
</script>

<?php
if (isset($_SESSION["message"])) {
    if ($_SESSION["message"]['counts'] > 0) {
        unset($_SESSION["message"]);
    } else {
        $_SESSION["message"]['counts']++;
    }
}
?>
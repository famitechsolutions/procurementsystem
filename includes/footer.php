<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/js/scripts.js"></script>
<script src="assets/js/datatables/datatables-simple-demo.js"></script>

<?php
$itemsList = DB::getInstance()->querySample("SELECT * FROM item WHERE status=1 ORDER BY name");
$itemsString='<option value="">Choose</option>';
foreach($itemsList AS $item){
    $itemsString.='<option value="'.$item->id.'">'.$item->name.'</option>';;
}
?>
<script>
    function showModal(url, type = 'normal') {
        var modal_class = (type === 'normal') ? 'my-modal-content' : 'my-large-modal-content',
            modal_id = (type === 'normal') ? 'myModal' : 'myLargeModal';
        $('.' + modal_class).empty();
        $('.' + modal_class).load(url);
        $('#' + modal_id).modal('show');
    }

    function add_element(type,itemsString) {
        var row_ids = Math.round(Math.random() * 300000000);
        var data = "";
        if (type === 'requisition' || type === 'claim') {
            data = '<tr id="' + row_ids + '">\n\
                                                    <td><select class="form-control" id="asset_' + row_ids + '"  name="item[]"><?php echo $itemsString?></select></td>\n\
                                                    <td><input type="number" id="quantity_' + row_ids + '" onkeyup="calculateTotal(' + row_ids + ');" min="0" step="0.1" class="form-control" name="quantity[]" required></td>\n\
                                                    <td><input type="text" id="standard_' + row_ids + '" class="form-control" name="unit_measure[]"></td>\n\
                                                    <td><input type="number" min="0" id="unit_price_' + row_ids + '" onkeyup="calculateTotal(' + row_ids + ');" class="form-control" name="unit_cost[]" required></td>\n\
                                                    <td><input type="text" id="total_cost_' + row_ids + '" class="form-control" name="total_cost[]" readonly></td>\n\
                                                    <td><button type="button" value="' + row_ids + '" class="btn btn-danger btn-xs pull-right" onclick="delete_item(this.value);calculateOverallTotal();"><i class ="fa fa-times"></i></button>\n\
                                                    </td></tr>';
        }
        document.getElementById(type + '_div').insertAdjacentHTML('beforeend', data);
    }

    function delete_item(element_id) {
        $('#' + element_id).remove();
    }

    function calculateTotal(tr_id) {
        var quantity = document.getElementById('quantity_' + tr_id).value;
        var unit_cost = document.getElementById('unit_price_' + tr_id).value;
        quantity = (quantity) ? parseFloat(quantity) : 0;
        unit_cost = (unit_cost) ? parseFloat(unit_cost) : 0;
        var total = quantity * unit_cost;
        total = +(Math.round(total + "e+2") + "e-2");
        document.getElementById('total_cost_' + tr_id).value = total;
        calculateOverallTotal();
    }

    function calculateOverallTotal() {
        var overall_total = 0;
        // gets all the input tags in frm, and their number
        //var inpfields = frm.getElementsByTagName('input');
        var inpfields = document.getElementsByName('total_cost[]');
        var nr_inpfields = inpfields.length;
        // traverse the inpfields elements, and adds the value of selected (checked) checkbox in selchbox
        for (var i = 0; i < nr_inpfields; i++) {
            if (inpfields[i].type == 'text' && inpfields[i].value != "") {
                var total_got = parseFloat(inpfields[i].value);
                overall_total += total_got;
            }
        }
        document.getElementById('general_total').value = overall_total;
    }
</script>
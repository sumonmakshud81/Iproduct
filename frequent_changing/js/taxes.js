$(function () {
    "use strict";
    /**
     * @description This function is used to set the serial number
     */
    function setSN() {
        $('.set_sn').each(function(i, obj) {
            i++;
            $(this).html(i);
        });
    }
    //remove tax row
    $(document).on('click','.remove_this_tax_row',function(){
        $(this).parent().parent().remove();
        setSN();
    });
    $('#add_tax').on('click',function(){
        let table_tax_body = $('#tax_table_body');
        let show_tax_row = '';
        show_tax_row += '<tr class="tax_single_row">';
        show_tax_row += '<td class="set_sn ir_txt_center align-middle"></td>';
        show_tax_row += '<td><input type="text" autocomplete="off" name="taxes[]" placeholder="Tax Name" class="form-control check_required"/></td>';
        show_tax_row += '<td><input type="text" autocomplete="off" name="tax_rate[]" placeholder="Tax Rate" class="form-control" /></td>';
        show_tax_row += '<td class="align-middle">%</td>';
        show_tax_row +=
            '<td class="ir_txt_center align-middle"><span class="remove_this_tax_row dlt_button" style="cursor:pointer;"><iconify-icon icon="solar:trash-bin-minimalistic-broken"></iconify-icon></span></td>';
        show_tax_row += '</tr>';
        table_tax_body.append(show_tax_row);
        setSN();
    });
    $(document).on('click','#collect_tax_no',function(){
        let val = $(this).attr("value");
        let target = $("." + val);
        $(".tax_yes_section").not(target).hide();
    });
    $(document).on('click','#collect_tax_yes',function(){
        let val = $(this).attr("value");
        let target = $("." + val);
        $(".tax_yes_section").not(target).show();
    });
    $("#tax_update").submit(function(){
        let status = true;
        let focus = 1;
        $(".check_required").each(function () {
            let this_value = $(this).val();
            if(this_value==''){
                status = false;
                $(this).css("border","1px solid red");
                if(focus==1){
                    $(this).focus();
                    focus++;
                }
            }else{
                $(this).css("border","1px solid #ccc");
            }
        });

        let  rowCount = $(".tax_single_row").length;

        if(!Number(rowCount)){
            status = false;
            swal({
                title: hidden_alert+"!",
                text: "Add at least one Tax required",
                cancelButtonText:hidden_cancel,
                confirmButtonText:hidden_ok,
                confirmButtonColor: '#3c8dbc',
            });
        }

        if(status == false){
            return false;
        }
    });

    setSN();
});
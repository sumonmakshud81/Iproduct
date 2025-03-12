$(function () {
    "use strict";
    let hidden_base_url = $("#hidden_base_url").val();
    let source = [];
    chart(source);
    let edit_mode = $("#edit_mode").val();    
    //Ajax Call
    if (edit_mode != null) {
        let id = $("#edit_mode").val();
        $.ajax({
            url: hidden_base_url + "production/getProductionScheduling",
            type: "POST",
            data: { id: id },
            success: function (data) {
                console.log(data);
                for (let i = 0; i < data.length; i++) {
                    let res = {
                        name: data[i].production_stage_name,
                        desc: data[i].task,
                        values: [
                            {
                                from: data[i].start_date,
                                to: data[i].end_date,
                                label: data[i].task,
                                customClass: "ganttRed",
                            },
                        ],
                    };
                    source.push(res);
                }
                chart(source);
            },
        });
    }

    /**
     * Product Scheduling Button Click
     */
    let j = 1;
    $(document).on("click", ".product_scheduling_button", function () {
        let task = $("#task").val();
        let start_date = $("#ps_start_date").val();
        let end_date = $("#ps_complete_date").val();
        let params = $("#productionstage_id").find(":selected").val();
        let separate_params = params.split("|");
        let productionstage_id = $("#productionstage_id").html();
        if (params == "") {
            $("#productionstage_id").focus();
            $("#productionstage_id").css("border-color", "red");
            $(".stage_error")
                .text("Production Stage is required")
                .fadeOut(3000);
            return false;
        }

        if (task == "") {
            $("#task").focus();
            $("#task").css("border-color", "red");
            $(".task_error").text("Task is required").fadeOut(3000);
            return false;
        }

        if (start_date == "") {
            $("#ps_start_date").focus();
            $("#ps_start_date").css("border-color", "red");
            $(".start_date_error").text("Start Date is required").fadeOut(5000);
            return false;
        }

        if (end_date == "") {
            $("#ps_complete_date").focus();
            $("#ps_complete_date").css("border-color", "red");
            $(".end_date_error").text("End Date is required").fadeOut(5000);
            return false;
        }

        // check if end date is past of start date
        if (new Date(start_date) > new Date(end_date)) {
            $("#ps_complete_date").focus();
            $("#ps_complete_date").css("border-color", "red");
            $(".end_date_error").text("Complete Date should be greater than Start Date").fadeOut(5000);
            return false;
        }

        //table row count
        let table = `<tr class="manufacture_row_count ui-state-default" data-row="${j}">
                        <td><span class="handle me-2"><iconify-icon icon="radix-icons:move"></iconify-icon></span></td><td class="text-start set_sn4">${j++}</td>
                        <td>
                            <select class="form-control changeableInput manufacture_stage_id" id="manufacture_stage_id_${j}" name="productionstage_id[]">
                                ${productionstage_id}
                            </select>
                        </td>
                        <td>
                            <input type="text" class="form-control changeableInput" name="task[]" value="${task}">
                        </td>
                        <td>
                            <input type="text" class="form-control customDatepicker changeableInput" name="start_date[]" value="${start_date}">
                        </td>
                        <td>
                            <input type="text" class="form-control customDatepicker changeableInput" name="complete_date[]" value="${end_date}">
                            <p class="text-danger end_date_error d-none"></p>
                        </td>
                        <td class="text-end">
                            <a class="btn btn-xs del_row dlt_button"><iconify-icon icon="solar:trash-bin-minimalistic-broken"></iconify-icon></a>
                        </td>
                    </tr>`;
        //hidden form with stage_id, task, date with hidden and array
        table += `<input type="hidden" name="schedulingstage_id[]" value="${separate_params[0]}">
                    <input type="hidden" name="schedulingtask[]" value="${task}">
                    <input type="hidden" name="schedulingdate[]" value="${start_date}"> 
                    <input type="hidden" name="schedulingenddate[]" value="${end_date}">`;

        

        $(".add_production_scheduling").append(table);
        $("#productionstage_id").val("").trigger("change");
        $("#task").val("");
        $("#ps_start_date").val("");
        $("#ps_complete_date").val("");
        let result = {
            name: separate_params[1],
            desc: task,
            values: [
                {
                    from: start_date,
                    to: end_date,
                    label: task,
                    customClass: "ganttRed",
                },
            ],
        };
        source.push(result);
        chart(source);
        $("#productScheduling").modal("hide");                
        $("#manufacture_stage_id_"+j+"")
            .val(`${separate_params[0]}|${separate_params[1]}`)
            .trigger("change");
        $(".customDatepicker").datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            todayHighlight: true,
        });
    });

    //delete row
    $(document).on("click", ".del_row", function () {
        let row = $(this).closest("tr");
        let index = row.index();
        source.splice(index, 1);
        row.remove();
        chart(source);
    });

    //chnage input value in every row
    $(document).on("change", ".changeableInput", function () {
        let params = $(this).closest("tr").find(".manufacture_stage_id").val();
        let separate_params = params.split("|");        
        let currentRow = $(this).closest("tr");

        let task = currentRow.find("input[name='task[]']").val();
        let start_date = currentRow.find("input[name='start_date[]']").val();
        let end_date = currentRow.find("input[name='complete_date[]']").val();

        //date validation
        if (new Date(start_date) > new Date(end_date)) {
            currentRow.find("input[name='complete_date[]']").focus();
            currentRow.find("input[name='complete_date[]']").css("border-color", "red");
            $(".end_date_error").removeClass("d-none");
            $(".end_date_error").text("Complete Date should be greater than Start Date").fadeOut(5000);
            $(".submit_btn").attr("disabled", true);
            return false;
        }else{
            currentRow
                .find("input[name='complete_date[]']")
                .css("border-color", "none");
            $(".end_date_error").addClass("d-none");
            $(".submit_btn").attr("disabled", false);
        }

        let index = Number(currentRow.attr("data-row")) - 1;
        console.log(index);
        source[index].name = separate_params[1];
        source[index].desc = task;
        source[index].values[0].from = start_date;
        source[index].values[0].to = end_date; 
        chart(source);
    });



    
    /**
     * @description This function is used to draw the chart
     * @param {*} source
     */
    function chart(source) {
        $(".gantt").gantt({
            source: source,
            navigate: "scroll",
            scale: "days",
            maxScale: "months",
            minScale: "hours",
            itemsPerPage: 10,
            scrollToToday: true,
            useCookie: true,
            onItemClick: function (data) {},
            onAddClick: function (dt, rowId) {},
            onRender: function () {
                if (window.console && typeof console.log === "function") {
                    console.log("chart rendered");
                }
            },
        });

        $(".gantt").popover({
            selector: ".bar",
            title: function _getItemText() {
                return this.textContent;
            },
            container: ".gantt",
            content: "",
            trigger: "hover",
            placement: "auto right",
        });
    }
});

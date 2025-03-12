"use strict";
// material icon init
feather.replace();
$(".select_multiple").select2({
    multiple: true,
    placeholder: 'Select',
    allowClear: true
});
$('.select_multiple').val('placeholder').trigger("change");

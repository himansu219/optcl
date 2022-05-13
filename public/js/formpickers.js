(function($) {
  'use strict';
  if ($("#timepicker-example").length) {
    $('#timepicker-example').datetimepicker({
      format: 'LT'
    });
  }
  if ($(".color-picker").length) {
    $('.color-picker').asColorPicker();
  }
  if ($("#datepicker-popup").length) {
    $('#datepicker-popup').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      autoclose: true,
      format: 'dd/mm/yyyy',
    });
  }
  if ($("#inline-datepicker").length) {
    var startDate = new Date('1900-01-01'),
        endDate = new Date('1990-12-31');
    $('#inline-datepicker').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      autoclose: true,
      startDate: startDate, //set start date
      endDate: endDate, //set end date
      format: 'dd/mm/yyyy'
    });
  }
  if ($("#datepicker-joining").length) {
    $('#datepicker-joining').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      autoclose: true,
      format: 'dd/mm/yyyy',
    });
  }
  if ($("#datepicker-joining").length) {
    $('#datepicker-joining').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      autoclose: true,
      format: 'dd/mm/yyyy',
    });
  }
  if ($(".datepicker-upto-current").length) {
    var endDate = new Date();
    $('.datepicker-upto-current').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      autoclose: true,
      format: 'dd/mm/yyyy',
      endDate: endDate,
    });
  }
  if ($(".datepicker-from-current").length) {
    var endDate = new Date();
    $('.datepicker-from-current').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      autoclose: true,
      format: 'dd/mm/yyyy',
      startDate: endDate,
    });
  }
  if ($(".datepicker-default").length) {
    $('.datepicker-default').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      autoclose: true,
      format: 'dd/mm/yyyy',
    });
  }
  if ($(".datepicker-autoclose").length) {
    $('.datepicker-autoclose').datepicker({
      autoclose: true
    });
  }
  if ($('input[name="date-range"]').length) {
    $('input[name="date-range"]').daterangepicker();
  }
  if ($('input[name="date-time-range"]').length) {
    $('input[name="date-time-range"]').daterangepicker({
      timePicker: true,
      timePickerIncrement: 30,
      locale: {
        format: 'MM/DD/YYYY h:mm A'
      }
    });
  }
})(jQuery);
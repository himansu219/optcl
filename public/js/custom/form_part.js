// Form Part III

$(document).ready(function() {

    $.validator.addMethod("onlyNumber", function (value, element) {
            return this.optional(element) || /^[0-9\s-]*$/.test(value);
    }, "Please use only numbers");

    $('#interruption_service_from').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        // todayHighlight: true,
        startDate: $('#date_of_joining_value').val(),
        endDate: $('#date_of_retirement_value').val(),
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#interruption_service_to').datepicker('setStartDate', minDate);
    });

    $('#interruption_service_to').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        // todayHighlight: true,
        startDate: $('#date_of_joining_value').val(),
        endDate: $('#date_of_retirement_value').val(),
    });

    $('#extraordinary_leave_from').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        // todayHighlight: true,
        startDate: $('#date_of_joining_value').val(),
        endDate: $('#date_of_retirement_value').val(),
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#extraordinary_leave_to').datepicker('setStartDate', minDate);
    });

    $('#extraordinary_leave_to').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        // todayHighlight: true,
        startDate: $('#date_of_joining_value').val(),
        endDate: $('#date_of_retirement_value').val(),
    });

    $('#period_of_suspension_from').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        // todayHighlight: true,
        startDate: $('#date_of_joining_value').val(),
        endDate: $('#date_of_retirement_value').val(),
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#period_of_suspension_to').datepicker('setStartDate', minDate);
    });

    $('#period_of_suspension_to').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        // todayHighlight: true,
        startDate: $('#date_of_joining_value').val(),
        endDate: $('#date_of_retirement_value').val(),
    });

    $('#work_charged_service_from').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        // todayHighlight: true,
        startDate: $('#date_of_joining_value').val(),
        endDate: $('#date_of_retirement_value').val(),
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#work_charged_service_to').datepicker('setStartDate', minDate);
    });

    $('#work_charged_service_to').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        // todayHighlight: true,
        startDate: $('#date_of_joining_value').val(),
        endDate: $('#date_of_retirement_value').val(),
    });

    $('#boy_service_from').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        // todayHighlight: true,
        startDate: $('#date_of_joining_value').val(),
        endDate: $('#date_of_retirement_value').val(),
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#boy_service_to').datepicker('setStartDate', minDate);
    });

    $('#boy_service_to').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        // todayHighlight: true,
        startDate: $('#date_of_joining_value').val(),
        endDate: $('#date_of_retirement_value').val(),
    });


    $('#any_other_service_from').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        // todayHighlight: true,
        startDate: $('#date_of_joining_value').val(),
        endDate: $('#date_of_retirement_value').val(),
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#any_other_service_to').datepicker('setStartDate', minDate);
    });

    $('#any_other_service_to').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        // todayHighlight: true,
        startDate: $('#date_of_joining_value').val(),
        endDate: $('#date_of_retirement_value').val(),
    });

    $('#addition_of_qualifying_service_from').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        // todayHighlight: true,
        startDate: $('#date_of_joining_value').val(),
        endDate: $('#date_of_retirement_value').val(),
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#addition_of_qualifying_service_to').datepicker('setStartDate', minDate);
    });

    $('#addition_of_qualifying_service_to').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        // todayHighlight: true,
        startDate: $('#date_of_joining_value').val(),
        endDate: $('#date_of_retirement_value').val(),
    });

    $('#life_time_arrear_from').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        // todayHighlight: true,
        startDate: $('#date_of_joining_value').val(),
        endDate: $('#date_of_retirement_value').val(),
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#life_time_arrear_to').datepicker('setStartDate', minDate);
    });

    $('#life_time_arrear_to').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        // todayHighlight: true,
        startDate: $('#date_of_joining_value').val(),
        endDate: $('#date_of_retirement_value').val(),
    });

    $(document).on('change', '.interruption_service_from', function() {
        let period_from = $(this).val();
        let attr_id = $(this).attr('id');
        let fieldname = $(this).data('fieldname');

        let period_to = $('#' + fieldname + '_to').val();

        if(period_to != '') {
            $('.page-loader').addClass('d-flex');
            get_year_month_day(period_from, period_to, fieldname, year_month_day_url);
        }
    });

    $(document).on('change', '.interruption_service_to', function() {
        let period_to = $(this).val();
        let attr_id = $(this).attr('id');
        let fieldname = $(this).data('fieldname');

        let period_from = $('#' + fieldname + '_from').val();

        if(period_from != '') {
            $('.page-loader').addClass('d-flex');
            get_year_month_day(period_from, period_to, fieldname, year_month_day_url);
        } else {
            $(this).val('');
            swal('', 'Please select period from date first', 'error');
        }
    });


    $(document).on('change', '.extraordinary_leave_from', function() {
        let period_from = $(this).val();
        let attr_id = $(this).attr('id');
        let fieldname = $(this).data('fieldname');

        let period_to = $('#' + fieldname + '_to').val();

        if(period_to != '') {
            $('.page-loader').addClass('d-flex');
            get_year_month_day(period_from, period_to, fieldname, year_month_day_url);
        }
    });

    $(document).on('change', '.extraordinary_leave_to', function() {
        let period_to = $(this).val();
        let attr_id = $(this).attr('id');
        let fieldname = $(this).data('fieldname');

        let period_from = $('#' + fieldname + '_from').val();

        if(period_from != '') {
            $('.page-loader').addClass('d-flex');
            get_year_month_day(period_from, period_to, fieldname, year_month_day_url);
        } else {
            $(this).val('');
            swal('', 'Please select period from date first', 'error');
        }
    });

    $(document).on('change', '.period_of_suspension_from', function() {
        let period_from = $(this).val();
        let attr_id = $(this).attr('id');
        let fieldname = $(this).data('fieldname');

        let period_to = $('#' + fieldname + '_to').val();

        if(period_to != '') {
            $('.page-loader').addClass('d-flex');
            get_year_month_day(period_from, period_to, fieldname, year_month_day_url);
        }
    });

    $(document).on('change', '.period_of_suspension_to', function() {
        let period_to = $(this).val();
        let attr_id = $(this).attr('id');
        let fieldname = $(this).data('fieldname');

        let period_from = $('#' + fieldname + '_from').val();

        if(period_from != '') {
            $('.page-loader').addClass('d-flex');
            get_year_month_day(period_from, period_to, fieldname, year_month_day_url);
        } else {
            $(this).val('');
            swal('', 'Please select period from date first', 'error');
        }
    });


    $(document).on('change', '.work_charged_service_from', function() {
        let period_from = $(this).val();
        let attr_id = $(this).attr('id');
        let fieldname = $(this).data('fieldname');

        let period_to = $('#' + fieldname + '_to').val();

        if(period_to != '') {
            $('.page-loader').addClass('d-flex');
            get_year_month_day(period_from, period_to, fieldname, year_month_day_url);
        }
    });

    $(document).on('change', '.work_charged_service_to', function() {
        let period_to = $(this).val();
        let attr_id = $(this).attr('id');
        let fieldname = $(this).data('fieldname');

        let period_from = $('#' + fieldname + '_from').val();

        if(period_from != '') {
            $('.page-loader').addClass('d-flex');
            get_year_month_day(period_from, period_to, fieldname, year_month_day_url);
        } else {
            $(this).val('');
            swal('', 'Please select period from date first', 'error');
        }
    });

    $(document).on('change', '.boy_service_from', function() {
        let period_from = $(this).val();
        let attr_id = $(this).attr('id');
        let fieldname = $(this).data('fieldname');

        let period_to = $('#' + fieldname + '_to').val();

        if(period_to != '') {
            $('.page-loader').addClass('d-flex');
            get_year_month_day(period_from, period_to, fieldname, year_month_day_url);
        }
    });

    $(document).on('change', '.boy_service_to', function() {
        let period_to = $(this).val();
        let attr_id = $(this).attr('id');
        let fieldname = $(this).data('fieldname');

        let period_from = $('#' + fieldname + '_from').val();

        if(period_from != '') {
            $('.page-loader').addClass('d-flex');
            get_year_month_day(period_from, period_to, fieldname, year_month_day_url);
        } else {
            $(this).val('');
            swal('', 'Please select period from date first', 'error');
        }
    });

    $(document).on('change', '.any_other_service_from', function() {
        let period_from = $(this).val();
        let attr_id = $(this).attr('id');
        let fieldname = $(this).data('fieldname');

        let period_to = $('#' + fieldname + '_to').val();

        if(period_to != '') {
            $('.page-loader').addClass('d-flex');
            get_year_month_day(period_from, period_to, fieldname, year_month_day_url);
        }
    });

    $(document).on('change', '.any_other_service_to', function() {
        let period_to = $(this).val();
        let attr_id = $(this).attr('id');
        let fieldname = $(this).data('fieldname');

        let period_from = $('#' + fieldname + '_from').val();

        if(period_from != '') {
            $('.page-loader').addClass('d-flex');
            get_year_month_day(period_from, period_to, fieldname, year_month_day_url);
        } else {
            $(this).val('');
            swal('', 'Please select period from date first', 'error');
        }
    });

    $(document).on('change', '.addition_of_qualifying_service_from', function() {
        let period_from = $(this).val();
        let attr_id = $(this).attr('id');
        let fieldname = $(this).data('fieldname');

        let period_to = $('#' + fieldname + '_to').val();

        if(period_to != '') {
            $('.page-loader').addClass('d-flex');
            get_year_month_day(period_from, period_to, fieldname, year_month_day_url);
        }
    });

    $(document).on('change', '.addition_of_qualifying_service_to', function() {
        let period_to = $(this).val();
        let attr_id = $(this).attr('id');
        let fieldname = $(this).data('fieldname');

        let period_from = $('#' + fieldname + '_from').val();

        if(period_from != '') {
            $('.page-loader').addClass('d-flex');
            get_year_month_day(period_from, period_to, fieldname, year_month_day_url);
        } else {
            $(this).val('');
            swal('', 'Please select period from date first', 'error');
        }
    });


    //input validations

    $('#life_time_arrear_pension_amount').keyup(function () { 
        this.value = this.value.replace(/[^0-9\ ]/g,'');
    });

    $('#emolument_last_basic_pay_value').keyup(function () { 
        this.value = this.value.replace(/[^0-9\ ]/g,'');
    });

    $(document).on('focusout', '#emolument_last_basic_pay_value', function() {
        var last_basic_pay = $(this).val();

        if(last_basic_pay > 0) {
            $('.page-loader').addClass('d-flex');
            var $max_completed_years =  $('#total_qualifying_max_completed_half_years_value').val();

            var net_qualifying_years = $('#net_qualifying_service_year_value').val();
            var net_qualifying_months = $('#net_qualifying_service_month_value').val();
            var net_qualifying_days = $('#net_qualifying_service_days_value').val();

            $('#emolument_last_basic_pay_value-error').hide();
            calculate_service_pension(parseInt($max_completed_years));
            calculate_commutation_pension();
            calculate_dcr_gratuity(net_qualifying_years, net_qualifying_months, net_qualifying_days);

            setTimeout(function() { 
                $('.page-loader').removeClass('d-flex');
            }, 2000);
        } else {
            $('#emolument_last_basic_pay_value-error').text('Please enter greater than zero').show();
        }
    });
    
    $('#application-form-3').validate({
        ignore: false,
        rules: {
            "emolument_last_basic_pay_value": {
                required: true,
                onlyNumber: true,
                maxlength: 7,
            },
            "add_recovery[0][label]": {
                required: true,
                maxlength: 40,
            },
            "add_recovery[0][value]": {
                required: true,
                onlyNumber: true,
                maxlength: 7,
            },
        },
        messages: {
           "emolument_last_basic_pay_value": {
                required: "Please enter last basic pay",
            },
            "add_recovery[0][label]": {
                required: 'Please enter recovery label',
            },
            "add_recovery[0][value]": {
                required: 'Please enter recovery value',
            },
          },
        submitHandler: function(form, event) { 
            event.preventDefault();
            $('.page-loader').addClass('d-flex');
            form.submit();
        },
        errorPlacement: function(label, element) {
            label.addClass('text-danger');
            label.insertAfter(element);
        },
        highlight: function(element, errorClass) {
            $(element).parent().addClass('has-danger')
            $(element).addClass('form-control-danger')
        }
    });
});

function get_year_month_day(from, to, fieldname, year_month_day_url) {
    $.ajax({
        url: year_month_day_url,
        type:'post',
        data:'from='+from+'&to='+to,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success:function(result) {
            $('#'+fieldname+'_year').text(result.years);
            $('#'+fieldname+'_month').text(result.months);
            $('#'+fieldname+'_days').text(result.days);

        
            $('#'+fieldname+'_year_value').val(result.years);
            $('#'+fieldname+'_month_value').val(result.months);
            $('#'+fieldname+'_days_value').val(result.days);

            var years = 0;
            var months = 0;
            var days = 0;

            $('.non-qualifying-service-periods-year').each(function() {
                if($(this).text() != '') {
                    years = Math.abs(parseInt(years) + parseInt($(this).text()));
                }
            });

            $('.non-qualifying-service-periods-month').each(function() {
                if($(this).text() != '') {
                    months = Math.abs(parseInt(months) + parseInt($(this).text()));
                }
            });

            $('.non-qualifying-service-periods-days').each(function() {
                if($(this).text() != '') {
                    days = Math.abs(parseInt(days) + parseInt($(this).text()));
                }
            });

            var gross_years = $('#gross_qualifying_year_value').val();
            var gross_months = $('#gross_qualifying_month_value').val();
            var gross_days = $('#gross_qualifying_days_value').val();

            var qualifying_service_years = Math.abs(parseInt(gross_years) - parseInt(years));
            var qualifying_service_months = Math.abs(parseInt(gross_months) - parseInt(months));
            var qualifying_service_days = Math.abs(parseInt(gross_days) - parseInt(days));

            $('.non-qualifying-service-periods-total-year').text(years);
            $('.non-qualifying-service-periods-total-month').text(months);
            $('.non-qualifying-service-periods-total-days').text(days);

            $('.total_non_qualifying_years').val(years);
            $('.total_non_qualifying_months').val(months);
            $('.total_non_qualifying_days').val(days);

            $('#qualifying_service_period_year').text(qualifying_service_years);
            $('#qualifying_service_period_month').text(qualifying_service_months);
            $('#qualifying_service_period_days').text(qualifying_service_days);

            $('#qualifying_service_period_year_value').val(qualifying_service_years);
            $('#qualifying_service_period_month_value').val(qualifying_service_months);
            $('#qualifying_service_period_days_value').val(qualifying_service_days);

            var addition_of_qualifying_service_years = $('#addition_of_qualifying_service_year').text();
            var addition_of_qualifying_service_months = $('#addition_of_qualifying_service_month').text();
            var addition_of_qualifying_service_days = $('#addition_of_qualifying_service_days').text();

            var net_qualifying_years = Math.abs(parseInt(qualifying_service_years) + parseInt(addition_of_qualifying_service_years));
            var net_qualifying_months = Math.abs(parseInt(qualifying_service_months) + parseInt(addition_of_qualifying_service_months));
            var net_qualifying_days = Math.abs(parseInt(qualifying_service_days) + parseInt(addition_of_qualifying_service_days));

            $('#net_qualifying_service_year').text(net_qualifying_years);
            $('#net_qualifying_service_month').text(net_qualifying_months);
            $('#net_qualifying_service_days').text(net_qualifying_days);

            $('#net_qualifying_service_year_value').val(net_qualifying_years);
            $('#net_qualifying_service_month_value').val(net_qualifying_months);
            $('#net_qualifying_service_days_value').val(net_qualifying_days);

            calculate_completed_half_years(net_qualifying_years, net_qualifying_months, net_qualifying_days);
            calculate_commutation_pension();
            calculate_dcr_gratuity(net_qualifying_years, net_qualifying_months, net_qualifying_days);

            $('.page-loader').removeClass('d-flex');
        }
    });
}

function calculate_completed_half_years(years, months, days) {
    var $total_completed_years = 0;
    var $max_completed_years = 0;
    var $completed_half_years = years * 2;

    if($completed_half_years >= 50) {
        $max_completed_years = 50;

        if(months < 3) {
            $total_completed_years = $completed_half_years;
        } else if((months > 3 && months < 9) || (months == 3 && days > 1)) {

            $total_completed_years = parseInt($completed_half_years) + 1;

        } else if(months < 9) {

            $total_completed_years = $completed_half_years + 1;

        } else if( (months > 9) || (months == 9 && days > 1)) {

            $total_completed_years = parseInt($completed_half_years) + 2;
        }

        if($total_completed_years >= 66)  {
            $total_completed_years = 66;
        }

    } else {
        if(months < 3) {
            $total_completed_years = parseInt($completed_half_years);
        } else if((months > 3 && months < 9) || (months == 3 && days > 1)) {

            $total_completed_years = parseInt($completed_half_years) + 1;

        } else if(months < 9) {

            $total_completed_years = parseInt($completed_half_years) + 1;

        } else if( (months > 9) || (months == 9 && days > 1)) {

            $total_completed_years = parseInt($completed_half_years) + 2;
        }

        $max_completed_years = $total_completed_years;
    }

    $('#total_qualifying_completed_half_years').text($total_completed_years + ' Six-monthly period.');
    $('#total_qualifying_completed_half_years_value').val($total_completed_years);
    $('#total_qualifying_max_completed_half_years_value').val($max_completed_years);

    calculate_service_pension(parseInt($max_completed_years));
}

// function for calculate the service function
function calculate_service_pension($total_completed_years) {
    var $last_basic_pay = $('#emolument_last_basic_pay_value').val();
    var $service_pension = 0;
    
    $service_pension = (parseInt($last_basic_pay) / 2) * ($total_completed_years/50);

    $('#service_pension_value').val($service_pension);
    $('#service_pension').text($.number($service_pension) + '/-');
    $('#enhanced_family_pension').text($.number($service_pension) + '/-');
    $('#enhanced_family_pension').val($service_pension);
}

// function for commutation related calculation
function calculate_commutation_pension() {
    var commutation_percentage = $('#commuted_percentage_value').val();
    var service_pension =  $('#service_pension_value').val();
    var commutation_amount_of_pension = 0;

    // Calculation Commutation Amount of Pension
    commutation_amount_of_pension = (parseInt(service_pension) * parseInt(commutation_percentage)) / 100;

    $('#commuted_amount_pension_value').val(commutation_amount_of_pension);
    $('#commuted_amount_pension').text($.number(commutation_amount_of_pension) + '/-');

    // Calculation Commutation Value of Pension
    var commutation_ratio = $('#commuted_factor_ratio_value').val();
    var commutation_pension_value = 0;

    var commutation_value_of_pension_cal = '['+ commutation_amount_of_pension +' * '+ commutation_ratio +' * 12]';
    $('#commutation_value_of_pension_cal').text(commutation_value_of_pension_cal);

    commutation_pension_value = Math.ceil(commutation_amount_of_pension * commutation_ratio * 12);

    $('#commuted_value_of_pension_value').val(commutation_pension_value);
    $('#commuted_value_of_pension').text($.number(commutation_pension_value) + '/-');

    //Residuary Pension after Commutaion
    var residuary_pension_amount = 0;

    residuary_pension_amount = service_pension - commutation_amount_of_pension;

    $('#residuary_pension_commutation_value').val(residuary_pension_amount);
    $('#residuary_pension_commutation').text($.number(residuary_pension_amount) + '/-');
    $('#enhanced_normal_family_pension').text($.number(residuary_pension_amount) + '/-');
    $('#normal_family_pension').val(residuary_pension_amount);
}

function calculate_dcr_gratuity(net_qualifying_years, net_qualifying_months, net_qualifying_days) {
    var completed_half_years = net_qualifying_years * 2;
    var total_dcr_gratuity = 0;
    var dcr_completed_years = 0;
    var last_basic_pay = $('#emolument_last_basic_pay_value').val();
    var da_percentage = $('#da_percentage_value').val();
    var total_da_amount= 0;

    if(completed_half_years >= 66) {
        dcr_completed_years = 66;
    } else {
        if(net_qualifying_months < 3) {

            dcr_completed_years = completed_half_years;

        } else if((net_qualifying_months > 3 && net_qualifying_months < 9) || (net_qualifying_months == 3 && net_qualifying_days > 1)) {

            dcr_completed_years = completed_half_years + 1;

        } else if(net_qualifying_months < 9) {

            dcr_completed_years = completed_half_years + 1;

        } else if( (net_qualifying_months > 9) || (net_qualifying_months == 9 && net_qualifying_days > 1)) {

            dcr_completed_years = completed_half_years + 2;
        }
    }

    total_da_amount = (last_basic_pay * da_percentage) / 100;

    $('#total_da_amount_value').val(total_da_amount);

    total_dcr_gratuity = ((parseInt(last_basic_pay) + parseInt(total_da_amount)) * 1/4 * parseInt(dcr_completed_years));

    $('#total_dcr_gratuity_amount').text($.number(total_dcr_gratuity) + '/-');
}
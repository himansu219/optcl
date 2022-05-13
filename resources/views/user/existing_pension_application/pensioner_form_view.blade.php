@extends('user.layout.layout')

@section('section_content')
<style type="text/css">
    #upload-demo{
        width: 450px;
        height: 300px;
        padding-bottom:25px;
    }
</style>
<div class="content-wrapper">
    <nav aria-label="breadcrumb" role="navigation">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('user_dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">View Details</li> 
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <img src="{{url('public')}}/images/logo_1.png" alt="image" class="brand_logo_1" />
                    <img src="{{url('public')}}/images/logo_2.png" alt="image" class="brand_logo_2" />
                    <h4 class="card-title align_center mb-2">ODISHA POWER TRANSMISSION CORPORATION LTD.</h4>
                    <h5 class="card-description align_center mb-1">(A Govt. of Odisha Undertaking)</h5>
                    <h5 class="card-description align_center mb-1">Gridco Pension Trust Fund</h5>
                    <p class="card-description align_center mb-1">Regd. Off – Janpath, Bhubaneswar – 751022</p>
                    
                    <div class="employe-code-check">
                        
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="home-1" role="tabpanel" aria-labelledby="home-tab">
                                <div class="media">
                                    <div class="media-body">
                                        
                                        <h6 class="text-center-normal font-weight-bold mb-25">PARTICULARS OF EX-EMPLOYEE</h6>
                                        <hr>
                                        <table class="table table-bordered perticular-table-details-page">
                                        <tr>
                                                <th width="20%">Tax Type</th>
                                                <td width="30%">{{ $pensionerDetails->type_name }}</td>
                                                <th width="20%"></th>
                                                <td width="30%"></td>
                                            </tr>
                                            <tr>
                                                <th width="20%">Pensioner Type</th>
                                                <td width="30%">{{ $pensionerDetails->pension_type }}</td>
                                                <th width="20%">PPO No</th>
                                                <td width="30%">{{ $pensionerDetails->old_ppo_no }}</td>
                                            </tr>
                                            <tr>
                                                <th>Attached PPO File</th>
                                                <td><a href="{{ url('/').'/'.$pensionerDetails->old_ppo_attachment }}" target="_blank"><i class="fa fa-file-pdf-o mr-2"></i>Attachment File</a></td>
                                                <th>New PPO No</th>
                                                <td>{{ $pensionerDetails->new_ppo_no }}</td>
                                            </tr>
                                            <tr>
                                                <th>Pensioner Name</th>
                                                <td>{{ $pensionerDetails->pensioner_name }}</td>
                                                <th>Mobile No</th>
                                                <td>{{ $pensionerDetails->mobile_number ? $pensionerDetails->mobile_number : 'NA' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Aadhaar No.</th>
                                                <td>{{ $pensionerDetails->aadhar_no ? $pensionerDetails->aadhar_no : 'NA' }}</td>
                                                <th>Employee Code</th>
                                                <td>{{ $pensionerDetails->employee_code ? $pensionerDetails->employee_code : 'NA' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Gender</th>
                                                <td>{{ $pensionerDetails->gender_name }}</td>
                                                <th>Designation</th>
                                                <td>{{ $pensionerDetails->designation_name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Date of Birth</th>
                                                <td>{{ $pensionerDetails->date_of_birth ? date('d/m/Y', strtotime($pensionerDetails->date_of_birth)) : 'NA' }}</td>
                                                <th>Date of Retirement</th>
                                                <td>{{ $pensionerDetails->date_of_retirement ? date('d/m/Y', strtotime($pensionerDetails->date_of_retirement)) : 'NA' }}</td>
                                            </tr>
                                            @if($pensionerDetails->pensioner_type == 2)
                                            <tr>
                                                <th>Date of Death</th>
                                                <td>{{ $pensionerDetails->date_of_death }}</td>
                                                <th></th>
                                                <td></td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <th>Basic Pension Amount</th>
                                                <td>{{ $pensionerDetails->basic_amount }}</td>
                                                <th>Basic Pension Effective Date</th>
                                                <td>{{ $pensionerDetails->basic_effective_date ? date('d/m/Y', strtotime($pensionerDetails->basic_effective_date)) : 'NA' }}</td>
                                            </tr>
                                            <tr>                                                
                                                <th>Additional Pension Amount</th>
                                                <td>{{ $pensionerDetails->additional_pension_amount ? $pensionerDetails->additional_pension_amount:"0" }}</td>
                                                <th></th>
                                                <td></td>
                                            </tr>
                                            @if($pensionerDetails->pensioner_type == 2)
                                            <tr>
                                                <th>Enhanced Pension Amount</th>
                                                <td>{{ $pensionerDetails->enhanced_pension_amount ? $pensionerDetails->enhanced_pension_amount:"0" }}</td>
                                                <th>End Date</th>
                                                <td>{{ $pensionerDetails->enhanced_pension_end_date }}</td>
                                            </tr>
                                            <tr>
                                                <th>Normal Pension Amount</th>
                                                <td>{{ $pensionerDetails->normal_pension_amount }}</td>
                                                <th>Effective Date</th>
                                                <td>{{ $pensionerDetails->normal_pension_effective_date ? date('d/m/Y', strtotime($pensionerDetails->normal_pension_effective_date)) : 'NA'  }}</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <th>Category</th>
                                                <td>{{ $pensionerDetails->category_name }}</td>
                                                <th>TI Amount(Percentage)</th>
                                                <td>{{ $pensionerDetails->ti_amount.' ('.$pensionerDetails->ti_percentage.'%)' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Name of the Bank</th>
                                                <td>{{ $pensionerDetails->bank_name }}</td>
                                                <th>Name Address of the Branch</th>
                                                <td>{{ $pensionerDetails->branch_name }}</td>
                                            </tr>
                                            <tr>
                                                <th>IFSC Code</th>
                                                <td>{{ $pensionerDetails->ifsc_code }}</td>
                                                <th>MICR Code</th>
                                                <td>{{ $pensionerDetails->micr_code }}</td>
                                            </tr>
                                            <tr>
                                                <th>Savings Bank A/C No.</th>
                                                <td>{{ $pensionerDetails->acc_number }}</td>
                                                <th></th>
                                                <td></td>
                                            </tr>
                                            @if($pensionerDetails->pensioner_type == 2)
                                            <tr>
                                                <th colspan="4" class="text-center">Family Pensioner Details</th>
                                            </tr>
                                            <tr>
                                                <th>Relation Type</th>
                                                <td>{{ $pensionerDetails->relation_name }}</td>
                                                <th>Current Status</th>
                                                <td>{{ $pensionerDetails->relation_status_name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Family Pensioner Name</th>
                                                <td>{{ $pensionerDetails->nominee_name }}</td>
                                                <th>Family Pensioner Mobile No.</th>
                                                <td>{{ $pensionerDetails->nominee_mobile }}</td>
                                            </tr>
                                            <tr>
                                                <th>Family Pensioner Aadhaar No.</th>
                                                <td>{{ $pensionerDetails->nominee_aadhar }}</td>
                                                <th>Family Pensioner Date of Birth</th>
                                                <td>{{ $pensionerDetails->nominee_dob ? date('d/m/Y', strtotime($pensionerDetails->nominee_dob)) : 'NA' }}</td>
                                            </tr>
                                            @endif
                                            <!-- <tr>
                                                <th colspan="4" class="text-center form-middle-heading">Gross Pension</th>
                                            </tr>
                                            <tr>
                                                <th>Gross Pension Amount</th>
                                                <td>{{ $pensionerDetails->gross_pension_amount }}</td>
                                                <th></th>
                                                <td></td>
                                            </tr> -->
                                        </table>
                                        
                                        <h6 class="text-center-normal form-middle-heading">Commutation</h6>
                                        <table class="table table-bordered mt-2">
                                            <thead>
                                                <!-- <tr>
                                                    <th colspan="2" class="text-center form-middle-heading">Commutation</th>
                                                </tr> -->
                                                <tr>
                                                    <th>Commutation Amount</th>
                                                    <th>Commutation End Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($commutation_list as $key => $commutation_data)
                                                    <tr>
                                                        <td>{{ number_format($commutation_data->commutation_amount, 2) }}</td>
                                                        <td>{{ $commutation_data->commutation_end_date ? date('d/m/Y', strtotime($commutation_data->commutation_end_date)) : 'NA' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        
                                        <table class="table table-bordered perticular-table-details-page mt-4">
                                            <tr>
                                                <th width="20%">Gross Pension Amount</th>
                                                <td width="80%">{{ number_format($pensionerDetails->gross_pension_amount,2) }}</td>
                                            </tr>
                                            <tr>
                                                <th width="20%">Total Income Amount</th>
                                                <td width="80%">{{ number_format($pensionerDetails->total_income,2) }}</td>
                                            </tr>
                                            @if($pensionerDetails->is_taxable_amount_generated == 1)
                                            <tr>
                                                <th width="20%">Taxable Amount</th>
                                                <td width="80%">{{ number_format($pensionerDetails->taxable_amount, 2) }}</td>
                                            </tr>
                                            @endif
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- content-wrapper ends -->
<div class="modal fade" id="crop_image" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Crop the image</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <input type="hidden" id="file_name" name="file_name" value="">
                    <!-- <img id="image"> -->
                    <div id="upload-demo" class="center-block"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="crop">Crop</button>
            </div>
        </div>
    </div>    
</div>

 @endsection
 @section('page-script')

  <script type="text/javascript">
    $(document).ready(function() {
        $('.datepickerClass').datepicker({
            autoclose: true,
        });
        $('.sidebar ul.nav li a').click(function(e) {
            $(this).addClass('step_active').removeClass('step_inactive');
            //$(this).closest('.nav-link').removeClass("step_active").addClass('step_inactive');
        });

        $.validator.addMethod("onlyNumber", function (value, element) {
            return this.optional(element) || /^[0-9\s-]*$/.test(value);
        }, "Please use only numbers.");

        // for block letter in name field
        $("#pensioner_name").on("keyup",function(){
        this.value = this.value.toUpperCase();
        }); 
        
       

       // Append area
       $('#commutation_add_button').on('click',function(){
            var commutation_count = $("#commutation_count").val();            
            if(commutation_count < 3){
                   
                
                //console.log($('#pension_form').valid());

                if($('#pension_form').valid()) {
                    // Updating the row count
                    var totRow = parseFloat(commutation_count)+1;
                    $("#commutation_count").val(totRow); 
                    $('#commutation_table_id').append('<tr><td>'+
                        '<input type="text" class="form-control commutation_amount_class amount_type" id="commutation_amount_'+totRow+'" name="commutation_amount['+totRow+']" placeholder="Commutation Amount">'+
                        '<label id="commutation_amount_'+totRow+'-error" class="error text-danger" for="commutation_amount_'+totRow+'"></label>'+
                        '</td>'+
                        '<td>'+
                            '<div id="" class="input-group date datepicker-from-current">'+
                                '<input type="text" class="form-control commutation_amount_end_date_class" id="commutation_amount_end_date_'+totRow+'" name="commutation_amount_end_date['+totRow+']" placeholder="Commutation End Date" readonly>'+
                                '<span class="input-group-addon input-group-append border-left">'+
                                    '<span class="mdi mdi-calendar input-group-text"></span>'+
                                '</span>'+
                            '</div>'+
                            '<label id="commutation_amount_end_date_'+totRow+'-error" class="error text-danger" for="commutation_amount_end_date_'+totRow+'"></label>'+
                        '</td>'+
                        '<td>'+
                            '<button type="button" class="btn btn-primary btn-next mr-2 commutation_remove_button" >Remove</button>'+
                        '</td>'+
                    '</tr>');
                    var endDate = new Date();
                    $('.datepicker-from-current').datepicker({
                      enableOnReadonly: true,
                      todayHighlight: true,
                      autoclose: true,
                      format: 'dd/mm/yyyy',
                      startDate: endDate,
                    });
                    validate_commutation();
                }
            }            
       });

        function validate_commutation(){
            $('.commutation_amount_class').each(function() {
                $(this).rules("add", {
                    required: true,
                    amount_only:true
                    messages: {
                        required: "Please enter commutation amount",
                    }
                });
            });

            $('.commutation_amount_end_date_class').each(function() {
                $(this).rules("add", {
                    required: true,
                    messages: {
                        required: "Please enter commutation end date",
                    }
                });
            });
        }

        $("#commutation_table_id").on('click','.commutation_remove_button',function(){
            var commutation_count = $("#commutation_count").val();
            $("#commutation_count").val(parseFloat(commutation_count)-1);
            $(this).closest('tr').remove();
        });

        function calculate_gross_amount(){
            //$("#submit_form_1").attr('disabled',true);
            var pesioner_type = $("#pesioner_type").val();
            var basic_pension_amount = $("#basic_pension_amount").val();
            var gross_amount = 0;
            var additional_pension_amount = $("#additional_pension_amount").val();
            var hidden_ti_amount = $("#hidden_ti_amount").val();
            if(pesioner_type == 'SP'){
                //console.log('one');
                // Service Pension
                if(basic_pension_amount !="" && additional_pension_amount !="" && hidden_ti_amount !=""){
                    //console.log('two');
                    gross_amount = parseFloat(basic_pension_amount)+parseFloat(additional_pension_amount)+parseFloat(hidden_ti_amount);
                }else{
                    //console.log('three');
                    gross_amount = 0
                }                
            } else if(pesioner_type == 'FM'){
                // Family Pension
                var dor = $('#dor').val();
                var dob = $("#dob").val();
                var date_of_death = $("#date_of_death").val();

                $.post("{{ route('get_family_pension_pension_amount_details') }}",{
                    "_token": "{{ csrf_token() }}",
                    dob:dob,
                    dor:dor,
                    date_of_death:date_of_death,
                    basic_pension_amount:basic_pension_amount,
                },function(response){
                    $('.page-loader').removeClass('d-flex');
                    console.log(response);
                    var obj = JSON.parse(response);
                    enhanced_pension_amount = obj.enhanced_pension_amount;
                    normal_pension_amount = obj.normal_pension_amount;
                    $("#enhanced_pension_amount").val(enhanced_pension_amount);
                    $("#normal_pension_amount").val(normal_pension_amount);
                });
                var calculation_pension_amount = 0;
                if(basic_pension_amount !="" && additional_pension_amount !="" && hidden_ti_amount !=""){
                    if(enhanced_pension_amount > 0){
                        calculation_pension_amount = enhanced_pension_amount;
                    }else{
                        calculation_pension_amount = normal_pension_amount;
                    }

                    gross_amount = parseFloat(calculation_pension_amount)+parseFloat(additional_pension_amount)+parseFloat(hidden_ti_amount);
                }else{
                    //console.log('three');
                    gross_amount = 0
                }
            }else {
                // In case of blank value
                gross_amount = 0;
            }
            $("#gross_pension").val(gross_amount);
            //$("#submit_form_1").attr('disabled',false);
        }


        
        $("#bank_name").on('change', function(){
            $('.page-loader').addClass('d-flex');
            var bank_id = $(this).val();
            $.post("{{ route('get_branch') }}",{
                "_token": "{{ csrf_token() }}",
                bank_id:bank_id
            },function(response){
                $('.page-loader').removeClass('d-flex');
                $("#branch_name_address").html(response);
                $("#ifsc_code").val("");
                $("#micr_code").val("");
            });
        });

        $("#relation_type").on('change', function(){
            $('.page-loader').addClass('d-flex');
            var relation_type = $(this).val();
            $.post("{{ route('existing_pensioner_get_relation_type') }}",{
                "_token": "{{ csrf_token() }}",
                relation_type:relation_type
            },function(response){
                $('.page-loader').removeClass('d-flex');
                $("#current_status").html(response);
            });
        });

        $("#branch_name_address").on('change',function(){
            var bank_branch_id = $(this).val();
            $('.page-loader').addClass('d-flex');
            $.post("{{ route('get_branch_details') }}",{
                "_token": "{{ csrf_token() }}",
                bank_branch_id:bank_branch_id
            },function(response){
                $('.page-loader').removeClass('d-flex');
                var obj = JSON.parse(response);
                $("#ifsc_code").val(obj.ifsc_code);
                $("#micr_code").val(obj.micr_code);
                $("#ifsc_code").valid();
            });
        });

        $("#relation_type_div").hide();
        $("#current_status_div").hide();
        $("#closing_date_div").hide();
        $("#date_of_death_div").hide();
        $("#attached_date_of_death_certificate_div").hide();

        $("#enhanced_pension_amount_div").hide();
        $("#enhanced_pension_end_date_div").hide();
        $("#normal_pension_amount_div").hide();
        $("#normal_pension_effective_date_div").hide();
        $("#fam_title_h6").hide();
        $("#family_hr_line").hide();
        $("#fam_pen_name_div").hide();
        $("#fam_pen_mob_div").hide();
        $("#fam_pen_aadhar_div").hide();
        $("#fam_pen_dob_div").hide();
        $("#rel_cur_status_end_date_div").hide();

        $("#pesioner_type").on('change', function(){
            var pesioner_type = $(this).val();
            if(pesioner_type == "FM"){
                $("#relation_type_div").show();
                $("#current_status_div").show();
                $("#closing_date_div").show();
                $("#date_of_death_div").show();
                $("#attached_date_of_death_certificate_div").show();
                // -------------------
                $("#enhanced_pension_amount_div").show();
                $("#enhanced_pension_end_date_div").show();
                $("#normal_pension_amount_div").show();
                $("#normal_pension_effective_date_div").show();
                $("#fam_title_h6").show();
                $("#family_hr_line").show();
                $("#fam_pen_name_div").show();
                $("#fam_pen_mob_div").show();
                $("#fam_pen_aadhar_div").show();
                $("#fam_pen_dob_div").show();
                //$("#rel_cur_status_end_date_div").show();
                // Clear Data
                $("#gross_pension").val("");
                $("#dob").val("");
                $("#age_year").val("");
                $("#age_month").val("");
                $("#age_days").val("");
                $("#age_dob").html("");
                $("#dor").val("");
                $("#date_of_death").val("");
                $("#basic_pension_amount").val("");
                $("#basic_pension_effective_date").val("");
                $("#additional_pension_amount").val("");
                $("#enhanced_pension_amount").val("");
                $("#enhanced_pension_end_date").val("");
                $("#normal_pension_amount").val("");
                $("#normal_pension_effective_date").val("");
                $("#ti_category_id").val("");
                $("#ti_amount").val("");
                $("#hidden_ti_amount").val("");
                $("#hidden_ti_percentage").val("");
            }else{                
                $("#relation_type_div").hide();
                $("#current_status_div").hide();
                $("#closing_date_div").hide();
                $("#date_of_death_div").hide();
                $("#attached_date_of_death_certificate_div").hide();
                // -------------------
                $("#enhanced_pension_amount_div").hide();
                $("#enhanced_pension_end_date_div").hide();
                $("#normal_pension_amount_div").hide();
                $("#normal_pension_effective_date_div").hide();
                $("#fam_title_h6").hide();
                $("#family_hr_line").hide();
                $("#fam_pen_name_div").hide();
                $("#fam_pen_mob_div").hide();
                $("#fam_pen_aadhar_div").hide();
                $("#fam_pen_dob_div").hide();
                //$("#rel_cur_status_end_date_div").hide();
                $("#gross_pension").val("");
                // Clear Data
                $("#gross_pension").val("");
                $("#dob").val("");
                $("#age_year").val("");
                $("#age_month").val("");
                $("#age_days").val("");
                $("#age_dob").html("");
                $("#dor").val("");
                $("#date_of_death").val("");
                $("#basic_pension_amount").val("");
                $("#basic_pension_effective_date").val("");
                $("#additional_pension_amount").val("");
                $("#enhanced_pension_amount").val("");
                $("#enhanced_pension_end_date").val("");
                $("#normal_pension_amount").val("");
                $("#normal_pension_effective_date").val("");
                $("#ti_category_id").val("");
                $("#ti_amount").val("");
                $("#hidden_ti_amount").val("");
                $("#hidden_ti_percentage").val("");
            }
            $(".js-example-basic-single").select2();
        });

        $("#current_status").on("change", function(){
            if($(this).val() == 9){
                $("#rel_cur_status_end_date_div").show();
            }else{
                $("#rel_cur_status_end_date_div").hide();
            }
        });

        $("#ti_category_id").on('change', function(){            
            var ti_category_id = $(this).val();
            var basic_amount = $("#basic_pension_amount").val();
            if(basic_amount != ""){
                $('.page-loader').addClass('d-flex');
                $.post('{{ route("category_ta_percentage_amount") }}',{
                    "_token": "{{ csrf_token() }}",
                    "ti_category_id":ti_category_id,
                    "basic_amount":basic_amount,
                },function(response){
                    $('.page-loader').removeClass('d-flex');
                    var resObj = JSON.parse(response);
                    $("#ti_amount").val(resObj.display_value);
                    $("#hidden_ti_amount").val(resObj.da_amount);
                    $("#hidden_ti_percentage").val(resObj.da_percentage); 
                });
            }
            setTimeout(calculate_gross_amount, 3000);
            //calculate_gross_amount();
        });

        $("#basic_pension_amount").on('change', function(){            
            var basic_amount = $(this).val();
            var ti_category_id = $("#ti_category_id").val();
            if(ti_category_id != "" && basic_amount != ""){
                $('.page-loader').addClass('d-flex');
                $.post('{{ route("category_ta_percentage_amount") }}',{
                    "_token": "{{ csrf_token() }}",
                    "ti_category_id":ti_category_id,
                    "basic_amount":basic_amount,
                },function(response){
                    $('.page-loader').removeClass('d-flex');
                    var resObj = JSON.parse(response);
                    $("#ti_amount").val(resObj.display_value);
                    $("#hidden_ti_amount").val(resObj.da_amount);
                    $("#hidden_ti_percentage").val(resObj.da_percentage);
                });
            }else{
                $("#ti_amount").val('');
            }
            setTimeout(calculate_gross_amount, 3000);
        });

        var $uploadCrop,
        rawImg,
        imageId;

        $('#crop_image').on('hidden.bs.modal', function(){
            var filename = $(this).closest('#crop_image').find($('#file_name'));
            var val = $(filename).val();

            $('#'+val).parent().find('.file-upload-info').val('');

            $('#'+val).val('');

            $('#upload-demo').croppie('destroy');
        });


        $('#attached_ppo_certificate').on('change', function() {
            check_upload_file(this, 'attached_ppo_certificate');
        });

        function check_upload_file(ele, id) {
            $(ele).parent().find('.form-control').val($(ele).val().replace(/C:\\fakepath\\/i, ''));

            $("#" + id + "-error").html("");
            
            var val = ele.value;

            if(val.indexOf('.') !== -1) {
                var ext = ele.value.match(/\.(.+)$/)[1];
                var size = ele.files[0].size;
                var file = ele.files[0];

                if(size > 5000000) {
                    $("#" + id + "-error").html('File size less than 5MB allowed');
                    $("#" + id + "-error").show();
                    ele.value = '';
                    $(ele).parent().find('.form-control').val('');
                } else {
                    switch (ext) {
                        /*case 'png':
                            $("#" + id + "-error").html('');
                            $("#" + id + "-error").hide();
                            break;
                        case 'jpg':
                            $("#" + id + "-error").html('');
                            $("#" + id + "-error").hide();
                            break;
                        case 'jpeg':
                            $("#" + id + "-error").html('');
                            $("#" + id + "-error").hide();
                            break;*/
                        case 'pdf':
                            $("#" + id + "-error").html('');
                            $("#" + id + "-error").hide();
                            break;
                        default:
                            $("#" + id + "-error").html('Please upload only pdf file');
                            $("#" + id + "-error").show();
                            ele.value = '';
                            $(ele).parent().find('.form-control').val('');
                    }

                    // $('#image').attr('src', $(ele).attr('src'));
                    $('#'+id+'_modal_canvas').attr('src', URL.createObjectURL(file));
                }
            } else {
                $("#" + id + "-error").html('Invalid file type');
                $("#" + id + "-error").show();
                ele.value = '';

                $(ele).parent().find('.form-control').val('');
            }
        }    

        $(document).on('click', '#crop', function() {
            $('.page-loader').addClass('d-flex'); 
            $('#crop_image').modal('hide');
            $uploadCrop.croppie('result', {
                type: 'blob',
                format: 'jpeg',
                size: {width: 150, height: 200}
            }).then(function (resp) {

                var formData = new FormData();
                var employee_id = $('#employee_id').val();
                var edit = $('#edit').val();
                var file_name = $('#file_name').val();

                var avatar = URL.createObjectURL(resp);

                formData.append(file_name, resp, 'avatar.jpg');
                formData.append('employee_id', employee_id);
                formData.append('edit', edit);
                formData.append('_token', "{{ csrf_token() }}");

                $.ajax({
                    url: "{{ route('save_existing_application') }}",
                    type: "POST",
                    data: formData,
                    contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
                    processData: false, // NEEDED, DON'T OMIT THIS
                    success:function(response) {
                       // $('#'+file_name).val('');
                        $('.page-loader').removeClass('d-flex');
                        $("#hidden_ppo_file").val(response);                        
                    },
                    error:function(response) {

                    }
                });
            });
        });


        $('.btn-next').click(function() {
            $('.nav-tabs > .active').next('li').find('a').trigger('click');
        });

        $('.btn-prev').click(function() {
            $('.nav-tabs > .active').prev('li').find('a').trigger('click');
        });

        $('#father_name').keyup(function () { 
          this.value = this.value.replace(/[^A-Za-z\ ]/g,'');
        });
        $('#husband_name').keyup(function () { 
          this.value = this.value.replace(/[^A-Za-z\ ]/g,'');
        });        

        $("#save_as_draft").on('click',function(e){
            e.preventDefault();
            //$('.pension_form_class').attr('id', 'pension_form_save_as_draft');

            //$('#pension_form_save_as_draft').submit();
            //console.log($('pension_form').serialize());
            //$("#pension_form").submit();
            var emp_code = $("#emp_code").val();
            var aadhaar_no = $("#aadhaar_no").val();
            var name = $("#name").val();
            var designation = $("#designation").val();
            var father_name = $("#father_name").val();
            var gender = $("#gender").val();
            var marital_status = $("#marital_status").val();
            var husband_name = $("#husband_name").val();
            var religion = $("#religion").val();
            var pf_acc_type = $("#pf_acc_type").val();
            var pf_acno = $("#pf_acno").val();
            var name_of_office_dept = $("#name_of_office_dept").val();
            var dob = $("#dob").val();
            var doj = $("#doj").val();
            var dor = $("#dor").val();
            //console.log(emp_code);

            $('.page-loader').addClass('d-flex');
            $.post('{{ route("existing_pensioner_form_save_as_draft") }}',{
                "_token": "{{ csrf_token() }}",
                "emp_code":emp_code,
                "aadhaar_no":aadhaar_no,
                "name":name,
                "designation":designation,
                "father_name":father_name,
                "gender":gender,
                "marital_status":marital_status,
                "husband_name":husband_name,
                "religion":religion,
                "pf_acc_type":pf_acc_type,
                "pf_acno":pf_acno,
                "name_of_office_dept":name_of_office_dept,
                "dob":dob,
                "doj":doj,
                "dor":dor,
            },function(response){
                $('.page-loader').removeClass('d-flex');
                if(response.status == 'success'){
                    location.reload();
                }
            });
        });
        
        $(".js-example-basic-single").on('change', function() {
            $(this).valid();
        });

        $("#doj").on('change', function() {
            $(this).valid();
            //$('#dor').valid();
        });
        $("#dor").on('change', function() {
            $(this).valid();
            //$('#doj').valid();            
        });
        $("#dob").on('change', function() {
            $(this).valid();
            $('#doj').val('');
            $('#dor').val('');
            var dob = $(this).val();
            $.post("{{ route('get_age_additional_pension') }}",{
                "_token": "{{ csrf_token() }}",
                dob:dob
            },function(response){
                $('.page-loader').removeClass('d-flex');
                console.log(response);
                var obj = JSON.parse(response);
                $("#age_dob").html("(Age - "+ obj.years+ " Years " +obj.months+ " Months " +obj.days+ " Days)");
                $("#age_year").val(obj.years);
                $("#age_month").val(obj.months);
                $("#age_days").val(obj.days);

                var basic_amount = $("#basic_pension_amount").val();
                var additional_pension_amount = $("#additional_pension_amount").val();
                $.post("{{ route('get_additional_pension_amount') }}",{
                    "_token": "{{ csrf_token() }}",
                    basic_amount:basic_amount,
                    year_value:obj.years,
                    month_value:obj.months,
                    day_value:obj.days,
                },function(response){
                    $('.page-loader').removeClass('d-flex');
                    var obj = JSON.parse(response);
                    $("#additional_pension_amount").val(obj.increment_value);
                    $("#additional_pension_percentage").html(obj.increment_percentage);
                });
            });
            setTimeout(calculate_gross_amount, 3000);
        });

        $("#basic_pension_amount").on("change", function(){
            var basic_amount = $(this).val();
            var year_value = $("#age_year").val();
            var month_value = $("#age_month").val();
            var day_value = $("#age_days").val();
            $.post("{{ route('get_additional_pension_amount') }}",{
                "_token": "{{ csrf_token() }}",
                basic_amount:basic_amount,
                year_value:year_value,
                month_value:month_value,
                day_value:day_value,
            },function(response){
                $('.page-loader').removeClass('d-flex');
                var obj = JSON.parse(response);
                $("#additional_pension_amount").val(obj.increment_value);
                $("#additional_pension_percentage").html(obj.increment_percentage);
            });
            setTimeout(calculate_gross_amount, 3000);
        });

        $.validator.addMethod("amount_only", function (value, element) {
            return this.optional(element) || /^\d{1,8}(?:\.\d{1,2})?$/.test(value);
        }, "Please enter in amount format");

        $.validator.addMethod("ppo_format", function (value, element) {
            return this.optional(element) || /^[A-Z0-9]+(\/[A-Z0-9]+)*$/.test(value);
        }, "Please enter valid PPO no");

        $("#pension_form").validate({
            onkeyup:false,
            rules: {
                "pesioner_type": {
                    required: true,
                },
                "old_ppo_no": {
                    required: true,
                    ppo_format: true,
                },
                "attached_ppo_certificate":{
                    required: true,
                },
                "pensioner_name":{
                    required: {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }
                    },
                },
                "mobile_number":{
                    required: false,
                },
                "aadhaar_number":{
                    required: false,
                    onlyNumber: true,
                    minlength: 12,
                    maxlength: 12,
                    remote:{
                        url:'{{ route("validate_aadhar_number") }}',
                        type:"post",
                        data:{
                            '_token': function() {
                               return '{{ csrf_token() }}';
                            }
                        }
                    },
                },
                "employee_code":{
                    required: false,
                },
                "gender":{
                    required: true,
                },
                "designation":{
                    required: true,
                },
                "dob":{
                    required: true,
                },
                "dor":{
                    required: true,
                },
                "date_of_death":{
                    required: {
                        depends:function(element){
                            var pesioner_type = $("#pesioner_type").val();
                            if(pesioner_type == 'FM'){
                                return true;
                            }else{
                                return false;
                            }
                        }
                    },
                },                
                "enhanced_pension_amount":{
                    required: {
                        depends:function(element){
                            var pesioner_type = $("#pesioner_type").val();
                            if(pesioner_type == 'FM'){
                                return true;
                            }else{
                                return false;
                            }
                        }
                    },
                },              
                "enhanced_pension_end_date":{
                    required: {
                        depends:function(element){
                            var pesioner_type = $("#pesioner_type").val();
                            if(pesioner_type == 'FM'){
                                return true;
                            }else{
                                return false;
                            }
                        }
                    },
                },             
                "normal_pension_amount":{
                    required: {
                        depends:function(element){
                            var pesioner_type = $("#pesioner_type").val();
                            if(pesioner_type == 'FM'){
                                return true;
                            }else{
                                return false;
                            }
                        }
                    },
                },            
                "normal_pension_effective_date":{
                    required: {
                        depends:function(element){
                            var pesioner_type = $("#pesioner_type").val();
                            if(pesioner_type == 'FM'){
                                return true;
                            }else{
                                return false;
                            }
                        }
                    },
                },          
                "relation_type":{
                    required: {
                        depends:function(element){
                            var pesioner_type = $("#pesioner_type").val();
                            if(pesioner_type == 'FM'){
                                return true;
                            }else{
                                return false;
                            }
                        }
                    },
                },          
                "current_status":{
                    required: {
                        depends:function(element){
                            var pesioner_type = $("#pesioner_type").val();
                            if(pesioner_type == 'FM'){
                                return true;
                            }else{
                                return false;
                            }
                        }
                    },
                },     
                "rel_cur_status_end_date":{
                    required: {
                        depends:function(element){
                            var pesioner_type = $("#pesioner_type").val();
                            var relation_type = $("#relation_type").val();
                            var current_status = $("#current_status").val();
                            if(pesioner_type == 'FM' && relation_type == 2 && current_status == 9){
                                return true;
                            }else{
                                return false;
                            }
                        }
                    },
                },
                "basic_pension_amount":{
                    required: true,
                    amount_only: true,
                },
                "basic_pension_effective_date":{
                    required: true,
                },
                "additional_pension_amount":{
                    required: true,
                },
                "ti_category_id":{
                    required: true,
                },
                "ti_amount":{
                    required: true,
                },
                "bank_name":{
                    required: true,
                },
                "branch_name_address":{
                    required: true,
                },
                "ifsc_code":{
                    required: true,
                },
                "micr_code":{
                    required: true,
                },
                "saving_bank_ac_no":{
                    required: true,
                },
                "nominee_name":{
                    required: {
                        depends:function(element){
                            var pesioner_type = $("#pesioner_type").val();
                            if(pesioner_type == 'FM'){
                                return true;
                            }else{
                                return false;
                            }
                        }
                    },
                },
                "nominee_mob_no":{
                    required: {
                        depends:function(element){
                            var pesioner_type = $("#pesioner_type").val();
                            if(pesioner_type == 'FM'){
                                return true;
                            }else{
                                return false;
                            }
                        }
                    },
                },
                "nominee_aadhar_no":{
                    required: {
                        depends:function(element){
                            var pesioner_type = $("#pesioner_type").val();
                            if(pesioner_type == 'FM'){
                                return true;
                            }else{
                                return false;
                            }
                        }
                    },
                },
                "nominee_dob":{
                    required: {
                        depends:function(element){
                            var pesioner_type = $("#pesioner_type").val();
                            if(pesioner_type == 'FM'){
                                return true;
                            }else{
                                return false;
                            }
                        }
                    },
                },
                "gross_pension":{
                    required: true,
                },
                "commutation_amount[]":{
                    required: true,
                    amount_only:true
                },
                "commutation_amount_end_date[]":{
                    required: true,
                },               
            },
            messages: {
                "pesioner_type": {                    
                    required: 'Please select pensioner type',
                },
                "old_ppo_no": {
                    required: 'Please enter PPO no',
                },
                "attached_ppo_certificate":{
                    required: 'Please upload PPO file',
                },
                "pensioner_name":{
                    required: 'Please enter pensioner name',
                },
                "aadhaar_no": {
                    required: 'Please enter aadhaar no',
                    minlength: 'Aadhaar No minimum of 12 digits',
                    maxlength: 'Aadhaar No maximum upto 12 digits',
                    remote: 'Aadhaar No already exists',
                },
                "gender":{
                    required: 'Please select gender',
                },
                "designation":{
                    required: 'Please select designation',
                },
                "dob":{
                    required: 'Please select date of birth',
                },
                "dor":{
                    required: 'Please select date of retiremet',
                },
                "date_of_death":{
                    required: 'Please select date of death',
                },                
                "enhanced_pension_amount":{
                    required: 'Please enter enhanced pension amount',
                },              
                "enhanced_pension_end_date":{
                    required: 'Please enter enhanced pension end date',
                },             
                "normal_pension_amount":{
                    required: 'Please enter normal pension amount',
                },            
                "normal_pension_effective_date":{
                    required: 'Please enter normal pension effective date',
                },          
                "relation_type":{
                    required: 'Please select relation type',
                },          
                "current_status":{
                    required: 'Please select current status',
                },     
                "rel_cur_status_end_date":{
                    required: 'Please select end date',
                },
                "basic_pension_amount":{
                    required: 'Please enter basic pension amount',
                },
                "basic_pension_effective_date":{
                    required: 'Please select basic pension effective date',
                },
                "additional_pension_amount":{
                    required: 'Please select date of birth',
                },
                "ti_category_id":{
                    required: 'Please select category',
                },
                "ti_amount":{
                    required: 'Please check category/basic pension amount',
                },
                "bank_name":{
                    required: 'Please select bank',
                },
                "branch_name_address":{
                    required: 'Please select branch',
                },
                "ifsc_code":{
                    required: 'Please select bank and branch',
                },
                "micr_code":{
                    required: 'Please select bank and branch',
                },
                "saving_bank_ac_no":{
                    required: 'Please enter saving account no',
                },
                "nominee_name":{
                    required: 'Please enter nominee name',
                },
                "nominee_mob_no":{
                    required: 'Please enter mobile number',
                },
                "nominee_aadhar_no":{
                    required: 'Please enter aadhar no',
                },
                "nominee_dob":{
                    required: 'Please select nominee date of birth',
                },
                "gross_pension":{
                    required: 'Please enter gross pension',
                },    
                "commutation_amount[]":{
                    required: 'Please enter commutation amount',
                },
                "commutation_amount_end_date[]":{
                    required: 'Please enter commutation end date',
                },    
            },
            submitHandler: function(form, event) {
                $('.page-loader').addClass('d-flex'); 
                event.preventDefault();
                var formData = new FormData(form);
                //$("#logid").prop('disabled',true);
                $.ajax({
                    type:'POST',
                    url:'{{ route("existing_pensioner_form_submission") }}',
                    data: formData,
                    dataType: 'JSON',
                    processData: false,
                    contentType: false,
                    success: function(response) {
                    //console.log(response);
                    $('.page-loader').removeClass('d-flex');
                      if(response['error']){
                        //$("#logid").prop('disabled',false);
                          for (i in response['error']) {
                              var element = $('#' + i);
                              var id = response['error'][i]['id'];
                              var eValue = response['error'][i]['eValue'];
                              //console.log(id);
                              //console.log(eValue);
                              $("#"+id).show();
                              $("#"+id).html(eValue);
                          }
                      }else{
                        location.href = "{{route('existing_pension_list')}}";
                      }
                    }
                });             
            },
            errorPlacement: function(label, element) {
                label.addClass('text-danger');
                label.insertAfter(element);
            },
            highlight: function(element, errorClass) {
                //$(element).parent().addClass('has-success');
                $(element).addClass('form-control-danger');
            }
        });
    });

    
    
    </script>


@endsection
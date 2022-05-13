@extends('user.layout.layout')

@section('section_content')

<style type="text/css">
    .select-drop {
        width: 100%;
    }
    .minus-btn {
        position: absolute; 
        right: 42px; 
        padding: 0;
        height: 25px;
        width: 26px; 
        /*border-radius: 50%; */
        margin-top: -6px;
    }
    /*.error {
        color: #DB504A !important;
    }*/
    .error-msg {
        display: none;
    }
    .spouse_type, .is_legal, .physically_handicapped, .is_second_spouse {
        display: none;
    }
    .nominee_row {
        margin-bottom: 30px;
    }
    #addNominee, .new_nominee, #saveNominee {
        float: right;
    }
    #upload-demo{
        width: 450px;
        height: 300px;
        padding-bottom:25px;
    }
    .circle-icon {
        color: green;
    }
    .img-icon {
        margin-top: 14px;
    }
</style>

<div class="content-wrapper">
    @if(Session::has('error'))
        <div class="alert alert-danger">{{ Session::get('error') }}</div>
    @endif
    @if(Session::has('success'))
        <div class="alert alert-success">{{ Session::get('success') }}</div>
    @endif
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <img src="{{url('public')}}/images/logo_1.png" alt="image" class="brand_logo_1" />
                    <img src="{{url('public')}}/images/logo_2.png" alt="image" class="brand_logo_2" />
                    <!-- <h4 class="card-title align_center">ODISHA POWER TRANSMISSION CORPORATION LTD.</h4>
                    <h5 class="card-description align_center">(A Govt. of Odisha Undertaking)</h5>
                    <h5 class="card-description align_center">Gridco Pension Trust Fund</h5>
                    <p class="card-description align_center">Regd. Off – Janpath, Bhubaneswar – 751022</p> -->

                    <h4 class="card-title align_center mb-2">ODISHA POWER TRANSMISSION CORPORATION LTD.</h4>
                    <h5 class="card-description align_center mb-1">(A Govt. of Odisha Undertaking)</h5>
                    <h5 class="card-description align_center mb-1">Gridco Pension Trust Fund</h5>
                    <p class="card-description align_center mb-1">Regd. Off – Janpath, Bhubaneswar – 751022</p>

                    <div class="employe-code-check">
                        <ul class="nav nav-tabs d-flex justify-content-center mt-5" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link" id="home-tab" href="{{ route('edit_nominee_application_form') }}">1. FAMILY PENSION FORM</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('edit_nominee_family_pensioner_form') }}">2. FAMILY PENSIONER</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active-tab" id="nominee-tab" data-toggle="tab" href="#nominee-1" role="tab" aria-controls="nominee-tab" aria-selected="true">3. NOMINEES</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact-1" role="tab" aria-controls="contact-1" aria-selected="false">4. LIST OF DOCUMENTS</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="">
                                <div class="media-body">
                                    <h4 class="mt-0 text-center">DETAILS OF FAMILY MEMBERS & NOMINATION </h4>
                                    <p class="text-center">Details of family as on the date of application:- (In case of having 2nd Spouse, the proof of death of first spouse and their children may be specified and enclosed)</p>
                                    <br />

                                    <div class="row grid-margin">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h4 class="card-title">Nominee Details</h4>
                                                    <p class="card-description"></p>
                                                    <div id="nominee-list"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <form action="{{ url('nominee/submit/nominee-details') }}" method="POST" class="forms-sample" enctype="multipart/form-data" id="nominee-details" autocomplete="off">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $id }}">

                                        <input type="hidden" name="nominee_preference_ids" id="nominee_preference_ids" value="{{ $nominee_preference_ids }}">
                                        <input type="hidden" name="nominee_preference_change_ids" id="nominee_preference_change_ids">
                                        <div id="nominee_list"></div>

                                        
                                        <!-- <div class="row new_nominee">
                                            <div class="col-md-12">
                                                <button type="button" id="addNominee" class="btn btn-success">+ Add Nominee</button>
                                                <button type="submit" id="saveNominee" class="btn btn-primary d-none">Save Nominee</button>
                                            </div>
                                        </div> -->
                                        <br>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <a href="{{ route('edit_nominee_family_pensioner_form') }}" class="btn btn-default mr-2 btn-prev">PREVIOUS</a>
                                                <button type="button" id="nominee-next-btn" class="btn btn-primary mr-2 btn-next">NEXT</button>

                                                <button type="button" id="addNominee" class="btn btn-success">+ Add Nominee</button>
                                                <button type="submit" id="saveNominee" class="btn btn-primary d-none">Save Nominee</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="crop_image" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Crop the image</h5>
                <button type="button" class="close cancel-close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <input type="hidden" id="file_name" name="file_name" value="">
                    <!-- <img id="image"> -->
                    <div id="upload-demo" class="center-block"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary cancel-close" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="crop">Crop</button>
            </div>
        </div>
    </div>    
</div>

@endsection

@section('page-script')

<!-- Custom js for this page-->
<script type="text/javascript">

    $(document).ready(function() {

        var $uploadCrop,
        rawImg,
        imageId;

        $('ul.nav li a').click(function(e) {
            $(this).addClass('step_active').removeClass('step_inactive');
        });

        if ($("#nominee-list").length) {
            $("#nominee-list").jsGrid({
                // height: "500px",
                width: "100%",
                // filtering: true,
                // editing: true,
                // inserting: true,
                sorting: true,
                paging: true,
                autoload: true,
                pageSize: 10,
                pageButtonCount: 5,
                noDataContent: "Nominee Not found",
                deleteConfirm: "Do you really want to delete the nominee?",
                data: <?php echo $nominee_details; ?>,
                fields: [{
                    title:"Nominee Name",
                    name: "nominee_name",
                    type: "text",
                    width: 100
                },
                {
                    title:"Nominee Aadhaar No.",
                    name: "nominee_aadhaar_no",
                    type: "number",
                    width: 100,
                    align: "center"
                },
                {
                    title: "Nominee Preference",
                    name:"nominee_preference_id",
                    type: "select",
                    width: 100,
                    items: <?php echo $nominee_prefences ?>,
                    valueField: "id",
                    textField: "nominee_prefrence"
                },
                { 
                    type: "control", title: "Action", name:"action", width: 100, editButton: false, deleteButton: false,
                    itemTemplate: function(value, item) {
                        var $result = jsGrid.fields.control.prototype.itemTemplate.apply(this, arguments);

                        var $customEditButton = $("<button>").attr({class: "customGridEditbutton jsgrid-button jsgrid-edit-button"})
                          .click(function(e) {

                            var id = item.id;
                            var preference_id = item.nominee_preference_id;

                            let nominee_ids = $('#nominee_preference_ids').val();

                            let nominee_preference_id_array = nominee_ids.split(',');

                            y = $.grep(nominee_preference_id_array, function(value) {
                                return value != preference_id;
                            });

                            x = y.toString();

                            $('#nominee_preference_ids').val(x);

                            if($("#nominee-details").valid()) {
                                $('.page-loader').addClass('d-flex');
                                let nominee_list_len = $('.nominee_row').length;

                                let nominee_preference_ids = '';

                                $(this).attr('disabled', true);
                                $(this).closest('div.js-button').find('.customGridDeletebutton').attr('disabled', true);

                                $.post("{{ route('add_new_nominee_data') }}",{
                                    "_token": "{{ csrf_token() }}", "key" : nominee_list_len, "nominee_preference_ids" : nominee_preference_ids, 'nominee_id' : id,
                                },function(response) {
                                    
                                    $("#nominee_list").append(response.html);

                                    let bank_branch_id = response.nominee_details.bank_branch_id;
                                    let dob_attachment_path = response.nominee_details.dob_attachment_path;
                                    let spouse_death_certificate_path = response.nominee_details.spouse_death_certificate_path;
                                    let legal_guardian_attachment_path = response.nominee_details.legal_guardian_attachment_path;

                                    validate();
                                    validate_attachment('dob_attachment_path');
                                    validate_attachment('1st_spouse_death_certificate_path');
                                    validate_attachment('legal_guardian_attachment_path');

                                    $('.date_of_birth').datepicker({
                                        autoclose: true,
                                        todayHighlight: true,
                                        endDate: new Date()
                                    });

                                    $('.1st_spouse_death_date').datepicker({
                                        autoclose: true,
                                        todayHighlight: true,
                                        endDate: new Date()
                                    });

                                    // $('.bank_details').trigger('change');
                                    $('.bank_branch').trigger('change');
                                    $('.relation_with_pensioner').trigger('change');
                                    $('.is_2nd_spouse').trigger('change');
                                    $('.employement_status').trigger('change');
                                    $('.is_minor').trigger('change');
                                    $('.is_physically_handicapped').trigger('change');

                                    if ($(".js-example-basic-single").length) {
                                        $(".js-example-basic-single").select2();
                                    }

                                    $('#addNominee').addClass('d-none');
                                    $('#saveNominee').removeClass('d-none');
                                    $('.btn-next').attr('disabled', true);

                                    $('.page-loader').removeClass('d-flex');
                                });
                            }
                            
                            e.stopPropagation();
                        });

                        var $customDeleteButton = $("<button >").attr({class: "customGridDeletebutton jsgrid-button jsgrid-delete-button"})
                            .click(function(e) {

                            swal({
                                title: "Are you sure?",
                                text: "You will not be able to recover this nominee details!",
                                type: "warning",
                                showCancelButton: true,
                                confirmButtonColor: '#DD6B55',
                                confirmButtonText: 'Yes, I am sure!',
                                cancelButtonText: "No, cancel it!",
                                closeOnConfirm: false,
                                closeOnCancel: false
                            }).then(function(isConfirm) {
                                if (isConfirm) {
                                    $('.page-loader').addClass('d-flex');
                                    var id = item.id;

                                    let nominee_preference_ids = $('#nominee_preference_ids').val();

                                    let nominee_preference_id_array = nominee_preference_ids.split(',');

                                    y = $.grep(nominee_preference_id_array, function(value) {
                                        return value != id;
                                    });

                                    x = y.toString();

                                    $('#nominee_preference_ids').val(x);


                                    if($(this).closest('.jsgrid-alt-row').length == 1) {
                                        $(this).closest('.jsgrid-alt-row').remove();
                                    } else {
                                        $(this).closest('.jsgrid-row').remove();
                                    }

                                    $.ajax({
                                        url:'{{ route("delete_nominee_nominee_details") }}',
                                        type:'post',
                                        data:'id='+id+'&_token={{csrf_token()}}',
                                        success:function(result) {
                                            $('.page-loader').removeClass('d-flex');

                                            swal({
                                                title: 'Deleted!',
                                                text: 'Nominee details has been deleted successfully!',
                                                icon: 'success'
                                            }).then(function() {
                                                window.location.reload();
                                            });
                                        }
                                    });
                                    e.stopPropagation();
                                } else {
                                    e.stopPropagation();
                                    return false;
                                }
                            });


                            /*if (confirm("Do you really want to delete the nominee?")) {
                                $('.page-loader').addClass('d-flex');
                                var id = item.id;

                                let nominee_preference_ids = $('#nominee_preference_ids').val();

                                let nominee_preference_id_array = nominee_preference_ids.split(',');

                                y = $.grep(nominee_preference_id_array, function(value) {
                                    return value != id;
                                });

                                x = y.toString();

                                $('#nominee_preference_ids').val(x);


                                if($(this).closest('.jsgrid-alt-row').length == 1) {
                                    $(this).closest('.jsgrid-alt-row').remove();
                                } else {
                                    $(this).closest('.jsgrid-row').remove();
                                }

                                $.ajax({
                                    url:'{{ route("delete_nominee_details") }}',
                                    type:'post',
                                    data:'id='+id+'&_token={{csrf_token()}}',
                                    success:function(result) {
                                        $('.page-loader').removeClass('d-flex');
                                    }
                                });
                                e.stopPropagation();
                            } else {
                                e.stopPropagation();
                                return false;
                            }*/

                        });

                        $('.jsgrid-header-cell.jsgrid-control-field').text('Action');
                        return $("<div class='js-button'>").append($customEditButton).append($customDeleteButton);
                    },
                }
                ],
            });
        }

        $('.jsgrid-header-cell.jsgrid-control-field').text('Action');

        $('.sidebar ul.nav li a').click(function(e) {
            $(this).addClass('step_active').removeClass('step_inactive');
            //$(this).closest('.nav-link').removeClass("step_active").addClass('step_inactive');
        });
        
        $('.date_of_birth').datepicker({
            autoclose: true,
            todayHighlight: true,
            endDate: new Date()
        });

        $('.1st_spouse_death_date').datepicker({
            autoclose: true,
            todayHighlight: true,
            endDate: new Date()
        });

        $(document).on('click', '.file-upload-browse', function() {
            var file = $(this).parent().parent().parent().find('.file-upload-default');
            file.trigger('click');
        });

        $(document).on('change', '.bank_details', function() {
            $('.page-loader').addClass('d-flex');
            let sid = $(this).val();
            let attr_id = $(this).attr('id');
            let key = $(this).data('key');
            $.ajax({
                url:'{{ route("nominee_get_bank_branch") }}',
                type:'post',
                data:'sid='+sid+'&_token={{csrf_token()}}',
                success:function(result){
                    $('#branch_' + key).html(result);
                    $('.page-loader').removeClass('d-flex');
                }
            });
        });

        $(document).on('change', '.bank_branch', function() {
            $('.page-loader').addClass('d-flex');
            let sid = $(this).val();
            let attr_id = $(this).attr('id');
            let key = $(this).data('key');
            $.ajax({
                url:'{{ route("nominee_get_details_branch") }}',
                type:'post',
                data:'sid='+sid+'&_token={{csrf_token()}}',
                success:function(result){
                    $('#ifsc_code_' + key).val(result.ifsc_code);
                    $('.page-loader').removeClass('d-flex');
                }
            });
        });

        $(document).on('change', '.relation_with_pensioner', function() {
            let sid = $(this).val();
            let attr_id = $(this).attr('id');
            let key = $(this).data('key');
            
            if(sid == 1) {
                $('#is_spouse_' + key).val(1);
                $('#spouse_type_' + key).show();
                if ($(".js-example-basic-single").length) {
                    $(".js-example-basic-single").select2();
                }

                validate_relation();
            } else {
                $('#is_spouse_' + key).val('');
                $('#is_2nd_spouse_' + key).val('');
                $('#spouse_type_' + key).hide();
                $('#is_second_spouse_death_date_' + key).hide();
                $('#is_second_spouse_death_cert_' + key).hide();
            }
        });

        $(document).on('change', '.is_2nd_spouse', function() {
            let sid = $(this).val();
            let attr_id = $(this).attr('id');
            let key = $(this).data('key');

            if(sid == 2) {
                $('#is_second_spouse_death_date_' + key).show();
                $('#is_second_spouse_death_cert_' + key).show();

                validate_relation_spouse();
                validate_attachment('1st_spouse_death_certificate_path');
            } else {
                $('#is_second_spouse_death_date_' + key).hide();
                $('#is_second_spouse_death_cert_' + key).hide();
            }
        });

        $(document).on('change', '.employement_status', function() {
            let sid = $(this).val();
            let attr_id = $(this).attr('id');
            let key = $(this).data('key');

            if(sid == 2) {
                $('#total_income_per_annum_' + key).val(0);
                $('#total_income_per_annum_' + key).attr('readonly', true);
            } else {
                // $('#total_income_per_annum_' + key).val('');
                $('#total_income_per_annum_' + key).attr('readonly', false);
            }
        });

        $(document).on('keyup', '.pension_amount_share_percentage', function() {
            let sid = $(this).val();
            let attr_id = $(this).attr('id');
            let key = $(this).data('key');

            if(sid > 100) {
                $('#pension_amount_share_percentage_' + key).val('');
                $('#pension_amount_share_percentage_' + key + '-error').text('Amount / Share payable to each cannot be more than 100').removeClass('error-msg').css('display', 'block');
            }
        });

        $(document).on('keyup', '.disability_percentage', function() {
            let sid = $(this).val();
            let attr_id = $(this).attr('id');
            let key = $(this).data('key');

            if(sid > 100) {
                $('#disability_percentage_' + key).val('');
                $('#disability_percentage_' + key + '-error').text('Disability percentage can not be more than 100').removeClass('error-msg').css('display', 'block');
            }
        });

        $(document).on('change', '.is_physically_handicapped', function() {
            let sid = $(this).val();
            let attr_id = $(this).attr('id');
            let key = $(this).data('key');

            if(sid == 1) {
                $('#physically_handicapped_cert_' + key).show();
                $('#physically_handicapped_percentage_' + key).show();

                validate_physically_handicap();
                validate_attachment('disability_certificate_path');
            } else {
                $('#physically_handicapped_cert_' + key).hide();
                $('#physically_handicapped_percentage_' + key).hide();
            }
        });

        $(document).on('change', '.is_minor', function() {
            let sid = $(this).val();
            let attr_id = $(this).attr('id');
            let key = $(this).data('key');

            if(sid == 1) {
                $('#is_legal_name_' + key).show();
                $('#is_legal_age_' + key).show();
                $('#is_legal_addr_' + key).show();
                $('#is_legal_attch_' + key).show();

                validate_minor();
                validate_attachment('legal_guardian_attachment_path');
            } else {
                $('#is_legal_name_' + key).hide();
                $('#is_legal_age_' + key).hide();
                $('#is_legal_addr_' + key).hide();
                $('#is_legal_attch_' + key).hide();
            }
        });

        $(document).on('click', '.minus-btn', function() {
            $(this).closest('.row').remove();

            $('#addNominee').removeClass('d-none');
            $('#saveNominee').addClass('d-none');
            $('.btn-next').attr('disabled', false);
        });

        $(document).on('change', '.nominee_preference_id', function() {
            let nominee_preference_id = $(this).val();
            let attr_id = $(this).attr('id');
            let key = $(this).data('key');
            let nominee_preference_ids = $('#nominee_preference_ids').val();

            let nominee_preference_id_array = nominee_preference_ids.split(',');

            if(nominee_preference_id_array.length != 0 && nominee_preference_id != '') {

                if($.inArray(nominee_preference_id, nominee_preference_id_array) !== -1) {
                    $('#nominee_preference_id_' + key + '-error').text('This nominee preference already selected, please select another preference').removeClass('error-msg').css('display', 'block');
                    $('#nominee_preference_id_' + key).select2().val('').trigger('change');
                } else {

                    if(nominee_preference_id != '') {
                        var elval = [];

                        $('.nominee_preference_id').each(function() {
                            let exist_key = $(this).data('key');

                            if(exist_key != key) {
                                elval.push($(this).val()); 
                            }
                        });

                        if($.inArray(nominee_preference_id, elval) !== -1) {
                           $('#nominee_preference_id_' + key + '-error').text('This nominee preference already selected, please select another preference').removeClass('error-msg').css('display', 'block');
                            //alert('this value already selected. Please choose another value');
                            $('#nominee_preference_id_' + key).select2().val('').trigger('change');
                        } else {
                            $('#nominee_preference_id_' + key + '-error').text('').hide();
                        }
                    }
                }
            }
        });

        $(document).on('click', '#addNominee', function() {

            if($("#nominee-details").valid()) {
                $('.page-loader').addClass('d-flex');
                let nominee_list_len = $('.nominee_row').length;                

                // let nominee_preference_ids = $('#nominee_preference_ids').val();
                let nominee_preference_ids = '';

                $.post("{{ route('add_new_nominee_data') }}",{
                    "_token": "{{ csrf_token() }}", "key" : nominee_list_len, "nominee_preference_ids" : nominee_preference_ids,
                },function(response) {
                    $("#nominee_list").append(response.html);
                    
                    validate();
                    validate_attachment('dob_attachment_path');

                    $('.date_of_birth').datepicker({
                        autoclose: true,
                        todayHighlight: true,
                        endDate: new Date()
                    });

                    $('.1st_spouse_death_date').datepicker({
                        autoclose: true,
                        todayHighlight: true,
                        endDate: new Date()
                    });

                    if ($(".js-example-basic-single").length) {
                        $(".js-example-basic-single").select2();
                    }

                    $('#addNominee').addClass('d-none');
                    $('#saveNominee').removeClass('d-none');
                    $('.btn-next').attr('disabled', true);


                    $('.page-loader').removeClass('d-flex');
                });
            }
        });

        $('.savings_bank_account_no').keyup(function () { 
            this.value = this.value.replace(/[^0-9\ ]/g,'');
        });

        $('.aadhaar_no').keyup(function () { 
            this.value = this.value.replace(/[^0-9\ ]/g,'');
        });

        $('.legal_guardian_age').keyup(function () { 
            this.value = this.value.replace(/[^0-9\ ]/g,'');
        });

        $('.mobile_no').keyup(function () { 
            this.value = this.value.replace(/[^0-9\ ]/g,'');
        });

        $('.pension_amount_share_percentage').keyup(function () { 
            this.value = this.value.replace(/[^0-9\ ]/g,'');
        });

        $('.total_income_per_annum').keyup(function () { 
            this.value = this.value.replace(/[^0-9\ ]/g,'');
        });

        $('.disability_percentage').keyup(function () { 
            this.value = this.value.replace(/[^0-9\ ]/g,'');
        });

        $('.nominee_name').keyup(function () { 
            this.value = this.value.replace(/[^A-Za-z\ ]/g,'');
        });

        $('.legal_guardian_name').keyup(function () { 
            this.value = this.value.replace(/[^A-Za-z\ ]/g,'');
        });

        $.validator.addMethod("onlyNumber", function (value, element) {
            return this.optional(element) || /^[0-9\s-]*$/.test(value);
        }, "Please use only numbers");    

        $.validator.addMethod("addressReg", function (value, element) {
            return this.optional(element) || /^[a-zA-Z0-9\s,/-]*$/.test(value);
        }, "Please use only letters, numbers and special characters(,/-).");

        $.validator.addMethod("precentage", function (value, element) {
            if(value > 100) {
                return false;
            }
            return true;
        }, "Percentage should not be allowed more than 100");

        $('#nominee-details').validate({
            onkeyup:false,
            submitHandler: function (form) {
                $('.page-loader').addClass('d-flex');
                event.preventDefault();
                var formData = new FormData(form);

                $.ajax({
                    type:'POST',
                    url:'{{ route("save_nominee_nominee_details") }}',
                    data: formData,
                    dataType: 'JSON',
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('.page-loader').removeClass('d-flex');
                        if(response['error']) {
                            for (i in response['error']) {
                                var element = $('#' + i);
                                var id = response['error'][i]['id'];
                                var eValue = response['error'][i]['eValue'];
                                $("#"+id).show();
                                $("#"+id).html(eValue);
                            }
                        } else {
                            // location.href = "{{--route('pension_documents')--}}";
                            window.location.reload();
                        }
                    }
                });
            }
        });

        $(document).on('click', '.cancel-close', function(){
            var filename = $(this).closest('#crop_image').find($('#file_name'));
            var val = $(filename).val();
            $('#'+val).parent().find('.file-upload-info').val('');

            $('#'+val).val('');

            $('#upload-demo').croppie('destroy');
        });

        $(document).on('click', '#crop', function() {

            $('.page-loader').addClass('d-flex');             
            
            $uploadCrop.croppie('result', {
                type: 'canvas',
                format: 'png',
                size: {width: 150, height: 200}
            }).then(function (resp) {
                // var avatar = URL.createObjectURL(resp);
                var file_name = $('#file_name').val();

                $('#'+file_name+'_hidden').val(resp);
                $("#"+file_name).attr('required', false);

                $('#crop_image').modal('hide');
                $('#upload-demo').croppie('destroy');

                setTimeout(function() { 
                    $("#"+file_name+"_img").attr("data-img", resp).removeClass('d-none');
                    $("#"+file_name+"_check").removeClass('d-none');
                    $('.page-loader').removeClass('d-flex');
                    
                }, 2000);
                
            });
        });
    
        $(document).on('change', '.dob_attachment_path', function() {
            let attr_id = $(this).attr('id');
            let key = $(this).data('key');

            check_upload_file(this, attr_id);

            if (this.files && this.files[0]) {
              var reader = new FileReader();
                reader.onload = function (e) {
                    $('#crop_image').modal('show');
                    rawImg = e.target.result;
                }
                reader.readAsDataURL(this.files[0]);
            }

            $('#file_name').val(attr_id);

            $uploadCrop = $('#upload-demo').croppie({
                viewport: {
                    width: 150,
                    height: 200,
                },
                // showZoomer: false,
                enforceBoundary: false,
                enableExif: true
            });

            $('#crop_image').on('shown.bs.modal', function(){
                $uploadCrop.croppie('bind', {
                    url: rawImg
                }).then(function() {
                });
            });
        });

        $(document).on('change', '.1st_spouse_death_certificate_path', function() {
            let attr_id = $(this).attr('id');
            let key = $(this).data('key');

            check_upload_file(this, attr_id);

            if (this.files && this.files[0]) {
              var reader = new FileReader();
                reader.onload = function (e) {
                    $('#crop_image').modal('show');
                    rawImg = e.target.result;
                }
                reader.readAsDataURL(this.files[0]);
            }

            $('#file_name').val(attr_id);

            $uploadCrop = $('#upload-demo').croppie({
                viewport: {
                    width: 150,
                    height: 200,
                },
                // showZoomer: false,
                enforceBoundary: false,
                enableExif: true
            });

            $('#crop_image').on('shown.bs.modal', function(){
                $uploadCrop.croppie('bind', {
                    url: rawImg
                }).then(function() {
                });
            });
        });

        $(document).on('change', '.disability_certificate_path', function() {
            let attr_id = $(this).attr('id');
            let key = $(this).data('key');

            check_upload_file(this, attr_id);

            if (this.files && this.files[0]) {
              var reader = new FileReader();
                reader.onload = function (e) {
                    $('#crop_image').modal('show');
                    rawImg = e.target.result;
                }
                reader.readAsDataURL(this.files[0]);
            }

            $('#file_name').val(attr_id);

            $uploadCrop = $('#upload-demo').croppie({
                viewport: {
                    width: 150,
                    height: 200,
                },
                // showZoomer: false,
                enforceBoundary: false,
                enableExif: true
            });

            $('#crop_image').on('shown.bs.modal', function(){
                $uploadCrop.croppie('bind', {
                    url: rawImg
                }).then(function() {
                });
            });
        });

        $(document).on('change', '.legal_guardian_attachment_path', function() {
            let attr_id = $(this).attr('id');
            let key = $(this).data('key');

            check_upload_file(this, attr_id);

            if (this.files && this.files[0]) {
              var reader = new FileReader();
                reader.onload = function (e) {
                    $('#crop_image').modal('show');
                    rawImg = e.target.result;
                }
                reader.readAsDataURL(this.files[0]);
            }

            $('#file_name').val(attr_id);

            $uploadCrop = $('#upload-demo').croppie({
                viewport: {
                    width: 150,
                    height: 200,
                },
                // showZoomer: false,
                enforceBoundary: false,
                enableExif: true
            });

            $('#crop_image').on('shown.bs.modal', function(){
                $uploadCrop.croppie('bind', {
                    url: rawImg
                }).then(function() {
                });
            });
        });

       /* $(document).on('focusout', '.savings_bank_account_no', function() {
            
            let account_no = $(this).val();
            let attr_id = $(this).attr('id');
            let key = $(this).data('key');
            let nominee_id = $('#nominee_id_' + key).val();
            let check = 0;

            $('.savings_bank_account_no').each(function() {
                let exist_account_no = $(this).val();
                let exist_data_key = $(this).data('key');

                if(exist_data_key != key) {
                    if(exist_account_no == account_no) {
                        check = 1;
                        swal("", "Account No. already added", 'error')
                        .then((value) => {
                            $('#savings_bank_account_no_'+key).val('');
                            return false;
                        });
                    }
                }
            });

            if(check == 0) {
                $('.page-loader').addClass('d-flex');
                if(account_no != '') {
                    $.ajax({
                        url:'{{ route("check_account_no") }}',
                        type:'post',
                        data:'account_no='+account_no+'&_token={{csrf_token()}}&nominee_id='+nominee_id,
                        success:function(result) {
                            $('.page-loader').removeClass('d-flex');
                            if(result.status == 'error') {
                                swal("", result.message, 'error')
                                .then((value) => {
                                    $('#savings_bank_account_no_'+key).val('');
                                });
                            }
                        }
                    });
                } else {
                    $('.page-loader').removeClass('d-flex');
                }
            }
        });*/

        $(document).on('click', '#nominee-next-btn', function() {
            location.href = "{{ route('nominee_pension_documents_page') }}";
        });
    });

    function validate() {
        $('.nominee_name').keyup(function () { 
            this.value = this.value.replace(/[^A-Za-z\ ]/g,'');
        });

        $('.legal_guardian_name').keyup(function () { 
            this.value = this.value.replace(/[^A-Za-z\ ]/g,'');
        });

        $('.savings_bank_account_no').keyup(function () { 
            this.value = this.value.replace(/[^0-9\ ]/g,'');
        });

        $('.aadhaar_no').keyup(function () { 
            this.value = this.value.replace(/[^0-9\ ]/g,'');
        });

        $('.mobile_no').keyup(function () { 
            this.value = this.value.replace(/[^0-9\ ]/g,'');
        });

        $('.pension_amount_share_percentage').keyup(function () { 
            this.value = this.value.replace(/[^0-9\ ]/g,'');
        });

        $('.disability_percentage').keyup(function () { 
            this.value = this.value.replace(/[^0-9\ ]/g,'');
        });

        $('.total_income_per_annum').keyup(function () { 
            this.value = this.value.replace(/[^0-9\ ]/g,'');
        });

        $('.legal_guardian_age').keyup(function () { 
            this.value = this.value.replace(/[^0-9\ ]/g,'');
        });

        $('.nominee_name').each(function() {
            $(this).rules("add", {                
                required: {
                        depends:function() {
                            $(this).val($.trim($(this).val()));
                            return true; 
                        }
                    },
                minlength: 4,
                maxlength: 50,
                messages: {
                    required: "Please enter nominee name",
                    minlength: 'nominee name minimum 4 characters',
                    maxlength: 'nominee name maximum 50 characters'
                }
            });
        });

        $('.date_of_birth').on('change', function(){
            $(this).valid();
        });

        $('.date_of_birth').each(function() {
            $(this).rules("add", {
                required: true,
                messages: {
                    required: "Please select date of birth",
                }
            });
        });

        $('.gender').on('change', function(){
            $(this).valid();
        });

        $('.gender').each(function() {
            $(this).rules("add", {
                required: true,
                messages: {
                    required: "Please select gender",
                }
            });
        });

        $('.relation_with_pensioner').on('change', function(){
            $(this).valid();
        });

        $('.relation_with_pensioner').each(function() {
            $(this).rules("add", {
                required: true,
                messages: {
                    required: "Please select relation with pensioner",
                }
            });
        });

        $('.marital_status').on('change', function(){
            $(this).valid();
        });

        $('.marital_status').each(function() {
            $(this).rules("add", {
                required: true,
                messages: {
                    required: "Please select marital status",
                }
            });
        });

        $('.aadhaar_no').each(function() {
            var thisValue = $(this);
            $(this).rules("add", {
                required: true,
                onlyNumber: true,
                minlength: 12,
                maxlength: 12,
                remote:{
                        url:'{{ route("validate_aadhar_number") }}',
                        type:"post",
                        data:{
                            '_token': function() {
                               return '{{ csrf_token() }}';
                            },
                            'aadhaar_no': function() {
                               return thisValue.val();
                            }
                        }
                    },
                messages: {
                    required: "Please enter Aadhaar no",
                    minlength: 'Aadhaar no must be 12 digits',
                    maxlength: 'Aadhaar no must be 12 digits',
                    remote: 'Aadhaar No already exists',
                }
            });
        });

        $('.mobile_no').each(function() {
            var thisValue = $(this);
            $(this).rules("add", {
                required: true,
                onlyNumber: true,
                minlength: 10,
                maxlength: 10,
                remote: {
                        url:'{{ route("validate_mobile_number") }}',
                        type:"post",
                        data:{
                            '_token': function() {
                               return '{{ csrf_token() }}';
                            },
                            'mobile_no': function() {
                               return thisValue.val();
                            }
                        }
                    },
                messages: {
                    required: "Please enter mobile no",
                    minlength: 'Mobile no must be 10 digits',
                    maxlength: 'Mobile no must be 10 digits',
                    remote: 'Mobile no already exits',
                }
            });
        });


        $('.savings_bank_account_no').each(function() {
            var thisValue = $(this);
            $(this).rules("add", {
                required: true,
                onlyNumber: true,
                minlength: 9,
                maxlength: 18,
                remote: {
                        url:'{{ route("validate_account") }}',
                        type:"post",
                        data:{
                            '_token': function() {
                               return '{{ csrf_token() }}';
                            },
                            'saving_bank_ac_no': function() {
                               return thisValue.val();
                            }
                        }
                    },
                messages: {
                    required: "Please enter savings bank account no",
                    minlength: 'Savings Bank account no minimum 9 digits',
                    maxlength: 'Savings Bank account no maximum 18 digits',
                    remote: 'A/C no already exitss',
                }
            });
        });

        $('.bank').on('change', function(){
            $(this).valid();
        });

        $('.bank').each(function() {
            $(this).rules("add", {
                required: true,
                messages: {
                    required: "Please select bank",
                }
            });
        });

        $('.branch').on('change', function(){
            $(this).valid();
        });

        $('.branch').each(function() {
            $(this).rules("add", {
                required: true,
                messages: {
                    required: "Please select branch",
                }
            });
        });

        $('.total_income_per_annum').each(function() {
            $(this).rules("add", {
                required: true,
                maxlength: 10,
                messages: {
                    required: "Please enter total income per annum",
                    maxlength: "Total income per annum maximum 10 digits"
                }
            });
        });

        $('.nominee_preference_id').each(function() {
            $(this).rules("add", {
                required: true,
                messages: {
                    required: "Please select nominee preference",
                }
            });
        });

        $('.employement_status').on('change', function(){
            $(this).valid();
        });

        $('.employement_status').each(function() {
            $(this).rules("add", {
                required: true,
                messages: {
                    required: "Please select employement status",
                }
            });
        });

        $('.is_physically_handicapped').on('change', function(){
            $(this).valid();
        });

        $('.is_physically_handicapped').each(function() {
            $(this).rules("add", {
                required: true,
                messages: {
                    required: "Please select physically handicapped",
                }
            });
        });

        $('.pension_amount_share_percentage').each(function() {
            $(this).rules("add", {
                required: true,
                onlyNumber: true,
                maxlength: 10,
                messages: {
                    required: "Please enter amount / share payable to each",
                    maxlength: "Amount / share payable maximum 10 digits"
                }
            });
        });

        $('.is_minor').on('change', function(){
            $(this).valid();
        });

        $('.is_minor').each(function() {
            $(this).rules("add", {
                required: true,
                messages: {
                    required: "Please select minor",
                }
            });
        });
    }

    function validate_relation() {
        $('.is_2nd_spouse').each(function() {
            $(this).rules("add", {
                required: true,
                messages: {
                    required: "Please select spouse type",
                }
            });
        });
    }

    function validate_relation_spouse() {
        $('.1st_spouse_death_date').each(function() {
            $(this).rules("add", {
                required: true,
                messages: {
                    required: "Please select death date of spouse",
                }
            });
        });        
    }

    function validate_minor() {
        $('.legal_guardian_name').each(function() {
            $(this).rules("add", {
                required: {
                        depends:function() {
                            $(this).val($.trim($(this).val()));
                            return true; 
                        }
                    },
                messages: {
                    required: "Please enter leagal guardian name",
                }
            });
        });

        $('.legal_guardian_age').each(function() {
            $(this).rules("add", {
                required: true,
                maxlength: 2,
                messages: {
                    required: "Please enter leagal guardian age",
                    required: 'Legal guardian age maximum 2 digits',
                }
            });
        });

        $('.legal_guardian_addr').each(function() {
            $(this).rules("add", {
                required: {
                        depends:function() {
                            $(this).val($.trim($(this).val()));
                            return true; 
                        }
                    },
                addressReg: true,
                messages: {
                    required: "Please enter address of legal guardian",
                }
            });
        });
    }

    function validate_physically_handicap() {
        $('.disability_percentage').each(function() {
            $(this).rules("add", {
                required: true,
                onlyNumber: true,
                messages: {
                    required: "Please enter disability percentage",
                }
            });
        });
    }

    function validate_attachment(id) {

        if(id == 'dob_attachment_path') {
            $('.dob_attachment_path').each(function() {

                let edit = $(this).data('edit');

                if(edit == 1) {
                } else {
                    $(this).rules("add", {
                        required: true,
                        messages: {
                            required: "Please upload proof of date of birth",
                        }
                    });
                }
            });
        }

        if(id == '1st_spouse_death_certificate_path') {
            $('.1st_spouse_death_certificate_path').each(function() {
                
                let edit = $(this).data('edit');

                if(edit == 1) {
                } else {
                    $(this).rules("add", {
                        required: true,
                        messages: {
                            required: "Please upload 1st wife death certificate",
                        }
                    });
                }
            });
        }

        if(id == 'disability_certificate_path') {
            $('.disability_certificate_path').each(function() {
                let edit = $(this).data('edit');

                if(edit == 1) {
                } else {
                    $(this).rules("add", {
                        required: true,
                        messages: {
                            required: "Please upload disability certificate",
                        }
                    });
                }
            });
        }

        if(id == 'legal_guardian_attachment_path') {
            $('.legal_guardian_attachment_path').each(function() {
                let edit = $(this).data('edit');

                if(edit == 1) {
                } else {
                    $(this).rules("add", {
                        required: true,
                        messages: {
                            required: "Please upload legal guardian attachment",
                        }
                    });
                }
            });
        }
    }

    function check_upload_file(ele, id) {
        $(ele).parent().find('.form-control').val($(ele).val().replace(/C:\\fakepath\\/i, ''));

        $("#" + id + "-error").html("");
        
        var val = ele.value;

        if(val.indexOf('.') !== -1) {
            var ext = ele.value.match(/\.(.+)$/)[1];
            var size = ele.files[0].size;

            if(size > 5000000) {
                $("#" + id + "-error").html('File size less than 5MB allowed');
                $("#" + id + "-error").show();
                ele.value = '';
                $(ele).parent().find('.form-control').val('');
            } else {
                switch (ext) {
                    case 'png':
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
                        break;
                    /*case 'pdf':
                        $("#" + id + "-error").html('');
                        $("#" + id + "-error").hide();
                        break;*/
                    default:
                        $("#" + id + "-error").html('Please upload only jpg, jpeg, png file');
                        $("#" + id + "-error").show();
                        ele.value = '';
                        $(ele).parent().find('.form-control').val('');
                }
            }
        } else {
            $("#" + id + "-error").html('Invalid file type');
            $("#" + id + "-error").show();
            ele.value = '';

            $(ele).parent().find('.form-control').val('');
        }
    }
</script>

@endsection
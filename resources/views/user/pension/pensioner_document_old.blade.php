@extends('user.layout.layout')

@section('section_content')

<style type="text/css">
    .error {
        display: none;
    }
</style>

<div class="content-wrapper">
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
                            <a class="nav-link" id="home-tab" href="{{ route('edit_pensioner_form') }}">1. PENSION/FAMILY PENSION FORM</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="{{ route('edit_personal_details') }}">2. PERSONAL DETAILS</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="home-tab" href="{{ route('nominee_form') }}">3. NOMINEES</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active-tab" id="contact-tab" data-toggle="tab" href="#contact-1" role="tab" aria-controls="contact-1" aria-selected="true">4. LIST OF DOCUMENTS</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div>
                            <h4 class="text-center">LIST OF DOCUMENTS (To be submitted by the applicant for service or family pension as per applicability)</h4>
                            <h4 class="text-center">PART I (B)</h4>
                            <br />
                            <form id="pension-documents" method="POST" class="forms-sample" enctype="multipart/form-data">
                                @csrf

                                <input type="hidden" name="employee_id" value="{{ $id }}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Attach recent passport size photograph with spouse (if married) and single photograph of self (applicant) duly attested<span class="text-danger">*</span></label>
                                            <input type="file" name="attached_recent_passport" id="attached_recent_passport" class="file-upload-default">
                                            <div class="input-group col-xs-12">
                                                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                                <div class="input-group-append">
                                                    <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                                </div>
                                            </div>
                                            <label id="attached_recent_passport-error" class="error mt-2 text-danger" for="attached_recent_passport">Please select the file</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Attach attested copies of the date of birth certificates of all family members & nominee(s)<span class="text-danger">*</span></label>
                                            <input type="file" name="attached_dob_certificate" id="attached_dob_certificate" class="file-upload-default">
                                            <div class="input-group col-xs-12">
                                                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                                <div class="input-group-append">
                                                    <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                                </div>
                                            </div>
                                            <label id="attached_dob_certificate-error" class="error mt-2 text-danger" for="attached_dob_certificate">Please select the file</label>
                                        </div>    
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Attach the Undertaking / Declaration for refund / recovery / of outstanding dues / excess payment wherever applicable<span class="text-danger">*</span></label>
                                            <input type="file" name="attached_undertaking_declaration" id="attached_undertaking_declaration" class="file-upload-default">
                                            <div class="input-group col-xs-12">
                                                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                                <div class="input-group-append">
                                                    <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                                </div>
                                            </div>
                                            <label id="attached_undertaking_declaration-error" class="error mt-2 text-danger" for="attached_undertaking_declaration">Please select the file</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Attach attested copy of the first page of Bank Pass Book with IFSC and MICR code<span class="text-danger">*</span></label>
                                            <input type="file" name="attached_bank_passbook" id="attached_bank_passbook" class="file-upload-default">
                                            <div class="input-group col-xs-12">
                                                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                                <div class="input-group-append">
                                                    <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                                </div>
                                            </div>
                                            <label id="attached_bank_passbook-error" class="error mt-2 text-danger" for="attached_bank_passbook">Please select the file</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Attach copy of cancelled cheque issued by the concerned bank<span class="text-danger">*</span> </label>
                                            <input type="file" name="attached_cancelled_chqeue" id="attached_cancelled_chqeue" class="file-upload-default">
                                            <div class="input-group col-xs-12">
                                                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                                <div class="input-group-append">
                                                    <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                                </div>
                                            </div>
                                            <label id="attached_cancelled_chqeue-error" class="error mt-2 text-danger" for="attached_cancelled_chqeue">Please select the file</label>
                                        </div>
                                    </div>

                                    @if($employee_master->date_of_joining >= '1991-03-31' && $employee_master->pf_account_type_id == 1)
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Attach Indemnity Bond (For the PF account which have not been transferred from the RPFC)<span class="text-danger">*</span></label>
                                            <input type="file" name="attached_indemnity_bond" id="attached_indemnity_bond" class="file-upload-default" required>
                                            <div class="input-group col-xs-12">
                                                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                                <div class="input-group-append">
                                                    <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                                </div>
                                            </div>
                                            <label id="attached_indemnity_bond-error" class="error mt-2 text-danger" for="attached_indemnity_bond">Please select the file</label>
                                        </div>
                                    </div>
                                    @else
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Attach Indemnity Bond (For the PF account which have not been transferred from the RPFC)<span class="text-danger">*</span></label>
                                            <input type="file" name="attached_indemnity_bond" id="attached_indemnity_bond" class="file-upload-default">
                                            <div class="input-group col-xs-12">
                                                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                                <div class="input-group-append">
                                                    <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                                </div>
                                            </div>
                                            <label id="attached_indemnity_bond-error" class="error mt-2 text-danger" for="attached_indemnity_bond">Please select the file</label>
                                        </div>
                                    </div>
                                    @endif

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Attach three descriptive roll slips each bearing three specimen signatures (L.T.I if illiterate). Particulars of height and identification marks (at least 2 conspicuous marks) and left hand thumb and all fingers impression of the applicant duly attested by the head of office.<span class="text-danger">*</span></label>
                                            <input type="file" name="attached_descriptive_roll_slips" id="attached_descriptive_roll_slips" class="file-upload-default">
                                            <div class="input-group col-xs-12">
                                                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                                <div class="input-group-append">
                                                    <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                                </div>
                                            </div>
                                            <label id="attached_descriptive_roll_slips-error" class="error mt-2 text-danger" for="attached_descriptive_roll_slips">Please select the file</label>
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('nominee_form') }}" class="btn btn-default mr-2 btn-prev">PREVIOUS</a>
                                <button type="submit" class="btn btn-primary mr-2">Apply</button>
                            </form>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- content-wrapper ends -->

@endsection

@section('page-script')
<script type="text/javascript">
    $(document).ready(function() {

        $('ul.nav li a').click(function(e) {
            $(this).addClass('step_active').removeClass('step_inactive');
        });

        /*$(document).on('click', '.file-upload-browse', function() {
            var file = $(this).parent().parent().parent().find('.file-upload-default');
            file.trigger('click');
        });*/
        
        $('#attached_recent_passport').on('change', function() {
            check_upload_file(this, 'attached_recent_passport');
        });

        $('#attached_dob_certificate').on('change', function() {
            check_upload_file(this, 'attached_dob_certificate');
        });

        $('#attached_undertaking_declaration').on('change', function() {
            check_upload_file(this, 'attached_undertaking_declaration');
        });

        $('#attached_bank_passbook').on('change', function() {
            check_upload_file(this, 'attached_bank_passbook');
        });

        $('#attached_cancelled_chqeue').on('change', function() {
            check_upload_file(this, 'attached_cancelled_chqeue');
        });

        $('#attached_indemnity_bond').on('change', function() {
            check_upload_file(this, 'attached_indemnity_bond');
        });

        $('#attached_descriptive_roll_slips').on('change', function() {
            check_upload_file(this, 'attached_descriptive_roll_slips');
        });

        $("#pension-documents").validate({
            rules: {
                attached_recent_passport: {
                    required: true,
                },
                attached_dob_certificate: {
                    required: true,
                },
                attached_undertaking_declaration: {
                    required: true,
                },
                attached_bank_passbook: {
                    required: true,
                },
                attached_cancelled_chqeue: {
                    required: true,
                },
                /*attached_indemnity_bond: {
                    required: true,
                },*/
                attached_descriptive_roll_slips: {
                    required: true,
                },
            },
            messages: {
                attached_recent_passport: {
                    required: 'Please select the file'
                },
                attached_dob_certificate: {
                    required: 'Please select the file'
                },
                attached_undertaking_declaration: {
                    required: 'Please select the file'
                },
                attached_bank_passbook: {
                    required: 'Please select the file'
                },
                attached_cancelled_chqeue: {
                    required: 'Please select the file'
                },
                attached_indemnity_bond: {
                    required: 'Please select the file'
                },
                attached_descriptive_roll_slips: {
                    required: 'Please select the file'
                },
            },
            submitHandler: function(form, event) {
                $('.page-loader').addClass('d-flex'); 
                event.preventDefault();
                var formData = new FormData(form);

                $.ajax({
                    type:'POST',
                    url:'{{ route("save_pension_documents") }}',
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
                        } else if(response['loginCheckMessage']) {
                            // location.href = "{{route('pensioner_form')}}";
                        } else {
                            location.href = "{{route('user_dashboard')}}";
                        }
                    }
                });
            },
            errorPlacement: function(label, element) {
                label.addClass('mt-2 text-danger');
                label.insertAfter(element);
            },
            highlight: function(element, errorClass) {
                $(element).parent().addClass('has-success');
                $(element).addClass('form-control-danger');
            }
        });
    });

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
                    case 'pdf':
                        $("#" + id + "-error").html('');
                        $("#" + id + "-error").hide();
                        break;
                    default:
                        $("#" + id + "-error").html('Please upload only jpg, jpeg, png or pdf file');
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
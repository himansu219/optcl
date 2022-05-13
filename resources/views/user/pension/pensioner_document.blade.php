@extends('user.layout.layout')

@section('section_content')

<style type="text/css">
    .error {
        display: none;
    }
    .mrgtop {
        margin-top: 10px;
    }
    .document_img {
        width: 70px !important; 
        height: 70px !important; 
        border-radius: 0 !important;
    }
    .img-container img {
      max-width: 100%;
    }
    .check-circle {
        margin-top: 5px;
        margin-right: 7px;
    }
    .circle-icon {
        color: green;
    }
    .img-icon {
        margin-top: 14px;
    }
    #upload-demo{
        width: 450px;
        height: 300px;
        padding-bottom:25px;
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
                            <a class="nav-link" id="home-tab" href="{{ route('edit_pensioner_form') }}">1. PENSION DETAILS</a>
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

                                <input type="hidden" name="employee_id" id="employee_id" value="{{ $id }}">
                                <input type="hidden" name="edit" id="edit" value="{{ $edit }}">

                                @php
                                    $attach_one = DB::table('optcl_pension_application_document')->where('employee_id', $id)->where('document_id', 1)->where('deleted', 0)->first();
                                @endphp
                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-6 col-form-label">Attach recent passport size photograph with spouse (if married) and single photograph of self (applicant) duly attested<span class="text-danger">*</span></label>
                                    <div class="col-sm-5 mrgtop">
                                        <input type="file" name="attached_recent_passport" id="attached_recent_passport" class="file-upload-default" @if(empty($attach_one)) required @endif>
                                        <div class="input-group">
                                            <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                            <div class="input-group-append">
                                                <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                            </div>                                            
                                        </div>
                                        <label id="attached_recent_passport-error" class="error mt-2 text-danger" for="attached_recent_passport"></label>
                                    </div>

                                    <div class="col-sm-1">
       
                                        @if(!empty($attach_one) && !empty($attach_one->document_attachment_path))
                                        <span class="check-circle" id="attached_recent_passport_check"><i class="fa fa-check-circle fa-2x circle-icon"></i></span>
                                        <span class="document_img_span" id="attached_recent_passport_img" data-img="{{ asset('public/' . $attach_one->document_attachment_path) }}"><i class="fa fa-file-image-o fa-2x img-icon"></i></span>
                                        @else
                                            <span class="check-circle d-none" id="attached_recent_passport_check"><i class="fa fa-check-circle fa-2x circle-icon"></i></span>
                                            <span class="document_img_span d-none" id="attached_recent_passport_img"><i class="fa fa-file-image-o fa-2x img-icon"></i></span>
                                        @endif
                                    </div>
                                </div>

                                @php
                                    $attach_three = DB::table('optcl_pension_application_document')->where('employee_id', $id)->where('document_id', 3)->where('deleted', 0)->first();
                                @endphp
                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-6 col-form-label">Attach attested copies of the date of birth certificates of all family members & nominee(s)<span class="text-danger">*</span></label>
                                    <div class="col-sm-5 mrgtop">
                                        <input type="file" name="attached_dob_certificate" id="attached_dob_certificate" class="file-upload-default" @if(empty($attach_three)) required @endif>
                                        <div class="input-group col-xs-12">
                                            <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                            <div class="input-group-append">
                                                <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                            </div>                                            
                                        </div>
                                        <label id="attached_dob_certificate-error" class="error mt-2 text-danger" for="attached_dob_certificate"></label>
                                    </div>
                                    <div class="col-sm-1">

                                        @if(!empty($attach_three) && !empty($attach_three->document_attachment_path))
                                            <span class="check-circle" id="attached_dob_certificate_check"><i class="fa fa-check-circle fa-2x circle-icon"></i></span>
                                            <span class="document_img_span" id="attached_dob_certificate_img" data-img="{{ asset('public/' . $attach_three->document_attachment_path) }}"><i class="fa fa-file-image-o fa-2x img-icon"></i></span>
                                        @else
                                            <span class="check-circle d-none" id="attached_dob_certificate_check"><i class="fa fa-check-circle fa-2x circle-icon"></i></span>
                                            <span class="document_img_span d-none" id="attached_dob_certificate_img"><i class="fa fa-file-image-o fa-2x img-icon"></i></span>
                                        @endif
                                    </div>
                                </div>

                                @php
                                    $attach_four = DB::table('optcl_pension_application_document')->where('employee_id', $id)->where('document_id', 4)->where('deleted', 0)->first();
                                @endphp
                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-6 col-form-label">Attach the Undertaking / Declaration for refund / recovery / of outstanding dues / excess payment wherever applicable<span class="text-danger">*</span></label>
                                    <div class="col-sm-5 mrgtop">
                                        <input type="file" name="attached_undertaking_declaration" id="attached_undertaking_declaration" class="file-upload-default" @if(empty($attach_four)) required @endif>
                                        <div class="input-group col-xs-12">
                                            <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                            <div class="input-group-append">
                                                <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                            </div>                                            
                                        </div>
                                        <label id="attached_undertaking_declaration-error" class="error mt-2 text-danger" for="attached_undertaking_declaration"></label>
                                    </div>
                                    <div class="col-sm-1">

                                        @if(!empty($attach_four) && !empty($attach_four->document_attachment_path))
                                            <span class="check-circle" id="attached_undertaking_declaration_check"><i class="fa fa-check-circle fa-2x circle-icon"></i></span>
                                            <span class="document_img_span" id="attached_undertaking_declaration_img" data-img="{{ asset('public/' . $attach_four->document_attachment_path) }}"><i class="fa fa-file-image-o fa-2x img-icon"></i></span>
                                        @else
                                            <span class="check-circle d-none" id="attached_undertaking_declaration_check"><i class="fa fa-check-circle fa-2x circle-icon"></i></span>
                                            <span class="document_img_span d-none" id="attached_undertaking_declaration_img"><i class="fa fa-file-image-o fa-2x img-icon"></i></span>
                                        @endif
                                    </div>
                                </div>

                                @php
                                    $attach_five = DB::table('optcl_pension_application_document')->where('employee_id', $id)->where('document_id', 5)->where('deleted', 0)->first();
                                @endphp
                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-6 col-form-label">Attach attested copy of the first page of Bank Pass Book with IFSC and MICR code<span class="text-danger">*</span></label>
                                    <div class="col-sm-5 mrgtop">
                                        <input type="file" name="attached_bank_passbook" id="attached_bank_passbook" class="file-upload-default" @if(empty($attach_five)) required @endif>
                                        <div class="input-group col-xs-12">
                                            <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                            <div class="input-group-append">
                                                <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                            </div>
                                        </div>
                                        <label id="attached_bank_passbook-error" class="error mt-2 text-danger" for="attached_bank_passbook"></label>
                                    </div>
                                    <div class="col-sm-1">
                                        @if(!empty($attach_five) && !empty($attach_five->document_attachment_path))
                                            <span class="check-circle" id="attached_bank_passbook_check"><i class="fa fa-check-circle fa-2x circle-icon"></i></span>
                                            <span class="document_img_span" id="attached_bank_passbook_img" data-img="{{ asset('public/' . $attach_five->document_attachment_path) }}"><i class="fa fa-file-image-o fa-2x img-icon"></i></span>
                                        @else
                                            <span class="check-circle d-none" id="attached_bank_passbook_check"><i class="fa fa-check-circle fa-2x circle-icon"></i></span>
                                            <span class="document_img_span d-none" id="attached_bank_passbook_img"><i class="fa fa-file-image-o fa-2x img-icon"></i></span>
                                        @endif
                                    </div>
                                </div>

                                @php
                                    $attach_six = DB::table('optcl_pension_application_document')->where('employee_id', $id)->where('document_id', 6)->where('deleted', 0)->first();
                                @endphp
                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-6 col-form-label">Attach copy of cancelled cheque issued by the concerned bank<span class="text-danger">*</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                    <div class="col-sm-5 mrgtop">
                                        <input type="file" name="attached_cancelled_chqeue" id="attached_cancelled_chqeue" class="file-upload-default" @if(empty($attach_six)) required @endif>
                                        <div class="input-group col-xs-12">
                                            <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                            <div class="input-group-append">
                                                <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                            </div>
                                        </div>
                                        <label id="attached_cancelled_chqeue-error" class="error mt-2 text-danger" for="attached_cancelled_chqeue"></label>
                                    </div>
                                    <div class="col-sm-1">
                                        @if(!empty($attach_six) && !empty($attach_six->document_attachment_path))
                                            <span class="check-circle" id="attached_cancelled_chqeue_check"><i class="fa fa-check-circle fa-2x circle-icon"></i></span>
                                            <span class="document_img_span" id="attached_cancelled_chqeue_img" data-img="{{ asset('public/' . $attach_six->document_attachment_path) }}"><i class="fa fa-file-image-o fa-2x img-icon"></i></span>
                                        @else
                                            <span class="check-circle d-none" id="attached_cancelled_chqeue_check"><i class="fa fa-check-circle fa-2x circle-icon"></i></span>
                                            <span class="document_img_span d-none" id="attached_cancelled_chqeue_img"><i class="fa fa-file-image-o fa-2x img-icon"></i></span>
                                        @endif
                                    </div>
                                </div>

                                @php
                                    $attach_seven = DB::table('optcl_pension_application_document')->where('employee_id', $id)->where('document_id', 7)->where('deleted', 0)->first();
                                @endphp

                                @if(!empty($employee_master->date_of_joining) && $employee_master->date_of_joining <= '1991-03-31' && $employee_master->pf_account_type_id == 1)
                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-6 col-form-label">Attach Indemnity Bond (For the PF account which have not been transferred from the RPFC)<span class="text-danger">*</span></label>
                                    <div class="col-sm-5 mrgtop">
                                        <input type="file" name="attached_indemnity_bond" id="attached_indemnity_bond" class="file-upload-default" @if(empty($attach_seven)) required @endif>
                                        <div class="input-group col-xs-12">
                                            <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                            <div class="input-group-append">
                                                <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                            </div>                                            
                                        </div>
                                        <label id="attached_indemnity_bond-error" class="error mt-2 text-danger" for="attached_indemnity_bond"></label>
                                    </div>
                                    <div class="col-sm-1">
                                        @if(!empty($attach_seven) && !empty($attach_seven->document_attachment_path))
                                            <span class="check-circle" id="attached_indemnity_bond_check"><i class="fa fa-check-circle fa-2x circle-icon"></i></span>
                                            <span class="document_img_span" id="attached_indemnity_bond_img" data-img="{{ asset('public/' . $attach_seven->document_attachment_path) }}"><i class="fa fa-file-image-o fa-2x img-icon"></i></span>
                                        @else
                                            <span class="check-circle d-none" id="attached_indemnity_bond_check"><i class="fa fa-check-circle fa-2x circle-icon"></i></span>
                                            <span class="document_img_span d-none" id="attached_indemnity_bond_img"><i class="fa fa-file-image-o fa-2x img-icon"></i></span>
                                        @endif
                                    </div>
                                </div>
                                @else
                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-6 col-form-label">Attach Indemnity Bond (For the PF account which have not been transferred from the RPFC)</label>
                                    <div class="col-sm-5 mrgtop">
                                        <input type="file" name="attached_indemnity_bond" id="attached_indemnity_bond" class="file-upload-default">
                                        <div class="input-group col-xs-12">
                                            <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                            <div class="input-group-append">
                                                <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                            </div>
                                        </div>
                                        <label id="attached_indemnity_bond-error" class="error mt-2 text-danger" for="attached_indemnity_bond"></label>
                                    </div>
                                    <div class="col-sm-1">
                                        @if(!empty($attach_seven) && !empty($attach_seven->document_attachment_path))
                                            <span class="check-circle" id="attached_indemnity_bond_check"><i class="fa fa-check-circle fa-2x circle-icon"></i></span>
                                            <span class="document_img_span" id="attached_indemnity_bond_img" data-img="{{ asset('public/' . $attach_seven->document_attachment_path) }}"><i class="fa fa-file-image-o fa-2x img-icon"></i></span>
                                        @else
                                            <span class="check-circle d-none" id="attached_indemnity_bond_check"><i class="fa fa-check-circle fa-2x circle-icon"></i></span>
                                            <span class="document_img_span d-none" id="attached_indemnity_bond_img"><i class="fa fa-file-image-o fa-2x img-icon"></i></span>
                                        @endif
                                    </div>
                                </div>
                                @endif

                                @php
                                    $attach_two = DB::table('optcl_pension_application_document')->where('employee_id', $id)->where('document_id', 2)->where('deleted', 0)->first();
                                @endphp
                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-6 col-form-label">Attach three descriptive roll slips each bearing three specimen signatures (L.T.I if illiterate). Particulars of height and identification marks (at least 2 conspicuous marks) and left hand thumb and all fingers impression of the applicant duly attested by the head of office.<span class="text-danger">*</span></label>
                                    <div class="col-sm-5 mrgtop">
                                        <input type="file" name="attached_descriptive_roll_slips" id="attached_descriptive_roll_slips" class="file-upload-default" @if(empty($attach_two)) required @endif>
                                        <div class="input-group col-xs-12">
                                            <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                            <div class="input-group-append">
                                                <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                            </div>
                                            
                                        </div>
                                        <label id="attached_descriptive_roll_slips-error" class="error mt-2 text-danger" for="attached_descriptive_roll_slips"></label>
                                    </div>
                                    <div class="col-sm-1">
                                        @if(!empty($attach_two) && !empty($attach_two->document_attachment_path))
                                            <span class="check-circle" id="attached_descriptive_roll_slips_check"><i class="fa fa-check-circle fa-2x circle-icon"></i></span>
                                            <span class="document_img_span" id="attached_descriptive_roll_slips_img" data-img="{{ asset('public/' . $attach_two->document_attachment_path) }}"><i class="fa fa-file-image-o fa-2x img-icon"></i></span>
                                        @else
                                            <span class="check-circle d-none" id="attached_descriptive_roll_slips_check"><i class="fa fa-check-circle fa-2x circle-icon"></i></span>
                                            <span class="document_img_span d-none" id="attached_descriptive_roll_slips_img"><i class="fa fa-file-image-o fa-2x img-icon"></i></span>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('nominee_form') }}" class="btn btn-default mr-2 btn-prev">PREVIOUS</a>
                                <button type="submit" class="btn btn-primary mr-2">Save & Preview</button>
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
        $("#pension-documents").validate({
            rules: {
                /*attached_recent_passport: {
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
                attached_indemnity_bond: {
                    required: true,
                },
                attached_descriptive_roll_slips: {
                    required: true,
                },*/
            },
            messages: {
                attached_recent_passport: {
                    required: 'Please upload Image'
                },
                attached_dob_certificate: {
                    required: 'Please upload Image'
                },
                attached_undertaking_declaration: {
                    required: 'Please upload Image'
                },
                attached_bank_passbook: {
                    required: 'Please upload Image'
                },
                attached_cancelled_chqeue: {
                    required: 'Please upload Image'
                },
                attached_indemnity_bond: {
                    required: 'Please upload Image'
                },
                attached_descriptive_roll_slips: {
                    required: 'Please upload Image'
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
                            location.href = "{{route('application_preview')}}";
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
</script>

<script type="text/javascript">
    $(document).ready(function() {

        var $uploadCrop,
        rawImg,
        imageId;

        $('ul.nav li a').click(function(e) {
            $(this).addClass('step_active').removeClass('step_inactive');
        });

        $('#crop_image').on('hidden.bs.modal', function(){
            var filename = $(this).closest('#crop_image').find($('#file_name'));
            var val = $(filename).val();

            $('#'+val).parent().find('.file-upload-info').val('');

            $('#'+val).val('');

            $('#upload-demo').croppie('destroy');
        });

        /*$(document).on('click', '.file-upload-browse', function() {
            var file = $(this).parent().parent().parent().find('.file-upload-default');
            file.trigger('click');
        });*/
        
        $('#attached_recent_passport').on('change', function() {
            check_upload_file(this, 'attached_recent_passport');

            if (this.files && this.files[0]) {
              var reader = new FileReader();
                reader.onload = function (e) {                    
                    $('#crop_image').modal('show');
                    rawImg = e.target.result;
                }
                reader.readAsDataURL(this.files[0]);
            }

            $('#file_name').val('attached_recent_passport');

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

        $('#attached_dob_certificate').on('change', function() {
            check_upload_file(this, 'attached_dob_certificate');

            if (this.files && this.files[0]) {
              var reader = new FileReader();
                reader.onload = function (e) {
                    $('#crop_image').modal('show');
                    rawImg = e.target.result;
                }
                reader.readAsDataURL(this.files[0]);
            }

            $('#file_name').val('attached_dob_certificate');

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

        $('#attached_undertaking_declaration').on('change', function() {
            check_upload_file(this, 'attached_undertaking_declaration');

            if (this.files && this.files[0]) {
              var reader = new FileReader();
                reader.onload = function (e) {
                    $('#crop_image').modal('show');
                    rawImg = e.target.result;
                }
                reader.readAsDataURL(this.files[0]);
            }

            $('#file_name').val('attached_undertaking_declaration');

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

        $('#attached_bank_passbook').on('change', function() {
            check_upload_file(this, 'attached_bank_passbook');

            if (this.files && this.files[0]) {
              var reader = new FileReader();
                reader.onload = function (e) {
                    $('#crop_image').modal('show');
                    rawImg = e.target.result;
                }
                reader.readAsDataURL(this.files[0]);
            }

            $('#file_name').val('attached_bank_passbook');

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

        $('#attached_cancelled_chqeue').on('change', function() {
            check_upload_file(this, 'attached_cancelled_chqeue');

            if (this.files && this.files[0]) {
              var reader = new FileReader();
                reader.onload = function (e) {
                    $('#crop_image').modal('show');
                    rawImg = e.target.result;
                }
                reader.readAsDataURL(this.files[0]);
            }

            $('#file_name').val('attached_cancelled_chqeue');

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

        $('#attached_indemnity_bond').on('change', function() {
            check_upload_file(this, 'attached_indemnity_bond');

            if (this.files && this.files[0]) {
              var reader = new FileReader();
                reader.onload = function (e) {                    
                    $('#crop_image').modal('show');
                    rawImg = e.target.result;
                }
                reader.readAsDataURL(this.files[0]);
            }

            $('#file_name').val('attached_indemnity_bond');

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

        $('#attached_descriptive_roll_slips').on('change', function() {
            check_upload_file(this, 'attached_descriptive_roll_slips');

            if (this.files && this.files[0]) {
              var reader = new FileReader();
                reader.onload = function (e) {
                    $('#crop_image').modal('show');
                    rawImg = e.target.result;
                }
                reader.readAsDataURL(this.files[0]);
            }

            $('#file_name').val('attached_descriptive_roll_slips');

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
                    url: "{{ route('save_pension_documents') }}",
                    type: "POST",
                    data: formData,
                    contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
                    processData: false, // NEEDED, DON'T OMIT THIS
                    success:function(response) {
                        $('#'+file_name).val('');
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
                            /*$("#"+file_name+"_img").attr("data-img", avatar).removeClass('d-none');
                            $("#"+file_name+"_check").removeClass('d-none');*/

                            $("#"+file_name+"_img").attr("data-img", avatar).removeClass('d-none');
                            $("#"+file_name+"_check").removeClass('d-none');

                            $("#"+file_name).attr('required', false);

                            $('#upload-demo').croppie('destroy');
                        }
                    },
                    error:function(response) {

                    }
                });
            });
        });
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
</script>
@endsection
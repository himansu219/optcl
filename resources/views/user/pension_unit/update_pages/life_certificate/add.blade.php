@extends('user.layout.layout')
@section('section_content')
<style type="text/css">
	.document_img {
	    width: 70px !important; 
	    height: 70px !important; 
	    border-radius: 0 !important;
	}
	.widt-50 {
		width: 50%
	}
    .marg-left {
        margin-left: 0px;
    }
    .marg-left-col {
        margin-left: 20px;
    }
    .error {
        /*margin-top: 10px;*/
    }
    .del-recovery-btn{
        margin-top: 20px;
    }
    .fsize {
        font-size: 13px !important;
    }
    .service-table {
        margin-bottom: 10px;
        margin-top: 10px;
    }
    .fa-check {
        color: green !important;
    }
    .fa-times {
        color: #DB504A !important;
    }
    .mrgtop {
        margin-top: 10px;
    }
    #form_is_checked {
        margin-left: 0px;
    }
    .service_period_duly, .service_period_absence {
        display: none;
    }
    .radio-margleft{
        margin-left: 5px;
    }
    .recovery-btn-group {
        margin-top: 31px;
    }
</style>
<div class="content-wrapper">
	<div class="row">
		<div class="col-12 grid-margin">
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('user_dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item">Update Pension Record</li>
                    <li class="breadcrumb-item"><a href="{{ route('pension_unit_life_certificate_list_page') }}">Life Certificate</a></li>
                    <li class="breadcrumb-item">Import Life Certificate</li>
                </ol>
            </nav> 
            <div class="card">
                <div class="card-body">
                <h4 class="card-title">Import Life Certificate</h4>
                <form method="post" action="" autocomplete="off" id="life_certificate_form" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>Upload File<span class="text-danger">*</span></label>
                            <input type="file" name="life_certificates_file" id="life_certificates_file" class="file-upload-default dob_attachment_path">
                            <div class="input-group col-xs-12">
                                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload File">
                                <div class="input-group-append">
                                    <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                </div>
                            </div>
                            <label id="life_certificates_file-error" class="error text-danger" for="life_certificates_file"></label>
                        </div>
                    </div>
                    <div class="row">
                    <div class="col-md-4 form-group mt-2">
                        <input type="submit" class="btn btn-success" value="Submit">
                    </div>
                    </div>
                </form>
                </div>
            </div>

		</div>
	</div>
</div>

@endsection

@section('page-script')
<script type="text/javascript">
    $(document).ready(function(){
        
        $("#life_certificate_form").validate({
            rules: {
                'life_certificates_file': {
                    required: true,
                },
            },
            messages: {
                'life_certificates_file': {
                    required: 'Please upload file',
                },
            },
            submitHandler: function(form, event) { 
                    event.preventDefault();
                    var formData = new FormData(form);
                    $.ajax({
                        type:'POST',
                        url:'{{ route("pension_unit_life_certificate_form_submission") }}',
                        data: formData,
                        dataType: 'JSON',
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            $('.page-loader').removeClass('d-flex');
                            if(response['error']){
                                //$("#logid").prop('disabled',false);
                                for (i in response['error']) {
                                    var element = $('#' + i);
                                    var id = response['error'][i]['id'];
                                    var eValue = response['error'][i]['eValue'];
                                    console.log(id);
                                    console.log(eValue);
                                    $("#"+id).show();
                                    $("#"+id).html(eValue);
                                }
                            }else{
                                // Success
                                location.href = "{{route('pension_unit_life_certificate_list_page')}}";
                            }
                        }
                    });
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
</script>

@endsection
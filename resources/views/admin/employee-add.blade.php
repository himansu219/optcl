@extends('user.layout.layout')

@section('container')
<div class="content-wrapper">
  <div class="row">
    <div class="col-12 grid-margin">
      @if(Session::has('error'))
      <div class="alert alert-danger">{{ Session::get('error') }}</div>
      @endif
      @if(Session::has('success'))
      <div class="alert alert-success">{{ Session::get('success') }}</div>
      @endif
      <nav aria-label="breadcrumb" role="navigation">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('admin_dashboard')}}">Dashboard</a></li>
          <!-- <li class="breadcrumb-item"><a href="#">Master Management</a></li> -->
          <li class="breadcrumb-item "><a href="{{route('employee_details')}}">Employee Master</a></li>
          <li class="breadcrumb-item active" aria-current="page">Import Employee</li>
        </ol>
      </nav>
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Import Pensioner Data</h4>

          <form class="forms-sample" id="employee_form" method="post" action="{{URL('employee_submit')}}" enctype="multipart/form-data">
            @csrf
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>File<span class="span-red">*</span></label>
                  <input type="file" name="import_file" id="import_file" class="file-upload-default">
                  <div class="input-group col-xs-12">
                    <input type="text" class="form-control file-upload-info" disabled placeholder="Import pensioner data">
                    <div class="input-group-append">
                      <button class="file-upload-browse btn btn-info" type="button">Import</button>
                    </div>

                  </div>
                  <label id="employee_master-error" class="error mt-2 text-danger" for="employee_master"></label>
                </div>
              </div>
            </div>
            <button type="submit" class="btn btn-success mr-2">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
@section('page-script')
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.19.2/additional-methods.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    // validator file size to be in MB
    $.validator.addMethod('filesize', function(value, element, param) {
      return this.optional(element) || (element.files[0].size <= param * 1000000)
    }, 'File size must be less than {0} MB');

    $("#employee_form").validate({
      rules: {
        import_file: {
          required: true,
          filesize: 5,
          extension: 'xlsx'

        }
      },
      messages: {
        import_file: {
          required: "Please upload file",
          filesize: "Maximum 5 MB allowed",
          extension: "File extension with .xlsx allowed"
        }
      },
      errorPlacement: function(label, element) {
        label.addClass('mt-2 text-danger');
        label.insertAfter(element);
      },
      highlight: function(element, errorClass) {
        $(element).parent().addClass('has-success')
        $(element).addClass('form-control-danger')
      }
    });
  });
</script>


@endsection
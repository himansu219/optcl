@extends('user.layout.layout')

@section('container')
<div class="content-wrapper">
                   <nav aria-label="breadcrumb" role="navigation">
                      <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('admin_dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="#">Master Management</a></li>
                        <li class="breadcrumb-item "><a href="{{route('country_details')}}">Manage Countries</a></li>
                        <li class="breadcrumb-item "><a href="{{route('country_add')}}">Add Country</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Country</li>
                      </ol>
                    </nav>
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Edit Country</h4>
                    <form class="forms-sample" id="country_form" method="post" action="{{URL('country-update/'.$result['0']->id)}}">
                      @csrf
                         <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleInputName1">Country Name</label> <span class="span-red">*</span>
                                    <input type="text" class="form-control" id="country_name" name="country_name" placeholder="Enter country name" autocomplete="off" value="{{$result['0']->country_name}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleInputName1">Country Code</label> <span class="span-red">*</span>
                                    <input type="text" class="form-control" id="country_code" name="country_code" placeholder="Enter country code" autocomplete="off" value="{{$result['0']->country_code}}">
                                </div>
                            </div>
                          </div>
                          <button type="submit" class="btn btn-success mr-2">Update</button>
                     </form>
                </div>
              </div>
   </div>  


  @endsection
  @section('page-script')

  
  <script type="text/javascript">

        $.validator.addMethod("addressReg", function (value, element) {
            return this.optional(element) || /^[a-zA-Z\s-]*$/.test(value);
        }, "Please use only letters");


        $("#country_form").validate({
            rules: {

              country_name: {
                  required: true,
                  minlength: 2,
                  maxlength: 20,
                  addressReg: true
              }
            },
            messages: {
               
              country_name: {                    
                    required: 'Please enter country name',
                    minlength: 'Country name minimum 2 characters',
                    maxlength: 'Country name maximum 20 characters'
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



  </script>

@endsection
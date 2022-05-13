@extends('user.layout.layout')

@section('container')
<div class="content-wrapper">
                    <nav aria-label="breadcrumb" role="navigation">
                      <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('admin_dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="#">Master Management</a></li>
                        <li class="breadcrumb-item "><a href="{{route('pension_unit_details')}}">Pension Units Master</a></li>
                        <li class="breadcrumb-item "><a href="{{route('pension_unit')}}">Add Pension Unit</a></li>
                         <li class="breadcrumb-item active" aria-current="page">Edit Pension Unit</li>
                      </ol>
                    </nav>
      
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Edit Pension Unit</h4>
                  <form class="forms-sample" id="pension_unit_form" method="post" action="{{URL('pension_unit_update/'.$result['0']->id)}}">
                      @csrf
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                            <label for="exampleInputName1">Unit Name</label> <span class="span-red">*</span>
                            <input type="text" class="form-control" id="unit_name" name="unit_name" placeholder="Enter pension unit name" autocomplete="off" value="{{$result['0']->pension_unit_name}}">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                            <label for="exampleInputEmail3">Unit Code</label> <span class="span-red">*</span>
                            <input type="text" class="form-control" id="unit_code" name="unit_code" placeholder="Enter pension unit code" autocomplete="off" value="{{$result['0']->unit_code}}">
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
            return this.optional(element) || /^[a-zA-Z0-9\s,()/-]*$/.test(value);
        }, "Please use only letters, numbers and special characters(,()/-).");

        $.validator.addMethod("pensionCode", function (value, element) {
            return this.optional(element) || /^[a-zA-Z0-9\s-]*$/.test(value);
        }, "Please use only letters, numbers");


    $("#pension_unit_form").validate({
            rules: {

              
              // district: {
              //   required: true
              // },
              unit_name: {
                  required: true,
                  minlength: 5,
                  maxlength: 70,
                  addressReg: true
              },
              unit_code: {
                  required: true,
                  minlength: 2,
                  maxlength: 50,
                  pensionCode: true
              }
            },
            messages: {
                
                // district: {
                //     required: 'Please select district name'
                    
                // },
                unit_name: {                    
                    required: 'Please enter pension unit name',
                    minlength: 'Pension unit name minimum 5 characters',
                    maxlength: 'Pension unit name maximum 70 characters'
                },
                unit_code: {                    
                    required: 'Please enter unit code',
                    minlength: 'Pension unit code minimum 5 characters',
                    maxlength: 'Pension unit code maximum 50 characters'
                },

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
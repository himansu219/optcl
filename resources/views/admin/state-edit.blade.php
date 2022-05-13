@extends('user.layout.layout')

@section('container')
<div class="content-wrapper">
            <nav aria-label="breadcrumb" role="navigation">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin_dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#">Master Management</a></li>
                <li class="breadcrumb-item "><a href="{{route('state_details')}}">State Master</a></li>
                <li class="breadcrumb-item "><a href="{{route('state_add')}}">Add State</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit State</li>
              </ol>
            </nav>
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Edit State Form</h4>
                    <form class="forms-sample" id="state_form" method="post" action="{{URL('state-update/'.$result['0']->id)}}">
                      @csrf
                         <div class="row">
                            <div class="col-md-4">
                              <div class="form-group">
                                <label>Country</label> <span class="span-red">*</span>
                                  <select class="js-example-basic-single" style="width:100%" id="country" name="country" autocomplete="off">
                                    <option value="{{$result['0']->country_id}}">{{$result['0']->country->country_name}}</option>
                                    @foreach($country as $list)
                                    <option value="{{$list->id}}">{{$list->country_name}}</option>
                                    @endforeach
                                  </select>
                              </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleInputName1">State Name</label> <span class="span-red">*</span>
                                    <input type="text" class="form-control" id="state_name" name="state_name" placeholder="Enter state name" autocomplete="off" value="{{$result['0']->state_name}}">
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


        $("#state_form").validate({
            rules: {

              country: {

                required: true 

              },

              state_name: {
                  required: true,
                  minlength: 2,
                  maxlength: 20,
                  addressReg: true
              }
            },
            messages: {

              country: {

                required: 'Please select country name'

              },
              state_name: {                    
                    required: 'Please enter state name',
                    minlength: 'State name minimum 2 characters',
                    maxlength: 'State name maximum 20 characters'
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
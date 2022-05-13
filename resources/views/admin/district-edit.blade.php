@extends('user.layout.layout')

@section('container')
<div class="content-wrapper">
                    <nav aria-label="breadcrumb" role="navigation">
                      <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('admin_dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="#">Master Management</a></li>
                        <li class="breadcrumb-item "><a href="{{route('district_details')}}">Districts master</a></li>
                        <li class="breadcrumb-item "><a href="{{route('district_add')}}">Add District</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit District</li>
                      </ol>
                    </nav>
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Edit District</h4>
                  <form class="forms-sample" id="district_form" method="post" action="{{URL('district-update/'.$result['0']->id)}}">
                      @csrf
                         <div class="row">
                            <div class="col-md-4">
                              <div class="form-group">
                                <label>Country</label> <span class="span-red">*</span>
                                  <select class="js-example-basic-single" style="width:100%" id="country" name="country" autocomplete="off">
                                    <!-- <option value="{{$result['0']->country_id}}">{{$result['0']->country_id}}</option> -->
                                    @foreach($country as $list)
                                    <option value="{{$list->id}}">{{$list->country_name}}</option>
                                    @endforeach
                                                                                
                                  </select>
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                <label>State</label> <span class="span-red">*</span>
                                  <select class="js-example-basic-single" style="width:100%" id="state" name="state" autocomplete="off">
                                    <option value="{{$result['0']->state_id}}">{{$result['0']->state->state_name}}</option>
                                    @foreach($state as $list)
                                    <option value="{{$list->id}}">{{$list->state_name}}</option>
                                    @endforeach
                                                                                
                                  </select>
                              </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleInputName1">District Name</label> <span class="span-red">*</span>
                                    <input type="text" class="form-control" id="district_name" name="district_name" placeholder="Enter district name" autocomplete="off" value="{{$result['0']->district_name}}">
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


        $("#district_form").validate({
            rules: {

              country: {

                required: true 

              },
              state: {

                  required: true 

              },

              district_name: {
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
              state: {

                required: 'Please select state name'

              },

              district_name: {                    
                    required: 'Please enter state name',
                    minlength: 'State name minimum 2 characters',
                    maxlength: 'State name maximum 20 characters'
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
<script type="text/javascript">

$(document).ready(function(){
  $('#country').change(function(){
    let cid=$(this).val();
    console.log(cid);
    $('#state').html('<option value="">Select State</option>')
    $.ajax({
      url:"{{route('statedata')}}",
      type:'post',
      data:'cid='+cid+'&_token={{csrf_token()}}',
      success:function(result){
         $('#state').html(result);
                    
      }
    });
  });
    
    
    
});
</script>
@endsection
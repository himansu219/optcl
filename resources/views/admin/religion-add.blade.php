@extends('user.layout.layout')

@section('container')
<div class="content-wrapper">
                    <nav aria-label="breadcrumb" role="navigation">
                        <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="{{route('admin_dashboard')}}">Dashboard</a></li>
                          <li class="breadcrumb-item" ><a href="#">Master Management</a></li>
                          <li class="breadcrumb-item"><a href="{{route('relation_details')}}">Relation Master</a></li>
                          <li class="breadcrumb-item active" aria-current="page">Add Relation</li> 
                        </ol>
                    </nav>
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Add  Relation</h4>
                  <form class="forms-sample" id="relation_form" method="post" action="{{URL('relation_submit')}}">
                      @csrf
                    <div class="row">

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="exampleInputName1">Relation Name</label> <span class="span-red">*</span>
                            <input type="text" class="form-control" id="relation_name" name="relation_name" placeholder="Enter relation name" autocomplete="off">
                        </div>
                      </div>
                   </div>
                      <button type="submit" class="btn btn-success mr-2">Submit</button>
                  </form>
                </div>
              </div>
       
   </div>

  @endsection
  @section('page-script')

  <script type="text/javascript">

        $.validator.addMethod("addressReg", function (value, element) {
            return this.optional(element) || /^[a-zA-Z/s-]*$/.test(value);
        }, "Please use only letters");


    $("#relation_form").validate({
            rules: {

              relation_name: {
                  required: true,
                  minlength: 2,
                  maxlength: 20,
                  addressReg: true
              }
            },
            messages: {
               
              relation_name: {                    
                    required: 'Please enter relation name',
                    minlength: 'Relation name minimum 2 characters',
                    maxlength: 'Relation name maximum 20 characters'
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
  <!-- <script type="text/javascript">

		jQuery(document).ready(function(){
			jQuery('#country').change(function(){
        let cid=jQuery(this).val();
        console.log(cid);
				jQuery('#state').html('<option value="">Select State</option>')
				jQuery.ajax({
					url:'/getState',
					type:'post',
					data:'cid='+cid+'&_token={{csrf_token()}}',
					success:function(result){
             jQuery('#state').html(result);
                        
					}
				});
			});
        
        jQuery('#state').change(function(){
          let sid=jQuery(this).val();
          jQuery.ajax({
            url:'/getDistrict',
            type:'post',
            data:'sid='+sid+'&_token={{csrf_token()}}',
            success:function(result){
                jQuery('#district').html(result);
                          
            }
          });
        });
        
    });
  </script> -->

@endsection
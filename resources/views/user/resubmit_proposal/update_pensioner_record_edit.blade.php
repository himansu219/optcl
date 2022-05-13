@extends('user.layout.layout')

@section('section_content')
<style type="text/css">
    .tablerow {
         background-color: white;
     }
</style>
<div class="content-wrapper">
        <nav aria-label="breadcrumb" role="navigation">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('user_dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="#">Update Pension Record</a></li>
            <li class="breadcrumb-item"><a href="{{route('update_pension_record')}}">Listing</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
          </ol>
        </nav>
    <div class="row">
       <div class="col-12 grid-margin">
            @if(Session::has('error'))
                <div class="alert alert-danger">{{ Session::get('error') }}</div>
            @endif
            @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success') }}</div>
            @endif
                  
            
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Update Pension Record</h4>
                
                <form class="forms-sample" id="update_pension_record_form" method="post" action="{{URL('update_pension_record_data_update')}}">
                        @csrf
                <input type="hidden" name="id" value="{{$result->id}}">
                    <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleInputEmail3">Bank Account No <span class="span-red">*</span></label>
                                    <input type="text" class="form-control numbersOnly" id="bank_account_no" name="bank_account_no" minlength="9" maxlength="18" placeholder="Enter Bank Account No" autocomplete="off" value="{{$result->bank_ac_no}}">
                                    <label id="bank_account_no-error" class="error mt-2 text-danger" for="bank_account_no"></label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Bank Name <span class="span-red">*</span></label>
                                    <select class="js-example-basic-single form-control" id="bank_name" name="bank_name" autocomplete="off">
                                        <option value="">Select Bank Name</option>
                                        @foreach($bank_name as $list)
                                        <option value="{{$list->id}}" @if($result->bank_id == $list->id) {{'selected'}} @endif>{{$list->bank_name}}</option>
                                        @endforeach
                                                                                    
                                    </select>
                                    <label id="bank_name-error" class="error mt-2 text-danger" for="bank_name"></label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    @php
                                        $bank_branch_id = $result->bank_branch_id;
                                        $branch_name = DB::table('optcl_bank_branch_master')->where('id', $bank_branch_id)->get();
                                    @endphp
                                    <label>Branch Name <span class="span-red">*</span></label>
                                    <select class="js-example-basic-single form-control" id="branch_name" name="branch_name" autocomplete="off">
                                        @foreach($bank_branch_name as $list)
                                            <option value="{{$list->id}}" @if($result->bank_branch_id == $list->id) {{'selected'}} @endif>{{$list->branch_name}}</option>
                                        @endforeach
                                    </select>
                                    <label id="branch_name-error" class="error mt-2 text-danger" for="branch_name"></label>
                                </div>
                            </div>
                        </div>
                    <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    @php
                                        $bid = $result->bank_branch_id;
                                        $ifsc_name = DB::table('optcl_bank_branch_master')->where('id', $bid)->first();
                                    @endphp
                                    <label for="exampleInputEmail3">IFSC Code</label>
                                <input type="text" class="form-control" id="ifsc_code" name="ifsc_code" placeholder="Enter IFSC Code" autocomplete="off" readonly value="{{ $ifsc_name->ifsc_code }}">
                                    <label id="ifsc_code-error" class="error mt-2 text-danger" for="ifsc_code"></label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    @php
                                        $bid = $result->bank_branch_id;
                                        $micr_name = DB::table('optcl_bank_branch_master')->where('id', $bid)->first();
                                    @endphp
                                    <label for="exampleInputEmail3">MICR Code</label>
                                    <input type="text" class="form-control" id="micr_code" name="micr_code" placeholder="Enter MICR Code" autocomplete="off" readonly value="{{ $micr_name->micr_code }}">
                                    <label id="micr_code-error" class="error mt-2 text-danger" for="micr_code"></label>
                                </div>
                            </div>
                       </div>
                        <button type="submit" class="btn btn-success mr-2">Update</button>
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
        $('.numbersOnly').keyup(function () { 
        this.value = this.value.replace(/[^0-9\.]/g,'');
        });
          
          // form validation 
          $("#update_pension_record_form").validate({
              rules: {
                bank_account_no: {
                  required: true,
                  minlength: 9,
                  maxlength: 18
                },
                bank_name: {
                  required: true 
                },
  
                branch_name: {
                    required: true
                }
              },
              messages: {
                bank_account_no: {
                  required: 'Please enter Bank Account No',
                  minlength: 'Bank account no minimum 9 digits',
                  maxlength: 'Bank account no maximum 18 digits'
                },
                bank_name: {
                  required: 'Please select bank name'
                },
                branch_name: {                    
                      required: 'Please select bank branch name'
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
            $('#bank_name').change(function(){
              $('.page-loader').addClass('d-flex');
              let bid=$(this).val();
              //console.log(bid);
              $('#branch_name').html('<option value="">Select Branch Name</option>')
              $('#ifsc_code').val('');
              $('#micr_code').val(''); 
              $.ajax({
                url:"{{route('bank_branch_data')}}",
                type:'post',
                data:'bid='+bid+'&_token={{csrf_token()}}',
                success:function(result){
                  $('.page-loader').removeClass('d-flex');
                  $('#branch_name').html(result);
                            
                }
              });
            });
            $('#branch_name').change(function(){
                $('.page-loader').addClass('d-flex');
                let bankname=$('#bank_name').val();
				let branchname=$(this).val();
                console.log(bankname);
                console.log(branchname);
				$.ajax({
					url:'{{ route("get_ifsc_micr") }}',
					type:'post',
					data:'branchname='+branchname+'&bankname='+bankname+'&_token={{csrf_token()}}',
                    //console.log(data);
					success:function(result){
                        $('.page-loader').removeClass('d-flex');
                        $('#ifsc_code').val(result.ifsc_code);
                        $('#micr_code').val(result.micr_code);
					}
				});
			});
    });
</script>
@endsection
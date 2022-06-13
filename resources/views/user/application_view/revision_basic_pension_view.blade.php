@extends('user.layout.layout')

@section('section_content')
<style type="text/css">
    #upload-demo{
        width: 450px;
        height: 300px;
        padding-bottom:25px;
    }
	.body-font-size {
		font-size: 0.8rem;
	}
</style>
<div class="content-wrapper">
    <nav aria-label="breadcrumb" role="navigation" class="bg-white">
        <ol class="breadcrumb">
          <li class="breadcrumb-item" ><a href="{{ route('user_dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item" >Update Pension Record</li>
          <li class="breadcrumb-item" ><a href="{{ route('pension_unit_revision_basic_pension') }}">Revision of Basic Pension</a></li>
          <li class="breadcrumb-item active" aria-current="page">View</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Application Details
                    @if(Auth::user()->system_user_role == '5')
                        <button type="button" id="approve-btn" class="btn btn-success float-right">Approve</button>
                    @endif                         
                </h4>
                <table class="table table-bordered perticular-table-details-page body-font-size bg-white">
                    <tr>
                        <th width="20%">PPO No.</th>
                        <td width="30%">{{ $request_details->ppo_no }}</td>
                        <th width="20%">Pension Employee No.</th>
                        <td width="30%">{{ $request_details->pensioner_emp_no }}</td>
                    </tr>
                    <tr>
                        <th width="20%">Pensioner Name</th>
                        <td width="30%">{{ $request_details->pensioner_name }}</td>
                        <th width="20%">Revised Basic Amount</th>
                        <td width="30%">{{ $request_details->pensioner_basic_amount ? number_format($request_details->pensioner_basic_amount, 2) : 0 }}</td>
                    </tr>
                    <tr>
                        <th>O.O No.</th>
                        <td>{{ $request_details->oo_no }}</td>
                        <th>O.O No. Date</th>
                        <td>{{ $request_details->oo_no_date }}</td>
                    </tr>                                          
                </table>
            </div>
        </div>
        </div>
    </div>
</div>

<div class="modal fade" id="application_remark" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
    <form action="{{ route('billing_officer_application_single_approval_family_pension_submission') }}" method="post" id="application_approval_remark" accept-charset="utf-8">
        @csrf
        <input type="hidden" name="monthly_changed_data_id" value="{{$appID}}">
        <input type="hidden" name="application_type" value="{{$request_details->application_type}}">
        <input type="hidden" name="pensioner_type" value="{{$request_details->pensioner_type}}">
        <input type="hidden" name="application_id" value="{{$request_details->application_id}}">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Approval</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Remark<span class="text-danger">*</span></label>
                                <textarea name="remarks" id="remarks" placeholder="Enter Remark" class="form-control remark_textarea" rows="6" required maxlength="500"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="proposalReturned" class="btn btn-raised btn-success">Submit</button>
                </div>
            </div>
        </div>
    </form>        
</div>
@endsection
@section('page-script')
<script type="text/javascript">
    $(document).ready(function() {
        $('#approve-btn').on('click', function() {
            $('#application_remark').modal('show');       
        });

        $("#application_approval_remark").validate({
            rules: {
                remarks: {
                    required: true,
                },
            },
            messages: {
                remarks: {
                    required: 'Please enter remark',
                },
              },
            submitHandler: function(form, event) { 
                    event.preventDefault();
                    $('.field_value').val(0);
                    $('#application_status').val(0);
                    var remark_value = $('#remarks').val();
                    $('#return_remark_value').val(remark_value);
                    $('#application_remark').modal('hide');
                    form.submit();
              },
              errorPlacement: function(label, element) {
                label.addClass('text-danger');
                label.insertAfter(element);
              },
              highlight: function(element, errorClass) {
                //$(element).parent().addClass('has-danger')
                $(element).addClass('form-control-danger')
              }
        });
    });
</script>
@endsection
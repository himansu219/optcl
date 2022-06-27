@extends('user.layout.layout')

@section('section_content')
<style type="text/css">
    #upload-demo{
        width: 450px;
        height: 300px;
        padding-bottom:25px;
    }
</style>
<div class="content-wrapper">
    <nav aria-label="breadcrumb" role="navigation" class="bg-white">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('user_dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">View Details</li> 
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Application Details
                        @if(Auth::user()->system_user_role == '5' && $monthly_data->is_billing_officer_approved == 0)                      
                            <button type="button" id="approve-btn" class="btn btn-success float-right">Approve</button>  
                        @endif                      
                    </h4>  
                <table class="table table-bordered perticular-table-details-page">                
                    @if($pensionerDetails->pensioner_type == 1)
                    <tr>
                        <th width="20%">Tax Type</th>
                        <td width="30%">{{ $pensionerDetails->type_name }}</td>
                        <th width="20%"></th>
                        <td width="30%"></td>
                    </tr>
                    @endif
                    <tr>
                        <th width="20%">Pensioner Type</th>
                        <td width="30%">{{ $pensionerDetails->pension_type }}</td>
                        <th width="20%">PPO No</th>
                        <td width="30%">{{ $pensionerDetails->old_ppo_no }}</td>
                    </tr>
                    <tr>
                        <th>Attached PPO File</th>
                        <td><a href="{{ url('/').'/'.$pensionerDetails->old_ppo_attachment }}" target="_blank"><i class="fa fa-file-pdf-o mr-2"></i>Attachment File</a></td>
                        <th>New PPO No</th>
                        <td>{{ $pensionerDetails->new_ppo_no }}</td>
                    </tr>
                    <tr>
                        <th>Pensioner Name</th>
                        <td>{{ $pensionerDetails->pensioner_name }}</td>
                        <th>Mobile No</th>
                        <td>{{ $pensionerDetails->mobile_number ? $pensionerDetails->mobile_number : 'NA' }}</td>
                    </tr>
                    <tr>
                        <th>Aadhaar No.</th>
                        <td>{{ $pensionerDetails->aadhar_no ? $pensionerDetails->aadhar_no : 'NA' }}</td>
                        <th>PAN</th>
                        <td>{{ $pensionerDetails->pan_no ? $pensionerDetails->pan_no : 'NA' }}</td>
                    </tr>
                    <tr>
                        <th>Employee Code</th>
                        <td>{{ $pensionerDetails->employee_code ? $pensionerDetails->employee_code : 'NA' }}</td>
                        <th></th>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Gender</th>
                        <td>{{ $pensionerDetails->gender_name }}</td>
                        <th>Designation</th>
                        <td>{{ $pensionerDetails->designation_name }}</td>
                    </tr>
                    <tr>
                        <th>Date of Birth</th>
                        <td>{{ $pensionerDetails->date_of_birth ? date('d/m/Y', strtotime($pensionerDetails->date_of_birth)) : 'NA' }}</td>
                        <th>Date of Retirement</th>
                        <td>{{ $pensionerDetails->date_of_retirement ? date('d/m/Y', strtotime($pensionerDetails->date_of_retirement)) : 'NA' }}</td>
                    </tr>
                    @if($pensionerDetails->pensioner_type == 2)
                    <tr>
                        <th>Date of Death</th>
                        <td>{{ $pensionerDetails->date_of_death ? date('d/m/Y', strtotime($pensionerDetails->date_of_death)) : 'NA' }}</td>
                        <th></th>
                        <td></td>
                    </tr>
                    @endif
                    <tr>
                        <th>Basic Pension Amount</th>
                        <td>{{ $pensionerDetails->basic_amount }}</td>
                        <th>Basic Pension Effective Date</th>
                        <td>{{ $pensionerDetails->basic_effective_date ? date('d/m/Y', strtotime($pensionerDetails->basic_effective_date)) : 'NA' }}</td>
                    </tr>
                    <tr>                                                
                        <th>Additional Pension Amount</th>
                        <td>{{ $pensionerDetails->additional_pension_amount ? $pensionerDetails->additional_pension_amount:"0" }}</td>
                        <th></th>
                        <td></td>
                    </tr>
                    @if($pensionerDetails->pensioner_type == 2)
                    <tr>
                        <th>Enhanced Pension Amount</th>
                        <td>{{ $pensionerDetails->enhanced_pension_amount ? $pensionerDetails->enhanced_pension_amount:"0" }}</td>
                        <th>End Date</th>
                        <td>{{ $pensionerDetails->enhanced_pension_end_date ? date('d/m/Y', strtotime($pensionerDetails->enhanced_pension_end_date)) : 'NA' }}</td>
                    </tr>
                    <tr>
                        <th>Normal Pension Amount</th>
                        <td>{{ $pensionerDetails->normal_pension_amount }}</td>
                        <th>Effective Date</th>
                        <td>{{ $pensionerDetails->normal_pension_effective_date ? date('d/m/Y', strtotime($pensionerDetails->normal_pension_effective_date)) : 'NA'  }}</td>
                    </tr>
                    @endif
                    <tr>
                        <th>Category</th>
                        <td>{{ $pensionerDetails->category_name }}</td>
                        <th>TI Amount(Percentage)</th>
                        <td>{{ $pensionerDetails->ti_amount.' ('.$pensionerDetails->ti_percentage.'%)' }}</td>
                    </tr>
                    <tr>
                        <th>Name of the Bank</th>
                        <td>{{ $pensionerDetails->bank_name }}</td>
                        <th>Name Address of the Branch</th>
                        <td>{{ $pensionerDetails->branch_name }}</td>
                    </tr>
                    <tr>
                        <th>IFSC Code</th>
                        <td>{{ $pensionerDetails->ifsc_code }}</td>
                        <th>MICR Code</th>
                        <td>{{ $pensionerDetails->micr_code }}</td>
                    </tr>
                    <tr>
                        <th>Savings Bank A/C No.</th>
                        <td>{{ $pensionerDetails->acc_number }}</td>
                        <th></th>
                        <td></td>
                    </tr>
                    @if($pensionerDetails->pensioner_type == 2)
                    <tr>
                        <th colspan="4" class="text-center">Family Pensioner Details</th>
                    </tr>
                    <tr>
                        <th>Relation Type</th>
                        <td>{{ $pensionerDetails->relation_name }}</td>
                        <th>Current Status</th>
                        <td>{{ $pensionerDetails->relation_status_name }}</td>
                    </tr>
                    <tr>
                        <th>Family Pensioner Name</th>
                        <td>{{ $pensionerDetails->nominee_name }}</td>
                        <th>Family Pensioner Mobile No.</th>
                        <td>{{ $pensionerDetails->nominee_mobile }}</td>
                    </tr>
                    <tr>
                        <th>Family Pensioner Aadhaar No.</th>
                        <td>{{ $pensionerDetails->nominee_aadhar }}</td>
                        <th>Family Pensioner Date of Birth</th>
                        <td>{{ $pensionerDetails->nominee_dob ? date('d/m/Y', strtotime($pensionerDetails->nominee_dob)) : 'NA' }}</td>
                    </tr>
                    @endif
                    <!-- <tr>
                        <th colspan="4" class="text-center form-middle-heading">Gross Pension</th>
                    </tr>
                    <tr>
                        <th>Gross Pension Amount</th>
                        <td>{{ $pensionerDetails->gross_pension_amount }}</td>
                        <th></th>
                        <td></td>
                    </tr> -->
                </table>
                
                <h6 class="text-center-normal form-middle-heading">Commutation</h6>
                <table class="table table-bordered mt-2">
                    <thead>
                        <!-- <tr>
                            <th colspan="2" class="text-center form-middle-heading">Commutation</th>
                        </tr> -->
                        <tr>
                            <th>Commutation Amount</th>
                            <th>Commutation End Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($commutation_list as $key => $commutation_data)
                            <tr>
                                <td>{{ number_format($commutation_data->commutation_amount, 2) }}</td>
                                <td>{{ $commutation_data->commutation_end_date ? date('d/m/Y', strtotime($commutation_data->commutation_end_date)) : 'NA' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <table class="table table-bordered perticular-table-details-page mt-4">
                    <tr>
                        <th width="20%">Gross Pension Amount</th>
                        <td width="80%">{{ number_format($pensionerDetails->gross_pension_amount,2) }}</td>
                    </tr>
                    <tr>
                        <th width="20%">Total Income Amount</th>
                        <td width="80%">{{ number_format($pensionerDetails->total_income,2) }}</td>
                    </tr>
                    @if($pensionerDetails->is_taxable_amount_generated == 1)
                    <tr>
                        <th width="20%">Taxable Amount</th>
                        <td width="80%">{{ number_format($pensionerDetails->taxable_amount, 2) }}</td>
                    </tr>
                    @endif
                </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- content-wrapper ends -->
<div class="modal fade" id="application_remark" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
    <form action="{{ route('billing_officer_application_single_approval_submission') }}" method="post" id="application_approval_remark" accept-charset="utf-8">
        @csrf
        <input type="hidden" name="monthly_changed_data_id" value="{{$monthly_data->id}}">
        <input type="hidden" name="application_type" value="{{$monthly_data->appliation_type}}">
        <input type="hidden" name="pensioner_type" value="{{$monthly_data->pensioner_type}}">
        <input type="hidden" name="application_id" value="{{$monthly_data->application_id}}">
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

        $('#return-btn').on('click', function() {
            $('.field_value').val(0);
            $('#return_application_status').val(0);
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
@extends('user.layout.layout')
@section('section_content')
<div class="content-wrapper">
	<div class="row">
		<div class="col-12 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title">Application Preview</h4>
					<div class="accordion" id="accordion" role="tablist">
						<div class="card">
							<div class="card-header" role="tab" id="headingOne">
								<h6 class="mb-0">
									<span class="text-primary">Pension Form</span>
									<a href="{{ route('edit_pensioner_form') }}" class="fa fa-edit float-right"><i class="fa fa-pencil-square-o"></i></a>
								</h6>
								
							</div>
							<div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion">
								<div class="card-body">
									<table class="table table-bordered">
										<tr>
											<th width="20%">Employee No/Code :</th>
											<td width="30%">{{ (!empty($proposal->employee_code)) ? $proposal->employee_code : ''  }}</td>
											<th width="20%">Aadhaar No :</th>
											<td width="30%">{{ (!empty($proposal->aadhaar_no)) ? $proposal->aadhaar_no : '' }}</td>
										</tr>
										<tr>
											<th>Name :</th>
											<td>{{ (!empty($proposal->employee_name)) ? $proposal->employee_name : ''  }}</td>
											<th>Designation :</th>
											<td>{{ (!empty($proposal->designation_name)) ? $proposal->designation_name : ''  }}</td>
										</tr>
										<tr>
											<th>Father's Name :</th>
											<td>{{ (!empty($proposal->father_name)) ? $proposal->father_name : ''  }}</td>
											<th>Gender :</th>
											<td>{{ (!empty($proposal->gender_name)) ? $proposal->gender_name : 'NA' }}</td>
										</tr>
										<tr>
											<th>Marital Status :</th>
											<td>{{ (!empty($proposal->marital_status_name)) ? $proposal->marital_status_name : 'NA' }}</td>
											<th>Religion :</th>
											<td>{{ (!empty($proposal->religion_name)) ? $proposal->religion_name : 'NA' }}</td>
										</tr>
										<tr>
											<th>PF A/C Type :</th>
											<td>{{ (!empty($proposal->account_type)) ? $proposal->account_type : 'NA'  }}</td>
											<th>PF A/C No. :</th>
											<td>{{ (!empty($proposal->pf_account_no)) ? $proposal->pf_account_no : 'NA'  }}</td>
										</tr>
										<tr>
											<th>Name of the Office :</th>
											<td>{{ $proposal->unit_name ? $proposal->unit_name : 'NA'  }}</td>
											<th>Date of Birth :</th>
											<td>{{ (!empty($proposal->date_of_birth)) ? \Carbon\Carbon::parse($proposal->date_of_birth)->format('d-m-Y') : 'NA'  }}</td>
										</tr>
										<tr>
											<th>Date of Joining Service :</th>
											<td>{{ (!empty($proposal->date_of_joining)) ? \Carbon\Carbon::parse($proposal->date_of_joining)->format('d-m-Y') : 'NA'  }}</td>
											<th>Date of Retirement :</th>
											<td>{{ (!empty($proposal->date_of_retirement)) ? \Carbon\Carbon::parse($proposal->date_of_retirement)->format('d-m-Y') : 'NA'  }}</td>
										</tr>
									</table>
								</div>
							</div>
						</div>

						<div class="card">
							<div class="card-header" role="tab" id="headingTwo">
								<h6 class="mb-0">
									<span class="text-primary">Personal Details</span>
									<a href="{{ route('edit_personal_details') }}" class="fa fa-edit float-right"><i class="fa fa-pencil-square-o"></i></a>
								</h6>
								
							</div>
							<div id="collapseTwo" class="collapse show" role="tabpanel" aria-labelledby="headingTwo" data-parent="#accordion">
								<div class="card-body">
									<table class="table table-bordered">
										<tr>
											<th colspan="4">Permanent Address</th>
										</tr>
										<tr>
											<th width="20%">At :</th>
											<td width="30%">{{ (!empty($proposal->permanent_addr_at)) ? $proposal->permanent_addr_at : 'NA'  }}</td>
											<th width="20%">Post :</th>
											<td width="30%">{{ (!empty($proposal->permanent_addr_post)) ? $proposal->permanent_addr_post : 'NA'  }}</td>
										</tr>
										<tr>
											<th>Pin :</th>
											<td>{{ (!empty($proposal->permanent_addr_pincode)) ? $proposal->permanent_addr_pincode : 'NA'  }}</td>
											<th>Country :</th>
											<td>{{ (!empty($proposal->cName)) ? $proposal->cName : ''  }}</td>
										</tr>
										<tr>
											<th>State :</th>
											<td>{{ (!empty($proposal->state_name)) ? $proposal->state_name : 'NA'  }}</td>
											<th>District :</th>
											<td>{{ (!empty($proposal->district_name)) ? $proposal->district_name : ''  }}</td>
										</tr>
										<tr>
											<th colspan="4">Present Address</th>
										</tr>
										<tr>
											<th>At :</th>
											<td>{{ (!empty($proposal->present_addr_at)) ? $proposal->present_addr_at : 'NA'  }}</td>
											<th>Post :</th>
											<td>{{ (!empty($proposal->present_addr_post)) ? $proposal->present_addr_post : 'NA'  }}</td>
										</tr>
										<tr>
											<th>Pin :</th>
											<td>{{ (!empty($proposal->present_addr_pincode)) ? $proposal->present_addr_pincode : 'NA'  }}</td>
											<th>Country :</th>
											<td>{{ (!empty($proposal->country_name)) ? $proposal->country_name : ''  }}</td>
										</tr>
										<tr>
											<th>State :</th>
											<td>{{ (!empty($proposal->sName)) ? $proposal->sName : 'NA'  }}</td>
											<th>District :</th>
											<td>{{ (!empty($proposal->dName)) ? $proposal->dName : ''  }}</td>
										</tr>
										<tr>
											<th>Telephone No with STD Code :</th>
											<td>{{ (!empty($proposal->telephone_std_code)) ? $proposal->telephone_std_code : 'NA'  }}</td>
											<th>Mobile No :</th>
											<td>{{ (!empty($proposal->mobile_no)) ? $proposal->mobile_no : ''  }}</td>
										</tr>
										<tr>
											<th>Email Address :</th>
											<td>{{ (!empty($proposal->email_address)) ? $proposal->email_address : 'NA'  }}</td>
											<th>PAN No :</th>
											<td>{{ (!empty($proposal->pan_no)) ? $proposal->pan_no : ''  }}</td>
										</tr>
										@php
											$bank_branch_id = $proposal->bank_branch_id;
											$bankDetaills = DB::table('optcl_bank_branch_master as bbm')
															->join('optcl_bank_master as b','b.id','=','bbm.bank_id')
															->select('b.bank_name','bbm.branch_name','bbm.ifsc_code','bbm.micr_code')
                                                            ->where('bbm.id', $bank_branch_id)
															->where('bbm.status', 1)
															->where('bbm.deleted', 0)
															->where('b.status', 1)
															->where('b.deleted', 0)
															->first();
											if($bankDetaills){
												$bankName = $bankDetaills->bank_name;
												$branchName = $bankDetaills->branch_name;
												$ifscCode = $bankDetaills->ifsc_code;
												$micrCode = $bankDetaills->micr_code;
											}else{
												$bankName = 'NA';
												$branchName = 'NA';
												$ifscCode = 'NA';
												$micrCode = 'NA';
											}

										@endphp
										<tr>
											<th>Savings Bank A/C No.(Single or Joint Account with Spouse) :</th>
											<td>{{ (!empty($proposal->savings_bank_account_no)) ? $proposal->savings_bank_account_no : 'NA'  }}</td>
											<th>Name of the Bank :</th>
											<td>{{ $bankName }}</td>
										</tr>										
										<tr>
											<th>Name Address of the Branch :</th>
											<td>{{ $branchName }}</td>
											<th>IFSC Code :</th>
											<td>{{ $ifscCode }}</td>
										</tr>
										<tr>
											<th>MICR Code :</th>
											<td>{{ $micrCode }}</td>
											<th>Amount of Basic Pay at the time of Retirement :</th>
											<td>{{ number_format(($proposal->basic_pay_amount_at_retirement ? $proposal->basic_pay_amount_at_retirement : 'NA'),2)  }}</td>
										</tr>
										<tr>
											<th colspan="3">Name of the Unit (where life certificate & income tax declaration to be submitted) :</th>
											<td>{{ $proposal->pension_unit_name ? $proposal->pension_unit_name : 'NA'  }}</td>
										</tr>
										<tr>
											<th colspan="3">Particulars of previous civil service if any and amount and nature of any pension or gratuity received :</th>
											<td>{{ $proposal->is_civil_service_amount_received == 1 ? 'Yes' : 'No'  }}</td>
										</tr>
										@if($proposal->is_civil_service_amount_received == 1)
										<tr>
											<th>Particulars of previous civil service :</th>
											<td>{{ $proposal->sName ? $proposal->civil_service_name : 'NA'  }}</td>
											<th>Amount and nature of any pension or gratuity received :</th>
											<td>{{ number_format(($proposal->dName ? $proposal->civil_service_received_amount : 'NA'),2)  }}</td>
										</tr>
										@endif
										<tr>
											<th colspan="3">Particulars of family pension if any Received / admissible from any other source to the retired employee and any members of his family : </th>
											<td>{{ $proposal->is_family_pension_received_by_family_members == 1 ? 'Yes' : 'No'  }}</td>
										</tr>	
										@if($proposal->is_family_pension_received_by_family_members == 1)
										<tr>
											<th>Enter admissible form any other source to the retired employee :</th>
											<td>{{ $proposal->sName ? $proposal->admission_source_of_family_pension : 'NA'  }}</td>
											<th>Members of his family :</th>
											<td>{{ $proposal->dName ? $proposal->relation_name : 'NA'  }}</td>
										</tr>
										<tr>
											<th>Name of member :</th>
											<td>{{ $proposal->sName ? $proposal->family_member_name : 'NA'  }}</td>
											<td></td>
											<td></td>
										</tr>
										@endif
										<tr>
											<th colspan="3">Whether Commutation of pension to be made & percentage to be specified (not applicable for applicants for family pension) : </th>
											<td>{{ $proposal->is_commutation_pension_applied == 1 ? 'Yes' : 'No'  }}</td>
										</tr>
										@if($proposal->is_commutation_pension_applied == 1)
										<tr>
											<th>Percentage Value :</th>
											<td>{{ ($proposal->sName ? $proposal->commutation_percentage : 'NA').'%'  }}</td>
											<td></td>
											<td></td>
										</tr>
										@endif
									</table>

								</div>
							</div>
						</div>


						<div class="card">
							<div class="card-header" role="tab" id="headingThree">
								<h6 class="mb-0">
									<span class="text-primary">Nominee Details</span>
									<a href="{{ route('nominee_form') }}" class="fa fa-edit float-right"><i class="fa fa-pencil-square-o"></i></a>
								</h6>
							</div>
							<div id="collapseThree" class="collapse show" role="tabpanel" aria-labelledby="headingThree" data-parent="#accordion">
								<div class="card-body">
									<table class="table table-bordered">
										@foreach($employee_nominees as $key=>$employee_nominee)
											<tr>
												<th colspan="4" class="text-center">Nominee</th>
											</tr>
											<tr>
												<th>Full Name of the Family Member</th>
												<td>{{ (!empty($employee_nominee->nominee_name)) ? $employee_nominee->nominee_name : ''  }}</td>
												
												<th>Mobile No</th>
												<td>{{ (!empty($employee_nominee->mobile_no)) ? $employee_nominee->mobile_no : ''  }}</td>
											</tr>
			
											<tr>
												<th>Date of Birth</th>
												<td>{{ (!empty($employee_nominee->date_of_birth)) ? \Carbon\Carbon::parse($employee_nominee->date_of_birth)->format('d-m-Y') : 'NA'  }}</td>

												<th>Proof of Date of Birth</th>
												<td>
													@if($employee_nominee->dob_attachment_path)
														<img class="document_img" src="{{ asset('public/' . $employee_nominee->dob_attachment_path) }}"> 
													@endif
												</td>
											</tr>
											
											<tr>
												<th>Gender</th>
												<td>{{ (!empty($employee_nominee->gender_name)) ? $employee_nominee->gender_name : ''  }}</td>
												
												<th>Relation with Pensioner</th>
												<td>{{ (!empty($employee_nominee->relation_name)) ? $employee_nominee->relation_name : ''  }}</td>
											</tr>

											@if(!empty($employee_nominee) && $employee_nominee->is_spouse == 1)
												@if(!empty($employee_nominee) && $employee_nominee->is_2nd_spouse == 1)
												<tr>
													<th>1st Spouse Death Date</th>
													<td>{{ (!empty($employee_nominee->{'1st_spouse_death_date'})) ? \Carbon\Carbon::parse($employee_nominee->{'1st_spouse_death_date'})->format('d-m-Y') : ''  }}</td>
													<th>1st Spouse Death Certificate</th>
													<td><img class="document_img" src="{{ asset('public/' . $employee_nominee->{'1st_spouse_death_certificate_path'}) }}"></td>
												</tr>
												@endif
											@endif

											<tr>
												<th>Nominee Preference</th>
												<td>{{ (!empty($employee_nominee->nominee_prefrence)) ? $employee_nominee->nominee_prefrence : ''  }}</td>

												<th>Name of the Bank</th>
												<td>{{ (!empty($employee_nominee->bank_name)) ? $employee_nominee->bank_name : ''  }}</td>
											</tr>

											<tr>
												<th>Name Address of the Branch</th>
												<td>{{ (!empty($employee_nominee->branch_name)) ? $employee_nominee->branch_name : ''  }}</td>
												
												<th>IFSC Code</th>
												<td>{{ (!empty($employee_nominee->ifsc_code)) ? $employee_nominee->ifsc_code : ''  }}</td>
											</tr>

											<tr>
												<th>Savings Bank A/C No. (Single / Joint A/C with Spouse)</th>
												<td>{{ (!empty($employee_nominee->savings_bank_account_no)) ? $employee_nominee->savings_bank_account_no : ''  }}</td>
												
												<th>Marital Status</th>
												<td>{{ (!empty($employee_nominee->marital_status_name)) ? $employee_nominee->marital_status_name : ''  }}</td>
											</tr>

											<tr>
												<th>Aadhaar No.</th>
												<td>{{ (!empty($employee_nominee->nominee_aadhaar_no)) ? $employee_nominee->nominee_aadhaar_no : ''  }}</td>
												
												<th>Employment Status</th>
												<td>
													@if($employee_nominee->employement_status == 1)
														Employeed
													@elseif($employee_nominee->employement_status == 2)
														Unemployeed
													@else
														NA
													@endif
												</td>
											</tr>

											<tr>
												<th>Total Income per annum</th>
												<td>{{ (!empty($employee_nominee->total_income_per_annum)) ? $employee_nominee->total_income_per_annum : '0'  }}</td>

												<th>Physically Handicapped</th>
												<td>
													@if($employee_nominee->is_physically_handicapped == 1)
														Yes
													@elseif($employee_nominee->is_physically_handicapped == 2)
														No
													@else
														NA
													@endif
												</td>
											</tr>
											@if($employee_nominee->is_physically_handicapped == 1)
											<tr>
												<th>Disability Certificate</th>
												<td><img class="document_img" src="{{ asset('public/' . $employee_nominee->disability_certificate_path) }}"></td>

												<th>Disability Percentage</th>
												<td>{{ (!empty($employee_nominee->disability_percentage)) ? $employee_nominee->disability_percentage : ''  }}</td>
											</tr>
											@endif
											<tr>
												<th>Amount / Share payable to Each</th>
												<td>{{ (!empty($employee_nominee->pension_amount_share_percentage)) ? $employee_nominee->pension_amount_share_percentage : ''  }}</td>

												<th>Minor</th>
												<td>
													@if($employee_nominee->is_minor == 1)
														Yes
													@elseif($employee_nominee->is_minor == 0)
														No
													@else
														NA
													@endif
												</td>
											</tr>
											@if($employee_nominee->is_minor == 1)
											<tr>
												<th>Legal Guardian Name</th>
												<td>{{ (!empty($employee_nominee->legal_guardian_name)) ? $employee_nominee->legal_guardian_name : ''  }}</td>

												<th>Legal Guardian Age</th>
												<td>{{ (!empty($employee_nominee->legal_guardian_age)) ? $employee_nominee->legal_guardian_age : ''  }}</td>
											</tr>
											<tr>
												<th>Legal Guardian Address</th>
												<td>{{ (!empty($employee_nominee->legal_guardian_addr)) ? $employee_nominee->legal_guardian_addr : ''  }}</td>

												<th>Legal Guardian Attachment</th>
												<td><img class="document_img" src="{{ asset('public/' . $employee_nominee->legal_guardian_attachment_path) }}"></td>
											</tr>
											@endif
										@endforeach
									</table>
								</div>
							</div>
						</div>

						<div class="card">
							<div class="card-header" role="tab" id="headingFour">
								<h6 class="mb-0">
									<span class="text-primary">List of Documents</span>
									<a href="{{ route('pension_documents') }}" class="fa fa-edit float-right"><i class="fa fa-pencil-square-o"></i></a>
								</h6>															
								
							</div>
							<div id="collapseFour" class="collapse show" role="tabpanel" aria-labelledby="headingFour" data-parent="#accordion">
								<div class="card-body">
									<table class="table table-bordered">
										@if(!empty($employee_documents))
											@foreach($employee_documents as $employee_document)
											<tr>
												<th width="70%">{{ $employee_document->document_name }}</th>
												<td>
													<img class="document_img" src="{{ asset('public/' . $employee_document->document_attachment_path ) }}" >
												</td>
											</tr>
											@endforeach
										@endif
									</table>
								</div>
							</div>
						</div>


						<form method="post" action="" id="declaration_form">
							@csrf
							<div class="form-group">
								
								<p class="declaration-class"><input type="checkbox" class="" name="declaration_status" id="declaration_status" value="1">I do hereby declare that the particulars submitted above are true in all aspects. I also undertake to keep the particulars of family, nominee and postal address of self up to date by notifying all changes therein to the Head of Office (Pension Controlling Unit) with a copy to funds section. Further I do hereby undertake that if any excess payment is made to me and is detected at any stage, the same shall be recovered from any dues payable to me or to my family members at any time in future.</p>
								<label id="declaration_status-error" class="error text-danger" for="declaration_status"></label>			
							</div>
							
							<div class="col-12 text-center mt-3">
								<input type="submit" value="Apply" class="btn btn-primary" name="">
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection
@section('page-script')
<script type="text/javascript">
	$(document).ready(function(){
		$("#declaration_form").validate({
            rules: {
                declaration_status: {
                    required: true,
                }
            },
            messages: {
                declaration_status: {
                    required: 'Please accept the declaration'
                }
            },
            submitHandler: function(form, event) {
                $('.page-loader').addClass('d-flex'); 
                event.preventDefault();
                var formData = new FormData(form);
                $.ajax({
                    type:'POST',
                    url:'{{ route("application_submit") }}',
                    data: formData,
                    dataType: 'JSON',
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('.page-loader').removeClass('d-flex');
                        if(response['error']) {
                            for (i in response['error']) {
                              var element = $('#' + i);
                              var id = response['error'][i]['id'];
                              var eValue = response['error'][i]['eValue'];
                              $("#"+id).show();
                              $("#"+id).html(eValue);
                            }
                        }else {
                            location.href = "{{route('view_details')}}";
                        }
                    }
                });
            },
            errorPlacement: function(label, element) {
                label.addClass('mt-2 text-danger');
                label.insertAfter(element);
            },
            highlight: function(element, errorClass) {
                $(element).parent().addClass('has-success');
                $(element).addClass('form-control-danger');
            }
        });

	});
</script>
@endsection
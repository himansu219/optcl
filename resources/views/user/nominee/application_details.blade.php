@extends('user.layout.layout')
@section('section_content')
<div class="content-wrapper">
	@if(Session::has('error'))
        <div class="alert alert-danger">{{ Session::get('error') }}</div>
    @endif
    @if(Session::has('success'))
        <div class="alert alert-success">{{ Session::get('success') }}</div>
    @endif
	<div class="row">
		<div class="col-12 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title">Application Details</h4>
					<div class="accordion" id="accordion" role="tablist">
						<div class="card">
							<div class="card-header" role="tab" id="headingOne">
								<h6 class="mb-0">
									<a data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Family Pension Form</a>
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
									<a data-toggle="collapse" href="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">Family Pensioner</a>
								</h6>
								
							</div>
							<div id="collapseTwo" class="collapse show" role="tabpanel" aria-labelledby="headingTwo" data-parent="#accordion">
								<div class="card-body">
									<table class="table table-bordered">
										<tr>
											<th colspan="4">PARTICULARS OF THE DECEASED PENSIONER</th>
										</tr>
										<tr>
											<th width="20%">Full Name :</th>
											<td width="30%">{{ (!empty($proposal->full_name)) ? $proposal->full_name : 'NA'  }}</td>
											<th width="20%">PPO No :</th>
											<td width="30%">{{ (!empty($proposal->ppo_no)) ? $proposal->ppo_no : 'NA'  }}</td>
										</tr>
										<tr>
											<th>Date of Death :</th>
											<td>{{ (!empty($proposal->dod)) ? $proposal->dod : 'NA'  }}</td>
											<th>Death Certificate :</th>
											<td>
												@if($proposal->death_certificate)
													<img class="document_img" src="{{ asset('public/' . $proposal->death_certificate) }}"> 
												@endif
											</td>
										</tr>
										<tr>
											<th colspan="4">PARTICULARS OF THE APPLICANT</th>
										</tr>
										<tr>
											<th>Name of Applicant :</th>
											<td>{{ (!empty($proposal->applicant_name)) ? $proposal->applicant_name : 'NA'  }}</td>
											<th>Relationship with the Deceased Pensioner :</th>
											<td>{{ (!empty($proposal->relationName)) ? $proposal->relationName : 'NA'  }}</td>
										</tr>
										<tr>
											<th>Employment Status :</th>
											<td>{{ (!empty($proposal->is_employment_status)) ? 'Yes' : 'No'  }}</td>
											<th>Particulars of employment :</th>
											<td>{{ (!empty($proposal->particular_of_employment)) ? $proposal->particular_of_employment : 'NA'  }}</td>
										</tr>
										<tr>
											<th>If the Applicant is in receipt of pension from any other sources :</th>
											<td>{{ (!empty($proposal->is_pension_status)) ? 'Yes' : 'No' }}</td>
											<th>Particulars of pension :</th>
											<td>{{ (!empty($proposal->particular_of_pension)) ? $proposal->particular_of_pension : 'NA'  }}</td>
										</tr>
										<tr>
											<th>Postal Address At :</th>
											<td>{{ (!empty($proposal->postal_addr_at)) ? $proposal->postal_addr_at : 'NA'  }}</td>
											<th>Savings Bank A/C No. :</th>
											<td>{{ (!empty($proposal->saving_bank_ac_no)) ? $proposal->saving_bank_ac_no : ''  }}</td>
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
											<th>Post :</th>
											<td>{{ (!empty($proposal->postal_addr_post)) ? $proposal->postal_addr_post : 'NA'  }}</td>
											<th>Name of the Bank :</th>
											<td>{{ $bankName  }}</td>
										</tr>
										<tr>
											<th>PIN Code :</th>
											<td>{{ (!empty($proposal->postal_addr_pincode)) ? $proposal->postal_addr_pincode : 'NA'  }}</td>
											<th>Name Address of the Branch :</th>
											<td>{{ $branchName }}</td>
										</tr>
										<tr>
											<th>Country :</th>
											<td>{{ (!empty($proposal->cName)) ? $proposal->cName : 'NA'  }}</td>
											<th>IFSC Code :</th>
											<td>{{ $ifscCode }}</td>
										</tr>
										<tr>
											<th>State :</th>
											<td>{{ (!empty($proposal->state_name)) ? $proposal->state_name : 'NA'  }}</td>
											<th>MICR Code :</th>
											<td>{{ $micrCode }}</td>
										</tr>
										<tr>
											<th>District :</th>
											<td>{{ (!empty($proposal->district_name)) ? $proposal->district_name : 'NA'  }}</td>
											<th></th>
											<td></td>
										</tr>
										<tr>
											<th colspan="3">Name of the Unit (where life certificate & income tax declaration to be submitted) :</th>
											<td>{{ (!empty($proposal->pension_unit_name)) ? $proposal->pension_unit_name : 'NA'  }}</td>
										</tr>										
										<tr>
											<th colspan="3">Particulars of family pension if any Received / admissible from any other source to the retired employee and any members of his family :</th>
											<td>{{ (!empty($proposal->is_family_pension_received_by_family_members)) ? 'Yes' : 'No'  }}</td>
										</tr>
										<tr>
											<th colspan="3">Enter admissible from any other source to the retired employee :</th>
											<td>{{ (!empty($proposal->admissible_form_any_other_source_to_the_retired_employee)) ? $proposal->admissible_form_any_other_source_to_the_retired_employee : 'NA'  }}</td>
										</tr>
										<tr>
											<th>Members of his family :</th>
											<td>{{ (!empty($proposal->relationName)) ? $proposal->relationName : 'NA'  }}</td>
											<th>Name of member</th>
											<td>{{ (!empty($proposal->family_member_name)) ? $proposal->family_member_name : 'NA'  }}</td>
										</tr>										
										<tr>
											<th colspan="3">Particulars of previous civil service if any and amount and nature of any pension or gratuity received. :</th>
											<td>{{ (!empty($proposal->is_civil_service_amount_received)) ? 'Yes' : 'No'  }}</td>
										</tr>
										<tr>
											<th>Enter particulars of previous civil service :</th>
											<td>{{ (!empty($proposal->civil_service_name)) ? $proposal->civil_service_name : 'NA'  }}</td>
											<th>Enter amount of any pension / gratuity received :</th>
											<td>{{ (!empty($proposal->pension_gratuity_received_amount)) ? $proposal->pension_gratuity_received_amount : 'NA'  }}</td>
										</tr>

									</table>

								</div>
							</div>
						</div>


						<div class="card">
							<div class="card-header" role="tab" id="headingThree">
								<h6 class="mb-0">
									<a data-toggle="collapse" href="#collapseThree" aria-expanded="true" aria-controls="collapseThree">Nominees</a>
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
									<a data-toggle="collapse" href="#collapseFour" aria-expanded="true" aria-controls="collapseFour">List of Documents</a>
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

						@if($add_recovery->count() > 0)
                            <div class="card">
                                <div class="card-header" role="tab" id="headingSix">
                                    <h6 class="mb-0">
                                    <a class="collapsed" data-toggle="collapse" href="#collapseSix" aria-expanded="false" aria-controls="collapseSix">Recovery Details</a>
                                    </h6>
                                </div>
                                <div id="collapseSix" class="collapse @if($application->application_status_id == 2) show @endif" role="tabpanel" aria-labelledby="headingSix" data-parent="#accordion">
                                    <div class="card-body">
                                        <table class="table table-bordered">
                                            @foreach($add_recovery as $recovery)
                                            <tr>
                                                <th>{{ $recovery->recovery_label }}</th>
                                                <td>{{ $recovery->recovery_value  }}</td>
                                            </tr>
                                            @endforeach
                                            @if(!empty($application->recovery_attachment))
                                                <tr>
                                                    <th>Last Pay Certificate</th>
                                                    <td>
                                                        <span class="document_pdf_span" data-title="Last Pay Certificate" data-pdf="{{ asset('public/' . $application->recovery_attachment) }}"><i class="fa fa-file-pdf-o"></i></span>
                                                    </td>
                                                </tr>
                                            @endif
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(!empty($service_form))
                            <div class="card">
                                <div class="card-header" role="tab" id="headingSeven">
                                    <h6 class="mb-0">
                                    <a class="collapsed" data-toggle="collapse" href="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">Part - II</a>
                                    </h6>
                                </div>
                                <div id="collapseSeven" class="collapse @if($application->application_status_id == 12) show @endif" role="tabpanel" aria-labelledby="headingSeven" data-parent="#accordion">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Date of Birth of Employee: </label> {{ (!empty($proposal->date_of_birth)) ? \Carbon\Carbon::parse($proposal->date_of_birth)->format('m/d/Y') : ''  }}
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Date of Joining: </label> {{ (!empty($proposal->date_of_joining)) ? \Carbon\Carbon::parse($proposal->date_of_joining)->format('m/d/Y') : ''  }}
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Date of Retirement: </label> {{ (!empty($proposal->date_of_retirement)) ? \Carbon\Carbon::parse($proposal->date_of_retirement)->format('m/d/Y') : ''  }}
                                                </div>
                                            </div>
                                        </div>

                                        <hr>
                                        <h5 class="text-center">Service Rendered in various offices/posts</h5>
                                        <hr>
                                        @php
                                            $service_form_offices = DB::table('optcl_employee_pension_service_offices')->where('application_id', $application->id)->where('service_pension_form_id', $service_form->id)->get();

                                        @endphp
                                        <div class="row">
                                            <div class="col-md-12">
                                                <table class="table table-bordered">
                                                    <thead class="text-center">
                                                        <tr>
                                                            <th rowspan="2" class="fsize">Organisation</th>
                                                            <th rowspan="2" class="fsize">Name Of Office</th>
                                                            <th rowspan="2" class="fsize">Post Held</th>
                                                            <th colspan="2" class="fsize">Period</th>
                                                            <th colspan="3" class="fsize">Period of Service</th>
                                                            <!-- <th rowspan="3" class="fsize">Action</th> -->
                                                        </tr>
                                                        <tr>
                                                            <th class="fsize">From</th>
                                                            <th class="fsize">to</th>
                                                            <th class="fsize">Years</th>
                                                            <th class="fsize">Months</th>
                                                            <th class="fsize">Days</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if($organisation_details->count() > 0)
                                                        @foreach($organisation_details as $service_form_office)
                                                        <tr class="service_rendered_len">
                                                            <td>{{ $service_form_office->organisation_name }}</td>
                                                            
                                                            <td>{{ $service_form_office->name_of_office }}</td>
                                                            
                                                            <td>{{ $service_form_office->post_held }}</td>
                                                            
                                                            <td>{{ \Carbon\Carbon::parse($service_form_office->service_period_from)->format('d/m/Y') }}</td>
                                                            
                                                            <td>{{ \Carbon\Carbon::parse($service_form_office->service_period_to)->format('d/m/Y') }}</td>
                                                            
                                                            <td>{{ $service_form_office->total_service_years }}</td>
                                                            
                                                            <td>{{ $service_form_office->total_service_months }}</td>
                                                            
                                                            <td>{{ $service_form_office->total_service_days }}</td>
                                                        </tr>
                                                        @endforeach
                                                        @endif
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="5" class="text-right">Gross Qualifying Period</th>
                                                            <td class="gross_years">{{ $service_form->gross_years }}</td>
                                                            <td class="gross_months">{{ $service_form->gross_months }}</td>
                                                            <td class="gross_days">{{ $service_form->gross_days }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th colspan="1">Non-Qualifying Period</th>
                                                            <td colspan="2">
                                                                {{ !empty($service_form->non_qualifying_period_from) ? \Carbon\Carbon::parse($service_form->non_qualifying_period_from)->format('d/m/Y') : '' }}
                                                            </td>

                                                            <td colspan="2">
                                                                {{ !empty($service_form->non_qualifying_period_to) ? \Carbon\Carbon::parse($service_form->non_qualifying_period_to)->format('d/m/Y') : '' }}
                                                            </td>

                                                            <td class="non_qualifying_years">{{ $service_form->non_qualifying_years }}</td>
                                                            <td class="non_qualifying_months">{{ $service_form->non_qualifying_months }}</td>
                                                            <td class="non_qualifying_days">{{ $service_form->non_qualifying_days }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th colspan="5" class="text-right">Net Qualifying Period</th>
                                                            <td class="net_years">{{ $service_form->net_qualifying_years }}</td>
                                                            <td class="net_months">{{ $service_form->net_qualifying_months }}</td>
                                                            <td class="net_days">{{ $service_form->net_qualifying_days }}</td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="staticEmail" class="col-sm-6 col-form-label">Whether the entire the service period is duly covered by service verficiation Certificate. (Specify the periods not verified)</label>
                                            <div class="col-sm-6 mrgtop">
                                                @if($service_form->is_service_period_duly == 1)
                                                    Yes
                                                @else
                                                    No
                                                @endif
                                            </div>
                                        </div>

                                        @if($service_form->is_service_period_duly == 1)
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Period From :</label> {{ !empty($service_form->service_period_duly_from) ? \Carbon\Carbon::parse($service_form->service_period_duly_from)->format('d/m/Y') : '' }}
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Period To :</label> {{ !empty($service_form->service_period_duly_to) ? \Carbon\Carbon::parse($service_form->service_period_duly_to)->format('d/m/Y') : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="form-group row">
                                            <label for="staticEmail" class="col-sm-6 col-form-label">Whether all the periods of absence including participation in strike, etc. have been regularised by grant  of leave or otherwise.(Specify the periods) </label>
                                            <div class="col-sm-6 mrgtop">
                                                @if($service_form->is_period_of_absence == 1)
                                                    Yes
                                                @else
                                                    No
                                                @endif
                                            </div>
                                        </div>

                                        @if($service_form->is_period_of_absence == 0)
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Period From :</label> {{ !empty($service_form->service_period_absence_from) ? \Carbon\Carbon::parse($service_form->service_period_absence_from)->format('d/m/Y') : '' }}
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Period To :</label> {{ !empty($service_form->service_period_absence_to) ? \Carbon\Carbon::parse($service_form->service_period_absence_to)->format('d/m/Y') : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="form-group row">
                                            <label for="staticEmail" class="col-sm-6 col-form-label">Status of departmental or judicial proceedings instituted/contemplated Or to be instituted against the employee if any </label>
                                            <div class="col-sm-6 mrgtop">
                                                @if($service_form->is_departmental_or_judicial == 1)
                                                    Yes
                                                @else
                                                    No
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="staticEmail" class="col-sm-6 col-form-label">Pay Band / Scale of Pay</label>
                                            <div class="col-sm-6">
                                                {{ $service_form->scale_of_pay }}
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="staticEmail" class="col-sm-6 col-form-label">Last Basic Pay as on the date of retirement/cessation of service</label>
                                            <div class="col-sm-6">
                                                {{ $service_form->last_basic_pay }}
                                            </div>
                                        </div>

                                        <hr>
                                        <h5 class="text-center">NO DEMAND CERTIFICATE</h5>
                                        <hr>

                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <label class="form-check-label">
                                                        There is nothing outstanding against <b>Sri/ Smt/ Miss {{ $proposal->employee_name }}, {{ (!empty($proposal->designation_name)) ? $proposal->designation_name : ''  }}</b> retired on <b>{{ $proposal->date_of_retirement }}</b> of <b>{{ $proposal->unit_name}}</b> so far as the GRIDCO/OPTCL is concerned / except (specify)
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

						<div class="card">
							<div class="card-header" role="tab" id="headingFive">
								<h6 class="mb-0">
									<a class="collapsed" data-toggle="collapse" href="#collapseFive" aria-expanded="false" aria-controls="collapseThree">Status History</a>
								</h6>
							</div>
							<div id="collapseFive" class="collapse" role="tabpanel" aria-labelledby="headingFive" data-parent="#accordion">
								<div class="card-body">
									<ul class="bullet-line-list">
										@if(!empty($statusHistory))
											@foreach($statusHistory as $status_data)
												<li>
												  <h6>{{ $status_data->status_name }}</h6>
												  	@if($status_data->remarks)
													  <p class="mb-0">{{$status_data->remarks}}</p>
													@endif 
													<p class="text-muted">
													    <i class="mdi mdi-clock"></i>
													    {{ date('d M Y h:i A', strtotime($status_data->created_at)) }}
													</p>
												</li>
											@endforeach
										@endif
									</ul>
								</div>
							</div>
						</div>
						
						
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
                    url:'{{ route("nominee_application_submit") }}',
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
                            location.href = "{{route('nominee_application_view_details')}}";
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
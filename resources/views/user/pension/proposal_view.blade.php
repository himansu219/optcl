@extends('user.layout.layout')

@section('section_content')

<div class="content-wrapper">
	<div class="row">
		<div class="col-12 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title">Pension Proposal Details</h4>
					<div class="accordion" id="accordion" role="tablist">
						<div class="card">
							<div class="card-header" role="tab" id="headingOne">
								<h6 class="mb-0">
									<a data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Pension Form</a>
								</h6>
							</div>
							<div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion">
								<div class="card-body">
									<div class="row">
										<div class="col-md-4">
											<label for="exampleInputPassword4"><b>Employee No/Code :</b> </label>
											<span>{{ (!empty($proposal->emp_code)) ? $proposal->emp_code : ''  }}</span>
										</div>

										<div class="col-md-4">
											<label for="exampleInputPassword4"><b>Aadhaar No :</b> </label>
											<span>{{ (!empty($proposal->aadhaar_no)) ? $proposal->aadhaar_no : ''  }}</span>
										</div>

										<div class="col-md-4">
											<label for="exampleInputPassword4"><b>Name :</b> </label>
											<span>{{ (!empty($proposal->name)) ? $proposal->name : ''  }}</span>
										</div>
									</div>

									<div class="row">
										<div class="col-md-4">
											<label for="exampleInputPassword4"><b>Designation :</b> </label>
											<span>{{ (!empty($proposal->designation)) ? $proposal->designation : ''  }}</span>
										</div>

										<div class="col-md-4">
											<label for="exampleInputPassword4"><b>Father's Name :</b> </label>
											<span>{{ (!empty($proposal->father_name)) ? $proposal->father_name : ''  }}</span>
										</div>

										<div class="col-md-4">
											<label for="exampleInputPassword4"><b>Gender :</b> </label>
											<span>
												@if((!empty($proposal->gender) && $proposal->gender == 1))
													Male
												@elseif((!empty($proposal->gender) && $proposal->gender == 2))
													Female
												@else
													
												@endif
											</span>
										</div>

										<div class="col-md-4">
											<label for="exampleInputPassword4"><b>Marital Status :</b> </label>
											<span>
												@if((!empty($proposal->marital_status) && $proposal->marital_status == 1))
													Married
												@elseif((!empty($proposal->marital_status) && $proposal->marital_status == 2))
													Unmarried
												@else
													
												@endif
											</span>
										</div>

										@if($proposal->gender == 2 && $proposal->marital_status == 1)
										<div class="col-md-4">
											<label for="exampleInputPassword4"><b>Husband's Name :</b> </label>
											<span>{{ (!empty($proposal->husband_name)) ? $proposal->husband_name : ''  }}</span>
										</div>
										@endif

										<div class="col-md-4">
											<label for="exampleInputPassword4"><b>Religion :</b> </label>
											<span>
												{{ (!empty($proposal->getReligion)) ? $proposal->getReligion->name : ''  }}
											</span>
										</div>

										<div class="col-md-4">
											<label for="exampleInputPassword4"><b>PF A/C No. :</b> </label>
											<span>{{ (!empty($proposal->pf_acno)) ? $proposal->pf_acno : ''  }}</span>
										</div>

										<div class="col-md-4">
											<label for="exampleInputPassword4"><b>Name of the Office :</b> </label>
											<span>{{ (!empty($proposal->officeLastServerd)) ? $proposal->officeLastServerd->name : ''  }}</span>
										</div>

										<div class="col-md-4">
											<label for="exampleInputPassword4"><b>Date of Joining Service :</b> </label>
											<span>{{ (!empty($proposal->doj)) ? \Carbon\Carbon::parse($proposal->doj)->format('d-m-Y') : ''  }}</span>
										</div>

										<div class="col-md-4">
											<label for="exampleInputPassword4"><b>Date of Retirement :</b> </label>
											<span>{{ (!empty($proposal->dor)) ? \Carbon\Carbon::parse($proposal->dor)->format('d-m-Y') : ''  }}</span>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="card">
							<div class="card-header" role="tab" id="headingTwo">
								<h6 class="mb-0">
									<a class="collapsed" data-toggle="collapse" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">Personal Details</a>
								</h6>
							</div>
							<div id="collapseTwo" class="collapse" role="tabpanel" aria-labelledby="headingTwo" data-parent="#accordion">
								<div class="card-body">
									
								</div>
							</div>
						</div>


						<div class="card">
							<div class="card-header" role="tab" id="headingThree">
								<h6 class="mb-0">
									<a class="collapsed" data-toggle="collapse" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">Nominee Details</a>
								</h6>
							</div>
							<div id="collapseThree" class="collapse" role="tabpanel" aria-labelledby="headingThree" data-parent="#accordion">
								<div class="card-body">
									
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
@endsection
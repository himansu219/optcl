@extends('user.layout.layout')

@section('section_content')
<style>
  #income_property_error{
    color: #ff0000;
    margin-top: 2px !important; 
    display: block;
  } 
  #other_income_error{
    color: #ff0000;
    margin-top: 2px !important; 
    display: block;
  } 
  #lic_error{
    color: #ff0000;
    margin-top: 2px !important; 
    display: block;
  } 
  #nsc_error{
    color: #ff0000;
    margin-top: 2px !important; 
    display: block;
  } 
  #ppf_error{
    color: #ff0000;
    margin-top: 2px !important; 
    display: block;
  } 
  #ety_d_error{
    color: #ff0000;
    margin-top: 2px !important; 
    display: block;
  } 
  #ety_dd_error{
    color: #ff0000;
    margin-top: 2px !important; 
    display: block;
  } 
  .mdi-pencil{
    font-size:20px;
    color:rgb(74, 172, 74);
  }
  .mdi-pencil:hover{
    color:rgb(0, 100, 0);
  }
  .mdi-delete{
    font-size:20px;
    color:rgb(225, 83, 83);
  }
  .mdi-delete:hover{
    color:rgb(191, 0, 0);
  }
  #sampleTable{
      border: 1px solid rgb(233, 233, 233);
  }
  .addClassbtn {
        margin-left:900px;
    }
 .fsize{
    font-size: 14px;
 }
  
</style>
<div class="content-wrapper">
          <nav aria-label="breadcrumb" role="navigation">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{route('user_dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="#">Update Pension Record</a></li>
              <li class="breadcrumb-item"><a href="{{route('update_pension_record')}}">Listing</a></li>
              <li class="breadcrumb-item active" aria-current="page">View Details</li>
            </ol>
          </nav>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                           <h4 class="card-title">Pension Record Details</h4>
                            @php
                            $bank_branch_id = $result->bank_branch_id;
                            $ifsc = DB::table('optcl_bank_branch_master')->where('id', $bank_branch_id)->first();
                            $bname = DB::table('optcl_bank_master')->where('id', $ifsc->bank_id)->first();
                            $status = '';
                                if($result->is_approved == 0){
                                    //$show = 'Yes';
                                    $dClass = 'badge-warning';
                                    $status = 'Pending';
                                }else{
                                    //$show = 'No';
                                    $dClass = 'badge-success';
                                    $status = 'Approved';
                                    
                                }
                            @endphp
                               <table class="table table-bordered">
                                    <tr class="view-tax">
                                        <th class="fsize">Created At</th>
                                        <td>{{ date("d-m-Y h:i A", strtotime($result->created_at)) }}</td>
                                    </tr>
                                    <tr class="view-tax">
                                        <th class="fsize">Status</th>
                                        <td>{{ $status }}</td>
                                    </tr>
                                    <tr class="view-tax">
                                        <th class="fsize">Bank A/C No.</th>
                                        <td>{{ $result->bank_ac_no }}</td>
                                    </tr>
                                    <tr class="view-tax">
                                        <th class="fsize">Bank Name</th>
                                        <td>{{ $bname->bank_name }}</td>
                                    </tr>
                                    <tr class="view-tax">
                                        <th class="fsize">Bank Branch Name</th>
                                        <td>{{ $ifsc->branch_name }}</td>
                                    </tr>
                                    <tr class="view-tax">
                                        <th class="fsize">IFSC Code</th>
                                        <td>{{ $ifsc->ifsc_code }}</td>
                                    </tr>
                                    <tr class="view-tax">
                                        <th class="fsize">MICR Code</th>
                                        <td>{{ $ifsc->micr_code }}</td>
                                    </tr>
                                   
                                </table>
                            </div>
                     
            </div>
        </div>
    </div>
</div>

@endsection
@section('page-script')

 @endsection
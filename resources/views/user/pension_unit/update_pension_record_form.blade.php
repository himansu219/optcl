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
  
</style>
<div class="content-wrapper">
    <nav aria-label="breadcrumb" role="navigation">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('user_dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item">Update Pension Record</li>
      </ol>
    </nav> 
    <div class="row">
        <div class="col-md-12 grid-margin">


        <div class="card">
          <div class="card-body">
              <h4 class="card-title">Filter</h4>
              <form class="forms-sample" id="filter_applications" method="post" action="" autocomplete="off">
                  @csrf
                  <div class="row">
                    <div class="col-md-3">
                      <label>Old/New PPO No.</label>
                      <input type="text" name="application_no" class="form-control" id="application_no" value="{{ !empty($request->application_no) ? $request->application_no : '' }}" >
                    </div>

                    <div class="col-md-3">
                      <label>Employee Code</label>
                      <input type="text" name="employee_code" class="form-control" id="employee_code" value="{{ !empty($request->employee_code) ? $request->employee_code : '' }}" maxlength="5">
                    </div>

                    <div class="col-md-3">
                      <label>Aadhaar/ Mobile No.</label>
                      <input type="text" name="employee_aadhaar_no" class="form-control only_number" maxlength="12" id="employee_aadhaar_no" value="{{ !empty($request->employee_aadhaar_no) ? $request->employee_aadhaar_no : '' }}">
                    </div>

                    <div class="col-md-3">
                      <label>Status</label>
                      <select class="js-example-basic-single form-control" id="app_status_id" name="app_status_id">
                          <option value="">Select Status</option>
                          
                      </select>
                    </div>
                                          
                  </div><br>
                  <button type="submit" id="filters" class="btn btn-success">Filter</button>
                  <a href="#" class="btn btn-warning">Reset</a>
              </form>
            </div>
        </div>

        <div class="card">
          <div class="card-body">
              <h4 class="card-title">Application List                
                  <a href="{{ route('monthly_changed_data_history') }}" class="btn btn-success float-right">Update Record</a>                 
              </h4>

              <div class="row">
                <div class="table-sorter-wrapper col-lg-12 table-responsive">
                  <table id="sortable-table-1" class="table table-striped">
                    <thead>
                      <tr>
                        <th class="sortStyle">Sl No.  </th>
                        <th class="sortStyle">Unit Name </th>
                        <th class="sortStyle">Employee Code</th>
                        <th class="sortStyle">Created at</th>
                        <th class="sortStyle">Status</th>
                        <th class="sortStyle">Action</th>
                        
                      </tr>
                    </thead>
                    <tbody>
                      
                    @if(count($result))
                    @foreach($result as $key => $list)
                          @php
                          //$dClass = '';
                          $status = '';
                          if($list->is_approved == 0){
                              //$show = 'Yes';
                              $dClass = 'badge-warning';
                              $status = 'Pending';
                          }else{
                              //$show = 'No';
                              $dClass = 'badge-success';
                              $status = 'Approved';
                              
                          }
                          $pension_id = DB::table('optcl_pension_unit_master')->where('id',$pension_unit_id)->first();

                            @endphp
                      <tr>
                        <td>{{ $result->firstItem() + $key }}</td>
                        <td>{{ $pension_id->pension_unit_name }}</td>
                        <td>{{ $list->employee_code }}</td>
                        <td>{{ date("d-m-Y h:i A", strtotime($list->created_at)) }}</td>
                        <td>
                          <label class="badge {{$dClass}} ">{{ $status }}</label>
                        </td>
                        <td><a  href="{{URL('pension-unit/update-pension-record/view/'.$list->id)}}"><i class="icon-eye"></i></a></td>
                        
                      </tr>
                      @endforeach
                      @else
                      <tr><td colspan="10" align="center">No Data Found</td></tr>    
                      @endif
                    </tbody>
                  </table>
                  @if ($result->lastPage() > 1)
                  <nav aria-label="..." class="float-right">
                      <ul class="pagination">
                          <li class="{{ ($result->currentPage() == 1) ? ' disabled' : '' }} page-item">
                              <a class="page-link" href="{{ $result->url(1) }}">Previous</a>
                          </li>
                          @for ($i = 1; $i <= $result->lastPage(); $i++)
                              <li class="{{ ($result->currentPage() == $i) ? ' active' : '' }}  page-item">
                                  <a class="page-link" href="{{ $result->url($i) }}">{{ $i }}</a>
                              </li>
                          @endfor
                          <li class="{{ ($result->currentPage() == $result->lastPage()) ? ' disabled' : '' }} page-item">
                              <a class="page-link" href="{{ $result->url($result->currentPage()+1) }}" >Next</a>
                          </li>
                      </ul>
                  </nav>
                  @endif
                </div>
              </div>
          </div>
        </div>

            
        </div>
    </div>
</div>
<!-- content-wrapper ends -->
              


 @endsection
 @section('page-script')

 @endsection
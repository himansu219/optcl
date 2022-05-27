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
    @if(Session::has('error'))
        <div class="alert alert-danger">{{ Session::get('error') }}</div>
    @endif
    @if(Session::has('success'))
        <div class="alert alert-success">{{ Session::get('success') }}</div>
    @endif
    <nav aria-label="breadcrumb" role="navigation" class="bg-white">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('user_dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item">Update Pension Record</li>
          <li class="breadcrumb-item" >Unit Change for Receiving Unit (Only)</li>
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
                        <label>PPO No.</label>
                        <input type="text" name="ppo_no_search" class="form-control" id="ppo_no_search" value="{{ !empty($request->ppo_no_search) ? $request->ppo_no_search : '' }}" maxlength="50">
                      </div>                    
                      <div class="col-md-3">
                        <label>Pensioner Name</label>
                        <input type="text" name="search_pensioner_name" class="form-control" id="search_pensioner_name" value="{{ !empty($request->search_pensioner_name) ? $request->search_pensioner_name : '' }}" maxlength="50">
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-3 mt-2">
                        <button type="submit" id="filters" class="btn btn-success">Filter</button>
                        <a href="{{ route('pension_unit_unit_change_receiving_unit_only_list') }}" class="btn btn-warning">Reset</a>
                      </div>
                    </div>
                  
              </form>
            </div>
        </div>

        <div class="card">
          <div class="card-body">
              <h4 class="card-title">Application List                
                  <a href="{{ route('pension_unit_unit_change_receiving_unit_only_add') }}" class="btn btn-success float-right">Add</a>                 
              </h4>
              <div class="row">
                <div class="table-sorter-wrapper col-lg-12 table-responsive">
                  <table id="sortable-table-1" class="table table-striped">
                    <thead>
                      <tr>
                        <th>Sl No.</th>
                        <th>PPO No.</th>
                        <th>Pensioner Name</th>
                        <th>Created at</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    @if(count($result))
                      @foreach($result as $key => $list)
                        <tr>
                          <td>{{ $result->firstItem() + $key }}</td>
                          <td>{{ $list->ppo_no }}</td>
                          <td>{{ $list->pensioner_name }}</td>
                          <td>{{ date("d-m-Y h:i A", strtotime($list->created_at)) }}</td>
                          <td>{{$list->status_name}}</td>
                          <td class="text-center">
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item text-black" data-toggle="dropdown">
                                        <i class="fa fa-bars"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" style="left: -21px;">
                                      <a href="{{ route('unit_change_receiving_unit_only_edit_page', array($list->cID)) }}" class="dropdown-item edit_desig"><i class="fa fa-pencil-square-o"></i>Edit</a>
                                      <a href="{{ route('unit_change_receiving_unit_only_view_page', array($list->cID)) }}" class="dropdown-item delete_desig"><i class="fa fa-eye"></i>View Details</a>
                                    </div>
                                </div>
                            </div>
                          </td>
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
@extends('user.layout.layout')

@section('section_content')

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
          <li class="breadcrumb-item active" aria-current="page">Arrears</li>
      </ol>
  </nav>
  <div class="row">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
            <h4 class="card-title">Filter</h4>
            <form class="forms-sample" id="filter_applications" method="post" action="" autocomplete="off">
                @csrf
                <div class="row">
                  <div class="col-md-3">
                    <label>PPO No.</label>
                    <input type="text" name="search_ppo_no" class="form-control" id="search_ppo_no" value="{{ !empty($request->search_ppo_no) ? $request->search_ppo_no : '' }}" >
                  </div>                                      
                </div>
                <div class="row mt-4">
                  <div class="col-md-12">
                    <button type="submit" id="filters" class="btn btn-success">Filter</button>
                    <a href="{{ route('billing_officer_arrears') }}" class="btn btn-warning">Reset</a>
                  </div>                  
                </div>
                
            </form>
          </div>
      </div>

      <div class="card">
        <div class="card-body">
            <h4 class="card-title">Application List
            <a href="{{route('billing_officer_arrears_add')}}" class="btn btn-success float-right" id="generate_bill">Add Arrear</a> 
            </h4>

            <div class="row">
              <div class="table-sorter-wrapper col-lg-12 table-responsive">
                <table id="sampleTable" class="table table-striped">
                  <thead>
                    <tr>
                      <th>Sl No.</th>
                      <th>Pensioner Type</th>
                      <th>Application Type</th>
                      <th>PPO No</th>
                      <th>Created At</th>
                      <th class="text-center">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if($applications->count() > 0)
                      @foreach($applications as $key => $application)
                        <tr>
                          <td>{{ $applications->firstItem() + $key }}</td>
                          <td>{{ $application->type_name }}</td>
                          <td>{{ $application->pension_type }}</td>
                          <td>{{ $application->new_ppo_no }}</td>
                          <td>{{ \Carbon\Carbon::parse($application->created_at)->format('d-m-Y h:i A') }}</td>
                          <td class="text-center">
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item text-black" data-toggle="dropdown">
                                        <i class="fa fa-bars"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" style="left: -21px;">
                                      <a href="{{ route('billing_officer_arrears_arrear_details', array($application->id)) }}" class="dropdown-item delete_desig"><i class="fa fa-eye"></i>Arrear Details</a>
                                      <a href="{{ route('billing_officer_arrears_multiple_arrear', array($application->id)) }}" class="dropdown-item delete_desig"><i class="fa fa-plus"></i>Add New</a>
                                    </div>
                                </div>
                            </div>
                          </td>
                        </tr>
                      @endforeach
                    @else
                      <tr><td colspan="5" align="center">No Data Found</td></tr>    
                    @endif
                  </tbody>
                </table>
                <nav aria-label="..." class="float-right">
                    <ul class="pagination">
                        <li class="{{ ($applications->currentPage() == 1) ? ' disabled' : '' }} page-item">
                            <a class="page-link" href="{{ $applications->url(1) }}">Previous</a>
                        </li>
                        @for ($i = 1; $i <= $applications->lastPage(); $i++)
                            <li class="{{ ($applications->currentPage() == $i) ? ' active' : '' }}  page-item">
                                <a class="page-link" href="{{ $applications->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor
                        <li class="{{ ($applications->currentPage() == $applications->lastPage()) ? ' disabled' : '' }} page-item">
                            <a class="page-link" href="{{ $applications->url($applications->currentPage()+1) }}" >Next</a>
                        </li>
                    </ul>
                </nav>
                
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
    $(document).ready(function() {

      $("#all_application_ids").change(function(){  //"select all" change 
          $(".application_ids").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
      });

      //".checkbox" change 
      $('.application_ids').change(function(){ 
        //uncheck "select all", if one of the listed checkbox item is unchecked
          if(false == $(this).prop("checked")){ //if this item is unchecked
              $("#all_application_ids").prop('checked', false); //change "select all" checked status to false
          }
        //check "select all" if all checkbox items are checked
        if ($('.application_ids:checked').length == $('.application_ids').length ){
          $("#all_application_ids").prop('checked', true);
        }
      });

    });



  </script>
@endsection
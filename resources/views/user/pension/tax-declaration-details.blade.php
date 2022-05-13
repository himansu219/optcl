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
          <li class="breadcrumb-item"><a href="{{route('tax_declaration')}}">Tax Declaration</a></li>
          <li class="breadcrumb-item active" aria-current="page">Listing</li>
        </ol>
      </nav> 
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                        
                    <div class="employe-code-check">
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="home-1" role="tabpanel" aria-labelledby="home-tab">
                                <div class="media">
                                    <div class="media-body">
                                        <h6 class="card-title">TAX DECLARATION</h6>
                                        <hr>
                                
                                           <table id="sortable-table-1" class="table table-striped">
                                            <thead>
                                              <tr>
                                                <th class="sortStyle">Sl No.</th>
                                                <th class="sortStyle">Created Date</th>
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
                                                @endphp
                                              <tr>
                                                <td>{{ $result->firstItem() + $key }}</td>
                                                <td>{{ date("d-m-Y h:i A", strtotime($list->created_at)) }}</td>
                                                {{-- <td>{{ \Carbon\Carbon::parse($list->created_at)->format('d-m-Y h:i:s') }}</td> --}}
                                                <td>
                                                <label class="badge {{$dClass}} ">{{ $status }}</label></td>

                                                <td><a  href="{{URL('user/tax-declaration/view/'.$list->id)}}"><i class="icon-eye"></i></a></td>
                                                {{-- <a class="btn btn-success btn-sm {{$dClass}}" href="{{URL('user/tax-declaration/edit/'.$list->id)}}">Edit</a> --}}
                                                
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
        </div>
    </div>
</div>
<!-- content-wrapper ends -->
              


 @endsection
 @section('page-script')

 @endsection
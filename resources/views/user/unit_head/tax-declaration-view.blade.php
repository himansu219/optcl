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
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            
            <div class="card">
                
                     
                         <div class="card-body">
                            <nav aria-label="breadcrumb" role="navigation">
                                <ol class="breadcrumb">
                                  <li class="breadcrumb-item"><a href="">Dashboard</a></li>
                                  <li class="breadcrumb-item"><a href="">Tax Declaration</a></li>
                                  <li class="breadcrumb-item"><a href="{{route('unit_head_tax_declaration')}}">Listing</a></li>
                                  <li class="breadcrumb-item active" aria-current="page">View Details</li>
                                </ol>
                            </nav>
                                      @php
                                        $dClass = '';
                                        if($result['0']->is_approved == 1){
                                            $dClass = 'd-none';
                                        }
                                    @endphp
                            
                            <button type="button" id="approve-btn" name="{{$result['0']->id}}" class="btn btn-success float-right {{$dClass}}">Approve</button><br><br>
                               <table class="table table-bordered">
                                    <tr>
                                        <th colspan="4" class="text-center-normal">TAX DECLARATION</th>
                                    </tr>
                                    <th class="fsize">Income other than Pension</th>
                                    @php
                                        $dClass = '';
                                        if($result['0']->is_income_other_pension == 1){
                                            $show = 'Yes';
                                        }else{
                                            $show = 'No';
                                            $dClass = 'd-none';
                                        }
                                    @endphp
                                    <td>{{$show}}</td> 
                                    
                                    <tr class="view-tax {{$dClass}}">
                                        <th class="fsize">Income Property</th>
                                        <td>{{$result['0']->income_property}}</td>
                                        
                                        <th class="fsize">Attachment</th>
                                        <td>
                                            @if($result['0']->income_property_file)
                                            <img class="document_img" src="{{ asset('public/' . $result['0']->income_property_file) }}"> 
                                           @endif
                                        </td>
                                    </tr>
                                    <tr class="view-tax {{$dClass}}">
                                        <th class="fsize">Other Income</th>
                                        <td>{{$result['0']->other_income}}</td>
                                        
                                        <th class="fsize">Attachment</th>
                                        <td>
                                            @if($result['0']->other_income_file)
                                            <img class="document_img" src="{{ asset('public/' . $result['0']->other_income_file) }}"> 
                                           @endif
                                        </td>
                                    </tr>
                                    <tr class="view-tax {{$dClass}}">
                                        <th class="fsize">LIC</th>
                                        <td>{{$result['0']->lic}}</td>
                                        
                                        <th class="fsize">Attachment</th>
                                        <td>
                                            @if($result['0']->lic_file)
                                            <img class="document_img" src="{{ asset('public/' . $result['0']->lic_file) }}"> 
                                           @endif
                                        </td>
                                    </tr>
                                    <tr class="view-tax {{$dClass}}">
                                        <th class="fsize">NSC</th>
                                        <td>{{$result['0']->nsc}}</td>
                                        
                                        <th class="fsize">Attachment</th>
                                        <td>
                                            @if($result['0']->nsc_file)
                                            <img class="document_img" src="{{ asset('public/' . $result['0']->nsc_file) }}"> 
                                           @endif
                                        </td>
                                    </tr>
                                    <tr class="view-tax {{$dClass}}">
                                        <th class="fsize">PPF</th>
                                        <td>{{$result['0']->ppf}}</td>
                                        
                                        <th class="fsize">Attachment</th>
                                        <td>
                                            @if($result['0']->ppf_file)
                                            <img class="document_img" src="{{ asset('public/' . $result['0']->ppf_file) }}"> 
                                           @endif
                                        </td>
                                    </tr>
                                    <tr class="view-tax {{$dClass}}">
                                        <th class="fsize">80 D</th>
                                        <td>{{$result['0']->eighty_d}}</td>
                                        
                                        <th class="fsize">Attachment</th>
                                        <td>
                                            @if($result['0']->eighty_d_file)
                                            <img class="document_img" src="{{ asset('public/' . $result['0']->eighty_d_file) }}"> 
                                           @endif
                                        </td>
                                    </tr>
                                    <tr class="view-tax {{$dClass}}">
                                        <th class="fsize">80 DD</th>
                                        <td>{{$result['0']->eighty_dd}}</td>
                                        
                                        <th class="fsize">Attachment</th>
                                        <td>
                                            @if($result['0']->eighty_dd_file)
                                            <img class="document_img" src="{{ asset('public/' . $result['0']->eighty_dd_file) }}"> 
                                           @endif
                                        </td>
                                    </tr>

                                </table>
                            </div>
                     
            </div>
        </div>
    </div>
</div>
<!-- content-wrapper ends -->
<div class="modal fade" id="img_show" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <img id="img-show" width="450" height="250">
            </div>
        </div>
    </div>
</div>            


 @endsection
 @section('page-script')
 <script type="text/javascript">
	$(document).ready(function() {
		$('.document_img').on('click', function() {
            var src = $(this).attr('src');

            $('#img-show').attr('src', src);
            $('#img_show').modal('show');
        });
	});
</script>
<script type="text/javascript">
    var base_url = "{{url('/')}}";
  </script>
  <script type="text/javascript">
    $(document).ready(function(){

     $("#approve-btn").on("click",function () {
        var userID = $(this).attr('name');
    //alert(userID);
    swal( {
      title: "Are you sure",
      text: "Want to approve this ?",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: '#DD6B55',
      confirmButtonText: 'Yes, delete it!',
      closeOnConfirm: true,
      closeOnCancel: true,
       })
        .then((value) => { 
          if(value){ 
              location.href=base_url+"/unit-head-approve-tax-declaration/"+userID;
        //   $.post(base_url+"member/delete/",{ pageID:idVal }); 
        //   swal({ title: "Data deleted successfully.", type: "success" })
        // .then(function(){
        //       location.reload();
        //   });
          
         } 
        });
    });
  });
</script>

 @endsection
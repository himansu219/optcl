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
        margin-left:815px;
    }
  
</style>
<div class="content-wrapper">
    <nav aria-label="breadcrumb" role="navigation">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('user_dashboard')}}">Dashboard</a></li>
          <li class="breadcrumb-item active" aria-current="page">Tax Declaration</li>
        </ol>
      </nav> 
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                        
                     <a class="btn btn-success btn-sm addClassbtn" href="{{route('fetchTaxDeclaration')}}">View Tax Declaration</a></br></br>
                 
                    <div class="employe-code-check">
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="home-1" role="tabpanel" aria-labelledby="home-tab">
                                <div class="media">
                                    <div class="media-body">
                                        <h6 class="card-title">TAX DECLARATION</h6>
                                        <hr>
                                        <br />

                                        
                                        {{-- <button type="button" id="show-tax-form" class="btn btn-xs btn-dark" style="float: right">Show Tax Declaration details</button> --}}
                                        <form  class="forms-sample" autocomplete="off" id="tax_form" action="" method="post"    enctype="multipart/form-data">
                                           @csrf
                                            <input type="hidden" name="emp_code" value="{{$username}}">
                                            <input type="hidden" name="emp_id" value="{{$empId}}">
                                            <input type="hidden" name="emp_aadhaar" value="{{$empAadhaar}}">
                                            <label for="exampleInputCity1">Income Other Than Pension</label>
                                            <br>

                        
                                                    <label class="radio-inline">
                                                      <input type="radio" name="is_income_other_pension" id="flexRadioDefault1" value="1">&nbsp; Yes
                                                    </label>&nbsp; &nbsp; &nbsp; &nbsp;
                                                  
                                                    <label class="radio-inline">
                                                      <input type="radio" name="is_income_other_pension" id="flexRadioDefault2" value="0" checked>&nbsp; No
                                                    </label>
                                                 
                                              <p></p>
                                              {{-- <hr> --}}
                                              <div class="row form_1_" id="tax_div">
                                                
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="income_property">Income From House Property<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control anns" id="income_property" name="income_property" maxlength="7" placeholder="Income from house property amount" onkeypress="return isNumberKey(event)">
                                                        {{-- <label id="emp_code-error" class="error text-danger" for="emp_code"></label> --}}
                                                        {{-- <span id="income_property_error"></span> --}}
                                                        <label id="income_property_error" class="error mt-2 text-danger" for="income_property"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                      <label>Income From House Property file  <span class="span-red">*</span></label>
                                                        <input type="file" name="income_property_file_path" id="income_property_file_path" class="file-upload-default">
                                                          <div class="input-group col-xs-12">
                                                            <input type="text" class="form-control file-upload-info" disabled placeholder="Upload income from house property file">
                                                            <div class="input-group-append">
                                                              <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                                            </div>
                                                           
                                                        </div>
                                                        <label id="income_property_file_path-error" class="error mt-2 text-danger" for="income_property_file_path"></label>
                                                    </div>
                                                  </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="other_income">Other Income<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control only_number" id="other_income" name="other_income" maxlength="7" placeholder="Other income" onkeypress="return isNumberKey(event)">
                                                        {{-- <label id="aadhaar_no-error" class="error text-danger" for="aadhaar_no"></label> --}}
                                                        {{-- <span id="other_income_error"></span> --}}
                                                        <label id="other_income_error" class="error mt-2 text-danger" for="other_income"></label>
                                                    </div>      
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                      <label>Other Income file  <span class="span-red">*</span></label>
                                                        <input type="file" name="other_income_file_path" id="other_income_file_path" class="file-upload-default">
                                                          <div class="input-group col-xs-12">
                                                            <input type="text" class="form-control file-upload-info" disabled placeholder="Upload other income file">
                                                            <div class="input-group-append">
                                                              <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                                            </div>
                                                           
                                                        </div>
                                                        <label id="other_income_file_path-error" class="error mt-2 text-danger" for="other_income_file_path"></label>
                                                    </div>
                                                  </div>
                                                
                                                <div class="col-md-12">
                                                  <h6>Savings</h6>
                                                </div>
                                              
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="lic">LIC<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control only_number" id="lic" name="lic" maxlength="7" placeholder="Enter LIC savings amount" onkeypress="return isNumberKey(event)">
                                                        {{-- <label id="name-error" class="error text-danger" for="name"></label> --}}
                                                        {{-- <span id="lic_error"></span> --}}
                                                        <label id="lic_error" class="error mt-2 text-danger" for="lic"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                      <label>LIC file  <span class="span-red">*</span></label>
                                                        <input type="file" name="lic_file_path" id="lic_file_path" class="file-upload-default">
                                                          <div class="input-group col-xs-12">
                                                            <input type="text" class="form-control file-upload-info" disabled placeholder="Upload LIC file">
                                                            <div class="input-group-append">
                                                              <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                                            </div>
                                                           
                                                        </div>
                                                        <label id="lic_file_path-error" class="error mt-2 text-danger" for="lic_file_path"></label>
                                                    </div>
                                                  </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="nsc">NSC<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control only_number" id="nsc" name="nsc" maxlength="7" placeholder="Enter NSC savings amount" onkeypress="return isNumberKey(event)">
                                                        {{-- <label id="name-error" class="error text-danger" for="name"></label> --}}
                                                        {{-- <span id="nsc_error"></span> --}}
                                                        <label id="nsc_error" class="error mt-2 text-danger" for="nsc"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                      <label>NSC file  <span class="span-red">*</span></label>
                                                        <input type="file" name="nsc_file_path" id="nsc_file_path" class="file-upload-default">
                                                          <div class="input-group col-xs-12">
                                                            <input type="text" class="form-control file-upload-info" disabled placeholder="Upload NSC file">
                                                            <div class="input-group-append">
                                                              <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                                            </div>
                                                           
                                                        </div>
                                                        <label id="nsc_file_path-error" class="error mt-2 text-danger" for="nsc_file_path"></label>
                                                    </div>
                                                  </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="ppf">PPF<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control only_number" id="ppf" name="ppf" maxlength="7" placeholder="Enter PPF savings amount" onkeypress="return isNumberKey(event)">
                                                        {{-- <label id="father_name-error" class="error text-danger" for="father_name"></label> --}}
                                                        {{-- <span id="ppf_error"></span> --}}
                                                        <label id="ppf_error" class="error mt-2 text-danger" for="ppf"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                      <label>PPF file  <span class="span-red">*</span></label>
                                                        <input type="file" name="ppf_file_path" id="ppf_file_path" class="file-upload-default">
                                                          <div class="input-group col-xs-12">
                                                            <input type="text" class="form-control file-upload-info" disabled placeholder="Upload PPF file">
                                                            <div class="input-group-append">
                                                              <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                                            </div>
                                                           
                                                        </div>
                                                        <label id="ppf_file_path-error" class="error mt-2 text-danger" for="ppf_file_path"></label>
                                                    </div>
                                                  </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="ety_d">80 D<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control only_number" id="ety_d" name="ety_d" maxlength="5" placeholder="Enter 80 D savings amount" onkeypress="return isNumberKey(event)">
                                                        {{-- <label id="father_name-error" class="error text-danger" for="father_name"></label> --}}
                                                        {{-- <span id="ety_d_error"></span> --}}
                                                        <label id="ety_d_error" class="error mt-2 text-danger" for="ety_d"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                      <label>80 D file  <span class="span-red">*</span></label>
                                                        <input type="file" name="ety_d_file_path" id="ety_d_file_path" class="file-upload-default">
                                                          <div class="input-group col-xs-12">
                                                            <input type="text" class="form-control file-upload-info" disabled placeholder="Upload 80 D file">
                                                            <div class="input-group-append">
                                                              <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                                            </div>
                                                           
                                                        </div>
                                                        <label id="ety_d_file_path-error" class="error mt-2 text-danger" for="ety_d_file_path"></label>
                                                    </div>
                                                  </div>
                                                <div class="col-md-6">
                                                  <div class="form-group">
                                                      <label for="ety_dd">80 DD<span class="text-danger">*</span></label>
                                                      <input type="text" class="form-control only_number" id="ety_dd" name="ety_dd" maxlength="5" placeholder="Enter 80 DD savings amount" onkeypress="return isNumberKey(event)">
                                                      {{-- <label id="father_name-error" class="error text-danger" for="father_name"></label> --}}
                                                      {{-- <span id="ety_dd_error"></span>                                                        <label id="ety_d_error" class="error mt-2 text-danger" for="ety_d"></label> --}}
                                                      <label id="ety_dd_error" class="error mt-2 text-danger" for="ety_dd"></label>
                                                  </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                      <label>80 DD file  <span class="span-red">*</span></label>
                                                        <input type="file" name="ety_dd_file_path" id="ety_dd_file_path" class="file-upload-default">
                                                          <div class="input-group col-xs-12">
                                                            <input type="text" class="form-control file-upload-info" disabled placeholder="Upload 80 DD file">
                                                            <div class="input-group-append">
                                                              <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                                            </div>
                                                           
                                                        </div>
                                                        <label id="ety_dd_file_path-error" class="error mt-2 text-danger" for="ety_dd_file_path"></label>
                                                    </div>
                                                  </div>
                                            </div>
                                            <button type="submit" name="submit" class="btn btn-success mr-2 btn-next" id="">Submit</button>
                                        </form>
                                        <hr>
                                        <br>
                                        <div id="tax-table">
                                            <table id="sampleTable" class="table table-striped">
                                                <thead>
                                                  <tr>
                                                    {{-- <th>Sl No.</th> --}}
                                                    <th class="sortStyle">Aadhaar No.</th>
                                                    <th class="sortStyle">Income Property</th>
                                                    <th class="sortStyle">Other Income</th>
                                                    <th class="sortStyle">LIC</th>
                                                    <th class="sortStyle">NSC</th>
                                                    <th class="sortStyle">PPF</th>
                                                    <th class="sortStyle">80 D</th>
                                                    <th class="sortStyle">80 DD</th>
                                                    <th class="sortStyle">Delete</th>
                                                  </tr>
                                                </thead>
                                                <tbody>
                                                {{-- @foreach($proposal_listing as $key => $list) --}}
                                                  
                                                  {{-- @endforeach --}}
                                                </tbody>
                                            </table>
                                            


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
</div>
<!-- content-wrapper ends -->
              


 @endsection
 @section('page-script')

  <script type="text/javascript">
    
    function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31 
          && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }
    
    $(document).ready(function() {
        
        // Fetch Tax Declaration Table
        fetchTax();
        function fetchTax(){
            $.ajax({
                type: "GET",
                url: "{{ route('fetchTaxDeclaration') }}",
                dataType: "json",
                success: function (response) {
                    // console.log(response.taxDetails);
                    var count = 1;
                    $.each(response.taxDetails, function (key, item) { 
                        
                        var incomeProperty = '';
                        var otherIncome = '';
                        var lic = '';
                        var nsc = '';
                        var ppf = '';
                        var eighty_d = '';
                        var eighty_dd = '';

                        if(item.income_property == null){
                            incomeProperty = 'N/A';
                        }else{
                            incomeProperty = item.income_property;
                        }                   
                        if(item.other_income == null){
                            otherIncome = 'N/A';
                        }else{
                            otherIncome = item.other_income;
                        }
                        if(item.lic == null){
                            lic = 'N/A';
                        }else{
                            lic = item.lic;
                        }
                        if(item.nsc == null){
                            nsc = 'N/A';
                        }else{
                            nsc = item.nsc;
                        }
                        if(item.ppf == null){
                            ppf = 'N/A';
                        }else{
                            ppf = item.ppf;
                        }
                        if(item.eighty_d == null){
                            eighty_d = 'N/A';
                        }else{
                            eighty_d = item.eighty_d;
                        }
                        if(item.eighty_dd == null){
                            eighty_dd = 'N/A';
                        }else{
                            eighty_dd = item.eighty_dd;
                        }

                        $('tbody').append('<tr id="tr'+item.id+'">\
                                <td>'+item.aadhaar_no+'</td>\
                                <td>'+incomeProperty+'</td>\
                                <td>'+otherIncome+'</td>\
                                <td>'+lic+'</td>\
                                <td>'+nsc+'</td>\
                                <td>'+ppf+'</td>\
                                <td>'+eighty_d+'</td>\
                                <td>'+eighty_dd+'</td>\
                                <td><a href="javascript:void(0)" value="'+item.id+'" data-id="'+item.id+'" id="del-tax" title="Delete"><i class="mdi mdi-delete"></i></a></td>\
                            </tr>'

                        );
                        if(item.income_property == null){
                            incomeProperty = '';
                        }else{
                            incomeProperty = item.income_property;
                        }                   
                        if(item.other_income == null){
                            otherIncome = '';
                        }else{
                            otherIncome = item.other_income;
                        }
                        if(item.lic == null){
                            lic = '';
                        }else{
                            lic = item.lic;
                        }
                        if(item.nsc == null){
                            nsc = '';
                        }else{
                            nsc = item.nsc;
                        }
                        if(item.ppf == null){
                            ppf = '';
                        }else{
                            ppf = item.ppf;
                        }
                        if(item.eighty_d == null){
                            eighty_d = '';
                        }else{
                            eighty_d = item.eighty_d;
                        }
                        if(item.eighty_dd == null){
                            eighty_dd = '';
                        }else{
                            eighty_dd = item.eighty_dd;
                        }
                        $('table').append('<div class="modal fade" id="taxmodal'+item.id+'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">\
                                                <div class="modal-dialog" role="document">\
                                                    <div class="modal-content">\
                                                    <div class="modal-header">\
                                                        <h5 class="modal-title" id="exampleModalLabel">Update Tax Declaration</h5>\
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">\
                                                        <span aria-hidden="true">&times;</span>\
                                                        </button>\
                                                    </div>\
                                                    <div class="modal-body">\
                                                        <div class="row">\
                                                            <div class="col-md-6">\
                                                                <div class="form-group">\
                                                                    <label for="edit_income_property">Income From House Property<span class="text-danger">*</span></label>\
                                                                    <input type="text" class="form-control anns edit_income_property" id="edit_income_property'+count+'" name="edit_income_property" maxlength="5" placeholder="Income from house property amount" onkeypress="return isNumberKey(event)">\
                                                                </div>\
                                                            </div>\
                                                            <div class="col-md-6">\
                                                                <div class="form-group">\
                                                                    <label for="edit_other_income">Other Income<span class="text-danger">*</span></label>\
                                                                    <input type="text" class="form-control anns edit_other_income" id="edit_other_income'+count+'" name="edit_other_income" maxlength="5" placeholder="Other income amount" onkeypress="return isNumberKey(event)">\
                                                                </div>\
                                                            </div>\
                                                        </div>\
                                                        <div class="row">\
                                                            <div class="col-md-6">\
                                                                <div class="form-group">\
                                                                    <label for="edit_lic">LIC<span class="text-danger">*</span></label>\
                                                                    <input type="text" class="form-control anns edit_lic" id="edit_lic'+count+'" name="edit_lic" maxlength="5" placeholder="LIC amount" onkeypress="return isNumberKey(event)">\
                                                                </div>\
                                                            </div>\
                                                            <div class="col-md-6">\
                                                                <div class="form-group">\
                                                                    <label for="edit_nsc">NSC<span class="text-danger">*</span></label>\
                                                                    <input type="text" class="form-control anns edit_nsc" id="edit_nsc'+count+'" name="edit_nsc" maxlength="5" placeholder="NSC amount" onkeypress="return isNumberKey(event)">\
                                                                </div>\
                                                            </div>\
                                                        </div>\
                                                        <div class="row">\
                                                            <div class="col-md-6">\
                                                                <div class="form-group">\
                                                                    <label for="edit_ppf">PPF<span class="text-danger">*</span></label>\
                                                                    <input type="text" class="form-control anns edit_ppf" id="edit_ppf'+count+'" name="edit_ppf" maxlength="5" placeholder="PPF amount" onkeypress="return isNumberKey(event)">\
                                                                </div>\
                                                            </div>\
                                                            <div class="col-md-6">\
                                                                <div class="form-group">\
                                                                    <label for="edit_ety_d">80 D<span class="text-danger">*</span></label>\
                                                                    <input type="text" class="form-control anns edit_ety_d" id="edit_ety_d'+count+'" name="edit_ety_d" maxlength="5" placeholder="80 D amount" onkeypress="return isNumberKey(event)">\
                                                                </div>\
                                                            </div>\
                                                        </div>\
                                                        <div class="row">\
                                                            <div class="col-md-6">\
                                                                <div class="form-group">\
                                                                    <label for="edit_ety_dd">80 DD<span class="text-danger">*</span></label>\
                                                                    <input type="text" class="form-control anns edit_ety_dd" id="edit_ety_dd'+count+'" name="edit_ety_dd" maxlength="5" placeholder="80 DD amount" onkeypress="return isNumberKey(event)">\
                                                                </div>\
                                                            </div>\
                                                        </div>\
                                                    </div>\
                                                    <div class="modal-footer">\
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>\
                                                        <button type="button" id="edit-tax" value="'+item.id+'" data-id="'+item.id+'" class="btn btn-primary">Update</button>\
                                                    </div>\
                                                    </div>\
                                                </div>\
                                            </div>'

                        );
                        
                        $('#edit_other_income'+count+'').attr('value', otherIncome);
                        $('#edit_lic'+count+'').attr('value', lic);
                        $('#edit_income_property'+count+'').attr('value', incomeProperty);
                        $('#edit_nsc'+count+'').attr('value', nsc);
                        $('#edit_ppf'+count+'').attr('value', ppf);
                        $('#edit_ety_d'+count+'').attr('value', eighty_d);
                        $('#edit_ety_dd'+count+'').attr('value', eighty_dd);
                        return count ++;


                        
                        
                    });
                }
            });
        }
        
        //Insert Tax Declaration
        $("#tax_form").validate({
            rules: {
                income_property: {
                    required: true,
                    number: true,
                    maxlength: 7
                    
                },
                other_income: {
                    required: true,
                    number: true,
                    maxlength: 7
                },
                lic:{
                    required: true,
                    number: true,
                    maxlength: 7
                },
                nsc:{
                    required: true,
                    number: true,
                    maxlength: 7
                },
                ppf: {
                    required: true,
                    number: true,
                    maxlength: 7
                },
                ety_d: {
                    required: true,
                    number: true,
                    maxlength: 7
                },
                ety_dd: {
                    required: true,
                    number: true,
                    maxlength: 7
                },
                income_property_file_path: {
                    required: true 
                },
                other_income_file_path: {
                    required: true 
                },
                lic_file_path: {
                    required: true 
                },
                nsc_file_path: {
                    required: true 
                },
                ppf_file_path: {
                    required: true 
                },
                ety_d_file_path: {
                    required: true 
                },
                ety_dd_file_path: {
                    required: true 
                }
            },
            messages: {                         
                income_property: {                    
                    required: 'Please enter income property',
                    maxlength: 'Please enter maximum 7 digits'
                },
                other_income: {
                    required: 'Please enter other income',
                    maxlength: 'Please enter maximum 7 digits'
                },
                lic:{
                    required: 'Please enter lic amount',
                    maxlength: 'Please enter maximum 7 digits'
                },
                nsc:{
                    required: 'Please enter nsc amount',
                    maxlength: 'Please enter maximum 7 digits'
                },
                ppf: {
                    required: 'Please enter ppf amount',
                    maxlength: 'Please enter maximum 7 digits'
                },
                ety_d: {
                    required: 'Please enter 80 D amount',
                    maxlength: 'Please enter maximum 7 digits'
                },
                ety_dd: {
                    required: 'Please enter 80 DD amount',
                    maxlength: 'Please enter maximum 7 digits'
                },
                income_property_file_path: {
                    required: 'Please select file',
                },
                other_income_file_path: {
                    required: 'Please select file',
                },
                lic_file_path: {
                    required: 'Please select file',
                },
                nsc_file_path: {
                    required: 'Please select file',
                },
                ppf_file_path: {
                    required: 'Please select file',
                },
                ety_d_file_path: {
                    required: 'Please select file',
                },
                ety_dd_file_path: {
                    required: 'Please select file',
                }
            },
            submitHandler: function(form, event) { 
                event.preventDefault();
                var formData = new FormData(form);
                //$("#logid").prop('disabled',true);
                $.ajax({
                    type:'POST',
                    url:'{{ route("submit_tax_declaration") }}',
                    data: formData,
                    dataType: 'JSON',
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if(response['error']){
                        
                            for (i in response['error']) {
                                var element = $('#' + i);
                                var id = response['error'][i]['id'];
                                var eValue = response['error'][i]['eValue'];
                                //console.log(id);
                                //console.log(eValue);
                                $("#"+id).show();
                                $("#"+id).html(eValue);
                            }

                        }else if(response['loginCheckMessage']){
                            location.href = "{{route('tax_declaration')}}";
                        }else{
                           
                            var elementID = response.lastID;
                            var taxDec = response.taxDeclaration;
                            
                            var incomeProperty = '';
                            var otherIncome = '';
                            var lic = '';
                            var nsc = '';
                            var ppf = '';
                            var eighty_d = '';
                            var eighty_dd = '';

                            if(taxDec.income_property == null){
                                incomeProperty = 'N/A';
                            }else{
                                incomeProperty = taxDec.income_property;
                            }                   
                            if(taxDec.other_income == null){
                                otherIncome = 'N/A';
                            }else{
                                otherIncome = taxDec.other_income;
                            }
                            if(taxDec.lic == null){
                                lic = 'N/A';
                            }else{
                                lic = taxDec.lic;
                            }
                            if(taxDec.nsc == null){
                                nsc = 'N/A';
                            }else{
                                nsc = taxDec.nsc;
                            }
                            if(taxDec.ppf == null){
                                ppf = 'N/A';
                            }else{
                                ppf = taxDec.ppf;
                            }
                            if(taxDec.eighty_d == null){
                                eighty_d = 'N/A';
                            }else{
                                eighty_d = taxDec.eighty_d;
                            }
                            if(taxDec.eighty_dd == null){
                                eighty_dd = 'N/A';
                            }else{
                                eighty_dd = taxDec.eighty_dd;
                            }
                            $('tbody').append('<tr id="tr'+taxDec.id+'">\
                                    <td>'+taxDec.aadhaar_no+'</td>\
                                    <td>'+incomeProperty+'</td>\
                                    <td>'+otherIncome+'</td>\
                                    <td>'+lic+'</td>\
                                    <td>'+nsc+'</td>\
                                    <td>'+ppf+'</td>\
                                    <td>'+eighty_d+'</td>\
                                    <td>'+eighty_dd+'</td>\
                                    <td><a href="javascript:void(0)" data-toggle="modal" data-target="#taxmodal'+taxDec.id+'" title="Edit"><i class="mdi mdi-pencil"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" value="'+taxDec.id+'" data-id="'+taxDec.id+'" id="del-tax" title="Delete"><i class="mdi mdi-delete"></i></a></td>\
                                </tr>'

                            );
                            swal("Tax declaration has been inserted!", {
                                icon: "success",
                                // text:"Loading...",
                                buttons: false,      
                                closeOnClickOutside: false,
                                timer: 10000,
                                
                            });
                            location.reload();
                        }
                    }
                });
            },
            errorPlacement: function(label, element) {
                label.addClass('text-danger');
                label.insertAfter(element);
            },
            highlight: function(element, errorClass) {
                $(element).parent().addClass('has-success');
                $(element).addClass('form-control-danger');
            }
        });


        // delete Tax Declaration
        $(document).on('click', '#del-tax', function(e){
            e.preventDefault();
            var id = $(this).data("id");
            
            // if(confirm("Do you really want to delete this record?")){
                $.ajax({
                    type: "POST",
                    url: "{{route('delTaxDeclaration')}}",
                    data: {
                        _token :  $("input[name=_token]").val(),
                        id : id,
                    },
                    success: function (response) {
                        $('tbody #tr'+id+'').remove();
                        console.log(id);
                        swal("Tax declaration has been deleted!", {
                            icon: "success",
                            // text:"Loading...",
                            buttons: false,      
                            closeOnClickOutside: false,
                            timer: 1000,
                        });
                    }
                });
            // }
            // console.log(id);
        })


        // Edit Tax Declaration
        // 
        
            
        

        $('#tax-table').hide();
        $(document).on('click', '#show-tax-form', function(e){
            e.preventDefault();
            $(this).html('Hide Tax Declaration details');
            $('#tax-table').show();
            $(this).prop('id', 'hide-tax-form');
        });
        // $("#tax-table").hide();
        
        $(document).on('click', '#hide-tax-form', function(e){
            e.preventDefault();
            $(this).html('Show Tax Declaration details');
            $('#tax-table').hide();
            $(this).prop('id', 'show-tax-form');
        });

        $("#tax_div").hide();
        $("#flexRadioDefault1, #flexRadioDefault2").on("change", function(){
            if($(this).val() == "1"){
                $("#tax_div").show();

                //   $('#tax_form').submit(function(e){
                    //     // console.log($("#phone").val().length );
                    //     if ($.trim($("#income_property").val()) === "") {
                    //         e.preventDefault();
                    //         $('#income_property_error').html('Income property can not be empty!');
                    //     }else{
                    //         $('#income_property_error').html('');
                    //     }

                        
                    //     if ($.trim($("#other_income").val()) === "") {
                    //         e.preventDefault();
                    //         $('#other_income_error').html('Other income can not be empty!');
                    //     }else{
                    //         $('#other_income_error').html('');
                    //     }


                    //     if ($.trim($("#lic").val()) === "") {
                    //         e.preventDefault();
                    //         $('#lic_error').html('LIC can not be empty!');
                    //     }else{
                    //         $('#lic_error').html('');
                    //     }


                    //     if ($.trim($("#nsc").val()) === "") {
                    //         e.preventDefault();
                    //         $('#nsc_error').html('NSC can not be empty!');
                    //     }else{
                    //         $('#nsc_error').html('');
                    //     } 

                    //     if ($.trim($("#ppf").val()) === "") {
                    //         e.preventDefault();
                    //         $('#ppf_error').html('PPF can not be empty!');
                    //     }else{
                    //         $('#ppf_error').html('');
                    //     }

                    //     if ($.trim($("#ety_d").val()) === "") {
                    //         e.preventDefault();
                    //         $('#ety_d_error').html('80 D can not be empty!');
                    //     }else{
                    //         $('#ety_d_error').html('');
                    //     }  

                    //     if ($.trim($("#ety_dd").val()) === "") {
                    //         e.preventDefault();
                    //         $('#ety_dd_error').html('80 DD can not be empty!');
                    //     }else{
                    //         $('#ety_d_error').html('');
                    //     }     
                //   });
                

            }else{
                $("#tax_div").hide();
            }
            
        })


        $('ul.nav li a').click(function(e) {
            $(this).addClass('step_active').removeClass('step_inactive');
        });

        /*$(document).on('click', '.file-upload-browse', function() {
            var file = $(this).parent().parent().parent().find('.file-upload-default');
            file.trigger('click');
        });*/
        
        $('#income_property_file_path').on('change', function() {
            check_upload_file(this, 'income_property_file_path');
        });

        $('#other_income_file_path').on('change', function() {
            check_upload_file(this, 'other_income_file_path');
        });

        $('#lic_file_path').on('change', function() {
            check_upload_file(this, 'lic_file_path');
        });

        $('#nsc_file_path').on('change', function() {
            check_upload_file(this, 'nsc_file_path');
        });

        $('#ppf_file_path').on('change', function() {
            check_upload_file(this, 'ppf_file_path');
        });

        $('#ety_d_file_path').on('change', function() {
            check_upload_file(this, 'ety_d_file_path');
        });

        $('#ety_dd_file_path').on('change', function() {
            check_upload_file(this, 'ety_dd_file_path');
        });

        // $('.document_img').on('click', function() {
        //     var src = $(this).attr('src');

        //     $('#img-show').attr('src', src);
        //     $('#img_show').modal('show');
        // });
   

    function check_upload_file(ele, id) {
        $(ele).parent().find('.form-control').val($(ele).val().replace(/C:\\fakepath\\/i, ''));

        $("#" + id + "-error").html("");
        
        var val = ele.value;

        if(val.indexOf('.') !== -1) {
            var ext = ele.value.match(/\.(.+)$/)[1];
            var size = ele.files[0].size;

            if(size > 5000000) {
                $("#" + id + "-error").html('File size less than 5MB allowed');
                $("#" + id + "-error").show();
                ele.value = '';
                $(ele).parent().find('.form-control').val('');
            } else {
                switch (ext) {
                    case 'png':
                        $("#" + id + "-error").html('');
                        $("#" + id + "-error").hide();
                        break;
                    case 'jpg':
                        $("#" + id + "-error").html('');
                        $("#" + id + "-error").hide();
                        break;
                    case 'jpeg':
                        $("#" + id + "-error").html('');
                        $("#" + id + "-error").hide();
                        break;
                    /*case 'pdf':
                        $("#" + id + "-error").html('');
                        $("#" + id + "-error").hide();
                        break;*/
                    default:
                        $("#" + id + "-error").html('Please upload only jpg, jpeg, png file');
                        $("#" + id + "-error").show();
                        ele.value = '';
                        $(ele).parent().find('.form-control').val('');
                }
            }
        } else {
            $("#" + id + "-error").html('Invalid file type');
            $("#" + id + "-error").show();
            ele.value = '';

            $(ele).parent().find('.form-control').val('');
        }
    }

 });
</script>


@endsection
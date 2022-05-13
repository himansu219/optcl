<div class="card d-none" id="bank_change_div">
    <div class="card-body">
        <h4 class="card-title">Bank Change</h4>
        <form method="post" action="" autocomplete="off" id="bank_change">
            @csrf
            <input type="hidden" name="bank_change_changed_type_id" id="bank_change_changed_type_id" >
            <div class="row">
            <div class="col-md-4 form-group">
                <label>PPO No.<span class="text-danger">*</span></label>
                <input type="text" class="form-control ppo_number_format" maxlength="12" name="bc_ppo_number" id="bc_ppo_number" >
                <label id="bc_ppo_number-error" class="error text-danger" for="bc_ppo_number"></label>
            </div>
            <div class="col-md-4 form-group">
                <label>Employee No.<span class="text-danger">*</span></label>
                <input type="text" class="form-control only_number" maxlength="6" name="bc_pension_emp_no" id="bc_pension_emp_no" >
                <label id="bc_pension_emp_no-error" class="error text-danger" for="bc_pension_emp_no"></label>
            </div>
            <div class="col-md-4 form-group">
                <label>Pensioner Name<span class="text-danger">*</span></label>
                <input type="text" class="form-control alpha" maxlength="100" name="bc_name_pensioner" id="bc_name_pensioner" >
                <label id="bc_name_pensioner-error" class="error text-danger" for="bc_name_pensioner"></label>
            </div>
            <div class="col-md-4 form-group">
                <label>Savings Bank A/C No.<span class="text-danger">*</span></label>
                <input type="text" class="form-control only_number" maxlength="18" name="bc_savings_bank_ac_no" id="bc_savings_bank_ac_no" >
                <label id="bc_savings_bank_ac_no-error" class="error text-danger" for="bc_savings_bank_ac_no"></label>
            </div>
            <div class="col-md-4 form-group">
                <label>Bank<span class="text-danger">*</span></label>
                <select class="js-example-basic-single" style="width:100%" id="bc_bank_name" name="bc_bank_name">
                    <option value="">Select Bank</option>
                    @foreach($banks as $bank)
                        <option value="{{$bank->id}}">{{$bank->bank_name}}</option>
                    @endforeach
                </select>
                <label id="bc_bank_name-error" class="error text-danger" for="bc_bank_name"></label>
            </div>
            <div class="col-md-4 form-group">
                <label>Branch<span class="text-danger">*</span></label>
                <select class="js-example-basic-single" style="width:100%" id="bc_branch_name_address" name="bc_branch_name_address">
                    <option value="">Select Branch</option>
                </select>
                <label id="bc_branch_name_address-error" class="error text-danger" for="bc_branch_name_address"></label>
            </div>
            <div class="col-md-4 form-group">
                <label>IFSC Code<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="bc_ifsc_code" name="bc_ifsc_code" placeholder=" Enter ifsc code" readonly>
                <label id="bc_ifsc_code-error" class="error text-danger" for="bc_ifsc_code"></label>
            </div>
            <div class="col-md-4 form-group">
                <label>NOC From Previous Bank<span class="text-danger">*</span></label>
                <select class="form-control" style="width:100%" id="bc_noc_previous_bank" name="bc_noc_previous_bank">
                    <option value="">Select Status</option>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
                <label id="bc_noc_previous_bank-error" class="error text-danger" for="bc_noc_previous_bank"></label>
            </div>
            <div class="col-md-4 form-group">
                <label>NOC Document<span class="text-danger">*</span></label>
                <input type="file" name="noc_previous_bank_attachment" id="noc_previous_bank_attachment" class="file-upload-default dob_attachment_path" >
                <div class="input-group col-xs-12">
                    <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                    <div class="input-group-append">
                        <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                    </div>
                </div>
                <label id="bc_noc_previous_bank-error" class="error text-danger" for="bc_noc_previous_bank"></label>
            </div>
            
            </div>
            <div class="row">
            <div class="col-md-4 form-group mt-2">
                <input type="submit" class="btn btn-success" value="Submit">
            </div>
            </div>
        </form>
    </div>
</div>

<div class="card d-none" id="revision_basic_pension_div">
    <div class="card-body">
        <h4 class="card-title">Revision of Basic Pension</h4>
        <form method="post" action="" autocomplete="off" id="revision_basic_pension">
            @csrf
            <input type="hidden" name="revision_basic_pension_changed_type_id" id="revision_basic_pension_changed_type_id" >
            <div class="row">
                <div class="col-md-4 form-group">
                    <label>PPO No.<span class="text-danger">*</span></label>
                    <input type="text" class="form-control ppo_number_format" maxlength="12" name="rbp_ppo_number" id="rbp_ppo_number" >
                    <label id="rbp_ppo_number-error" class="error text-danger" for="rbp_ppo_number"></label>
                </div>
                <div class="col-md-4 form-group">
                    <label>Pension Employee No.<span class="text-danger">*</span></label>
                    <input type="text" class="form-control only_number" maxlength="6" name="rbp_pension_emp_no" id="rbp_pension_emp_no" >
                    <label id="rbp_pension_emp_no-error" class="error text-danger" for="rbp_pension_emp_no"></label>
                </div>
                <div class="col-md-4 form-group">
                    <label>Pensioner Name<span class="text-danger">*</span></label>
                    <input type="text" class="form-control alpha" maxlength="100" name="rbp_name_pensioner" id="rbp_name_pensioner" >
                    <label id="rbp_name_pensioner-error" class="error text-danger" for="rbp_name_pensioner"></label>
                </div>  
                <div class="col-md-4 form-group">
                    <label>Receive Basic Amount<span class="text-danger">*</span></label>
                    <input type="text" class="form-control amount_type" maxlength="8" name="rbp_basic_amt" id="rbp_basic_amt" >
                    <label id="rbp_basic_amt-error" class="error text-danger" for="rbp_basic_amt"></label>
                </div>  
                <div class="col-md-4 form-group">
                    <label>O.O No.<span class="text-danger">*</span></label>
                    <input type="text" class="form-control only_number" maxlength="30" name="rbp_oo_no" id="rbp_oo_no" >
                    <label id="rbp_oo_no-error" class="error text-danger" for="rbp_oo_no"></label>
                </div>     
                <div class="col-md-4 form-group">
                    <label>O.O No. Date<span class="text-danger">*</span></label>
                    <input type="text" class="form-control datepicker-default" name="rbp_oo_no_date" id="rbp_oo_no_date" readonly>
                    <label id="rbp_oo_no_date-error" class="error text-danger" for="rbp_oo_no_date"></label>
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
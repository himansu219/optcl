<div class="card d-none" id="dropped_case_death_case_div">
    <div class="card-body">
        <h4 class="card-title">Dropped Case/Death Case</h4>
        <form method="post" action="" autocomplete="off" id="dropped_case_death_case">
            @csrf
            <input type="hidden" id="dropped_case_death_case_changed_type_id" name="dropped_case_death_case_changed_type_id">
            <div class="row">
            <div class="col-md-4 form-group">
                <label>PPO No.<span class="text-danger">*</span></label>
                <input type="text" class="form-control ppo_number_format" maxlength="12" name="dcdc_ppo_number" id="dcdc_ppo_number" >
                <label id="dcdc_ppo_number-error" class="error text-danger" for="dcdc_ppo_number"></label>
            </div>
            <div class="col-md-4 form-group">
                <label>Pension Employee No.<span class="text-danger">*</span></label>
                <input type="text" class="form-control only_number" maxlength="6" name="dcdc_pension_emp_no" id="dcdc_pension_emp_no" >
                <label id="dcdc_pension_emp_no-error" class="error text-danger" for="dcdc_pension_emp_no"></label>
            </div>
            <div class="col-md-4 form-group">
                <label>Pensioner Name<span class="text-danger">*</span></label>
                <input type="text" class="form-control alpha" maxlength="100" name="dcdc_name_pensioner" id="dcdc_name_pensioner" >
                <label id="dcdc_name_pensioner-error" class="error text-danger" for="dcdc_name_pensioner"></label>
            </div>
            <div class="col-md-4 form-group">
                <label>Date of Death<span class="text-danger">*</span></label>
                <input type="text" class="form-control datepicker-upto-current" name="dcdc_dod" id="dcdc_dod" readonly>
                <label id="dcdc_dod-error" class="error text-danger" for="dcdc_dod"></label>
            </div>
            <div class="col-md-4 form-group">
                <label>Remark<span class="text-danger">*</span></label>
                <textarea name="dcdc_remark" id="dcdc_remark" class="form-control remark_box" maxlength="200"></textarea>
                <label id="dcdc_remark-error" class="error text-danger" for="dcdc_remark"></label>
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
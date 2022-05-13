<div class="card d-none" id="restoration_commutation_div">
    <div class="card-body">
        <h4 class="card-title">Restoration of Commutation</h4>
        <form method="post" action="" autocomplete="off" id="restoration_commutation">
            @csrf
            <input type="hidden" id="restoration_commutation_changed_type_id" name="restoration_commutation_changed_type_id">

            <div class="row">
                <div class="col-md-4 form-group">
                    <label>PPO No.<span class="text-danger">*</span></label>
                    <input type="text" class="form-control ppo_number_format" maxlength="12" name="rc_ppo_number" id="rc_ppo_number" >
                    <label id="rc_ppo_number-error" class="error text-danger" for="rc_ppo_number"></label>
                </div>
                <div class="col-md-4 form-group">
                    <label>Pension Employee No.<span class="text-danger">*</span></label>
                    <input type="text" class="form-control only_number" maxlength="6" name="rc_pension_emp_no" id="rc_pension_emp_no" >
                    <label id="rc_pension_emp_no-error" class="error text-danger" for="rc_pension_emp_no"></label>
                </div>
                <div class="col-md-4 form-group">
                    <label>Pensioner Name<span class="text-danger">*</span></label>
                    <input type="text" class="form-control alpha" maxlength="100" name="rc_name_pensioner" id="rc_name_pensioner" >
                    <label id="rc_name_pensioner-error" class="error text-danger" for="rc_name_pensioner"></label>
                </div>  
                <div class="col-md-4 form-group">
                    <label>Receive Commutation Amount<span class="text-danger">*</span></label>
                    <input type="text" class="form-control amount_type" maxlength="8" name="rc_rcv_comm_amt" id="rc_rcv_comm_amt" >
                    <label id="rc_rcv_comm_amt-error" class="error text-danger" for="rc_rcv_comm_amt"></label>
                </div>  
                <div class="col-md-4 form-group">
                    <label>Date of Restoration<span class="text-danger">*</span></label>
                    <input type="text" class="form-control datepicker-default" name="rc_dor" id="rc_dor" readonly>
                    <label id="rc_dor-error" class="error text-danger" for="rc_dor"></label>
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
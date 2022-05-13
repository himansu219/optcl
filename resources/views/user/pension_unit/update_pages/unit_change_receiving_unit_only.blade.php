<div class="card d-none" id="unit_change_receiving_unit_only_div">
    <div class="card-body">
        <h4 class="card-title">Unit Change for Receiving Unit (Only)</h4>
        <form method="post" action="" autocomplete="off" id="unit_change_receiving_unit_only" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="unit_change_receiving_unit_only_changed_type_id" id="unit_change_receiving_unit_only_changed_type_id" >
            <div class="row">
            <div class="col-md-4 form-group">
                <label>PPO No.<span class="text-danger">*</span></label>
                <input type="text" class="form-control ppo_number_format" maxlength="12" name="ucruo_ppo_number" id="ucruo_ppo_number" >
                <label id="ucruo_ppo_number-error" class="error text-danger" for="ucruo_ppo_number"></label>
            </div>
            <div class="col-md-4 form-group">
                <label>Pension Employee No.<span class="text-danger">*</span></label>
                <input type="text" class="form-control only_number" maxlength="6" name="ucruo_pension_emp_no" id="ucruo_pension_emp_no" >
                <label id="ucruo_pension_emp_no-error" class="error text-danger" for="ucruo_pension_emp_no"></label>
            </div>
            <div class="col-md-4 form-group">
                <label>Pensioner Name<span class="text-danger">*</span></label>
                <input type="text" class="form-control alpha" maxlength="100" name="ucruo_name_pensioner" id="ucruo_name_pensioner" >
                <label id="ucruo_name_pensioner-error" class="error text-danger" for="ucruo_name_pensioner"></label>
            </div>   
            <div class="col-md-4 form-group">
                <label>Name of Prev. Pension Unit<span class="text-danger">*</span></label>
                <select class="js-example-basic-single" style="width:100%" id="urcuo_name_prev_pension_unit" name="urcuo_name_prev_pension_unit">
                    <option value="">Select Name of the Unit</option>
                    @foreach($pension_units as $unitData)
                        <option value="{{$unitData->id}}">{{$unitData->pension_unit_name}}</option>
                    @endforeach
                </select>
                <label id="urcuo_name_prev_pension_unit-error" class="error text-danger" for="urcuo_name_prev_pension_unit"></label>
            </div>   
            <div class="col-md-4 form-group">
                <label>Name of New Pension Unit<span class="text-danger">*</span></label>
                <select class="js-example-basic-single" style="width:100%" id="urcuo_name_new_pension_unit" name="urcuo_name_new_pension_unit">
                    <option value="">Select Name of the Unit</option>
                    @foreach($pension_units as $unitData)
                        <option value="{{$unitData->id}}">{{$unitData->pension_unit_name}}</option>
                    @endforeach
                </select>
                <label id="urcuo_name_new_pension_unit-error" class="error text-danger" for="urcuo_name_new_pension_unit"></label>
            </div>  
            <div class="col-md-4 form-group">
                <label>Letter No. for Above Changes<span class="text-danger">*</span></label>
                <input type="file" name="ucruo_letter_no_above_changes" id="ucruo_letter_no_above_changes" class="file-upload-default dob_attachment_path" >
                <div class="input-group col-xs-12">
                    <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                    <div class="input-group-append">
                        <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                    </div>
                </div>
                <label id="ucruo_letter_no_above_changes-error" class="error text-danger" for="ucruo_letter_no_above_changes"></label>
            </div> 
            <div class="col-md-4 form-group">
                <label>Date for above Changes<span class="text-danger">*</span></label>
                <input type="text" class="form-control datepicker-default" name="ucruo_date_for_above_changes" id="ucruo_date_for_above_changes" readonly>
                <label id="ucruo_date_for_above_changes-error" class="error text-danger" for="ucruo_date_for_above_changes"></label>
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
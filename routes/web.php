<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginContoller;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PensionerProposalController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DealingAssistantController;
use App\Http\Controllers\DdoController;
use App\Http\Controllers\UnitHeadController;
use App\Http\Controllers\PensionerBenefitController;
use App\Http\Controllers\HRDealingAssistantController;
use App\Http\Controllers\HRSanctionAuthorityController;
use App\Http\Controllers\HRExecutiveController;
use App\Http\Controllers\InitiatorController;
use App\Http\Controllers\VerifierController;
use App\Http\Controllers\ApproverController;
use App\Http\Controllers\FPInitiatorController;
use App\Http\Controllers\FPVerifierController;
use App\Http\Controllers\FPApproverController;

use App\Http\Controllers\PensionerLoginController;

//admin controller
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\PensionUnitController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\ReligionController;

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\CountryMasterController;
use App\Http\Controllers\StateMasterController;
use App\Http\Controllers\DistrictMasterController;
use App\Http\Controllers\BankNameMasterController;
use App\Http\Controllers\BankBranchNameMasterController;
use App\Http\Controllers\RelationMasterController;
use App\Http\Controllers\NomineePreferenceController;
use App\Http\Controllers\GenderMasterController;
use App\Http\Controllers\UserDesignationController;
use App\Http\Controllers\Form16MasterController;
use App\Http\Controllers\DaMasterController;
use App\Http\Controllers\TiMasterController;
use App\Http\Controllers\EmployeeMasterController;
use App\Http\Controllers\CalculationRuleMasterController;
use App\Http\Controllers\CommutationMasterController;
use App\Http\Controllers\NomineeRegistrationController;
use App\Http\Controllers\TaxDeclarationController;
use App\Http\Controllers\PensionerRecordUpdateController;
use App\Http\Controllers\NomineeLoginController;
use App\Http\Controllers\NomineeProposalController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\DaAddApplicantController;
use App\Http\Controllers\ArrearsController;

use App\Libraries\Util;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('calculate-tds', function () {
  Util::calculateTDS();
});


// User Area
Route::get('/',[LoginContoller::class, 'index'])->name('login_form');
Route::post('login-submit', [LoginContoller::class, 'login_submit'])->name('validate_user');

// Service pensioner forgot password
Route::get('user/forgot-password',[LoginContoller::class,'ForgotPassword'])->name('forgot_service_pensioner_password');
Route::post('user/forgot-password/verify',[LoginContoller::class,'VerifyMobileAadhaar'])->name('verify_mobile_aadhaar_no');
Route::get('user/verify-otp',[LoginContoller::class,'VerifyOtpPage'])->name('verify_otp_page');
// Service pensioner verify otp
Route::post('user/forgot-password/otp-verify', [LoginContoller::class,'VerifyOtp'])->name('pensioner_verify_otp');
// Service pensioner resend otp
Route::post('user/forgot-password/resend_otp',[LoginContoller::class,'ReSendOtp'])->name('pensioner_resend_otp');

// Admin forgot password
Route::get('admin/forgot-password',[AdminLoginController::class,'ForgotPassword'])->name('admin_forgot_password');
Route::get('set_password/{id}',[AdminLoginController::class,'SetPassword'])->name('set_password');
Route::post('verify_set_password',[AdminLoginController::class,'SetPasswordVerify'])->name('set_password_verify');
Route::post('admin/forgot-password/verify',[AdminLoginController::class,'VerifyMobileAadhaar'])->name('admin_verify_mobile_aadhaar_no');
Route::get('admin/set/forgot-password',[AdminLoginController::class,'setForgotPassword'])->name('set_forgot_password');
// Admin verify otp
Route::post('admin/forgot-password/otp-verify', [AdminLoginController::class,'VerifyOtp'])->name('admin_verify_otp');
// Admin resend otp
Route::post('admin/forgot-password/resend_otp',[AdminLoginController::class,'ReSendOtp'])->name('admin_resend_otp');

// user change password
Route::get('user/change-password', [ChangePasswordController::class,'indexUsers'])->name('user_change_password');
Route::post('user/change_password', [ChangePasswordController::class,'changePasswordUser'])->name('update_user_change_password');
//admin change password
Route::get('change-password', [ChangePasswordController::class,'index'])->name('change_password');
Route::post('change_password', [ChangePasswordController::class,'changePassword'])->name('update_change_password');
// nominee login
Route::post('nominee-login-submit', [NomineeLoginController::class, 'login_submit'])->name('validate_nominee');
 // nominee application form 1
Route::get('nominee/application-form',[NomineeProposalController::class, 'nominee_application_form'])->name('nominee_application_form');
 // nominee application form 2
 Route::get('nominee/family-pensioner-form',[NomineeProposalController::class, 'nominee_family_pensioner_form'])->name('nominee_family_pensioner_form');
  // nominee application form 3
  Route::get('nominee/nominee-form',[NomineeProposalController::class, 'NomineeForm'])->name('nominee_nominee_form');
  Route::post('nominee/add-nominee',[NomineeProposalController::class, 'add_new_nominee_data'])->name('add_new_nominee_data');
// nominee application form 1 submit
Route::post('nominee/submit/nominee-form',[NomineeProposalController::class, 'save_nominee_form'])->name('save_nominee_form');
// nominee application form 2 submit
Route::post('nominee/submit/nominee-family-pensioner-form',[NomineeProposalController::class, 'save_nominee_family_pensioner_form'])->name('save_nominee_family_pensioner_form');
Route::get('nominee/check/employee',[NomineeProposalController::class, 'check_employee'])->name('nominee_check_employee');
// nominee application form 3 submit
Route::post('nominee/submit/nominee-details', [NomineeProposalController::class, 'save_nominee_details'])->name('save_nominee_nominee_details');
Route::post('nominee-get-bank-branch', [NomineeProposalController::class, 'get_bank_branch'])->name('nominee_get_bank_branch');
Route::post('nominee-get-details-branch', [NomineeProposalController::class, 'getBranchDetails'])->name('nominee_get_details_branch');
Route::post('delete-nominee', [NomineeProposalController::class, 'delete_nominee_details'])->name('delete_nominee_nominee_details');
// Nominee Pension Document
Route::get('nominee-pension-document', [NomineeProposalController::class, 'pension_documents'])->name('nominee_pension_documents_page');
Route::post('submit/nominee-documents', [NomineeProposalController::class, 'save_pension_documents'])->name('save_nominee_documents_details');
Route::get('application/nominee-preview',[NomineeProposalController::class, 'application_preview'])->name('nominee_application_preview');
Route::post('user/submit/nominee/application',[NomineeProposalController::class, 'application_submit'])->name('nominee_application_submit');
Route::get('application/nominee-applcation-details',[NomineeProposalController::class, 'application_details'])->name('nominee_application_view_details');

// Nominee application submission area - Dealing Assistant
Route::get('application/dealing-assistant/family-pension','App\Http\Controllers\FPDealingAssistantController@applications')->name('family_pension_da_applications');
Route::get('application/family-pension/application-details/{id}','App\Http\Controllers\FPDealingAssistantController@application_details')->name('family_pension_application_details');
Route::post('nominee/dealing-assistant/application-approval','App\Http\Controllers\FPDealingAssistantController@applications_approval')->name('nominee_dealing_assistant_approval');
Route::post('nominee/dealing-assistant/application-submission','App\Http\Controllers\FPDealingAssistantController@applications_submission')->name('nominee_dealing_assistant_submission');
Route::post('nominee/dealing-assistant/application-add-recovery','App\Http\Controllers\FPDealingAssistantController@applications_store_recovery')->name('nominee_dealing_assistant_recovery_submission');
Route::post('nominee/dealing-assistant/application-form-ii','App\Http\Controllers\FPDealingAssistantController@service_pension_form_submission')->name('nominee_dealing_assistant_form_ii');
// Nominee application submission area - Finance Executive
Route::get('application/finance-executive/family-pension','App\Http\Controllers\FPFinanceExecutiveController@applications')->name('family_pension_fe_applications');
Route::get('application/finance-executive/family-pension/application-details/{id}','App\Http\Controllers\FPFinanceExecutiveController@application_details')->name('family_pension_fe_application_details');
Route::post('nominee/finance-executive/application-approval','App\Http\Controllers\FPFinanceExecutiveController@applications_approval')->name('nominee_finance_executive_approval');
Route::post('nominee/finance-executive/application-submission','App\Http\Controllers\FPFinanceExecutiveController@applications_submission')->name('nominee_finance_executive_submission');
Route::post('nominee/finance-executive/application-add-recovery','App\Http\Controllers\FPFinanceExecutiveController@applications_store_recovery')->name('nominee_finance_executive_recovery_submission');
Route::post('nominee/finance-executive/application-form-ii','App\Http\Controllers\FPFinanceExecutiveController@service_pension_form_submission')->name('nominee_finance_executive_form_ii');
// Nominee application submission area - Unit Head
Route::get('application/unit-head/family-pension','App\Http\Controllers\FPUnitHeadController@applications')->name('family_pension_unit_head_applications');
Route::get('application/unit-head/family-pension/application-details/{id}','App\Http\Controllers\FPUnitHeadController@application_details')->name('family_pension_unit_head_application_details');
Route::post('nominee/unit-head/application-submission','App\Http\Controllers\FPUnitHeadController@applications_submission')->name('nominee_unit_head_submission');
Route::post('nominee/unit-head/application-add-recovery','App\Http\Controllers\FPUnitHeadController@applications_store_recovery')->name('nominee_unit_head_recovery_submission');
Route::post('nominee/unit-head/application-form-ii','App\Http\Controllers\FPUnitHeadController@service_pension_form_submission')->name('nominee_unit_head_form_ii');
// ----------------------------------------------------------------
// Nominee application submission area - Sanction Authority(HR Wings)
Route::get('application/hr-wings/sanctioning-authority/family-pension/application-details/{id}','App\Http\Controllers\FPHRSanctionAuthorityController@fp_application_details')->name('family_pension_hr_sanctioning_authority');
// Family Pension Application Assisgnment Details
Route::get('application/hr-wings/sanctioning-authority/family-pension/assignment-application-details/{id}','App\Http\Controllers\FPHRSanctionAuthorityController@fp_assignment_application_details')->name('family_pension_hr_sanctioning_authority_assignment_app_details');
// ------------------------------------------------------------------
// Nominee application submission area - Dealing Assistant(HR Wings)
Route::get('application/hr-wings/dealing-assistant/family-pension/application-details/{id}','App\Http\Controllers\FPHRDealingAssistantController@fp_application_details')->name('family_pension_hr_dealing_assistant');
Route::post('application/hr-wings/dealing-assistant/family-pension/submission', 'App\Http\Controllers\FPHRDealingAssistantController@fp_applications_submission')->name('family_pension_hr_dealing_assistant_submission');
Route::post('application/hr-wings/dealing-assistant/family-pension/application-approval','App\Http\Controllers\FPHRDealingAssistantController@fp_applications_approval')->name('family_pension_hr_dealing_assistant_approval');
Route::post('application/hr-wings/dealing-assistant/family-pension/update-recovery','App\Http\Controllers\FPHRDealingAssistantController@fp_applications_store_recovery')->name('family_pension_hr_dealing_assistant_update_recovery');
Route::post('application/hr-wings/dealing-assistant/family-pension/update-part-ii','App\Http\Controllers\FPHRDealingAssistantController@fp_service_pension_form_submission')->name('family_pension_hr_dealing_assistant_update_part_ii');
Route::post('application/hr-wings/dealing-assistant/family-pension/update-part-iii','App\Http\Controllers\FPHRDealingAssistantController@fp_service_pension_form_three_submission')->name('family_pension_hr_dealing_assistant_update_part_iii');
// -----------------------------------------------------------
// Calculation sheet generation - Family Pensioner
Route::get('application/hr-wings/dealing-assistant/family-pension/calculate-pensionar-benefits/{id}', 'App\Http\Controllers\FPPensionerBenefitController@calculate_pensionar_benefits')->name('fp_calculate_pensionar_benefits');
Route::post('application/hr-wings/dealing-assistant/family-pension/calculate-rules', 'App\Http\Controllers\FPPensionerBenefitController@calculate_rules')->name('fp_calculate_rules');
Route::post('application/hr-wings/dealing-assistant/family-pension/calculate-service-pension-save', 'App\Http\Controllers\FPPensionerBenefitController@calculate_service_pension_save')->name('fp_calculate_service_pension_save');
Route::post('application/hr-wings/dealing-assistant/family-pension/calculate-dcr-gratuity-save', 'App\Http\Controllers\FPPensionerBenefitController@calculate_dcr_gratuity_save')->name('fp_calculate_dcr_gratuity_save');

// Family pensioner calculation
Route::post('application/hr-wings/dealing-assistant/family-pension/calculate-family-pensioner', 'App\Http\Controllers\FPPensionerBenefitController@get_family_pension_details')->name('fp_get_family_pension_details');
Route::post('application/hr-wings/dealing-assistant/family-pension/save-family-pensioner', 'App\Http\Controllers\FPPensionerBenefitController@save_transaction_details')->name('fp_save_transaction_details');
// Commutation Rule One
Route::post('application/hr-wings/dealing-assistant/family-pension/calculate-commutation-pension', 'App\Http\Controllers\FPPensionerBenefitController@get_commutation_rule_one')->name('fp_get_commutation_rule_one');
// Commutation Rule Two
Route::post('application/hr-wings/dealing-assistant/family-pension/calculate-commutation-pension-worked-out', 'App\Http\Controllers\FPPensionerBenefitController@get_commutation_rule_two')->name('fp_get_commutation_rule_two');
// Commutation Rule Three
Route::post('application/hr-wings/dealing-assistant/family-pension/calculate-commutation-reduced-pension', 'App\Http\Controllers\FPPensionerBenefitController@get_commutation_rule_three')->name('fp_get_commutation_rule_three');
Route::post('application/hr-wings/dealing-assistant/family-pension/calculation-sheet-submitted', 'App\Http\Controllers\FPPensionerBenefitController@calculation_sheet_submitted')->name('fp_calculation_sheet_submitted');
//--------------------------------------------------------------------------------
// HR Executive - Family Pensioner
Route::get('application/hr-wings/hr-executive/family-pension/application-details/{id}', 'App\Http\Controllers\FPHRExecutiveController@application_details')->name('fp_hr_executive_application_details');
Route::post('application/hr-wings/hr-executive/family-pension/application-approval', 'App\Http\Controllers\FPHRExecutiveController@applications_approval')->name('fp_hr_executive_field_approval');
Route::post('application/hr-wings/hr-executive/family-pension/submission', 'App\Http\Controllers\FPHRExecutiveController@applications_submission')->name('fp_hr_executive_applications_submission');
Route::post('application/hr-wings/hr-executive/family-pension/store-recovery', 'App\Http\Controllers\FPHRExecutiveController@applications_store_recovery')->name('fp_hr_executive_applications_store_recovery');
Route::post('application/hr-wings/hr-executive/family-pension/service-pension-form-submission', 'App\Http\Controllers\FPHRExecutiveController@service_pension_form_submission')->name('fp_hr_executive_service_pension_form_submission');
Route::post('application/hr-wings/hr-executive/family-pension/service-pension-form-three-submission', 'App\Http\Controllers\FPHRExecutiveController@service_pension_form_three_submission')->name('fp_hr_executive_service_pension_form_part_three_submission');
// ----------------------------------------------------------------------------
// Sanction Authority - Family Pensioner
Route::post('application/hr-wings/sanctioning-authority/family-pension/submission', 'App\Http\Controllers\FPHRSanctionAuthorityController@submit_application_approval')->name('fp_sanction_authority_application_submission');
Route::get('application/hr-wings/sanctioning-authority/family-pension/applications/sanction-order-generate/{id}', 'App\Http\Controllers\FPHRSanctionAuthorityController@sanction_order_generate')->name('fp_sanction_order_generate');
Route::post('application/hr-wings/sanctioning-authority/family-pension/applications/sanction-order-submit', 'App\Http\Controllers\FPHRSanctionAuthorityController@sanction_order_submit')->name('fp_sanction_order_submit');
Route::get('application/hr-wings/sanctioning-authority/family-pension/applications/gratuity-sanction-order-generate/{id}', 'App\Http\Controllers\FPHRSanctionAuthorityController@gratuity_sanction_order_generate')->name('fp_gratuity_sanction_order_generate');
Route::post('application/hr-wings/sanctioning-authority/family-pension/applications/gratuity-sanction-order-submit', 'App\Http\Controllers\FPHRSanctionAuthorityController@gratuity_sanction_order_submit')->name('fp_gratuity_sanction_order_submit');
Route::get('application/hr-wings/sanctioning-authority/family-pension/applications/forward-initiator/{id}', 'App\Http\Controllers\FPHRSanctionAuthorityController@application_forward_initiator')->name('fp_application_forward_initiator');
Route::post('application/hr-wings/sanctioning-authority/family-pension/applications/resubmit', 'App\Http\Controllers\FPHRSanctionAuthorityController@application_resubmission')->name('fp_hr_sanction_authority_application_resubmission');
// ---------------------------------------------------------------------
// Dealing Assistant Resubmission - Family Pensioner 
Route::post('application/hr-wings/dealing-assistant/family-pension/resubmit', 'App\Http\Controllers\FPHRDealingAssistantController@application_resubmission')->name('fp_hr_dealing_assistant_application_resubmission');
// ---------------------------------------------------------------------
// Nominee Resubmission - Family Pensioner
Route::get('application/family-pension/resubmit','App\Http\Controllers\FPResubmitPensionController@edit_pensioner_form')->name('family_pensioner_form_page');
Route::post('application/family-pension/resubmission','App\Http\Controllers\FPResubmitPensionController@update_pensioner_form')->name('family_pensioner_form_page_resubmission');
Route::get('application/family-pension/personal-details-resubmit','App\Http\Controllers\FPResubmitPensionController@edit_personal_details')->name('family_pensioner_personal_resubmit_page');
Route::post('application/family-pension/personal-details/resubmission','App\Http\Controllers\FPResubmitPensionController@update_personal_details')->name('family_pensioner_update_persional_details_resubmission');
Route::get('application/family-pension/nominee-details/resubmit','App\Http\Controllers\FPResubmitPensionController@nominee_form')->name('family_pensioner_nominee_form_resubmit');
Route::post('application/family-pension/nominee-details/resubmission', 'App\Http\Controllers\FPResubmitPensionController@save_nominee_details')->name('family_pensioner_nominee_details_resubmission');
Route::get('application/family-pension/nominee-details/pension-documents/resubmit', 'App\Http\Controllers\FPResubmitPensionController@pension_documents')->name('family_pensioner_nominee_pension_documents');
Route::post('application/family-pension/nominee-details/pension-documents/resubmission', 'App\Http\Controllers\FPResubmitPensionController@save_pension_documents')->name('family_pensioner_nominee_pension_documents_resubmission');
//Route::post('dealing-assistant/applications/resubmission', [DealingAssistantController::class, 'applications_resubmission'])->name('dealing_assistant_applications_resubmission');


// nominee application form 1 edit
Route::get('nominee/edit-nominee-application-form',[NomineeProposalController::class, 'edit_nominee_application_form'])->name('edit_nominee_application_form');
// nominee application form 2 edit or family pensioner form
Route::get('nominee/edit-nominee-family-pensioner-form',[NomineeProposalController::class, 'edit_nominee_family_pensioner_form'])->name('edit_nominee_family_pensioner_form');
// nominee application form 1 save as draft
Route::post('nominee/save-as-draft/nominee-form',[NomineeProposalController::class, 'save_as_draft_nominee_form'])->name('save_as_draft_nominee_form');
// nominee application form 2 or family pensioner form save as draft
Route::post('nominee/save-as-draft/nominee-family-pensioner-form',[NomineeProposalController::class, 'save_as_draft_nominee_family_pensioner_form'])->name('save_as_draft_nominee_family_pensioner_form');
// nominee application form 1 update
Route::post('nominee/update/nominee-form',[NomineeProposalController::class, 'update_nominee_form'])->name('update_nominee_form');
// nominee application form 2 update or family pensioner form
Route::post('nominee/update/nominee-family-pensioner-form',[NomineeProposalController::class, 'update_nominee_family_pensioner_form'])->name('update_nominee_family_pensioner_form');
Route::post('state-nominee-family-pensioner-form', [NomineeProposalController::class, 'get_state'])->name('get_state_nominee_family_pensioner_form');
Route::post('district-nominee-family-pensioner-form', [NomineeProposalController::class, 'get_district'])->name('get_district_nominee_family_pensioner_form');
Route::post('nominee-get-branch',[NomineeProposalController::class, 'get_branch'])->name('nominee_get_branch');
Route::post('nominee-get-branch-details',[NomineeProposalController::class, 'get_branch_details'])->name('nominee_get_branch_details');

// nominee registration 
Route::get('nominee/registration',[NomineeRegistrationController::class,'index'])->name('nominee_registration');
Route::post('nominee_registration_submit',[NomineeRegistrationController::class,'RegistrationFormSubmit'])->name('nominee_registration_form_submit');

Route::get('nominee/registration/verify-otp',[NomineeRegistrationController::class,'registrationVerifyOTP'])->name('nominee_registration_verify_otp');
Route::post('nominee/registration/otp-submission',[NomineeRegistrationController::class,'verifySubmitNomineeRegistration'])->name('nominee_registration_otp_submission');
// nominee login
Route::get('nominee',[NomineeRegistrationController::class,'NomineeLogin'])->name('nominee_login');
// nominee forgot password
Route::get('nominee/forgot-password',[NomineeRegistrationController::class,'ForgotPassword'])->name('forgot_nominee_password');
// nominee verify mobile no
Route::post('nominee/forgot-password/verify',[NomineeRegistrationController::class,'VerifyMobileAadhaar'])->name('verify_nominee_mobile_aadhaar_no');
Route::get('nominee/verify-otp',[NomineeRegistrationController::class,'verify_otp'])->name('verify_otp');
// nominee otp verify
//Route::get('nominee/otp/{id}', [NomineeRegistrationController::class,'OtpIndex'])->name('nominee_otp');
Route::post('nominee/forgot-password/otp_verify', [NomineeRegistrationController::class,'VerifyOtp'])->name('nominee_verify_otp');
// nominee resend otp
Route::post('nominee/forgot-password/resend_otp',[NomineeRegistrationController::class,'ReSendOtp'])->name('nominee_resend_otp');

// Pensioner Record Update index page
Route::get('user/update-pension-record',[PensionerRecordUpdateController::class,'Index'])->name('update_pension_record');
// Add Pensioner Record Update form
Route::get('user/update-pension-record/add',[PensionerRecordUpdateController::class,'AddUpdatePensionRecordForm'])->name('update_pension_record_add');
 // View Pensioner Record Update form
 Route::get('user/update-pension-record/view/{id}', [PensionerRecordUpdateController::class, 'viewUpdatePensionRecord'])->name('update_pension_record_view');
// Edit Pensioner Record Update form
Route::get('user/update-pension-record/edit/{id}',[PensionerRecordUpdateController::class,'EditUpdatePensionRecord'])->name('update_pension_record_edit');
 // get bank branch data in pensioner update record form
 Route::post('getbranchdata', [PensionerRecordUpdateController::class,'getbankBranch'])->name('bank_branch_data');
 // get ifsc code and micr code according to branch name in pensioner update form
 Route::post('getifscmicrcode', [PensionerRecordUpdateController::class, 'getIfscMicr'])->name('get_ifsc_micr');
 // pensioner record update
 Route::post('update_pension_record_data', [PensionerRecordUpdateController::class, 'UpdatePensionRecordFormSubmit'])->name('update_pension_record_form');
 // pensioner record update data updated
 Route::post('update_pension_record_data_update', [PensionerRecordUpdateController::class, 'UpdatePensionRecordData'])->name('update_pension_record_data');
 //  Pension Unit Update Pension Record 
Route::any('pension-unit/update-pension-record', [PensionerRecordUpdateController::class, 'PensionUnitUpdatePensionRecord'])->name('pension_unit_update_pension_record');




/* ------ Update Pension Record ------ */
Route::get('pension-unit/update-pension-record/update', [PensionerRecordUpdateController::class, 'update_record'])->name('pension_unit_update_record');
/*  ------ Additional Family Pensioner after Death of SP/FP -------- */
// Listing
Route::any('pension-unit/update-pension-record/additional-family-pensioner/list', [PensionerRecordUpdateController::class, 'update_record_listing'])->name('pension_unit_additional_family_pensioner');
// Form Page
Route::get('pension-unit/update-pension-record/additional-family-pensioner/add', [PensionerRecordUpdateController::class, 'update_record_additional'])->name('update_record_update_record_additional');
// Pensioner Details
Route::post('pension-unit/update-pension-record/additional-family-pensioner/pensioner-details', [PensionerRecordUpdateController::class, 'additional_pensioner_new_pensioner_pensioner_details'])->name('pension_unit_additional_pensioner_new_pensioner_pensioner_details');
// Form Submission
Route::post('pension-unit/update-pension-record/additional-family-pensioner', [PensionerRecordUpdateController::class, 'update_record_submission'])->name('pension_unit_update_record_submission');
// Edit Page
Route::get('pension-unit/update-pension-record/additional-family-pensioner-edit/{crID}', [PensionerRecordUpdateController::class, 'update_record_additional_new_pensioner_edit'])->name('update_record_additional_new_pensioner_edit');
// Edit page submission
Route::post('pension-unit/update-pension-record/additional-family-pensioner-edit', [PensionerRecordUpdateController::class, 'pension_unit_update_record_edit_submission'])->name('pension_unit_update_record_edit_submission');
// View Details
Route::get('pension-unit/update-pension-record/additional-family-pensioner-view/{crID}', [PensionerRecordUpdateController::class, 'update_record_additional_new_pensioner_view'])->name('update_record_additional_new_pensioner_view');

// - View Details
Route::get('pension-unit/update-pension-record/view/{id}', [PensionerRecordUpdateController::class, 'viewPensionUnitUpdatePensionRecord'])->name('pension_unit_update_pension_record_view');
/* ------ Revision of Basic Pension ---------- */
// Listing
Route::any('pension-unit/update-pension-record/revision-basic-pension/list', [PensionerRecordUpdateController::class, 'revision_basic_pension_listing'])->name('pension_unit_revision_basic_pension');
// Form Page
Route::get('pension-unit/update-pension-record/revision-basic-pension/add', [PensionerRecordUpdateController::class, 'revision_basic_pension_form_page'])->name('revision_basic_pension_form_page');
// Form Submission
Route::post('pension-unit/update-pension-record/revision-basic-pension-submission', [PensionerRecordUpdateController::class, 'revision_basic_pension_submission'])->name('pension_unit_revision_basic_pension_submission');
// Edit Page
Route::get('pension-unit/update-pension-record/revision-basic-pension/edit/{id}', [PensionerRecordUpdateController::class, 'revision_basic_pension_edit_page'])->name('pension_unit_revision_basic_pension_edit_page');
// Edit Form Submission
Route::post('pension-unit/update-pension-record/revision-basic-pension/edit-form/submission', [PensionerRecordUpdateController::class, 'revision_basic_pension_edit_submission'])->name('pension_unit_revision_basic_pension_edit_submission');
// - View Details
Route::get('pension-unit/update-pension-record/revision-basic-pension/view/{id}', [PensionerRecordUpdateController::class, 'revision_basic_pension_view_page'])->name('pension_unit_revision_basic_pension_view');
// Pensioner Details
Route::post('pension-unit/update-pension-record/revision-basic-pension/pensioner-details', [PensionerRecordUpdateController::class, 'pensioner_details'])->name('revision_basic_pension_pensioner_details');
// Taxable Amount Update Redirection
Route::get('pension-unit/update-pension-record/revision-basic-pension/taxable-amount-update/{id}', [PensionerRecordUpdateController::class, 'revision_taxable_amount_calculation_page'])->name('pension_unit_revision_taxable_amount_calculation_page');
/* ------ Additional Pension ---------- */
// Listing
Route::any('pension-unit/update-pension-record/additional-pension/list', [PensionerRecordUpdateController::class, 'additional_pension_listing'])->name('pension_unit_additional_pension_listing');
// Form Page
Route::get('pension-unit/update-pension-record/additional-pension/add', [PensionerRecordUpdateController::class, 'additional_pension_add'])->name('pension_unit_update_additional_pension_add_page');
// Get Pensioner Details
Route::post('pension-unit/update-pension-record/additional-pension/pensioner-details', [PensionerRecordUpdateController::class, 'additional_pension_pensioner_details'])->name('pension_unit_update_additional_pension_pensioner_details');
// Form Submission
Route::post('pension-unit/update-pension-record/additional-pension-submission', [PensionerRecordUpdateController::class, 'additional_pension_submission'])->name('pension_unit_additional_pension_submission');
// Edit Page
Route::get('pension-unit/update-pension-record/additional-pension/{id}', [PensionerRecordUpdateController::class, 'update_additional_pension_edit_page'])->name('pension_unit_update_additional_pension_page');
// Edit Page Submission
Route::post('pension-unit/update-pension-record/additional-pension/edit/submission', [PensionerRecordUpdateController::class, 'additional_pension_edit_submission'])->name('pension_unit_update_additional_pension_page_submission');
// View Details
Route::get('pension-unit/update-pension-record/additional-pension/view/{id}', [PensionerRecordUpdateController::class, 'additional_pension_view_page'])->name('pension_unit_additional_pension_page_view');
/* ------ Bank Change  ------*/
// Listing
Route::any('pension-unit/update-pension-record/bank-change/list', [PensionerRecordUpdateController::class, 'bank_change_list'])->name('pension_unit_bank_change_list');
// Form Page
Route::get('pension-unit/update-pension-record/bank-change/add', [PensionerRecordUpdateController::class, 'bank_change_add'])->name('pension_unit_bank_change_add');
// Pensioner Details
Route::post('pension-unit/update-pension-record/bank-change/pensioner-details', [PensionerRecordUpdateController::class, 'bank_change_pensioner_details'])->name('pension_unit_bank_change_pensioner_details');
// Form Submission
Route::post('pension-unit/update-pension-record/bank-change-submission', [PensionerRecordUpdateController::class, 'bank_change_submission'])->name('pension_unit_bank_change_submission');
// Edit Page
Route::get('pension-unit/update-pension-record/bank-change/edit/{id}', [PensionerRecordUpdateController::class, 'bank_change_edit_page'])->name('pension_unit_bank_change_edit_page');
// Edit form submission
Route::post('pension-unit/update-pension-record/bank-change/edit/submission', [PensionerRecordUpdateController::class, 'bank_change_edit_submission'])->name('pension_unit_bank_change_edit_submission');
// View Details
Route::get('pension-unit/update-pension-record/bank-change/view/{id}', [PensionerRecordUpdateController::class, 'bank_change_view_page'])->name('pension_unit_bank_change_view');
/* ------ Unit Change for Receiving Unit (Only)  ------*/
// Listing
Route::any('pension-unit/update-pension-record/unit-change-receiving-unit-only/list', [PensionerRecordUpdateController::class, 'unit_change_receiving_unit_only_list'])->name('pension_unit_unit_change_receiving_unit_only_list');
// Form Page
Route::get('pension-unit/update-pension-record/unit-change-receiving-unit-only/add', [PensionerRecordUpdateController::class, 'unit_change_receiving_unit_only_add'])->name('pension_unit_unit_change_receiving_unit_only_add');
// Pensioner Details
Route::post('pension-unit/update-pension-record/unit-change-receiving-unit-only/pensioner-details', [PensionerRecordUpdateController::class, 'unit_change_receiving_unit_only_pensioner_details'])->name('pension_unit_unit_change_receiving_unit_only_pensioner_details');
// Form Submission
Route::post('pension-unit/update-pension-record/unit-change-receiving-unit-only/submission', [PensionerRecordUpdateController::class, 'unit_change_receiving_unit_only_submission'])->name('pension_unit_unit_change_receiving_unit_only_submission');
// Edit Page
Route::get('pension-unit/update-pension-record/unit-change-receiving-unit-only/edit/{id}', [PensionerRecordUpdateController::class, 'unit_change_receiving_unit_only_edit_page'])->name('unit_change_receiving_unit_only_edit_page');
// Edit Form Submission
Route::post('pension-unit/update-pension-record/unit-change-receiving-unit-only/edit/submission', [PensionerRecordUpdateController::class, 'unit_change_receiving_unit_only_edit_submission'])->name('pension_unit_unit_change_receiving_unit_only_edit_submission');
// View Page
Route::get('pension-unit/update-pension-record/unit-change-receiving-unit-only/view/{id}', [PensionerRecordUpdateController::class, 'unit_change_receiving_unit_only_view_page'])->name('unit_change_receiving_unit_only_view_page');
/* ------ Dropped Case/ Death Case  ------*/
// Listing
Route::any('pension-unit/update-pension-record/udropped-case-death-case/list', [PensionerRecordUpdateController::class, 'dropped_case_death_case_list'])->name('pension_unit_dropped_case_death_case_list');
// Form Page
Route::get('pension-unit/update-pension-record/udropped-case-death-case/add', [PensionerRecordUpdateController::class, 'dropped_case_death_case_add'])->name('pension_unit_dropped_case_death_case_add');
// Pensioner Details
Route::post('pension-unit/update-pension-record/dropped-case-death-case/pensioner-details', [PensionerRecordUpdateController::class, 'dropped_case_death_case_pensioner_details'])->name('pension_unit_dropped_case_death_case_pensioner_details');
// Form Submission
Route::post('pension-unit/update-pension-record/dropped-case-death-case/submission', [PensionerRecordUpdateController::class, 'dropped_case_death_case_submission'])->name('pension_unit_dropped_case_death_case_submission');
// Edit Page
Route::get('pension-unit/update-pension-record/dropped-case-death-case/edit/{id}', [PensionerRecordUpdateController::class, 'dropped_case_death_case_edit_page'])->name('pension_unit_dropped_case_death_case_page');
// Edit Form submission
Route::post('pension-unit/update-pension-record/dropped-case-death-case/edit/submission', [PensionerRecordUpdateController::class, 'dropped_case_death_case_edit_submission'])->name('pension_unit_dropped_case_death_case_edit_submission');
// View Details
Route::get('pension-unit/update-pension-record/dropped-case-death-case/view/{id}', [PensionerRecordUpdateController::class, 'dropped_case_death_case_view_page'])->name('pension_unit_dropped_case_death_case_view');
/* ------ Restoration of Commutation  ------*/
// Listing
Route::any('pension-unit/update-pension-record/restoration-commutation/list', [PensionerRecordUpdateController::class, 'restoration_commutation_listing'])->name('pension_unit_restoration_commutation');
// Form Page
Route::get('pension-unit/update-pension-record/restoration-commutation/add', [PensionerRecordUpdateController::class, 'restoration_commutation_add'])->name('pension_unit_restoration_commutation_add');
// Pensioner and Commutation Details
Route::post('pension-unit/update-pension-record/restoration-commutation/add', [PensionerRecordUpdateController::class, 'restoration_commutation_pensioner_commutation_details'])->name('pension_unit_restoration_commutation_pensioner_commutation_details');
// Form Submission
Route::post('pension-unit/update-pension-record/restoration-commutation/submission', [PensionerRecordUpdateController::class, 'restoration_commutation_submission'])->name('pension_unit_restoration_commutation_submission');
// Edit Page
Route::get('pension-unit/update-pension-record/restoration-commutation/edit/{id}', [PensionerRecordUpdateController::class, 'restoration_commutation_edit_page'])->name('pension_unit_restoration_commutation_page');
// Edit Form Submission
Route::post('pension-unit/update-pension-record/restoration-commutation/edit/submission', [PensionerRecordUpdateController::class, 'restoration_commutation_edit_submission'])->name('pension_unit_restoration_commutation_edit_submission');
// View Details
Route::get('pension-unit/update-pension-record/restoration-commutation/view/{id}', [PensionerRecordUpdateController::class, 'restoration_commutation_view_page'])->name('pension_unit_restoration_commutation_view');
/* ------ TDS Information  ------*/
// List Page
Route::get('pension-unit/update-pension-record/tds-information/list/', [PensionerRecordUpdateController::class, 'tds_information_list_page'])->name('pension_unit_tds_information_list_page');
// Form Page
Route::get('pension-unit/update-pension-record/tds-information/add/', [PensionerRecordUpdateController::class, 'tds_information_form_page'])->name('pension_unit_tds_information_form_page');
// Form Submission
Route::post('pension-unit/update-pension-record/tds-information/submission', [PensionerRecordUpdateController::class, 'tds_information_form_submission'])->name('pension_unit_tds_information_submission');
// Edit Form Page
Route::get('pension-unit/update-pension-record/tds-information/edit/{appID}', [PensionerRecordUpdateController::class, 'tds_information_edit_form_page'])->name('pension_unit_tds_information_edit_form_page');
// Edit Form Submission
Route::post('pension-unit/update-pension-record/tds-information/edit/submission', [PensionerRecordUpdateController::class, 'tds_information_form_edit_submission'])->name('pension_unit_tds_information_edit_submission');
// View Page
Route::get('pension-unit/update-pension-record/tds-information/view/{appID}', [PensionerRecordUpdateController::class, 'tds_information_view_page'])->name('pension_unit_tds_information_view_page');
/* ------ Life Certificate  ------*/
// List Page
Route::get('pension-unit/update-pension-record/life-certificate/list/', [PensionerRecordUpdateController::class, 'life_certificate_list_page'])->name('pension_unit_life_certificate_list_page');
// Form Page
Route::get('pension-unit/update-pension-record/life-certificate/add/', [PensionerRecordUpdateController::class, 'life_certificate_form_page'])->name('pension_unit_life_certificate_form_page');
// Form Submission
Route::post('pension-unit/update-pension-record/life-certificate/import/', [PensionerRecordUpdateController::class, 'life_certificate_form_submission'])->name('pension_unit_life_certificate_form_submission');
// View Page
Route::get('pension-unit/update-pension-record/life-certificate/view/{appID}', [PensionerRecordUpdateController::class, 'life_certificate_view_page'])->name('pension_unit_life_certificate_view_page');

/* ------ Arrears  ------*/
// Listing
Route::any('billing-officers/update-pension-record/arrears', [ArrearsController::class, 'index'])->name('billing_officer_arrears');
// Add
Route::get('billing-officers/update-pension-record/arrears/add', [ArrearsController::class, 'add'])->name('billing_officer_arrears_add');
// Pensioner Details
Route::post('billing-officers/arrears/pensioner-details/', [ArrearsController::class, 'pensioner_details'])->name('billing_officer_pensioner_details');
// Form Submission
Route::post('billing-officers/arrears/submission/', [ArrearsController::class, 'arrear_submission'])->name('billing_officer_arrear_submission');
// Pensioner Arrear Details
Route::get('billing-officers/update-pension-record/arrears/arrear-details/{pID}', [ArrearsController::class, 'arrear_data_details'])->name('billing_officer_arrears_arrear_details');
// Add multiple arrear details
Route::get('billing-officers/update-pension-record/arrears/multiple-arrear/{pID}', [ArrearsController::class, 'add_multiple'])->name('billing_officer_arrears_multiple_arrear');
// Multiple Form Submission
Route::post('billing-officers/arrears/multiple/submission/', [ArrearsController::class, 'multiple_arrear_submission'])->name('billing_officer_multiple_arrear_submission');

// Income details according to PPO number
Route::post('pension-unit/update-pension-record/total-income-value/details/', [PensionerRecordUpdateController::class, 'get_data_from_ppo_no'])->name('pension_unit_get_data_from_ppo_no');

// Pension Unit Approve Tax Declaration
Route::get('pension-unit-approve-update-pension-record/{id}', [PensionerRecordUpdateController::class, 'approvePensionUnitUpdatePensionRecord']);
// pension unit view application start
Route::any('pension-unit/applications', [PensionUnitController::class, 'applications'])->name('pension_unit_applications');
Route::get('pension-unit/applications-details/{id}', [PensionUnitController::class, 'application_details'])->name('pension_unit_application_details');
Route::post('pension-unit/applications/approval', [PensionUnitController::class, 'applications_approval'])->name('pension_unit_applications_approval');
Route::post('pension-unit/applications/submission', [PensionUnitController::class, 'applications_submission'])->name('pension_unit_applications_submission');
Route::post('pension-unit/applications/store-recovery', [PensionUnitController::class, 'applications_store_recovery'])->name('pension_unit_applications_store_recovery');
Route::post('pension-unit-get-year-month-day', [PensionUnitController::class, 'get_year_month_day'])->name('pension_unit_get_year_month_day');
Route::post('pension-unit/applications/service-pension-form-submission', [PensionUnitController::class, 'service_pension_form_submission'])->name('pension_unit_service_pension_form_submission');
// pension unit view application end
// pension unit physical verification
Route::get('pension-unit/physical-verification/pending', [PensionUnitController::class, 'pending_physical_verification'])->name('pending_physical_verification');
Route::get('pension-unit/physical-verification/pending/view/{id}', [PensionUnitController::class, 'viewPendingPhysicalVerification'])->name('viewPendingPhysicalVerification');
Route::any('pension-unit/physical-verification/filter', [PensionUnitController::class, 'filter_pending_physical_verification'])->name('filter_pending_physical_verification');
Route::post('pension-unit/physical-verification-form-submission', [PensionUnitController::class, 'physical_verification_submission'])->name('physical_verification_submission');


 

// User Panel
Route::get('user/dashboard',[DashboardController::class, 'dashboard'])->name('user_dashboard');
Route::get('user/check/employee',[PensionerProposalController::class, 'check_employee'])->name('check_employee');
Route::post('user/submit/employee',[PensionerProposalController::class, 'submit_check_employee'])->name('submit_check_employee');

Route::get('user/pension-form',[PensionerProposalController::class, 'pensioner_form'])->name('pensioner_form');
Route::post('user/submit/pension-form',[PensionerProposalController::class, 'save_pensioner_form'])->name('save_pensioner_form');
Route::get('user/edit-pension-form',[PensionerProposalController::class, 'edit_pensioner_form'])->name('edit_pensioner_form');
Route::post('user/update/pension-form',[PensionerProposalController::class, 'update_pensioner_form'])->name('update_pensioner_form');
Route::post('pensioner/save-as-draft/pension-form',[PensionerProposalController::class, 'save_as_draft_pensioner_form'])->name('save_as_draft_pensioner_form');

Route::get('user/persional-details',[PensionerProposalController::class, 'personal_details'])->name('personal_details');
Route::post('user/submit/personal-details',[PensionerProposalController::class, 'save_personal_details'])->name('save_personal_details');
Route::get('user/edit-personal-details',[PensionerProposalController::class, 'edit_personal_details'])->name('edit_personal_details');
Route::post('user/update/personal-details',[PensionerProposalController::class, 'update_personal_details'])->name('update_personal_details');
Route::post('personal-details/save-as-draft/pension-form',[PensionerProposalController::class, 'save_as_draft_personal_details'])->name('save_as_draft_personal_details');
// Application view details
Route::get('application/view-details',[PensionerProposalController::class, 'view_details'])->name('view_details');

// Application Preview and Conformation
Route::get('application/application-preview',[PensionerProposalController::class, 'application_preview'])->name('application_preview');
Route::post('user/submit/application',[PensionerProposalController::class, 'application_submit'])->name('application_submit');

Route::post('get-branch',[PensionerProposalController::class, 'get_branch'])->name('get_branch');

Route::get('user/nominee-details',[PensionerProposalController::class, 'nominee_form'])->name('nominee_form');
Route::post('user/add-nominee',[PensionerProposalController::class, 'add_new_nominee'])->name('add_new_nominee');
Route::post('user/submit/nominee-details', [PensionerProposalController::class, 'save_nominee_details'])->name('save_nominee_details');

Route::get('user/pension-documents', [PensionerProposalController::class, 'pension_documents'])->name('pension_documents');
Route::post('user/submit/pension-documents', [PensionerProposalController::class, 'save_pension_documents'])->name('save_pension_documents');
Route::post('get-bank-branch', [PensionerProposalController::class, 'get_bank_branch'])->name('get_bank_branch');
Route::post('state-personal-details', [PensionerProposalController::class, 'get_state'])->name('get_state');
Route::post('district-personal-details', [PensionerProposalController::class, 'get_district'])->name('get_district');

Route::get('user/logout',[LoginContoller::class, 'logout'])->name('user_logout');

// Captcha
Route::get('/reload-captcha', [LoginContoller::class, 'reloadCaptcha'])->name('reloadcaptcha');
// Route::post('getDistrict2','App\Http\Controllers\PensionUnitController@getDistrict2');
// for nominee register
//Route::get('register', [RegisterController::class, 'registration_form'])->name('registration_form');


Route::get('login', [PensionerLoginController::class, 'index'])->name('pensioner_login');
Route::post('login_submit',[PensionerLoginController::class, 'login_submit']);
// pensioner logout
Route::get('logout', [PensionerLoginController::class, 'logout']);
// Admin Area
Route::get('admin',[AdminLoginController::class, 'index'])->name('admin_login_form');
Route::post('admin_login_submit',[AdminLoginController::class, 'admin_login_submit']);

// admin logout
Route::get('admin.logout',[AdminLoginController::class, 'logout']);

// Route::post('getDistrict2','App\Http\Controllers\PensionUnitController@getDistrict2');


Route::get('register', [PensionerRegisterController::class,'index']);
Route::post('register_form_submit', [PensionerRegisterController::class,'register_form_submit']);

// Route::get('otp/{id}', [PensionerOtpController::class,'index']);
// Route::post('otp_verify', [PensionerOtpController::class,'verify_otp']);

// Route::get('forgot_password', [PensionerForgotPasswordController::class,'index']);
// Route::post('verify_mobile_no',[PensionerForgotPasswordController::class,'verify_mobile_no']);
// Route::get('resend_otp/{id}',[PensionerResendOtpController::class,'resend']);

Route::get('pensioner_form2',[PensionerForm2Controller::class,'index']);

// Tax Declaration Routes
 Route::get('user/tax/declaration', [TaxDeclarationController::class, 'tax_declaration'])->name('tax_declaration');
 Route::post('user/tax/declaration/submit', [TaxDeclarationController::class, 'submit_tax_declaration'])->name('submit_tax_declaration');
 Route::get('user/fetch-tax-declaration', [TaxDeclarationController::class, 'fetchTaxDeclaration'])->name('fetchTaxDeclaration');
 Route::post('user/tax/declaration/remove', [TaxDeclarationController::class, 'delTaxDeclaration'])->name('delTaxDeclaration');
//  Route::post('user/tax/declaration/update', [TaxDeclarationController::class, 'editTaxDeclaration'])->name('editTaxDeclaration');
 // View Tax Declaration
 Route::get('user/tax-declaration/view/{id}', [TaxDeclarationController::class, 'viewTaxDeclaration'])->name('tax_declaration_view');
 // Edit Tax Declaration
 Route::get('user/tax-declaration/edit/{id}', [TaxDeclarationController::class, 'editTaxDeclaration'])->name('tax_declaration_edit');
// Unit Head Tax Declaration 
Route::get('unit-head/tax-declaration', [TaxDeclarationController::class, 'UnitHeadTaxDeclaration'])->name('unit_head_tax_declaration');
// Unit Head view Tax Declaration 
Route::get('unit-head/tax-declaration/view/{id}', [TaxDeclarationController::class, 'viewUnitHeadTaxDeclaration'])->name('unit_head_tax_declaration_view');
// Unit Head Approve Tax Declaration
Route::get('unit-head-approve-tax-declaration/{id}', [TaxDeclarationController::class, 'approveUnitHeadTaxDeclaration']);
//Finance Executive Tax Declaration 
Route::get('finance-executive/tax-declaration', [TaxDeclarationController::class, 'FinanceExecutiveTaxDeclaration'])->name('finance_executive_tax_declaration');
//  Dealing Assistant Tax Declaration 
Route::get('hr-dealing/tax-declaration', [TaxDeclarationController::class, 'DealingAssistantTaxDeclaration'])->name('dealing_assistant_tax_declaration');
//  Dealing Assistant View Tax Declaration 
Route::get('hr-dealing/tax-declaration/view/{id}', [TaxDeclarationController::class, 'viewDealingAssistantTaxDeclaration'])->name('dealing_assistant_tax_declaration_view');


// Admin Area
//Route::get('admin',[AdminLoginController::class, 'index']);
Route::post('admin_login_submit',[AdminLoginController::class, 'admin_login_submit']);

// admin logout
Route::get('admin.logout',[AdminLoginController::class, 'logout']);


Route::group(['middleware'=>['auth']],function(){
   //admin dashboard
    Route::get('admin-dashboard',[AdminLoginController::class, 'dashboardindex'])->name('admin_dashboard'); 
    // add pension unit
    Route::get('pension-unit/add',[PensionUnitController::class, 'index'])->name('pension_unit'); 
    Route::post('pension_unit_submit', [PensionUnitController::class, 'pension_unit_submit']);
    // view pension unit
    Route::get('pension-unit', [PensionUnitController::class, 'fetch_pension_unit'])->name('pension_unit_details');
    // edit pension unit
    Route::get('pension-unit/edit/{id}', [PensionUnitController::class, 'edit_pension_unit'])->name('pension_unit_edit');
    // update  pension unit
    Route::post('pension_unit_update/{id}', [PensionUnitController::class, 'update_pension_unit']);
    // delete pension unit
    Route::get('pension_unit_delete/{id}', [PensionUnitController::class, 'delete_pension_unit']);
    // chnage status pension uint
    Route::post('changeStatus', [PensionUnitController::class, 'changeStatus'])->name('check_status_pension_unit');
    // add  unit
    Route::get('unit/add',[UnitController::class, 'index'])->name('unit_add');
    Route::post('unit_submit', [UnitController::class, 'unit_submit']);
    // view unit 
    Route::get('unit', [UnitController::class, 'fetch_unit'])->name('unit_details');
    // edit  unit
    Route::get('unit/edit/{id}', [UnitController::class, 'edit_unit']);
    // update  unit
    Route::post('unit_update/{id}', [UnitController::class, 'update_unit']);
    // delete  unit
    Route::get('unit_delete/{id}', [UnitController::class, 'delete_unit']);
    // chnage status
    Route::post('changeStatus_optcl_unit', [UnitController::class, 'changeStatus'])->name('check_status_optcl_unit');

    // add  designation
    Route::get('designation/add',[DesignationController::class, 'index'])->name('designation_add');
    Route::post('designation_submit', [DesignationController::class, 'designation_submit']);
    // view designation 
    Route::get('designation', [DesignationController::class, 'fetch_designation'])->name('designation_details');
    // edit  designation
    Route::get('designation/edit/{id}', [DesignationController::class, 'edit_designation']);
    // update  designation
    Route::post('designation_update/{id}', [DesignationController::class, 'update_designation']);
    // delete  designation
    Route::get('designation_delete/{id}', [DesignationController::class, 'delete_designation']);
    // change status designation
    Route::post('changeStatus_designation', [DesignationController::class, 'changeStatus'])->name('check_status_designation');


       // add  religion
    Route::get('religion/add',[ReligionController::class, 'index'])->name('religion_add');
    Route::post('religion_submit', [ReligionController::class, 'religion_submit']);
    // view religion 
    Route::get('religion', [ReligionController::class, 'fetch_religion'])->name('religion_details');
    // edit  religion
    Route::get('religion/edit/{id}', [ReligionController::class, 'edit_religion']);
    // update  religion
    Route::post('religion_update/{id}', [ReligionController::class, 'update_religion']);
    // delete  religion
    Route::get('religion_delete/{id}', [ReligionController::class, 'delete_religion']);
    // check status religion
    Route::post('changeStatus_religion', [ReligionController::class, 'changeStatus'])->name('check_status_religion');


    // add  user
    Route::get('user/add',[AdminUserController::class, 'index'])->name('user_add');
    Route::post('user-submit', [AdminUserController::class, 'user_submit']);
    // fetch designation 
    Route::post('getDesignation', [AdminUserController::class,'getDesignation'])->name('user_desgnation_data');
    // view user 
    Route::any('user', [AdminUserController::class, 'fetch_user'])->name('user_details');
    // edit  user
    Route::get('user/edit/{id}', [AdminUserController::class, 'edit_user']);
    // update  user
    Route::post('user-update/{id}', [AdminUserController::class, 'update_user']);
    // delete  user
    Route::get('user-delete/{id}', [AdminUserController::class, 'delete_user']);
    // check status user
    Route::post('changeStatus_user', [AdminUserController::class, 'changeStatus'])->name('check_status_user');

    // add  country
    Route::get('country/add',[CountryMasterController::class, 'index'])->name('country_add');
    Route::post('country-submit', [CountryMasterController::class, 'country_submit']);
    // view country 
    Route::get('country', [CountryMasterController::class, 'fetch_country'])->name('country_details');
    // edit  country
    Route::get('country/edit/{id}', [CountryMasterController::class, 'edit_country']);
    // update  country
    Route::post('country-update/{id}', [CountryMasterController::class, 'update_country']);
    // delete  country
    Route::get('country-delete/{id}', [CountryMasterController::class, 'delete_country']);
    // check status religion
    Route::post('changeStatus_country', [CountryMasterController::class, 'changeStatus'])->name('check_status_country');

     // add  state
     Route::get('state/add',[StateMasterController::class, 'index'])->name('state_add');
     Route::post('state-submit', [StateMasterController::class, 'state_submit']);
     // view state 
     Route::any('state', [StateMasterController::class, 'fetch_state'])->name('state_details');
     // edit  state
     Route::get('state/edit/{id}', [StateMasterController::class, 'edit_state']);
     // update  state
     Route::post('state-update/{id}', [StateMasterController::class, 'update_state']);
     // delete  state
     Route::get('state-delete/{id}', [StateMasterController::class, 'delete_state']);
     // check status state
     Route::post('changeStatus_state', [StateMasterController::class, 'changeStatus'])->name('check_status_state');

      // add  district
    Route::get('district/add',[DistrictMasterController::class, 'index'])->name('district_add');
    Route::post('district-submit', [DistrictMasterController::class, 'district_submit']);
    // view district 
    Route::any('district', [DistrictMasterController::class, 'fetch_district'])->name('district_details');
    // edit  district
    Route::get('district/edit/{id}', [DistrictMasterController::class, 'edit_district']);
    // update  district
    Route::post('district-update/{id}', [DistrictMasterController::class, 'update_district']);
    // delete  district
    Route::get('district-delete/{id}', [DistrictMasterController::class, 'delete_district']);
    // get state details in state table
    Route::post('getState2', [DistrictMasterController::class,'getState'])->name('statedata');
    // filter district
    Route::any('filter-district', [DistrictMasterController::class,'filterDistrict']);
    // check status district
    Route::post('changeStatus_district', [DistrictMasterController::class, 'changeStatus'])->name('check_status_district');

      // add  bank name
    Route::get('bank-name/add',[BankNameMasterController::class, 'index'])->name('bank_name_add');
    Route::post('bank-name-submit', [BankNameMasterController::class, 'bank_name_submit']);
    // view bank name 
    Route::get('bank-name', [BankNameMasterController::class, 'fetch_bank_name'])->name('bank_name_details');
    // edit  bank name
    Route::get('bank-name/edit/{id}', [BankNameMasterController::class, 'edit_bank_name']);
    // update  bank name
    Route::post('bank-name-update/{id}', [BankNameMasterController::class, 'update_bank_name']);
    // delete  bank name
    Route::get('bank-name-delete/{id}', [BankNameMasterController::class, 'delete_bank_name']);
    // check status bank name
    Route::post('changeStatus_bank_name', [BankNameMasterController::class, 'changeStatus'])->name('check_status_bank_name');

    // add  bank branch 
    Route::get('bank-branch/add',[BankBranchNameMasterController::class, 'index'])->name('bank_branch_add');
    Route::post('bank-branch-submit', [BankBranchNameMasterController::class, 'bank_branch_submit']);
    // view bank branch  
    Route::any('bank-branch', [BankBranchNameMasterController::class, 'fetch_bank_branch'])->name('bank_branch_details');
    // edit  bank branch 
    Route::get('bank-branch/edit/{id}', [BankBranchNameMasterController::class, 'edit_bank_branch']);
    // update  bank branch 
    Route::post('bank-branch-update/{id}', [BankBranchNameMasterController::class, 'update_bank_branch']);
    // delete  bank branch 
    Route::get('bank-branch-delete/{id}', [BankBranchNameMasterController::class, 'delete_bank_branch']);
      // check status bank branch
    Route::post('changeStatus_bank_branch', [BankBranchNameMasterController::class, 'changeStatus'])->name('check_status_bank_branch');

     // add  relation
    Route::get('relation/add',[RelationMasterController::class, 'index'])->name('relation_add');
    Route::post('relation_submit', [RelationMasterController::class, 'relation_submit']);
      // view relation 
    Route::get('relation', [RelationMasterController::class, 'fetch_relation'])->name('relation_details');
     // edit  relation
    Route::get('relation/edit/{id}', [RelationMasterController::class, 'edit_relation']);
     // update  relation
    Route::post('relation_update/{id}', [RelationMasterController::class, 'update_relation']);
     // delete  relation
    Route::get('relation_delete/{id}', [RelationMasterController::class, 'delete_relation']);
    // check status relation
    Route::post('changeStatus_relation', [RelationMasterController::class, 'changeStatus'])->name('check_status_relation');

    // view nominee preference 
    Route::get('nomineepreference', [NomineePreferenceController::class, 'fetch_nominee'])->name('nominee_details');
    // check status nominee
    Route::post('changeStatus_nominee', [NomineePreferenceController::class, 'changeStatus'])->name('check_status_nominee');

    // view gender
    Route::get('admin/gender', [GenderMasterController::class, 'fetch_gender'])->name('gender_details');

    // check status gender
    Route::post('changeStatus_gender', [GenderMasterController::class, 'changeStatus'])->name('check_status_gender');

    // view user designation
    Route::get('admin/user/designation', [UserDesignationController::class, 'fetch_user_designation'])->name('user_designation_details');
    
     // change status user designation
     Route::post('changeStatus_user_designation', [UserDesignationController::class, 'changeStatus'])->name('check_status_user_designation');

      // add  form 16
    Route::get('form16/add',[Form16MasterController::class, 'index'])->name('form16_add');
    Route::post('form16_submit', [Form16MasterController::class, 'form16_submit']);
      // view form 16 
    Route::get('form16', [Form16MasterController::class, 'fetch_form16'])->name('form16_details');
     // edit  form 16
    Route::get('form16/edit/{id}', [Form16MasterController::class, 'edit_form16'])->name('form16_edit');
     // update  form 16
    Route::post('form16_update/{id}', [Form16MasterController::class, 'update_form16']);
     // delete  form 16
    Route::get('form16_delete/{id}', [Form16MasterController::class, 'delete_form16']);

    // change status form 16
    Route::post('changeStatus_form16', [Form16MasterController::class, 'changeStatus'])->name('check_status_form16');

      // add  da
    Route::get('da/add',[DaMasterController::class, 'index'])->name('da_add');
    Route::post('da_submit', [DaMasterController::class, 'da_submit']);
      // view da
    Route::any('da', [DaMasterController::class, 'fetch_da'])->name('da_details');
     // edit  da
    Route::get('da/edit/{id}', [DaMasterController::class, 'edit_da'])->name('da_edit');
     // update  da
    Route::post('da_update/{id}', [DaMasterController::class, 'update_da']);
     // delete  da
    Route::get('da_delete/{id}', [DaMasterController::class, 'delete_da']);
    // change da
    Route::post('changeStatus_da', [DaMasterController::class, 'changeStatus'])->name('check_status_da');

      // add  ti
    Route::get('ti/add',[TiMasterController::class, 'index'])->name('ti_add');
    Route::post('ti_submit', [TiMasterController::class, 'ti_submit']);
      // view ti
    Route::any('ti', [TiMasterController::class, 'fetch_ti'])->name('ti_details');
     // edit  ti
    Route::get('ti/edit/{id}', [TiMasterController::class, 'edit_ti'])->name('ti_edit');
     // update  ti
    Route::post('ti_update/{id}', [TiMasterController::class, 'update_ti']);
     // delete  ti
    Route::get('ti_delete/{id}', [TiMasterController::class, 'delete_ti']);
    // change ti
    Route::post('changeStatus_ti', [TiMasterController::class, 'changeStatus'])->name('check_status_ti');
    // add  employee
  
    Route::get('employee/import',[EmployeeMasterController::class, 'index'])->name('employee_add');
    Route::post('employee_submit', [EmployeeMasterController::class, 'employee_submit']);
      // view employee
    Route::any('employee', [EmployeeMasterController::class, 'fetch_employee'])->name('employee_details');
     // edit  employee
    Route::get('employee/edit/{id}', [EmployeeMasterController::class, 'edit_employee'])->name('employee_edit');
     // update  employee
    Route::post('employee_update/{id}', [EmployeeMasterController::class, 'update_employee']);
     // delete  employee
    Route::get('employee_delete/{id}', [EmployeeMasterController::class, 'delete_employee']);
  // change employee
    Route::post('changeStatus_employee', [EmployeeMasterController::class, 'changeStatus'])->name('check_status_employee');
  	
  	// add  Calculation Rules
    Route::get('rule/add',[CalculationRuleMasterController::class, 'index'])->name('rule_add');
    Route::post('rule-submit', [CalculationRuleMasterController::class, 'rule_submit']);
    // view Calculation Rules  
    Route::any('rules', [CalculationRuleMasterController::class, 'fetch_rule'])->name('rule_details');
    // edit Calculation Rules 
    Route::get('rule/edit/{id}', [CalculationRuleMasterController::class, 'edit_rule']);
    // update Calculation Rules 
    Route::post('rule-update/{id}', [CalculationRuleMasterController::class, 'update_rule']);
    // delete Calculation Rules
    Route::get('rule-delete/{id}', [CalculationRuleMasterController::class, 'delete_rule']);
    // change calculation
    Route::post('changeStatus_calculation', [CalculationRuleMasterController::class, 'changeStatus'])->name('check_status_calculation');

    //view commutation_details
    Route::any('commutation', [CommutationMasterController::class, 'fetch_commutation'])->name('commutation_details');
    // change commutation
    Route::post('changeStatus_commutation', [CommutationMasterController::class, 'changeStatus'])->name('check_status_commutation');
});
 
Route::get('user/proposal-listing', [PensionerProposalController::class, 'proposal_listing'])->name('proposal_listing');
Route::get('user/proposal-view/{id}', [PensionerProposalController::class, 'proposal_view'])->name('proposal_view');
Route::post('get-details-branch', [PensionerProposalController::class, 'getBranchDetails'])->name('get_details_branch');
Route::post('get-branch-details',[PensionerProposalController::class, 'get_branch_details'])->name('get_branch_details');
Route::post('user/delete-nominee-details', [PensionerProposalController::class, 'delete_nominee_details'])->name('delete_nominee_details');
Route::post('check-account-no', [PensionerProposalController::class, 'check_account_no'])->name('check_account_no');

// OPTCL Unit Dealing Assiatant
Route::any('dealing/applications', [DealingAssistantController::class, 'applications'])->name('dealing_applications');
Route::get('dealing/applications-details/{id}', [DealingAssistantController::class, 'application_details'])->name('dealing_application_details');
Route::post('dealing/applications/approval', [DealingAssistantController::class, 'applications_approval'])->name('applications_approval');
Route::post('dealing/applications/submission', [DealingAssistantController::class, 'applications_submission'])->name('applications_submission');
Route::post('dealing/applications/store-recovery', [DealingAssistantController::class, 'applications_store_recovery'])->name('applications_store_recovery');
Route::post('get-year-month-day', [DealingAssistantController::class, 'get_year_month_day'])->name('get_year_month_day');
Route::post('dealing/applications/service-pension-form-submission', [DealingAssistantController::class, 'service_pension_form_submission'])->name('service_pension_form_submission');
// add applicant in optcl unit dealing assistant
Route::get('dealing-assistant/add-applicant',[DaAddApplicantController::class, 'add_applicant'])->name('add_applicant');
Route::get('dealing-assistant/view-applicant',[DaAddApplicantController::class, 'view_applicant'])->name('view_applicant');
Route::post('add_applicant_submit',[DaAddApplicantController::class, 'add_applicant_submit'])->name('da_add_applicant_submit');
Route::any('dealing-assistant/view-applicant/filter', [DaAddApplicantController::class, 'filter_applicants'])->name('filter_applicants');
Route::get('dealing-assistant/edit-applicant/{appID}',[DaAddApplicantController::class, 'edit_applicant'])->name('edit_applicant');
Route::post('dealing-assistant/update-applicant/submission',[DaAddApplicantController::class, 'update_applicant_details'])->name('update_applicant_details');
Route::get('dealing-assistant/delete-applicant/{appID}',[DaAddApplicantController::class, 'delete_applicant'])->name('delete_applicant');
Route::get('dealing-assistant/notify-applicant/{appID}',[DaAddApplicantController::class, 'notify_applicant'])->name('notify_applicant');
// Pension Unit Existing Pension Proposal Submission
Route::any('pensioner-unit/existing/application/pension-list','App\Http\Controllers\ExistingProposalController@pension_list')->name('existing_pension_list');
Route::get('pensioner-unit/existing/application/pensioner-form','App\Http\Controllers\ExistingProposalController@pensioner_form')->name('existing_pensioner_form');
Route::post('pensioner-unit/existing/application/pensioner-form/get-relation-status','App\Http\Controllers\ExistingProposalController@get_relation_type')->name('existing_pensioner_get_relation_type');

Route::get('pensioner-unit/existing/application/taxable-amount/{cID}','App\Http\Controllers\ExistingProposalController@show_taxable_amount')->name('existing_pensioner_taxable_amount');
Route::post('pensioner-unit/existing/application/taxable-amount-submission','App\Http\Controllers\ExistingProposalController@taxable_amount_submit')->name('existing_pensioner_taxable_amount_submission');

Route::post('dealing-assistant/existing/application/pensioner-form/save-as-draft','
    App\Http\Controllers\ExistingProposalController@save_as_draft_pensioner_form')->name('existing_pensioner_form_save_as_draft');
Route::post('dealing-assistant/existing/application/pensioner-form/submission','App\Http\Controllers\ExistingProposalController@save_pensioner_form')->name('existing_pensioner_form_submission');

Route::get('pensioner-unit/existing/application/view-details','App\Http\Controllers\ExistingProposalController@pensioner_details')->name('existing_pensioner_details');
    



// Finance Executive User
Route::any('finance-executive/applications', [DdoController::class, 'applications'])->name('ddo_applications');
Route::get('finance-executive/applications-details/{id}', [DdoController::class, 'application_details'])->name('ddo_application_details');
Route::post('finance-executive/applications/approval', [DdoController::class, 'applications_approval'])->name('ddo_applications_approval');
Route::post('finance-executive/applications/rejection', [DdoController::class, 'applications_rejection'])->name('ddo_applications_rejection');
Route::post('finance-executive/get/remarks', [DdoController::class, 'get_rejection_remark'])->name('get_rejection_remark');
Route::post('finance-executive/applications/submission', [DdoController::class, 'applications_submission'])->name('ddo_applications_submission');

Route::post('finance-executive/applications/store-recovery', [DdoController::class, 'applications_store_recovery'])->name('fe_applications_store_recovery');
Route::post('finance-executive/get-year-month-day', [DdoController::class, 'get_year_month_day'])->name('fe_get_year_month_day');
Route::post('finance-executive/applications/service-pension-form-submission', [DdoController::class, 'service_pension_form_submission'])->name('fe_service_pension_form_submission');


// Unit Head User
Route::any('unit-head/applications', [UnitHeadController::class, 'applications'])->name('unit_head_applications');
Route::get('unit-head/applications-details/{id}', [UnitHeadController::class, 'application_details'])->name('unit_head_application_details');
Route::post('unit-head/applications/approval', [UnitHeadController::class, 'applications_approval'])->name('unit_head_applications_approval');
Route::post('unit-head/applications/rejection', [UnitHeadController::class, 'applications_rejection'])->name('unit_head_applications_rejection');
Route::post('unit-head/get/remarks', [UnitHeadController::class, 'get_rejection_remark'])->name('unit_head_get_rejection_remark');
Route::post('unit-head/applications/submission', [UnitHeadController::class, 'applications_submission'])->name('unit_head_applications_submission');


Route::get('calculate-pensionar-benefits/{id}', [PensionerBenefitController::class, 'calculate_pensionar_benefits'])->name('calculate_pensionar_benefits');
Route::post('calculate-rules', [PensionerBenefitController::class, 'calculate_rules'])->name('calculate_rules');
Route::post('calculate-service-pension-save', [PensionerBenefitController::class, 'calculate_service_pension_save'])->name('calculate_service_pension_save');
Route::post('calculate-dcr-gratuity-save', [PensionerBenefitController::class, 'calculate_dcr_gratuity_save'])->name('calculate_dcr_gratuity_save');

// Family pensioner calculation
Route::post('calculate-family-pensioner', [PensionerBenefitController::class, 'get_family_pension_details'])->name('get_family_pension_details');
Route::post('save-family-pensioner', [PensionerBenefitController::class, 'save_transaction_details'])->name('save_transaction_details');
// Commutation Rule One
Route::post('calculate-commutation-pension', [PensionerBenefitController::class, 'get_commutation_rule_one'])->name('get_commutation_rule_one');
// Commutation Rule Two
Route::post('calculate-commutation-pension-worked-out', [PensionerBenefitController::class, 'get_commutation_rule_two'])->name('get_commutation_rule_two');
// Commutation Rule Three
Route::post('calculate-commutation-reduced-pension', [PensionerBenefitController::class, 'get_commutation_rule_three'])->name('get_commutation_rule_three');
Route::post('calculation-sheet-submitted', [PensionerBenefitController::class, 'calculation_sheet_submitted'])->name('calculation_sheet_submitted');



// HR Wing Dealing Assistant
Route::any('hr-dealing/applications', [HRDealingAssistantController::class, 'applications'])->name('hr_dealing_applications');
Route::get('hr-dealing/applications-details/{id}', [HRDealingAssistantController::class, 'application_details'])->name('hr_dealing_application_details');
Route::post('hr-dealing/applications/approval', [HRDealingAssistantController::class, 'applications_approval'])->name('hr_applications_approval');
Route::post('hr-dealing/applications/submission', [HRDealingAssistantController::class, 'applications_submission'])->name('hr_applications_submission');
Route::post('hr/applications/store-recovery', [HRDealingAssistantController::class, 'applications_store_recovery'])->name('hr_applications_store_recovery');
Route::post('hr/applications/service-pension-form-submission', [HRDealingAssistantController::class, 'service_pension_form_submission'])->name('hr_service_pension_form_submission');
Route::post('hr/applications/service-pension-form-three-submission', [HRDealingAssistantController::class, 'service_pension_form_three_submission'])->name('hr_service_pension_form_part_three_submission');
Route::get('hr-dealing/applications/sanction-order-generate/{id}', [HRDealingAssistantController::class, 'sanction_order_generate'])->name('sanction_order_generate');
Route::post('hr-dealing/applications/sanction-order-submit', [HRDealingAssistantController::class, 'sanction_order_submit'])->name('sanction_order_submit');
Route::get('hr-dealing/applications/gratuity-sanction-order-generate/{id}', [HRDealingAssistantController::class, 'gratuity_sanction_order_generate'])->name('gratuity_sanction_order_generate');
Route::post('hr-dealing/applications/gratuity-sanction-order-submit', [HRDealingAssistantController::class, 'gratuity_sanction_order_submit'])->name('gratuity_sanction_order_submit');



// Resubmit Form Details
Route::get('application/resubmit','App\Http\Controllers\ResubmitPensionController@edit_pensioner_form')->name('resubmit_page');
Route::post('application/resubmission','App\Http\Controllers\ResubmitPensionController@update_pensioner_form')->name('update_resubmission');
Route::get('application/personal-details-resubmit','App\Http\Controllers\ResubmitPensionController@edit_personal_details')->name('personal_resubmit_page');
Route::post('application/personal-details/resubmission','App\Http\Controllers\ResubmitPensionController@update_personal_details')->name('update_persional_details_resubmission');
Route::get('application/nominee-details/resubmit','App\Http\Controllers\ResubmitPensionController@nominee_form')->name('nominee_form_resubmit');
Route::post('user/resubmission/nominee-details', 'App\Http\Controllers\ResubmitPensionController@save_nominee_details')->name('save_nominee_details_resubmission');
Route::get('user/resubmit/pension-documents', 'App\Http\Controllers\ResubmitPensionController@pension_documents')->name('pension_documents_resubmit');
Route::post('user/resubmit/pension-documents', 'App\Http\Controllers\ResubmitPensionController@save_pension_documents')->name('save_pension_documents_resubmission');
Route::post('dealing-assistant/applications/resubmission', [DealingAssistantController::class, 'applications_resubmission'])->name('dealing_assistant_applications_resubmission');

// HR Wing Sanction Authority
Route::any('hr-sanction-authority/applications', [HRSanctionAuthorityController::class, 'applications'])->name('hr_sanction_authority_applications');
Route::get('hr-sanction-authority/applications-details/{id}', [HRSanctionAuthorityController::class, 'application_details'])->name('hr_sanction_authority_application_details');
Route::post('hr-sanction-authority/single/application/assignment', [HRSanctionAuthorityController::class, 'application_assignment'])->name('hr_sanction_authority_application_assignment');
Route::post('hr-sanction-authority/multiple/application/assignment', [HRSanctionAuthorityController::class, 'multiple_application_assignment'])->name('hr_sanction_authority_multiple_application_assignment');

Route::any('hr-sanction-authority/application/assignment-list', [HRSanctionAuthorityController::class, 'assignments'])->name('assignments');
Route::get('hr-sanction-authority/application/assignment-history', [HRSanctionAuthorityController::class, 'assignment_history'])->name('assignments_history');
Route::get('hr-sanction-authority/application-approval/{id}', [HRSanctionAuthorityController::class, 'application_approval'])->name('application_approval');
Route::post('hr-sanction-authority/application-approval', [HRSanctionAuthorityController::class, 'submit_application_approval'])->name('submit_application_approval');

// HR Excutive
Route::any('hr-executive/applications', [HRExecutiveController::class, 'applications'])->name('hr_executive_applications');
Route::get('hr-executive/applications-details/{id}', [HRExecutiveController::class, 'application_details'])->name('hr_executive_application_details');
Route::post('hr-executive/applications/approval', [HRExecutiveController::class, 'applications_approval'])->name('hr_executive_applications_approval');
Route::post('hr-executive/applications/submission', [HRExecutiveController::class, 'applications_submission'])->name('hr_executive_applications_submission');
Route::post('hr-executive/applications/store-recovery', [HRExecutiveController::class, 'applications_store_recovery'])->name('hr_executive_applications_store_recovery');
Route::post('hr-executive/applications/service-pension-form-submission', [HRExecutiveController::class, 'service_pension_form_submission'])->name('hr_executive_service_pension_form_submission');
Route::post('hr-executive/applications/service-pension-form-three-submission', [HRExecutiveController::class, 'service_pension_form_three_submission'])->name('hr_executive_service_pension_form_part_three_submission');
Route::get('hr-executive/applications/sanction-order-generate/{id}', [HRExecutiveController::class, 'sanction_order_generate'])->name('hr_executive_sanction_order_generate');
Route::post('hr-executive/applications/sanction-order-submit', [HRExecutiveController::class, 'sanction_order_submit'])->name('hr_executive_sanction_order_submit');
Route::get('hr-executive/applications/gratuity-sanction-order-generate/{id}', [HRExecutiveController::class, 'gratuity_sanction_order_generate'])->name('hr_executive_gratuity_sanction_order_generate');
Route::post('hr-executive/applications/gratuity-sanction-order-submit', [HRExecutiveController::class, 'gratuity_sanction_order_submit'])->name('hr_executive_gratuity_sanction_order_submit');


// Extra validation link
Route::post('check/aadhar-no', 'App\Http\Controllers\ExtraController@validate_aadhar_number')->name('validate_aadhar_number');
Route::post('check/account-no', 'App\Http\Controllers\ExtraController@validate_account_number')->name('validate_account_number');
Route::post('check/doj', 'App\Http\Controllers\ExtraController@validate_doj')->name('validate_doj');
Route::post('check/dor', 'App\Http\Controllers\ExtraController@validate_dor')->name('validate_dor');
Route::post('check/pan', 'App\Http\Controllers\ExtraController@validate_pan')->name('validate_pan');
Route::post('check/account-number', 'App\Http\Controllers\ExtraController@validate_account')->name('validate_account');
Route::post('check/email-address', 'App\Http\Controllers\ExtraController@validate_email')->name('validate_email');
Route::post('check/mobile-number', 'App\Http\Controllers\ExtraController@validate_mobile_number')->name('validate_mobile_number');
Route::post('check/pensinor-nominee-mobile-number', 'App\Http\Controllers\ExtraController@validate_pensinor_nominee_mobile_number')->name('validate_pensinor_nominee_mobile_number');
Route::post('check/pensinor-nominee-aadhar-no', 'App\Http\Controllers\ExtraController@validate_pensinor_nominee_aadhar_number')->name('validate_pensinor_nominee_aadhar_number');
Route::post('convert_days_to_year_month_days', 'App\Http\Controllers\ExtraController@convert_days_to_year_month_days')->name('convert_days_to_year_month_days');

// for DA Applicant form
Route::post('check/da/employee-code', 'App\Http\Controllers\ExtraController@validate_da_employee_code')->name('validate_da_employee_code');
Route::post('check/da/aadhaar-no', 'App\Http\Controllers\ExtraController@validate_da_aadhaar_no')->name('validate_da_aadhaar_no');
Route::post('check/da/mobile-no', 'App\Http\Controllers\ExtraController@validate_da_mobile_number')->name('validate_da_mobile_number');

// 30-11-2021
// Dealing Assistant
Route::post('application/hr-wing/dealing-assistant/resubmit', [HRDealingAssistantController::class, 'application_resubmission'])->name('hr_dealing_assistant_application_resubmission');
Route::get('hr-sanction-authority/application-forward/initiator/{id}', [HRSanctionAuthorityController::class, 'application_forward_initiator'])->name('application_forward_initiator');
Route::post('application/hr-wing/sanction-authority/resubmit', [HRSanctionAuthorityController::class, 'application_resubmission'])->name('hr_sanction_authority_application_resubmission');

// Notification
Route::get('notification/list', 'App\Http\Controllers\NotificationController@list')->name('notifications');


// Initiator User
Route::any('initiator/applications', [InitiatorController::class, 'applications'])->name('initiator_applications');
Route::get('initiator/applications-details/{id}', [InitiatorController::class, 'application_details'])->name('initiator_application_details');
Route::post('initiator/applications/approval', [InitiatorController::class, 'applications_approval'])->name('initiator_applications_approval');
Route::get('initiator/family-pension/applications-details/{id}', [FPInitiatorController::class, 'application_details'])->name('initiator_family_pension_application_details');
Route::post('initiator/family-pension/applications/approval', [FPInitiatorController::class, 'applications_approval'])->name('initiator_family_pension_applications_approval');


// Verifier User
Route::any('verifier/applications', [VerifierController::class, 'applications'])->name('verifier_applications');
Route::get('verifier/applications-details/{id}', [VerifierController::class, 'application_details'])->name('verifier_application_details');
Route::post('verifier/applications/approval', [VerifierController::class, 'applications_approval'])->name('verifier_applications_approval');
Route::get('verifier/family-pension/applications-details/{id}', [FPVerifierController::class, 'application_details'])->name('verifier_family_pension_application_details');
Route::post('verifier/family-pension/applications/approval', [FPVerifierController::class, 'applications_approval'])->name('verifier_family_pension_applications_approval');


// Approver User
Route::any('approver/applications', [ApproverController::class, 'applications'])->name('approver_applications');
Route::get('approver/applications-details/{id}', [ApproverController::class, 'application_details'])->name('approver_application_details');
Route::post('approver/applications/approval', [ApproverController::class, 'applications_approval'])->name('approver_applications_approval');
Route::get('approver/applications/ppo-order-generate/{id}', [ApproverController::class, 'ppo_order_generate'])->name('approver_ppo_order_generate');
Route::post('approver/applications/ppo-order-submit', [ApproverController::class, 'ppo_order_submit'])->name('approver_ppo_order_submit');

Route::get('approver/family-pension/applications-details/{id}', [FPApproverController::class, 'application_details'])->name('approver_family_pension_application_details');
Route::post('approver/family-pension/applications/approval', [FPApproverController::class, 'applications_approval'])->name('approver_family_pension_applications_approval');
Route::get('approver/family-pension/applications/ppo-order-generate/{id}', [FPApproverController::class, 'ppo_order_generate'])->name('approver_family_pension_ppo_order_generate');
Route::post('approver/family-pension/applications/ppo-order-submit', [FPApproverController::class, 'ppo_order_submit'])->name('approver_family_pension_ppo_order_submit');

// 09-02-2022
Route::post('category/ta/percentage-amount', 'App\Http\Controllers\ExistingProposalController@get_category_ti_amount')->name('category_ta_percentage_amount');
Route::post('save/existing/document', 'App\Http\Controllers\ExistingProposalController@save_existing_documents')->name('save_existing_application');

Route::post('pensioner-unit/existing/get-age-value', 'App\Http\Controllers\ExistingProposalController@get_age_additional_pension')->name('get_age_additional_pension');
Route::post('pensioner-unit/existing/get-additional-amount', 'App\Http\Controllers\ExistingProposalController@get_additional_pension_amount')->name('get_additional_pension_amount');

Route::post('pensioner-unit/existing/get-family-pension-amount-details', 'App\Http\Controllers\ExistingProposalController@get_family_pension_pension_amount_details')->name('get_family_pension_pension_amount_details');
Route::get('pensioner-unit/existing/user/pensioner-details/{penID}', 'App\Http\Controllers\ExistingProposalController@pensioner_details')->name('get_existing_pensioner_details');

// Monthly Changed Data
Route::any('pensioner-unit/existing/user/monthly-changed-data/', 'App\Http\Controllers\MonthlyChangedDataController@list')->name('monthly_changed_data_list');
Route::post('pensioner-unit/existing/user/monthly-changed-data/single/forward-to-billing-officer', 'App\Http\Controllers\MonthlyChangedDataController@application_assignment')->name('monthly_changed_data_single_forward_to_billing_officer');
Route::post('pensioner-unit/existing/user/monthly-changed-data/multiple/forward-to-billing-officer', 'App\Http\Controllers\MonthlyChangedDataController@multiple_application_assignment')->name('monthly_changed_data_multiple_forward_to_billing_officer');
Route::any('pensioner-unit/existing/user/monthly-changed-data/history', 'App\Http\Controllers\MonthlyChangedDataController@history')->name('monthly_changed_data_history');
Route::get('pensioner-unit/existing/user/monthly-changed-data/pensioner-details/{penID}', 'App\Http\Controllers\ExistingProposalController@pensioner_details')->name('get_monthly_changed_data_pensioner_details');

// Billing Officer
Route::get('billing-officer/application/list', 'App\Http\Controllers\BillingOfficerController@list')->name('billing_officer_list');
Route::post('billing-officer/application/net-amount-details', 'App\Http\Controllers\BillingOfficerController@get_net_amount_details')->name('get_net_amount_details');
Route::post('billing-officer/application/save-net-amount-details', 'App\Http\Controllers\BillingOfficerController@save_net_pension')->name('save_net_amount_details');
Route::any('billing-officer/application/approval/list', 'App\Http\Controllers\BillingOfficerController@approval_list')->name('billing_officer_approval_list_list');
Route::post('billing-officer/application/generate-bill', 'App\Http\Controllers\BillingOfficerController@generate_bill')->name('generate_bill');
// Billing Officer Multiple Application Approval
Route::post('billing-officer/application/approval/submission', 'App\Http\Controllers\BillingOfficerController@multiple_application_assignment')->name('billing_officer_application_approval_submission');
// Billing Officer Single Application Approval Service Pensioner
Route::post('billing-officer/application/single/approval/submission', 'App\Http\Controllers\BillingOfficerController@monthly_changed_data_approval_service_pensioner')->name('billing_officer_application_single_approval_submission');
// Billing Officer Single Application Approval Family Pensioner
Route::post('billing-officer/application/single/approval/family-pensioner/submission', 'App\Http\Controllers\BillingOfficerController@monthly_changed_data_approval_family_pensioner')->name('billing_officer_application_single_approval_family_pension_submission');
// Billing Ifficer Service Pensioner Approval Details
Route::get('billing-officer/application/single/approval/family-pensioner/{appID}', 'App\Http\Controllers\BillingOfficerController@show_net_pension')->name('net_pension_calculation_sheet');
Route::any('billing-officer/application/history', 'App\Http\Controllers\BillingOfficerController@history')->name('billing_officer_application_history');
Route::get('billing-officer/application/net-pension-calculation/{appID}', 'App\Http\Controllers\BillingOfficerController@show_net_pension')->name('net_pension_calculation_sheet');
Route::get('billing-officer/application/net-pension-calculation-view/{appID}', 'App\Http\Controllers\BillingOfficerController@show_net_pension_view')->name('net_pension_calculation_sheet_view');
// Revision of Basic Pension View Page Billing Officer
Route::get('billing-officer/application/revision-basic-pension-view/{appID}', 'App\Http\Controllers\BillingOfficerController@revision_basic_pension_view_page')->name('billing_officer_revision_basic_pension_view');

// Generate Bill
Route::post('billing-officer/application/submit-net-pension-calculation/', 'App\Http\Controllers\GenerateBillController@generate_bill_sheet')->name('generate_bill_sheet');
// Bill Download
Route::get('billing-officer/application/bill-download', 'App\Http\Controllers\BillingOfficerController@download_bill')->name('download_bill');
// Billing Historyx
Route::any('billing-officer/application/billing-history/', 'App\Http\Controllers\BillingOfficerController@billing_history')->name('billing_history');

// Service Pensioner Application View - Pension Unit User
Route::get('pention-unit-head/service-pension/application-details/{appID}', 'App\Http\Controllers\ApplicationViewController@sp_application_details')->name('pension_unit_head_sp_application_view');
// Family Pensioner Application View - Pension Unit User
Route::get('pention-unit-head/family-pension/application-details/{appID}', 'App\Http\Controllers\ApplicationViewController@fp_application_details')->name('pension_unit_head_fp_application_view');
// Service Pensioner History Application View - Pension Unit User
Route::get('pention-unit-head/service-pension/history/application-details/{appID}', 'App\Http\Controllers\ApplicationViewController@sp_application_details')->name('pension_unit_head_sp_history_application_view');
// Family Pensioner History Application View - Pension Unit User
Route::get('pention-unit-head/family-pension/history/application-details/{appID}', 'App\Http\Controllers\ApplicationViewController@fp_application_details')->name('pension_unit_head_fp_history_application_view');
// Service Pensioner Application View - Billing Officer
Route::get('billing-officer/service-pension/application-details/{appID}', 'App\Http\Controllers\ApplicationViewController@sp_application_details')->name('billing_officer_sp_application_view');
// Family Pensioner Application View - Billing Officer
Route::get('billing-officer/family-pension/application-details/{appID}', 'App\Http\Controllers\ApplicationViewController@fp_application_details')->name('billing_officer_fp_application_view');
// Service Pensioner Application Approved View - Billing Officer
Route::get('billing-officer/service-pension/approved/application-details/{appID}', 'App\Http\Controllers\ApplicationViewController@sp_application_details')->name('billing_officer_sp_approved_application_view');
// Family Pensioner Application Approved View - Billing Officer
Route::get('billing-officer/family-pension/approved/application-details/{appID}', 'App\Http\Controllers\ApplicationViewController@fp_application_details')->name('billing_officer_fp_approved_application_view');


Route::get('form16/show', [Form16MasterController::class, 'fetch_form16_show'])->name('form16_show');
 
//Clear Cache facade value:
Route::get('clear-all', function() {
  $exitCode = Artisan::call('cache:clear');
  $exitCode = Artisan::call('optimize');
  $exitCode = Artisan::call('route:cache');
  $exitCode = Artisan::call('route:clear');
  $exitCode = Artisan::call('view:clear');
  $exitCode = Artisan::call('config:cache');
  return '<h1>All Things cleared</h1>';
});
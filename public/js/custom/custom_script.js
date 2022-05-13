$("body").on("keypress paste",".only_number", function (e) {
    var regex = new RegExp("^[0-9]$");
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    if (regex.test(str)) {
        return true;
    }
    e.preventDefault();
    return false;
});

$("body").on("keypress paste",".alpha_numeric", function (e) {
    var regex = new RegExp("^[a-zA-Z0-9 ]$");
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    if (regex.test(str)) {
        return true;
    }
    e.preventDefault();
    return false;
});
// Alpha Numeric NoSpace
$("body").on("keypress paste",".anns", function (e) {
    var regex = new RegExp("^[a-zA-Z0-9]$");
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    if (regex.test(str)) {
        return true;
    }
    e.preventDefault();
    return false;
});

$("body").on("keypress paste",".alpha", function (e) {
    var regex = new RegExp("^[a-zA-Z ]$");
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    if (regex.test(str)) {
        return true;
    }
    e.preventDefault();
    return false;
});

$("body").on("keypress paste",".alpha_ns", function (e) {
    var regex = new RegExp("^[a-zA-Z]$");
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    if (regex.test(str)) {
        return true;
    }
    e.preventDefault();
    return false;
});

$("body").on("keypress paste",".pancard", function (e) {
    var regex = new RegExp("^[A-Za-z0-9]$");
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    if (regex.test(str)) {
        return true;
    }
    e.preventDefault();
    return false;
});

// $(".yearpicker").yearpicker({
//   year: 2020,
//   startYear: 1990,
//   endYear: 2020
// });


// Code for custom choose field
$(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});

$('body').on("cut copy paste",".ccp_restrict",function(e) {
  e.preventDefault();
});

$("body").on("keypress paste",".emailValidation", function (event) {

    if(event.charCode == 45|| event.charCode == 95||event.charCode == 8 || event.charCode == 9|| event.charCode == 64|| event.charCode == 46||(event.charCode >= 48 && event.charCode <= 57) ||(event.charCode >= 97 && event.charCode <= 122)||(event.charCode >= 65 && event.charCode <= 90)){
        return true;
    }
    event.preventDefault();
    return false;
});

$("body").on("keypress paste",".verify_email_address", function (e) {
    var regex = new RegExp("/^[a-zA-Z0-9]+(\.[_a-zA-Z0-9]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,15})$/");
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    console.log(regex.test(this.value));
    if (regex.test(str)) {
        return true;
    }
    e.preventDefault();
    return false;
});

/*Address*/
$("body").on("keypress paste",".check_my_address", function (e) {
    var regex = new RegExp("^[A-Za-z0-9 ,/-]");
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    console.log(regex.test(this.value));
    if (regex.test(str)) {
        return true;
    }
    e.preventDefault();
    return false;
});

$("body").on("keypress paste",".anch", function (e) {
    var regex = new RegExp("^[A-Za-z0-9/-]");
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    console.log(regex.test(this.value));
    if (regex.test(str)) {
        return true;
    }
    e.preventDefault();
    return false;
});
// With Space
$("body").on("keypress paste",".anchs", function (e) {
    var regex = new RegExp("^[A-Za-z0-9 /-]");
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    console.log(regex.test(this.value));
    if (regex.test(str)) {
        return true;
    }
    e.preventDefault();
    return false;
});

$("body").on("keypress paste",".remark_box", function (e) {
    var regex = new RegExp("^[A-Za-z0-9 .,/-]");
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    console.log(regex.test(this.value));
    if (regex.test(str)) {
        return true;
    }
    e.preventDefault();
    return false;
}); 

$("body").on("keypress paste",".amount_type", function (e) {
    var regex = new RegExp("^[0-9.]");
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    console.log(regex.test(this.value));
    if (regex.test(str)) {
        return true;
    }
    e.preventDefault();
    return false;
});

$("body").on("keypress paste",".ppo_number_format", function (e) {
    var regex = new RegExp("^[0-9/]");
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    console.log(regex.test(this.value));
    if (regex.test(str)) {
        return true;
    }
    e.preventDefault();
    return false;
});
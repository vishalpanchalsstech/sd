<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Responsive Admin Dashboard Template">
    <meta name="keywords" content="admin,dashboard">
    <meta name="author" content="stacks">
    <!-- The above 6 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <!-- Title -->
    <title>Driver Registration</title>


    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
    <link href="<?php echo base_url() ?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url() ?>assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo base_url() ?>assets/plugins/icomoon/style.css" rel="stylesheet">
    <link href="<?php echo base_url() ?>assets/plugins/uniform/css/default.css" rel="stylesheet"/>
    <link href="<?php echo base_url() ?>assets/plugins/switchery/switchery.min.css" rel="stylesheet"/>

    <!-- Theme Styles -->
    <link href="<?php echo base_url() ?>assets/css/space.min.css" rel="stylesheet">
    <link href="<?php echo base_url() ?>assets/css/custom.css" rel="stylesheet">

    <!-- <script src="<?php echo base_url();?>assets/js/jquery.validate.min.js"></script> -->
</head>


<body>


<!-- Page Container -->
<div class="page-container">
    <!-- Page Inner -->
    <div class="page-inner login-page">
        <div id="main-wrapper" class="container-fluid">
            <div class="row">
                <div class="col-sm-6 col-md-3 login-box">

                    <div class="form-group"><?php  echo validation_errors(); ?></div>
                    <?php if(isset($msg)){ echo $msg; } ?>
                    <?php if(isset($msgf)){ echo $msgf;} ?>

                    <h4 class="login-title">Driver Registration Form :</h4>
                    <form id="Signup_Form" name="Signup_Form" method="post" action="<?php echo base_url(); ?>driverregister/insert_registration" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="Name">Name :</label>
                                <input type="text" class="form-control" id="name" name="name">
                                <?php echo form_error('name'); ?>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail">Email address :</label>
                                <input type="email" class="form-control" id="email" name="email" onchange="check_duplicate_email()">
                                <p id="msg_dupli" class="error-message"></p>
                                <?php echo form_error('email'); ?>
                            </div>

                            <div class="form-group">
                                <label for="Password">Password :</label>
                                <input type="password" class="form-control" id="Password" name="Password"  minlength="6">
                                <?php echo form_error('Password'); ?>
                            </div>

                            <div class="form-group">
                                <label for="Password">Confirm Password :</label>
                                <input type="password" class="form-control" id="Conf_Password" name="Conf_Password" minlength="6">
                                <?php echo form_error('Conf_Password'); ?>
                            </div>

                            <div class="form-group">
                                <label for="Building">Building :</label>
                                <input type="text" class="form-control" id="Building" name="Building"  placeholder="Building">
                                <?php echo form_error('Building'); ?>
                            </div>

                            <div class="form-group">
                                <label for="Street">Street :</label>
                                <input type="text" class="form-control" id="Street" name="Street"  placeholder="Street*">
                                <?php echo form_error('Street'); ?>
                            </div>

                            <div class="form-group">
                                <label for="Country">Country :</label>
                                <div class="custom-country">
                                    <input type="text" id="country" autocomplete="off" class="form-control"  name="country"/>
                                </div>
                                <?php echo form_error('country'); ?>
                            </div>

                            <div class="form-group">
                                <label for="Suburb">Suburb :</label>
                                <input type="text" class="form-control" id="city" name="Suburb" autocomplete="off"  placeholder="Suburb*">
                                <div id="suburb-div" class="" style="display:none;">
                                    <ul></ul>
                                </div>
                                <?php echo form_error('Suburb'); ?>
                            </div>

                            <div class="form-group">
                                <label for="state">State/City :</label>
                                <input type="text" class="form-control" id="state" name="State"  placeholder="State/City*">
                                <?php echo form_error('State'); ?>
                            </div>

                            <div class="form-group">
                                <label for="postalcode">Postcode :</label>
                                <input type="text" class="form-control" id="postalcode" name="Postcode"  placeholder="Postcode*">
                                <?php echo form_error('postalcode'); ?>
                            </div>

                            <div class="form-group">
                                <label for="documentImage">Profile Image :</label>
                                <input type="file" class="file-type" id="file2" name="ProfileImg" onchange="validatefile(this);">
                                <div id="shfilerr"></div>
                                <?php echo form_error('ProfileImg'); ?>
                            </div>

                            <div class="form-group">
                                <label for="PhoneNo">Phone No :</label>
                                <input type="text" class="form-control" id="PhoneNo" name="PhoneNo"  minlength="10" maxlength="12">
                                <?php echo form_error('PhoneNo'); ?>
                            </div>

                            <div class="form-group">
                                <label for="licenceNo">Licence No :</label>
                                <input type="text" class="form-control" id="licenceNo" name="licenceNo">
                                <?php echo form_error('licenceNo'); ?>
                            </div>

                            <div class="form-group">
                                <label for="vehiclename">Vehicle Name :</label>
                                <input type="text" class="form-control" id="vehiclename" name="vehiclename">
                                <?php echo form_error('vehiclename'); ?>
                            </div>

                            <div class="form-group">
                                <label for="vehicleno">Vehicle No :</label>
                                <input type="text" class="form-control" id="vehicleno" name="vehicleno">
                                <?php echo form_error('vehicleno'); ?>
                            </div>

                            <div class="form-group">
                                <label for="documentImage">Document Image :</label>
                                <input type="file" class="file-type" id="file" name="DocumentImg" onchange="svalidatefile(this);">
                                <div id="shfilerr2"></div>
                                <?php echo form_error('DocumentImg'); ?>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6">
                                    <button class="btn btn-primary" type="submit">Register</button>
                                </div>
                                <div class="col-md-6">
                                    <a href="<?php echo base_url(); ?>login" class="btn btn-default">Login</a><br>
                                </div>
                            </div>
                            <!--<a href="index.html" class="forgot-link">Forgot password?</a>-->
                    </form>
                </div>
            </div>
        </div>
    </div><!-- /Page Content -->
</div><!-- /Page Container -->

<link href="<?php echo base_url() ?>assets/plugins/jquery/jquery-ui.css" rel="Stylesheet">
<script src="<?php echo base_url() ?>assets/plugins/jquery/jquery-1.11.2.min.js"></script>
<!--<script src="<?php echo base_url() ?>assets/plugins/jquery/jquery-3.1.0.min.js"></script>-->
<script src="<?php echo base_url() ?>assets/plugins/jquery/jquery-ui.js" ></script>
<!-- Javascripts -->
<link href="<?php echo base_url() ?>assets/css/easy-autocomplete/easy-autocomplete.css" rel="stylesheet">
<link href="<?php echo base_url() ?>assets/css/easy-autocomplete/easy-autocomplete.min.css" rel="stylesheet">

<link href="<?php echo base_url() ?>assets/css/easy-autocomplete/easy-autocomplete.themes.min.css" rel="stylesheet">
<script src="<?php echo base_url() ?>assets/js/custom.js"></script>

<script src="<?php  echo base_url() ?>assets/js/jquery.easy-autocomplete.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/jquery.easy-autocomplete.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/uniform/js/jquery.uniform.standalone.js"></script>
<!-- <script src="<?php echo base_url() ?>assets/plugins/jquery-validation/jquery.validate.min.js"></script> -->
<script src="<?php echo base_url() ?>assets/plugins/switchery/switchery.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/space.min.js"></script>



<script type="text/javascript">

    $(document).ready(function(){

        var availableCountry = [
            <?php 
            if(isset($country_details)){ foreach ($country_details as $country) {
            echo '"'.$country->Name.'-'.$country->SortName.'",';
        }
        } ?>
        ];
        $("#country").autocomplete({
            source: function(req, response) {
                var results = $.ui.autocomplete.filter(availableCountry, req.term);
                response(results.slice(0, 8));//for getting 5 results
            }
        });
    });
</script>
<style>
    .login-box
    {
        width:30% !important;
        height:auto;
    }
</style>
<script>
    var base_url = "<?php echo base_url();?>";

    	$('#PhoneNo').keyup(function()
        {
            if (/\D/g.test(this.value))
            {
              // Filter non-digits from input value.
              this.value = this.value.replace(/\D/g, '');
            }
        });


    /*********** Driver Registration form validation @ Krushna @ **************/

    $(function()
    {
        $("form[name='Signup_Form']").validate({
            errorClass   : "error-message",
            errorElement : "p",

            rules:
            {
                name: "required",
                email:{
                        required: true,
                        email: true
                      },
             Password:{
                        required: true,
                      },

           Conf_Password:{
                            required: true,
                            equalTo: "#Password"
                         },
                licenceNo: "required",
                PhoneNo: "required",
                DocumentImg:{
                        required: true

                     },
                ProfileImg:{
                        required: true
                     },
                     vehiclename:"required",
                     vehicleno:"required",
                     Street: "required",
                     country: "required",
                     Suburb:"required",
                     State:"required",
            },

            messages:
            {
              name:"Name field is required.",
              email:"Email is required.",
              licenceNo:"licenceNo field is required.",
              DocumentImg:"Document Image is required.",
              ProfileImg:"Profile Image is required.",
              vehiclename:"Vehicle Name is required.",
              vehicleno:"Vehicle No is required.",
              Street:"Street/city  Required",
              country:"Country Required",
              Suburb:"Suburb Required or not valid select from suggestion",
            },

            submitHandler: function(form)
            {
                var dupli_emal = check_duplicate_email();
                if(dupli_emal == '1')
                {
                    return false;
                }
                else
                {
                    if($('form#Signup_Form .error-class').length < 1)
                    {
                        form.submit();
                    }
                    else
                    {
                            return false;
                    }
                }
            }

        });
    });

    /*********** Js for Check Email Duplication gose here @ Krushna @ **************/

    function check_duplicate_email()
    {

        var email = $('#email').val();
        var myAjaxValue = null;
        $.ajax({
            url: base_url+"driverregister/validate_email",
            type: 'POST',
            'async': false,
            'global': false,
            data: {
                email: email,
            },
            success: function(res)
            {
                if(res == '0')
                {
                    $('#email').addClass('valid').removeClass('error-message');
                    $('#msg_dupli').html('');
                    $('#msg_dupli').css("display", "none");
                    myAjaxValue = 0;
                }
                else
                {
                    $('#email').addClass('error-message').removeClass('valid');
                    $('#msg_dupli').html('This email is already used. Please choose another.');
                    $('#msg_dupli').css("display", "block");
                    myAjaxValue = 1;
                }
            }
        });
        return myAjaxValue;
    }
    /***** Restrict File Extensions While upload File 2 ******/
    function svalidatefile(oInput) {

        var _validFileExtensions = [".jpg", ".jpeg",".png"];
        if (oInput.type == "file")
        {
            var sFileName = oInput.value;
            if (sFileName.length > 0)
            {
                var blnValid = false;
                for (var j = 0; j < _validFileExtensions.length; j++)
                {
                    var sCurExtension = _validFileExtensions[j];
                    if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase())
                    {
                        blnValid = true;
                        $('#shfilerr2').html('');
                        break;
                    }
                }

                if (!blnValid) {

                    //alert("Sorry, " + sFileName + " is invalid, allowed extensions are: " + _validFileExtensions.join(", "));
                    $('#shfilerr2').html('<label id="Name-error" class="error" for="Name"><strong>Sorry file is invalid, allowed extensions are: .jpg, .jpeg, .png  </strong></label>');
                    oInput.value = "";
                    return false;
                }
            }
        }
        return true;
    }



</script>
</body>
</html>
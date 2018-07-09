<div class="login-box" style="margin: 4% auto;">
 <div class="login-logo">
   <b>Quick</b> Chat
  </div>
 
  <!-- /.login-logo -->
  <div class="login-box-body">
		  <?php if(isset($msg)) echo $msg; ?>
		
    <p class="login-box-msg">Change Password</p>

    <form name="changepassword"  method="post" action="<?php echo base_url(); ?>changepassword/change_password_check">
       <div class="form-group has-feedback">
		<input id="old_pwd" type="password" name="old_pwd"  class="form-control" placeholder="Old Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" id="new_pwd" name="new_pwd" class="form-control" placeholder="New Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
	  <div class="form-group has-feedback">
        <input type="password" id="cnf_pwd" name="cnf_pwd" class="form-control" placeholder="Confirm Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">     
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-block btn-success">Sign In</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

  </div>
  <!-- /.login-box-body -->
</div>

<!-- /.login-box -->



<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<style>
	
	.modal-login {
		max-width: 400px;
	}
	.modal-login .modal-content {
		padding: 20px;
		border-radius: 5px;
		border: none;
	}
	.modal-login .modal-header {
		border-bottom: none;
        position: relative;
		justify-content: center;
	}
	.modal-login .close {
        position: absolute;
		top: -10px;
		right: -10px;
	}
	.modal-login h4 {
		color: #636363;
		text-align: center;
		font-size: 26px;
		margin-top: 0;
	}
	.modal-login .modal-content {
		color: #999;
		border-radius: 1px;
    	margin-bottom: 15px;
        background: #fff;
		border: 1px solid #f3f3f3;
        box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
        padding: 25px;
    }
	.modal-login .form-group {
		margin-bottom: 20px;
	}
	.modal-login label {
		font-weight: normal;
		font-size: 13px;
	}
	.modal-login .form-control {
		min-height: 38px;
		padding-left: 5px;
		box-shadow: none !important;
		border-width: 0 0 1px 0;
		border-radius: 0;
	}
	.modal-login .form-control:focus {
		border-color: #ccc;
	}
	.modal-login .input-group-addon {
		max-width: 42px;
		text-align: center;
		background: none;
		border-width: 0 0 1px 0;
		padding-left: 5px;
		border-radius: 0;
	}
    .modal-login .btn {        
        font-size: 16px;
        font-weight: bold;
		/*background: #19aa8d;*/
        border-radius: 3px;
		border: none;
		min-width: 140px;
        outline: none !important;
    }
	.modal-login .btn:hover, .modal-login .btn:focus {
		/*background: #179b81;*/
	}
	.modal-login .hint-text {
		text-align: center;
		padding-top: 5px;
		font-size: 13px;
	}
	.modal-login .modal-footer {
		color: #999;
		border-color: #dee4e7;
		text-align: center;
		margin: 0 -25px -25px;
		font-size: 13px;
		justify-content: center;
	}
	.modal-login a {
		color: #fff;
		text-decoration: underline;
	}
	.modal-login a:hover {
		text-decoration: none;
	}
	.modal-login a {
		color: #19aa8d;
		text-decoration: none;
	}	
	.modal-login a:hover {
		text-decoration: underline;
	}
	.modal-login .fa {
		font-size: 21px;
	}
	.modal-backdrop {
   background-color: #f2f2f2!important;
   opacity: 1!important
	}
	.has-error .form-control{
		border-color:#a94442!important
	}
	.btn-primary{
		background-image: -webkit-linear-gradient(top, #<?php echo $this->config->item('pdm2Color_bg1');?> 0%, #<?php echo $this->config->item('pdm2Color_bg2');?> 100%);
	  background-image: -o-linear-gradient(top, #<?php echo $this->config->item('pdm2Color_bg1');?> 0%, #<?php echo $this->config->item('pdm2Color_bg2');?> 100%);
	  background-image: -webkit-gradient(linear, left top, left bottom, from(#<?php echo $this->config->item('pdm2Color_bg1');?>), to(#<?php echo $this->config->item('pdm2Color_bg2');?>));
	  background-image: linear-gradient(to bottom, #<?php echo $this->config->item('pdm2Color_bg1');?> 0%, #<?php echo $this->config->item('pdm2Color_bg2');?> 100%);
	  background-repeat: repeat-x;
	  border-color: #<?php echo $this->config->item('pdm2Color_line');?>;
	  color:#fff
	}
	.btn-primary:hover,
	.btn-primary:focus {
	  background-color: #<?php echo $this->config->item('pdm2Color_bg2');?>;
	  background-position: 0 -15px;
	  color:#fff;
	  border-color: #<?php echo $this->config->item('padm2Color_over_line');?>;
	}
	.btn-primary:active,
	.btn-primary.active {
	  background-color: #<?php echo $this->config->item('pdm2Color_bg_active');?>;
	  border-color: #<?php echo $this->config->item('pdm2Color_over_line');?>;
	}
</style>

<script>
	$(function(){
		$('#login').modal({
			backdrop: 'static',
  		keyboard: false
		});
	});
</script>

<!-- Modal HTML -->
<div id="login" class="modal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
	<div class="modal-dialog modal-login">
		<div class="modal-content">
			<div class="modal-header">				
				<h4 class="modal-title" style="line-height:1">
					<?php echo $this->config->item('siteLogo');?><br />
					<small style="font-size:12px"><?php echo $this->config->item('siteLogoSmall');?></small>
				</h4>
			</div>
			<div class="modal-body">
				<form action="<?php echo base_url();?>index.php/login/process" method="post" class="form-horizontal" data-toggle="validator" role="form">
						<div class="form-group required">
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-user"></i></span>
								<input type="text" value="admin" class="form-control" id="userid" name="userid" placeholder="아이디를 입력하세요." autocomplete="off" required>
							</div>
							<span class="help-block with-errors"></span>
						</div>
						<div class="form-group required">
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-lock"></i></span>
								<input type="password" value="admin1234" class="form-control" id="password" name="password" placeholder="비밀번호를 입력하세요." required>
							</div>
							<span class="help-block with-errors"></span>
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-primary btn-block btn-lg">로그인</button>
						</div>
						<p class="hint-text">관리자에게 아이디를 신청하세요.</p>
				</form>
			</div>
		</div>
		<?php if(! is_null($msg)) echo '<div class="alert alert-danger alert-dismissable text-center" style="position:relative" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="right:10px;top:10px"><span aria-hidden="true">&times;</span></button>'.$msg.'</div>';?>		
			
	</div>
</div>





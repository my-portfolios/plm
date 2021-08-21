<?php
defined('BASEPATH') OR exit('No direct script access allowed');
////
$user = ['User','User_write'];
$bg_user = '';
if(in_array($this->uri->segment(2), $user)){
	$bg_user = 'bg-gray';
	$write_txt = '유저등록';
}
////
$org = ['Org','Org_write'];
$bg_org = '';
if(in_array($this->uri->segment(2), $org)){
	$bg_org = 'bg-gray';
	$write_txt = '조직도등록';
}
////
$comp = ['Comp','Comp_write'];
$bg_comp = '';
if(in_array($this->uri->segment(2), $comp)){
	$bg_comp = 'bg-gray';
	$write_txt = '거래처등록';
}
////
$format = ['Format','Format_write'];
$bg_format = '';
if(in_array($this->uri->segment(2), $format)){
	$bg_format = 'bg-gray';
	$write_txt = '양식등록';
}
////
$board = ['Board','Board_write'];
$bg_board = '';
if(in_array($this->uri->segment(2), $board)){
	$bg_board = 'bg-gray';
	$write_txt = '게시판등록';
}
$group = ['Group','Group_write'];
$bg_group = '';
if(in_array($this->uri->segment(2), $group)){
	$bg_group = 'bg-gray';
	$write_txt = '그룹등록';
}
////

?>
<script>
	$(function(){
		$(document).on('click','.btn_left_write',function(){
			var m = $('.list-group-item.bg-gray').attr('m');
			location.href="<?php echo site_url();?>/<?php echo $this->uri->segment(1)?>/"+m+"_write";
		});
		$('.list-group-item').click(function(){
			var m = $(this).attr('m');
			location.href="<?php echo site_url();?>/<?php echo $this->uri->segment(1)?>/"+m;
		});
	});
</script>
<div id="wp_left" class="gray_border_right">
		
		<?php $this->load->view('/public/userInfo.php');?>
		
		<div class="text-center pt-20 ptb-20">
			<div class="btn-group btn-group-toggle" data-toggle="buttons">
				<button type="button" class="btn btn_left_write btn-dark mt-10">
					<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
					 <?php echo $write_txt?>
				</button>
			</div>
		</div>
		
		<ul class="list-group-c list-group" style="">
			<li class="list-group-item s_y <?php echo $bg_format; ?>" m="Format" style="cursor:pointer;" data-toggle="tooltip" data-placement="top" title="양식관리">
				양식관리
		    <i class="pull-right fa fa-ellipsis-h text-default" style="font-size:14px"></i>
		  </li>
		<?php if($this->session->userdata('userauth') == 'admin'){ ?>
		  <li class="list-group-item s_y <?php echo $bg_user; ?>" m="User" style="cursor:pointer;" data-toggle="tooltip" data-placement="top" title="유저관리">
				유저관리
		    <i class="pull-right fa fa-ellipsis-h text-default" style="font-size:14px"></i>
		  </li>
		  <li class="list-group-item s_y <?php echo $bg_org; ?>" m="Org" style="cursor:pointer;" data-toggle="tooltip" data-placement="top" title="조직도관리">
				조직도관리
		    <i class="pull-right fa fa-ellipsis-h text-default" style="font-size:14px"></i>
			</li>
			<li class="list-group-item s_y <?php echo $bg_group; ?>" m="Group" style="cursor:pointer;" data-toggle="tooltip" data-placement="top" title="그룹관리">
				그룹관리
		    <i class="pull-right fa fa-ellipsis-h text-default" style="font-size:14px"></i>
		  </li>
		  <li class="list-group-item s_y <?php echo $bg_comp; ?>" m="Comp" style="cursor:pointer;" data-toggle="tooltip" data-placement="top" title="거래처관리">
				거래처관리
		    <i class="pull-right fa fa-ellipsis-h text-default" style="font-size:14px"></i>
		  </li>
		  <li class="list-group-item s_y <?php echo $bg_board; ?>" m="Board" style="cursor:pointer;" data-toggle="tooltip" data-placement="top" title="게시판관리">
				게시판관리
		    <i class="pull-right fa fa-ellipsis-h text-default" style="font-size:14px"></i>
		  </li>
		 <?php } ?>
		</ul>
		
		
</div>
		
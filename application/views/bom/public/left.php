<?php
defined('BASEPATH') OR exit('No direct script access allowed');
////
$part = ['Part','Part_write'];
$bg_part = '';
if(in_array($this->uri->segment(2), $part)){
	$bg_part = 'bg-gray';
	$write_txt = '부품등록';
}
////
$cate = ['Cate','Cate_write'];
$bg_cate = '';
if(in_array($this->uri->segment(2), $cate)){
	$bg_cate = 'bg-gray';
	$write_txt = '카테고리등록';
}
////
$pdt = ['Pdt','Pdt_write'];
$bg_pdt = '';
if(in_array($this->uri->segment(2), $pdt)){
	$bg_pdt = 'bg-gray';
	$write_txt = '제품등록';
}
////
$mapping = ['Mapping','Mapping_write'];
$bg_mapping = '';
if(in_array($this->uri->segment(2), $mapping)){
	$bg_mapping = 'bg-gray';
	$write_txt = '프로젝트매핑';
}
////
?>
<script>
	$(function(){
		if('<?php echo $this->uri->segment(2); ?>' == 'Mapping' || '<?php echo $this->uri->segment(2); ?>' == 'Mapping_write'){
			$('.btn_left_write').addClass('disabled');
		}
		$(document).on('click','.btn_left_write',function(){
			var m = $('.list-group-item.bg-gray').attr('m');
			if('<?php echo $this->uri->segment(2); ?>' != 'Mapping' && '<?php echo $this->uri->segment(2); ?>' != 'Mapping_write'){
				location.href="<?php echo site_url();?>/<?php echo $this->uri->segment(1)?>/"+m+"_write";
			}else{
				bootbox.alert({
				size:'small',
				message : '프로젝트매핑은 PMS를 등록하세요.',
				buttons: {
					ok: {
						label: '확인',
						className: "btn-<?php echo $this->config->item($this->uri->segment(1).'Color')?>"
					}
				}
			});
			}
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
			<li class="list-group-item s_y <?php echo $bg_part; ?>" m="Part" style="cursor:pointer;" data-toggle="tooltip" data-placement="top" title="부품정보관리">
				부품정보관리
		    <i class="pull-right fa fa-ellipsis-h text-default" style="font-size:14px"></i>
		  </li>
		  <li class="list-group-item s_y <?php echo $bg_cate; ?>" m="Cate" style="cursor:pointer;" data-toggle="tooltip" data-placement="top" title="카테고리관리">
				카테고리관리
		    <i class="pull-right fa fa-ellipsis-h text-default" style="font-size:14px"></i>
		  </li>
		  <li class="list-group-item s_y <?php echo $bg_pdt; ?>" m="Pdt" style="cursor:pointer;" data-toggle="tooltip" data-placement="top" title="제품정보관리">
				제품정보관리
		    <i class="pull-right fa fa-ellipsis-h text-default" style="font-size:14px"></i>
		  </li>
		  <li class="list-group-item s_y <?php echo $bg_mapping; ?>" m="Mapping" style="cursor:pointer;" data-toggle="tooltip" data-placement="top" title="프로젝트매핑">
				프로젝트매핑
		    <i class="pull-right fa fa-ellipsis-h text-default" style="font-size:14px"></i>
		  </li>
		</ul>
		
		
</div>
		
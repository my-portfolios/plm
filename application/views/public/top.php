<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//권한별 버튼 disabled
$pem = '';
if($this->session->userdata('userauth') == 'admin' || $this->session->userdata('userauth') == 'emp'){
	$pem = '';
}else{
	$pem = 'disabled';
}
?>
<script>
	function orgView(id){
		PopupCenter("/index.php/admin/Org_view?id="+id, 'Org_view', '1000', '630');
	}
	function newCnt(tables,delyns){
		//새글 count 가져오기
		$.ajax({
			type: 'post',
			dataType: 'json',
			url: '<?php echo base_url();?>index.php/Common/getNewCnt',
			data: {table : tables, delyn : delyns},
			success: function (data) {
					if(data > 0){
				 	$('[table='+tables+']').append("<div class='topCnt'>"+data+"</div>");
					}
			},
			error: function (request, status, error) {
				console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
			}
		});
	}
	
	$(function(){
		$(document).on('click','.topMenu0',function(){
			location.href="<?php echo site_url();?>/dash/Main";
		});
		$(document).on('click','.topMenu1',function(){
			location.href="<?php echo site_url();?>/pdm2/Main";
		});
		$(document).on('click','.topMenu2',function(){
			location.href="<?php echo site_url();?>/rm/Main";
		});
		$(document).on('click','.topMenu3',function(){
			location.href="<?php echo site_url();?>/pms/Main";
		});
		$(document).on('click','.topMenu4',function(){
			
			location.href="<?php echo site_url();?>/board/Main";
			
		});
		$(document).on('click','.topMenu5',function(){
			location.href="<?php echo site_url();?>/bom/Part";
		});
		$(document).on('click','.btn_admin',function(){
			location.href="<?php echo site_url();?>/admin/Format";
		});
		
		$(document).on('click','#wp_top h1',function(){
			if("<?php echo $this->uri->segment(1);?>" == 'bom'){
				location.href="<?php echo site_url();?>/<?php echo $this->uri->segment(1);?>/Part";
			}else if("<?php echo $this->uri->segment(1);?>" == 'admin'){
				location.href="<?php echo site_url();?>/<?php echo $this->uri->segment(1);?>/Format";
			}else{
				location.href="<?php echo site_url();?>/<?php echo $this->uri->segment(1);?>/Main";
			}
		});
		<?php if($pem == ''){?>
		//newCnt('PLM_PDM_FILE','PF_DEL_YN');//param 테이블 이름
		//newCnt('PLM_PMS','PP_DEL_YN');//param 테이블 이름
		<?php } ?>
		//newCnt('PLM_RM','PR_DEL_YN');//param 테이블 이름
		//newCnt('PLM_BOARD_CONTENTS','CONTS_DEL_YN');//param 테이블 이름
	});
</script>
<div id="wp_top" class="bg-<?php echo $this->config->item($this->uri->segment(1).'Color')?>">
	<h1><span class="glyphicon glyphicon-<?php echo $this->config->item($this->uri->segment(1).'MainIcon')?>"></span>&nbsp;<?php echo $this->config->item($this->uri->segment(1).'Title')?></h1>
	
	<div class="top_menu text-center">
		<button type="button" class="btn topMenu0 btn-transparent <?php if($this->uri->segment(1)=='dash'){echo 'btn-'.$this->config->item('rmColor').' active'; }?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->config->item('dashTitle'); ?>">
			<span class="glyphicon glyphicon-th-large" aria-hidden="true"></span>
		</button>
		<button type="button" table="PLM_RM" class="btn topMenu2 btn-transparent <?php if($this->uri->segment(1)=='rm'){echo 'btn-'.$this->config->item('rmColor').' active'; }?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->config->item('rmTitle'); ?>">
			<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
		</button>
		<button type="button" table="PLM_BOARD_CONTENTS" class="btn topMenu4 btn-transparent <?php if($this->uri->segment(1)=='board'){echo 'btn-'.$this->config->item('boardColor').' active'; }?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->config->item('boardTitle'); ?>">
			<span class="glyphicon glyphicon-link" aria-hidden="true"></span>
		</button>
		
		<button <?php echo $pem;?> type="button" table="PLM_PMS" class="btn topMenu3 btn-transparent <?php if($this->uri->segment(1)=='pms'){echo 'btn-'.$this->config->item('pmsColor').' active'; }?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->config->item('pmsTitle'); ?>">
			<span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
		</button>
		
		<button <?php echo $pem;?> type="button" class="btn topMenu5 btn-transparent <?php if($this->uri->segment(1)=='bom'){echo 'btn-'.$this->config->item('bomColor').' active'; }?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->config->item('bomTitle'); ?>">
			<span class="glyphicon glyphicon-paperclip" aria-hidden="true"></span>
		</button>
		<button <?php echo $pem;?> type="button" table="PLM_PDM_FILE" class="btn topMenu1 btn-transparent <?php if($this->uri->segment(1)=='pdm2' ){echo 'btn-'.$this->config->item('pdm2Color').' active'; }?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->config->item('pdm2Title'); ?>">
			<span class="glyphicon glyphicon-cloud" aria-hidden="false"></span>
		</button>
	</div>
	
	<ul class="top_etc">
		<li>
			<button type="button" class="btn btn-default btn-xs" onclick="infoView('<?php echo $_SESSION['userid']?>')">
				<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
				내정보
			</button>
		</li>
		<li>
			<button type="button" class="btn btn-default btn-xs" onclick="orgView('')">
				<i class="fa fa-sitemap"></i>
				조직도
			</i>
		</li>
		<?php if($this->session->userdata('userauth') == 'admin' || $this->session->userdata('userauth') == 'emp'){ ?>
		<li>
			<button type="button" class="btn btn-default btn-xs btn_admin">
				<span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
				관리
			</button>
		</li>
		<?php } ?>
	</ul>
	
</div>
		
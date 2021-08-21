<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<style>
.bootstrap-tagsinput input{
	display:none;
}
.bootstrap-tagsinput span span{
	display:none;
}
.label-info{
	background:#b1b1b1
}
.bootstrap-tagsinput{
	border:none;
	box-shadow:none;
	padding: 5px 0
}
#wp{
	min-width:auto!important
}
.viewOnly,
.gdfCell.edit .teamworkIcon
{
	display:none!important
}
.colorByStatus{
	max-height:100%!important
}

html{
	overflow:hidden!important
}
</style>

	<form id="frm_pms_write" name="frm_pms_write" data-toggle="validator" role="form" action="" method="post" >
		<input type="hidden" id="PP_ID" name="PP_ID" value="<?php if($list){ echo $list->PP_ID; } ?>"/>
		<!--
		<div class="gray_border_bottom pb-20">
		  <h3>프로젝트 정보 <small>Project</small></h3>
		</div>
		<br />
		
		<div class="form-group required ">
			<label for="">프로젝트명</label>
			<div class="view_inputs gray_border_bottom pb-15">
				<?php if($list){ echo $list->PP_NM; } ?>
			</div>
		</div>
		<div class="form-group required ">
			<label for="">담당자</label>
			<div class="view_inputs gray_border_bottom pb-15">
				<?php 
				if( $empList != null ){
					$emps = '';
					foreach( $empList as $data ){
						$emps .= $data->EMP_NM . '(' . $data->EMP_ID . ')  ';
					}
					echo $emps;
				} 
				?>
			</div>
		</div>
		
		<div class="form-group required">
			<label for="PF_KEYWORD">키워드</label>
			<div>
				<?php 

				if( $keywordList != null ){
					foreach( $keywordList as $data ){

				?>
					<script>
					$(function(){
						$('#PF_KEYWORD').tagsinput('add', '<?php echo $data->PK_NM; ?>');
					});
					</script>
				<?php
					}

				}
				?>
				<input id="PF_KEYWORD" name="PF_KEYWORD" type="text" data-role="tagsinput" class="form-control" placeholder="" aria-label="..." value="" readonly>
			</div>
		</div>
		-->
	</form>
	<?php include($_SERVER["DOCUMENT_ROOT"]."/application/views/pms/Gantt.php"); ?>	<!-- 간트차트 -->
	
	
	
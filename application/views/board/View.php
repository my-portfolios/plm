<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script>
$(window).load(function(){
	$('#frm_board_view .snote').summernote({
		height: 300,          // 기본 높이값
		minHeight: null,      // 최소 높이값(null은 제한 없음)
		maxHeight: null,      // 최대 높이값(null은 제한 없음)
		focus: true,          // 페이지가 열릴때 포커스를 지정함
		lang: 'ko-KR',         // 한국어 지정(기본값은 en-US)
		disableDragAndDrop: true,
		toolbar: []
	});
	$('#frm_board_view .snote').summernote('disable');
	
	$.each($('.extIcon'),function(i,v){
		var t = $(v).text();
		$(v).html(extIcon(t,'y'));
	});
});

$(function(){
	
	/* 취소 */
	$(document).on('click','.btn_cancel',function(){
		/*location.href="<?php echo base_url();?>index.php/board/Main";*/
		history.back();
	});
	
	/* 글 수정 */
	$(document).on('click','.btn_upd',function(){
		var conts_id ="<?php echo $this->input->get('c_id'); ?>";
		$('#frm_board_view').attr('action','<?php echo base_url();?>index.php/board/Write?id=<?php echo $this->input->get('id');?>&c_id='+conts_id);
		$('#frm_board_view').submit();
	});
		
});
</script>
<style>
/**에디터**/
#frm_board_view .note-editor.note-frame{
	
}
#frm_board_view .note-resizebar{
	display:none
}
#frm_board_view .note-editor{
	box-shadow:none;
}
#frm_board_view .panel{
	border-radius: 0!important;
	border-top:none!important;
	border-color:#e5e5e5!important
}
/**/
</style>
<div id="wp_right">
	
	<div class="grid_area">
	<!-- 수정 -->
	<form id="frm_board_view" name="frm_board_view" class="p-20" data-toggle="validator" role="form" action="" method="post" enctype="multipart/form-data">
		<div class="gray_border_bottom pb-20">
		  <h3><?php if($list){ echo $list->BOARD_NM; } ?></h3>
		</div>
		<br />
		<!--
	  <div class="form-group required">
	    <label for="">제목</label>
	    <div class="view_inputs gray_border_bottom pb-15">
				<?php if($list){ echo $list->CONTS_TITLE; } ?>
			</div>
	  </div>
	  -->
	  <div class="form-group required">
	    <label for="">제목</label>
	    <div class="view_inputs gray_border_bottom pb-15">
				<?php if($list){ ?>
					<?php echo $list->CONTS_TITLE;?>
					<br /><small>작성일 : <?php echo $list->INS_DT;?></small>
					<br /><small>수정일 : <?php echo $list->UPD_DT;?></small>
				<?php } ?>
				
			</div>
	  </div>
	  <!--
	  <div class="form-group required">
			<label for="PFD_PATH">프로젝트</label>
			<div>
				<?php 
					if( $pmsList != null ){
						$pmss = '';
						$pmsArr = '';
						$i=0;
						$com = '';
						foreach( $pmsList as $data ){
							if($i != 0){
								$com = ',';
							}
							if($data->DEL_YN == 'Y'){
								$pmss .= '<span>'.$com.$data->PP_NM . '(' . $data->PP_ID . '_삭제)  </span>';
							}else{
								$pmss .= '<span>'.$com.$data->PP_NM . '(' . $data->PP_ID . ')  </span>';
							}
							$i++;
						}
					} 
				?>
				<div class="view_inputs gray_border_bottom pb-15">
					<?php if($pmsList != null) { echo $pmss; }else{ echo '등록된 프로젝트가 없습니다.';} ?>
				</div>
			</div>
		</div>	
		<div class="form-group required">
		  <label for="PF_EMP">담당자</label>
		   <?php 
				if( $empList != null ){
					$emps = '';
					$empArr = '';
					foreach( $empList as $data ){
						
						if($data->DEL_YN == 'Y'){
							$emps .= $data->EMP_NM . '(' . $data->EMP_ID . ')_삭제  ';
						}else{
							$emps .= $data->EMP_NM . '(' . $data->EMP_ID . ')  ';
						}
						
						$empArr .= '<input type="hidden" id="PF_EMP" name="PF_EMP[]" value="'.$data->EMP_ID.'"/><input type="hidden" id="PF_EMP_NM" name="PF_EMP_NM[]" value="'.$data->EMP_NM.'"/>';
					}
					echo $empArr;
				} 
			?>
			
			<div class="view_inputs gray_border_bottom pb-15">
				<?php if($empList != null) { echo $emps; }else{ echo '등록된 공유자가 없습니다.';} ?>
			</div>
		</div>
		-->
			
	  <div class="form-group required">
	    <label for="">내용</label>
		<div style="height:2px;overflow:hidden;" class="bg-<?php echo $this->config->item($this->uri->segment(1).'Color');?>"></div>
	    <textarea class="form-control snote" id="CONTS_CONT" name="CONTS_CONT" required><?php if($list){ echo $list->CONTS_CONT; } ?></textarea>
	  </div>
	  <div class="form-group">
	    <label for="">첨부된 파일</label>
		
		<?php 
			$i = 0;
			if( $fileList != null ){
			//	for($i=0; $i<count($fileList); $i++){
				foreach( $fileList as $data ){
					$i++;
		?>
		<br/>
		<span class="extIcon"><?php echo $data->PF_FILE_EXT;?></span>
		<a href="<?php echo site_url()?>/pdm/Upload_view/fileDownload?tempName=<?php echo $data->PF_FILE_TEMP_NM ?>&fileName=<?php echo $data->PF_FILE_REAL_NM ?>">
		<?php echo $data->PF_FILE_REAL_NM ?>
		</a>
		<?php 
				}
			} 
			if($i == 0) echo '<div class="mt-10">첨부된 파일이 없습니다.</div>';
		?>
	</div>
		
		<div class="text-center gray_border_top">
			<?php if($this->session->userdata('userauth') == 'admin' || $this->session->userdata('userid') == $list->INS_ID ){ ?>
			<button type="button" class="btn btn_upd btn-<?php echo $this->config->item($this->uri->segment(1).'Color');?> btn-sm mt-10 ">
				수정
			</button>
			<?php } ?>
			<button type="button" class="btn btn_cancel btn-default btn-sm mt-10 ">
				목록
			</button>
		</div>
	</form>
	<?php 
	//	$_POST['PLM_TYPE'] = 'rm'; 
		$_POST['PARENT_ID'] = $_GET['c_id']; 
		$_POST['PP_ID'] = '';
		//if($list){ $_POST['PP_ID'] = $list->PP_ID; }; 
		$_POST['TITLE'] = '';
		if($list){ $_POST['TITLE'] = $list->CONTS_TITLE; }; 
		$_POST['DEL_YN'] = '';
		if($list){ $_POST['DEL_YN'] = $list->CONTS_DEL_YN; }; 
	?>
	<?php include($_SERVER["DOCUMENT_ROOT"]."/application/views/com/Reply.php"); ?>	<!-- 댓글 -->
</div>
</div>
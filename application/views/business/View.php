<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script>
$(window).load(function(){
	$('#frm_business_view .snote').summernote({
		height: 300,          // 기본 높이값
		minHeight: null,      // 최소 높이값(null은 제한 없음)
		maxHeight: null,      // 최대 높이값(null은 제한 없음)
		focus: true,          // 페이지가 열릴때 포커스를 지정함
		lang: 'ko-KR',         // 한국어 지정(기본값은 en-US)
		disableDragAndDrop: true,
		toolbar: []
	});
	$('#frm_business_view .snote').summernote('disable');
	
	$.each($('.extIcon'),function(i,v){
		var t = $(v).text();
		$(v).html(extIcon(t,'y'));
	});
});

$(function(){
	
	/* 취소 */
	$(document).on('click','.btn_cancel',function(){
		location.href="<?php echo base_url();?>index.php/business/Main";
	});
	
	/* 글 수정 */
	$(document).on('click','.btn_upd',function(){
		var id ="<?php echo $this->input->get('id'); ?>";
		$('#frm_business_view').attr('action','<?php echo base_url();?>index.php/business/Write?id='+id);
		$('#frm_business_view').submit();
	});	
});
function wbsView(v){
PopupCenter("<?php echo site_url()?>/pms/WbsView?id="+v, 'WBSVIEW', '1000', '600');
}
</script>
<style>
/**에디터**/
#frm_business_view .note-editor.note-frame{
	
}
#frm_business_view .note-resizebar{
	display:none
}
#frm_business_view .note-editor{
	box-shadow:none;
}
#frm_business_view .panel{
	border-radius: 0!important;
	border-top:none!important;
	border-color:#e5e5e5!important
}
/**/
</style>
<div id="wp_right">
	
	<div class="grid_area">
	<!-- 수정 -->
	<form id="frm_business_view" name="frm_business_view" class="p-20" data-toggle="validator" role="form" action="" method="post" enctype="multipart/form-data">
		<div class="gray_border_bottom pb-20">
		  <h3>게시판 상세보기 <small>View</small>
		</div>
		<br />
	  <div class="form-group required">
	    <label for="">제목</label>
	    <div class="view_inputs gray_border_bottom pb-15">
				<?php if($list){ echo $list->PB_TITLE; } ?>
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
	  <div class="form-group required">
	    <label for="">내용</label>
		<div style="height:2px;overflow:hidden;" class="bg-<?php echo $this->config->item($this->uri->segment(1).'Color');?>"></div>
	    <textarea class="form-control snote" id="PB_CONT" name="PB_CONT" required><?php if($list){ echo $list->PB_CONT; } ?></textarea>
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
			<button type="button" class="btn btn_upd btn-<?php echo $this->config->item($this->uri->segment(1).'Color');?> btn-sm mt-10 ">
				수정
			</button>
			<button type="button" class="btn btn_cancel btn-default btn-sm mt-10 ">
				목록
			</button>
		</div>
	</form>
</div>
</div>
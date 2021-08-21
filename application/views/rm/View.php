<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script>
$(window).load(function(){
	$('#frm_rm_view .snote').summernote({
		height: 300,          // 기본 높이값
		minHeight: null,      // 최소 높이값(null은 제한 없음)
		maxHeight: null,      // 최대 높이값(null은 제한 없음)
		focus: true,          // 페이지가 열릴때 포커스를 지정함
		lang: 'ko-KR',         // 한국어 지정(기본값은 en-US)
		disableDragAndDrop: true,
		toolbar: [
    // [groupName, [list of button]]
	    ['style', ['bold', 'italic', 'underline', 'clear']],
	    ['font', ['strikethrough', 'superscript', 'subscript']],
	    ['fontsize', ['fontsize']],
	    ['color', ['color']],
	    ['table', ['table']],
	    ['para', ['ul', 'ol', 'paragraph']],
	    ['height', ['height']]
	  ]
	});
	$('#frm_rm_view .snote').summernote('disable');
	
	$.each($('.extIcon'),function(i,v){
		var t = $(v).text();
		$(v).html(extIcon(t,'y'));
	});
});

$(function(){
	
	/* 취소 */
	$(document).on('click','.btn_cancel',function(){
		/*location.href="<?php echo base_url();?>index.php/rm/Main";*/
		history.back();
	});
	
	/* 글 수정 */
	$(document).on('click','.btn_upd',function(){
		var pr_id ="<?php echo $this->input->get('id'); ?>";
		var hash = window.location.hash;
		$('#frm_rm_view').attr('action','<?php echo base_url();?>index.php/rm/Write?id='+pr_id+hash);
		$('#frm_rm_view').submit();
	});
	
	/* 진행상태 변경 */
	$(document).on('change','#PR_STATUS',function(){
		
		var pr_id ="<?php echo $this->input->get('id'); ?>";
		var val = $(this).val();
		var text = $('#frm_rm_view').find('#PR_STATUS option:selected').text();
		
		var data = {
			"PR_ID"		: pr_id,
			"PR_STATUS"	: val
		};
		
		$.ajax({
			type: 'post',
			dataType: 'json',
			url: '<?php echo base_url();?>index.php/rm/View/pr_status_upd',
			data: data,
			async : false,
			success: function (data) {
				if(data){
					bootbox.alert({
						size:'small',
						message : '['+text+'] 로 변경되었습니다.',
						buttons: {
							ok: {
								label: '확인',
								className: "btn-<?php echo $this->config->item($this->uri->segment(1).'Color')?>"
							}
						}
					});
				}
			},
			error: function (request, status, error) {
				console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
			}
		});
		
	});
	
});

function wbsView(v){
PopupCenter("<?php echo site_url()?>/pms/WbsView?id="+v, 'WBSVIEW', '1000', '600');
}
</script>
<style>
/**에디터**/
#frm_rm_view .note-editor.note-frame{
	
}
#frm_rm_view .note-resizebar{
	display:none
}
#frm_rm_view .note-editor{
	box-shadow:none;
}
#frm_rm_view .panel{
	border-radius: 0!important;
	border-top:none!important;
	border-color:#e5e5e5!important
}
/**/
</style>
<div id="wp_right">
	
	<div class="grid_area">
	<!-- 수정 -->
	<form id="frm_rm_view" name="frm_rm_view" class="p-20" data-toggle="validator" role="form" action="" method="post" enctype="multipart/form-data">
		<div class="gray_border_bottom pb-20">
		  <h3>요구사항 상세보기 <small>View</small>
		  <?php if($this->session->userdata('userauth') == 'admin' || $this->session->userdata('userauth') == 'emp'){ ?>
		  <select class="form-control" id="PR_STATUS" name="PR_STATUS" style="width:130px;display:inline-block">
				<option <?php if($list->PR_STATUS == '1'){echo 'selected';} ?> value="1">접수완료</option>
				<option <?php if($list->PR_STATUS == '2'){echo 'selected';} ?> value="2">조치완료</option>
				<option <?php if($list->PR_STATUS == '3'){echo 'selected';} ?> value="3">진행중</option>
				<option <?php if($list->PR_STATUS == '4'){echo 'selected';} ?> value="4">반려</option>
			
			</select>
		  <?php } ?>
			</h3>
		</div>
		<br />
	  <div class="form-group required">
	    <label for="">제목</label>
	    <div class="view_inputs gray_border_bottom pb-15">
				<?php if(count($list) > 0){ ?>
					<?php echo $list->PR_TITLE;?>
					<br /><small>작성일 : <?php echo $list->INS_DT;?></small>
					<br /><small>수정일 : <?php echo $list->UPD_DT;?></small>
				<?php } ?>
			</div>
	  </div>
	   <div class="form-group required">
	    <label for="">완료요청일</label>
	    <div class="view_inputs gray_border_bottom pb-15">
				<?php if($list){ echo $list->PR_HOPE_END_DAT; } ?>
			</div>
	  </div>
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
								/*$com = ',';*/
							}
							if($data->DEL_YN == 'Y'){
								$pmss .= '<span class="btn btn-default btn-xs disabled">'.$com.$data->PP_NM . '(삭제됨)  </span> ';
							}else{
								$pmss .= '<span title="새창열림" class="btn btn-default btn-xs" onclick=wbsView("'.$data->PP_ID.'")>'.$com.$data->PP_NM . '(' . $data->PP_ID . ') &nbsp;&nbsp;<span class="fa fa-window-restore" style="top: 0;position: relative;"></span></span> ';
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
							$emps .= '<span class="btn btn-default btn-xs disabled">'.$data->EMP_NM . '(' . $data->EMP_ID . ' 삭제됨)</span> ';
						}else{
							$emps .= '<span class="nav-item dropdown">';
				      $emps .= '<a class="nav-link dropdown-toggle btn btn-default btn-xs" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
				      $emps .= $data->EMP_NM . '(' . $data->EMP_ID . ')&nbsp;&nbsp;<span class="fa fa-sort-down" style="top: -2px;position: relative;"></span>';
				      $emps .= '</a> ';
				      $emps .= '<div class="dropdown-menu" aria-labelledby="navbarDropdown">';
							$emps .= '<a onclick=msgView("'.$data->EMP_ID.'") class="dropdown-item">쪽지보내기</a>';
							$emps .= '<a onclick=infoView("'.$data->EMP_ID.'") class="dropdown-item">정보보기</a>';
							$emps .= '</div>';
				      $emps .= '</span>';
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
	    <textarea class="form-control snote" id="PR_CONT" name="PR_CONT" required><?php if($list){ echo $list->PR_CONT; } ?></textarea>
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
		$_POST['PARENT_ID'] = $_GET['id']; 
		$_POST['PP_ID'] = '';
		if($list){ $_POST['PP_ID'] = $list->PP_ID; }; 
		$_POST['TITLE'] = '';
		if($list){ $_POST['TITLE'] = $list->PR_TITLE; }; 
		$_POST['DEL_YN'] = '';
		if($list){ $_POST['DEL_YN'] = $list->PR_DEL_YN; }; 
	?>
	<?php include($_SERVER["DOCUMENT_ROOT"]."/application/views/com/Reply.php"); ?>	<!-- 댓글 -->
</div>
</div>
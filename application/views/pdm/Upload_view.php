<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script>
$(window).load(function(){
	$('.snote').summernote({
		height: 300,          // 기본 높이값
		minHeight: null,      // 최소 높이값(null은 제한 없음)
		maxHeight: null,      // 최대 높이값(null은 제한 없음)
		focus: true,          // 페이지가 열릴때 포커스를 지정함
		lang: 'ko-KR',         // 한국어 지정(기본값은 en-US)
		disableDragAndDrop: true,
		toolbar: []
	});
	$('.snote').summernote('disable');
});
$(function(){
	
	/* 수정 */
	$(document).on('click','.btn_fileUpd',function(){
		
		var pf_id = $('#frm_upload_view').find('#PF_ID').val();
		location.href="<?php echo site_url()?>/pdm/Upload?id="+pf_id;
		
	});
	
	/* 취소 */
	$(document).on('click','.btn_cancel',function(){
		location.href="<?php echo base_url();?>index.php/pdm/Main";
	});
	
	$('.extIcon').html(extIcon("<?php echo $list->PF_FILE_EXT;?>"));
	
});
</script>
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

/**에디터**/
.note-editor.note-frame{
	
}
.note-resizebar{
	display:none
}
.note-editor{
	box-shadow:none;
}
.panel{
	border-radius: 0!important;
	border-top:none!important;
	border-color:#e5e5e5!important
}
/**/
</style>

<!-- 파일뷰 팝업 -->
<div id="wp_right">
	
	<div class="grid_area">
	<form id="frm_upload_view" class="p-20" data-toggle="validator" role="form" action="" method="post" enctype="multipart/form-data" >
		<div class="gray_border_bottom pb-20">
		  <h3>상세보기 <small>file detail view</small></h3>
		</div>
		<br />
		<?php if( count($list) > 0 ){ ?>
		<input type="hidden" id="PF_ID" name="PF_ID" value="<?php echo $list->PF_ID ?>" />
	  <div class="form-group required ">
	    <label for="PF_NM">파일이름</label>
			<div class="view_inputs gray_border_bottom pb-15">
				<?php echo $list->PF_NM ?>
			</div>
	  </div>
	  
		<div class="form-group required">
			<label for="PFD_PATH">카테고리</label>
			
			<div class="view_inputs gray_border_bottom pb-15">
				<?php echo $list->PF_PATH ?>
			</div>
			
			<input type="hidden" name="PFD_ID" id="PFD_ID" value="<?php echo $list->PFD_ID ?>" />
		</div>
	<div class="form-group required">
	  <label for="PF_EMP_TEXT">공유</label>
		<div>
			<?php 
				if( $empList != null ){
					$emps = '';
					$empArr = '';
					$i=0;
					$com = '';
					foreach( $empList as $data ){
						if($i != 0){
							$com = ',';
						}
						$emps .= $com.$data->EMP_NM . '(' . $data->EMP_ID . ')  ';
						$i++;
					}
				} 
			?>
		  
		  <div class="view_inputs gray_border_bottom pb-15">
				<?php if($empList != null) { echo $emps; }else{ echo '등록된 공유자가 없습니다.';} ?>
			</div>
		</div>
		<span class="help-block with-errors"></span>
	</div>	
	<div class="form-group required">
		<label for="PFD_PATH">프로젝트</label>

		<div class="view_inputs gray_border_bottom pb-15">
			<?php echo $list->PP_ID ?>
		</div>

		<input type="hidden" name="PFD_ID" id="PFD_ID" value="<?php echo $list->PFD_ID ?>" />
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
				
				if($keywordList[0]->PK_NM == ''){
					echo '<div class="view_inputs gray_border_bottom pb-15">등록된 키워드가 없습니다.</div>';
				}
			?>
			
			
		  <input id="PF_KEYWORD" name="PF_KEYWORD" type="text" data-role="tagsinput" class="form-control" placeholder="" aria-label="..." value="" readonly>
		</div>
	</div>
	  <div class="form-group required">
	    <label for="PF_CONT">내용</label>
	    <div style="height:2px;overflow:hidden;" class="bg-<?php echo $this->config->item($this->uri->segment(1).'Color');?>"></div>
	    <textarea class="form-control snote" id="PF_CONT" name="PF_CONT"><?php echo $list->PF_CONT ?></textarea>
	    <span class="help-block with-errors"></span>
	  </div>
	  <div class="form-group required">
	    <label for="PF_FILE">첨부된 파일 : </label>
	    <span class="extIcon"></span>
	    <a href="<?php echo site_url()?>/pdm/Upload_view/fileDownload?tempName=<?php echo $list->PF_FILE_TEMP_NM ?>&fileName=<?php echo $list->PF_FILE_REAL_NM ?>">
			<?php echo $list->PF_FILE_REAL_NM ?>
			
		</a>
	  </div>
	  
	  <?php } ?>
	  <div class="text-center gray_border_top">
			<button type="button" class="btn btn_fileUpd btn-<?php echo $this->config->item($this->uri->segment(1).'Color');?> btn-sm mt-10 ">
				수정
			</button>
			<button type="button" class="btn btn_cancel btn-default btn-sm mt-10 ">
				목록
			</button>
		</div>
	  
	</form>
	
	<!--파일이력-->
	<div class="p-20">
		<div class="mb-10">
			<h3>파일변경이력</h3>
		</div>
		<div style="height:2px;overflow:hidden;" class="bg-<?php echo $this->config->item($this->uri->segment(1).'Color');?>"></div>
	  <table class="tbl_main table table-hover" id="historygrid">
			<colgroup>
				<col />
				<col style="width:5%" />
				<col style="width:10%" />
				<col style="width:10%" />
				<col style="width:10%" />
			</colgroup>
			<thead>
				<tr>
					<th onclick="sortTable(1,'historygrid')">제목(파일명)</th>
					<th onclick="sortTable(2,'historygrid')">종류</th>
					<th onclick="sortTable(3,'historygrid')">용량</th>
					<th onclick="sortTable(4,'historygrid')">작성자</th>
					<th onclick="sortTable(5,'historygrid')">작성일</th>
				</tr>
			</thead>
			<tbody>
				<?php if( $versionList != null ){ ?>
				<?php 
				$i=0;
				foreach( $versionList as $data ) { ?>
				<tr>
					<td class='text-left'>
						<strong><?php echo $data->PF_NM ?></strong>
						<div style="color:#666">이전 경로 : <?php echo $data->PF_PATH ?></div>
					</td>
					<td class='text-center'>
						<script>
							$(function(){
								$('.extIcon_his<?php echo $i?>').html(extIcon("<?php echo $data->PF_FILE_EXT;?>"));
							});
						</script>
						<span class="extIcon_his<?php echo $i?>"></span>
						<a href="<?php echo site_url()?>/pdm/Upload_view/fileDownload?tempName=<?php echo $data->PF_FILE_TEMP_NM ?>&fileName=<?php echo $data->PF_FILE_REAL_NM ?>"><?php echo $data->PF_FILE_EXT ?></a>
					</td>
					<td class='text-center'><?php echo $data->PF_FILE_SIZE ?> kb</td>
					<td class='text-center'><?php echo $data->INS_ID ?></td>
					<td class='text-center'><?php echo $data->INS_DT ?></td>
				</tr>
				<?php 
				$i++;
				} ?>
				<?php } ?>
			</tbody>
		</table>
	</div>
	
	</div>
	
</div>
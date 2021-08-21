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

	/* 담당자 검색 */
	$(document).on('click','.btn_search_emp',function(){
		$('#pop_empSearch').modal('show');
		$('#pop_empSearch').find('option').eq(0).attr('selected','selected');
	});
	
	/* 프로젝트 검색 */
	$('.btn_search_pms').click(function(){
		$('#pop_pmsSearch').modal('show');
		$('#pop_pmsSearch').find('option').eq(0).attr('selected','selected');
	});
	
	/* 글 저장 */
	$(document).on('click','.btn_save',function(){
		
		bootbox.confirm({
			size: "small",
			message: "저장하시겠습니까?", 
			buttons: {
		        confirm: {
		            label: '확인',
		            className: "btn-<?php echo $this->config->item($this->uri->segment(1).'Color');?>"
		        },
		        cancel: {
		            label: '취소'
		        }
		    },
			callback: function(result){
		
				if(result == true){
					fn_save();
				}
			}
		});
		
	});

});

/* 저장 */
function fn_save(){
	
	var id ="<?php echo $this->input->get('id'); ?>";
	
	if(id == ""){
		$('#frm_business_write').attr('action','<?php echo base_url();?>index.php/business/Write/save');
	}else{
		$('#frm_business_write').attr('action','<?php echo base_url();?>index.php/business/Write/upd');
	}
	
	$('#frm_business_write').submit();
	
}
</script>
<?php include($_SERVER["DOCUMENT_ROOT"]."/application/views/com/Pop_empSearch.php"); ?>	<!-- 담당자검색 팝업 -->
<div id="wp_right">
	
	<div class="grid_area">
	<!-- 수정 -->
	<form id="frm_business_write" name="frm_business_write" class="p-20" data-toggle="validator" role="form" action="" method="post" enctype="multipart/form-data">
		<input type="hidden" id="PB_ID" name="PB_ID" value="<?php if($list){ echo $list->PB_ID; } ?>"/>
		<div class="gray_border_bottom pb-20">
		  <h3>글쓰기 <small>Write</small></h3>
		</div>
		<br />
	  <div class="form-group required">
	    <label for="">제목</label>
	    <input type="text" class="form-control" id="PB_TITLE" name="PB_TITLE" placeholder="제목을 입력해주세요." value="<?php if($list){ echo $list->PB_TITLE; } ?>" required>
	    <span class="help-block with-errors"></span>
	  </div>
		<div class="form-group">
		  <label for="PF_EMP">담당자</label>
		   <?php 
				if( $empList != null ){
					$emps = '';
					$empArr = '';
					foreach( $empList as $data ){
						$emps .= $data->EMP_NM . '(' . $data->EMP_ID . ')  ';
						$empArr .= '<input type="hidden" id="PF_EMP" name="PF_EMP[]" value="'.$data->EMP_ID.'"/><input type="hidden" id="PF_EMP_NM" name="PF_EMP_NM[]" value="'.$data->EMP_NM.'"/>';
					}
					echo $empArr;
				} 
			?>
			<div class="input-group">
			  <input id="PF_EMP_TEXT" name="PF_EMP_TEXT" type="text" data-emp="emp_input" class="form-control" placeholder="오른쪽 검색버튼을 이용해 검색해주세요." readonly value="<?php if($empList != null){ echo $emps; } ?>">
			  <!-- PF_EMP[] -->
			  <div class="input-group-btn">
					<button type="button" class="btn_search_emp btn btn-default">검색</button>
			  </div>
			</div>
			<span class="help-block with-errors"></span>
		</div>
			
	  <div class="form-group required">
	    <label for="">내용</label>
	    <textarea class="form-control snote" id="PB_CONT" name="PB_CONT" required><?php if($list){ echo $list->PB_CONT; } ?></textarea>
	    <span class="help-block with-errors"></span>
	  </div>
	  <div class="form-group">
	    <label for="">파일</label>
		<input type="file" id="PF_FILE" name="PF_FILE[]" multiple >
		<?php 
			$i = 0;
			if( $fileList != null ){
				
				foreach( $fileList as $data ){
					$i++;
		?>
		<br/>
		삭제
		<input type="checkbox" name="FILE_DEL[]" value="<?php echo $data->PBF_ID; ?>"/>
		<span class="extIcon"><?php echo $data->PF_FILE_EXT; ?></span>
		<a href="<?php echo site_url()?>/pdm/Upload_view/fileDownload?tempName=<?php echo $data->PF_FILE_TEMP_NM ?>&fileName=<?php echo $data->PF_FILE_REAL_NM ?>">
			<?php echo $data->PF_FILE_REAL_NM ?>
		</a>
		<?php 
				}
			} 
			if($i == 0) echo '<div class="mt-10">첨부된 파일이 없습니다.</div>';
		?>
	  </div>
	  <script>
			$("#PF_FILE").fileinput({
			    //uploadUrl: "/file-upload-batch/2",
			    //uploadAsync: true,
			    language : "kr",
			    previewFileIcon: '<i class="fa fa-file"></i>',
			    allowedPreviewTypes: null, // set to empty, null or false to disable preview for all types
			    previewFileIconSettings: { // configure your icon file extensions
		        'doc': '<i class="fa fa-file-word-o text-primary"></i>',
		        'xls': '<i class="fa fa-file-excel-o text-success"></i>',
		        'ppt': '<i class="fa fa-file-powerpoint-o text-danger"></i>',
		        'pdf': '<i class="fa fa-file-pdf-o text-danger"></i>',
		        'zip': '<i class="fa fa-file-archive-o text-muted"></i>',
		        'htm': '<i class="fa fa-file-code-o text-info"></i>',
		        'txt': '<i class="fa fa-file-text-o text-info"></i>',
		        'mov': '<i class="fa fa-file-movie-o text-warning"></i>',
		        'mp3': '<i class="fa fa-file-audio-o text-warning"></i>',
		        // note for these file types below no extension determination logic 
		        // has been configured (the keys itself will be used as extensions)
		        'jpg': '<i class="fa fa-file-photo-o text-danger"></i>', 
		        'gif': '<i class="fa fa-file-photo-o text-warning"></i>', 
		        'png': '<i class="fa fa-file-photo-o text-primary"></i>'    
		    },
		    previewFileExtSettings: { // configure the logic for determining icon file extensions
	        'doc': function(ext) {
	            return ext.match(/(doc|docx)$/i);
	        },
	        'xls': function(ext) {
	            return ext.match(/(xls|xlsx)$/i);
	        },
	        'ppt': function(ext) {
	            return ext.match(/(ppt|pptx)$/i);
	        },
	        'zip': function(ext) {
	            return ext.match(/(zip|rar|tar|gzip|gz|7z)$/i);
	        },
	        'htm': function(ext) {
	            return ext.match(/(htm|html)$/i);
	        },
	        'txt': function(ext) {
	            return ext.match(/(txt|ini|csv|java|php|js|css)$/i);
	        },
	        'mov': function(ext) {
	            return ext.match(/(avi|mpg|mkv|mov|mp4|3gp|webm|wmv)$/i);
	        },
	        'mp3': function(ext) {
	            return ext.match(/(mp3|wav)$/i);
	        },
	    }
			});
		</script>
	  <div class="text-center gray_border_top">
			<button type="button" class="btn btn_save btn-<?php echo $this->config->item($this->uri->segment(1).'Color');?> btn-sm mt-10 ">
				저장
			</button>
			<button type="button" class="btn btn_cancel btn-default btn-sm mt-10 ">
				취소
			</button>
		</div>
	  
	</form>
	
</div>
</div>
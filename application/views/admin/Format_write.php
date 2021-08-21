<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$PageNm = 'admin';//seg 1
$PageType = 'Format_write';//seg 2

/*수정이면*/
if($list){
	$PF_ID  	= $list->PF_ID;
	$PF_NM  	= $list->PF_NM;
	$PF_CONT 	= $list->PF_CONT;
	$INS_ID		= $list->INS_ID;
	$INS_DT 	= $list->INS_DT;
}else{
	$PF_ID  	= '';
	$PF_NM  	= '';
	$PF_CONT 	= '';
	$INS_ID		= '';
    $INS_DT 	= '';
}
//첨부파일 사용유무
$fileYns = 'N';

?>

<script>
//EDIT 로드
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
	//아이콘 넣기
	$.each($('.extIcon'),function(i,v){
		var t = $(v).text();
		$(v).html(extIcon(t,'y'));
	});
});

$(function(){
	
	/* 취소 */
	$(document).on('click','.btn_cancel',function(){
		history.back();
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
	
	if ($('#frm_write').validator('validate').has('.has-error').length === 0) {
		
		//로딩 구현
		
		$('#loading').modal('show');
		<?php if($fileYns == 'Y'){?>
		var fd = new FormData();
		$.each($('input[id=PF_FILE]')[0].files,function(i,v){
			fd.append('file', v);
		}); 
	  
		$.ajax({
			url: '',
			type: 'POST',
			processData: false,
			data: fd,
			xhr: function() {
				var xhr = $.ajaxSettings.xhr();
				xhr.upload.addEventListener('progress', function(ev) {
					$('#loading').find('.progress-bar').css('width',(ev.loaded/(ev.total/100))+'%');
				}, false);

				return xhr;
			},
			beforeStart: function() {
				$('#loading').find('.progress-bar').css('width','0%');
			},
			success: function() {
			}
			
		});
		<?php } ?>
		var view_id ="<?php echo $this->input->get('id'); ?>";
	
		if(view_id == ""){
			$('#frm_write').attr('action','<?php echo base_url();?>index.php/<?php echo $PageNm?>/<?php echo $PageType?>/save');
		}else{
			$('#frm_write').attr('action','<?php echo base_url();?>index.php/<?php echo $PageNm?>/<?php echo $PageType?>/upd');
		}
		
		$('#frm_write').submit();
		
	}else{
		//필수항목
	}
		
}
</script>
<div id="wp_right">
	
	<div class="grid_area">
	<!-- 작성,수정 -->
	<form id="frm_write" name="frm_write" class="p-20" data-toggle="validator" role="form" action="" method="post" enctype="multipart/form-data" >
		<input type="hidden" id="PF_ID" name="PF_ID" value="<?php echo $PF_ID;?>" />
		<div class="gray_border_bottom pb-20">
			<h3>양식정보 <small>Write</small></h3>
		</div>
		<br />
		
		<div class="form-group required">
			<label for="">양식명<span class="req-text" title="필수입력">*</span></label>
			<input type="text" class="form-control" id="PF_NM" name="PF_NM" placeholder="양식명을 입력해주세요." value="<?php echo $PF_NM;?>" required>
			<span class="help-block with-errors"></span>
		</div> 

		<div class="form-group required">
			<label for="">내용<span class="req-text" title="필수입력">*</span></label>
			<textarea class="form-control snote" id="PF_CONT" name="PF_CONT" required><?php echo $PF_CONT;?></textarea>
			<span class="help-block with-errors"></span>
		</div>
		
	  <?php if($fileYns == 'Y'){?>
	  <div class="form-group">
	    <label for="">파일</label>
	    <input type="file" id="PF_FILE" name="PF_FILE[]" multiple>
	    
	    <!--첨부파일-->
	    <?php 
				$i = 0;
				if( $fileList != null ){
					
					foreach( $fileList as $data ){
						$i++;
			?>
			<br/>
			삭제
			<input type="checkbox" name="FILE_DEL[]" value="<?php echo $data->FILELIST_ID; ?>"/>
			<span class="extIcon"><?php echo $data->PF_FILE_EXT; ?></span>
			<a href="<?php echo site_url()?>/pdm/Upload_view/fileDownload?tempName=<?php echo $data->PF_FILE_TEMP_NM ?>&fileName=<?php echo $data->PF_FILE_REAL_NM ?>">
				<?php echo $data->PF_FILE_REAL_NM ?>
			</a>
			<?php 
					}
				} 
				if($i == 0) echo '<div class="mt-10">첨부된 파일이 없습니다.</div>';
			?>
	    <!--첨부파일 끝-->
	    
	    <span class="help-block with-errors"></span>
	  </div> 
	 	<?php } ?>
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
			<?php if($INS_ID != ''){ ?>
				<?php if($this->session->userdata('userauth') == 'admin' || $this->session->userdata('userid') == $INS_ID ){ ?>
				<button type="button" class="btn btn_save btn-<?php echo $this->config->item($this->uri->segment(1).'Color');?> btn-sm mt-10 ">
					저장
				</button>
				<?php } ?>
			<?php }else{ // 신규등록 ?>
				<button type="button" class="btn btn_save btn-<?php echo $this->config->item($this->uri->segment(1).'Color');?> btn-sm mt-10 ">
					저장
				</button>
			<?php } ?>
			<button type="button" class="btn btn_cancel btn-default btn-sm mt-10 ">
				취소
			</button>
		</div>
	  
	</form>
	
</div>
</div>
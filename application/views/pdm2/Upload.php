<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script>
	
function snoteLoad(){
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
};

$(function(){
	
	$('#PF_KEYWORD').tagsinput();
	$('#frm_upload').validator();
		 
	/* 카테고리 검색 */
	//$(document).on('click','.btn_search_folder',function(){
	$('.btn_search_folder').click(function(){
		$('#pop_folderSearch').modal('show');
		$('#pop_folderSearch').find('#parent_frm').val('frm_upload');
		getFolderSearchTree();
	});
	
	/* 담당자 검색 */
	//$(document).on('click','.btn_search_emp',function(){
	$('.btn_search_emp').click(function(){
		$('#pop_empSearch').modal('show');
		$('#pop_empSearch').find('option').eq(0).attr('selected','selected');
	});
	
	/* 프로젝트 검색 */
	//$(document).on('click','.btn_search_pms',function(){
	$('.btn_search_pms').click(function(){
		$('#pop_pmsSearch').modal('show');
		$('#pop_pmsSearch').find('option').eq(0).attr('selected','selected');

	});
	
	/* 저장 */
	$('.btn_upload').click(function(){
	//$(document).on('click','.btn_upload',function(){
		
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
	
	/* 취소 */
	//$(document).on('click','.btn_cancel',function(){
	$('.btn_cancel').click(function(){

		$('#content_ajax').html('');
		$('.lists').show();
		$(window).trigger('resize');
		window.history.back();
	});
	
});

/* 저장 */
function fn_save(){
	
	if ($('#frm_upload').validator('validate').has('.has-error').length === 0) {
		
		//로딩 구현
		$('#loading').modal('show');
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
		  	console.log(1);
		  }
	  });
		
		var pf_id ="<?php echo $this->input->get('id'); ?>";
	  	  // 카테고리 선택 안할시 루트에 저장
		  if($("#PF_PATH").val()=='') $("#PF_PATH").val('PDM');
		  if($("#PFD_ID").val()=='') $("#PFD_ID").val('PLM');
		  
		if(pf_id == "NEW"){
			$('#frm_upload').attr('action','<?php echo base_url();?>index.php/pdm2/Upload/upload');
			$('#frm_upload').submit();
		}else{
			$('#frm_upload').attr('action','<?php echo base_url();?>index.php/pdm2/Upload/update');
			$('#frm_upload').submit();
		}
	}else{
		// 필수
	}
	
}
</script>

<?php include("Pop_folderSearch.php"); ?>	<!-- 카테고리검색 팝업 -->
<?php include($_SERVER["DOCUMENT_ROOT"]."/application/views/com/Pop_empSearch.php"); ?>	<!-- 담당자검색 팝업 -->
<?php include($_SERVER["DOCUMENT_ROOT"]."/application/views/com/Pop_pmsSearch.php"); ?>	<!-- 프로젝트검색 팝업 -->
<!-- 폴더관리 팝업 -->
<div id="wp_right">
	
	<div class="grid_area">

	<!-- 수정 -->
	<form id="frm_upload" class="p-20" data-toggle="validator" role="form" action="" method="post" enctype="multipart/form-data" >
		<input type="hidden" id="PF_ID" name="PF_ID" value="<?php if($list){ echo $list->PF_ID; } ?>" />
		<div class="gray_border_bottom pb-20">
		  <h3>파일업로드 <small>file upload</small></h3>
		</div>
		<br />
	  <div class="form-group required">
	    <label for="PF_NM">파일이름 <span class="req-text" title="필수입력">*</span></label>
	    <input type="text" class="form-control" id="PF_NM" name="PF_NM" placeholder="파일이름을 입력해주세요." value="<?php if($list){ echo $list->PF_NM; } ?>" required>
	    <span class="help-block with-errors"></span>
	  </div>
	  
	  <div class="form-group">
		  <label for="PF_PATH">카테고리</label>
			<div class="input-group">
			  <input id="PF_PATH" name="PF_PATH" type="text" class="form-control req-readonly" autocomplete="off" placeholder="오른쪽 검색버튼을 이용해 검색해주세요." aria-label="..." value="<?php if($list){  echo $list->PF_PATH; } ?>">
			  <input type="hidden" name="PFD_ID" id="PFD_ID" value="<?php if($list){ echo $list->PFD_ID; } ?>" />
			  <div class="input-group-addon btn_search_folder">
			    <span class="glyphicon glyphicon-search"></span>
			  </div>
			</div>
			<span class="help-block with-errors"></span>
		</div>
	<div class="form-group required">
	  <label for="">프로젝트</label>
		<?php 
				if( $pmsList != null ){
					$pmss = '';
					$pmsArr = '';
					foreach( $pmsList as $data ){
						$pmss .= $data->PP_NM . '  ';
						$pmsArr .= '<input type="hidden" id="PF_PMS" name="PF_PMS[]" value="'.$data->PP_ID.'"/><input type="hidden" id="PF_PMS_NM" name="PF_PMS_NM[]" value="'.$data->PP_NM.'"/>';
					}
					echo $pmsArr;
				} 
			?>
		<div class="input-group">
		  <input id="PF_PMS_TEXT" name="PF_PMS_TEXT" type="text" data-pms="pms_input" class="form-control req-readonly" autocomplete="off" onkeypress="return false;" placeholder="오른쪽 검색버튼을 이용해 검색해주세요." aria-label="..." value="<?php if($pmsList != null){ echo $pmss; } ?>">
		  <div class="input-group-addon btn_search_pms">
		    <span class="glyphicon glyphicon-search"></span>
		  </div>
		</div>
	</div>
	<div class="form-group required">
	  <label for="PF_EMP">공유</label>
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
		  <input id="PF_EMP_TEXT" name="PF_EMP_TEXT" type="text" data-emp="emp_input" class="form-control req-readonly" autocomplete="off" onkeypress="return false;" placeholder="오른쪽 검색버튼을 이용해 검색해주세요." aria-label="..." value="<?php if($empList != null){ echo $emps; } ?>">
		  <div class="input-group-addon btn_search_emp">
		    <span class="glyphicon glyphicon-search"></span>
		  </div>
		</div>
		<span class="help-block with-errors"></span>
	</div>
	<div class="form-group required">
	  <label for="PF_KEYWORD">키워드 (작성후 ENTER)</label>
		<div>
			<?php 
				if( $keywordList != null ){
					$keywordArr = [];
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
		  <input id="PF_KEYWORD" name="PF_KEYWORD" type="text" data-role="tagsinput" class="form-control" placeholder="" aria-label="..." value="">
		</div>
	</div>
			
	  <div class="form-group required">
	    <label for="PF_CONT">내용 <span class="req-text" title="필수입력">*</span></label>
	    <textarea class="form-control snote" id="PF_CONT" name="PF_CONT" required><?php if($list){ echo $list->PF_CONT; } ?></textarea>
		<!--
		<div class="pull-right" style="margin-top: -6px;">
			<button type="button" data="'+i+'" class="btn_selFormat btn-default btn btn-xs">양식선택</button>
		</div>
		<div style="clear:both"></div>
		-->
	    <span class="help-block with-errors"></span>
	  </div>
	  <div class="form-group required">
	  <label for="file">파일 <span class="req-text" title="필수입력">*</span> (한 파일만 업로드 가능합니다)</label>
		<?php 
			if($list){
				if($list->PF_INIT_ID_TYPE == $this->uri->segment(1)){ 
		?>
		<div class="file-loading">
		    <input id="PF_FILE" name="PF_FILE" type="file" <?php if(!$list){ echo 'required'; } ?>>
		</div>
		<?php 
				}else{
					if($list->VIEW_ID != null){
						?>
						<a href="<?php echo site_url()?>/<?php echo $list->PF_INIT_ID_TYPE ?>/View?id=<?php echo $list->VIEW_ID ?>">해당 글 바로가기</a>
						<?php
					}
					echo '( PDM에서 업로드된 파일만 변경 가능합니다. )';
				}
			}else{
				?>
				<div class="file-loading">
					<input id="PF_FILE" name="PF_FILE" type="file" <?php if(!$list){ echo 'required'; } ?>>
				</div>
				<?php
			}
		?>
			<?php if($list){ ?>
				<div class="mt-10">
				 	<label for="PF_FILE">첨부된 파일 : </label>
				 	<script>
						$(function(){
					 		//확장자 아이콘
							$('.extIcon').html(extIcon("<?php echo $list->PF_FILE_EXT;?>","y"));
						});
				 	</script>
				 	<span class="extIcon"></span>
					<a href="<?php echo site_url()?>/pdm2/Upload_view/fileDownload?tempName=<?php echo $list->PF_FILE_TEMP_NM; ?>&fileName=<?php echo $list->PF_FILE_REAL_NM; ?>">
						<?php echo $list->PF_FILE_REAL_NM; ?>
					</a>
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
		
	    
	    <span class="help-block with-errors"></span>
	  </div>
	  
	  <div class="text-center gray_border_top">
			<button type="button" class="btn btn_upload btn-<?php echo $this->config->item($this->uri->segment(1).'Color');?> btn-sm mt-10 ">
				저장
			</button>
			<button type="button" class="btn btn_cancel btn-default btn-sm mt-10 ">
				취소
			</button>
		</div>
	  
	</form>

	<!--파일이력
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
				foreach( $versionList as $data ) {	
				?>
				<tr>
					<td class='text-left'>
						<strong><?php echo $data->PF_NM ?></strong>
						<div style="color:#666">이전 경로 : <?php echo $data->PF_PATH ?></div>
					</td>
					<td class='text-center'>
						<script>
							$(function(){
								$('.extIcon_his<?php echo $i?>').html(extIcon("<?php echo $data->PF_FILE_EXT;?>","y"));
							});
						</script>
						<span class="extIcon_his<?php echo $i?>"></span>
						<a href="<?php echo site_url()?>/pdm2/Upload_view/fileDownload?tempName=<?php echo $data->PF_FILE_TEMP_NM ?>&fileName=<?php echo $data->PF_FILE_REAL_NM ?>"><?php echo $data->PF_FILE_EXT ?></a>
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
	-->
</div>
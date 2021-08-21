<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$PageNm = 'admin';//seg 1
$PageType = 'User_write';//seg 2

/*수정이면*/
if($list){
	$PE_ID  = $list->PE_ID;
	$PG_ID  = $list->PG_ID;
	$PE_NM  = $list->PE_NM;
	$PE_PWD = $list->PE_PWD;
	$PE_TEL = $list->PE_TEL;
	$PE_AUTH= $list->PE_AUTH;
	$ETC2= $list->ETC2;
	$INS_DT = $list->INS_DT;
	$ID_CHK = 'Y';
}else{
	$PE_ID  = '';
	$PG_ID  = '';
	$PE_NM  = '';
	$PE_PWD = '';
	$PE_TEL = '';
    $PE_AUTH= '';
    $ETC2= '';
    $INS_DT = '';
	$ID_CHK = 'N';
}
//첨부파일 사용유무
$fileYns = 'Y';

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
	
	/* 거래처 검색 */
	$(document).on('click','.btn_search_comp',function(){
		$('#pop_compSearch').modal('show');
		$('#pop_compSearch').find('option').eq(0).attr('selected','selected');
	});
	
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
	
	/* 중복확인 */
	$(document).on('click','.btn_idChk',function(){
		var pe_id = $('#frm_write').find('#PE_ID').val();
		
		if(pe_id == ''){
			bootbox.alert({
				size:'small',
				message : '아이디를 입력해주세요.',
				buttons: {
					ok: {
						label: '확인',
						className: "btn-<?php echo $this->config->item($this->uri->segment(1).'Color')?>"
					}
				}
			});
			return preventDefaultAction(false);
		}else{
			fn_idChk(pe_id);
		}
		
	});
	
});

/* 아이디 중복확인 */
function fn_idChk(pe_id){
	
	var data = {
		 "pe_id": pe_id
	};
	
	$.ajax({
		type: 'post',
		dataType: 'json',
		url: '<?php echo base_url();?>index.php/admin/User_write/idChk',
		data: data,
		async:false,
		success: function (data) {
			var msg = '';
			
			if(data != '0'){
				msg = '이미 사용중인 아이디입니다.';
				$('#frm_write').find('#PE_ID').val('');
				fn_needIdChk('N');
			}else{
				msg = '사용가능한 아이디입니다.';
				fn_needIdChk('Y');
			}
			
			bootbox.alert({
				size:'small',
				message : msg,
				buttons: {
					ok: {
						label: '확인',
						className: "btn-<?php echo $this->config->item($this->uri->segment(1).'Color')?>"
					}
				}
			});
			
		},
		error: function (request, status, error) {
			console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
		}
	});
}

/* 아이디 체크해야함 */
function fn_needIdChk(val){
	$('#frm_write').find('#idChk').val(val);
}

/* 아이디 중복확인 했는지 체크 */
function fn_checkId(){
	var idChk = $('#frm_write').find('#idChk').val();
	if(idChk != 'Y' && idChk == 'N'){
		bootbox.alert({
			size:'small',
			message : '아이디 중복확인을 해주세요.',
			buttons: {
				ok: {
					label: '확인',
					className: "btn-<?php echo $this->config->item($this->uri->segment(1).'Color')?>"
				}
			}
		});
		return false;
	}else{
		return true;
	}
}

/* 저장 */
function fn_save(){
	
	if ($('#frm_write').validator('validate').has('.has-error').length === 0) {
		
		if(!fn_checkId()) return false;
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
<?php include($_SERVER["DOCUMENT_ROOT"]."/application/views/com/Pop_compSearch.php"); ?>	<!-- 거래처 검색 팝업 -->
<div id="wp_right">
	
	<div class="grid_area">
	<!-- 작성,수정 -->
	<form id="frm_write" name="frm_write" class="p-20" data-toggle="validator" role="form" action="" method="post" enctype="multipart/form-data" >
		<div class="gray_border_bottom pb-20">
			<h3>유저정보 <small>Write</small></h3>
		</div>
		<br />
		
		<div class="form-group required"> 
			<label for="">아이디<span class="req-text" title="필수입력">*</span></label>
			<?php if(!$list){ ?>
			<div class="input-group">
				<input type="text" class="form-control" id="PE_ID" name="PE_ID" onchange="fn_needIdChk('N');" placeholder="아이디를 입력해주세요." value="<?php echo $PE_ID;?>" required>
				<div class="input-group-addon">
					<div class="btn_idChk">
						중복확인
					</div>
					<input type="hidden" id="idChk" name="idChk" value="<?php echo $ID_CHK;?>" />
				</div>
			</div>
			<?php }else{ ?>
			<div class="view_inputs gray_border_bottom pb-15">
				<input type="hidden" id="PE_ID" name="PE_ID" value="<?php echo $PE_ID;?>">
				<?php echo $PE_ID; ?>
			</div>
			<?php } ?>
			<span class="help-block with-errors"></span>
		</div>

		<div class="form-group required">
			<label for="">이름<span class="req-text" title="필수입력">*</span></label>
			<input type="text" class="form-control" id="PE_NM" name="PE_NM" placeholder="이름을 입력해주세요." value="<?php echo $PE_NM;?>" required>
			<span class="help-block with-errors"></span>
		</div> 

		<div class="form-group required">
			<label for="">비밀번호<span class="req-text" title="필수입력">*</span></label>
			<input type="text" class="form-control" id="PE_PWD" name="PE_PWD" placeholder="비밀번호를 입력해주세요." value="<?php echo $PE_PWD;?>" required>
			<span class="help-block with-errors"></span>
		</div> 	
		
		<div class="form-group">
			<label for="">연락처</label>
			<input type="text" class="form-control" id="PE_TEL" name="PE_TEL" placeholder="연락처를 입력해주세요." value="<?php echo $PE_TEL;?>">
			<span class="help-block with-errors"></span>
		</div>
		
		<div class="form-group">
			<label for="">직급</label>
			<input type="text" class="form-control" id="ETC2" name="ETC2" placeholder="직급을 입력해주세요(과장,부장)." value="<?php echo $ETC2;?>">
			<span class="help-block with-errors"></span>
		</div>

		<div class="form-group required">
			<label for="PG_ID">그룹</label>
			<select class="form-control" id="PG_ID" name="PG_ID">
				<option value="">그룹</option>
				<?php 
					if( $groupList != null ){
						$groupArr = '';
						foreach( $groupList as $data ){
							$groupArr .= '<option value="'.$data->PG_ID.'" '.(($data->PG_ID==$PG_ID)?"selected":"").'/>'.$data->PG_NM.'</option>';
						}
						echo $groupArr;
					}
				?>
			</select>
			<span class="help-block with-errors"></span>
		</div> 
		
		<div class="form-group">
		  <label for="PC_ID">거래처</label>
		   <?php 
				if( $compList != null ){
					$comps = '';
					$compArr = '';
					foreach( $compList as $data ){
						$comps .= $data->PC_NM .'  ';
						$compArr .= '<input type="hidden" id="PF_COMP" name="PF_COMP[]" value="'.$data->PC_ID.'"/><input type="hidden" id="PF_COMP_NM" name="PF_COMP_NM[]" value="'.$data->PC_NM.'"/>';
					}
					echo $compArr;
				}
			?>
			<div class="input-group">
			  <input id="PF_COMP_TEXT" name="PF_COMP_TEXT" type="text" data-comp="comp_input" class="form-control req-readonly" placeholder="오른쪽 검색버튼을 이용해 검색해주세요." value="<?php if($compList != null){ echo $comps; } ?>">
			  <!-- PF_COMP[] -->
			  <div class="input-group-addon btn_search_comp">
			    <span class="glyphicon glyphicon-search"></span>
			  </div>
			</div>
			<span class="help-block with-errors"></span>
		</div>
		
		<div class="form-group required">
			<label for="PE_AUTH">권한<span class="req-text" title="필수입력">*</span></label>
			<select class="form-control" id="PE_AUTH" name="PE_AUTH">
				<option value="user" <?php if($PE_AUTH == 'user'){echo 'selected';} ?>>사용자</option>
				<option value="emp" <?php if($PE_AUTH == 'emp'){echo 'selected';} ?>>작업자</option>
				<option value="admin" <?php if($PE_AUTH == 'admin'){echo 'selected';} ?>>시스템관리자</option>
			</select>
			<span class="help-block with-errors"></span>
		</div> 
		
	  <?php if($fileYns == 'Y'){?>
	  <div class="form-group">
	    <label for="">사진 (사진은 한장만 등록해 주세요. 다중 업로드시 첫번째 사진이 대표사진으로 지정됩니다.)</label>
	    <input type="file" id="PF_FILE" name="PF_FILE[]" accept=".jpg,.gif,.png">
	    
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
			<a onclick="if(!confirm('한번 삭제한 파일은 복구가 불가능합니다.\n그래도 삭제하시겠습니까?')) return false;" href="<?php echo site_url()?>/admin/User_write/delete?fileName=<?php echo $data->FILELIST_ID ?>&id=<?php echo $PE_ID;?>">
				<i class="fa fa-close" style="color:red"></i>
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
	 	<style>
	 		.file-actions,
	 		.file-drag-handle{
	 			display:none
	 		}
	 	</style>
	  <script>
	  	$("#PF_FILE").change(function(){
	  		//$('[type=checkbox]').attr('checked','checked');
	  	});
	  	
			$("#PF_FILE").fileinput({
			    //uploadUrl: "/file-upload-batch/2",
			    //uploadAsync: true,
			    language : "kr",
			    autoOrientImage: true,
			    overwriteInitial: false,
			    initialPreview: [
			    
			    <?php 
						$i = 0;
						if( $fileList != null ){
							
							foreach( $fileList as $data ){
								$i++;
					?>
			    	"<?php echo site_url()?>/pdm/Upload_view/fileDownload?tempName=<?php echo $data->PF_FILE_TEMP_NM ?>&fileName=<?php echo $data->PF_FILE_REAL_NM ?>",
			    <?php }} ?>
			    ],
			    initialPreviewAsData: true, // identify if you are sending preview data only and not the raw markup
			    initialPreviewFileType: 'image', // image is the default and can be overridden in config below
			    
			    initialPreviewConfig: [
			    <?php 
						$i = 0;
						if( $fileList != null ){
							
							foreach( $fileList as $data ){
								$i++;
					?>
        		{caption: "<?php echo $data->PF_FILE_REAL_NM ?>", size: <?php echo $data->PF_FILE_SIZE ?>, width: "120px", url: "", key: <?php echo $data->FILELIST_ID ?>},
        	<?php }} ?>
        	],
			    allowedFileExtensions: ["jpg", "png", "gif"],
			    previewFileIcon: '<i class="fa fa-file"></i>',
			    //allowedPreviewTypes: null, // set to empty, null or false to disable preview for all types
			    /*
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
	    */
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
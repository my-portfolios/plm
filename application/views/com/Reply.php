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
	$('.snote').summernote('disable');
	
	$.each($('.replyCont'),function(i,v){
		if($(v).height() > 100){
			$(v).css({
				"height": '100px',
				"margin-top": '10px',
				"position": 'relative',
				"overflow": 'hidden'
			});
			$(v).append('<div class="gra_bt" style="background : url(<?php echo base_url();?>img/gra_white.png) repeat-x left bottom;position: absolute;bottom: 0;height: 17px;z-index: 1;width: 100%;"></div>');
			$(document).on('click','.replyView',function(){
				$(this).next().css('height','auto');
				$(this).html('자세히 <span class="fa fa-sort-up" style="top: 3px;position: relative;"></span>');
				$(this).removeClass('replyView');
				$(this).addClass('replyViewClose');
				$(this).next().find('.gra_bt').hide();
			});
			$(document).on('click','.replyViewClose',function(){
				$(this).next().css('height','100px');
				$(this).html('자세히 <span class="fa fa-sort-down" style="top: -2px;position: relative;"></span>');
				$(this).removeClass('replyViewClose');
				$(this).addClass('replyView');
				$(this).next().find('.gra_bt').show();
			});
			$(v).before('<div class="rev replyView btn btn-default btn-xs">자세히 <span class="fa fa-sort-down" style="top: -2px;position: relative;"></span></div>');
		}
	});
	
});

$(function(){
	$('.reply_upd').css('display','none');
	
	/* 댓글 연필 버튼 */
	$(document).on('click','.btn_reply_upd',function(){
		var reply_id = $(this).attr('data-id');
		$('.view_'+reply_id).css('display','none');
		$('.upd_'+reply_id).css('display','');
	});
	
	/* 댓글 휴지통 버튼 */
	$(document).on('click','.btn_reply_del',function(){
		var reply_id = $(this).attr('data-id');
		bootbox.confirm({
			size: "small",
			message: "삭제하시겠습니까? ",
			buttons: {
				confirm: {
					label: '확인',
					className: "btn-<?php echo $this->config->item($this->uri->segment(1).'Color');?>"
				},
				cancel: {
					label: '취소'
				}
			},
			callback: function (result) {
				if(result == true){
					fn_reply_delete(reply_id);
				}
			}
		});	
	});
	
	/* 댓글 수정 버튼 */
	$(document).on('click','.btn_reply_bottom_upd',function(){
		var reply_id = $(this).attr('data-id');
		bootbox.confirm({
			size: "small",
			message: "수정하시겠습니까? ",
			buttons: {
				confirm: {
					label: '확인',
					className: "btn-<?php echo $this->config->item($this->uri->segment(1).'Color');?>"
				},
				cancel: {
					label: '취소'
				}
			},
			callback: function (result) {
				if(result == true){
					
					fn_update(reply_id);
					
				}
			}
		});	
		
	});
	
	/* 댓글 취소 버튼 */
	$(document).on('click','.btn_reply_bottom_cancel',function(){
		var reply_id = $(this).attr('data-id');
		$('.view_'+reply_id).css('display','');
		$('.upd_'+reply_id).css('display','none');
	});
	
	/* 신규 댓글 작성 버튼 */
	$(document).on('click','.btn_reply_save',function(){
		
		bootbox.confirm({
			size: "small",
			message: "등록하시겠습니까? ",
			buttons: {
				confirm: {
					label: '확인',
					className: "btn-<?php echo $this->config->item($this->uri->segment(1).'Color');?>"
				},
				cancel: {
					label: '취소'
				}
			},
			callback: function (result) {
				if(result == true){
					
					fn_save();
				
				}
			}
		});	
		
	});
	$(".files").fileinput({
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
});

function fn_save(){
	if ($('#frm_reply_write').validator('validate').has('.has-error').length === 0) {
		
		//로딩 구현
		$('#loading').modal('show');
		var fd = new FormData();
		$.each($('input[id=REPLY_FILE2]')[0].files,function(i,v){
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
		
		$('#frm_reply_write').attr('action','<?php echo base_url();?>index.php/com/Reply/save');
		$('#frm_reply_write').submit();
	}else{
		//console.log(111);
	}
}

function fn_update(reply_id){
	if ($('#frm_reply_'+reply_id).validator('validate').has('.has-error').length === 0) {
		
		//로딩 구현
		$('#loading').modal('show');
		var fd = new FormData();
		$.each($('input[id=REPLY_FILE]')[0].files,function(i,v){
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
		
		$('#frm_reply_'+reply_id).attr('action','<?php echo base_url();?>index.php/com/Reply/upd');
		$('#frm_reply_'+reply_id).submit();
	}else{
		console.log(111);
	}
}

/* 댓글 삭제 */
function fn_reply_delete(reply_id){
	
	var data = {
		 "reply_id": reply_id
		 ,"plm_type":"<?php echo $this->uri->segment(1); ?>"
	};
	
	$.ajax({
		type: 'post',
		dataType: 'json',
		url: '<?php echo base_url();?>index.php/com/Reply/delete',
		data: data,
		success: function (data) {
			$('#frm_reply_'+reply_id).remove();
			bootbox.alert({
				size: "small",
				message: "삭제되었습니다.",
				buttons: {
					ok: {
						label: '확인',
						className: "btn-<?php echo $this->config->item($this->uri->segment(1).'Color');?>"
					}
				}
			});
		},
		error: function (request, status, error) {
			console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
		}
	});
	
}
</script>

	<div class="p-20">
		<div class="gray_border_bottom pb-20">
		  <h3>댓글 <small>Reply</small></h3>
		</div>
		<br />
		<div>
			<ul class="media-list">
				<!--댓글 loop-->
				<?php 
					if( $replyList != null ){
						foreach( $replyList as $data ){
				?>
				<form id="frm_reply_<?php echo $data->REPLY_ID; ?>"  data-toggle="validator" role="form" action="" method="post" enctype="multipart/form-data">
				<input type="hidden" id="PLM_TYPE" name="PLM_TYPE" value="<?php echo $this->uri->segment(1); ?>"/>
				<input type="hidden" id="PARENT_ID" name="PARENT_ID" value="<?php echo $data->PARENT_ID; ?>"/>
				<input type="hidden" id="REPLY_ID" name="REPLY_ID" value="<?php echo $data->REPLY_ID; ?>"/>
				<!-- pdm에 첨부파일 등록 시 필요한 기본정보 -->
				<input type="hidden" id="PP_ID" name="PP_ID" value="<?php echo $_POST['PP_ID']; ?>"/>
				<input type="hidden" id="TITLE" name="TITLE" value="<?php echo $_POST['TITLE']; ?>"/>
				<input type="hidden" id="URI" name="URI" value="<?php echo $this->uri->segment(1); ?>"/>
				<!-- pdm에 첨부파일 등록 시 필요한 기본정보 -->
				<!-- 보기 시작 -->
				<li class="media pb-15 gray_border_bottom reply_view view_<?php echo $data->REPLY_ID; ?>">
					<div class="media-left">
						<script>
							$(function(){
								getPic("<?php echo $data->INS_ID;?>",'.my_pic_view_<?php echo $data->REPLY_ID; ?>');
							});
						</script>
						<div style="width: 70px;height:70px;overflow:hidden" class="my_pic_view_<?php echo $data->REPLY_ID; ?>">
						</div>
					</div>
					<div class="media-body">
					  <h4 class="media-heading"><div class="mb-10 m170"><?php echo $data->INS_NM; ?></div>
					  	<div>
					  	<script>
					  		$(function(){
					  			userYn("<?php echo $data->INS_ID; ?>","userSideBtn_<?php echo $data->REPLY_ID;?>");
					  		});
					  	</script>
					  	
					  	<?php
					  	$emps = '';
					  	$emps .= ' <span class="nav-item dropdown">';
				      $emps .= '<a class="m170 nav-link dropdown-toggle btn btn-default btn-xs userSideBtn_'.$data->REPLY_ID.'" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
				      $emps .= $data->INS_NM . '(' . $data->INS_ID . ')&nbsp;&nbsp;<span class="fa fa-sort-down" style="top: -2px;position: relative;"></span>';
				      $emps .= '</a> ';
				      $emps .= '<div class="dropdown-menu" aria-labelledby="navbarDropdown">';
							$emps .= '<a onclick=msgView("'.$data->INS_ID.'") class="dropdown-item">쪽지보내기</a>';
							$emps .= '<a onclick=infoView("'.$data->INS_ID.'") class="dropdown-item">정보보기</a>';
							$emps .= '</div>';
				      $emps .= '</span>';
				      echo $emps;
					  	?>
					  	</div>
					  </h4>
					  <span class="media-heading">
					  	<div class="mb-10">
					  	<?php echo $data->INS_DT; ?>
					  	</div>
					  </span>
					  <!--상세면-->
					  <div class="replyCont">
					  <?php echo $data->REPLY_CONT; ?>
					  </div>
					  <!--첨부된 파일이 있으면-->
						<div class="mt-10">
							첨부된 파일
							<?php 
								if( $replyFileList != null ){
									$i = 0;
									foreach( $replyFileList as $fileList ){
										if($fileList->PARENT_ID == $data->REPLY_ID ){
											$i++;
							?>
							<br/>
							<span class="extIcon"><?php echo $fileList->PF_FILE_EXT;?></span>
							<a href="<?php echo site_url()?>/pdm/Upload_view/fileDownload?tempName=<?php echo $fileList->PF_FILE_TEMP_NM ?>&fileName=<?php echo $fileList->PF_FILE_REAL_NM ?>">
								<?php echo $fileList->PF_FILE_REAL_NM ?>
							</a>
							<?php 
										}
									}
									if($i == 0) echo '<br />첨부된 파일이 없습니다.';
								}
							?>
						</div>
					  <!--첨부된 파일이 있으면_끝-->
					</div>
					<!--내댓글이거나 관리자면 or 권한이 있으면 보임-->
					<div class="media-right">
					<?php if($this->session->userdata('userauth') == 'admin' || $this->session->userdata('userid') == $data->INS_ID ){ ?>
						<div class="btn-group text-right" role="group" aria-label="..."  style="width:50px">
							<button type="button" class="btn btn-default btn-xs btn_reply_upd" data-id="<?php echo $data->REPLY_ID; ?>">
								<span class="glyphicon glyphicon-pencil"></span>
							</button>
							<button type="button" class="btn btn-default btn-xs btn_reply_del" data-id="<?php echo $data->REPLY_ID; ?>">
								<span class="glyphicon glyphicon-trash"></span>
							</button>
						</div>
					<?php } ?>
					</div>
					<!--내댓글이거나 관리자면 or 권한이 있으면 보임_끝-->
				</li>
			<!-- 보기 끝 -->
			<!-- 수정 시작 -->
			  <li class="media pb-15 gray_border_bottom reply_upd upd_<?php echo $data->REPLY_ID; ?>">
			    <div class="media-left">
			    	<script>
							$(function(){
								getPic("<?php echo $data->INS_ID;?>",'.my_pic_upd_<?php echo $data->REPLY_ID; ?>');
							});
						</script>
						<div style="width: 70px;height:70px;overflow:hidden" class="my_pic_upd_<?php echo $data->REPLY_ID; ?>">
						</div>
			    </div>
			    <div class="media-body">
			      <h4 class="media-heading">
			      	<div class="mb-10 m170">
			      		<?php echo $data->INS_NM; ?>
			      	</div>
			      	<div>
				      	<script>
						  		$(function(){
						  			userYn("<?php echo $data->INS_ID; ?>","userSideBtn_<?php echo $data->REPLY_ID;?>");
						  		});
						  	</script>
				      	<?php
						  	$emps = '';
						  	$emps .= ' <span class="nav-item dropdown">';
					      $emps .= '<a class="m170 nav-link dropdown-toggle btn btn-default btn-xs userSideBtn_'.$data->REPLY_ID.'" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
					      $emps .= $data->INS_NM . '(' . $data->INS_ID . ')&nbsp;&nbsp;<span class="fa fa-sort-down" style="top: -2px;position: relative;"></span>';
					      $emps .= '</a> ';
					      $emps .= '<div class="dropdown-menu" aria-labelledby="navbarDropdown">';
								$emps .= '<a onclick=msgView("'.$data->INS_ID.'") class="dropdown-item">쪽지보내기</a>';
								$emps .= '<a onclick=infoView("'.$data->INS_ID.'") class="dropdown-item">정보보기</a>';
								$emps .= '</div>';
					      $emps .= '</span>';
					      echo $emps;
						  	?>
					  	</div>
			      </h4>
			      <!--수정버튼누르면-->
					<textarea class="form-control snote" id="REPLY_CONT" name="REPLY_CONT" required>
						<?php echo $data->REPLY_CONT; ?>
					</textarea>
					<span class="help-block with-errors"></span>
			      <!--첨부된 파일이 있으면-->
			      <div class="form-group">
					<label for="">파일</label>
					<input type="file" id="REPLY_FILE" name="REPLY_FILE[]" class="files" multiple>
					<div class="mt-10">
						첨부된 파일
						<?php 
							if( $replyFileList != null ){
								$i = 0;
								foreach( $replyFileList as $fileList ){
									if($fileList->PARENT_ID == $data->REPLY_ID ){
										$i++;
						?>
						<br/>
						삭제
						<input type="checkbox" name="REPLY_FILE_DEL[]" value="<?php echo $fileList->FILELIST_ID ?>"/>
						<span class="extIcon"><?php echo $fileList->PF_FILE_EXT;?></span>
						<a href="<?php echo site_url()?>/pdm/Upload_view/fileDownload?tempName=<?php echo $fileList->PF_FILE_TEMP_NM ?>&fileName=<?php echo $fileList->PF_FILE_REAL_NM ?>">
							<?php echo $fileList->PF_FILE_REAL_NM ?>
						</a>
						<a onclick="if(!confirm('한번 삭제한 파일은 복구가 불가능합니다.\n그래도 삭제하시겠습니까?')) return false;" href="<?php echo site_url()?>/board/Write/delete?fileName=<?php echo $fileList->FILELIST_ID ?>&ID=<?php echo $_GET['id'];?>&C_ID=<?php echo $_GET['c_id'];?>">
							<i class="fa fa-close" style="color:red"></i>
						</a>
						<?php 
									}
								}
								if($i == 0) echo '<br />첨부된 파일이 없습니다.';
							}
						?>
					</div>
				  </div>
			      <!--첨부된 파일이 있으면_끝-->
				  
					<div class="text-center gray_border_top">
						<button type="button" class="btn_reply_bottom_upd mt-10 btn btn-<?php echo $this->config->item($this->uri->segment(1).'Color');?> btn-xs" data-id="<?php echo $data->REPLY_ID; ?>">댓글 수정</button>
						<button type="button" class="btn_reply_bottom_cancel mt-10 btn btn-default btn-xs" data-id="<?php echo $data->REPLY_ID; ?>">취소</button>
					</div>
			    </div>
			  </li>
			  </form>
			  <!-- 수정 끝 -->
			  <!--댓글 loop_끝-->
				<?php
						}
					} 
				?>
				
			</ul>
		</div>
		<br />		
		<form id="frm_reply_write" name="frm_reply_write" data-toggle="validator" role="form" action="" method="post" enctype="multipart/form-data" >
			<input type="hidden" id="PLM_TYPE" name="PLM_TYPE" value="<?php echo $this->uri->segment(1); ?>"/>
			<input type="hidden" id="PARENT_ID" name="PARENT_ID" value="<?php echo $_POST['PARENT_ID']; ?>"/>
			<input type="hidden" id="BOARD_ID" name="BOARD_ID" value="<?php echo $this->input->get('id'); ?>"/>
			<!-- pdm에 첨부파일 등록 시 필요한 기본정보 -->
			<input type="hidden" id="PP_ID" name="PP_ID" value="<?php echo $_POST['PP_ID']; ?>"/>
			<input type="hidden" id="TITLE" name="TITLE" value="<?php echo $_POST['TITLE']; ?>"/>
			<input type="hidden" id="URI" name="URI" value="<?php echo $this->uri->segment(1); ?>"/>
			<!-- pdm에 첨부파일 등록 시 필요한 기본정보 -->
			<div class="form-group required">
				<label for=""><?php echo $_SESSION['username'].'['.$_SESSION['userid'].']';?>님으로 댓글쓰기</label>
				<textarea class="form-control snote" id="REPLY_CONT" name="REPLY_CONT" required></textarea>
				<span class="help-block with-errors"></span>
			</div>
			<div class="form-group">
		    <label for="">파일</label>
		    <input type="file" id="REPLY_FILE2" name="REPLY_FILE[]" class="files" multiple>
		  </div>
		  
		  <div class="text-center gray_border_top">
				<button type="button" class="btn_reply_save mt-10 btn btn-<?php echo $this->config->item($this->uri->segment(1).'Color');?> btn-xs">댓글작성</button>
			</div>
		</form>
	 </div> 
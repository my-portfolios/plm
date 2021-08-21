<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
.filebox label { display: inline-block; padding: .5em .75em; color: #999; font-size: inherit; line-height: normal; vertical-align: middle; background-color: #fdfdfd; cursor: pointer; border: 1px solid #ebebeb; border-bottom-color: #e2e2e2; border-radius: .25em; } 
.filebox input[type="file"] { /* 파일 필드 숨기기 */ position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip:rect(0,0,0,0); border: 0; }
</style>
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
	$('.btn_cancel').click(function(){
		/*location.href="<?php echo base_url();?>index.php/board/Main";*/
		history.back();
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

	$(window).bind("beforeunload", function (e){
		return "저장하지 않은 정보는 소실될 수 있습니다.\n그래도 닫으시겠습니까?";
	});

});

/* 저장 */
function fn_save(){
	
	if ($('#frm_board_write').validator('validate').has('.has-error').length === 0) {
		
		//로딩 구현
		$('#loading').modal('show');
		var fd = new FormData();

		//가변파일에 의한 ID찾기
		var input_id ='PF_FILE';
		for(var i=1;i<100;i++) {
			if($('input[id=PF_FILE_F'+i+']').length>0){
				input_id = 'PF_FILE_F'+i;
				break;
			}
		}

		$.each($('input[id='+input_id+']')[0].files,function(i,v){
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
		
		var c_id ="<?php echo $this->input->get('id'); ?>";
	
		if(c_id == ""){
			$('#frm_board_write').attr('action','<?php echo base_url();?>index.php/board/Write/save');
		}else{
			$('#frm_board_write').attr('action','<?php echo base_url();?>index.php/board/Write/upd');
		}
		
		$('#frm_board_write').submit();
	}else{
		//필수항목
	}
		
}
</script>
<?php include($_SERVER["DOCUMENT_ROOT"]."/application/views/com/Pop_empSearch.php"); ?>	<!-- 담당자검색 팝업 -->
<?php include($_SERVER["DOCUMENT_ROOT"]."/application/views/com/Pop_pmsSearch.php"); ?>	<!-- 프로젝트검색 팝업 -->
<div id="wp_right">
	
	<div class="grid_area">
	<!-- 수정 -->
	<form id="frm_board_write" name="frm_board_write" class="p-20" data-toggle="validator" role="form" action="" method="post" enctype="multipart/form-data" >
		<input type="hidden" id="CONTS_ID" name="CONTS_ID" value="<?php if($list){ echo $list->CONTS_ID; } ?>"/>
		<div class="gray_border_bottom pb-20">
		  <h3>글쓰기 <small>Write</small></h3>
		</div>
		<br />
	  <div class="form-group">
	    <label for="">공지<span class="req-text"></span></label>
	    <input type="checkbox" id="BOARD_NOTICE" name="BOARD_NOTICE" value="Y" <?php if($list && strpos($list->BOARD_NOTICE, $list->CONTS_ID.",") !== false) echo("checked");?>>
	    <span class="help-block with-errors"></span>
	  </div>
	  <div class="form-group required">
	    <label for="">제목<span class="req-text" title="필수입력">*</span></label>
	    <input type="text" class="form-control" id="CONTS_TITLE" name="CONTS_TITLE" placeholder="제목을 입력해주세요." value="<?php if($list){ echo $list->CONTS_TITLE; } ?>" required>
	    <span class="help-block with-errors"></span>
	  </div>
	  <div class="form-group required">
	    <label for="">게시판<span class="req-text" title="필수입력">*</span></label>
	    <select class="form-control" id="PARENT_ID" name="PARENT_ID" required>
			<?php 
				if( $boardList != null ){
					foreach( $boardList as $data ){
			?>
				<option value="<?php echo $data->BOARD_ID ?>" <?php if( $list && $data->BOARD_ID == $list->PARENT_ID ){ echo 'selected'; } ?>><?php echo $data->BOARD_TITLE; ?></option>
			<?php			
					}
				} 
			?>
		</select>
	    <span class="help-block with-errors"></span>
	  </div>
	  <!--
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
			  <input id="PF_PMS_TEXT" name="PF_PMS_TEXT" type="text" data-pms="pms_input" class="form-control req-readonly" placeholder="오른쪽 검색버튼을 이용해 검색해주세요." aria-label="..." value="<?php if($pmsList != null){ echo $pmss; } ?>">
			  <div class="input-group-addon btn_search_pms">
			    <span class="glyphicon glyphicon-search"></span>
			  </div>
			</div>
			 <span class="help-block with-errors"></span>
		</div>
		
		<div class="form-group required">
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
			  <input id="PF_EMP_TEXT" name="PF_EMP_TEXT" type="text" data-emp="emp_input" class="form-control req-readonly" placeholder="오른쪽 검색버튼을 이용해 검색해주세요." value="<?php if($empList != null){ echo $emps; } ?>">
			  
			  <div class="input-group-addon btn_search_emp">
			    <span class="glyphicon glyphicon-search"></span>
			  </div>
			</div>
			<span class="help-block with-errors"></span>
		</div>
		
		-->
			
	  <div class="form-group required">
	    <label for="">내용<span class="req-text" title="필수입력">*</span></label>
	    <textarea class="form-control snote" id="CONTS_CONT" name="CONTS_CONT" required><?php if($list){ echo $list->CONTS_CONT; } ?></textarea>
	    <span class="help-block with-errors"></span>
	  </div>
	  <div class="form-group filebox">
		<label for="" onclick="filepopup();">파일업로드</label>
		<input name="PF_FILE[]" type="file" multiple="multiple" id="PF_FILE" />
		
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
		<a onclick="if(!confirm('한번 삭제한 파일은 복구가 불가능합니다.\n그래도 삭제하시겠습니까?')) return false;" href="<?php echo site_url()?>/board/Write/delete?fileName=<?php echo $data->FILELIST_ID ?>&ID=<?php echo $_GET['id'];?>&C_ID=<?php echo $_GET['c_id'];?>">
			<i class="fa fa-close" style="color:red"></i>
		</a>
		<?php 
				}
			} 
			if($i == 0) echo '<div class="mt-10">첨부된 파일이 없습니다.</div>';
		?>
	  </div>
		<script>
		function filepopup() {
			$(".MultiFile-wrap > input[type=file]:not('.displayNone')").trigger('click');
		}
		// wait for document to load
		$(function(){

			// invoke plugin
			$('#PF_FILE').MultiFile({
				onFileChange: function(){
				//console.log(this, arguments);
				$.each($('.MultiFile-list .extIcon'),function(i,v){
					var t = $(v).text();
					if(t!='') $(v).html(extIcon(t,'y'));
				});
				$.each($('.MultiFile-list .MultiFile-label'),function(i,v){
					$('.MultiFile-remove').html("<i class='fa fa-close' style='color:red'></i>");
				});
			}
			});

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
	<script src='<?php echo base_url();?>js/jquery.form.js' type="text/javascript" language="javascript"></script>
	<script src='<?php echo base_url();?>js/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
	<script src='<?php echo base_url();?>js/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
	</form>
	
</div>
</div>
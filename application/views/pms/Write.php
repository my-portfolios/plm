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
	$(document).on('click','#fullscrbtn',function(){
		$("#TWGanttArea").toggleClass('minHeightGantt');
	});
	$.each($('.extIcon'),function(i,v){
		var t = $(v).text();
		$(v).html(extIcon(t,'y'));
	});
});

$(function(){

	/* 취소 */
	$(document).on('click','.btn_cancel',function(){
		/*location.href="<?php echo base_url();?>index.php/pms/Main";*/
		history.back();
		
	});

	/* 담당자 검색 */
	$(document).on('click','.btn_search_emp',function(){
		$('#pop_empSearch').modal('show');
		$('#pop_empSearch').find('option').eq(0).attr('selected','selected');
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
	
	/* 거래처 검색 */
	$(document).on('click','.btn_search_comp',function(){
		$('#pop_compSearch').modal('show');
		$('#pop_compSearch').find('option').eq(0).attr('selected','selected');
	});

	/* 거래처 검색 */
	$(document).on('click','.btn_search_group',function(){
		$('#pop_groupSearch').modal('show');
		$('#pop_groupSearch').find('option').eq(0).attr('selected','selected');
	});

});

/* 저장 */
function fn_save(){
	
	var sc = ge.currentTask;
	if(sc){

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

		var pp_id ="<?php echo $this->input->get('id'); ?>"
		if(pp_id == ""){
			$('#frm_pms_write').attr('action','<?php echo base_url();?>index.php/pms/Write/save');
		}else{
			$('#frm_pms_write').attr('action','<?php echo base_url();?>index.php/pms/Write/upd');
		}
		
		$('[name=projectSc]').val(JSON.stringify(ge.saveProject()));
		
		$('#frm_pms_write').submit();
	}else{
		
		bootbox.alert({
				size:'small',
				message : '등록된 일정이 없습니다.',
			  buttons: {
		        ok: {
		            label: '확인',
		            className: "btn-<?php echo $this->config->item($this->uri->segment(1).'Color')?>"
		        }
		    }
		});
	}
}
//currentTask
</script>
<?php include($_SERVER["DOCUMENT_ROOT"]."/application/views/com/Pop_compSearch.php"); ?>	<!-- 거래처 검색 팝업 -->
<div id="wp_right">
	
	<div class="grid_area">
	<!-- 수정 -->
	<form id="frm_pms_write" name="frm_pms_write" class="p-20" data-toggle="validator" role="form" action="" method="post" enctype="multipart/form-data">
		<input type="hidden" name="projectSc">
		<input type="hidden" id="PP_ID" name="PP_ID" value="<?php if($list){ echo $list->PP_ID; } ?>"/>
		<div class="gray_border_bottom pb-20">
		  <h3>상세보기 <small>file detail view</small></h3>
		</div>
		<br />
	  <div class="form-group required">
	    <label for="">프로젝트명<span class="req-text" title="필수입력">*</span></label>
	    <input type="text" class="form-control" id="PP_NM" name="PP_NM" placeholder="프로젝트명을 입력해주세요." value="<?php if($list){ echo $list->PP_NM; } ?>" required>
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
		<div class="form-group">
			<label for="PF_GROUP">그룹</label>
			<?php 
			
				if( $groupList != null ){
					$groups = '';
					$groupArr = '';
					foreach( $groupList as $data ){
						$groups .= $data->PG_NM . '(' . $data->PG_ID . ')  ';
						$groupArr .= '<input type="hidden" id="PG_ID" name="PG_ID[]" value="'.$data->PG_ID.'"/>';
					}
					echo $groupArr;
				} 
				
			?>
			<div class="input-group">
				<input id="PF_GROUP_TEXT" name="PF_GROUP_TEXT" type="text" data-group="group_input" class="form-control req-readonly" placeholder="오른쪽 검색버튼을 이용해 검색해주세요." value="<?php if($groupList != null){ echo $groups; } ?>">
				<!-- PF_EMP[] -->
				<div class="input-group-addon btn_search_group">
			    <span class="glyphicon glyphicon-search"></span>
			  </div>
			</div>
			<span class="help-block with-errors"></span>
		</div>
		<div class="form-group required">
			<label for="PF_EMP">담당자<span class="req-text" title="필수입력">*</span></label>
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
				<input id="PF_EMP_TEXT" name="PF_EMP_TEXT" type="text" data-emp="emp_input" class="form-control req-readonly" placeholder="오른쪽 검색버튼을 이용해 검색해주세요."  required value="<?php if($empList != null){ echo $emps; } ?>">
				<!-- PF_EMP[] -->
				<div class="input-group-addon btn_search_emp">
			    <span class="glyphicon glyphicon-search"></span>
			  </div>
			</div>
			<span class="help-block with-errors"></span>
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
				?>
			<input id="PF_KEYWORD" name="PF_KEYWORD" type="text" data-role="tagsinput" class="form-control" placeholder="" aria-label="..." value="">
			</div>
		</div>
	   <div class="form-group required">
	    <label for="">프로젝트 일정<span class="req-text" title="필수입력">*</span></label>
	    <?php include($_SERVER["DOCUMENT_ROOT"]."/application/views/pms/Gantt.php"); ?>	<!-- 간트차트 -->
	    <!--
			<div class='input-group date datetimepicker'>
				<input type="text" class="form-control" id="PP_ST_DAT" name="PP_ST_DAT" placeholder="시작일" value="<?php if($list){ echo $list->PP_ST_DAT; } ?>" required>
				<span class="input-group-addon">
					<span class="glyphicon glyphicon-calendar"></span>
				</span>
			</div>
			<div class='input-group date datetimepicker'>
				<input type="text" class="form-control" id="PP_ED_DAT" name="PP_ED_DAT" placeholder="완료일" value="<?php if($list){ echo $list->PP_ED_DAT; } ?>" required>
				<span class="input-group-addon">
					<span class="glyphicon glyphicon-calendar"></span>
				</span>
			</div>
			<span class="help-block with-errors"></span>
			-->
	  </div>
	  <div class="form-group required">
	    <label for="">내용<span class="req-text" title="필수입력">*</span></label>
	    <textarea class="form-control snote" id="PP_CONT" name="PP_CONT" required><?php if($list){ echo $list->PP_CONT; } ?></textarea>
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
		<a onclick="if(!confirm('한번 삭제한 파일은 복구가 불가능합니다.\n그래도 삭제하시겠습니까?')) return false;" href="<?php echo site_url()?>/pms/Write/delete?fileName=<?php echo $data->FILELIST_ID ?>&id=<?php echo $this->input->get('id'); ?>">
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
		<br /><br />
	  <div id="content_ajax_gantt"></div>
	<script src='<?php echo base_url();?>js/jquery.form.js' type="text/javascript" language="javascript"></script>
	<script src='<?php echo base_url();?>js/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
	<script src='<?php echo base_url();?>js/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
	</form>
	

	
</div>
	
</div>



<?php include($_SERVER["DOCUMENT_ROOT"]."/application/views/com/Pop_groupSearch.php"); ?>	<!-- 그룹 검색 팝업 -->
<?php include($_SERVER["DOCUMENT_ROOT"]."/application/views/com/Pop_empSearch.php"); ?>	<!-- 담당자검색 팝업 -->
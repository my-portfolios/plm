<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$PageNm = 'bom';//seg 1
$PageType = 'Part_write';//seg 2

/*수정이면*/
if($list){
	$BP_ID   = $list->BP_ID;
	$BP_NM   = $list->BP_NM;
	$BP_STD  = $list->BP_STD;
	$BP_MTR  = $list->BP_MTR;
	$BP_WTB  = $list->BP_WTB;
	$BP_CONT = $list->BP_CONT;
	$INS_DT  = $list->INS_DT;
}else{
	$BP_ID = '';
	$BP_NM = '';
	$BP_STD = '';
	$BP_MTR = '';
	$BP_WTB = '';
	$BP_CONT = '';
	$INS_DT = '';
}

//첨부파일 사용유무
$fileYns = 'Y';

?>

<style>
.filebox label { display: inline-block; padding: .5em .75em; color: #999; font-size: inherit; line-height: normal; vertical-align: middle; background-color: #fdfdfd; cursor: pointer; border: 1px solid #ebebeb; border-bottom-color: #e2e2e2; border-radius: .25em; } 
.filebox input[type="file"] { /* 파일 필드 숨기기 */ position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip:rect(0,0,0,0); border: 0; }
</style>

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

	/* 거래처 검색 */
	$(document).on('click','.btn_search_comp',function(){
		$('#pop_compSearch').modal('show');
		$('#pop_compSearch').find('option').eq(0).attr('selected','selected');
	});
	
});

/* 저장 */
function fn_save(){
	
	if ($('#frm_write').validator('validate').has('.has-error').length === 0) {
		
		//로딩 구현
		$('#loading').modal('show');
		<?php if($fileYns == 'Y'){?>

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
		<input type="hidden" id="BP_ID" name="BP_ID" value="<?php echo $BP_ID;?>"/>
		<div class="gray_border_bottom pb-20">
		  <h3>부품정보 <small>Write</small></h3>
		</div>
		<br />
	  <div class="form-group required">
	    <label for="">부품명<span class="req-text" title="필수입력">*</span></label>
	    <input type="text" class="form-control" id="BP_NM" name="BP_NM" placeholder="부품명을 입력해주세요." value="<?php echo $BP_NM;?>" required>
	    <span class="help-block with-errors"></span>
	  </div>
	   
	  <div class="form-group required">
	    <label for="">규격<span class="req-text" title="필수입력">*</span></label>
	    <input type="text" class="form-control" id="BP_STD" name="BP_STD" placeholder="규격을 입력해주세요." value="<?php echo $BP_STD;?>" required>
	    <span class="help-block with-errors"></span>
	  </div> 
		
	  <div class="form-group required">
	    <label for="">재질<span class="req-text" title="필수입력">*</span></label>
	    <input type="text" class="form-control" id="BP_MTR" name="BP_MTR" placeholder="재질을 입력해주세요." value="<?php echo $BP_MTR;?>" required>
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
	    <label for="">구입처</label>
	    <input type="text" class="form-control" id="BP_WTB" name="BP_WTB" placeholder="구입처을 입력해주세요." value="<?php echo $BP_WTB;?>">
	    <span class="help-block with-errors"></span>
	  </div> 
			
	  <div class="form-group required">
	    <label for="">내용<span class="req-text" title="필수입력11">*</span></label>
			
	    <textarea class="form-control snote" id="BP_CONT" name="BP_CONT" required><?php echo $BP_CONT;?></textarea>
	    <span class="help-block with-errors"></span>
	  </div>
	  <?php if($fileYns == 'Y'){?>
		<div class="form-group filebox">
			<label for="" onclick="filepopup();">파일업로드</label>
			<input name="PF_FILE[]" type="file" multiple="multiple" id="PF_FILE" />
	    
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
			<a onclick="if(!confirm('한번 삭제한 파일은 복구가 불가능합니다.\n그래도 삭제하시겠습니까?')) return false;" href="<?php echo site_url()?>/bom/Part_write/delete?fileName=<?php echo $data->FILELIST_ID ?>&BP_ID=<?php echo $BP_ID;?>">
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
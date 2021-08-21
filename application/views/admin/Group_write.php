<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$PageNm = 'admin';//seg 1
$PageType = 'Group_write';//seg 2

/*수정이면*/
if($list->PG_NM){
	$PG_ID  = $list->PG_ID;
	$PG_NM  = $list->PG_NM;
	$PG_TEL = $list->PG_TEL;
	$INS_DT = $list->INS_DT;
}else{
	$PG_ID  = $list;
	$PG_NM  = '';
	$PG_TEL = '';
    $INS_DT = '';
}

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
	
	/* 구성원 */
	$(document).on('click','.btn_search_mem',function(){
		$('#pop_memSearch').modal('show');
		$('#pop_memSearch').find('option').eq(0).attr('selected','selected');
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
	
});

/* 저장 */
function fn_save(){
	
	if ($('#frm_write').validator('validate').has('.has-error').length === 0) {

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
<?php include($_SERVER["DOCUMENT_ROOT"]."/application/views/com/Pop_memSearch.php"); ?>	<!-- 거래처 검색 팝업 -->
<div id="wp_right">
	
	<div class="grid_area">
	<!-- 작성,수정 -->
	<form id="frm_write" name="frm_write" class="p-20" data-toggle="validator" role="form" action="" method="post" enctype="multipart/form-data" >
		<input type="hidden" name="PG_ID" value="<?php echo $PG_ID?>">
		<div class="gray_border_bottom pb-20">
			<h3>그룹정보 <small>Write</small></h3>
		</div>
		<br />

		<div class="form-group required">
			<label for="">그룹명<span class="req-text" title="필수입력">*</span></label>
			<input type="text" class="form-control" id="PG_NM" name="PG_NM" placeholder="이름을 입력해주세요." value="<?php echo $PG_NM;?>" required>
			<span class="help-block with-errors"></span>
		</div> 
		
		<div class="form-group">
			<label for="">전화번호</label>
			<input type="text" class="form-control" id="PG_TEL" name="PG_TEL" placeholder="전화번호를 입력해주세요." value="<?php echo $PG_TEL;?>">
			<span class="help-block with-errors"></span>
		</div>
		
		<div class="form-group">
		  <label for="PE_ID">구성원</label>
		   <?php 
				if( $memList != null ){
					$mems = '';
					$memArr = '';
					foreach( $memList as $data ){
						$mems .= $data->PE_NM .'('.$data->PE_ID.')  ';
						$memArr .= '<input type="hidden" id="PF_MEM" name="PF_MEM[]" value="'.$data->PE_ID.'"/>';
					}
					echo $memArr;
				}
			?>
			<div class="input-group">
			  <input id="PF_MEM_TEXT" name="PF_MEM_TEXT" type="text" data-mem="mem_input" class="form-control req-readonly" placeholder="오른쪽 검색버튼을 이용해 검색해주세요." value="<?php if($memList != null){ echo $mems; } ?>">
			  <!-- PF_MEM[] -->
			  <div class="input-group-addon btn_search_mem">
			    <span class="glyphicon glyphicon-search"></span>
			  </div>
			</div>
			<span class="help-block with-errors"></span>
		</div>

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
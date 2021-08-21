<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$PageNm = 'bom';//seg 1
$PageType = 'Cate_write';//seg 2

/*수정이면*/
if($list){
	$BC_ID   = $list->BC_ID;
	$BC_NM   = $list->BC_NM;
	$BC_CONT  = $list->BC_CONT;
	$INS_DT  = $list->INS_DT;
}else{
	$BC_ID = '';
	$BC_NM = '';
	$BC_CONT = '';
	$INS_DT = '';
}
//첨부파일 사용유무
$fileYns = 'N';

?>
<style>
	.ui-jqgrid-bdiv{
		/*스크롤사용x*/
		overflow-x:hidden!important
	}
	.ui-jqgrid-bdiv {
          min-height:100px;
  }
</style>
<script>
//EDIT 로드
$(window).load(function(){
	$('.snote').summernote({
		height: 200,          // 기본 높이값
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
	//부품정보 그리드
	$("#cateDtlList").jqGrid({//그리드 세팅
      url:'<?php echo site_url()?>'+'/<?php echo $PageNm?>/<?php echo $PageType?>/loadCateDtl?detail=<?php echo $BC_ID?>',      
      mtype : "POST",             
      datatype: "json",            
      colNames:['BCD_ID','부품명','규격','재질','BP_ID','등록일'],       
      colModel:[
          {name:'BCD_ID',index:'BCD_ID', width:100, align:"center", hidden:true},
          {name:'BP_NMS',index:'BP_NMS', width:100, align:"center"},
          {name:'BP_STDS',index:'BP_STDS', width:100, align:"center"},
          {name:'BP_MTRS',index:'BP_MTRS', width:100, align:"center"},
          {name:'BP_IDS',index:'BP_IDS', width:100, align:"center", hidden: true},
          /*
          {name:'BCD_AMT',index:'BCD_AMT', width:50, align:"center", hidden: true, editable:true,
          	formatter: function (cellvalue, options, rowObject, action) {
					    return '<input class="form-control text-right" name="BCD_AMT[]" value="'+cellvalue+'" type="text" />';
					  }
          },
          */
          {name:'INS_DT',index:'INS_DT', width:100, align:"center", formatter:"date", formatoptions:{newformat:"Y-m-d"}}
      ],
      
      rowNum:10000,
      height: '100%',
      //rowList:[30,100,500],
      //pager: '#cateDtlPage',
      sortname: 'INS_DT',
	  	sortorder: 'desc',
	  	sorttype: 'date',
	  	shrinkToFit: true,
      autowidth: true,
      viewrecords: true,
      //rownumbers: true,
      gridview: true,
      caption:"목록",
      multiselect: true,
      multiselectWidth: 60,
      loadBeforeSend:function(){
      	//기존 로딩 사요안함 style로 display none
      	$('#loading').modal("show");
      },
      loadComplete:function(data){
      	
		//$('.gridCnt').html('전체 :<strong>'+$("#"+list).getGridParam("records")+'</strong> 건');//카운트 넣기
		
		$("#cateDtlList").jqGrid('setGridWidth',$('#gbox_cateDtlList').parent().innerWidth());//넓이지정
		$('#loading').modal("hide");
      }
  });
  $(window).resize(function(){//리사이즈 이벤트
		$("#cateDtlList").jqGrid('setGridWidth',$('#gbox_cateDtlList').parent().innerWidth());//넓이지정
	});
	/* 추가 */
	$(document).on('click','.btn_search_bom',function(){
		$('#pop_bomSearch').modal('show');
		
		$("#bom_right").setGridParam({
			page:1
		}).trigger("reloadGrid");
		
		/*같은 내용 그리드 삭제*/
		var cdl = $("#cateDtlList").jqGrid('getDataIDs');
		var rows = cdl.length;
		for (var i = rows - 1; i >= 0; i--) {
			$('#bom_right').jqGrid('delRowData', cdl[i]);
		}
		//select 박스가 초기화됨(bug)
		$.each($('#pop_bomSearch').find('select'),function(i,v){
			$(v).find('option').eq(0).attr('selected','selected');
		});
		
	});
	//선택삭제
	$(document).on('click','.btn_search_bom_del',function(){
		
		var ids = $("#cateDtlList").jqGrid('getGridParam', 'selarrrow'); 
		if(ids.length > 0){
			var rows = ids.length;
			for (var i = rows - 1; i >= 0; i--) {
				$("#cateDtlList").jqGrid('delRowData', ids[i]);
			}
		}else{
		bootbox.alert({
				size:'small',
				message : '선택된 항목이 없습니다.',
				buttons: {
					ok: {
						label: '확인',
						className: "btn-<?php echo $this->config->item($PageNm.'Color')?>"
					}
				}
			});
		}
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
			<?php if($list){ ?>
				message: "저장하시겠습니까?<br />카테고리 수정시 등록된 제품도 같이 수정해주셔야 합니다.", 
			<?php }else{ ?>
				message: "저장하시겠습니까?", 
			<?php } ?>
			buttons: {
		        confirm: {
		            label: '확인',
		            className: "btn-<?php echo $this->config->item($PageNm.'Color');?>"
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
		
		//부품정보 append
		var cateDtlList = $("#cateDtlList").jqGrid('getRowData');
		$.each(cateDtlList,function(i,v){
			$('#frm_write').append('<input type="hidden" name="BP_IDS[]" value="'+v.BP_IDS+'" />');
			$('#frm_write').append('<input type="hidden" name="BCD_ID[]" value="'+v.BCD_ID+'" />');
		});
		
		$('#frm_write').submit();
	}else{
		//필수항목
	}
}
</script>

<?php include($_SERVER["DOCUMENT_ROOT"]."/application/views/com/Pop_bomSearch.php"); ?>	<!-- 봄(부품)검색 팝업 -->

<div id="wp_right">
	
	<div class="grid_area">
	<!-- 작성,수정 -->
	<form id="frm_write" name="frm_write" class="p-20" data-toggle="validator" role="form" action="" method="post" enctype="multipart/form-data" >
		<input type="hidden" id="BC_ID" name="BC_ID" value="<?php echo $BC_ID;?>"/>
		<div class="gray_border_bottom pb-20">
		  <h3>카테고리정보 <small>Write</small></h3>
		</div>
		<br />
	  <div class="form-group required">
	    <label for="">카테고리명<span class="req-text" title="필수입력">*</span></label>
	    <input type="text" class="form-control" id="BC_NM" name="BC_NM" placeholder="카테고리명을 입력해주세요." value="<?php echo $BC_NM;?>" required>
	    <span class="help-block with-errors"></span>
	  </div>
			
	  <div class="form-group required">
	    <label for="">내용<span class="req-text" title="필수입력">*</span></label>
	    <textarea class="form-control snote" id="BC_CONT" name="BC_CONT" required><?php echo $BC_CONT;?></textarea>
	    <span class="help-block with-errors"></span>
	  </div>
	  
	  <?php if($fileYns == 'Y'){//첨부파일 사용이면 Y : N?>
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
	  
	  <div class="form-group required">
	  	<div class="mb-10">
	  		<label for="">부품선택</label>
	  		<div class="pull-right">
			  	<a class="btn_search_bom btn-default btn btn-xs">부품추가</a>
			  	<a class="btn_search_bom_del btn-default btn btn-xs">선택삭제</a>
		  	</div>
	  	</div>
	  	<div style="height:2px;overflow:hidden;" class="bg-<?php echo $this->config->item($PageNm.'Color');?>"></div>
	    <table id="cateDtlList"></table>
	    <div id="cateDtlPage"></div>
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
	  <div class="text-center">
			<button type="button" class="btn btn_save btn-<?php echo $this->config->item($PageNm.'Color');?> btn-sm mt-10 ">
				저장
			</button>
			<button type="button" class="btn btn_cancel btn-default btn-sm mt-10 ">
				취소
			</button>
		</div>
	  
	</form>
	
</div>
</div>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$PageNm = 'bom';//seg 1
$PageType = 'Pdt_write';//seg 2

/*수정이면*/
if($list){
	$BPD_ID   = $list->BPD_ID;
	$BPD_CD   = $list->BPD_CD;
	$BPD_NM   = $list->BPD_NM;
	$BPD_CONT  = $list->BPD_CONT;
	$INS_DT  = $list->INS_DT;
}else{
	$BPD_ID   = '';
	$BPD_CD   = '';
	$BPD_NM   = '';
	$BPD_CONT  = '';
	$INS_DT  = '';
}
//첨부파일 사용유무
$fileYns = 'Y';

?>
<style>
	.ui-jqgrid-bdiv{
		/*스크롤사용x*/
		overflow-x:hidden!important
	}
	.tablediv,
	.ui-subgrid,
	.subgrid-cell{
		background: #f9f9f9
	}
	.ui-subgrid .ui-jqgrid tr.jqgrow{
		background:#fff!important
	}
	.ui-icon-carat-1-sw{
		background-position: -76px 1px;
		margin-left: 3px;
		display:none;
	}
	.ui-jqgrid .ui-widget-content{
		border:none
	}
	.subgrid-cell{
		    border-right: 1px dashed #ddd!important;
	}
	
	.ui-jqgrid-bdiv {
          min-height:100px;
  }
  .ui-subgrid .ui-jqgrid-bdiv{
  	       min-height:auto;
  }
  .ui-subgrid{
  	border-bottom:1px solid #ddd;
  }
  .subgrid-data{
  	border-bottom:none!important
  }

  .filebox label { display: inline-block; padding: .5em .75em; color: #999; font-size: inherit; line-height: normal; vertical-align: middle; background-color: #fdfdfd; cursor: pointer; border: 1px solid #ebebeb; border-bottom-color: #e2e2e2; border-radius: .25em; } 
  .filebox input[type="file"] { /* 파일 필드 숨기기 */ position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip:rect(0,0,0,0); border: 0; }
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
      url:'<?php echo site_url()?>'+'/<?php echo $PageNm?>/<?php echo $PageType?>/loadCateDtl?detail=<?php echo $BPD_ID?>',      
      mtype : "POST",             
      datatype: "json",            
      colNames:['BCD_ID','부품명','규격','재질','BP_ID','개수','등록일'],       
      colModel:[
          {name:'BCD_ID',index:'BCD_ID', width:100, align:"center", hidden:true},
          {name:'BP_NMS',index:'BP_NMS', width:100},
          {name:'BP_STDS',index:'BP_STDS', width:100, align:"center"},
          {name:'BP_MTRS',index:'BP_MTRS', width:100, align:"center"},
          {name:'BP_IDS',index:'BP_IDS', width:100, align:"center", hidden: true},
          {name:'BCD_AMT',index:'BCD_AMT', width:50, align:"center", editable:true, sortable: false,
          	formatter: function (cellvalue, options, rowObject, action) {
          		if(cellvalue == 0){
          			cellvalue = 1;
          		}
				return '<input class="form-control text-right" name="BCD_AMT[]" value="'+cellvalue+'" type="text" />';
			}
          },
          {name:'INS_DT',index:'INS_DT', width:100, align:"center", formatter:"date", formatoptions:{newformat:"Y-m-d"}}
      ],
      
      rowNum:10000,
      height: '100%',
      width: 568,
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
  
  //카테고리정보 그리드
	$("#cateList").jqGrid({//그리드 세팅
      url:'<?php echo site_url()?>'+'/<?php echo $PageNm?>/<?php echo $PageType?>/loadCate?detail=<?php echo $BPD_ID?>',      
      mtype : "POST",             
      datatype: "json",            
      colNames:['BC_ID','카테고리명','등록일'],       
      colModel:[
          {name:'BC_ID',index:'BC_ID', width:100, align:"center", hidden:true},
          {name:'BC_NMS',index:'BC_NMS', width:100},
          {name:'INS_DT',index:'INS_DT', width:100, align:"center", formatter:"date", formatoptions:{newformat:"Y-m-d"}}
      ],
      
      rowNum:10000,
      height: '100%',
      //rowList:[30,100,500],
      //pager: '#catePage',
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
			
			$("#cateList").jqGrid('setGridWidth',$('#gbox_cateList').parent().innerWidth());//넓이지정
			$('#loading').modal("hide");
      },
      //서브테이블 불러오기
      
      subGrid: true,
	    subGridRowExpanded: function(subgrid_id, row_id) {
	       var subgrid_table_id;
	       subgrid_table_id = subgrid_id+"_t";
	       jQuery("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table>");
	       jQuery("#"+subgrid_table_id).jqGrid({
	       		url:'<?php echo site_url()?>'+'/<?php echo $PageNm?>/<?php echo $PageType?>/loadInDtl?detail='+row_id+'&pdt_id=<?php echo $this->input->get('id'); ?>',      
	          datatype: "json",
	          colNames: ['CATE_DTL_ID','BP_ID','부품명','규격','재질','개수','등록일'],
	          colModel: [
				{name:"CATE_DTL_ID",index:"CATE_DTL_ID",width:80, hidden:true,
					formatter: function (cellvalue, options, rowObject, action) {
						if(cellvalue=='no') return '';
						else
					    return '<input class="form-control text-right" name="CATE_DTL_ID[]" value="'+cellvalue+'" type="hidden" />';
					}
				},
	            {name:"BP_ID",index:"BP_ID",width:80, hidden:true,
					formatter: function (cellvalue, options, rowObject, action) {
						if(cellvalue=='no') return '';
						else
					    return '<input class="form-control text-right" name="BP_ID[]" value="'+cellvalue+'" type="hidden" /><input class="form-control text-right" name="ROW_ID[]" value="'+row_id+'" type="hidden" />';
					}
				},
	            {name:"BP_NM",index:"BP_NM",width:130, sortable: false},
	            {name:"BP_STD",index:"BP_STD",width:80,align:"center", sortable: false},
	            {name:"BP_MTR",index:"BP_MTR",width:80,align:"center", sortable: false},
	            {name:"BP_AMT",index:"BP_AMT",width:80,align:"center", sortable: false,
					formatter: function (cellvalue, options, rowObject, action) {
						if(cellvalue == 0){
          			cellvalue = 1;
          		}
				 	 if(cellvalue=='no') return '';
						else
					    return '<input class="form-control text-right" name="BP_AMT[]" value="'+cellvalue+'" type="text" />';
					}
				},
	            {name:"INS_DT",index:"INS_DT",width:100,hidden:true}
	          ],
	          height: '100%',
	          width: $('.tablediv').width(),
	          rowNum:10000,
	          sortname: 'INS_DT',
	          sortorder: "dest",
	          loadComplete:function(data){
							var subgridLen = $('#gview_'+subgrid_table_id).find('.ui-jqgrid-bdiv tbody tr').size();
							if(subgridLen == 1){
								jQuery("#"+subgrid_table_id).jqGrid('addRow', {
									rowID : 1,
									initdata : {CATE_DTL_ID : 'no', BP_ID : 'no', BP_NM : '내역이 없습니다.', BP_STD : '', BP_MTR : '', BP_AMT : 'no', INS_DT : ''}
								});								
							}
	          }
	       });
	   }
      
      
  });
  
  $(window).resize(function(){//리사이즈 이벤트
		$("#cateDtlList").jqGrid('setGridWidth',$('#gbox_cateDtlList').parent().innerWidth());//넓이지정
		$("#cateList").jqGrid('setGridWidth',$('#gbox_cateList').parent().innerWidth());//넓이지정
		
	});
	/* 추가(부품) */
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
	/* 추가(카테고리) */
	$(document).on('click','.btn_search_bom_cate',function(){
		$('#pop_bomSearch_cate').modal('show');
		
		$("#bom_cate_right").setGridParam({
			page:1
		}).trigger("reloadGrid");
		
		/*같은 내용 그리드 삭제*/
		var cdl = $("#cateList").jqGrid('getDataIDs');
  	var rows = cdl.length;
		for (var i = rows - 1; i >= 0; i--) {
			$('#bom_cate_right').jqGrid('delRowData', cdl[i]);
		}
		
		//select 박스가 초기화됨(bug)
		$.each($('#pop_bomSearch_cate').find('select'),function(i,v){
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
	
	$(document).on('click','.btn_search_bom_cate_del',function(){
		
		var ids = $("#cateList").jqGrid('getGridParam', 'selarrrow'); 
		
		if(ids.length > 0){
			var rows = ids.length;
			for (var i = rows - 1; i >= 0; i--) {
				$('#cateList_'+ids[i]).parent().parent().remove();//서브그리드같이 삭제 
				$("#cateList").jqGrid('delRowData', ids[i]);
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
			message: "저장하시겠습니까?", 
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
		
		//부품정보 append
		
		var cateDtlList = $("#cateDtlList").jqGrid('getRowData');
		$.each(cateDtlList,function(i,v){
			$('#frm_write').append('<input type="hidden" name="BP_IDS[]" value="'+v.BP_IDS+'" />');
			$('#frm_write').append('<input type="hidden" name="BCD_ID[]" value="'+v.BCD_ID+'" />');
		});
		
		//카테고리정보 append 저장전 서브그리드 모두 펼침
		var cateRowIds = $("#cateList").getDataIDs();
    $.each(cateRowIds, function (index, rowId) {
            $("#cateList").expandSubGridRow(rowId); 
    });
		var cateList = $("#cateList").jqGrid('getRowData');
		$.each(cateList,function(i,v){
			$('#frm_write').append('<input type="hidden" name="BC_ID[]" value="'+v.BC_ID+'" />');
		});
		setTimeout(function(){
			$('#frm_write').submit();
		},100);
	}else{
		//필수항목
	}
}
</script>

<?php include($_SERVER["DOCUMENT_ROOT"]."/application/views/com/Pop_bomSearch.php"); ?>	<!-- 봄(부품)검색 팝업 -->
<?php include($_SERVER["DOCUMENT_ROOT"]."/application/views/com/Pop_bomSearch_cate.php"); ?>	<!-- 봄(카테고리)검색 팝업 -->

<div id="wp_right">
	
	<div class="grid_area">
	<!-- 작성,수정 -->
	<form id="frm_write" name="frm_write" class="p-20" data-toggle="validator" role="form" action="" method="post" enctype="multipart/form-data" >
		<input type="hidden" id="BPD_ID" name="BPD_ID" value="<?php echo $BPD_ID;?>"/>
		<div class="gray_border_bottom pb-20">
		  <h3>제품정보 <small>Write</small></h3>
		</div>
		<br />
	  <div class="form-group required">
	    <label for="">제품명<span class="req-text" title="필수입력">*</span></label>
	    <input type="text" class="form-control" id="BPD_NM" name="BPD_NM" placeholder="제품명을 입력해주세요." value="<?php echo $BPD_NM;?>" required>
	    <span class="help-block with-errors"></span>
	  </div>
	  
	  <div class="form-group required">
	    <label for="">제품코드<span class="req-text" title="필수입력">*</span></label>
	    <input type="text" class="form-control" id="BPD_CD" name="BPD_CD" placeholder="제품코드를 입력해주세요." value="<?php echo $BPD_CD;?>" required>
	    <span class="help-block with-errors"></span>
	  </div>
			
	  <div class="form-group required">
	    <label for="">내용<span class="req-text" title="필수입력">*</span></label>
	    <textarea class="form-control snote" id="BPD_CONT" name="BPD_CONT" required><?php echo $BPD_CONT;?></textarea>
	    <span class="help-block with-errors"></span>
	  </div>
	  
	  <?php if($fileYns == 'Y'){//첨부파일 사용이면 Y : N?>
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
			<a onclick="if(!confirm('한번 삭제한 파일은 복구가 불가능합니다.\n그래도 삭제하시겠습니까?')) return false;" href="<?php echo site_url()?>/bom/Pdt_write/delete?fileName=<?php echo $data->FILELIST_ID ?>&BPD_ID=<?php echo $BPD_ID;?>">
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
	  
	  <div class="form-group required">
	  	<div class="mb-10">
	  		<label for="">부품 카테고리 선택</label>
	  		<div class="pull-right">
			  	<a class="btn_search_bom_cate btn-default btn btn-xs">부품카테고리추가</a>
			  	<a class="btn_search_bom_cate_del btn-default btn btn-xs">선택삭제</a>
		  	</div>
	  	</div>
	  	<div style="height:2px;overflow:hidden;" class="bg-<?php echo $this->config->item($PageNm.'Color');?>"></div>
	    <table id="cateList"></table>
	    <div id="catePage"></div>
	  </div>
	  
	  <div class="form-group required">
	  	<div class="mb-10">
	  		<label for="">추가부품선택</label>
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
	  <div class="text-center">
			<button type="button" class="btn btn_save btn-<?php echo $this->config->item($PageNm.'Color');?> btn-sm mt-10 ">
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
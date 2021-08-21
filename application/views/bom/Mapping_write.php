<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$PageNm = 'bom';//seg 1
$PageType = 'Mapping_write';//seg 2

if($list){
	$PP_ID   = $list->PP_ID;
	$PP_NM   = $list->PP_NM;
}else{
	$PP_ID   = '';
	$PP_NM   = '';
}

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
      url:'<?php echo site_url()?>'+'/<?php echo $PageNm?>/<?php echo $PageType?>/loadCateDtl?detail=<?php echo $PP_ID?>',      
      mtype : "POST",             
      datatype: "json",            
      colNames:['BP_ID','부품명','규격','재질','개수'],       
      colModel:[
          {name:'BP_IDS',index:'BP_IDS', width:100, align:"center", hidden: true},
          {name:'BP_NMS',index:'BP_NMS', width:100},
          {name:'BP_STDS',index:'BP_STDS', width:100, align:"center"},
          {name:'BP_MTRS',index:'BP_MTRS', width:100, align:"center"},
		  {name:'BCD_AMT',index:'BCD_AMT', width:50, align:"center", editable:true, sortable: false,
          	formatter: function (cellvalue, options, rowObject, action) {
          		if(cellvalue == 0){
          			cellvalue = 1;
          		}
				return '<input class="form-control text-right" name="BCD_AMT[]" value="'+cellvalue+'" type="text" />';
			}
          }
      ],
      rowNum:30,
      height: '100%',
      rowList:[30,100,500],
      pager: '#cateDtlPage',
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
      url:'<?php echo site_url()?>'+'/<?php echo $PageNm?>/<?php echo $PageType?>/loadCate?detail=<?php echo $PP_ID?>',      
      mtype : "POST",             
      datatype: "json",            
      colNames:['BC_ID','카테고리명'],       
      colModel:[
          {name:'BC_ID',index:'BC_ID', width:100, align:"center", hidden:true},
          {name:'BC_NMS',index:'BC_NMS', width:100}
      ],
      
      rowNum:30,
      height: '100%',
      rowList:[30,100,500],
      pager: '#catePage',
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
	       		url:'<?php echo site_url()?>'+'/<?php echo $PageNm?>/<?php echo $PageType?>/loadInDtl_cate?detail='+row_id,      
	          datatype: "json",
	          colNames: ['BP_ID','부품명','규격','재질','개수'],
	          colModel: [
	            {name:"BP_ID",index:"BP_ID",width:80,hidden:true},
	            {name:"BP_NM",index:"BP_NM",width:130, sortable: false},
	            {name:"BP_STD",index:"BP_STD",width:80,align:"center", sortable: false},
	            {name:"BP_MTR",index:"BP_MTR",width:80,align:"center", sortable: false},      
	            {name:"BCD_AMT",index:"BCD_AMT",width:80,align:"center", sortable: false , hidden: true}
	          ],
	          height: '100%',
	          width: $('.tablediv').width(),
	          rowNum:20,
	          sortname: 'INS_DT',
	          sortorder: "desc",
	          loadComplete:function(data){
	          	var subgridLen = $('#gview_'+subgrid_table_id).find('.ui-jqgrid-bdiv tbody tr').size();
				if(subgridLen == 1){
					jQuery("#"+subgrid_table_id).jqGrid('addRow', {
						rowID : 1,
						initdata : {BP_NM : '내역이 없습니다.'}
					});								
				}
				
	          }
	       });
	   }
      
      
  });
  
  //제품정보 그리드
	$("#pdtList").jqGrid({//그리드 세팅
		url:'<?php echo site_url()?>'+'/<?php echo $PageNm?>/<?php echo $PageType?>/loadPdt?detail=<?php echo $PP_ID?>',      
		mtype : "POST",             
		datatype: "json",            
		colNames:['BPD_ID','제품코드','제품명'],       
		colModel:[
			{name:'BPD_ID',index:'BPD_ID', width:100, align:"center", hidden:true},
			{name:'BPD_CD',index:'BPD_CD', width:100},
			{name:'BPD_NM',index:'BPD_NM', width:100, align:"center"},
		],
		rowNum:30,
		height: '100%',
		rowList:[30,100,500],
		pager: '#pdtPage',
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

			$("#pdtList").jqGrid('setGridWidth',$('#gbox_pdtList').parent().innerWidth());//넓이지정
			$('#loading').modal("hide");
		},
		//서브테이블 불러오기
		subGrid: true,
		subGridRowExpanded: function(subgrid_id, row_id) {
			var subgrid_table_id;
			subgrid_table_id = subgrid_id+"_t";
			jQuery("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table>");
			jQuery("#"+subgrid_table_id).jqGrid({
				url:'<?php echo site_url()?>'+'/<?php echo $PageNm?>/<?php echo $PageType?>/loadPdtDtl?detail='+row_id,      
				datatype: "json",
				colNames: ['BC_ID','구분','카테고리/부품명','규격','재질','개수'],
				colModel: [
					{name:"BC_ID",index:"BC_ID",width:80,hidden:true},
					{name:"GUBUN",index:"GUBUN",width:30,align:"left"},
					{name:"BC_NM",index:"BC_NM",width:130, sortable: false},
					{name:"BP_STD",index:"BP_STD",width:30,align:"center", sortable: false},
					{name:"BP_MTR",index:"BP_MTR",width:30,align:"center", sortable: false},
					{name:"BP_AMT",index:"BP_AMT",width:30,align:"center", sortable: false}
				],
				height: '100%',
				width: $('.tablediv').width(),
				rowNum:20,
				sortname: 'INS_DT',
				sortorder: "desc",
				loadComplete:function(data){
					$.each($('#'+subgrid_id).find('tr[id^=PART_]'),function(i,v){
						$(v).find('td').eq(0).find('a').remove();
						$(v).find('td').eq(0).removeClass();
					});

					var subgridLen = $('#gview_'+subgrid_table_id).find('.ui-jqgrid-bdiv tbody tr').size();
					if(subgridLen == 1){	
						jQuery("#"+subgrid_table_id).jqGrid('addRow', {
							rowID : 1,
							initdata : {GUBUN : '내역이 없습니다.'}
						});								
					}
				},
				//서브테이블 불러오기

				subGrid: true,
				subGridRowExpanded: function(subgrid_id, row_id) {
					var pdt_id = $(this).parents('tr').prev('tr').attr('id');
					var subgrid_table_id;
					subgrid_table_id = subgrid_id+"_t";
					jQuery("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table>");
					jQuery("#"+subgrid_table_id).jqGrid({
						url:'<?php echo site_url()?>'+'/<?php echo $PageNm?>/<?php echo $PageType?>/loadInDtl?detail='+row_id+'&pdt_id='+pdt_id,      
						datatype: "json",
						colNames: ['BP_ID','부품명','규격','재질','개수'],
						colModel: [
							{name:"BP_ID",index:"BP_ID",width:80,hidden:true},
							{name:"BP_NM",index:"BP_NM",width:130, sortable: false},
							{name:"BP_STD",index:"BP_STD",width:80,align:"center", sortable: false},
							{name:"BP_MTR",index:"BP_MTR",width:80,align:"center", sortable: false},           
							{name:"BCD_AMT",index:"BCD_AMT",width:80,align:"center", sortable: false}
						],
						height: '100%',
						width: $('.tablediv').width(),
						rowNum:20,
						sortname: 'INS_DT',
						sortorder: "desc",
						loadComplete:function(data){
							var subgridLen = $('#gview_'+subgrid_table_id).find('.ui-jqgrid-bdiv tbody tr').size();
							if(subgridLen == 1){
								jQuery("#"+subgrid_table_id).jqGrid('addRow', {
									rowID : 1,
									initdata : {BP_NM : '내역이 없습니다.'}
								});								
							}
						}
					});
				}
			});
			
		}
      
  });
  
  $(window).resize(function(){//리사이즈 이벤트
		$("#cateDtlList").jqGrid('setGridWidth',$('#gbox_cateDtlList').parent().innerWidth());//넓이지정
		$("#cateList").jqGrid('setGridWidth',$('#gbox_cateList').parent().innerWidth());//넓이지정
		$("#pdtList").jqGrid('setGridWidth',$('#gbox_pdtList').parent().innerWidth());//넓이지정
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
	
	/* 추가(제품) */
	$(document).on('click','.btn_search_bom_pdt',function(){
		$('#pop_bomSearch_pdt').modal('show');
		
		$("#bom_pdt_right").setGridParam({
			page:1
		}).trigger("reloadGrid");
		
		/*같은 내용 그리드 삭제*/
		var cdl = $("#pdtList").jqGrid('getDataIDs');
		var rows = cdl.length;
		for (var i = rows - 1; i >= 0; i--) {
			$('#bom_pdt_right').jqGrid('delRowData', cdl[i]);
		}
		
		//select 박스가 초기화됨(bug)
		$.each($('#pop_bomSearch_pdt').find('select'),function(i,v){
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
	
	$(document).on('click','.btn_search_bom_pdt_del',function(){
		
		var ids = $("#pdtList").jqGrid('getGridParam', 'selarrrow'); 
		
		if(ids.length > 0){
			var rows = ids.length;
			for (var i = rows - 1; i >= 0; i--) {
				$('#pdtList_'+ids[i]).parent().parent().remove();//서브그리드같이 삭제 
				$("#pdtList").jqGrid('delRowData', ids[i]);
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
		
		var id ="<?php echo $this->input->get('id'); ?>";
	
		$('#frm_write').attr('action','<?php echo base_url();?>index.php/<?php echo $PageNm?>/<?php echo $PageType?>/save');
		
		//제품정보 append
		var pdtList = $("#pdtList").jqGrid('getRowData');
		$.each(pdtList,function(i,v){
			$('#frm_write').append('<input type="hidden" name="BPD_ID[]" value="'+v.BPD_ID+'" />');
		});
		
		//부품정보 append
		var cateDtlList = $("#cateDtlList").jqGrid('getRowData');
		$.each(cateDtlList,function(i,v){
			$('#frm_write').append('<input type="hidden" name="BP_IDS[]" value="'+v.BP_IDS+'" />');
			$('#frm_write').append('<input type="hidden" name="BCD_ID[]" value="'+v.BCD_ID+'" />');
		});
		
		//카테고리정보 append
		var cateList = $("#cateList").jqGrid('getRowData');
		$.each(cateList,function(i,v){
			$('#frm_write').append('<input type="hidden" name="BC_ID[]" value="'+v.BC_ID+'" />');
		});
		
		$('#frm_write').submit();
	}else{
		//필수항목
	}
}
</script>

<?php include($_SERVER["DOCUMENT_ROOT"]."/application/views/com/Pop_bomSearch.php"); ?>	<!-- 봄(부품)검색 팝업 -->
<?php include($_SERVER["DOCUMENT_ROOT"]."/application/views/com/Pop_bomSearch_cate.php"); ?>	<!-- 봄(카테고리)검색 팝업 -->
<?php include($_SERVER["DOCUMENT_ROOT"]."/application/views/com/Pop_bomSearch_pdt.php"); ?>	<!-- 봄(제품)검색 팝업 -->

<div id="wp_right">
	
	<div class="grid_area">
	<!-- 작성,수정 -->
	<form id="frm_write" name="frm_write" class="p-20" data-toggle="validator" role="form" action="" method="post" enctype="multipart/form-data" >
		<input type="hidden" id="PP_ID" name="PP_ID" value="<?php echo $list->PP_ID;?>"/>
		<div class="gray_border_bottom pb-20">
		  <h3>매핑정보 <small>Write</small></h3>
		</div>
		<br />
	  <div class="form-group required">
	    <label for="">프로젝트명</label>
		<div class="view_inputs gray_border_bottom pb-15">
			<?php echo $list->PP_NM; ?>
		</div>
	    <span class="help-block with-errors"></span>
	  </div>
	  
	  <div class="form-group required">
	  	<div class="mb-10">
	  		<label for="">제품 선택</label>
	  		<div class="pull-right">
			  	<a class="btn_search_bom_pdt btn-default btn btn-xs">제품추가</a>
			  	<a class="btn_search_bom_pdt_del btn-default btn btn-xs">선택삭제</a>
		  	</div>
	  	</div>
	  	<div style="height:2px;overflow:hidden;" class="bg-<?php echo $this->config->item($PageNm.'Color');?>"></div>
	    <table id="pdtList"></table>
	    <div id="pdtPage"></div>
	  </div>
	  
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
	  <br />
	  
	 
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
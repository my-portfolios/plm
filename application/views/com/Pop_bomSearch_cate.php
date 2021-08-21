<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- bom 팝업 -->
<script>
	
	
	$(function(){
		
		var bom_cate_right = 'bom_cate_right';
		var bom_cate_right_page = 'bom_cate_right_page';
		
		$("#"+bom_cate_right).jqGrid({//그리드 세팅
      url:'<?php echo site_url()?>'+'/com/Pop_bomSearch_cate/searchGrid',      
      mtype : "POST",  
      datatype: "json",            
      colNames:['BCD_ID','카테고리명','등록일'],       
      colModel:[
      		//target name 이랑 같아야됨
          {name:'BC_ID',index:'BC_ID', width:100, align:"center", hidden:true},
          {name:'BC_NMS',index:'BC_NM', width:100, align:"center"},
          {name:'INS_DT',index:'INS_DT', width:100, align:"center", formatter:"date", formatoptions:{newformat:"Y-m-d"}}
      ],
      width: 568,
      height: 342,
      rowNum:30,
      rowList:[30],
      pager: '#'+bom_cate_right_page,
      sortname: 'INS_DT',
	  	sortorder: 'desc',
      viewrecords: true,
      //rownumbers: true,
      gridview: true,
      shrinkToFit: true,
      caption:"목록",
      multiselect: true,
      multiselectWidth: 40,
      loadBeforeSend:function(){
      },
      loadComplete:function(data){
      	
      	/*같은 내용 그리드 삭제*/
      	var cdl = $("#cateList").jqGrid('getDataIDs');
      	var rows = cdl.length;
				for (var i = rows - 1; i >= 0; i--) {
					$('#bom_cate_right').jqGrid('delRowData', cdl[i]);
				}
				
		  }
  	});
		
		$(document).on('click','.selectAdd',function(){//선택추가
			var sel = $( "#"+bom_cate_right ).jqGrid('getGridParam', "selarrrow" ); 
			var rowD;
			$.each(sel,function(i,v){
				rowD = $( "#"+bom_cate_right ).jqGrid('getRowData', v );
				$("#cateList").jqGrid('addRowData',v,rowD,"first");
			});
			var rows = sel.length;
			for (var i = rows - 1; i >= 0; i--) {
				$('#'+bom_cate_right).jqGrid('delRowData', sel[i]);
			}
		});
		
		$(document).on('click','.search_bom_cate',function(){//검색
			$("#"+bom_cate_right).setGridParam({
				postData:{
					"searchOper":$("#pop_bomSearch_cate #searchOper").val(),
					"_search1":$("#pop_bomSearch_cate #_search1").val(),
					"searchField":$("#pop_bomSearch_cate #searchField option:selected").val(),
					"searchString":$("#pop_bomSearch_cate #searchString").val()
				},
				page:1
			}).trigger("reloadGrid");
		});		
	});
</script>
<div class="modal fade" id="pop_bomSearch_cate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"  aria-hidden="true">
  <div class="modal-dialog" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title" id="myModalLabel">검색</h4>
	  </div>
	  <div class="modal-body" style="height:500px;overflow:auto">	 
	  	
	  <form id="frm_search" name="frm_search" method="post" onsubmit="return false;">
			<input type="hidden" id="searchOper" name="searchOper" value="cn" />
			<input type="hidden" id="_search1" name="_search1" value="true" />
			<select class="form-control width_100px" style="width: 120px" id="searchField" name="searchField">
				<option value="BC_NM">카테고리명</option>
				<option value="BC_CONT">내용</option>
			</select>
			
			<input type="text" id="searchString" name="searchString" class="form-control width_200px" placeholder="검색어를 입력해주세요.">
			<a class="search_bom_cate btn btn-<?php echo $this->config->item($this->uri->segment(1).'Color')?>">
				<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
				검색
			</a>
		</form>	
	  	
			<h6 class="mtb-10"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> 목록
				<div class="pull-right">
					<button class="selectAdd btn-default btn btn-xs" style="margin-top: -4px;">선택추가</button>
				</div>
			</h6>
			<div style="height:2px;overflow:hidden;" class="bg-<?php echo $this->config->item($this->uri->segment(1).'Color');?>"></div>
			<table id="bom_cate_right"></table>
			<div id="bom_cate_right_page"></div>
			
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
	  </div>
	</div>
  </div>
</div>

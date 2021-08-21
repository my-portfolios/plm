<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- 거래처 검색 팝업 -->
<script>
	
	$(function(){
		
		var group_left = 'group_left';
		var group_right = 'group_right';
		var id = "<?php echo $this->input->get("id")?>";
		function rightGrid(){
			$("#"+group_right).jqGrid({//그리드 세팅
	      url:'<?php echo site_url()?>'+'/com/Pop_groupSearch/searchGrid?id='+id,      
	      mtype : "POST",  
	      datatype: "json",            
	      colNames:['PG_ID','그룹명','전화번호'],
	      colModel:[
			{name:'PG_ID',index:'PG_ID', width:200, align:"center", hidden:true},
			{name:'PG_NM',index:'PG_NM', width:200, align:"center"},
			{name:'PG_TEL',index:'PG_TEL', width:160, align:"center"}
	      ],
	      width: 568,
	      height: 153,
	      rowNum:1000,
	      rowList:[1000,100,500],
	      //sortname: 'a',
	      cmTemplate: {sortable: false},
	      sortable: false,
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
	      	var sel = $( "#"+group_left ).jqGrid('getDataIDs');
			var rows = sel.length;
			for (var i = rows - 1; i >= 0; i--) {
				$('#'+group_right).jqGrid('delRowData', sel[i]);
			}
	      }
	  	});
		}
		
		var type = "<?php echo $this->uri->segment(1)?>";
		if(type == 'admin'){
			var seg2 = "<?php echo $this->uri->segment(2)?>";
			if(seg2.indexOf('User') != -1){
				type = 'user';
			}
		}
		var url = '<?php echo site_url()?>'+'/com/Pop_groupSearch/searchGridModify?id='+id;
		$("#"+group_left).jqGrid({//그리드 세팅
			url:url,
			datatype: "json",          
			colNames:['PG_ID','그룹명','전화번호'],
            colModel:[
                {name:'PG_ID',index:'PG_ID', width:200, align:"center", hidden:true},
                {name:'PG_NM',index:'PG_NM', width:200, align:"center"},
                {name:'PG_TEL',index:'PG_TEL', width:160, align:"center"}
            ],
			width: 568,
			height: 153,
			rowNum:1000,
			rowList:[1000,100,500],
			//sortname: 'a',
			cmTemplate: {sortable: false},
			sortable: false,
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
				//왼쪽그리드 완료후 로딩
				setTimeout(function(){
					rightGrid();
				},1000);
			}
		});
		
		$(document).on('click','.search_group',function(){//검색
			$("#"+group_right).setGridParam({
				postData:{
					"searchOper":$("#searchOper").val(),
					"_search1":$("#_search1").val(),
					"searchField":$("#searchField option:selected").val(),
					"searchString":$("#searchString").val()
				},
				page:1
			}).trigger("reloadGrid");
		});
		
		$(document).on('click','.selectAdd',function(){//선택추가
			var sel = $( "#"+group_right ).jqGrid('getGridParam', "selarrrow" ); 
			var rowD;
			$.each(sel,function(i,v){
				rowD = $( "#"+group_right ).jqGrid('getRowData', v );
				$("#"+group_left).jqGrid('addRowData',v,rowD,"first");
			});
			var rows = sel.length;
			for (var i = rows - 1; i >= 0; i--) {
				$('#'+group_right).jqGrid('delRowData', sel[i]);
			}
		});
		
		$(document).on('click','.selectDel',function(){//선택삭제
			var sel = $( "#"+group_left ).jqGrid('getGridParam', "selarrrow" ); 
			var rowD;
			$.each(sel,function(i,v){
				rowD = $( "#"+group_left ).jqGrid('getRowData', v );
				$("#"+group_right).jqGrid('addRowData',v,rowD,"first");
			});
			var rows = sel.length;
			for (var i = rows - 1; i >= 0; i--) {
				$('#'+group_left).jqGrid('delRowData', sel[i]);
			}
		});
		
		/* 확인 */
		$('.btnOk').click(function(){
			$("input#PG_ID").remove();
			var chktext = "";
			var sel = $( "#"+group_left ).jqGrid('getDataIDs' ); 
			var rows = sel.length;
			for (var i = rows - 1; i >= 0; i--) {
				var a = $( "#"+group_left ).jqGrid('getRowData', sel[i] );
				chktext += a.PG_NM + "(" + a.PG_ID + ")  ";
				var html = '<input type="hidden" id="PG_ID" name="PG_ID[]" value="'+a.PG_ID+'"/>';
				$('[data-group="group_input"]').after(html);
			}
			$('[data-group="group_input"]').val(chktext);
		});
		
	});
</script>
<div class="modal fade" id="pop_groupSearch" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title" id="myModalLabel">검색</h4>
	  </div>
	  <div class="modal-body" style="height:504px;overflow:auto">	 
	  	
	  <form id="frm_search" name="frm_search" method="post" onsubmit="return false;">
			<input type="hidden" id="searchOper" name="searchOper" value="cn" />
			<input type="hidden" id="_search1" name="_search1" value="true" />
			<select class="form-control" style="width:110px;display:inline-block" id="searchField" name="searchField">
				<option value="PG_NM">그룹명</option>
				<option value="PG_TEL">전화번호</option>
			</select>
			
			<input type="text" id="searchString" name="searchString" class="form-control width_200px" placeholder="검색어를 입력해주세요.">
			<a class="search_group btn btn-<?php echo $this->config->item($this->uri->segment(1).'Color')?>">
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
			<table id="group_right"></table>
			<h6 class="mtb-10"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> 선택된 목록
				<div class="pull-right">
					<button class="selectDel btn-default btn btn-xs" style="margin-top: -4px;">선택취소</button>
				</div>
			</h6>
			<div style="height:2px;overflow:hidden;" class="bg-<?php echo $this->config->item($this->uri->segment(1).'Color');?>"></div>
			<table id="group_left"></table>
			
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btnOk btnOk_group btn-<?php echo $this->config->item($this->uri->segment(1).'Color')?>" data-dismiss="modal">확인</button>
		<button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
	  </div>
	</div>
  </div>
</div>

<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- 거래처 검색 팝업 -->
<script>
	
	$(function(){
		
		var comp_left = 'comp_left';
		var comp_right = 'comp_right';
		
		function rightGrid(){
			$("#"+comp_right).jqGrid({//그리드 세팅
	      url:'<?php echo site_url()?>'+'/com/Pop_compSearch/searchGrid',      
	      mtype : "POST",  
	      datatype: "json",            
	      colNames:['거래처코드','거래처명','대표자명'],
	      colModel:[
			{name:'a',index:'a', width:200, align:"center", hidden:true},
			{name:'b',index:'b', width:200, align:"center"},
			{name:'c',index:'c', width:160, align:"center"}
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
	      	var sel = $( "#"+comp_left ).jqGrid('getDataIDs');
			var rows = sel.length;
			for (var i = rows - 1; i >= 0; i--) {
				$('#'+comp_right).jqGrid('delRowData', sel[i]);
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
		var id = "<?php echo $this->input->get("id")?>";
		var url = '<?php echo site_url()?>'+'/com/Pop_compSearch/searchGridModify?id='+id+'&type='+type;
		$("#"+comp_left).jqGrid({//그리드 세팅
			url:url,
			datatype: "json",          
			colNames:['거래처코드','거래처명','대표자명'],
			colModel:[
				{name:'a',index:'a', width:200, align:"center", hidden:true},
				{name:'b',index:'b', width:200, align:"center"},
				{name:'c',index:'c', width:160, align:"center"}
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
		
		$(document).on('click','.search_comp',function(){//검색
			$("#"+comp_right).setGridParam({
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
			var sel = $( "#"+comp_right ).jqGrid('getGridParam', "selarrrow" ); 
			var rowD;
			$.each(sel,function(i,v){
				rowD = $( "#"+comp_right ).jqGrid('getRowData', v );
				$("#"+comp_left).jqGrid('addRowData',v,rowD,"first");
			});
			var rows = sel.length;
			for (var i = rows - 1; i >= 0; i--) {
				$('#'+comp_right).jqGrid('delRowData', sel[i]);
			}
		});
		
		$(document).on('click','.selectDel',function(){//선택삭제
			var sel = $( "#"+comp_left ).jqGrid('getGridParam', "selarrrow" ); 
			var rowD;
			$.each(sel,function(i,v){
				rowD = $( "#"+comp_left ).jqGrid('getRowData', v );
				$("#"+comp_right).jqGrid('addRowData',v,rowD,"first");
			});
			var rows = sel.length;
			for (var i = rows - 1; i >= 0; i--) {
				$('#'+comp_left).jqGrid('delRowData', sel[i]);
			}
		});
		
		/* 확인 */
		$('.btnOk').click(function(){
			$("input#PF_COMP").remove();
			$("input#PF_COMP_NM").remove();
			var chktext = "";
			var sel = $( "#"+comp_left ).jqGrid('getDataIDs' ); 
			var rows = sel.length;
			for (var i = rows - 1; i >= 0; i--) {
				var a = $( "#"+comp_left ).jqGrid('getRowData', sel[i] );
				chktext += a.b + "(" + a.a + ")  ";
				var html = '<input type="hidden" id="PF_COMP" name="PF_COMP[]" value="'+a.a+'"/><input type="hidden" id="PF_COMP_NM" name="PF_COMP_NM[]" value="'+a.b+'"/>';
				$('[data-comp="comp_input"]').after(html);
			}
			$('[data-comp="comp_input"]').val(chktext);
		});
		
	});
</script>
<div class="modal fade" id="pop_compSearch" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
				<option value="PC_NM">거래처명</option>
				<option value="PC_EMP_NM">대표자명</option>
			</select>
			
			<input type="text" id="searchString" name="searchString" class="form-control width_200px" placeholder="검색어를 입력해주세요.">
			<a class="search_comp btn btn-<?php echo $this->config->item($this->uri->segment(1).'Color')?>">
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
			<table id="comp_right"></table>
			<h6 class="mtb-10"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> 선택된 목록
				<div class="pull-right">
					<button class="selectDel btn-default btn btn-xs" style="margin-top: -4px;">선택취소</button>
				</div>
			</h6>
			<div style="height:2px;overflow:hidden;" class="bg-<?php echo $this->config->item($this->uri->segment(1).'Color');?>"></div>
			<table id="comp_left"></table>
			
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btnOk btnOk_comp btn-<?php echo $this->config->item($this->uri->segment(1).'Color')?>" data-dismiss="modal">확인</button>
		<button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
	  </div>
	</div>
  </div>
</div>

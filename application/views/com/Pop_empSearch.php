<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- 폴더관리 팝업 -->
<script>
	
	$(function(){
		
		var emp_left = 'emp_left';
		var emp_right = 'emp_right';
		
		function rightGrid(){
			$("#"+emp_right).jqGrid({//그리드 세팅
	      url:'<?php echo site_url()?>'+'/com/Pop_empSearch/searchGrid',      
	      mtype : "POST",  
	      datatype: "json",            
	      colNames:['아이디','이름','직급','연락처'],
	      colModel:[
	          {name:'a',index:'a', width:200, align:"center"},
	          {name:'b',index:'b', width:160, align:"center"},
	          {name:'c',index:'c', width:160, align:"center"},
	          {name:'d',index:'d', width:160, align:"center"}
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
	      	
	      	var sel = $( "#"+emp_left ).jqGrid('getDataIDs' ); 
			var rows = sel.length;
			for (var i = rows - 1; i >= 0; i--) {
				$('#'+emp_right).jqGrid('delRowData', sel[i]);
			}
	      }
	  	});
		}
		
		var type = "<?php echo $this->uri->segment(1)?>";
		var id = "<?php echo $this->input->get("id")?>";
		
		if("<?php echo $this->uri->segment(1)?>" == 'board'){
			type = "<?php echo $this->input->get('board'); ?>";
			id	 = "<?php echo $this->input->get('conts_id'); ?>";
		}
		$("#"+emp_left).jqGrid({//그리드 세팅
			url:'<?php echo site_url()?>'+'/com/Pop_empSearch/searchGridModify?id='+id+'&type='+type,
			datatype: "json",          
			colNames:['아이디','이름','직급','연락처'],
			colModel:[
				{name:'a',index:'a', width:200, align:"center"},
				{name:'b',index:'b', width:160, align:"center"},
				{name:'c',index:'c', width:160, align:"center"},
				{name:'d',index:'d', width:160, align:"center"}
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
		
		$(document).on('click','.search_emp',function(){//검색
			$("#"+emp_right).setGridParam({
				postData:{
					"searchOper":$("#searchOper_e").val(),
					"_search1":$("#_search1_e").val(),
					"searchField":$("#searchField_e option:selected").val(),
					"searchString":$("#searchString_e").val()
				},
				page:1
			}).trigger("reloadGrid");
		});
		
		$(document).on('click','.selectAdd',function(){//선택추가
			var sel = $( "#"+emp_right ).jqGrid('getGridParam', "selarrrow" ); 
			var rowD;
			$.each(sel,function(i,v){
				rowD = $( "#"+emp_right ).jqGrid('getRowData', v );
				$("#"+emp_left).jqGrid('addRowData',v,rowD,"first");
			});
			var rows = sel.length;
			for (var i = rows - 1; i >= 0; i--) {
				$('#'+emp_right).jqGrid('delRowData', sel[i]);
			}
		});
		
		$(document).on('click','.selectDel',function(){//선택삭제
			var sel = $( "#"+emp_left ).jqGrid('getGridParam', "selarrrow" ); 
			var rowD;
			$.each(sel,function(i,v){
				rowD = $( "#"+emp_left ).jqGrid('getRowData', v );
				$("#"+emp_right).jqGrid('addRowData',v,rowD,"first");
			});
			var rows = sel.length;
			for (var i = rows - 1; i >= 0; i--) {
				$('#'+emp_left).jqGrid('delRowData', sel[i]);
			}
		});
		
		/* 확인 */
		$('.btnOk').click(function(){
			$("input#PF_EMP").remove();
			$("input#PF_EMP_NM").remove();
			var chktext = "";
			var sel = $( "#"+emp_left ).jqGrid('getDataIDs' ); 
			var rows = sel.length;
			$('#new-nodelist').html('');
			for (var i = rows - 1; i >= 0; i--) {
				var a = $( "#"+emp_left ).jqGrid('getRowData', sel[i] );
				chktext += a.b + "(" + a.a + ")  ";
				if("<?php echo $this->uri->segment(2); ?>" == 'Org_write'){
					var html = '<li><input type="text" class="new-node form-control" readonly style="margin-bottom:3px" value="'+a.c+'^'+a.b+'^'+a.d+'^'+a.a+'"></li>';
					$('#new-nodelist').append(html);
				}else{
					var html = '<input type="hidden" id="PF_EMP" name="PF_EMP[]" value="'+a.a+'"/><input type="hidden" id="PF_EMP_NM" name="PF_EMP_NM[]" value="'+a.b+'"/>';
					$('[data-emp="emp_input"]').after(html);
				}
			}
			$('[data-emp="emp_input"]').val(chktext);
		});
		
	});
	
</script>
<div class="modal fade" id="pop_empSearch" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title" id="myModalLabel">검색</h4>
	  </div>
	  <div class="modal-body" style="height:504px;overflow:auto">	 
	  	
	  <form id="frm_search" name="frm_search" method="post" onsubmit="return false;">
			<input type="hidden" id="searchOper_e" name="searchOper_e" value="cn" />
			<input type="hidden" id="_search1_e" name="_search1_e" value="true" />
			<select class="form-control width_100px" id="searchField_e" name="searchField_e">
				<option value="PE_ID">아이디</option>
				<option value="PE_NM">이름</option>
			</select>
			
			<input type="text" id="searchString_e" name="searchString_e" class="form-control width_200px" placeholder="검색어를 입력해주세요.">
			<a class="search_emp btn btn-<?php echo $this->config->item($this->uri->segment(1).'Color')?>">
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
			<table id="emp_right"></table>
			<h6 class="mtb-10"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> 선택된 목록
				<div class="pull-right">
					<button class="selectDel btn-default btn btn-xs" style="margin-top: -4px;">선택취소</button>
				</div>
			</h6>
			<div style="height:2px;overflow:hidden;" class="bg-<?php echo $this->config->item($this->uri->segment(1).'Color');?>"></div>
			<table id="emp_left"></table>
			
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btnOk btnOk_emp btn-<?php echo $this->config->item($this->uri->segment(1).'Color')?>" data-dismiss="modal">확인</button>
		<button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
	  </div>
	</div>
  </div>
</div>

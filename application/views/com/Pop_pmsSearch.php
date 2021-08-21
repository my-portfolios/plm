<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- 폴더관리 팝업 -->
<script>
	
	$(function(){
		
		
		var pms_left = 'pms_left';
		var pms_right = 'pms_right';
		var btnAr = 'pop_pmsSearch';
		
		function rightGrid(){
			$("#"+pms_right).jqGrid({//그리드 세팅
	      url:'<?php echo site_url()?>'+'/com/Pop_pmsSearch/searchGrid',      
	      mtype : "POST",             
	      datatype: "json",            
	      colNames:['코드','이름'],
	      colModel:[
	          {name:'a',index:'a', width:200, align:"center"},
	          {name:'b',index:'b', width:160, align:"center"}
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
	      	var sel = $( "#"+pms_left ).jqGrid('getDataIDs' ); 
			    var rows = sel.length;
					for (var i = rows - 1; i >= 0; i--) {
						$('#'+pms_right).jqGrid('delRowData', sel[i]);
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
		
		$("#"+pms_left).jqGrid({//그리드 세팅
			url:'<?php echo site_url()?>'+'/com/Pop_pmsSearch/searchGridModify?id='+id+'&type='+type,
			datatype: "json",          
			colNames:['코드','이름'],
			colModel:[
				{name:'a',index:'a', width:200, align:"center"},
				{name:'b',index:'b', width:160, align:"center"}
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
		
		$(document).on('click','#'+btnAr+' .search_pms',function(){//검색
			$("#"+pms_right).setGridParam({
				postData:{
					"searchOper":$('#'+btnAr+" #searchOper_p").val(),
					"_search1":$('#'+btnAr+" #_search1_p").val(),
					"searchField":$('#'+btnAr+" #searchField_p option:selected").val(),
					"searchString":$('#'+btnAr+" #searchString_p").val()
				},
				page:1
			}).trigger("reloadGrid");
		});
		
		$(document).on('click','#'+btnAr+' .selectAdd',function(){//선택추가
			var sel = $( "#"+pms_right ).jqGrid('getGridParam', "selarrrow" ); 
			var rowD;
			$.each(sel,function(i,v){
				rowD = $( "#"+pms_right ).jqGrid('getRowData', v );
				$("#"+pms_left).jqGrid('addRowData',v,rowD,"first");
			});
			var rows = sel.length;
			for (var i = rows - 1; i >= 0; i--) {
				$('#'+pms_right).jqGrid('delRowData', sel[i]);
			}
		});
		
		$(document).on('click','#'+btnAr+' .selectDel',function(){//선택삭제
			var sel = $( "#"+pms_left ).jqGrid('getGridParam', "selarrrow" ); 
			var rowD;
			$.each(sel,function(i,v){
				rowD = $( "#"+pms_left ).jqGrid('getRowData', v );
				$("#"+pms_right).jqGrid('addRowData',v,rowD,"first");
			});
			var rows = sel.length;
			for (var i = rows - 1; i >= 0; i--) {
				$('#'+pms_left).jqGrid('delRowData', sel[i]);
			}
		});
		
		/* 확인 */
		$('.btnOk').click(function(){
			
			$("input#PF_PMS").remove();
			$("input#PF_PMS_NM").remove();
			var chktext = "";
			var sel = $( "#"+pms_left ).jqGrid('getDataIDs' ); 
			var rows = sel.length;
			for (var i = rows - 1; i >= 0; i--) {
				var a = $( "#"+pms_left ).jqGrid('getRowData', sel[i] );
				chktext += a.b + "(" + a.a + ")  ";
				var html = '<input type="hidden" id="PF_PMS" name="PF_PMS[]" value="'+a.a+'"/><input type="hidden" id="PF_PMS_NM" name="PF_PMS_NM[]" value="'+a.b+'"/>';
				$('[data-pms="pms_input"]').after(html);
			}
			$('[data-pms="pms_input"]').val(chktext);
			
		});
		
	});
</script>
<div class="modal fade" id="pop_pmsSearch" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title" id="myModalLabel">프로젝트 검색</h4>
	  </div>
	  <div class="modal-body" style="height:504px;overflow:auto">	 
	  	
	  <form id="frm_search" name="frm_search" method="post" onsubmit="return false;">
			<input type="hidden" id="searchOper_p" name="searchOper_p" value="cn" />
			<input type="hidden" id="_search1_p" name="_search1_p" value="true" />
			<select class="form-control width_100px" id="searchField_p" name="searchField_p">
				<option value="PP_ID">코드</option>
				<option value="PP_NM">이름</option>
			</select>
			
			<input type="text" id="searchString_p" name="searchString_p" class="form-control width_200px" placeholder="검색어를 입력해주세요.">
			<a class="search_pms btn btn-<?php echo $this->config->item($this->uri->segment(1).'Color')?>">
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
			<table id="pms_right"></table>
			<h6 class="mtb-10"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> 선택된 목록
				<div class="pull-right">
					<button class="selectDel btn-default btn btn-xs" style="margin-top: -4px;">선택취소</button>
				</div>
			</h6>
			<div style="height:2px;overflow:hidden;" class="bg-<?php echo $this->config->item($this->uri->segment(1).'Color');?>"></div>
			<table id="pms_left"></table>
			
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btnOk btn-<?php echo $this->config->item($this->uri->segment(1).'Color')?>" data-dismiss="modal">확인</button>
		<button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
	  </div>
	</div>
  </div>
</div>

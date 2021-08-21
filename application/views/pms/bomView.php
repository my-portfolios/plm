<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
	#bomList tr.ui-state-highlight,
	#bomList tr.ui-state-hover{
		background:#fff!important;
	}
</style>
<script>
//기존설정
$.extend($.jgrid.ajaxOptions, { async: false });//그리드 동기로 변환
var bom_list 	= "bom_list";//그리드 아이디
var bom_pager 	= "bom_pager";//그리드 페이징

function bomList(id){
	$("#"+bom_list).jqGrid({//그리드 세팅
		url:'<?php echo site_url()?>'+'/pms/BomView/getData',      
		mtype : "POST",             
		datatype: "json",      
		postData : {
			"id" : id
		},      
		colNames:['PART_ID','부품명','규격','재질','수량','구분','BPD_ID','구분명'],       
		colModel:[
			{name:'PART_ID',index:'PART_ID', width:100, align:"center", hidden:true},
			{name:'PART_NM',index:'PART_NM', width:100, align:"left"},
			{name:'BP_STD',index:'BP_STD', width:100, align:"center"},
			{name:'BP_MTR',index:'BP_MTR', width:100, align:"center"},
			{name:'BCD_AMT',index:'BCD_AMT', width:100, align:"center"},
			{name:'GUBUN',index:'GUBUN', width:100, align:"center"},
			{name:'BPD_ID',index:'BPD_ID', width:100, align:"center", hidden:true},
			{name:'BPD_NM',index:'BPD_NM', width:100, align:"center"}
		],
		height:300,
		rowNum:30,
		rowList:[30,100,500],
		pager: '#'+bom_pager,
		sortname: 'PART_NM',
		sortorder: 'desc',
		sorttype: 'date',
		shrinkToFit: true,
		autowidth: true,
		viewrecords: true,
		//rownumbers: true,
		gridview: true,
		caption:"목록",
		multiselect: false,
		multiselectWidth: 60,
		loadBeforeSend:function(){
			//기존 로딩 사요안함 style로 display none
			$('#loading').modal("show");
		},
		loadComplete:function(data){
			$('#loading').modal("hide");
			//디자인상 멀티셀렉트를 true 를 유지 => checkbox disabled
			//$('#bomList').find('[type=checkbox]').attr('disabled','disabled');
		
		},
		onSelectRow: function(ids) { 
			//디자인상 멀티셀렉트를 true 를 유지 => checkbox prop false
			//$('#bomList').find('[type=checkbox]').prop('checked',false);
		}
	});	
}

	/* 검색 */
	function search_boms(){
		$("#bom_list").setGridParam({
			postData:{
				"searchOper":$("#searchOper_boms").val(),
				"_search1":$("#_search1_boms").val(),
				"searchField":$("#searchField_boms option:selected").val(),
				"searchString":$("#searchString_boms").val()
			},
			page:1
		}).trigger("reloadGrid");
	}

</script>


<div class="modal" id="bomList" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">부품정보</h4>
	  </div>
	  <div class="modal-body">
	  		<div class="gray_border_bottom">
		  		<form id="frm_search_boms" name="frm_search_boms" method="post" onsubmit="return false;">
						<input type="hidden" id="searchOper_boms" name="searchOper" value="cn" />
						<input type="hidden" id="_search1_boms" name="_search1" value="true" />
						<select class="form-control width_100px" style="width:120px" id="searchField_boms" name="searchField">
							<option value="Z.PART_NM" selected >부품명</option>
							<option value="Z.BP_STD">규격</option>
							<option value="Z.BP_MTR">재질</option>
							<option value="Z.BCD_AMT">수량</option>
							<option value="Z.GUBUN">구분</option>
							<option value="Z.BPD_NM">구분명</option>
						</select>
						<input type="text" id="searchString_boms" name="searchString" class="form-control width_200px" placeholder="검색어를 입력해주세요.">
						<a class="btn btn-<?php echo $this->config->item($this->uri->segment(1).'Color')?>" onclick="search_boms()">
							<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
							검색
						</a>
					</form>
					<br />
	  		</div>
				<table id="bom_list"></table>
				<div id="bom_pager"></div>
			</div>
	  
	  <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
	  </div>
	</div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


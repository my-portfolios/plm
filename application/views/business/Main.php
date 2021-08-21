<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script>
//기존설정
var list = "list";//그리드 아이디

$(function(){

	$("#"+list).jqGrid({//그리드 세팅
		url:'<?php echo site_url()?>'+'/business/Main/getMailBox',      
		mtype : "POST",             
		datatype: "json",            
		postData: {
			"mc_id" : "none"
		},
		colNames:['MSG_NO','SUBJECT','3','DT'],       
		colModel:[
			{name:'MSG_NO',index:'MSG_NO', width:50, align:"center"},
			{name:'SUBJECT',index:'SUBJECT', width:300, align:"left"},
			{name:'3',index:'3', width:100, align:"center"},
			{name:'DT',index:'DT', width:100, align:"center"}
		],
		rowNum:30,
		rowList:[30,100,500],
		pager: '#'+pager,
		sortname: 'DT',
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
			heightAuto('#wp_top','#wp_left','#wp_right','#wp_bottom','.search_area');//높이 맞춤
			$("#"+list).jqGrid('setGridWidth',$('#gbox_'+list).parent().innerWidth());//넓이지정
			$("#"+list).jqGrid('setGridHeight',$('#gbox_'+list).parent().outerHeight() -55);//높이지정
			$('#loading').modal("hide");
			$('.gridCnt').html('전체 :<strong>'+$("#"+list).getGridParam("records")+'</strong> 건');//카운트 넣기
			$(window).trigger('resize');
			$('[data-toggle="tooltip"]').tooltip();
		},
		onCellSelect: function (rowId, iCol, content, event) {
			if(iCol == 2){	//제목
				var msg_no 	= $("#"+list).jqGrid('getRowData',rowId).MSG_NO;
				var mc_id 	= $("#"+list).getGridParam("postData").mc_id;
				fn_getMailBody(mc_id , msg_no);
			}
		}
	  });
});

function fn_getMailBox(mc_id){
	
	$("#"+list).setGridParam({
		postData: {
			"mc_id" : mc_id
		},
		page:1
	}).trigger("reloadGrid");
	
}

function fn_getMailBody(mc_id , msg_no){
	$('#frm_mailBody #MC_ID').val(mc_id);
	$('#frm_mailBody #MSG_NO').val(msg_no);
	$('#frm_mailBody').attr('action','<?php echo base_url();?>index.php/business/MailBody');
	$('#frm_mailBody').submit();
	/*
	var data = {
		 "mc_id"	: mc_id
		,"msg_no"	: msg_no
	};
	$.ajax({
		type: 'get',
		dataType: 'json',
		url: ,
		data: data,
		async : false,
		success: function (data) {
			console.log(data);
		},
		error: function (request, status, error) {
			console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
		}
	});
	*/
}
</script>
<div id="wp_right">
	<form id="frm_mailBody" name="frm_mailBody" method="get">
		<input type="hidden" id="MC_ID" name="MC_ID" value=""/>
		<input type="hidden" id="MSG_NO" name="MSG_NO" value=""/>
	</form>
	<div class="search_area p-20 gray_border_bottom">
		<form id="frm_search" name="frm_search" method="post" onsubmit="return false;">
			<input type="hidden" id="searchOper" name="searchOper" value="cn" />
			<input type="hidden" id="_search1" name="_search1" value="true" />
			<select class="form-control width_100px" id="searchField" name="searchField">
				<option value="PR_TITLE">제목</option>
				<option value="INS_NM">작성자</option>
			</select>
			<label for="searchtext">검색어</label>
			<input type="text" id="searchString" name="searchString" class="form-control width_200px" placeholder="검색어를 입력해주세요.">
			<a class="search btn btn-<?php echo $this->config->item($this->uri->segment(1).'Color')?>">
				<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
				검색
			</a>
			<a class="btn btn-default" onclick="ref()">
				<span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>
				새로고침
			</a>
		
		<div>
			<button type="button" class="selectDel btn btn-default btn-sm mt-10" onclick="selectRows('del');">
				<span class="glyphicon glyphicon-floppy-remove" aria-hidden="true"></span>
				선택삭제
			</button>
			
			<button type="button" class="selectBokwon btn btn-default btn-sm mt-10" onclick="selectRows('bok');">
			<span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>
				선택복원
			</button>
			
			<button type="button" class="btn btn-default btn-sm mt-10" onclick="faView(this)">
				<span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span>
				즐겨찾기만 보기
			</button>
			
		</div>
		</form>
		<div class="location mt-10 pl-0">
			<span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
				<span class="locas">목록</span>
			<div class="pull-right gridCnt">
				로딩중...
			</div>
		</div>
		
	</div>
	<div class="grid_area">
		<table id="list" style="border-collapse: inherit;">
		</table>
		<div id="pager"></div>
	</div>
</div>		

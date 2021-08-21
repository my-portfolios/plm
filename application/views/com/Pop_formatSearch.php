<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- 양식 팝업 -->
<script>
	//주석제거
	function getRightText(str) {
		while(true) {
			if (str.indexOf('<!--') != -1) {
				var start = str.indexOf('<!--');
				var end = str.indexOf('-->');
				str = str.substring(0, start) + str.substring(end+3, str.length);
			} else {
				break;
			}
		}
		return str;
	}
	
	$(function(){
		
		var format_right = 'format_right';
		var format_right_page = 'format_right_page';
		
		$("#"+format_right).jqGrid({//그리드 세팅
		  url:'<?php echo site_url()?>'+'/com/Pop_formatSearch/searchGrid',      
		  mtype : "POST",  
		  datatype: "json",            
		  colNames:['PF_ID','양식명','양식내용','작성자','작성일'],       
		  colModel:[
				//target name 이랑 같아야됨
			  {name:'PF_ID',index:'PF_ID', width:100, align:"center", hidden:true},
			  {name:'PF_NM',index:'PF_NM', width:100, align:"center"},
			  {name:'PF_CONT',index:'PF_CONT', width:100, align:"center", hidden:true},
			  {name:'INS_NM',index:'INS_NM', width:100, align:"center"},
			  {name:'INS_DT',index:'INS_DT', width:100, align:"center", formatter:"date", formatoptions:{newformat:"Y-m-d"}}
		  ],
      width: 568,
      height: 342,
      rowNum:30,
      rowList:[30],
      pager: '#'+format_right_page,
      sortname: 'INS_DT',
	  sortorder: 'desc',
      viewrecords: true,
      //rownumbers: true,
      gridview: true,
      shrinkToFit: true,
      caption:"목록",
      multiselect: true,
      multiselectWidth: 40,
	  beforeSelectRow: function(rowid, e){
			$("#"+format_right).jqGrid('resetSelection');
			return(true);
		},
      loadBeforeSend:function(){
      },
      loadComplete:function(data){
		var myGrid = $("#"+format_right);
		$("#cb_"+myGrid[0].id).hide();
	  }
		
  	});
	
		//확인
		$(document).on('click','.btn_setFormat',function(){
			
			var selRowIds = $("#"+format_right).jqGrid('getGridParam', 'selarrrow').length;
			if(selRowIds == 0){
				bootbox.alert({
					size:'small',
					message : '적용할 양식을 선택해주세요.',
					buttons: {
						ok: {
							label: '확인',
							className: "btn-<?php echo $this->config->item($this->uri->segment(1).'Color');?>"
						}
					}
				});
				return preventDefaultAction(false);
			}
			
			bootbox.confirm({
				size: "small",
				message: "현재의 내용이 초기화된 후 적용됩니다. 적용하시겠습니까?", 
				buttons: {
					confirm: {
						label: '확인',
						className: "btn-<?php echo $this->config->item($this->uri->segment(1).'Color');?>"
					},
					cancel: {
						label: '취소'
					}
				},
				callback: function(result){
					if(result == true){
						var sel = $( "#"+format_right ).jqGrid('getGridParam', "selarrrow" ); 
						var pf_cont = $( "#"+format_right ).getCell(sel,'PF_CONT');
						var id = $("#pop_formatSearch #tg").val();
						$('[contenteditable="true"]').eq(id).parent().parent().parent().find('.snote').summernote('reset');
						$('[contenteditable="true"]').eq(id).parent().parent().parent().find('.snote').summernote('code',getRightText(pf_cont));
						$("#pop_formatSearch").modal('hide');
					}
				}
			});
		});
		
		$(document).on('click','.search_format',function(){//검색
			$("#"+format_right).setGridParam({
				postData:{
					"searchOper":$("#pop_formatSearch #searchOper_s").val(),
					"_search1":$("#pop_formatSearch #_search1_s").val(),
					"searchField":$("#pop_formatSearch #searchField_s option:selected").val(),
					"searchString":$("#pop_formatSearch #searchString_s").val()
				},
				page:1
			}).trigger("reloadGrid");
		});		
	});
</script>

<div class="modal fade" id="pop_formatSearch" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"  aria-hidden="true">
<input type="hidden" id="tg" name="tg" value="" />
  <div class="modal-dialog" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title" id="myModalLabel">검색</h4>
	  </div>
	  <div class="modal-body" style="height:500px;overflow:auto">	 
	  	
	  <form id="frm_search" name="frm_search" method="post" onsubmit="return false;">
			<input type="hidden" id="searchOper_s" name="searchOper_s" value="cn" />
			<input type="hidden" id="_search1_s" name="_search1_s" value="true" />
			<select class="form-control width_100px" style="width: 120px" id="searchField_s" name="searchField_s">
				<option value="PF_NM">양식명</option>
				<option value="INS_NM">작성자</option>
			</select>
			
			<input type="text" id="searchString_s" name="searchString_s" class="form-control width_200px" placeholder="검색어를 입력해주세요.">
			<a class="search_format btn btn-<?php echo $this->config->item($this->uri->segment(1).'Color')?>">
				<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
				검색
			</a>
		</form>	
	  	
			<h6 class="mtb-10"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> 목록
			<!--
				<div class="pull-right">
					<button class="selectAdd btn-default btn btn-xs" style="margin-top: -4px;">선택추가</button>
				</div>
			-->
			</h6>
			<div style="height:2px;overflow:hidden;" class="bg-<?php echo $this->config->item($this->uri->segment(1).'Color');?>"></div>
			<table id="format_right"></table>
			<div id="format_right_page"></div>
			
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn_setFormat btn-<?php echo $this->config->item($this->uri->segment(1).'Color')?>">확인</button>
		<button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
	  </div>
	</div>
  </div>
</div>

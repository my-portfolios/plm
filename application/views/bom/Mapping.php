<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$PageNm = 'bom';//seg 1
$PageType = 'Mapping';//seg 2
?>

<script>
	//기존설정
	$.extend($.jgrid.ajaxOptions, { async: false });//그리드 동기로 변환
	var list = "<?php echo $PageType?>_list";//그리드 아이디
	var pager = "<?php echo $PageType?>_pager";//그리드 페이징
	
	function ref(){//그리드 새로고침
		location.href="<?php echo site_url()?>/<?php echo $PageNm?>/<?php echo $PageType?>";
	}
	
	function formatOpt1(cellvalue, options, rowObject){//즐찾 셋팅
	  var str ='';
	 	var gap ='-';
		str += "<span class='link' onclick='linkGrid(this)' data='"+rowObject[0]+"'>"+cellvalue+"</span><div class='mt-5'> "+gap+"</div>";
		return str;
	}
	
	//링크 클릭
	function linkGrid(t){
		var data = $(t).attr('data');
		location.href="<?php echo site_url();?>/<?php echo $PageNm?>/<?php echo $PageType?>_write?id="+data;
	}
	
	/* 검색 */
	function fn_search(){
		$("#"+list).setGridParam({
			postData:{
				"searchOper":$("#searchOper").val(),
				"_search1":$("#_search1").val(),
				"searchField":$("#searchField option:selected").val(),
				"searchString":$("#searchString").val()
			},
			page:1
		}).trigger("reloadGrid");
	}
	
	$(function(){
		$(document).on('click','.search',function(){//검색
			fn_search();
		});
		
		$(document).on('keydown','#searchString',function(key){
			
			if(key.keyCode == 13){
				fn_search();
			}
			
		});
		
		$(window).resize(function(){//리사이즈 이벤트
			$("#"+list).jqGrid('setGridWidth',$('#gbox_'+list).parent().innerWidth());//넓이지정
			$("#"+list).jqGrid('setGridHeight',$('#gbox_'+list).parent().outerHeight() -55);//높이지정
		});
	});
	
	//선택삭제
	function selectRows(t){
		var ids = $("#"+list).jqGrid('getGridParam', 'selarrrow');      //체크된 row id들을 배열로 반환
		var msg = "총 "+ids.length+"건의 매핑정보를 삭제 합니다.<br />프로젝트는 삭제되지 않습니다. ";
		
		if(ids.length > 0){
			bootbox.confirm({
				size: "small",
				message: msg,
				buttons: {
					confirm: {
						label: '확인',
						className: "btn-<?php echo $this->config->item($PageNm.'Color');?>"
					},
					cancel: {
						label: '취소'
					}
				},
				callback: function (result) {
					if(result == true){
						var arr = [];
						for(var i = 0; i < ids.length; i++){
							var rowObject = $("#"+list).getRowData(ids[i]);      //체크된 id의 row 데이터 정보를 Object 형태로 반환
							var value = rowObject.PP_ID;     //Obejct key값이 PR_ID value값 반환
							arr.push(value);
						}
						var postdata = {
							"REMOVE_ARR" :arr
						}
						$("#"+list).setGridParam({
							postData:postdata,
							page:1
						}).trigger("reloadGrid");
					}
				}
			});	
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
	}
	
	$(window).load(function(){
		$("#"+list).jqGrid({//그리드 세팅
			url:'<?php echo site_url()?>'+'/<?php echo $PageNm?>/<?php echo $PageType?>/loadData',      
			mtype : "POST",             
			datatype: "json",            
			colNames:['PP_ID','프로젝트명'],       
			colModel:[
				{name:'PP_ID',index:'PP_ID', width:100, align:"center", hidden:true},
				{name:'PP_NM',index:'PP_NM', width:300, align:"left",formatter:formatOpt1}
			],
			rowNum:30,
			rowList:[30,100,500],
			pager: '#'+pager,
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
				heightAuto('#wp_top','#wp_left','#wp_right','#wp_bottom','.search_area');//높이 맞춤
				$("#"+list).jqGrid('setGridWidth',$('#gbox_'+list).parent().innerWidth());//넓이지정
				$("#"+list).jqGrid('setGridHeight',$('#gbox_'+list).parent().outerHeight() -55);//높이지정
				$('#loading').modal("hide");
				$('.gridCnt').html('전체 :<strong>'+$("#"+list).getGridParam("records")+'</strong> 건');//카운트 넣기
				$(window).trigger('resize');
				$('[data-toggle="tooltip"]').tooltip();
				//param초기화
				$("#"+list).setGridParam({
					postData: {
						"REMOVE_ARR" :null,
						"FA_YN":null
					}
				});
			}
		});
	});
</script>
<div id="wp_right">
	<div class="search_area p-20 gray_border_bottom">
		<form id="frm_search" name="frm_search" method="post" onsubmit="return false;">
			<input type="hidden" id="searchOper" name="searchOper" value="cn" />
			<input type="hidden" id="_search1" name="_search1" value="true" />
			<select class="form-control width_100px" style="width:120px" id="searchField" name="searchField">
				<option value="PP_NM">프로젝트명</option>
			</select>
			<label for="searchtext">검색어</label>
			<input type="text" id="searchString" name="searchString" class="form-control width_200px" placeholder="검색어를 입력해주세요.">
			<a class="search btn btn-<?php echo $this->config->item($PageNm.'Color')?>">
				<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
				검색
			</a>
			<a class="btn btn-default" onclick="ref()">
				<span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>
				새로고침
			</a>
		
			<div>
				<button type="button" class="selectDel btn btn-default btn-sm mt-10" onclick="selectRows();">
					<span class="glyphicon glyphicon-floppy-remove" aria-hidden="true"></span>
					선택삭제
				</button>
				
			</div>
		</form>
		<div class="location mt-10 pl-0">
			<span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
				<span class="locas">프로젝트 목록</span>
			<div class="pull-right gridCnt">
				로딩중...
			</div>
		</div>
		
	</div>
	<div class="grid_area">
		<table id="<?php echo $PageType?>_list" style="border-collapse: inherit;">
		</table>
		<div id="<?php echo $PageType?>_pager"></div>
	</div>
</div>		

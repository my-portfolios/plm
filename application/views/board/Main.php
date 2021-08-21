<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$PageNm = 'board';//seg 1
$PageType = 'main';//seg 2
?>

<script>
	//기존설정
	$.extend($.jgrid.ajaxOptions, { async: false });//그리드 동기로 변환
	var list = "<?php echo $PageType?>_list";//그리드 아이디
	var pager = "<?php echo $PageType?>_pager";//그리드 페이징
	
	function ref(){//그리드 새로고침
		location.href="<?php echo site_url()?>/<?php echo $PageNm?>/<?php echo $PageType?>";
	}
	function faView(t){//즐찾 보기
		if(!$(t).hasClass('tg')){
			$("#"+list).setGridParam({
	        postData:{
	        	"FA_SORT_STAR":"true",
	        	"FA_USER": "<?php echo $_SESSION['userid'];?>",
	        	"FA_TYPE": '<?php echo $PageNm?>'
	        },
	        page:1
	    }).trigger("reloadGrid");
	    $(t).addClass('tg').html('<span class="glyphicon glyphicon-star" aria-hidden="true"></span> 즐겨찾기 닫기');
  	}else{
			$("#"+list).setGridParam({
	        postData:{
	        	"FA_SORT_STAR":"false"
	        },
	        page:1
	    }).trigger("reloadGrid");
	    $(t).removeClass('tg').html('<span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span> 즐겨찾기 보기');
  	}
	}
	
		
	//선택삭제
	function selectRows(t){
		var ids = $("#"+list).jqGrid('getGridParam', 'selarrrow');      //체크된 row id들을 배열로 반환
		var msg = "총 "+ids.length+"건을 삭제하시겠습니까? ";
		
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
							var value = rowObject.CONTS_ID;     //Obejct key값이 PR_ID value값 반환
							arr.push(value);
						}
						
						//권한체크 (본인글만 삭제가능(관리자제외))
						if('<?php echo $this->session->userdata('userauth')?>' != 'admin'){
							if(!fn_chkInsId(arr)){
								bootbox.alert({
									size:'small',
									message : '본인이 작성한 글만 삭제가능합니다.',
									buttons: {
										ok: {
											label: '확인',
											className: "btn-<?php echo $this->config->item($this->uri->segment(1).'Color')?>"
										}
									}
								});
								$(this).modal('hide');
								return false;
							}
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
	
	//선택된 pf_id 들의 작성자가 로그인된 계정과 같은지 확인 ( 자신이 작성한 글만 자신이 삭제가능 (관리자제외) )
	function fn_chkInsId(ids){
		var result = false;
		var data = {
			"ids" : ids
		};
		$.ajax({
			type: 'post',
			dataType: 'json',
			url: '<?php echo site_url()?>'+'/<?php echo $PageNm?>/<?php echo $PageType?>/chkInsId',
			data: data,
			async : false,
			success: function (data) {
				result = data;
			},
			error: function (request, status, error) {
				console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
			}
		});
		return result;
	}
	
	function formatOpt1(cellvalue, options, rowObject){//즐찾 셋팅
		var str ='';
	 	var gap ='-';
		if(rowObject[6] > 0){
			str += "<span class=\"test1\"><sapn data-toggle='tooltip' data-placement='right' title='즐겨찾기 취소' class='glyphicon glyphicon-star' style='color:orange;cursor:pointer;margin-right:4px' onclick=\"fa_btn('"+rowObject[0]+"')\"></span><span class='link' onclick='linkGrid(this)' data='"+rowObject[0]+"'>"+cellvalue+"</span><div class='mt-5'> "+gap+"</div></span>";
		}else{
			str += "<span class=\"test1\"><sapn data-toggle='tooltip' data-placement='right' title='즐겨찾기 추가' class='glyphicon glyphicon-star-empty' style='color:#ccc;cursor:pointer;margin-right:4px' onclick=\"fa_btn('"+rowObject[0]+"')\"></span><span class='link' onclick='linkGrid(this)' data='"+rowObject[0]+"'>"+cellvalue+"</span><div class='mt-5'> "+gap+"</div></span>";
		}
		return str;
	}
	
	function formatOpt2(cellvalue, options, rowObject){//아이디
		var str ='';
	 	str += "<span class='link' onclick=msgView('"+cellvalue+"')>"+cellvalue+"</span>";
		return str;
	}
	
	
	function fa_btn(val){//즐찾여부
		$("#"+list).setGridParam({
			postData:{
				"FA_YN":"true",
				"FA_TYPE":"<?php echo $PageNm?>",
				"FA_USER":"<?php echo $_SESSION['userid'];?>",
				"FA_VAL": val
			}
		}).trigger("reloadGrid");
	}
	
	//링크 클릭
	function linkGrid(t){
		var data = $(t).attr('data');
		location.href="<?php echo site_url();?>/<?php echo $PageNm?>/View?id=<?php echo $this->input->get('id');?>&c_id="+data;
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
	
	$(window).load(function(){
		
		$("#"+list).jqGrid({//그리드 세팅
		url:'<?php echo site_url()?>'+'/<?php echo $PageNm?>/<?php echo $PageType?>/loadData?board_id=<?php echo $this->input->get('id'); ?>',      
		mtype : "POST",     
		datatype: "json",            
		colNames:['CONTS_ID','제목','INS_ID','작성자','UPD_ID','최종수정일','','BOARD_NOTICE'],       
		colModel:[
			{name:'CONTS_ID',index:'CONTS_ID', width:100, align:"center", hidden:true},
			{name:'CONTS_TITLE',index:'CONTS_TITLE', width:300, align:"left",formatter:formatOpt1},
			{name:'INS_ID',index:'INS_ID', width:150, align:"center", hidden:true},
			{name:'INS_NM',index:'INS_NM', width:100, align:"center"},
			{name:'UPD_ID',index:'UPD_ID', width:100, align:"center", hidden:true},
			{name:'UPD_DT',index:'UPD_DT', width:100, align:"center", formatter:"date", formatoptions:{newformat:"Y-m-d"}},
			{name:'FA_CNT',index:'FA_CNT', width:100, align:"center", hidden:true},
			{name:'BOARD_NOTICE',index:'BOARD_NOTICE', width:100, align:"center", hidden:true}
		],
      rowNum:30,
      rowList:[30,100,500],
      pager: '#'+pager,
	  sortname: 'UPD_DT',
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
		fn_chkDisabled(list);	//권한 (관리자 , 작성자만 체크박스 활성화) string : jqgrid table id
		// Row Color Change Event
		var ids = $("#"+list).getDataIDs();
		// Grid Data Get!
		$.each(
			ids,function(idx, rowId){
			rowData = $("#"+list).getRowData(rowId);
			// 만약 rowName 컬럼의 데이터가 공백이라면 해당 Row의 색상을 변경!           
			if (rowData.BOARD_NOTICE == 'Y') {
				$("#"+list).setRowData(rowId, false, { background:"#effaff" });
			}
		}
		);       
      }
  });
	});




</script>
<div id="wp_right">
	<div class="search_area p-20 gray_border_bottom">
		<form id="frm_search" name="frm_search" method="post" onsubmit="return false;">
			<input type="hidden" id="searchOper" name="searchOper" value="cn" />
			<input type="hidden" id="_search1" name="_search1" value="true" />
			<select class="form-control width_100px" id="searchField" name="searchField">
				<option value="CONTS_TITLE">제목</option>
				<option value="INS_NM">작성자</option>
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
			<button type="button" class="selectDel btn btn-default btn-sm mt-10" onclick="selectRows('del');">
				<span class="glyphicon glyphicon-floppy-remove" aria-hidden="true"></span>
				선택삭제
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
		<table id="<?php echo $PageType?>_list" style="border-collapse: inherit;">
		</table>
		<div id="<?php echo $PageType?>_pager"></div>
	</div>
</div>		

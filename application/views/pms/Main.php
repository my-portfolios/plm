<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script>
//기존설정
$.extend($.jgrid.ajaxOptions, { async: false });//그리드 동기로 변환
var pms_list 	= "pms_list";//그리드 아이디
var pms_pager 	= "pms_pager";//그리드 페이징

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
	
		$("#"+pms_list).jqGrid('setGridWidth',$('#gbox_'+pms_list).parent().innerWidth());//넓이지정
		$("#"+pms_list).jqGrid('setGridHeight',$('#gbox_'+pms_list).parent().outerHeight() -55);//높이지정
		
	});
	
	/* 선택복원 */
		$(document).on('click','.selectBokwon',function(){
			
			var selRowIds = $("#"+pms_list).jqGrid("getGridParam", "selarrrow");
			
			if(selRowIds.length < 1){
				bootbox.alert({
					size:'small',
					message : '선택된 항목이 없습니다.',
					buttons: {
						ok: {
							label: '확인',
							className: "btn-<?php echo $this->config->item($this->uri->segment(1).'Color')?>"
						}
					}
				});
			}else{

			bootbox.confirm({
				size: "small",
				message: "복원하시겠습니까? ",
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
						$("#"+pms_list).setGridParam({
							postData:{
								"BOKWON_ARR":selRowIds
							},
							page:1
						}).trigger("reloadGrid");
					}
				}
			});
		}
	});
});

$(window).load(function(){
	$("#"+pms_list).jqGrid({//그리드 세팅
		url:'<?php echo site_url()?>'+'/pms/Main/loadData',      
		mtype : "POST",             
		datatype: "json",            
		colNames:['PP_ID','프로젝트명','거래처','시작일','완료일','작성자','최종수정일','등록일','진행상태','','','즐찾'],       
		colModel:[
			{name:'PP_ID',index:'PP_ID', width:100, align:"center", hidden:true},
			{name:'PP_NM',index:'PP_NM', width:300, align:"left",formatter:formatOpt1},
			{name:'PC_NM',index:'PC_NM', width:150, align:"center"},
			{name:'PP_ST_DAT',index:'PR_TITLE', width:150, align:"center", formatter:"date", formatoptions:{newformat:"Y-m-d"}},
			{name:'PP_ED_DAT',index:'PR_HOPE_END_DAT', width:150, align:"center", formatter:"date", formatoptions:{newformat:"Y-m-d"}},
			{name:'INS_NM',index:'INS_NM', width:100, align:"center"},
			{name:'UPD_DT',index:'UPD_DT', width:150, align:"center", formatter:"date", formatoptions:{newformat:"Y-m-d"}},
			{name:'INS_DT',index:'INS_DT', width:150, align:"center", formatter:"date", formatoptions:{newformat:"Y-m-d"}, hidden:true},
			{name:'PP_STATUS',index:'PR_STATUS', width:100, align:"center",formatter:formatOpt2,hidden:true},
			{name:'부품정보',index:'부품정보', width:100, align:"center",formatter:formatOpt4,sortable:false,hidden:true},
			{name:'WBS_VIEW',index:'WBS_VIEW', width:100, align:"center",formatter:formatOpt3,sortable:false,hidden:true},
			{name:'FA_CNT',index:'FA_CNT', width:100, align:"center", hidden:true}
		],
		rowNum:30,
		rowList:[30,100,500],
		pager: '#'+pms_pager,
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
			
			$("#"+pms_list).jqGrid('setGridWidth',$('#gbox_'+pms_list).parent().innerWidth());//넓이지정
			$("#"+pms_list).jqGrid('setGridHeight',$('#gbox_'+pms_list).parent().outerHeight() -55);//높이지정
			$('#loading').modal("hide");
			$('.gridCnt').html('전체 :<strong>'+$("#"+pms_list).getGridParam("records")+'</strong> 건');//카운트 넣기
			$(window).trigger('resize');
			$('[data-toggle="tooltip"]').tooltip();
			fn_btnSetting();
			
			//param초기화
			$("#"+pms_list).setGridParam({
				postData: {
					"REMOVE_ARR" :null,
					"DEL_ARR" :null,
					"BOKWON_ARR":null,
					"FA_YN":null
				}
			});
			
		}
	});
	hashSet();
});

/* 검색 */
function fn_search(){
	$("#"+pms_list).setGridParam({
		postData:{
			"searchOper"	:$("#searchOper").val(),
			"_search1"		:$("#_search1").val(),
			"searchField"	:$("#searchField option:selected").val(),
			"searchString"	:$("#searchString").val()
		},
		page:1
	}).trigger("reloadGrid");
}

//즐찾 셋팅
function formatOpt1(cellvalue, options, rowObject){
	var str ='';
	var progress = '';
	
	var status = rowObject[8];
	
	if(status != null){
		if(status >= 100){
			progress = '<div>프로젝트가 완료되었습니다.</div>';
		}else{
			
			var s = 0;
			var t;
					t = setInterval(function(){
						
						if(s >= status){
							clearInterval(t);
						}
						$('.L_'+rowObject[0]).css('width',s+'%');
						$('.per_'+rowObject[0]).text(s);
						s++;
					},10);
			
			progress = '<div class="mb-5">현재 <span class="per_'+rowObject[0]+'">'+status+'</span>% 진행중</div><div class="progress"><div class="progress-bar L_'+rowObject[0]+' progress-bar-<?php echo $this->config->item($this->uri->segment(1).'Color');?>" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div> </div>';
		}
	}
	/*
	if(gap < 0){
		var gapT = (gap - gap) - gap;
		gap =  '<code class="mt-10">완료요청일 '+gapT+ '일 지났습니다.</code>';
	}else{
		if(gap == 0){
			gap =  ' <kbd class="mt-10">완료요청일 오늘 까지입니다.</kbd>';
		}else{
			gap =  ' <span class="codeGreen">완료요청일 ' +gap+ '일 남았습니다.</span>';
		}
	}
	 */
	//즐찾이면
	
	if(rowObject[11] > 0){
		str += "<span class=\"test1\"><sapn data-toggle='tooltip' data-placement='right' title='즐겨찾기 취소' class='glyphicon glyphicon-star' style='color:orange;cursor:pointer;margin-right:4px' onclick=\"fa_btn('"+rowObject[0]+"')\"></span><span class='link' onclick='linkGrid(this)' data='"+rowObject[0]+"'>"+cellvalue+"</span><div style='margin-top:-13px;' > "+progress+"</div></span>";	
		str += '<div class="mt-10"><input type="button" class="btn btn-default btn-xs" onclick=fn_bomView("'+rowObject[0]+'") value="부품정보"/>&nbsp;<input type="button" class="btn btn-default btn-xs" onclick=fn_wbsView("'+rowObject[0]+'") value="WBS VIEW"/></div>'; 
	}else{
		str += "<span class=\"test1\"><sapn data-toggle='tooltip' data-placement='right' title='즐겨찾기 추가' class='glyphicon glyphicon-star-empty' style='color:#ccc;cursor:pointer;margin-right:4px' onclick=\"fa_btn('"+rowObject[0]+"')\"></span><span class='link' onclick='linkGrid(this)' data='"+rowObject[0]+"'>"+cellvalue+"</span><div style='margin-top:-13px;'> "+progress+"</div></span>";
		str += '<div class="mt-10"><input type="button" class="btn btn-default btn-xs" onclick=fn_bomView("'+rowObject[0]+'") value="부품정보"/>&nbsp;<input type="button" class="btn btn-default btn-xs" onclick=fn_wbsView("'+rowObject[0]+'") value="WBS VIEW"/></div>'; 
	}
	return str;
	
}
//진행상태
function formatOpt2(cellvalue, options, rowObject){
	var progress = '';
	if(cellvalue != undefined){
		progress = cellvalue + '%';
	}
	return progress;
}

//wbs view
function formatOpt3(cellvalue, options, rowObject){
	return '<input type="button" class="btn btn-default btn-xs" onclick=fn_wbsView("'+rowObject[0]+'") value="WBS VIEW"/>'; 
}

//wbs view 클릭
function fn_wbsView(v){
	PopupCenter("<?php echo site_url()?>/pms/WbsView?id="+v, 'WBSVIEW', '1000', '600');
}

//부품정보
function formatOpt4(cellvalue, options, rowObject){
	return '<input type="button" class="btn btn-default btn-xs" onclick=fn_bomView("'+rowObject[0]+'") value="부품정보"/>'; 
}

//wbs view 클릭
function fn_bomView(v){
	$("#bomList").modal('show');
	bomList(v);
	$('#bomList').on('hide.bs.modal', function (event) {
	  $("#bom_list").jqGrid('GridUnload');
	});
	/*PopupCenter("<?php echo site_url()?>/pms/BomView?id="+v, 'BOMVIEW', '1000', '600');*/
}

//즐찾여부
function fa_btn(val){
	$("#"+pms_list).setGridParam({
		postData:{
			"FA_YN":"true",
			"FA_TYPE":"pms",
			"FA_USER":"<?php echo $_SESSION['userid'];?>",
			"FA_VAL": val
		}
	}).trigger("reloadGrid");
}

//즐찾 보기
function faView(t){

	if(!$(t).hasClass('tg')){
		$("#"+pms_list).setGridParam({
			postData:{
				"FA_SORT_STAR":"true",
				"FA_USER": "<?php echo $_SESSION['userid'];?>",
				"FA_TYPE": 'pms'
			},
			page:1
		}).trigger("reloadGrid");
		$(t).addClass('tg').html('<span class="glyphicon glyphicon-star" aria-hidden="true"></span> 즐겨찾기 닫기');
	}else{
		$("#"+pms_list).setGridParam({
			postData:{
				"FA_SORT_STAR":"false"
			},
			page:1
		}).trigger("reloadGrid");
		$(t).removeClass('tg').html('<span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span> 즐겨찾기 보기');
	}
}

//링크 클릭
function linkGrid(t){
	var data = $(t).attr('data');
	var hash = window.location.hash;
	location.href="<?php echo site_url();?>/pms/Write?id="+data+hash;
}
//그리드 새로고침
function ref(){
	location.href="<?php echo site_url()?>/pms/Main";
}

//체크박스 선택제어
function selectRows(){
	var docType = $("#pms_list").getGridParam("postData").docType;
	var ids = $("#"+pms_list).jqGrid('getGridParam', 'selarrrow');      //체크된 row id들을 배열로 반환
	if(ids.length > 0){
		bootbox.confirm({
			size: "small",
			message: "총 "+ids.length+"건을 삭제하시겠습니까? ",
			buttons: {
				confirm: {
					label: '확인',
					className: "btn-<?php echo $this->config->item($this->uri->segment(1).'Color');?>"
				},
				cancel: {
					label: '취소'
				}
			},
			callback: function (result) {
				if(result == true){
					var arr = [];
					for(var i = 0; i < ids.length; i++){
						var rowObject = $("#"+pms_list).getRowData(ids[i]);      //체크된 id의 row 데이터 정보를 Object 형태로 반환
						var value = rowObject.PP_ID;     //Obejct key값이 PR_ID value값 반환
						arr.push(value);
					}
					
					if(docType == 'trash'){
							var postdata = {
								"REMOVE_ARR" :arr
							}
						}else{
							
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
								"DEL_ARR" :arr
							}
						}
					
					$("#"+pms_list).setGridParam({
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
					className: "btn-<?php echo $this->config->item($this->uri->segment(1).'Color')?>"
				}
			}
		});
		return preventDefaultAction(false);
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
		url: '<?php echo base_url();?>index.php/pms/Main/chkInsId',
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

/* 버튼세팅변경 */
function fn_btnSetting(){
	var docType = $("#pms_list").getGridParam("postData").docType;
	if(docType == 'trash'){	//휴지통
		$('.selectDel').html('<span class="glyphicon glyphicon-floppy-remove" aria-hidden="true"></span>영구삭제');
		$('.selectBokwon').css('display','');
	}else{
		$('.selectDel').html('<span class="glyphicon glyphicon-floppy-remove" aria-hidden="true"></span>선택삭제');
		$('.selectBokwon').css('display','none');
	}
}
</script>
<?php include("bomView.php"); ?>	<!-- bom 팝업 -->
<div id="wp_right">
	<div class="search_area p-20 gray_border_bottom">
		<form id="frm_search" name="frm_search" method="post" onsubmit="return false;">
			<input type="hidden" id="searchOper" name="searchOper" value="cn" />
			<input type="hidden" id="_search1" name="_search1" value="true" />
			<select class="form-control width_100px" id="searchField" name="searchField">
				<option value="PP_NM">프로젝트</option>
				<option value="PC_NM">거래처</option>
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
		</form>
		<div>
			<button type="button" class="selectDel btn btn-default btn-sm mt-10" onclick="selectRows();">
				<span class="glyphicon glyphicon-floppy-remove" aria-hidden="true"></span>
				선택삭제
			</button>
			
			<button type="button" class="selectBokwon btn btn-default btn-sm mt-10">
			<span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>
				선택복원
			</button>
			
			<button type="button" class="btn btn-default btn-sm mt-10" onclick="faView(this)">
				<span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span>
				즐겨찾기만 보기
			</button>
			
		</div>
		<div class="location mt-10 pl-0">
			<span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> 
				<span class="locas">프로젝트 목록</span>
			<div class="pull-right gridCnt">
				로딩중...
			</div>
		</div>
		
	</div>
	<div class="grid_area">
		<table id="pms_list" style="border-collapse: inherit;">
		</table>
		<div id="pms_pager"></div>
	</div>
</div>	


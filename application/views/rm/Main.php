<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script>
	//기존설정
	$.extend($.jgrid.ajaxOptions, { async: false });//그리드 동기로 변환
	var rm_list = "rm_list";//그리드 아이디
	var rm_pager = "rm_pager";//그리드 페이징
	
	
	function tab_type(t,v){//탭타입 all:1:2
		$("#"+rm_list).setGridParam({
			postData:{
				"TAB":v
			},
			page:1
		}).trigger("reloadGrid");
		/*
		$('.btn_tabs').removeClass("btn-<?php echo $this->config->item($this->uri->segment(1).'Color')?>").addClass('btn-default');
		$(t).addClass("btn-<?php echo $this->config->item($this->uri->segment(1).'Color')?>");
		*/
	}
	
	function ref(){//그리드 새로고침
		location.href="<?php echo site_url()?>/rm/Main";
	}
	
	function faView(t){//즐찾 보기
		if(!$(t).hasClass('tg')){
			$("#"+rm_list).setGridParam({
	        postData:{
	        	"FA_SORT_STAR":"true",
	        	"FA_USER": "<?php echo $_SESSION['userid'];?>",
	        	"FA_TYPE": 'rm'
	        },
	        page:1
	    }).trigger("reloadGrid");
	    $(t).addClass('tg').html('<span class="glyphicon glyphicon-star" aria-hidden="true"></span> 즐겨찾기 닫기');
  	}else{
			$("#"+rm_list).setGridParam({
	        postData:{
	        	"FA_SORT_STAR":"false"
	        },
	        page:1
	    }).trigger("reloadGrid");
	    $(t).removeClass('tg').html('<span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span> 즐겨찾기 보기');
  	}
	}
	
		
	//체크박스 선택제어
	function selectRows(t){
		var docType = $("#"+rm_list).getGridParam("postData").docType;
		var ids = $("#"+rm_list).jqGrid('getGridParam', 'selarrrow');      //체크된 row id들을 배열로 반환
		if(t == 'del'){
			var msg = "총 "+ids.length+"건을 삭제하시겠습니까? ";
		}else{
			var msg = "총 "+ids.length+"건을 복원하시겠습니까? ";
		}
		
		if(ids.length > 0){
			bootbox.confirm({
				size: "small",
				message: msg,
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
							var rowObject = $("#"+rm_list).getRowData(ids[i]);      //체크된 id의 row 데이터 정보를 Object 형태로 반환
							var value = rowObject.PR_ID;     //Obejct key값이 PR_ID value값 반환
							arr.push(value);
						}
						if(t == 'del'){
							
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
						}else{
							var postdata = {
									"BOKWON_ARR":arr
								}
						}
						$("#"+rm_list).setGridParam({
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
			url: '<?php echo base_url();?>index.php/rm/Main/chkInsId',
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
	
	function formatOpt1(cellvalue, options, rowObject){
	  var str ='';
	  //디데이구하기
		var now = new Date();
		
		var then = new Date(parseDate(rowObject[3])); 
		var gap = then.getTime() - now.getTime();
		gap = Math.floor(gap / (1000 * 60 * 60 * 24) + 1);
		if(gap > 10){
			gap_w = 10;
		}else{
			gap_w = gap;
		}
		var lines = '<span class="rBarNm r1"></span><span class="rBarNm r2"></span><span class="rBarNm r3"></span><span class="rBarNm r4"></span><span class="rBarNm r5"></span><span class="rBarNm r6"></span><span class="rBarNm r7"></span><span class="rBarNm r8"></span><span class="rBarNm r9"></span><span class="rBarNm r10"></span>';
		if(gap < 0){
			var gapT = (gap - gap) - gap;
			gap =  '<span class="mt-10"><span class="rBarWp">'+lines+'<span class="lbg">0</span><div class="rBar"></div><span class="rbg">+10</span></span><br />완료요청일 '+gapT+ '일 지났습니다.</span>';
		}else{
			if(gap == 0){
				gap =  '<span class="mt-10"><span class="rBarWp">'+lines+'<span class="lbg">0</span><div class="rBar"><span style="width: 5%;background:red"></span></div><span class="rbg">+10</span></span><br />완료요청일 오늘 까지입니다.</span>';
			}else{
				if(gap > 4){
				gap =  '<span class="mt-10"><span class="rBarWp">'+lines+'<span class="lbg">0</span><div class="rBar"><span style="width: '+gap_w+'0%;"></span></div><span class="rbg">+10</span></span><br />완료요청일 ' +gap+ '일 남았습니다.</span>';
				}else{
					gap =  '<span class="mt-10"><span class="rBarWp">'+lines+'<span class="lbg">0</span><div class="rBar"><span style="width: '+gap_w+'0%;background:orange"></span></div><span class="rbg">+10</span></span><br />완료요청일 ' +gap+ '일 남았습니다.</span>';
				}
			}
		}
		switch(rowObject[4]) {
		  case '1':
		      gap = gap;
		      break;
		  case '2':
		      gap = '<span class="mt-10 comp_list"><i class="fa fa-check" style="color:green;font-size:10px"></i>&nbsp;&nbsp;조치 완료된 항목입니다.</span>';
		      break;
		  case '3':
		      gap = gap;
		      break;
		 	case '4':
		      gap = '<span class="mt-10 err_list"><i class="fa fa-repeat" style="color:#ccc;font-size:10px"></i>&nbsp;&nbsp;반려된 항목입니다.</span>';
		      break;
		  default:
		      gap = '';
		}
		
		if(rowObject[8] > 0){
			str += "<span class=\"test1\"><sapn data-toggle='tooltip' data-placement='right' title='즐겨찾기 취소' class='glyphicon glyphicon-star' style='color:orange;cursor:pointer;margin-right:4px' onclick=\"fa_btn('"+rowObject[0]+"')\"></span><span class='link' onclick='linkGrid(this)' data='"+rowObject[0]+"'>"+cellvalue+"</span><div class='mt-5'> "+gap+"</div></span>";
		}else{
			str += "<span class=\"test1\"><sapn data-toggle='tooltip' data-placement='right' title='즐겨찾기 추가' class='glyphicon glyphicon-star-empty' style='color:#ccc;cursor:pointer;margin-right:4px' onclick=\"fa_btn('"+rowObject[0]+"')\"></span><span class='link' onclick='linkGrid(this)' data='"+rowObject[0]+"'>"+cellvalue+"</span><div class='mt-5'> "+gap+"</div></span>";
		}
		return str;
	}
	
	//new Date() 사용 시 , IE에서 'Invalid Date' 발생해서 이 함수 사용
	function parseDate(strDate) {
	    var _strDate = strDate;
	    var _dateObj = new Date(_strDate);
	    if (_dateObj.toString() == 'Invalid Date') {
	        _strDate = _strDate.split('.').join('-');
	        _dateObj = new Date(_strDate);
	    }
	    if (_dateObj.toString() == 'Invalid Date') {
	        var _parts = _strDate.split(' ');
	 
	        var _dateParts = _parts[0];
	        _dateObj = new Date(_dateParts);
	 
	        if (_parts.length > 1) {
	            var _timeParts = _parts[1].split(':');
	            _dateObj.setHours(_timeParts[0]);
	            _dateObj.setMinutes(_timeParts[1]);
	            if (_timeParts.length > 2) {
	                _dateObj.setSeconds(_timeParts[2]);
	            }
	        }
	    }
	 
	    return _dateObj;
	}	
	
	function formatOpt3(cellvalue, options, rowObject){//진행상태
		
		if(cellvalue == '1'){
			return '접수완료';
		}
		if(cellvalue == '2'){
			return '조치완료';
		}
		if(cellvalue == '3'){
			return '진행중';
		}
		if(cellvalue == '4'){
			return '반려됨';
		}
		
	}
	
	function fa_btn(val){//즐찾여부
		$("#"+rm_list).setGridParam({
			postData:{
				"FA_YN":"true",
				"FA_TYPE":"rm",
				"FA_USER":"<?php echo $_SESSION['userid'];?>",
				"FA_VAL": val
			}
		}).trigger("reloadGrid");
	}
	
	//링크 클릭
	function linkGrid(t){
		var data = $(t).attr('data');
		var hash = window.location.hash;
		location.href="<?php echo site_url();?>/rm/View?id="+data+hash;
	}
	
	/* 검색 */
	function fn_search(){
		$("#"+rm_list).setGridParam({
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
			$("#"+rm_list).jqGrid('setGridWidth',$('#gbox_'+rm_list).parent().innerWidth());//넓이지정
			$("#"+rm_list).jqGrid('setGridHeight',$('#gbox_'+rm_list).parent().outerHeight() -55);//높이지정
		});
	});
	
	$(window).load(function(){
		$("#"+rm_list).jqGrid({//그리드 세팅
      url:'<?php echo site_url()?>'+'/rm/Main/loadData',      
      mtype : "POST",             
      datatype: "json",            
      colNames:['PR_ID','제목','거래처','완료요청일','진행상태','INS_ID','작성자','최종수정일'],       
      colModel:[
          {name:'PR_ID',index:'PR_ID', width:100, align:"center", hidden:true},
          {name:'PR_TITLE',index:'PR_TITLE', width:300, align:"left",formatter:formatOpt1},
		  {name:'PC_NM',index:'PC_NM', width:100, align:"center"},
          {name:'PR_HOPE_END_DAT',index:'PR_HOPE_END_DAT', width:150, align:"center", formatter:"date", formatoptions:{newformat:"Y-m-d"}},
          {name:'PR_STATUS',index:'PR_STATUS', width:100, align:"center",formatter:formatOpt3},
          {name:'INS_ID',index:'INS_ID', width:100, align:"center", hidden:true},
		  {name:'INS_NM',index:'INS_NM', width:100, align:"center"},
          {name:'UPD_DT',index:'UPD_DT', width:150, align:"center", formatter:"date", formatoptions:{newformat:"Y-m-d"}}
      ],
      rowNum:30,
      rowList:[30,100,500],
      pager: '#'+rm_pager,
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
      	
      	$("#"+rm_list).jqGrid('setGridWidth',$('#gbox_'+rm_list).parent().innerWidth());//넓이지정
		$("#"+rm_list).jqGrid('setGridHeight',$('#gbox_'+rm_list).parent().outerHeight() -55);//높이지정
		$('#loading').modal("hide");
		$('.gridCnt').html('전체 :<strong>'+$("#"+rm_list).getGridParam("records")+'</strong> 건');//카운트 넣기
		$(window).trigger('resize');
		$('[data-toggle="tooltip"]').tooltip();
		
		$('.comp_list').parent().parent().parent().css('background','#fbfffc');
		$('.err_list').parent().parent().parent().css('background','#fffbfb');
		
		fn_btnSetting();
		
		//param초기화
		$("#"+list).setGridParam({
			postData: {
				"REMOVE_ARR" :null,
				"DEL_ARR" :null,
				"BOKWON_ARR":null,
				"FA_YN":null
			}
		});
		
		fn_chkDisabled('rm_list');	//권한 (관리자 , 작성자만 체크박스 활성화) string : jqgrid table id
		
    }
	});
  hashSet();
	});
	
	

/* 버튼세팅변경 */
function fn_btnSetting(){
	var docType = $("#rm_list").getGridParam("postData").docType;
	if(docType == 'trash'){	//휴지통
		$('.selectDel').html('<span class="glyphicon glyphicon-floppy-remove" aria-hidden="true"></span>영구삭제');
		$('.selectBokwon').css('display','');
	}else{
		$('.selectDel').html('<span class="glyphicon glyphicon-floppy-remove" aria-hidden="true"></span>선택삭제');
		$('.selectBokwon').css('display','none');
	}
}


</script>
<div id="wp_right">
	<div class="search_area p-20 gray_border_bottom">
		<form id="frm_search" name="frm_search" method="post" onsubmit="return false;">
			<input type="hidden" id="searchOper" name="searchOper" value="cn" />
			<input type="hidden" id="_search1" name="_search1" value="true" />
			<select class="form-control width_100px" id="searchField" name="searchField">
				<option value="PR_TITLE">제목</option>
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
			
			<select class="form-control width_100px input-sm mt-10" name="TAB" onchange="tab_type(this,this.value)">
				<option value="all">전체</option>
				<option value="1" selected>접수완료</option>
				<option value="3">진행중</option>
				<option value="2">조치완료</option>
				<option value="4">반려</option>
			</select>
			<!--
			<button type="button" onclick="tab_type(this,'all')" class="mt-10 btn_tabs btn-sm btn btn-default">
				전체
			</button>
			<button type="button" onclick="tab_type(this,'3')" class="mt-10 btn_tabs btn-sm btn btn-default">
				진행중
			</button>
			<button type="button" onclick="tab_type(this,'1')" class="mt-10 btn_tabs btn-sm btn btn-<?php echo $this->config->item($this->uri->segment(1).'Color')?>">
				접수완료
			</button>
			<button type="button" onclick="tab_type(this,'2')" class="mt-10 btn_tabs btn-sm btn btn-default">
				조치완료
			</button>
			-->
			
		</div>
		</form>
		<div class="location mt-10 pl-0">
			<span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
				<span class="locas">요구사항 목록</span>
			<div class="pull-right gridCnt">
				로딩중...
			</div>
		</div>
		
	</div>
	<div class="grid_area">
		<table id="rm_list" style="border-collapse: inherit;">
		</table>
		<div id="rm_pager"></div>
	</div>
</div>		

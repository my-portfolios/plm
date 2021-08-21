<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script>
	
	$.extend($.jgrid.ajaxOptions, { async: false });//그리드 동기로 변환
	//설정
	var list = "pdm_list";//그리드 아이디
	var pager = "pdm_pager";//그리드 페이징
	var jsleftTree = $('#jstree-tree-left');
	
	function selectTypeGrid(gridType){//그리드 타입변경
		$.cookie('gridType',gridType,{path: '/'});
		location.href="Main";
	}
	//그리드TYPE 별 모델
	function gridSet(){
		var gridType = $.cookie('gridType');
		if(gridType =='grid'){
			var cm = [
          {name:'PF_ID',index:'PF_ID', width:100, align:"center", hidden:true},
          {name:'PFD_ID',index:'PFD_ID', width:100, align:"center", hidden:true},
          {name:'PF_NM',index:'PF_NM', width:200, align:"left",formatter:formatOpt1},
		  {name:'PC_NM',index:'PC_NM', width:200, align:"center"},
          {name:'PF_PATH',index:'PF_PATH', width:100, align:"center",hidden:true},
          {name:'PF_FILE_SIZE',index:'PF_FILE_SIZE', width:100, align:"center",formatter:formatBytes, hidden:true},
          {name:'PF_FILE_EXT',index:'PF_FILE_EXT', width:100, align:"center",hidden:true},
          {name:'INS_ID',index:'INS_ID', width:100, align:"center", hidden:true},
          {name:'INS_DT',index:'INS_DT', width:100, align:"center",hidden:true},
          {name:'UPD_ID',index:'UPD_ID', width:100, align:"center",hidden:true},
          {name:'UPD_DT',index:'UPD_DT', width:100, align:"center",formatter:updateDate, hidden:true},
          {name:'PF_FILE_TEMP_NM',index:'PF_FILE_TEMP_NM', width:100, align:"center",hidden: true},
          {name:'PF_FILE_REAL_NM',index:'PF_FILE_REAL_NM', width:100, align:"center",hidden: true},
          {name:'KEYWORD',index:'KEYWORD', width:100, align:"center",hidden: true},
          {name:'PF_CNT',index:'PF_CNT', width:100, align:"center", hidden:true}
      ];
      var ps = 49;
		}else{
			var cm = [
					
          {name:'PF_ID',index:'PF_ID', width:100, align:"center", hidden:true},
          {name:'PFD_ID',index:'PFD_ID', width:100, align:"center", hidden:true},
          {name:'PF_NM',index:'PF_NM', width:200, align:"left",formatter:formatOpt1},
		  {name:'PC_NM',index:'PC_NM', width:200, align:"center"},
          {name:'PF_PATH',index:'PF_PATH', width:100, align:"center",hidden:true},
          {name:'PF_FILE_SIZE',index:'PF_FILE_SIZE', width:100, align:"center",formatter:formatBytes},
          {name:'PF_FILE_EXT',index:'PF_FILE_EXT', width:100, align:"center",hidden:true},
          {name:'INS_ID',index:'INS_ID', width:100, align:"center"},
          {name:'INS_DT',index:'INS_DT', width:100, align:"center",hidden:true},
          {name:'UPD_ID',index:'UPD_ID', width:100, align:"center",hidden:true},
          {name:'UPD_DT',index:'UPD_DT', width:100, align:"center",formatter:updateDate},
          {name:'PF_FILE_TEMP_NM',index:'PF_FILE_TEMP_NM', width:100, align:"center",hidden: true},
          {name:'PF_FILE_REAL_NM',index:'PF_FILE_REAL_NM', width:100, align:"center",hidden: true},
          {name:'KEYWORD',index:'KEYWORD', width:100, align:"center",hidden: true},
          {name:'PF_CNT',index:'PF_CNT', width:100, align:"center", hidden:true}
          
      ];
      var ps = 30;
		}
		
		//그리드 세팅
		$("#"+list).jqGrid({
      url:'<?php echo site_url()?>'+'/pdm2/Main/loadData',      
      mtype : "POST",             
      datatype: "json",            
      colNames:['PF_ID','PFD_ID','제목','거래처','PF_PATH','파일용량','PF_FILE_EXT','작성자','작성일','UPD_ID','최종수정일','PF_FILE_TEMP_NM','PF_FILE_REAL_NM','KEYWORD','FA_CNT'],       
      colModel:cm,
      rowNum:ps,
      rowList:[ps,100,500],
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
      	//기존 로딩 사요안함 style로 display none 동기시 작동안할듯
      	$('#loading').modal("show");
      },
      loadComplete:function(data){
      	//높이 맞춤
      	heightAuto('#wp_top','#wp_left','#wp_right','#wp_bottom','.search_area');
      	//넓이지정
      	$("#"+list).jqGrid('setGridWidth',$('#gbox_'+list).parent().innerWidth());
      	//높이지정
		$("#"+list).jqGrid('setGridHeight',$('#gbox_'+list).parent().outerHeight() -55);
		//카운트 넣기
		$('.gridCnt').html('전체 :<strong>'+$.number($("#"+list).getGridParam("records"))+'</strong> 건');
		//로딩 하이딩
		$('#loading').modal("hide");
		$(window).trigger('resize');
		$('[data-toggle="tooltip"]').tooltip();
		
		//버튼세팅
		fn_btnSetting();
		//그리드,리스트 버튼 active 변경
		var t = $.cookie('gridType');
		if(t == 'grid'){
			$('.tgrid').addClass('active');
		}
		if(t == 'list' || t == undefined){
			$('.tl').addClass('active');
		}
		
		//영구삭제,삭제,복원,즐겨찾기 데이타 초기화
		$("#"+list).setGridParam({
			postData: {
				"REMOVE_ARR" :null,
				"DEL_ARR" :null,
				"BOKWON_ARR":null,
				"FA_YN":null
			}
		});
		
		fn_chkDisabled(list);	//권한 (관리자 , 작성자만 체크박스 활성화) string : jqgrid table id
      }
	  });
	  
	  //해쉬실행(left.php)
	  hashSet();
	}
	//검색폼 POSTDATA에 넣기
	function detailSearchSeri(){
		var frm = $('[id=frm_search]').serialize();
		var frmArr = frm.split('&');
		var obj = {};
		$.each(frmArr,function(i,v){
			var vArr = v.split('=');
			eval ("obj."+ vArr[0] + "= '"+vArr[1]+"'");
		});
		$("#"+list).setGridParam({
			postData:obj
		});
		
	}
	//그리드 새로고침
	function ref(){
		location.href="<?php echo site_url()?>/pdm2/Main";
	}
	
	
	$(function(){
		//검색 클릭
		$(document).on('click','.search',function(){
			fn_search();
		});
		
		//상세검색 toogle
		$(document).on('click','.sd_y',function(){
			$(this).addClass('sd_n').removeClass('sd_y');
			$('.searchDetails').show();
			$(window).trigger('resize');
			$(this).find('span').removeClass('glyphicon-menu-down').addClass('glyphicon-menu-up');
			$("#"+list).setGridParam({
				postData:{
					"detailSearch":"y"
				}
			});
		});
		$(document).on('click','.sd_n',function(){
			$(this).addClass('sd_y').removeClass('sd_n');
			$('.searchDetails').hide();
			$(window).trigger('resize');
			$(this).find('span').removeClass('glyphicon-menu-up').addClass('glyphicon-menu-down');
			$("#"+list).setGridParam({
				postData:{
					"detailSearch":"n"
				}
			});
		});
			
		//리사이즈 이벤트
		$(window).resize(function(){
			
			//넓이지정
			$("#"+list).jqGrid('setGridWidth',$('#gbox_'+list).parent().innerWidth());
			//높이지정
			$("#"+list).jqGrid('setGridHeight',$('#gbox_'+list).parent().outerHeight() -55);
		});
		
		//선택이동
		$(document).on('click','.selectMove',function(){
			var selRowIds = $("#"+list).jqGrid("getGridParam", "selarrrow");
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
				$('#pop_fileMove').modal('show');
				fn_getFileMoveTree(selRowIds);
			}
		});
		
		/* 검색 */
		$(document).on('keydown','#searchString',function(key){
			if(key.keyCode == 13){
				fn_search();
			}
			
		});
		
		/* 선택복원 */
		$(document).on('click','.selectBokwon',function(){
			var selRowIds = $("#"+list).jqGrid("getGridParam", "selarrrow");
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
				var msg = "총 "+selRowIds.length+"건을 복원하시겠습니까? ";
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
					callback: function(result){
						if(result == true){
							fn_bokwon(selRowIds);
						}
					}
				});
			}
		});
	});
	
	/* 선택복원 */
	function fn_bokwon(chkArr){
		
		$("#"+list).setGridParam({
			postData:{
				"BOKWON_ARR":chkArr
			},
			page:1
		}).trigger("reloadGrid");
		
	}
	
	/* 버튼세팅변경 */
	function fn_btnSetting(){
		var docType = $("#"+list).getGridParam("postData").docType;
		if(docType == 'trash'){	//휴지통
			$('.selectMove').prop('disabled',true);
			$('.selectDel').html('<span class="glyphicon glyphicon-floppy-remove" aria-hidden="true"></span>영구삭제');
			$('.selectBokwon').css('display','');
		}else{
			$('.selectMove').prop('disabled',false);
			$('.selectDel').html('<span class="glyphicon glyphicon-floppy-remove" aria-hidden="true"></span>선택삭제');
			$('.selectBokwon').css('display','none');
		}
	}
	
	/* 검색 */
	function fn_search(){
		
		detailSearchSeri();
		
		var gridPostData = $("#"+list).getGridParam("postData");
		jsleftTree.jstree('select_node',gridPostData.treeid);
		
		$("#"+list).setGridParam({
		postData:{
			"searchOper":'cn',
			"_search1":'true',
			"searchField":$("#searchField option:selected").val(),
			"searchString":$("#searchString").val()
		},
		page:1
		}).trigger("reloadGrid");
		
		
	}
	//바이트 계산
	function formatBytes(cellvalue, options, rowObject) {
	    if(cellvalue < 1024) return cellvalue + " Bytes";
	    else if(cellvalue < 1048576) return(cellvalue / 1024).toFixed(1) + " KB";
	    else if(cellvalue < 1073741824) return(cellvalue / 1048576).toFixed(1) + " MB";
	    else return(cellvalue / 1073741824).toFixed(1) + " GB";
	};
	function updateDate(cellvalue, options, rowObject) {
		/*
			if(rowObject[9]){
				 return cellvalue + '<br /><span style="font-size:10px;color:#999">작성일 ' +rowObject[7] + '</span>'
			}else{
	    	return rowObject[7];
	  	}
	  */
	  return cellvalue;
	};
	
	function linkGrid(t){//링크 클릭
		if($.type(t) == 'object'){
			var data = $(t).attr('data');
		}else{
			var data = t;
		}
		location.hash = 'VIEW_'+data;
	}
	
	function formatOpt1_old(cellvalue, options, rowObject){//즐찾 셋팅 넘느림
	  var str ='';
	  var data = {
			"FA_CHECK": rowObject[0],
			"FA_TYPE": 'pdm2',
			"FA_USER": "<?php echo $_SESSION['userid'];?>"
		};
  	$.ajax({
			type: 'post',
			dataType: 'json',
			url: '<?php echo base_url();?>index.php/pdm2/Main/fa_check',
			data: data,
			async : false,
			success: function (data) {
				//이미지면 이미지로 보기
				if(rowObject[5] == 'jpg' || rowObject[5] =='jpeg' || rowObject[5] == 'gif' || rowObject[5] == 'png'){
					var imgUrl = "<img style='border:1px solid #ededed; vertical-align:middle;margin-top:-4px' width='34' height='40' src='<?php echo site_url()?>/pdm2/Upload_view/fileDownload?tempName="+rowObject[10]+"&fileName="+rowObject[11]+"' />";
					
				}else{
					var imgUrl = '';
				}
				//확장자가 없으면
				if(rowObject[5] == null || rowObject[5] == ''){
					rowObject[5] = 'none';
				}
				//키워드가 없으면
				if(rowObject[12] == null || rowObject[12] == ''){
					rowObject[12] = '등록된 키워드가 없습니다.';
				}
				//즐찾이면
				if(data == 0){
					str += "<span class='listIconType'>"+imgUrl+extIcon(rowObject[5])+"<p style='font-size:10px;margin-top:-6px;white-space: nowrap; overflow: hidden; text-overflow: ellipsis;'>"+rowObject[5]+"</p></span> <span class=\"test1\"><sapn data-toggle='tooltip' data-placement='right' title='즐겨찾기 추가' class='glyphicon glyphicon-star-empty' style='color:#ccc;cursor:pointer;margin-right:4px' onclick=\"fa_btn('"+rowObject[0]+"')\"></span><a title='"+cellvalue+"' class='link' onclick='linkGrid(this)' href='#' data='"+rowObject[0]+"'>"+cellvalue+"</a><div>"+rowObject[3]+"</div><div>"+rowObject[12]+"</div></span>";
				}else{
					str += "<span class='listIconType'>"+imgUrl+extIcon(rowObject[5])+"<p style='font-size:10px;margin-top:-6px;white-space: nowrap; overflow: hidden; text-overflow: ellipsis;'>"+rowObject[5]+"</p></span> <span class=\"test1\"><sapn data-toggle='tooltip' data-placement='right' title='즐겨찾기 취소' class='glyphicon glyphicon-star' style='color:orange;cursor:pointer;margin-right:4px' onclick=\"fa_btn('"+rowObject[0]+"')\"></span><a title='"+cellvalue+"' class='link' onclick='linkGrid(this)' href='#' data='"+rowObject[0]+"'>"+cellvalue+"</a><div>"+rowObject[3]+"</div><div>"+rowObject[12]+"</div></span>";
				}
			},
			error: function (request, status, error) {
				console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
			}
		});
		
		return str;
		
	}
	
	function formatOpt1(cellvalue, options, rowObject){//즐찾 셋팅 & 제목
		var str ='';
		var imgUrl = '';
		//이미지면 이미지로 보기
		if(rowObject[6] == 'jpg' || rowObject[6] == 'gif' || rowObject[6] == 'png'){
			/*
			imgUrl = "<img style='border:1px solid #ededed; vertical-align:middle;margin-top:-4px' width='34' height='40' src='<?php echo site_url()?>/pdm2/Upload_view/fileDownload?tempName="+rowObject[10]+"&fileName="+rowObject[11]+"' />";
			*/
			var spTh = rowObject[11].split('.');	
			imgUrl = "<div style='width:34px;height:40px;overflow:hidden;margin:0 auto'><a href='/uploads/"+spTh[0]+"."+spTh[1]+"' data-toggle='lightbox'><img class='img-fluid' style='border:1px solid #ededed; vertical-align:middle;margin-top:-4px' width='34' height='' src='/uploads/"+spTh[0]+"_thumb."+spTh[1]+"' /></a></div>";
		}else{
			imgUrl = '';
		}
		
		//확장자가 없으면
		if(rowObject[6] == null || rowObject[6] == ''){
			rowObject[6] = 'none';
		}
		//키워드가 없으면
		if(rowObject[13] == null || rowObject[13] == ''){
			rowObject[13] = '등록된 키워드가 없습니다.';
		}
		//즐찾이면
		if(rowObject[14] > 0){
			str += "<span class='listIconType'>"+imgUrl+extIcon(rowObject[6])+"<p style='font-size:10px;white-space: nowrap; overflow: hidden; text-overflow: ellipsis;'>"+rowObject[6]+"</p></span> <span class=\"test1\"><sapn data-toggle='tooltip' data-placement='right' title='즐겨찾기 취소' class='glyphicon glyphicon-star' style='color:orange;cursor:pointer;margin-right:4px' onclick=\"fa_btn('"+rowObject[0]+"')\"></span><a title='"+cellvalue+"' class='link' onclick='linkGrid(this)' href='#VIEW_"+rowObject[0]+"' data='"+rowObject[0]+"'>"+cellvalue+"</a><div>"+rowObject[4]+"</div><div>"+rowObject[13]+"</div></span>";	
		}else{
			str += "<span class='listIconType'>"+imgUrl+extIcon(rowObject[6])+"<p style='font-size:10px;white-space: nowrap; overflow: hidden; text-overflow: ellipsis;'>"+rowObject[6]+"</p></span> <span class=\"test1\"><sapn data-toggle='tooltip' data-placement='right' title='즐겨찾기 추가' class='glyphicon glyphicon-star-empty' style='color:#ccc;cursor:pointer;margin-right:4px' onclick=\"fa_btn('"+rowObject[0]+"')\"></span><a title='"+cellvalue+"' class='link' onclick='linkGrid(this)' href='#VIEW_"+rowObject[0]+"' data='"+rowObject[0]+"'>"+cellvalue+"</a><div>"+rowObject[4]+"</div><div>"+rowObject[13]+"</div></span>";
		}
		return str;
	}
	
	function fa_btn(val){//즐찾여부
		$("#"+list).setGridParam({
        postData:{
        	"FA_YN":"true",
        	"FA_TYPE": "<?php echo $this->uri->segment(1); ?>",
        	"FA_USER":"<?php echo $_SESSION['userid'];?>",
        	"FA_VAL": val
        }
    }).trigger("reloadGrid");
	}
	
	function faView(t){//즐찾 보기
		if(!$(t).hasClass('tg')){
			$("#"+list).setGridParam({
	        postData:{
	        	"FA_SORT_STAR":"true",
	        	"FA_USER": "<?php echo $_SESSION['userid'];?>",
	        	"FA_TYPE": "<?php echo $this->uri->segment(1);?>"
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
	    $(t).removeClass('tg').html('<span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span> 즐겨찾기만 보기');
  	}
	}
	
	function treeView(p,etcData){//트리클릭
		if(etcData){
			$("#"+list).setGridParam({
			postData:etcData,
			page:1
			}).trigger("reloadGrid");
		}else{
			
			$("#"+list).setGridParam({
				postData:{
					"docType":null,
					"treeid":p
				},
				page:1
			}).trigger("reloadGrid");
		}
	}
	
	function selectRows(){//체크박스 선택제어
		
		
		
		var docType = $("#"+list).getGridParam("postData").docType;
		
		var ids = $("#"+list).jqGrid('getGridParam', 'selarrrow');      //체크된 row id들을 배열로 반환
		if(ids.length > 0){
			
			bootbox.confirm({
				size: "small",
				message: "총 "+ids.length+"건을 삭제하시겠습니까?<br /><code>영구삭제는 복구가 불가능합니다.</code> ",
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
							var rowObject = $("#"+list).getRowData(ids[i]);	//체크된 id의 row 데이터 정보를 Object 형태로 반환
							var value = rowObject.PF_ID;	//Obejct key값이 PF_ID value값 반환
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
								"DEL_ARR" :arr,
								"UPD_ID":"<?php echo $_SESSION['userid'];?>"
							}
						}
						$("#"+list).setGridParam({
							postData: postdata,
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
		}
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	//선택된 pf_id 들의 작성자가 로그인된 계정과 같은지 확인 ( 자신이 작성한 글만 자신이 삭제가능 (관리자제외) )
	function fn_chkInsId(ids){
		var result = false;
		var data = {
			"ids" : ids
		};
		$.ajax({
			type: 'post',
			dataType: 'json',
			url: '<?php echo base_url();?>index.php/pdm2/Main/chkInsId',
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
	
</script>
<?php include("Pop_fileMove.php"); ?>	<!-- 선택이동 팝업 -->
<input type="hidden" id="selected_id" name="selected_id" value=""/>


<div id="content_ajax"></div>
<div id="wp_right" class="lists">
	<div class="search_area p-20 gray_border_bottom">
		<form id="frm_search" method="post" onsubmit="return false;">
			<select class="form-control" id="searchField" name="searchField"  style="width:130px;display:inline-block">
				<option value="PF_NM">제목</option>
				<!--<option value="PF_FILE_EXT">종류</option>
				<option value="ins_id">아이디</option> 
				<option value="INS_ID">작성자</option> -->
			</select>

			<label for="searchtext">검색어</label>
			<input type="text" id="searchString" name="searchString" value="" class="form-control width_200px" placeholder="검색어를 입력해주세요.">
			<a class="search btn btn-<?php echo $this->config->item($this->uri->segment(1).'Color')?>">
				<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
				검색
			</a>
			<a class="searchDetail sd_y btn btn-default">
				<span class="glyphicon glyphicon-menu-down" aria-hidden="true"></span>
				상세검색
			</a>
			<a class="btn btn-default" onclick="ref()">
				<span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>
				새로고침
			</a>
			
			<div class="mt-10 searchDetails" style="line-height: 3.5">
				
				<label>문서내용</label>
				<input type='text' name="doccont" class="form-control width_200px" placeholder="ex ..." />
				
				<label>문서형식</label>
				<input type='text' name="docext" class="form-control width_200px" placeholder="ex png,jpg..." />
				
				<label>작성자</label>
				<input type='text' name="insnm" class="form-control width_200px" placeholder="ex 홍길동" />
				
				<br />
				
				<label>거래처</label>
				<input type='text' name="pcnm" class="form-control width_200px" placeholder="ex ..." />
				<label>올린기간</label>
				<div style="vertical-align:middle;width:150px;display:inline-block">
					<div class='input-group date datetimepicker sdate'>
						<input type='text' name="sdate" class="form-control"  placeholder="..." />
						<span class="input-group-addon">
							<span class="glyphicon glyphicon-calendar"></span>
						</span>
					</div>
				</div>
				~
				<div style="vertical-align:middle;width:150px;display:inline-block">
					<div class='input-group date datetimepicker edate'>
	            <input type='text' name="edate" class="form-control" placeholder="..."/>
	            <span class="input-group-addon">
	                <span class="glyphicon glyphicon-calendar"></span>
	            </span>
	        </div>
				</div>
				<label>키워드</label>
				<input class="form-control width_200px" name="keyword" placeholder="ex ..." >
			</div>
			
			
			
			
		</form>
		<button type="button" class="selectMove btn btn-default btn-sm mt-10 ">
			<span class="glyphicon glyphicon-transfer" aria-hidden="true"></span>
			선택이동
		</button>
		
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
		
		<div class="pull-right">
		<button type="button" onclick="selectTypeGrid('grid');" class="btn btn-default btn-sm mt-10 tgrid" data-toggle="tooltip" data-placement="top" title="그리드">
			<span class="glyphicon glyphicon-th" aria-hidden="true"></span>
		</button>
		
		<button type="button" onclick="selectTypeGrid('list');" class="selectTypeList btn btn-default btn-sm mt-10 tl" data-toggle="tooltip" data-placement="top" title="리스트">
			<span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
		</button>
		</div>
		<div class="pl-0 mt-10 location">
			<span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
			<div class="locas" style="display:inline">목록</div>
			<div class="pull-right gridCnt">
				로딩중...
			</div>
		</div>
	</div>
	<!--<div class="grid_area" onScroll="scrolled(this)">-->
	<div class="grid_area">
		<table id="pdm_list"  style="border-collapse: inherit;">
		</table>
		<div id="pdm_pager"></div>
	</div>
</div>		

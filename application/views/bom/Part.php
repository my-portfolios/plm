<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$PageNm = 'bom';//seg 1
$PageType = 'Part';//seg 2
?>

<script>
	//확장자 아이콘
	function extIcon(ext,sm){
			var fileObj = {
				'doc': '<i class="fa fa-file-word-o text-primary"></i>',
				'docx': '<i class="fa fa-file-word-o text-primary"></i>',
				
				'xls': '<i class="fa fa-file-excel-o text-success"></i>',
				'xlsx': '<i class="fa fa-file-excel-o text-success"></i>',
				
				'ppt': '<i class="fa fa-file-powerpoint-o text-danger"></i>',
				'pptx': '<i class="fa fa-file-powerpoint-o text-danger"></i>',
				
				'pdf': '<i class="fa fa-file-pdf-o text-danger"></i>',
				
				'zip': '<i class="fa fa-file-archive-o text-muted"></i>',
				'rar': '<i class="fa fa-file-archive-o text-muted"></i>',
				'tar': '<i class="fa fa-file-archive-o text-muted"></i>',
				'gzip': '<i class="fa fa-file-archive-o text-muted"></i>',
				'gz': '<i class="fa fa-file-archive-o text-muted"></i>',
				'7z': '<i class="fa fa-file-archive-o text-muted"></i>',
				
				'htm': '<i class="fa fa-file-code-o text-info"></i>',
				'html': '<i class="fa fa-file-code-o text-info"></i>',
				
				'txt': '<i class="fa fa-file-text-o text-info"></i>',
				'ini': '<i class="fa fa-file-text-o text-info"></i>',
				'csv': '<i class="fa fa-file-text-o text-info"></i>',
				'java': '<i class="fa fa-file-text-o text-info"></i>',
				'php': '<i class="fa fa-file-text-o text-info"></i>',
				'js': '<i class="fa fa-file-text-o text-info"></i>',
				'css': '<i class="fa fa-file-text-o text-info"></i>',
				
				'mov': '<i class="fa fa-file-movie-o text-warning"></i>',
				'avi': '<i class="fa fa-file-movie-o text-warning"></i>',
				'mpg': '<i class="fa fa-file-movie-o text-warning"></i>',
				'mkv': '<i class="fa fa-file-movie-o text-warning"></i>',
				'mov': '<i class="fa fa-file-movie-o text-warning"></i>',
				'mp4': '<i class="fa fa-file-movie-o text-warning"></i>',
				'3gp': '<i class="fa fa-file-movie-o text-warning"></i>',
				'webm': '<i class="fa fa-file-movie-o text-warning"></i>',
				'wmv': '<i class="fa fa-file-movie-o text-warning"></i>',
				
				
				'mp3': '<i class="fa fa-file-audio-o text-warning"></i>',
				'wav': '<i class="fa fa-file-audio-o text-warning"></i>',
				
				'jpg': '<i class="fa fa-file-photo-o text-danger"></i>', 
				'jpeg': '<i class="fa fa-file-photo-o text-danger"></i>',
				'gif': '<i class="fa fa-file-photo-o text-warning"></i>', 
				'png': '<i class="fa fa-file-photo-o text-primary"></i>'    
    	}
    	
    	
			var fileIcon = '<i class="fa fa-file"></i>';
			$.each(fileObj,function(i,v){
				if(i == ext){
					if(i == 'jpg' || i =='jpeg' || i == 'gif' || i == 'png'){
						if(sm == 'y'){
							fileIcon = v;
						}else{
							fileIcon = '';
						}
					}else{
						fileIcon = v;
					}
				}
			});
			return fileIcon;
		}

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
	        	"FA_TYPE": '<?php echo $PageType?>'
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
							var value = rowObject.BP_ID;     //Obejct key값이 PR_ID value값 반환
							arr.push(value);
						}
						var postdata = {
							"REMOVE_ARR" :arr
						}
						$("#"+list).setGridParam({
							postData:postdata,
							page:1
						}).trigger("reloadGrid");
						$.each($('.extIcon'),function(i,v){
							var t = $(v).text();
							$(v).html(extIcon(t,'y'));
						});
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
	
	
	
	function formatOpt1(cellvalue, options, rowObject){//즐찾 셋팅
	  	var str ='';
	 	var gap ='-';
	
		var result = $.ajax({
			type : 'GET',
			url : "<?php echo site_url()?>/<?php echo $PageNm?>/<?php echo $PageType?>/loadFileList",
			data : {"id" : rowObject[0]}, 
			async: false
    	}).responseText;
		result = JSON.parse(result);
		var fileList = '';
		if(result.rows!=null) {
			fileList=result.rows;
			//console.log(fileList);
			//console.log(fileList.length);
		}

		var html = '';

		if(fileList.length > 0) {
			for(var i=0;i<fileList.length;i++) {
				html+='<a href="<?php echo site_url()?>/pdm/Upload_view/fileDownload?tempName='+fileList[i][2]+'&fileName='+fileList[i][3]+'" title='+fileList[i][3]+'><span class="extIcon">'+fileList[i][1]+'</span></a>';
				//다뷰웹 문서뷰어
				//html+='<span class="extIcon" onclick="doOpen(\'<?php echo "http://".$_SERVER['HTTP_HOST']."/uploads/"?>'+fileList[i][2]+'\')">'+fileList[i][1]+'</span>';
			}
		}
		//console.log(html)
		if(rowObject[8] > 0){
			str += "<span class=\"test1\"><sapn data-toggle='tooltip' data-placement='right' title='즐겨찾기 취소' class='glyphicon glyphicon-star' style='color:orange;cursor:pointer;margin-right:4px' onclick=\"fa_btn('"+rowObject[0]+"')\"></span><span class='link' onclick='linkGrid(this)' data='"+rowObject[0]+"'>"+cellvalue+"</span>"+html+"<div class='mt-5'> "+gap+"</div></span>";
		}else{
			str += "<span class=\"test1\"><sapn data-toggle='tooltip' data-placement='right' title='즐겨찾기 추가' class='glyphicon glyphicon-star-empty' style='color:#ccc;cursor:pointer;margin-right:4px' onclick=\"fa_btn('"+rowObject[0]+"')\"></span><span class='link' onclick='linkGrid(this)' data='"+rowObject[0]+"'>"+cellvalue+"</span>"+html+"<div class='mt-5'> "+gap+"</div></span>";
		}
		return str;
	}

	function formatOpt2(cellvalue, options, rowObject){
	  	var html ='';
	
		var result = $.ajax({
			type : 'GET',
			url : "<?php echo site_url()?>/<?php echo $PageNm?>/<?php echo $PageType?>/loadCompList",
			data : {"id" : rowObject[0]}, 
			async: false
    	}).responseText;
		result = JSON.parse(result);
		var compList = '';
		if(result.rows!=null) {
			compList=result.rows;
			//console.log(compList);
			//console.log(compList.length);
		}

		if(compList.length > 0) {
			for(var i=0;i<compList.length;i++) {
				html+='<p>'+compList[i][2]+'</p>';
			}
		}
		//console.log(html);
		return html;
	}
	
	
	function fa_btn(val){//즐찾여부
		$("#"+list).setGridParam({
			postData:{
				"FA_YN":"true",
				"FA_TYPE":"<?php echo $PageType?>",
				"FA_USER":"<?php echo $_SESSION['userid'];?>",
				"FA_VAL": val
			}
		}).trigger("reloadGrid");

		$.each($('.extIcon'),function(i,v){
			var t = $(v).text();
			$(v).html(extIcon(t,'y'));
		});
	}

	function excel_upload(){
		$('#excel_upload').modal('show');
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

		$.each($('.extIcon'),function(i,v){
				var t = $(v).text();
				$(v).html(extIcon(t,'y'));
		});
	}

	// 다뷰어
	var daviewWebURL = "http://davu.kr/FileUpload?filepath="; 
      function doOpen(openDocURL) { 
         var url = daviewWebURL + openDocURL; 
         //alert(url); 
         window.open(url); 
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
      url:'<?php echo site_url()?>'+'/<?php echo $PageNm?>/<?php echo $PageType?>/loadData',      
      mtype : "POST",             
      datatype: "json",            
      colNames:['BP_ID','부품명','규격','재질','거래처','내용','작성자','작성일'],       
      colModel:[
          {name:'BP_ID',index:'BP_ID', width:100, align:"center", hidden:true},
          {name:'BP_NM',index:'BP_NM', width:300, align:"left",formatter:formatOpt1},
          {name:'BP_STD',index:'BP_STD', width:150, align:"center"},
		  {name:'BP_MTR',index:'BP_MTR', width:100, align:"center"},
		  {name:'BP_ASC',index:'BP_ASC', width:100, align:"center",formatter:formatOpt2},
          {name:'BP_CONT',index:'BP_CONT', width:100, align:"center", hidden:true},
          {name:'INS_ID',index:'INS_ID', width:100, align:"center"},
          {name:'INS_DT',index:'INS_DT', width:100, align:"center", formatter:"date", formatoptions:{newformat:"Y-m-d"}}
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

	$.each($('.extIcon'),function(i,v){
			var t = $(v).text();
			$(v).html(extIcon(t,'y'));
		});
	});




</script>
<?php include($_SERVER["DOCUMENT_ROOT"]."/application/views/com/Excel_upload.php"); ?>	<!-- 엑셀업로드 팝업 -->
<div id="wp_right">
	<div class="search_area p-20 gray_border_bottom">
		<form id="frm_search" name="frm_search" method="post" onsubmit="return false;">
			<input type="hidden" id="searchOper" name="searchOper" value="cn" />
			<input type="hidden" id="_search1" name="_search1" value="true" />
			<select class="form-control width_100px" id="searchField" name="searchField">
				<option value="BP_NM">부품명</option>
				<option value="BP_STD">규격</option>
				<option value="BP_MTR">재질</option>
				<option value="BP_CONT">내용</option>
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
			
			<button type="button" class="btn btn-default btn-sm mt-10" onclick="faView(this);">
				<span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span>
				즐겨찾기만 보기
			</button>

			<button type="button" class="btn btn-default btn-sm mt-10" onclick="excel_upload();">
				<span class="glyphicon glyphicon-upload" aria-hidden="true"></span>
				엑셀일괄 등록
			</button>
			
		</div>
		</form>
		<div class="location mt-10 pl-0">
			<span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
				<span class="locas">부품 정보 목록</span>
			<div class="pull-right gridCnt">
				로딩중...
			</div>
		</div>
		
	</div>
	<script>
			$("#PF_FILE").fileinput({
			    //uploadUrl: "/file-upload-batch/2",
			    //uploadAsync: true,
			    language : "kr",
			    previewFileIcon: '<i class="fa fa-file"></i>',
			    allowedPreviewTypes: null, // set to empty, null or false to disable preview for all types
			    previewFileIconSettings: { // configure your icon file extensions
		        'doc': '<i class="fa fa-file-word-o text-primary"></i>',
		        'xls': '<i class="fa fa-file-excel-o text-success"></i>',
		        'ppt': '<i class="fa fa-file-powerpoint-o text-danger"></i>',
		        'pdf': '<i class="fa fa-file-pdf-o text-danger"></i>',
		        'zip': '<i class="fa fa-file-archive-o text-muted"></i>',
		        'htm': '<i class="fa fa-file-code-o text-info"></i>',
		        'txt': '<i class="fa fa-file-text-o text-info"></i>',
		        'mov': '<i class="fa fa-file-movie-o text-warning"></i>',
		        'mp3': '<i class="fa fa-file-audio-o text-warning"></i>',
		        // note for these file types below no extension determination logic 
		        // has been configured (the keys itself will be used as extensions)
		        'jpg': '<i class="fa fa-file-photo-o text-danger"></i>', 
		        'gif': '<i class="fa fa-file-photo-o text-warning"></i>', 
		        'png': '<i class="fa fa-file-photo-o text-primary"></i>'    
		    },
		    previewFileExtSettings: { // configure the logic for determining icon file extensions
	        'doc': function(ext) {
	            return ext.match(/(doc|docx)$/i);
	        },
	        'xls': function(ext) {
	            return ext.match(/(xls|xlsx)$/i);
	        },
	        'ppt': function(ext) {
	            return ext.match(/(ppt|pptx)$/i);
	        },
	        'zip': function(ext) {
	            return ext.match(/(zip|rar|tar|gzip|gz|7z)$/i);
	        },
	        'htm': function(ext) {
	            return ext.match(/(htm|html)$/i);
	        },
	        'txt': function(ext) {
	            return ext.match(/(txt|ini|csv|java|php|js|css)$/i);
	        },
	        'mov': function(ext) {
	            return ext.match(/(avi|mpg|mkv|mov|mp4|3gp|webm|wmv)$/i);
	        },
	        'mp3': function(ext) {
	            return ext.match(/(mp3|wav)$/i);
	        },
	    }
			});
	</script>
	<div class="grid_area">
		<table id="<?php echo $PageType?>_list" style="border-collapse: inherit;">
		</table>
		<div id="<?php echo $PageType?>_pager"></div>
	</div>
</div>		

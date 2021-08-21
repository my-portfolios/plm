<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
if(!isset($_COOKIE['type'])){
	setcookie("type", 'list', time()+3600, "/");
}
*/
?>
<script>
	
	function searchData(){
		//검색정보 cookie 저장
			$.cookie('local','RM',{ path: '/' });
		var searchSe = $('#frm_search').serialize();
		var seArr = searchSe.split("&");
		$.each(seArr,function(i,v){
			var seInArr = v.split("=");
			$.cookie(seInArr[0],seInArr[1],{ path: '/' });
		});
	}
	function setSearchData(){
		if($.cookie('detailYn') == 'Y'){
			if($('.searchDetails').css('display') == "none"){
				$('.searchDetail').click();
				$('.searchFile').click();
			}
		}else{
			if($.cookie('local') == 'RM'){
				$('.searchFile').click();
			}
		};
	}
	
	$(function(){
		
		$(window).load(function(){
			setSearchData();
		});
		
		/*gridtype(beta)*/
		$(document).on('click','.selectTypeGrid',function(){
			$.cookie('type','grid',{ path: '/' });
			location.href="<?php echo base_url();?>";
		});
		
		/*gridtype(beta)*/
		$(document).on('click','.selectTypeList',function(){
			$.cookie('type','list',{ path: '/' });
			location.href="<?php echo base_url();?>";
		});
		
		/*상세검색*/
		$(document).on('click','[role=sd_y]',function(){
			//$('.list-group-c .s_y').css('background','#fff');
			$("[name=detailYn]").val('Y');
			$('.searchDetails').show();
			$(this).attr('role','sd_n');
			$('.search_area').height(180);
			$(window).trigger('resize');
			$(this).find('span').removeClass('glyphicon-menu-down').addClass('glyphicon-menu-up');
			$('.locas').text('검색');
			$.cookie('detailYn','Y',{ path: '/' });
		});
		/**/
		$(document).on('click','[role=sd_n]',function(){
			//$('.list-group-c .s_y').css('background','#fff');
			$("[name=detailYn]").val('N');
			$('.searchDetails').hide();
			$(this).attr('role','sd_y');
			$('.search_area').innerHeight(142);
			$(window).trigger('resize');
			$(this).find('span').removeClass('glyphicon-menu-up').addClass('glyphicon-menu-down');
			$('.locas').text('검색');
			
			$('[name=docType] option').eq(0).prop('selected',true);
			$('#searchtext').val('');
			$('[name=sdate]').val('');
			$('[name=edate]').val('');
			$('[name=keyword]').val('');
			$.cookie('detailYn','N',{ path: '/' });
		});
		/* 검색 엔터 */
		$('#frm_search').find('#searchtext').keydown(function (key) {
			searchData();//검색데이타저장
			//$('.list-group-c .s_y').css('background','#fff');
			if(key.keyCode == 13){//키가 13이면 실행 (엔터는 13)
				if($('#frm_search').find('#searchtext').val() != '' || $('[name=detailYn]').val() == 'Y'){
	 				
	 				$.cookie('se','0',{ path: '/' });
					fn_search(false);
					$('.locas').text('검색');
					
				}else{
					bootbox.alert({
						size:'small',
						message : '검색어가 없습니다.'
					});
				}
			}
		});
		
		/* 검색 */
		$(document).on('click','.searchFile',function(){
			searchData();//검색데이타저장
			//$('.list-group-c .s_y').css('background','#fff');
			if($('#frm_search').find('#searchtext').val() != '' || $('[name=detailYn]').val() == 'Y'){
				
				$.cookie('se','0',{ path: '/' });
				fn_search(false);
				$('.locas').text('검색');
			}else{
				bootbox.alert({
					size:'small',
					message : '검색어가 없습니다.'
				});
			}
		});
		
		//전체선택
		$(document).on('click','.allchk',function(){
			
			if($(this).prop("checked")) {
				$("#listgrid").find('input[type=checkbox]').attr('checked','checked');
			}else{
				$("#listgrid").find('input[type=checkbox]').removeAttr('checked');
			}
			
		});
		
		//선택이동
		$(document).on('click','.selectMove',function(){
			
			var chks = $('.tbl_main tbody').find('input[type=checkbox]:checked');
			
			if(chks.length < 1){
				bootbox.alert({
					size:'small',
					message : '선택된 항목이 없습니다.'
				});
				return preventDefaultAction(false);
			}
			
			var chkArr = [];
			/* 이동시키기로 선택한 폴더들 배열에 담음 */
			$.each(chks,function(i,v){
				chkArr.push(v.value);
			});
			
			$('#pop_fileMove').modal('show');
			fn_getFileMoveTree(chkArr);
			
		});
		
		/* 선택삭제 */
		$(document).on('click','.selectDel',function(){
			
			var chks = $('.tbl_main tbody').find('input[type=checkbox]:checked');
			
			if(chks.length < 1){
				bootbox.alert({
					size:'small',
					message : '선택된 항목이 없습니다.'
				});
				return preventDefaultAction(false);
			}
			
			var chkArr = [];
			/* 이동시키기로 선택한 폴더들 배열에 담음 */
			$.each(chks,function(i,v){
				chkArr.push(v.value);
			});
			
			bootbox.confirm({
				size: "small",
				message: "삭제하시겠습니까?", 
				callback: function(result){
			
					if(result == true){
						fn_delete(chkArr);
					}
				}
			});
			
		});
		
		/* 선택복원 */
		$(document).on('click','.selectBokwon',function(){
			
			var chks = $('.tbl_main tbody').find('input[type=checkbox]:checked');
			
			if(chks.length < 1){
				bootbox.alert({
					size:'small',
					message : '선택된 항목이 없습니다.'
				});
				return preventDefaultAction(false);
			}
			
			var chkArr = [];
			/* 복원시키기로 선택한 폴더들 배열에 담음 */
			$.each(chks,function(i,v){
				chkArr.push(v.value);
			});
			
			bootbox.confirm({
				size: "small",
				message: "복원하시겠습니까?", 
				callback: function(result){
			
					if(result == true){
						fn_bokwon(chkArr);
					}
				}
			});
			
		});
		
	});
	
	function scrolled(o)
	{
		//visible height + pixel scrolled = total height 
		if(o.offsetHeight + o.scrollTop == o.scrollHeight)
		{
			
			$('#loading').modal('show');
			setTimeout(function(){
				//console.log('scroll');
				//addList();
				
				$.cookie('limit',parseInt($.cookie('limit')) + 20,{ path: '/' });
				var searchText = $('#frm_search').find('#searchtext').val();
				if(searchText != ''){
					if($.cookie('se') == '0'){
						fn_search(true);
					}else if($.cookie('se') == '2'){
						fn_search(true);	
					}else{
						fn_getChildren($.cookie('local'),true);
					}
				}else{
					if($.cookie('se') == '0'){
						$('#loading').modal('hide');
						if($("[name=detailYn]").val() == 'Y'){
							fn_search(true);	
						}else{
							bootbox.alert({
								size:'small',
								message : '이미 검색된 영역입니다.<br /> 검색어가 존재 하지 않습니다.<br />검색어 입력 또는 폴더를 선택해 주세요.'
							});
						}
					}else if($.cookie('se') == '2'){
						fn_search(true);
					}else{
						fn_getChildren($.cookie('local'),true);
					}
				}
				
			},100);
		}
	}
	
	
	function moreItem(){
		$('#loading').modal('show');
			setTimeout(function(){
				//console.log('scroll');
				//addList();
				
				$.cookie('limit',parseInt($.cookie('limit')) + 20,{ path: '/' });
				var searchText = $('#frm_search').find('#searchtext').val();
				if(searchText != ''){
					if($.cookie('se') == '0'){
						fn_search(true);
					}else if($.cookie('se') == '2'){
						fn_search(true);	
					}else{
						fn_getChildren($.cookie('local'),true);
					}
				}else{
					if($.cookie('se') == '0'){
						$('#loading').modal('hide');
						if($("[name=detailYn]").val() == 'Y'){
							fn_search(true);	
						}else{
							bootbox.alert({
								size:'small',
								message : '이미 검색된 영역입니다.<br /> 검색어가 존재 하지 않습니다.<br />검색어 입력 또는 폴더를 선택해 주세요.'
							});
						}
					}else if($.cookie('se') == '2'){
						fn_search(true);
					}else{
						fn_getChildren($.cookie('local'),true);
					}
				}
				
			},100);
	}
	
	/* 검색 */
	function fn_search(scroll){
		
		if(scroll!=true){
			$.cookie('limit',0,{ path: '/' });
		}
		
		var searchType = $('#frm_search').find('#searchType').val();
		var searchText = $('#frm_search').find('#searchtext').val();
		var setype = $('#frm_search').find('#setype').is(':checked');
		var docType = $('[name=docType] option:selected').val();
		var detailYn = $('[name=detailYn]').val();
		var sdate = $('[name=sdate]').val();
		var edate = $('[name=edate]').val();
		var keyword = $('[name=keyword]').val();
		var data = {
			 "searchType"	: searchType
			,"searchText"	: searchText
			,"setype"	: setype
			,"docType"	: docType
			,"detailYn"	: detailYn
			,"sdate"	: sdate
			,"edate"	: edate
			,"keyword"	: keyword
			,"setype_local"	: $.cookie('local')
			,"limit"	: $.cookie('limit')
		};
		$.ajax({
			type: 'post',
			dataType: 'json',
			url: '<?php echo base_url();?>index.php/pdm/Main/search',
			data: data,
			success: function (data) {
				$('#loading').modal('hide');
				fn_setList(data,scroll);
			},
			error: function (request, status, error) {
				console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
			}
		});
		
	}
	
	/* 선택삭제 */
	function fn_delete(chkArr){
		
		var url = '';
		
		if($('.selectDel').text() == '영구삭제'){
			url = '<?php echo base_url();?>index.php/pdm/Main/remove';
		}else{
			url = '<?php echo base_url();?>index.php/pdm/Main/delete';
		}
		
		var data = {
			"chkArr": chkArr.toString()
		};
		
		$.ajax({
			type: 'post',
			dataType: 'json',
			url: url,
			data: data,
			success: function (data) {
				//$('#jstree-tree-left').jstree(true).refresh();
				console.log(data);
				bootbox.alert({
					size:'small',
					message : '삭제되었습니다.',
					buttons: {
						ok: {
							label: '확인',
							className: "btn-<?php echo $this->config->item($this->uri->segment(1).'Color')?>"
						}
					}
				});
				$.each(chkArr,function(i,v){
					$('input[value='+v+']').parent().parent().remove();
				});
			},
			error: function (request, status, error) {
				console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
			}
		});
		
	}
	
	/* 선택복원 */
	function fn_bokwon(chkArr){
		
		var data = {
			"chkArr": chkArr.toString()
		};
		
		$.ajax({
			type: 'post',
			dataType: 'json',
			url: '<?php echo base_url();?>index.php/pdm/Main/bokwon',
			data: data,
			success: function (data) {
				//$('#jstree-tree-left').jstree(true).refresh();
				bootbox.alert({
					size:'small',
					message : '복원되었습니다.',
					buttons: {
						ok: {
							label: '확인',
							className: "btn-<?php echo $this->config->item($this->uri->segment(1).'Color')?>"
						}
					}
				});
				$.each(chkArr,function(i,v){
					$('input[value='+v+']').parent().parent().remove();
				});
			},
			error: function (request, status, error) {
				console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
			}
		});
		
	}
	
	/* 리스트불르오기 */
	function fn_getChildren( id, scroll ){
		if(scroll != true){
			$.cookie('limit',0,{ path: '/' });
		}
		$('.allchk').removeAttr('checked');
	
			var data = {
				"parent_id": id
				,"limit"	: $.cookie('limit')
			};
			
			$.ajax({
				type: 'post',
				dataType: 'json',
				url: '<?php echo base_url();?>index.php/pdm/Main/getChildren',
				data: data,
				success: function (data) {
					//console.log(data);
					$('#loading').modal('hide');
					fn_setList(data,scroll);
				},
				error: function (request, status, error) {
					console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
				}
			});
	}
	
	/* 버튼세팅변경 */
	function fn_btnSetting(){
		
		if($.cookie('docType') == 'TRASH'){	//휴지통
			$('.selectMove').prop('disabled',true);
			$('.selectDel').html('<span class="glyphicon glyphicon-floppy-remove" aria-hidden="true"></span>영구삭제');
			$('.selectBokwon').css('display','');
		}else{
			$('.selectMove').prop('disabled',false);
			$('.selectDel').html('<span class="glyphicon glyphicon-floppy-remove" aria-hidden="true"></span>선택삭제');
			$('.selectBokwon').css('display','none');
		}
		
	}
	
	/* 리스트 뿌리기 */
	function fn_setList(data,scroll){
		
		fn_btnSetting();	//버튼세팅변경
		
		if(scroll != true){
			$('.tbl_main tbody tr').remove();
		}
		
		$.each(data,function(i,v){
			
			var pf_id			= data[i].PF_ID;
			var pfd_id			= data[i].PFD_ID;
			var pf_nm			= data[i].PF_NM;
			var keyword			= data[i].KEYWORD;
			var pf_cont			= data[i].PF_CONT; 		
			var pf_path			= data[i].PF_PATH;
			var pf_file_real_nm	= data[i].PF_FILE_REAL_NM;			
			var pf_file_temp_nm	= data[i].PF_FILE_TEMP_NM;		
			var pf_file_size	= data[i].PF_FILE_SIZE;	
			var pf_file_ext		= data[i].PF_FILE_EXT;	
			var ins_nm			= data[i].INS_NM;
			var ins_id			= data[i].INS_ID;	
			var ins_dt			= data[i].INS_DT;			
			var udt_dt = data[i].UPD_DT
			var udt_nm = data[i].UPD_NM
			var html = "";
			
			var keywordHtml = '';
			
			if(keyword){
				var keywordArr = keyword.split(",");  
				if(keywordArr.length > 0){
					keywordHtml += "<div style='margin-top:8px;'>";
					$.each(keywordArr,function(i,v){
						if(i == 0){
							var se = '';
						}else{
							var se = ', ';
						}
						keywordHtml += se+'#'+v+'&nbsp;';
					});
					keywordHtml += "</div>";
				}
			}
			
			html += "<tr>";
			
			if(pf_file_ext == 'jpg' || pf_file_ext =='jpeg' || pf_file_ext == 'gif' || pf_file_ext == 'png'){
				var imgUrl = "<img style='border:1px solid #ededed; vertical-align:middle;margin-top:-4px' width='34' height='40' src='<?php echo site_url()?>/pdm/Upload_view/fileDownload?tempName="+pf_file_temp_nm+"&fileName="+pf_file_real_nm+"' />";
			}else{
				var imgUrl = '';
			}
			
			var udt = '', udtNm = "";
			if(udt_dt != null){
				var udt = "<span style='font-size:10px;color:#666'>(수정:"+udt_dt+")</span>";
				var udtNm = "<span style='font-size:10px;color:#666'>(수정:"+udt_nm+")</span>";
			}
			
			html += "<td style='vertical-align: middle;' class='text-center'><input type='checkbox' value='"+pf_id+"'/></td>";
			//html += "<td class='text-center'>"+pf_path+"</td>";//분류
			html += "<td style='vertical-align: middle;' class='text-left'><span class='listIconType'>"+imgUrl+extIcon(pf_file_ext)+"<p style='font-size:10px;margin-top:-6px;white-space: nowrap; overflow: hidden; text-overflow: ellipsis;'>"+pf_file_ext+"</p></span><a class='listLinkType' onclick=fn_local('"+pfd_id+"',this) url='<?php echo site_url()?>/pdm/Upload_view?id="+pf_id+"'>"+pf_nm+"</a><div class='listPath'>"+pf_path+"</div><div class='listKeyword'>"+keywordHtml+"</div></td>";
			html += "<td style='vertical-align: middle;' class='text-center'><a href='<?php echo site_url()?>/pdm/Upload_view/fileDownload?tempName="+pf_file_temp_nm+"&fileName="+pf_file_real_nm+"'>"+pf_file_ext+"</a></td>";
			html += "<td style='vertical-align: middle;' class='text-center'>"+pf_file_size+" kb</td>";
			html += "<td style='vertical-align: middle;' class='text-center'>"+ins_nm+"<br />"+udtNm+"</td>";
			html += "<td style='vertical-align: middle;' class='text-center'>"+ins_dt+"<br />"+udt+"</td>";
			html += "</tr>";
			
			var cnt = $('#listgrid').find('tbody tr').size() + 1;
			$('.dataCnt').html( "전체 :<strong>"+cnt + "</strong> 건" );
			$('.tbl_main tbody').append(html);
			
		});
		
		if(data.length == 0 || data.length < 20){
			$('.noDataTr').remove();
			html = "<tr class='noDataTr'>";
			html += "<td colspan='6' class='ptb-20 text-center'>더이상 데이타가 없습니다.</td>";	/* 없음 */
			html += "</tr>";
			$('.tbl_main tbody').append(html);
			$('.moreItem').hide();
		}else{
			$('.moreItem').show();
		}
		
		
	}
	
	function fn_local(p,t){
		//$.cookie('local',p,{ path: '/' });
		location.href=$(t).attr('url');
	}
	
</script>
<?php include("Pop_fileMove.php"); ?>	<!-- 선택이동 팝업 -->
<input type="hidden" id="selected_id" name="selected_id" value=""/>
<div id="wp_right">
	<div class="search_area p-20 gray_border_bottom">
		<form id="frm_search" method="post" onsubmit="return false;">
			<input name="detailYn" type="hidden" value="N">
			<select class="form-control" id="searchType" name="searchType"  style="width:130px;display:inline-block">
				<option value="file_nm">제목</option>
				<option value="path">분류</option>
				<option value="file_ext">종류</option>
				<!--<option value="ins_id">아이디</option> -->
				<option value="ins_nm">작성자</option> 
			</select>

			<label for="searchtext">검색어</label>
			<input type="text" id="searchtext" name="searchtext" value="<?php echo $this->input->cookie('searchtext');?>" class="form-control width_200px" placeholder="검색어를 입력해주세요.">
			<a class="searchFile btn btn-<?php echo $this->config->item($this->uri->segment(1).'Color')?>">
				<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
				검색
			</a>
			<a class="searchDetail btn btn-default" role='sd_y'>
				<span class="glyphicon glyphicon-menu-down" aria-hidden="true"></span>
				상세검색
			</a>
			<!--
			&nbsp;&nbsp;
			<input type="checkbox" id="setype" name="setype">
			<label for="setype" style="display:inline-block;margin-top:6px">현재 경로에서 검색</label>
			-->
			<div class="mt-10 searchDetails">
				<label>문서형식</label>
				<select name="docType" class="form-control width_200px">
					<option value="A" <?php if($this->input->cookie('docType') == 'A') {echo 'selected';}?>>전체</option>
					<option value="M" <?php if($this->input->cookie('docType') == 'M') {echo 'selected';}?>>공유받은파일</option>
					<option value="IMAGE" <?php if($this->input->cookie('docType') == 'IMAGE') {echo 'selected';}?>>이미지</option>
					<option value="MOVIE" <?php if($this->input->cookie('docType') == 'MOVIE') {echo 'selected';}?>>동영상</option>
					<option value="TXT"<?php if($this->input->cookie('docType') == 'TXT') {echo 'selected';}?>>문서</option>
					<option value="RM" <?php if($this->input->cookie('docType') == 'RM') {echo 'selected';}?>>요구사항관리</option>
					<option value="TRASH" <?php if($this->input->cookie('docType') == 'TRASH') {echo 'selected';}?>>휴지통</option>
				</select>
				<label>올린기간</label>
				<div style="vertical-align:middle;width:150px;display:inline-block">
					<div class='input-group date datetimepicker sdate'>
						<input type='text' name="sdate" class="form-control" value="<?php echo $this->input->cookie('sdate');?>" />
						<span class="input-group-addon">
							<span class="glyphicon glyphicon-calendar"></span>
						</span>
					</div>
				</div>
				~
				<div style="vertical-align:middle;width:150px;display:inline-block">
					<div class='input-group date datetimepicker edate'>
	            <input type='text' name="edate" class="form-control" value="<?php echo $this->input->cookie('edate');?>" />
	            <span class="input-group-addon">
	                <span class="glyphicon glyphicon-calendar"></span>
	            </span>
	        </div>
				</div>
				<label>키워드</label>
				<input class="form-control width_200px" name="keyword" value="<?php echo $this->input->cookie('keyword');?>" >
			</div>
		</form>
		<button type="button" class="selectMove btn btn-default btn-sm mt-10 ">
			<span class="glyphicon glyphicon-transfer" aria-hidden="true"></span>
			선택이동
		</button>
		<button type="button" class="selectDel btn btn-default btn-sm mt-10">
			<span class="glyphicon glyphicon-floppy-remove" aria-hidden="true"></span>
			선택삭제
		</button>
		<button type="button" class="selectBokwon btn btn-default btn-sm mt-10">
			<span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>
			선택복원
		</button>
		<div class="pull-right">
		<button type="button" class="selectTypeGrid btn btn-default btn-sm mt-10" data-toggle="tooltip" data-placement="top" title="그리드">
			<span class="glyphicon glyphicon-th" aria-hidden="true"></span>
		</button>
		
		<button type="button" class="selectTypeList btn btn-default btn-sm mt-10" data-toggle="tooltip" data-placement="top" title="리스트">
			<span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
		</button>
		</div>
		<div class="pl-0 mt-10 location">
			<span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
			<div class="locas" style="display:inline"></div>
			<div class="pull-right dataCnt"></div>
		</div>
	</div>
	<!--<div class="grid_area" onScroll="scrolled(this)">-->
	<div class="grid_area">
		<table class="tbl_main table table-hover" id="listgrid">
			<colgroup>
				<col style="width:5%" />
				<!--<col style="width:10%" />-->
				<col />
				<col style="width:10%" />
				<col style="width:10%" />
				<col style="width:10%" />
				<col style="width:13%" />
			</colgroup>
			<thead>
				<tr>
					<th><input class="allchk" type="checkbox" /></th>
					<!--<th onclick="sortTable(1,'listgrid')">분류</th>-->
					<th onclick="sortTable(1,'listgrid')">제목(파일명)</th>
					<th onclick="sortTable(2,'listgrid')">종류</th>
					<th onclick="sortTable(3,'listgrid')">용량</th>
					<th onclick="sortTable(4,'listgrid')">작성자</th>
					<th onclick="sortTable(5,'listgrid')">작성일</th>
				</tr>
			</thead>
			<tbody>
				
			</tbody>
		</table>
		<div class="text-center pb-20 moreItem">
			<div class="text-center btn btn-default" onclick="moreItem()">+20 더보기&nbsp;&nbsp;<span style="top:3px" class="glyphicon glyphicon-option-horizontal"></span></div>
		</div>
	</div>
</div>		

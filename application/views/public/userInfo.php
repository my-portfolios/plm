<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php if(isset($_COOKIE['wp_left']) != ''){?>
	<!--left right-->
	<style>
		#wp_left{
			width: <?php echo $_COOKIE['wp_left']?>px;
		}
		#wp_right{
			left: <?php echo $_COOKIE['wp_left']?>px;
		}
	</style>
	
<?php } ?>


<div class="leftProWp">
	<div class="leftPro gray_border_bottom text-center p-20" <?php if(isset($popupYn) && isset($popupYn) == 'Y'){ ?> style="display:none" <?php } ?>>
		<div class="leftPic">
			<!--프로필이미지가 없을경우-->
			<div class="my_pic">
			</div>
		</div>
		<ul>
			<li><?php echo $this->session->userdata('username');?></li>
			<li><?php echo $this->session->userdata('userid');?></li>
			<li title="<?php echo $this->session->userdata('compnm');?>">
				<?php 
					echo $this->session->userdata('compnm');
				?>
			</li>
			<li class="mt-10">
				<button type="button" class="btn btn-default btn-xs logout">
					<span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>
					로그아웃
				</button>
				<!-- msgView('admin') -->
				<button type="button" class="btn btn-default btn-xs" onclick="msgList()">
					<span class="glyphicon glyphicon-envelope newBns" aria-hidden="true"></span>
					<em class="msg_cnt_now">loading...</em>
				</button>
				<!--
				<button type="button" class="btn btn-default btn-xs modifyinfo">
					<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
					정보수정
				</button>
				-->
			</li>
		</ul>
	</div>
	<script>
		
		$(window).load(function(){
			//leftSroll
			$('#wp_left').scroll(function(){
				var height = ($("#wp_left").scrollTop());
				$('.ui-resizable-e').css("top",height+'px');
			});
			
			
			//leftSize
			$('#wp_left').resizable({
			    handles: 'e',
			    minWidth: 250,
			    maxWidth: 500,
			    resize: function( event, ui ) {
				    	
			        $("#wp_right").css({
									'left':ui.size.width+'px'
							});
							$.cookie('wp_left',ui.size.width,{path: '/'});
							$(window).trigger('resize');
				  }
			});
			
			$( document ).ajaxComplete(function(event,jqxhr,settings) {
				if($.cookie('wp_left')){
					$("#wp_right").css({
						'left':$.cookie('wp_left')+'px'
					});
				}
			});
		});
		
		$(window).load(function(){
			//top menu html을 그냥 가져옴
			var top_m = $('.top_menu').html();
			$('.leftUserMenu').append(top_m);
			$('.leftUserMenu').find('.tooltip').remove();//활성된 툴팁있으면 제거 
			$('[data-toggle="tooltip"]').tooltip();
		});
		
		$(function(){
		
			//tab
			$(document).on('click','.msg_tab2',function(){
				$("#umsgList").modal('hide');
				$("#mList").jqGrid('GridUnload');
				$("#umsgListSend").modal('show');
				msgListSend();
			});
			$(document).on('click','.msg_tab1',function(){
				$("#umsgListSend").modal('hide');
				$("#mListSend").jqGrid('GridUnload');
				$("#umsgList").modal('show');
				msgList();
			});
			//새로운 메세지 interval
			noViewCnt();
			var msgCnt;
			msgCnt = setInterval(function(){ 
				noViewCnt();
			}, 10000);
			
			//이미지 넣기
			getPic("<?php echo $this->session->userdata('userid');?>",'.my_pic');
			
			//메세지 보내기 버튼
			$(document).on('click','.msg_send',function(){
				if($('.msg').val() == ''){
					bootbox.alert({
						size:'small',
						message : '내용이 없습니다.',
					  buttons: {
							ok: {
								label: '확인',
								className: "btn-<?php echo $this->config->item($this->uri->segment(1).'Color')?>"
							}
						}
					});
				}else{
					msgPush( $('#umsgPush').find('.modal-title > span').text() );
				}
			});
		});
		
		function skinChange(v){
			$.ajax({
				type: 'post',
				dataType: 'json',
				url: '<?php echo base_url();?>index.php/Common/skinChange',
				data : {skin:v},
				success: function (data) {
					bootbox.alert({
						size:'small',
						message : '재로그인 하시면 스킨이 변경됩니다.',
					  buttons: {
							ok: {
								label: '확인',
								className: "btn-<?php echo $this->config->item($this->uri->segment(1).'Color')?>"
							}
						}
					});
				},
				error: function (request, status, error) {
					console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
				}
			});
		}
		
		function msgView(id){
			$('#umsgPush').on('show.bs.modal', function (event) {
			  $('#umsgPush').find('.modal-title > span').text(id);
			});
			$("#umsgPush").modal('show');
		}
		function infoView(id){
			var nowId = "<?php echo $_SESSION['userid']; ?>";
			var $skinHtml = '';
			var $logouts = '';
			if(nowId == id){
				$skinHtml += '<div class="pt-20 cput_skin">';
		    $skinHtml += '<label>스킨선택</label>';
				$skinHtml += '<select class="form-control" onchange="skinChange(this.value)">';
		    $skinHtml += '<option value="Def"  <?php if($this->session->userdata("userskin") == "Def"){ echo "selected";} ?>>기본</option>';
				$skinHtml += '<option value="Dark" <?php if($this->session->userdata("userskin") == "Dark"){ echo "selected";} ?>>Dark</option>';
		    $skinHtml += '</select>';
		    $skinHtml += '</div>';
		    //$logouts = '&nbsp;&nbsp;<button type="button" class="btn btn-default btn-xs logout">로그아웃</button>';
			}
			$.ajax({
				type: 'post',
				dataType: 'json',
				url: '<?php echo base_url();?>index.php/Common/infoView',
				data : {id:id},
				success: function (data) {
					$('.userViewPic').html('');
					$('.userViewInfos').html('');
					getPic(data[0].PE_ID,'.userViewPic');
					$('.userViewInfos').append('<div title="'+data[0].PE_TEL+'" style="white-space: nowrap;width: 147px;overflow: hidden;text-overflow: ellipsis;"><b>아이디 : </b>'+data[0].PE_ID+'</div>');
					$('.userViewInfos').append('<div title="'+data[0].PE_NM+'" style="white-space: nowrap;width: 147px;overflow: hidden;text-overflow: ellipsis;"><b>이름 : </b>'+data[0].PE_NM+$logouts+'</div>');
					$('.userViewInfos').append('<div title="'+data[0].PE_TEL+'" style="white-space: nowrap;width: 147px;overflow: hidden;text-overflow: ellipsis;"><b>연락처 : </b>'+data[0].PE_TEL+'</div>');
					$('.cput').find('.cput_skin').remove();
					$('.cput').append($skinHtml);
					$("#infoView").modal('show');
				},
				error: function (request, status, error) {
					console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
				}
			});
			
		};
		var cntOn = 0;
		function noViewCnt(){
			$.ajax({
				type: 'post',
				dataType: 'json',
				url: '<?php echo base_url();?>index.php/Common/msgViewYns',
				success: function (data) {
					if(parseInt(data) > parseInt($('.msg_cnt_now').text())){
							$('#newMsg').find('.msgText').html('새로운 <a href="#none" onclick=msgList() class="link"  data-dismiss="modal">쪽지가</a> 도착했습니다.');
							$('#newMsg').modal('show');
					};
					if(cntOn == 0){
						if(parseInt(data) > 0){
								$('#newMsg').find('.msgText').html('확인하지 않은 쪽지가 <a href="#none" onclick=msgList() class="link"  data-dismiss="modal">'+data+'건</a> 있습니다.');
								$('#newMsg').modal('show');
						}
						cntOn++;
					}
					$('.msg_cnt_now').text(data);
				},
				error: function (request, status, error) {
					console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
				}
			});
		}
		
		function msgPush(id){
			$.ajax({
				type: 'post',
				dataType: 'json',
				url: '<?php echo base_url();?>index.php/Common/msgPush',
				data: {
					R_ID : id,
					S_ID : "<?php echo $this->session->userdata('userid');?>",
					MSG : $('.msg').val()
				},
				success: function (data) {
					console.log(data);
					if(data){
						bootbox.alert({
							size:'small',
							message : '메세지 전송이 완료되었습니다.',
						  buttons: {
								ok: {
									label: '확인',
									className: "btn-<?php echo $this->config->item($this->uri->segment(1).'Color')?>"
								}
							}
						});
						$("#umsgPush").modal('hide');
						$("#umsgPush .msg").val('');
					}else{
						bootbox.alert({
							size:'small',
							message : '없는 사용자 이거나, 잘못된 사용자 입니다.',
						  buttons: {
								ok: {
									label: '확인',
									className: "btn-<?php echo $this->config->item($this->uri->segment(1).'Color')?>"
								}
							}
						});
						$("#umsgPush").modal('hide');
						$("#umsgPush .msg").val('');
					}
				},
				error: function (request, status, error) {
					console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
				}
			});
		}
		function textareas(cellvalue, options, rowObject){
			return '<textarea class="form-control" style="width:100%;height:100px" readonly>'+cellvalue+'</textarea>';
		}
		function sendid(cellvalue, options, rowObject){
			var $emps = '';
			  	$emps += ' <span class="nav-item dropdown">';
		      $emps += '<a class="nav-link dropdown-toggle btn btn-default btn-xs" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
		      $emps += '<span title="'+cellvalue+'('+rowObject[6]+')" class="msgIdArea">'+cellvalue + '(' + rowObject[6] + ')</span>&nbsp;&nbsp;<span class="fa fa-sort-down" style="top: -2px;position: relative;"></span>';
		      $emps += '</a> ';
		      $emps += '<div class="dropdown-menu" aria-labelledby="navbarDropdown">';
					$emps += '<a onclick=msgView("' + rowObject[6] + '") class="dropdown-item">쪽지보내기</a>';
					$emps += '<a onclick=infoView("' + rowObject[6] + '") class="dropdown-item">정보보기</a>';
					$emps += '</div>';
		      $emps += '</span>';
		      return $emps;
			//return '<a href="#none" class="link" onclick=msgView("'+rowObject[6]+'")>'+cellvalue+'</a>';
		}
		function msgList(){
			$("#newMsg").modal('hide');
			$("#umsgList").modal('show');
			
			$('#umsgList').on('hide.bs.modal', function (event) {
			  $("#mList").jqGrid('GridUnload');
			});
			
			$("#mList").jqGrid({//그리드 세팅
				url:'<?php echo site_url()?>'+'/Common/msgList',      
				mtype : "POST",             
				datatype: "json",      
				postData: {
					"id" : "<?php echo $this->session->userdata('userid');?>"
				},
				colNames:['id','받는사람','보낸사람','내용','받은날짜','확인'],       
				colModel:[
					{name:'id',index:'id', width:100, align:"center", hidden:true},
					{name:'R_ID',index:'R_ID', width:100, align:"center", hidden:true},
					{name:'S_ID',index:'S_ID', width:100, align:"center", formatter: sendid},
					{name:'MSG',index:'MSG', width:250, align:"left", hidden:true},
					{name:'INS_DT',index:'INS_DT', width:150, align:"center"},
					{name:'ETC2',index:'ETC2', width:50, align:"center"},
				],
				width: 568,
				height: 285,
				rowNum:10,
				rowList:[10,100,500],
				pager: '#mPage',
				sortname: 'INS_DT',
				sortorder: 'desc',
				sorttype: 'date',
				shrinkToFit: true,
				//autowidth: true,
				viewrecords: true,
				//rownumbers: true,
				gridview: true,
				caption:"목록",
				multiselect: true,
				multiselectWidth: 60,
				loadBeforeSend:function(){
				},
				loadComplete:function(data){
					
					$("#mList").setGridParam({
						postData:{
							"DEL_ALL" :'',
							"REMOVE_ARR" :'',
							"CON_ALL" :''
						}
					}).trigger("reloadGrid");
				},
				
				/*내용보기 시작*/
				subGrid: true,
		    subGridRowExpanded: function(subgrid_id, row_id) {
		       var subgrid_table_id;
		       subgrid_table_id = subgrid_id+"_t";
		       jQuery("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table>");
		       jQuery("#"+subgrid_table_id).jqGrid({
		       		url:'<?php echo site_url()?>'+'/Common/msgList',
		          mtype : "POST",             
							datatype: "json",      
		          postData: {
								"id" : "<?php echo $this->session->userdata('userid');?>",
								"viewYn" : 'Y',
								"msg_id" : row_id
							},
		          colNames:['id','받는사람','보낸사람','내용','받은날짜','확인'], 
		          colModel: [
		           	{name:'id',index:'id', width:100, align:"center", hidden:true},
								{name:'R_ID',index:'R_ID', width:100, align:"center", hidden:true},
								{name:'S_ID',index:'S_ID', width:100, align:"center", formatter: sendid, hidden:true},
								{name:'MSG',index:'MSG', width:250, align:"left",formatter: textareas, sortable : false},
								{name:'INS_DT',index:'UPD_DT', width:150, align:"center", formatter:"date", formatoptions:{newformat:"Y-m-d"}, hidden:true},
								{name:'ETC2',index:'ETC2', width:100, align:"center", hidden:true},
		          ],
		          height: '100%',
	          	width: $('.tablediv').width(),
		          rowNum:1,
		          sortname: 'id',
		          sortorder: "dest",
		          loadComplete:function(data){
		          	//확인여부 text변경
		          	$("[id="+row_id+"]").find('[aria-describedby=mList_ETC2]').text('Y');
		          	jQuery("#"+subgrid_table_id).setGridParam({
									postData:{
										"viewYn" : ''
									},
									page:1
								}).trigger("reloadGrid");
		          }
		       });
		   }
				/*내용보기 끝*/
				
		});
			
		}
		
		
		
		
		/*보낸쪽지*/
		function msgListSend(){
			
			$("#newMsg").modal('hide');
			$("#umsgListSend").modal('show');
			$('#umsgListSend').on('hide.bs.modal', function (event) {
			  $("#mListSend").jqGrid('GridUnload');
			});
			
			$("#mListSend").jqGrid({//그리드 세팅
				url:'<?php echo site_url()?>'+'/Common/msgListSend',      
				mtype : "POST",             
				datatype: "json",      
				postData: {
					"id" : "<?php echo $this->session->userdata('userid');?>"
				},
				colNames:['id','보낸사람','받는사람','내용','보낸날짜','확인'],       
				colModel:[
					{name:'id',index:'id', width:100, align:"center", hidden:true},
					{name:'S_ID',index:'S_ID', width:100, align:"center", hidden:true},
					{name:'R_ID',index:'R_ID', width:100, align:"center", formatter: sendid},
					{name:'MSG',index:'MSG', width:250, align:"left", hidden:true},
					{name:'INS_DT',index:'INS_DT', width:200, align:"center"},
					{name:'ETC2',index:'ETC2', width:50, align:"center", hidden:true},
				],
				width: 568,
				height: 285,
				rowNum:10,
				rowList:[10,100,500],
				pager: '#mPageSend',
				sortname: 'INS_DT',
				sortorder: 'desc',
				sorttype: 'date',
				shrinkToFit: true,
				//autowidth: true,
				viewrecords: true,
				//rownumbers: true,
				gridview: true,
				caption:"목록",
				multiselect: true,
				multiselectWidth: 60,
				loadBeforeSend:function(){
				},
				loadComplete:function(data){
					
					$("#mListSend").setGridParam({
						postData:{
							"DEL_ALL" :'',
							"REMOVE_ARR" :'',
							"CON_ALL" :''
						}
					}).trigger("reloadGrid");
				},
				
				/*내용보기 시작*/
				subGrid: true,
		    subGridRowExpanded: function(subgrid_id, row_id) {
		       var subgrid_table_id;
		       subgrid_table_id = subgrid_id+"_t_s";
		       jQuery("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table>");
		       jQuery("#"+subgrid_table_id).jqGrid({
		       		url:'<?php echo site_url()?>'+'/Common/msgListSend',
		          mtype : "POST",             
							datatype: "json",      
		          postData: {
								"id" : "<?php echo $this->session->userdata('userid');?>",
								"viewYn" : 'Y',
								"msg_id" : row_id
							},
		          colNames:['id','보낸사람','받는사람','내용','보낸날짜','확인'], 
		          colModel: [
		           	{name:'id',index:'id', width:100, align:"center", hidden:true},
								{name:'S_ID',index:'S_ID', width:100, align:"center", hidden:true},
								{name:'R_ID',index:'R_ID', width:100, align:"center", formatter: sendid, hidden:true},
								{name:'MSG',index:'MSG', width:250, align:"left",formatter: textareas, sortable : false},
								{name:'INS_DT',index:'UPD_DT', width:150, align:"center", formatter:"date", formatoptions:{newformat:"Y-m-d"}, hidden:true},
								{name:'ETC2',index:'ETC2', width:100, align:"center", hidden:true},
		          ],
		          height: '100%',
	          	width: $('.tablediv').width(),
		          rowNum:1,
		          sortname: 'id',
		          sortorder: "dest",
		          loadComplete:function(data){
		          	//확인여부 text변경
		          	/*
		          	$("[id="+row_id+"]").find('[aria-describedby=mList_ETC2]').text('Y');
		          	jQuery("#"+subgrid_table_id).setGridParam({
									postData:{
										"viewYn" : ''
									},
									page:1
								}).trigger("reloadGrid");
								*/
		          }
		       });
		   }
				/*내용보기 끝*/
				
		});
			
		}
		
		
		
		
		
	/* 메세지 검색 */
	function msg_search(){
		$("#mList").setGridParam({
			postData:{
				"searchOper":$("#searchOper_msg").val(),
				"_search1":$("#_search1_msg").val(),
				"searchField":$("#searchField_msg option:selected").val(),
				"searchString":$("#searchString_msg").val()
			},
			page:1
		}).trigger("reloadGrid");
	}
	/* 보낸 메세지 검색 */
	function msg_search_send(){
		$("#mListSend").setGridParam({
			postData:{
				"searchOper":$("#searchOper_msg_send").val(),
				"_search1":$("#_search1_msg_send").val(),
				"searchField":$("#searchField_msg_send option:selected").val(),
				"searchString":$("#searchString_msg_send").val()
			},
			page:1
		}).trigger("reloadGrid");
	}	
	/*메세지 비우기*/	
	function del_msg(){
		var msg = "모든 쪽지를 삭제 합니다. ";
		bootbox.confirm({
				size: "small",
				message: msg,
				buttons: {
					confirm: {
						label: '확인',
						className: "btn-<?php echo $this->config->item($this->uri->segment(1).'Color')?>"
					},
					cancel: {
						label: '취소'
					}
				},
				callback: function (result) {
					if(result == true){
						
						var postdata = {}
						postdata = {
								"DEL_ALL" :'Y'
							}
							
						$("#mList").setGridParam({
							postData:postdata,
							page:1
						}).trigger("reloadGrid");
					}
				}
			});	
	}
	/*보낸 메세지 비우기*/	
	function del_msg_send(){
		var msg = "모든 쪽지를 삭제 합니다. ";
		bootbox.confirm({
				size: "small",
				message: msg,
				buttons: {
					confirm: {
						label: '확인',
						className: "btn-<?php echo $this->config->item($this->uri->segment(1).'Color')?>"
					},
					cancel: {
						label: '취소'
					}
				},
				callback: function (result) {
					if(result == true){
						
						var postdata = {}
						postdata = {
								"DEL_ALL" :'Y'
							}
							
						$("#mListSend").setGridParam({
							postData:postdata,
							page:1
						}).trigger("reloadGrid");
					}
				}
			});	
	}
	/*메세지 확인*/	
	function con_msg(){
		var msg = "모든 쪽지를 확인 상태로 변경합니다. ";
		bootbox.confirm({
				size: "small",
				message: msg,
				buttons: {
					confirm: {
						label: '확인',
						className: "btn-<?php echo $this->config->item($this->uri->segment(1).'Color')?>"
					},
					cancel: {
						label: '취소'
					}
				},
				callback: function (result) {
					if(result == true){
						
						var postdata = {}
						postdata = {
								"CON_ALL" :'Y'
							}
							
						$("#mList").setGridParam({
							postData:postdata,
							page:1
						}).trigger("reloadGrid");
					}
				}
			});	
	}
	/*선택 메세지 삭제*/
	function selectRows_msg(t){
		
		var ids = $("#mList").jqGrid('getGridParam', 'selarrrow');      //체크된 row id들을 배열로 반환
		var msg = "";
		
		if(t == 'del'){
			msg = "총 "+ids.length+"건을 삭제하시겠습니까? ";
		}
		
		if(ids.length > 0){
			bootbox.confirm({
				size: "small",
				message: msg,
				buttons: {
					confirm: {
						label: '확인',
						className: "btn-<?php echo $this->config->item($this->uri->segment(1).'Color')?>"
					},
					cancel: {
						label: '취소'
					}
				},
				callback: function (result) {
					if(result == true){
						var arr = [];
						for(var i = 0; i < ids.length; i++){
							var rowObject = $("#mList").getRowData(ids[i]);      //체크된 id의 row 데이터 정보를 Object 형태로 반환
							var value = rowObject.id;     //Obejct key값이 value값 반환
							arr.push(value);
						}
						
						
						var postdata = {}
						
						if(t == 'del'){
							postdata = {
								"REMOVE_ARR" :arr
							}
						}
						
						$("#mList").setGridParam({
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
		}
	}
	
	/*선택 보낸 메세지 삭제*/
	function selectRows_msg_send(t){
		
		var ids = $("#mListSend").jqGrid('getGridParam', 'selarrrow');      //체크된 row id들을 배열로 반환
		var msg = "";
		
		if(t == 'del'){
			msg = "총 "+ids.length+"건을 삭제하시겠습니까? ";
		}
		
		if(ids.length > 0){
			bootbox.confirm({
				size: "small",
				message: msg,
				buttons: {
					confirm: {
						label: '확인',
						className: "btn-<?php echo $this->config->item($this->uri->segment(1).'Color')?>"
					},
					cancel: {
						label: '취소'
					}
				},
				callback: function (result) {
					if(result == true){
						var arr = [];
						for(var i = 0; i < ids.length; i++){
							var rowObject = $("#mListSend").getRowData(ids[i]);      //체크된 id의 row 데이터 정보를 Object 형태로 반환
							var value = rowObject.id;     //Obejct key값이 value값 반환
							arr.push(value);
						}
						
						
						var postdata = {}
						
						if(t == 'del'){
							postdata = {
								"REMOVE_ARR" :arr
							}
						}
						
						$("#mListSend").setGridParam({
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
		}
	}
		
		
	</script>
	
	<style>
		/*서브그리드*/
		.ui-jqgrid-bdiv{
		/*스크롤사용x*/
			overflow-x:hidden!important
		}
		.tablediv,
		.ui-subgrid,
		.subgrid-cell{
			background: #f9f9f9
		}
		.ui-subgrid .ui-jqgrid tr.jqgrow{
			background:#fff!important
		}
		.ui-icon-carat-1-sw{
			background-position: -76px 1px;
			margin-left: 3px;
			display:none;
		}
		.ui-jqgrid .ui-widget-content{
			border:none
		}
		.subgrid-cell{
			    border-right: 1px dashed #ddd!important;
		}
		
		.ui-jqgrid-bdiv {
	          min-height:100px;
	  }
	  .ui-subgrid .ui-jqgrid-bdiv{
	  	       min-height:auto;
	  }
	  .ui-subgrid{
	  	border-bottom:1px solid #ddd;
	  }
	  .subgrid-data{
	  	border-bottom:none!important
	  }
	  /*msg modal position 변경*/
	  #newMsg{
	  	right:20px;
	  	width: 320px;
	  	left: inherit!important;
	  	right: 20px;
	  	bottom: 0!important;
	  	top: inherit!important;
	  }
	  /*jqgrid overflow 없앰*/
	  #mList tr.jqgrow td,
	  #mListSend tr.jqgrow td{
	  	overflow:inherit!important
	  }
	  /*아이디 영역*/
	  .msgIdArea{
			    width: 81px;
			    height: 16px;
			    display: inline-block;
			    overflow: hidden;
			    text-overflow: ellipsis;
		}
		
	</style>
	
	<div class="leftUserMenu">
	</div>
	
	<!--메세지 보내기 (ID선택)-->
	<div class="modal" id="umsgPush" tabindex="-1" role="dialog" style="z-index:1051">
	  <div class="modal-dialog modal-sm" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">[<span></span>] 쪽지 보내기</h4>
	      </div>
	      <div class="modal-body">
	        <textarea style="height:100px" class="msg form-control"></textarea>
	      </div>
	      <div class="modal-footer">
	      	<button type="button" class="msg_send btn btn-<?php echo $this->config->item($this->uri->segment(1).'Color');?>"><span class="glyphicon glyphicon-send"></span> 보내기</button>
	        <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	
	
	<!--받은쪽지함-->
	<div class="modal" id="umsgList" tabindex="-1" role="dialog">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">받은 쪽지</h4>
	      </div>
	      <div class="modal-body" style="height:500px;overflow:auto">
	      	<div>
	      	<form id="frm_search_msg" name="frm_search_msg" method="post" onsubmit="return false;">
						<input type="hidden" id="searchOper_msg" name="searchOper" value="cn" />
						<input type="hidden" id="_search1_msg" name="_search1" value="true" />
						<select class="form-control width_100px" style="width:120px" id="searchField_msg" name="searchField">
							<option value="S_ID" selected >보낸사람</option>
							<option value="MSG">내용</option>
						</select>
						
						<input type="text" id="searchString_msg" name="searchString" class="form-control width_200px" placeholder="검색어를 입력해주세요.">
						<a class="btn btn-<?php echo $this->config->item($this->uri->segment(1).'Color')?>" onclick="msg_search()">
							<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
							검색
						</a>
					</form>
					
					<button type="button" class="btn btn-default btn-sm mt-10" onclick="selectRows_msg('del');">
						<span class="glyphicon glyphicon-floppy-remove" aria-hidden="true"></span>
						선택삭제
					</button>
					<button type="button" class="btn btn-default btn-sm mt-10" onclick="del_msg();">
						<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
						비우기
					</button>
					<button type="button" class="btn btn-default btn-sm mt-10" onclick="con_msg();">
						<span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span>
						전체확인
					</button>
					
	      	</div>
	      	
	      	<br />
	      	<ul class="nav nav-tabs msg_tab">
					  <li class="msg_tab1 active"><a>받은쪽지</a></li>
					  <li class="msg_tab2"><a>보낸쪽지</a></li>
					</ul>
	      	
	      	
	        <table id="mList"></table>
	        <div id="mPage"></div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	
	
	
	
	
	
	<!--보낸 쪽지함-->
	<div class="modal" id="umsgListSend" tabindex="-1" role="dialog">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">보낸 쪽지</h4>
	      </div>
	      <div class="modal-body" style="height:500px;overflow:auto">
	      	<div>
	      	<form id="frm_search_msg" name="frm_search_msg_send" method="post" onsubmit="return false;">
						<input type="hidden" id="searchOper_msg_send" name="searchOper" value="cn" />
						<input type="hidden" id="_search1_msg_send" name="_search1" value="true" />
						<select class="form-control width_100px" style="width:120px" id="searchField_msg_send" name="searchField">
							<option value="R_ID" selected >받는사람</option>
							<option value="MSG">내용</option>
						</select>
						
						<input type="text" id="searchString_msg_send" name="searchString" class="form-control width_200px" placeholder="검색어를 입력해주세요.">
						<a class="btn btn-<?php echo $this->config->item($this->uri->segment(1).'Color')?>" onclick="msg_search_send()">
							<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
							검색
						</a>
					</form>
					
					<button type="button" class="btn btn-default btn-sm mt-10" onclick="selectRows_msg_send('del');">
						<span class="glyphicon glyphicon-floppy-remove" aria-hidden="true"></span>
						선택삭제
					</button>
					<button type="button" class="btn btn-default btn-sm mt-10" onclick="del_msg_send();">
						<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
						비우기
					</button>
					
	      	</div>
	      	
	      	<br />
	      	<ul class="nav nav-tabs">
					  <li class="msg_tab1"><a>받은쪽지</a></li>
					  <li class="msg_tab2 active"><a>보낸쪽지</a></li>
					</ul>
	      	
	      	
	        <table id="mListSend"></table>
	        <div id="mPageSend"></div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	
	
	
	
	
	<!--새로운쪽지알림-->
	<div class="modal fade" id="newMsg" tabindex="-1" role="dialog" data-backdrop="false" data-keyboard="false">
	  <div class="modal-dialog modal-sm" role="document">
	    <div class="modal-content">
	      <div class="modal-body" style="padding:0">
	      	<div class="p-20">
	      		<div>
	      			<span class="glyphicon glyphicon-envelope" aria-hidden="true"></span> <span class="msgText">새로운 쪽지가 있습니다.</span>
	      		</div>
	      	</div>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	
	
	
	
	
	
	
	<!--유저정보-->
	<div class="modal" id="infoView" tabindex="-1" role="dialog">
	  <div class="modal-dialog modal-sm" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">정보</h4>
	      </div>
	      <div class="modal-body" style="padding:0">
	      	<div class="p-20 cput">
		      	<div class="userViewPic pull-left" style="width:70px;height:70px;overflow:hidden;border-radius:100px">
		      	</div>
		      	<div class="userViewInfos pull-left" style="margin-left:20px;line-height: 192%;">
		      	</div>
		      	<div class="clear"></div>
		      	<!--
		      	
		      	-->
	      	</div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	
	
</div>
		
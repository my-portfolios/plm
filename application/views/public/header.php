<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="utf-8">
	<title><?php echo $this->config->item('siteTitle')?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<!--[if lte IE 9]>
	<script type="text/javascript">
	alert('IE 브라우저는 9이상만 지원합니다.');
	location.href="/index.php/Ieerror";
	</script>
	<![endif]-->
	
	<script src='<?php echo base_url();?>js/moment.min.js'></script>
	<script src="<?php echo base_url();?>js/jquery.min.3.2.js"></script>
	<script src="<?php echo base_url();?>js/jquery-migrate-1.4.1.min.js"></script>
	<script src="<?php echo base_url();?>js/jquery-ui.min.js"></script>
	<script src="<?php echo base_url();?>js/i18n/grid.locale-kr.js"></script>
	<script src="<?php echo base_url();?>js/moment-with-locales.js"></script>
	<script src="<?php echo base_url();?>js/jquery.cookie.js"></script>
	<script src="<?php echo base_url();?>js/bootstrap.min.js"></script>
	<script src="<?php echo base_url();?>js/bootbox.min.js"></script>
	<script src="<?php echo base_url();?>js/validator.js"></script>
	<script src="<?php echo base_url();?>js/jstree.js"></script>
	<script src="<?php echo base_url();?>js/jstree.checkbox.js"></script>
	<script src="<?php echo base_url();?>js/jstree.contextmenu.js"></script>
	<script src="<?php echo base_url();?>js/jstree.dnd.js"></script>
	<script src="<?php echo base_url();?>js/jstree.types.js"></script>
	<script src="<?php echo base_url();?>js/jstree.wholerow.js"></script>
	<script src="<?php echo base_url();?>js/jstree.state.js"></script>
	<script src="<?php echo base_url();?>js/vakata-jstree.js"></script>
	<script src="<?php echo base_url();?>js/jstree.conditionalselect.js"></script>
	<script src="<?php echo base_url();?>js/jquery.jqGrid.min.js"></script>
	<script src="<?php echo base_url();?>js/bootstrap-tagsinput.js"></script>
	<script src="<?php echo base_url();?>js/fileinput.js"></script>
	<script src="<?php echo base_url();?>js/locales/kr.js"></script>
	<script src="<?php echo base_url();?>js/bootstrap-datetimepicker.min.js"></script>
	<script src="<?php echo base_url();?>js/jquery.number.js"></script>
	<script src="<?php echo base_url();?>js/jquery.orgchart.js"></script>
	<script src="<?php echo base_url();?>js/ekko-lightbox.js"></script>
	<!--<script src="<?php echo base_url();?>js/ekko-lightbox.js.map"></script>-->
	<script src="<?php echo base_url();?>js/html2canvas.js"></script>
	<script src="<?php echo base_url();?>js/evol-colorpicker.js"></script>
	
	<script src="<?php echo base_url();?>js/jspdf.debug.js"></script>
	
	<link href="/lib/summernote/summernote.css" rel="stylesheet">
	<script src="/lib/summernote/summernote.js"></script>
	<!-- summer note korean language pack -->
	<script src="/lib/summernote/lang/summernote-ko-KR.js"></script>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="<?php echo base_url();?>css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo base_url();?>css/bootstrap.css">
	<link rel="stylesheet" href="<?php echo base_url();?>js/themes/default/style.css">
	<link rel="stylesheet" href="<?php echo base_url();?>css/jquery-ui.css">
	<link rel="stylesheet" href="<?php echo base_url();?>css/ui.jqgrid.css">
	<link rel="stylesheet" href="<?php echo base_url();?>css/bootstrap-tagsinput.css">
	<link rel="stylesheet" href="<?php echo base_url();?>css/fileinput.css">
	<link rel="stylesheet" href="<?php echo base_url();?>css/bootstrap-theme.css">
	<link href="<?php echo base_url();?>css/bootstrap-toggle.min.css" rel="stylesheet">
	<script src="<?php echo base_url();?>js/bootstrap-toggle.min.js"></script>
	<link rel="stylesheet" href="<?php echo base_url();?>css/bootstrap-datetimepicker.css">
	<link rel="stylesheet" href="<?php echo base_url();?>css/jquery.orgchart.css">
	<link rel="stylesheet" href="<?php echo base_url();?>css/ekko-lightbox.css">
	<link rel="stylesheet" href="<?php echo base_url();?>css/evol-colorpicker.css">
	<script>
		/*jqgrid 5.이상시
		if ($.fn.jqGrid["GridUnload"] === undefined) {
	    $.fn.jqGrid["GridUnload"] = $.jgrid.gridUnload;
		}
		*/
	</script>
	<!--style.php-->
	<?php
	if($this->session->userdata('userskin') == ''){
		include($_SERVER["DOCUMENT_ROOT"]."/application/views/public/style_Def.php");
	}else{ 
	 include($_SERVER["DOCUMENT_ROOT"]."/application/views/public/style_".$this->session->userdata('userskin').".php"); 
	}
	//mb to kb
	function mtb($f)
    {
    		$str=ini_get($f);
				preg_match('/[0-9]+/', $str, $match);
				
				return ($match[0] * 1024);
		}
	
	?>	
	
	
	<style>
		/*jqgrid*/
		.ui-jqgrid{
    	z-index:1
    }
		.ui-jqgrid-bdiv:after{
			content: '내용이 없습니다.';
	    display: block;
	    width: 100%;
	    text-align: left;
	    position: absolute;
	    top: 18px;
	    left: 20px;
	    z-index:0;
		}
		.ui-jqgrid-btable{
			position:relative;
			z-index:1;
		}
		.nav-item{
			margin-bottom: 2px;
	    margin-right: 3px;
	    display: inline-block;
		}
	</style>
	 
	
	<script>	
		
		
		//권한 (관리자 , 작성자만 체크박스 활성화) jqgrid
		function fn_chkDisabled(table){
			if("<?php echo $this->session->userdata('userauth')?>" != 'admin'){
				var ids = '';
				ids = $("#"+table).jqGrid('getDataIDs');
				$.each(ids,function(i,v){
					var ins_id = $("#"+table).getRowData(v).INS_ID;
					if(ins_id != '<?php echo $this->session->userdata('userid') ?>'){
						$("#jqg_"+table+"_"+v).attr('disabled','disabled');
					}
					$('#'+v).click(function(){
						var id = $(this).attr('id');
						if($(this).find('.cbox').attr('disabled') == 'disabled'){
							$(this).find('.cbox').attr('checked',false);
							$("#"+table).setSelection(id, false);
						}
					});
				});
				$("#cb_"+table).attr('onchange','fn_chkDisabled_change("'+table+'")');
			}
		}
		function fn_chkDisabled_change(table){
			var ids = '';
					ids = $("#"+table).jqGrid('getDataIDs');
					$.each(ids,function(i,v){
						if($('#jqg_'+table+'_'+v).attr('disabled') == 'disabled'){
								$('#jqg_'+table+'_'+v).attr('checked',false);
								$("#"+table).setSelection(v, false);
							}
					});
		}
		//권한 (관리자 , 작성자만 체크박스 활성화)_끝
		
		//삭제된 유저인지 확인 (reply id side menu)
		function userYn(id,target){
			$.ajax({
				type: 'post',
				dataType: 'json',
				url: '<?php echo base_url();?>index.php/Common/userYn',
				data: {"id" : id},
				success: function (data) {
					//true false
					if(data){
						//
					}else{
						$('.'+target).attr('disabled','disabled');
						$('.'+target).addClass('disabled');
					}
				},
				error: function (request, status, error) {
					console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
				}
			});
		};
		//유저 id로 이름가져오기 fn : id
		function getUserIdToNm(id,target){
			$.ajax({
				type: 'post',
				dataType: 'json',
				url: '<?php echo base_url();?>index.php/Common/getUserIdToNm',
				data: {"id" : id},
				success: function (data) {
					var d = data.split('^');
					if(d[1] == 0){
						$('.'+target).text(d[0]);
						$('.'+target).parent().attr('disabled','disabled');
						$('.'+target).parent().addClass('disabled');
					}else{
						$('.'+target).text(d[0]);
					}
				},
				error: function (request, status, error) {
					console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
				}
			});
		};
		//윈도우 팝업 센터 정렬
		function PopupCenter(url, title, w, h) {
		    var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : window.screenX;
		    var dualScreenTop = window.screenTop != undefined ? window.screenTop : window.screenY;
		    var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
		    var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;
		    var left = ((width / 2) - (w / 2)) + dualScreenLeft;
		    var top = ((height / 2) - (h / 2)) + dualScreenTop;
		    var newWindow = window.open(url, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
		    if (window.focus) {
		        newWindow.focus();
		    }
		}
		
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
		
		//ie return false 버그용

		 function preventDefaultAction(rtnValue) {
		  if (!rtnValue)
		  {
		   if (typeof event.preventDefault!= 'undefined') {
		     event.preventDefault(); // W3C
		   } else {
		     event.returnValue = false; // IE
		   }
		  }
		   return rtnValue;
		 }
		
		//높이 지정
		function heightAuto(top,left,right,bottom,searcharea){//top,left,right,bottom
			var th = $(top).outerHeight();
			var bh = $(bottom).outerHeight();
			if($(searcharea).parent().css('display') != 'none'){
				var sh = $(searcharea).outerHeight();
			}else{
				var sh = 0;
			}
			var wh= $(window).outerHeight();
			var tb = th+bh;
			var tbr = th+bh+sh;
			var ch = wh-tb;
			var chr = wh-tbr;
			$(left).outerHeight(ch);
			$(right).outerHeight(ch);
			$(right).find('.grid_area').outerHeight(chr);
			/**/
			
		}
		
		//user 사진
		function getPic(userids,targets){
			$.ajax({
				type: 'post',
				dataType: 'json',
				url: '<?php echo base_url();?>index.php/Common/getPic',
				data: {id : userids},
				success: function (data) {
					if(data){
						var n = data.PF_FILE_TEMP_NM.split('.');
						if(n[1] == 'gif'){
							$(targets).append('<img style="margin-left: 50%;transform: translateX(-50%);" height="70px" data-toggle="tooltip" data-placement="bottom" src="/uploads/'+n[0]+'.'+n[1]+'" />');
						}else{
							$(targets).append('<img style="margin-left: 50%;transform: translateX(-50%);" height="70px" data-toggle="tooltip" data-placement="bottom" src="/uploads/'+n[0]+'_thumb.'+n[1]+'" />');
						}
					}else{
						$(targets).append('<img width="70px" height="70px" data-toggle="tooltip" data-placement="bottom" title="프로필 이미지가 없습니다." src="/img/no_pro.png" />');
					}
				},
				error: function (request, status, error) {
					console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
				}
			});
		}
		
		$(window).load(function(){
			
			
				//양식선택 버튼넣기
				if($('#content_ajax').size() == 0){
					setTimeout(function(){
						var s = $('[contenteditable="true"]');
						$.each(s,function(i,v){
							$(v).parent().parent().after('<div class="pull-right" style="margin-top: -6px;margin-bottom:10px"><button type="button" data="'+i+'" class="btn_selFormat btn-default btn btn-xs">양식선택</button></div><div style="clear:both"></div>');
						});
					},500);
				}else{
					$( document ).ajaxComplete(function(event,jqxhr,settings) {
						if(settings.url.indexOf('/index.php/pdm2/Upload') != -1){
							setTimeout(function(){
								var s = $('[contenteditable="true"]');
								$.each(s,function(i,v){
									$(v).parent().parent().after('<div class="pull-right" style="margin-top: -6px;;margin-bottom:10px"><button type="button" data="'+i+'" class="btn_selFormat btn-default btn btn-xs">양식선택</button></div><div style="clear:both"></div>');
								});
							},500);
						}
						
					});
				}
				
				heightAuto('#wp_top','#wp_left','#wp_right','#wp_bottom','.search_area');//높이 맞춤
				setTimeout(function(){
					$('#loading').modal("hide");
				},500); 
				
			});
		
		
		/*파일인풋 세팅 변경*/
		$.fn.fileinput.defaults2 = {
			maxFileSize: '<?php echo mtb("upload_max_filesize")?>'
		}
		
		/*파일인풋 텍스트 수정*/
		$.fn.fileinputLocales.en = {
			initialCaption: '최대 파일크기 : <?php echo ini_get("upload_max_filesize")?> / 전체 전송크기 : <?php echo ini_get("post_max_size");?>'
		}
		
		$(function(){
			
			/*이미지 미리보기*/
			$(document).on('click', '[data-toggle="lightbox"]', function(event) {
	          event.preventDefault();
	          $(this).ekkoLightbox();
	      });
			
			/* 양식 선택 */
			$(document).on('click','.btn_selFormat',function(){
				$("#format_right").setGridParam({
					page:1
				}).trigger("reloadGrid");
				
				var textarea = $(this).attr('data');
				$("#pop_formatSearch").modal('show');
				$("#pop_formatSearch").find('#tg').val(textarea);
			});
		
			
			/*req-readonly*/
			$(document).on('keyup','.req-readonly',function(){
				$(this).val('');
			});
			$(document).on('keydown','.req-readonly',function(){
				$(this).val('');
			});
			$(document).on('focus','.req-readonly',function(){
				$(this).blur();
			});
			
			/*데이타피커*/
      $('.datetimepicker').datetimepicker({
          locale: 'ko',
          format: 'YYYY-MM-DD',
          //showTodayButton: true,
          tooltips: {
          	today: '오늘'
          },
          useCurrent: false //Important! See issue #1075
      });
      
      $(".sdate").on("dp.change", function (e) {
          $('.edate').data("DateTimePicker").minDate(e.date);
      });
      $(".edate").on("dp.change", function (e) {
          $('.sdate').data("DateTimePicker").maxDate(e.date);
      });
			
			/*ajax404에러가 아닐때만 페이지 이동*/
			$( document ).ajaxError(function(event, jqxhr, settings, thrownError) {
				if(jqxhr.status != '404'){
			  	//location.href="/";
				}
			});
			/*기본으로 로딩바 보이게*/
			$('#loading').modal("show");
			
			/*툴팁활성*/
			$('[data-toggle="tooltip"]').tooltip();
			
			/* 모달 초기화 (닫을때)
			$('.modal').on('hidden.bs.modal', function (e) {
			  $(this)
				.find("input[type=text],textarea,select,file")
				   .val('')
				   .end()
				.find("input[type=checkbox], input[type=radio]")
				   .prop("checked", "")
				   .end();
			});
			 */
			
			
			$(window).resize(function(){//리사이즈 이벤트
				heightAuto('#wp_top','#wp_left','#wp_right','#wp_bottom','.search_area');//높이 맞춤
				//서브그리드있으면 삭제		
				$('.ui-subgrid').remove();
				$('.ui-sgcollapsed').removeClass('sgexpanded');
				$('.ui-sgcollapsed').addClass('sgcollapsed');
				$('.ui-sgcollapsed').find('a span').removeClass('ui-icon-minus').addClass('ui-icon-plus');
			});

			
			//로그아웃
			$(document).on('click','.logout',function(){
				bootbox.confirm({
					size: "small",
				  message: "로그아웃 하시겠습니까?", 
				  callback: function(result){
				  	if(result == true){
							location.href="<?php echo base_url();?><?php echo index_page();?>/Welcome/do_logout";	
						}
				  },
				  buttons: {
			        confirm: {
			            label: '확인',
			            className: "btn-<?php echo $this->config->item($this->uri->segment(1).'Color')?>"
			        }
			    }
				});
			});
			
			//left menu hide & show
			
			$('#wp_right').append('<div class="leftMenuClose"></div>');
			
			/*
			$('.leftMenuClose').click(function(){
				
				bootbox.confirm({
					size: "small",
					message: '화면이 새로고침 됩니다.', 
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
							if($('#wp_right').css('left') == '0px'){
								$.cookie('leftMenuYn','N',{path: '/'});
							}else{
								$.cookie('leftMenuYn','Y',{path: '/'});
							}
							location.reload();
						}
					}
				});
			});
			*/
		});
		
		//스크롤바 여부확인 
		$.fn.hasScrollBar = function() {
    return (this.prop("scrollHeight") == 0 && this.prop("clientHeight") == 0)
            || (this.prop("scrollHeight") > this.prop("clientHeight"));
		};

	</script>
	
</head>
<body>
	<?php
	//response
	include($_SERVER["DOCUMENT_ROOT"]."/application/views/public/response.php");	
	?>
	<div id="loading" class="modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="overflow-y:visible;">
		<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
			    <h5 style="margin:0;">
			       Loading...
			    </h5>
			</div>
			<div class="modal-body">
				<div class="progress progress-striped active " style="margin-bottom:0;">
				    <div class="progress-bar bg-<?php echo $this->config->item($this->uri->segment(1).'Color');?>" style="width: 100%">
				    </div>
				</div>
	          </div>
	      </div>
	  </div>
	</div>
	<div id="wp">
		
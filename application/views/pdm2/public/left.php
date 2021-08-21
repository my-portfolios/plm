<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script>
$(function(){
	//트리 가져오기
	var jstreeObj = $('#jstree-tree-left');
	jstreeObj.jstree({
		'core' : {
			"dblclick_toggle" : false,
			'multiple' : false,
			'data' : function (node, cb) {
				$.ajax({
					"url" : "/index.php/Common/getTreeJson",
					"data" : { "id" : node.id },
					type: 'POST',
					"success": function(data) {
						var data = $.parseJSON(data);
						cb(data);
					}
				});
			}
		}
	}).on('loaded.jstree', function(e,data) {
		//트리로드 이벤트
	//	jstreeObj.jstree('open_node', 'PFD_1');
		gridSet();
		var hash = window.location.hash;
		if(hash == ''){
			var pfd_id = '<?php echo $this->session->flashdata("pfd_id");?>';
			if(pfd_id){
				jstreeObj.jstree('select_node', pfd_id);
			}else{
				jstreeObj.jstree('select_node', 'PLM');
			}
		}
	});
  
	jstreeObj.bind(//트리메뉴 클릭
		"select_node.jstree", function(evt, data){
			location.hash = 'TREE_'+data.node.id;
		}
	);
	
	/* 로드 시 모든 다이얼로그 삭제 */
	$('.ui-dialog').each(function(i,v){
		v.remove();
	});
	
	
	/* 폴더관리 버튼 클릭 */
	$(document).on('click','.btn_add_folder',function(){
		$('#pop_folder').modal('show');
    $('#pop_folder').on('shown.bs.modal', function() {
    	 getFolderTree();
		});
	});
	
	
	/* 업로드 버튼 클릭 */
	$(document).on('click','.btn_add_file',function(){
		  location.hash = 'WRITE_NEW';
	});
	
	/*왼쪽 메뉴 클릭*/

	$(document).on('click','.list-group-c .s_y',function(){
		location.hash = '#'+$(this).attr('m');
	});
});

$(window).bind( 'hashchange', function(e) { 
	hashSet();
});
function linkGrids(t){//링크 클릭
	
	$("#loading").modal('show');
	/*location.href="<?php echo site_url();?>/pdm2/Upload_view?id="+data;*/
	$( "#content_ajax" ).load( '<?php echo site_url();?>/pdm2/Upload_view?id='+t, function() {
	  heightAuto('#wp_top','#wp_left','#wp_right','#wp_bottom','');
	  $('.lists').hide();
	  $("#loading").modal('hide');
	  snoteLoad();
	});
	
}
function hashSet(){
	var hash = window.location.hash;
	
	if(hash.indexOf('#VIEW') != -1){
		var h = hash.replace('#VIEW_','');
		linkGrids(h);
	}
	if(hash.indexOf('#WRITE') != -1){
		var h = hash.replace('#WRITE_','');
		if(h=='NEW'){
			btn_fileUpd('NEW');
		}else{
			btn_fileUpd(h);
		}
	}
	if(hash.indexOf('#TREE') != -1){
		var h = hash.replace('#TREE_','');
		$('#jstree-tree-left').jstree("deselect_all");	//왼쪽트리디셀렉트
		$('#jstree-tree-left').jstree('select_node', h);
		
		$('#jstree-tree-left').jstree('open_node', h);
		if($('#pdm_list').size() > 0){
			detailSearchSeri();
			treeView(h);
		}
		$('#content_ajax').html('');
		$('.lists').show();
		$(window).trigger('resize');
		
		$('.s_y').removeClass('bg-gray');//메뉴 bg삭제
		
		var nodePath = $('#jstree-tree-left').jstree().get_path($('#jstree-tree-left').jstree("get_selected", true)[0], ' > ');
		$('.locas').text(nodePath);//타이틀변경
		
	}
	
	var mArr = [ '#all', "#m", "#image", "#movie","#doc","#rm","#bom","#trash" ];
	
	if($.inArray(hash,mArr) != -1){
		if ("onhashchange" in window) {
    	//$('[m='+hash.replace('#','')+']').click();
    	
    	$('#jstree-tree-left').jstree("deselect_all");	//왼쪽트리디셀렉트
			$('#jstree-tree-left').jstree("close_all");		//왼쪽트리모두 닫기
			
			var etcData = {
				"treeid":'',
				"docType":$('[m='+hash.replace('#','')+']').attr('M')
			}
			detailSearchSeri();
			treeView('',etcData);
			
			$('#content_ajax').html('');//아작스 컨텐츠 삭제
			$('.lists').show();//그리드 보이기
			$(window).trigger('resize');//리사이즈 이벤트
			
			$('.s_y').removeClass('bg-gray');//메뉴bg삭제
			$('[m='+hash.replace('#','')+']').addClass('bg-gray');//메뉴bg넣기
			
			$('.locas').text($('[m='+hash.replace('#','')+']').text());//타이틀변경
    	
		}
	}
	if(!hash){
		$('#content_ajax').html('');//아작스 컨텐츠 삭제
		$('.lists').show();//그리드 보이기
		$(window).trigger('resize');//리사이즈 이벤트
	}
}

function btn_fileUpd(pf_id){/* 수정 */
	if(!pf_id){
	var pf_id = $('#frm_upload_view').find('#PF_ID').val();
	}else{
		var pf_id = pf_id;
	}
	$("#loading").modal('show');
	$( "#content_ajax" ).load( "<?php echo site_url()?>/pdm2/Upload?id="+pf_id, function() {
	  heightAuto('#wp_top','#wp_left','#wp_right','#wp_bottom','');
	  $("#loading").modal('hide');
	  snoteLoad();
	  location.hash = 'WRITE_'+pf_id;
	});
	$('.lists').hide();//그리드 숨기기
	$(window).trigger('resize');//리사이즈 이벤트
}

</script>
<style>
	#jstree-tree-left .jstree-container-ul.jstree-children{
		/*
		margin-left: -24px
		*/
	}
</style>
<?php include($_SERVER["DOCUMENT_ROOT"]."/application/views/pdm2/Pop_folder.php"); ?>	<!-- 폴더관리 팝업 -->
<form class="leftMenuFrm" name="leftMenuFrm" action="<?php echo site_url()?>/pdm2/Upload" method="POST">
	<input type="hidden" name="id" />
</form>
<div id="wp_left" class="gray_border_right">
		
		<?php $this->load->view('/public/userInfo.php');?>
		<div class="text-center ptb-20">
			<div class="btn-group btn-group-toggle" data-toggle="buttons">
				<button type="button" class="btn btn-dark mt-10 btn_add_file">
					<span class="glyphicon glyphicon-open" aria-hidden="true"></span>
					업로드
				</button>
			</div>
		</div>
		
		<ul class="list-group-c list-group" style="">
			<li class="list-group-item s_y" m="all" style="cursor:pointer;" data-toggle="tooltip" data-placement="top" title="전체파일 보기">
				전체
		    <i class="pull-right fa fa-ellipsis-h text-default" style="font-size:14px"></i>
		  </li>
		  <li class="list-group-item s_y" m="m" style="cursor:pointer;" data-toggle="tooltip" data-placement="top" title="공유받은 파일만 보기">
				공유받은파일
		    <i class="pull-right fa fa-share-alt text-default" style="font-size:14px"></i>
		  </li>
		  <li class="list-group-item s_y" m="image" style="cursor:pointer;" data-toggle="tooltip" data-placement="top" title="이미지 파일만 보기">
				이미지
		    <i class="pull-right fa fa-file-photo-o text-default" style="font-size:14px"></i>
		  </li>
		  <li class="list-group-item s_y" m="movie" style="cursor:pointer;"  data-toggle="tooltip" data-placement="top" title="동영상 파일만 보기">
				동영상
		    <i class="pull-right fa fa-file-movie-o text-default" style="font-size:14px"></i>
		  </li>
		  <li class="list-group-item s_y" m="doc" style="cursor:pointer;" data-toggle="tooltip" data-placement="top" title="문서형식 파일만 보기">
				문서
		    <i class="pull-right fa fa-file-text-o text-default" style="font-size:14px"></i>
		  </li>
		  <li class="list-group-item s_y" m="rm" style="cursor:pointer;" data-toggle="tooltip" data-placement="top" title="요구사항관리 파일만 보기">
				요구사항관리
		    <i class="pull-right fa fa-user text-default" style="font-size:14px"></i>
		  </li>
		  <li class="list-group-item s_y" m="bom" style="cursor:pointer;" data-toggle="tooltip" data-placement="top" title="BOM 파일만 보기">
				BOM
		    <i class="pull-right fa fa-paperclip text-default" style="font-size:14px"></i>
		  </li>
		  <?php if($this->session->userdata('userauth') == 'admin'){ ?>
		  <li class="list-group-item s_y" m="trash" style="cursor:pointer;" data-toggle="tooltip" data-placement="top" title="삭제된 파일만 보기">
				휴지통
		    <i class="pull-right fa fa-trash text-default" style="font-size:14px"></i>
		  </li>
		  <?php } ?>
		</ul>
		
		<div class="position-relative">
			<?php if($this->session->userdata('userauth') == 'admin'){ ?>
			<button type="button" class="btn btn-default btn_add_folder btn-xs position-absolute" style="right:15px;top:0" data-toggle="tooltip" data-placement="left" title="폴더관리">
				<span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
			</button>
			<?php } ?>	
			<div class="p-20" style="margin-right:37px;padding-top:0!important">
			<?php
				$df = disk_free_space("/var/www");
				$dt = disk_total_space("/var/www");
				$du = $dt - $df;
				$dp = sprintf('%.2f',($du / $dt) * 100);
				$df = formatSize($df);
				$du = formatSize($du);
				$dt = formatSize($dt);
				function formatSize( $bytes )
				{
				        $types = array( 'B', 'KB', 'MB', 'GB', 'TB' );
				        for( $i = 0; $bytes >= 1024 && $i < ( count( $types ) -1 ); $bytes /= 1024, $i++ );
				                return( round( $bytes, 2 ) . " " . $types[$i] );
				}
		   	
		   	//경고 스타일
		   	if($dp >= 90){
		   		$back_style_color = 'red';
		   	}else if($dp >= 70){
		   		$back_style_color = 'orange';
		   	}else{
		   		$back_style_color = '#909090';
		   	}
		   	
			?>
			
				<div class="progress">
					
				  <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $dp?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $dp?>%; background:<?php echo $back_style_color;?>">
				    <span class="sr-only"><?php echo $dp?>%</span>
				  </div>
				</div>
				<ul style="font-size:11px;padding-top:5px">
					<li><?php echo $dt;?> 중 <?php echo $df;?> 사용가능</li>
					<?php if($dp >= 90){?>
						<li style="color:red"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> 파일을 정리하세요.</li>
					<?php }else if($dp >= 70){ ?>
						<li style="color:orange"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> 파일을 정리하세요.</li>
					<?php } ?>
				</ul>
			
			</div>
			<div id="jstree-tree-left" class="m-15" style="overflow: hidden;">
				<!--트리-->
			</div>
		</div>
		
</div>
		
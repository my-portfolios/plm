<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script>
$(function(){
	//new tree
	//트리콜
	var jstreeObj = $('#jstree-tree-left');
	jstreeObj.jstree({
		'core' : {
			"dblclick_toggle" : false,
			'multiple' : false,
			'data' : function (node, cb) {
				$.ajax({
					"url" : "/index.php/Common/getTreeJson",
					"data" : { "id" : node.id },//ff=folder/file/all
					type: 'POST',
					"success": function(data) {
						var data = $.parseJSON(data);
						cb(data);
					}
				});
			}
		}
	}).on('loaded.jstree', function(e,data) {
		if($.cookie('local') != undefined && $.cookie('local') != ''){
			jstreeObj.jstree('select_node', $.cookie('local'));	
			jstreeObj.jstree('open_node', $.cookie('local'));
		}else{
			jstreeObj.jstree('select_node', 'PLM');
		}
		/*
		$.each(jstreeObj.find('.jstree-ocl'),function(i,v){
			if(!$(v).parent().hasClass('jstree-leaf')){
				$(v).next().find('.folderImg').attr('style','background-position:-44px -26px!important');
			}
		});
		*/

	});
  
	jstreeObj.bind(
		"select_node.jstree", function(evt, data){
			$.cookie('se','1',{ path: '/' });
			$('.list-group-c .s_y').css('background','#fff');
			$.cookie('detailYn','N',{ path: '/' });
			<?php if(uri_string() == 'pdm/Main'){?>
			searchData();//검색데이타저장		
			<?php }?>	
			var loca = false;
			var id = data.node.id;
			var parent = data.node.parent;
			$("[name=LM]").removeAttr('checked');
			$.cookie('local',data.node.id,{ path: '/' });

			<?php if(uri_string() == 'pdm/Main'){?>
	      //	$('#loading').modal('show');
			
			$('.location .locas').html(jstreeObj.jstree().get_path(jstreeObj.jstree("get_selected", true)[0], ' > '));
			
			var baseUrl = window.location.href.split('#')[0];
			var baseHash = window.location.href.split('#')[1];
			document.location = "#"+data.node.id;
			fn_getChildren($.cookie('local'));
			<?php }else{ ?>
			$(document).on('click','#jstree-tree-left a.jstree-anchor',function(){
				location.href="/";
			});
			<?php }?>
			jstreeObj.jstree('open_node', $.cookie('local'));
			$("#searchtext").val('');
			$("#setype").prop('checked',false);
			if($('.searchDetails').css('display') == 'block'){
				$('.searchDetail').click();
			}
			
		}
	);
	/*
	jstreeObj.bind("open_node.jstree", function(evt, data){
		$.each(jstreeObj.find('.jstree-ocl'),function(i,v){
			if(!$(v).parent().hasClass('jstree-leaf')){
				$(v).next().find('.folderImg').attr('style','background-position:-44px -26px!important');
			}
		});
	});
	*/
	/* 로드 시 모든 다이얼로그 삭제 */
	$('.ui-dialog').each(function(i,v){
		v.remove();
	});
	
	
	/* 폴더관리 */
	$(document).on('click','.btn_add_folder',function(){
		$('#pop_folder').modal('show');
    $('#pop_folder').on('shown.bs.modal', function() {
    	 getFolderTree();
		});
	});
	

	
	/* 업로드 */
	$(document).on('click','.btn_add_file',function(){
		$('.leftMenuFrm').submit();
	});
	

	$(document).on('click','.list-group-c .s_y',function(){
		$('#jstree-tree-left').jstree("close_all");
		$('#jstree-tree-left').jstree("deselect_all");
		<?php if(uri_string() == 'pdm/Main'){?>
		searchData();//검색데이타저장
		$('#searchtext').val('');
		$('[name=sdate]').val('');
		$('[name=edate]').val('');
		$('[name=keyword]').val('');
		$('#setype').prop('checked',false);
		var d = $(this).attr('d');
		if($('.searchDetails').css('display') == 'none'){
			$('.searchDetail').click();
		}
		$('.searchDetails [name=docType]').find('[value="'+d+'"]').prop('selected',true);
			$('.searchFile').click();
			$('.list-group-c .s_y').css('background','#fff');
			$(this).css('background','#f7f7f7');
			$('.locas').text('검색');
			$.cookie('local','RM',{ path: '/' });
		<?php }else{ ?>
			var d = $(this).attr('d');
			$.cookie('docType',d,{ path: '/' });
			$.cookie('detailYn','Y',{ path: '/' });
			location.href='/';
		<?php } ?>
	});
	
});

function hash(){
		var baseHash = window.location.href.split('#')[1];
		$('#jstree-tree-left').jstree("deselect_all");
		$('#jstree-tree-left').jstree('select_node', baseHash);
		if($('#jstree-tree-left').jstree('select_node', baseHash) == false){
			location.href='/';
		}
}


</script>
<style>
	#jstree-tree-left .jstree-container-ul.jstree-children{
		margin-left: -24px
	}
</style>
<?php include($_SERVER["DOCUMENT_ROOT"]."/application/views/pdm/Pop_folder.php"); ?>	<!-- 폴더관리 팝업 -->
<form class="leftMenuFrm" name="leftMenuFrm" action="<?php echo site_url()?>/pdm/Upload" method="POST">
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
		  <li class="list-group-item s_y" d='M' style="cursor:pointer;<?php if($this->input->cookie('docType') == 'M'){ echo 'background:#f7f7f7';}?>">
				공유받은파일
		    <i class="pull-right fa fa-share-alt text-default" style="font-size:14px"></i>
		  </li>
		  <li class="list-group-item s_y" d='IMAGE' style="cursor:pointer;<?php if($this->input->cookie('docType') == 'IMAGE'){ echo 'background:#f7f7f7';}?>">
				이미지
		    <i class="pull-right fa fa-file-photo-o text-default" style="font-size:14px"></i>
		  </li>
		  <li class="list-group-item s_y"  d='MOVIE' style="cursor:pointer;<?php if($this->input->cookie('docType') == 'MOVIE'){ echo 'background:#f7f7f7';}?>">
				동영상
		    <i class="pull-right fa fa-file-movie-o text-default" style="font-size:14px"></i>
		  </li>
		  <li class="list-group-item s_y"  d='TXT' style="cursor:pointer;<?php if($this->input->cookie('docType') == 'TXT'){ echo 'background:#f7f7f7';}?>">
				문서
		    <i class="pull-right fa fa-file-text-o text-default" style="font-size:14px"></i>
		  </li>
		  <li class="list-group-item s_y"  d='RM' style="cursor:pointer;<?php if($this->input->cookie('docType') == 'RM'){ echo 'background:#f7f7f7';}?>">
				요구사항관리
		    <i class="pull-right fa fa-user text-default" style="font-size:14px"></i>
		  </li>
		  <li class="list-group-item s_y"  d='TRASH' style="cursor:pointer;<?php if($this->input->cookie('docType') == 'TRASH'){ echo 'background:#f7f7f7';}?>">
				휴지통
		    <i class="pull-right fa fa-trash text-default" style="font-size:14px"></i>
		  </li>
		</ul>
		
		
		<div class="position-relative">
			<button type="button" class="btn btn-default btn_add_folder btn-xs position-absolute" style="right:15px;top:0" data-toggle="tooltip" data-placement="left" title="폴더관리">
				<span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
			</button>
			
			<div id="jstree-tree-left" class="m-15" style="overflow: hidden;">
				<!--트리-->
			</div>
		</div>
		
</div>
		
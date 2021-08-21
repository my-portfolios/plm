<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- 폴더관리 팝업 -->
<script>
	
	function noItem(){
		bootbox.alert({
				size:'small',
				message : '선택된 폴더가 없습니다.',
			  buttons: {
		        ok: {
		            label: '확인',
		            className: "btn-<?php echo $this->config->item($this->uri->segment(1).'Color')?>"
		        }
		    }
			});
			return preventDefaultAction(false);
	}
	
	$(function(){
		
		/* 폴더추가 */
		$(document).on('click','.addFolder',function(){
			
			var sel = $('#jstree-tree-pop-folder').jstree(true).get_selected();
			if(sel.length){
			
			var a = $('#jstree-tree-pop-folder').jstree(true).create_node(sel);
			
			$('#jstree-tree-pop-folder').jstree("deselect_all");
			$('#jstree-tree-pop-folder').jstree('select_node', a);
			$('#jstree-tree-pop-folder').jstree('edit', a);
			$('#'+a).find('input').val('새폴더').width(100);
			}else{
				noItem();
			}
		});
		
		/* 이름변경 */
		$(document).on('click','.renameFolder',function(){
			var sel = $('#jstree-tree-pop-folder').jstree(true).get_selected();
			if(sel.length){
			$('#jstree-tree-pop-folder').jstree(true).edit(sel);
			}else{
				noItem();
			}	
		});
		
		/* 폴더삭제 */
		$(document).on('click','.deleteFolder',function(){
			var sel = $('#jstree-tree-pop-folder').jstree(true).get_selected();
			if(sel.length){
			
				bootbox.confirm({
					size: "small",
					message: "폴더를 삭제 하시겠습니까? ",
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
							var sel_node = $('#jstree-tree-pop-folder').jstree(true).get_node(sel);
							sel_node.children_d.push(sel[0]);
							
							var delArr = [];
							delArr.push(sel_node.children_d);
							
							fn_chkChildren(delArr,sel);
						}
					}
				});	
			
			}else{
				noItem();
			}
		});
		
	});
	
	//하위 폴더나 파일이 있는지 체크
	function fn_chkChildren(delArr,sel){
		var len = delArr[0].length;
		
		if(len > 1){	//하위 폴더가 있는 경우
			
			bootbox.alert({
				size:'small',
				message : '하위 폴더 및 파일이 존재합니다. 삭제 후 시도해주세요.',
				buttons: {
					ok: {
						label: '확인',
						className: "btn-<?php echo $this->config->item($this->uri->segment(1).'Color')?>"
					}
				}
			});
			return preventDefaultAction(false);
			
		}else if(len == 1){	//하위 파일이 있는 경우
			
			var data = {
				 "parent_id": sel[0]
			};
			
			$.ajax({
				type: 'post',
				dataType: 'json',
				url: '<?php echo base_url();?>index.php/pdm2/Pop_folder/chkChildFile',
				data: data,
				async:false,
				success: function (data) {
					if(data.cnt > 0){
						bootbox.alert({
							size:'small',
							message : '하위 폴더 및 파일이 존재합니다. 삭제 후 시도해주세요.',
							buttons: {
								ok: {
									label: '확인',
									className: "btn-<?php echo $this->config->item($this->uri->segment(1).'Color')?>"
								}
							}
						});
					}else{
						fn_deleteFolder(delArr,sel);
					}
				},
				error: function (request, status, error) {
					console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
				}
			});
			
		}
	}
	
	function fn_chkChildFile(parent_id){
		
		var result = false;
		
		
		
		return result;
	}
	
	/* 드래그해서 위치이동 */
	function fn_dragMove( parent_id , id , path ){
		
		var data = {
			 "parent_id": parent_id
			,"id"		: id
			,"path"		: path
		};
		$.ajax({
			type: 'post',
			dataType: 'json',
			url: '<?php echo base_url();?>index.php/pdm2/Pop_folder/moveFolder',
			data: data,
			success: function (data) {
				$('#jstree-tree-left').jstree(true).refresh();
				$('#jstree-tree-pop-folder').jstree(true).refresh();
			},
			error: function (request, status, error) {
				console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
			}
		});
		
	}
	
	/* 이름변경 */
	function fn_updateFolder( pfd_id , text , path){
		
		var data = {
			 "pfd_id"	: pfd_id,
			"text"		: text,
			"path" 	: path
		};
		
		
		
		$.ajax({
			type: 'post',
			dataType: 'json',
			url: '<?php echo base_url();?>index.php/pdm2/Pop_folder/updateFolder',
			data: data,
			success: function (data) {
				$('#jstree-tree-left').jstree(true).refresh();
			},
			error: function (request, status, error) {
				console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
			}
		});
		
	}
	
	/* 폴더추가 */
	function fn_addFolder( parent_id , text ){
		
		var data = {
			 "parent_id"	: parent_id
			,"text"		: text
		};
		
		$.ajax({
			type: 'post',
			dataType: 'json',
			url: '<?php echo base_url();?>index.php/pdm2/Pop_folder/addFolder',
			data: data,
			success: function (data) {
				
				if(data){
					$('#jstree-tree-left').jstree(true).refresh();
					$('#jstree-tree-pop-folder').jstree(true).refresh();
					setTimeout(function(){
						$('#jstree-tree-pop-folder').jstree('select_node', data);
					},100);
				}
				
			},
			error: function (request, status, error) {
				console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
			}
		});
	}
	
	/* 폴더삭제 */
	function fn_deleteFolder(delArr,sel){
		
		var data = {
			"delArr"	: delArr
		};
		
		$.ajax({
			type: 'post',
			dataType: 'json',
			url: '<?php echo base_url();?>index.php/pdm2/Pop_folder/deleteFolder',
			data: data,
			success: function (data) {
				
				if(data){
					$('#jstree-tree-pop-folder').jstree("delete_node", sel);
					$('#jstree-tree-left').jstree(true).refresh();
					$('#jstree-tree-pop-folder').jstree(true).refresh();
				}
				
			},
			error: function (request, status, error) {
				console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
			}
		});
		
	}
	
	/* 처음 트리 불러오기 */
	function getFolderTree(){
		
		var jstreeObj = $('#jstree-tree-pop-folder');
		jstreeObj.jstree("destroy");
		jstreeObj.jstree({
			'core' : {
				"animation" : 0,
				"check_callback" : true,
				'force_text' : true,
			//	"themes" : { "stripes" : true },
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
				,
				worker : true
			},
			dnd: {
				is_draggable: function (nodes) {
					var i = 0,
						j = nodes.length;
					for (; i < j; i++) {
						if (this.get_node(nodes[i], true).hasClass('no_dragging')) {
							return false;
						}
					}
					return true;
				}
			},
			plugins : ['contextmenu','dnd']
			}).bind("move_node.jstree", function(e, data) {
				
				var path = [];
				var pfd_id = [data.node.id];
				path.push(data.instance.get_path(data.node.id,'>'));
				$.each(data.node.children_d,function(i,v){
					path.push(data.instance.get_path(v,'>'));
					pfd_id.push(v);
				});
				
				fn_dragMove(data.parent , pfd_id , path);
				
				//fn_dragMove(data.parent , data.node.id);
			}).on('loaded.jstree', function() {
		    jstreeObj.jstree('open_all');
		  }).on("rename_node.jstree",function(e, data) {
			
			if(data.text == ''){
				$('#jstree-tree-pop-folder').jstree("delete_node", data.node.id);
			}
			if( data.old == '' ){	//create
				fn_addFolder(data.node.parent,data.text);
			}else{					//update
				var path = [];
				var pfd_id = [data.node.id];
				path.push(data.instance.get_path(data.node.id,'>'));
				$.each(data.node.children_d,function(i,v){
					path.push(data.instance.get_path(v,'>'));
					pfd_id.push(v);
				});
				fn_updateFolder(pfd_id,data.text,path);
			}
		});
	}
	
</script>
<div class="modal fade" id="pop_folder" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title" id="myModalLabel">
			폴더관리&nbsp;
			<button class="renameFolder btn btn-default btn-xs">이름변경</button>
			<button class="addFolder btn btn-default btn-xs">폴더추가</button>
			<button class="deleteFolder btn btn-default btn-xs">폴더삭제</button>
		</h4>
	  </div>
	  <div class="modal-body" style="height:500px;overflow:auto">
			<div id="jstree-tree-pop-folder">
				<!--트리-->
			</div>
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
	  </div>
	</div>
  </div>
</div>

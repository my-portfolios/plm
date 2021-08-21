<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- 폴더관리 팝업 -->
<script>
	function fn_getFileMoveTree(chkArr){
		
		if($.cookie('local') != undefined){	}
		
		var jstreeObj = $('#jstree-tree-pop-fileMove');
		jstreeObj.jstree("destroy");
		jstreeObj.jstree({
			'core' : {
				"animation" : 0,
				"check_callback" : true,
				'force_text' : true,
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
				,
				worker : true
			},
			"types" : {
				"plugins" : ["changed"]
			}
		}).on("changed.jstree", function (e, data) {
			
			bootbox.confirm({
	    	size: "small",
		    message: "<span style='color:blue'>"+data.instance.get_path(data.node,' > ')+ "</span><br />위치로 이동 하시겠습니까? ",
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
			    	var selectedNode = data.selected[0];
			    	var path = data.instance.get_path(data.node,' > ');
						fn_folderSelect(chkArr,selectedNode,path);
			  	}
		    }
			});	
			
		}).on('loaded.jstree', function() {
			jstreeObj.jstree('open_all');
		});
	}

	function fn_folderSelect(chkArr,parent_id,path){
		
		var data = {
			 "parent_id": parent_id
			,"chkArr"	: chkArr.toString()
			,"path"	: path
		};
		
		$.ajax({
			type: 'post',
			dataType: 'json',
			url: '<?php echo base_url();?>index.php/pdm/Pop_fileMove/move',
			data: data,
			success: function (data) {
				
				bootbox.alert({
					size	:'small',
					message :'이동하였습니다.'
				});
				
				$.cookie('local',parent_id,{ path: '/' });
				$('#pop_fileMove').modal('hide');
				$('#jstree-tree-left').jstree('select_node', parent_id);
				$('#jstree-tree-left').jstree(true).refresh();
			},
			error: function (request, status, error) {
				console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
			}
		});
		
	}
</script>
<div class="modal fade" id="pop_fileMove" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title" id="myModalLabel">선택이동</h4>
	  </div>
	  <div class="modal-body" style="height:500px;overflow:auto">	  
			<div id="jstree-tree-pop-fileMove">
				<!--트리-->
			</div>
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
	  </div>
	</div>
  </div>
</div>

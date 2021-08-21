<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- 폴더관리 팝업 -->
<script>
	/* 처음 트리 불러오기 */
	function getFolderSearchTree(){
		
		if($.cookie('local') != undefined){	}
		
		var jstreeObj = $('#jstree-tree-pop-folderSearch');
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
			var selectedNode = data.selected[0];
			var path = $('#jstree-tree-pop-folderSearch').jstree(true).get_path(selectedNode," > ");
			fn_folderSelect(selectedNode , path);
		}).on('loaded.jstree', function() {
			jstreeObj.jstree('open_all');
		});
	}
	
	/* 카테고리 선택 */
	function fn_folderSelect(selectedNode , path){
		var frm = $('#pop_folderSearch').find('#parent_frm').val();
		$('#'+frm).find('#PFD_ID').val(selectedNode);
		$('#'+frm).find('#PF_PATH').val(path);
		$('#pop_folderSearch').find('#parent_frm').val('');
		$('#pop_folderSearch').modal('hide');
	}
	
</script>
<div class="modal fade" id="pop_folderSearch" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<input type="hidden" id="parent_frm" name="parent_frm" value=""/>
  <div class="modal-dialog" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title" id="myModalLabel">카테고리 선택</h4>
	  </div>
	  <div class="modal-body" style="height:500px;overflow:auto">	  
			<div id="jstree-tree-pop-folderSearch">
				<!--트리-->
			</div>
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
	  </div>
	</div>
  </div>
</div>

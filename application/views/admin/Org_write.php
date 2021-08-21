<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$PageNm = 'admin';//seg 1
$PageType = 'Org_write';//seg 2

/*수정이면*/
if($list){
	$ORG_ID   = $list->ORG_ID;
	$ORG_NM   = $list->ORG_NM;
	$ORG_YN   = $list->ORG_YN;
	$ORG_DATA  = $list->ORG_DATA;
	$INS_DT  = $list->INS_DT;
}else{
	$ORG_ID = '';
	$ORG_NM = '';
	$ORG_YN   = '';
	$ORG_DATA = '';
	$INS_DT = '';
}
//첨부파일 사용유무
$fileYns = 'N';

?>

<script>
$(window).load(function(){
	//아이콘 넣기
	$.each($('.extIcon'),function(i,v){
		var t = $(v).text();
		$(v).html(extIcon(t,'y'));
	});
});

$(function(){
	
	/*color*/
	$("#colorp1").colorpicker({
    color: "#06ba9a",
    strings: '색상선택,기본색상,웹색상,색상선택,뒤로,내역,내역이 없습니다.'
	});
	$("#colorp2").colorpicker({
    color: "#06ba9a",
    strings: '색상선택,기본색상,웹색상,색상선택,뒤로,내역,내역이 없습니다.'
	});
	
	/* 취소 */
	$(document).on('click','.btn_cancel',function(){
		history.back();
	});
	//추가 타입변경
	$('[name=rds]').change(function(){
		$('#new-nodelist').html('');
		$('#PF_EMP_TEXT').val('');
		$('.sdr_in1').val('');
		$('.sdr_in2').val('');
		$('.sdr_in3').val('');
		var v =	$(this).val();
		if(v == '1'){
			$('.sde').show();
			$('.sdr').hide();
		}else{
			$('.sde').hide();
			$('.sdr').show();
		}
	});
});


/* 저장 */
function fn_save(){
	
	if ($('#frm_write').validator('validate').has('.has-error').length === 0) {
		
		//로딩 구현
		$('#loading').modal('show');
		
		<?php if($fileYns == 'Y'){?>
		var fd = new FormData();
		$.each($('input[id=PF_FILE]')[0].files,function(i,v){
			fd.append('file', v);
		}); 
	  
	  $.ajax({
		  url: '',
		  type: 'POST',
		  processData: false,
		  data: fd,
		  xhr: function() {
			  var xhr = $.ajaxSettings.xhr();
			  xhr.upload.addEventListener('progress', function(ev) {
				  $('#loading').find('.progress-bar').css('width',(ev.loaded/(ev.total/100))+'%');
			  }, false);

			  return xhr;
		  },
		  beforeStart: function() {
				$('#loading').find('.progress-bar').css('width','0%');
		  },
		  success: function() {
		  }
	  });
		<?php } ?>
		
		var view_id ="<?php echo $this->input->get('id'); ?>";
		if(view_id == ""){
			$('#frm_write').attr('action','<?php echo base_url();?>index.php/<?php echo $PageNm?>/<?php echo $PageType?>/save');
		}else{
			$('#frm_write').attr('action','<?php echo base_url();?>index.php/<?php echo $PageNm?>/<?php echo $PageType?>/upd');
		}
		$('#frm_write').submit();
	}else{
		//필수항목
	}
}
</script>

<div id="wp_right">
	
	<div class="grid_area">
	<!-- 작성,수정 -->
	<form id="frm_write" name="frm_write" class="p-20" data-toggle="validator" role="form" action="" method="post" enctype="multipart/form-data" >
		<input type="hidden" id="ORG_ID" name="ORG_ID" value="<?php echo $ORG_ID;?>"/>
		<div class="gray_border_bottom pb-20">
		  <h3>조직도정보 <small>Write</small></h3>
		</div>
		<br />
	  <div class="form-group required">
	    <label for="">조직도명<span class="req-text" title="필수입력">*</span></label>
	    <input type="text" class="form-control" id="ORG_NM" name="ORG_NM" placeholder="카테고리명을 입력해주세요." value="<?php echo $ORG_NM;?>" required>
	    <span class="help-block with-errors"></span>
	  </div>
	  
	  <div class="form-group required">
	    <label for="">사용유무<span class="req-text" title="필수입력">*</span></label>
	    <br />
	    <label><input type="radio" id="ORG_YN1" name="ORG_YN" value="N" <?php if($ORG_YN == '' || $ORG_YN == 'N') { ?>checked<?php } ?> required> 사용안함</label>
	    <label><input type="radio" id="ORG_YN2" name="ORG_YN" value="Y" <?php if($ORG_YN == 'Y') { ?>checked<?php } ?> required> 사용함</label>
	    <span class="help-block with-errors"></span>
	  </div>
	  
	  <div class="form-group">
		  <div id="chart-container"></div>
		</div>
		
		<div id="edit-panel" style="background:#f9f9f9;border:1px solid #ededed;padding:20px;margin-bottom:10px;border-radius:3px">
			조직도 EDIT(실제 저장은 위 차트만 저장됩니다.)<br /><br />
			<div class="form-group">
			
		  	<!--
		    <span id="chart-state-panel" class="radio-panel">
		      <input type="radio" name="chart-state" id="rd-view" value="view"><label for="rd-view">View</label>
		      <input type="radio" name="chart-state" id="rd-edit" value="edit" checked="true"><label for="rd-edit">Edit</label>
		    </span>
		    -->
		    <label class="selected-node-group">선택된 항목</label>
		    <input type="text" id="selected-node" class="selected-node-group form-control" readonly="readonly">
		  </div>
		  <div class="form-group">
		    <label>새로운 항목</label>
		    &nbsp;
		    <label for="rds1"><input id="rds1" name="rds" type="radio" value="1" checked /> 선택추가</label>
		    <label for="rds2"><input id="rds2" name="rds" type="radio" value="2" /> 직접추가</label>
		    
		    <div class="pull-right" style="position:relative;top:-5px">
		    	<!--
		    	<button type="button" id="btn-add-input" class="btn-default btn btn-xs">항목추가</button>
		    	<button type="button" id="btn-remove-input" class="btn-default btn btn-xs">삭제</button>
		    	-->
		    </div>
		    <ul id="new-nodelist" style="position:absolute;left:-10000px;top:-10000px">
		    </ul>
		    
		    <div class="form-group sdr" style="display:none">
		    	<label for="">직급 / 이름 / 연락처<span class="req-text" title="필수입력">*</span></label><br />
				  <input class="form-control sdr_in1" style="width:100px;display:inline-block" placeholder="직급" />
				  <input class="form-control sdr_in2" style="width:100px;display:inline-block" placeholder="이름" />
				  <input class="form-control sdr_in3" style="width:200px;display:inline-block" placeholder="연락처" />
				</div>
		    
		    <div class="input-group sde" style="margin-bottom:15px">
				  <input id="PF_EMP_TEXT" name="PF_EMP_TEXT" type="text" data-emp="emp_input" class="form-control" readonly autocomplete="off" onkeypress="return false;" placeholder="오른쪽 검색버튼을 이용해 검색해주세요." aria-label="..." value="">
				  <div class="input-group-addon btn_search_emp">
				    <span class="glyphicon glyphicon-search"></span>
				  </div>
				</div>
				
				<div class="form-group">
					<label>색상</label>
					<br />
					<div style="position:relative;width: 100px">
				   	<input id="colorp1" class="form-control colorPicker evo-cp0" />
				  </div>
				</div>
		    
		    <span id="node-type-panel" class="radio-panel">
		    	<div class="radio">
		      	<label for="rd-parent"><input type="radio" name="node-type" id="rd-parent" value="parent">최상위에 추가(root)</label>
		      </div>
		      <div class="radio">
		      	<label for="rd-child"><input type="radio" name="node-type" id="rd-child" value="children">선탠된 하위에 추가(child)</label>
		      </div>
		      <div class="radio">
		      	<label for="rd-sibling"><input type="radio" name="node-type" id="rd-sibling" value="siblings">같은 라인에 추가(Sibling)</label>
		    	</div>	
		    </span>
		    <div class="text-center">
		    	<button type="button" id="btn-add-nodes" class="btn-default btn btn-xs">추가</button>
		    	<!--
		    	<button type="button" id="btn-delete-nodes" class="btn-default btn btn-xs">선택삭제</button>
		    	<button type="button" id="btn-reset" class="btn-default btn btn-xs">리셋</button>-->
		    </div>
		  </div>
		</div>
		
		<div class="form-group">

	    <textarea name="ORG_DATA" id="ORG_DATA" style="position:absolute;top:-10000px;left:-10000px"><?php echo $ORG_DATA;?></textarea>
	  </div>
	  
	  <?php if($fileYns == 'Y'){//첨부파일 사용이면 Y : N?>
	  <div class="form-group">
	    <label for="">파일</label>
	    <input type="file" id="PF_FILE" name="PF_FILE[]" multiple>
	    
	    <!--첨부파일-->
	    <?php 
				$i = 0;
				if( $fileList != null ){
					
					foreach( $fileList as $data ){
						$i++;
			?>
			<br/>
			삭제
			<input type="checkbox" name="FILE_DEL[]" value="<?php echo $data->FILELIST_ID; ?>"/>
			<span class="extIcon"><?php echo $data->PF_FILE_EXT; ?></span>
			<a href="<?php echo site_url()?>/pdm/Upload_view/fileDownload?tempName=<?php echo $data->PF_FILE_TEMP_NM ?>&fileName=<?php echo $data->PF_FILE_REAL_NM ?>">
				<?php echo $data->PF_FILE_REAL_NM ?>
			</a>
			<?php 
					}
				} 
				if($i == 0) echo '<div class="mt-10">첨부된 파일이 없습니다.</div>';
			?>
	    <!--첨부파일 끝-->
	    
	    <span class="help-block with-errors"></span>
	  </div> 
	  <?php } ?>
	  
	 
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
	  <div class="text-center gray_border_top">
			<button type="button" class="btn btn_save btn-<?php echo $this->config->item($PageNm.'Color');?> btn-sm mt-10 ">
				저장
			</button>
			<button type="button" class="btn btn_cancel btn-default btn-sm mt-10 ">
				취소
			</button>
		</div>
	  
	</form>
	
</div>
</div>


<!--수정-->
	<div class="modal" id="chartModifys" tabindex="-1" role="dialog">
	  <div class="modal-dialog" role="document" style="width: 452px">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">정보</h4>
	      </div>
	      <div class="modal-body" style="padding:0">
	      	<div class="p-20 orgChartModifyWp">
	      		<div class="mb-10">
	      			USER선택으로 선택된 항목의 프로필 이미지는 수정하실 수 없습니다.<br />
	      			ex) 삭제 후 재생성
	      		</div>
	      		<label for="">직급 / 이름 / 연락처<span class="req-text" title="필수입력">*</span></label><br />
		      	<input type="text" class="j1 form-control mb-5" style="width: 100px;display:inline-block" placeholder="직급" />
		      	<input type="text" class="j2 form-control mb-5" style="width: 100px;display:inline-block" placeholder="이름"/>
		      	<input type="text" class="j3 form-control mb-5" style="width: 200px;display:inline-block" placeholder="연락처" />
		      	<input type="hidden" class="j4" />
		      	<div>
							<label>색상</label>
							<br />
							<div style="position:relative;width: 100px">
						   	<input id="colorp2" class="form-control colorPicker evo-cp0" />
						  </div>
						</div>
		      	
	      	</div>
	      </div>
	      <div class="modal-footer">
	      	<button type="button" class="btn btn-chartModfy btn-<?php echo $this->config->item($PageNm.'Color');?>">저장</button><button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->


<!--차트-->
<style>
	#chart-container{
		min-height:200px;
		max-height: 350px;
		box-sizing: border-box;
    display: inline-block;
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    background-image: linear-gradient(90deg, rgba(95, 95, 95, 0.15) 10%, rgba(0, 0, 0, 0) 10%), linear-gradient(rgba(95, 95, 95, 0.15) 10%, rgba(0, 0, 0, 0) 10%);
    background-size: 10px 10px;
    border: 1px dashed rgba(0,0,0,0);
    padding: 20px;
    width:100%;
    overflow:auto;
	}
	#chart-container .orgchart{width:100%;min-height:200px;background:none}
	.edge { display: none!important; }
	#edit-panel.edit-state>:not(#chart-state-panel) { display: none; }
	#edit-panel.edit-parent-node .selected-node-group { display: none; }
	/*#edit-panel.edit-parent-node button:not(#btn-add-nodes) { display: none; }*/
	#edit-panel.edit-parent-node .btn-inputs { display: none; }
	.orgImg img{
		height:50px!important
	}
	/*color*/
	.evo-pointer{position:absolute;right:-24px;top:0;height: 34px;padding: 6px 12px;border-radius: 4px;border-bottom-left-radius: 0;border-top-left-radius: 0}
	.evo-pop{
		width: 202px
	}
	.evo-pop span{
		font-size:11px !important;
	}
	.evo-color span{
		margin: -2px 0 4px 3px;
	}
	.evo-palette{
		    border-spacing: 3px 0;
	}
	.evo-palette-ie{
			border-spacing: 2px 0;
	}
	.evo-cp-wrap{
		width: 100px!important;
		padding:0!important;
		margin: 0!important
	}
	.evo-pointer{
		border:1px solid #ccc;
		height:34px
	}
</style>
<script type="text/javascript">
	
    $(function() {
    	
    $(document).on('click','.orgChartM',function(){//수정팝업 open
    	var a = $('.node.focused').attr('id');
    	var aSplit = a.split('^');
			$('#chartModifys').modal('show');
			if(aSplit[0] == ''){aSplit[0] = '-';}
			if(aSplit[1] == ''){aSplit[1] = '-';}
			if(aSplit[2] == ''){aSplit[2] = '-';}
			$('.orgChartModifyWp .j1').val(aSplit[0]);
			$('.orgChartModifyWp .j2').val(aSplit[1]);
			$('.orgChartModifyWp .j3').val(aSplit[2]);
			$('.orgChartModifyWp .j4').val(aSplit[3]);
			$('#colorp2').val(aSplit[4]);
			$('#colorp2').next().css('background-color',aSplit[4]);
    });
    
    $(document).on('click','.btn-chartModfy',function(){//수정 차트 적용
    	var a = $('.node.focused').attr('id');
    	var t = $('.node.focused');
    	var aSplit = a.split('^');
			var a_a = $('.orgChartModifyWp .j1').val();
			var a_b = $('.orgChartModifyWp .j2').val();
			var a_c = $('.orgChartModifyWp .j3').val();
			var a_d = $('.orgChartModifyWp .j4').val();
			if(a_a==''){a_a = '-';}if(a_b==''){a_b = '-';}if(a_c==''){a_c = '-';}
			t.attr('id',a_a+'^'+a_b+'^'+a_c+'^'+a_d+'^'+$('#colorp2').val());
			t.find('.orgImg .office').text(a_a);
			t.find('.orgImg .title').text(a_b);
			t.find('.orgImg .content').text(a_c);
			t.find('.orgImg .title').css('background',$('#colorp2').val());
			t.find('.orgImg .content').css('border-color',$('#colorp2').val());
			$('#chartModifys').modal('hide');
    });
    
    $(document).on('click','.orgChartD',function(){
    	
    	bootbox.confirm({
				size: "small",
				message: "삭제하시겠습니까?<br />삭제시 하위 존재하는 모든 항목이 삭제됩니다.", 
				buttons: {
			        confirm: {
			            label: '확인',
			            className: "btn-<?php echo $this->config->item($PageNm.'Color');?>"
			        },
			        cancel: {
			            label: '취소'
			        }
			    },
				callback: function(result){
					if(result == true){
						var $node = $('#selected-node').data('node');
			      if (!$node) {
			        bootbox.alert({
									size:'small',
									message : '삭제할 항목을 선택해 주세요.',
									buttons: {
										ok: {
											label: '확인',
											className: "btn-<?php echo $this->config->item($PageNm.'Color')?>"
										}
									}
								});
			        return;
			      } 
			      oc.removeNodes($node);
			      $('#selected-node').val('').data('node', null);
					}
				}
			});
    	
    });
    
		
		<?php if($ORG_DATA){?>
    var datascource = <?php echo $ORG_DATA?>;
  	<?php }else{ ?>
  	var datascource = {};
  	<?php } ?>
  	
  	var getId = function() {
      return (new Date().getTime()) * 1000 + Math.floor(Math.random() * 1001);
    };

		var nodeTemplate = function(data) {
			var name = data.name.split('^');
			var getIds = getId();
			if(name[3] != ''){
				getPic(name[3],'.orgPic_'+name[3]+getIds);
			}
			if(name[0] ==''){name[0] = '-';}
			if(name[1] ==''){name[1] = '-';}
			if(name[2] ==''){name[2] = '-';}
      return '<button data-toggle="tooltip" data-placement="top" title="수정" type="button" id="btn-add-input" class="btn-default btn btn-xs orgChartM"><i class="fa fa-pencil" aria-hidden="true"></i></button>'
      + ' <button data-toggle="tooltip" data-placement="top" title="삭제" type="button" id="btn-add-input" class="btn-default btn btn-xs orgChartD"><i class="fa fa-trash-o" aria-hidden="true"></i></button>'
      + '<div style="position:relative" class="orgImg"><div style="left: -33px;top: -15px;position:absolute;width:50px;height:50px;overflow:hidden;border-radius:100px" class="orgPic_'+name[3]+getIds+'"></div><div class="title" style="background:'+name[4]+'">'+name[0]+'</div><div class="content"><div>'+name[1]+'</div><div>'+name[2]+'</div></div>';
			/*
			var name = data.name.split('^');
      return '<div class="office">'+name[0]+'</div><div class="title">'+name[1]+'</div><div class="content">'+name[2]+'</div><div class="org_userid" style="display:none">'+name[3]+'</div>';
      */
    };
		

	
    var oc = $('#chart-container').orgchart({
      'data' : datascource,
      'chartClass': 'edit-state',
      'parentNodeSymbol': '',
      'draggable': true,
      'createNode': function($node, data) {
        $node[0].id = data.name + '^'+getId();
      },
      'nodeTemplate': nodeTemplate,
      'initCompleted' : function(){
      	$('[data-toggle="tooltip"]').tooltip();
      }
    });
    
  	if(!datascource.name){
  		$('.orgchart').remove();
  	}

    oc.$chartContainer.on('click', '.node', function() {
      var $this = $(this);
      $('#selected-node').val($this.find('.title').text()).data('node', $this);
    });

    oc.$chartContainer.on('click', '.orgchart', function(event) {
      if (!$(event.target).closest('.node').length) {
        $('#selected-node').val('');
      }
    });

    $('input[name="chart-state"]').on('click', function() {
      $('.orgchart').toggleClass('edit-state', this.value !== 'view');
      $('#edit-panel').toggleClass('edit-state', this.value === 'view');
      if ($(this).val() === 'edit') {
        $('.orgchart').find('tr').removeClass('hidden')
          .find('td').removeClass('hidden')
          .find('.node').removeClass('slide-up slide-down slide-right slide-left');
      } else {
        $('#btn-reset').trigger('click');
      }
    });

    $('input[name="node-type"]').on('click', function() {
      var $this = $(this);
      if ($this.val() === 'parent') {
        $('#edit-panel').addClass('edit-parent-node');
        $('#new-nodelist').children(':gt(0)').remove();
      } else {
        $('#edit-panel').removeClass('edit-parent-node');
      }
    });

    $('#btn-add-input').on('click', function() {
      $('#new-nodelist').append('<li><input type="text" class="new-node form-control" style="margin-bottom:3px"></li>');
    });

    $('#btn-remove-input').on('click', function() {
      var inputs = $('#new-nodelist').children('li');
      if (inputs.length > 1) {
        inputs.last().remove();
      }
    });

    $('#btn-add-nodes').on('click', function() {
    	var dcv = $('[name=rds]:checked').val();
    	var d = $('#colorp1').val();
    	if(dcv == 2){
    		var a = $('.sdr_in1').val();
    		var b = $('.sdr_in2').val();
    		var c = $('.sdr_in3').val();
    		if(a == ''){ a = '-';}if(b == ''){ b = '-';}if(c == ''){ c = '-';}
    		$('#new-nodelist').html('');
    		$('#new-nodelist').append('<li><input type="text" class="new-node form-control" readonly style="margin-bottom:3px" value="'+a+'^'+b+'^'+c+'^"></li>');
    	}
      var $chartContainer = $('#chart-container');
      var nodeVals = [];
      $('#new-nodelist').find('.new-node').each(function(index, item) {
        var validVal = item.value.trim();
        if (validVal.length) {
          nodeVals.push(validVal+'^'+d);
        }
      });
      var $node = $('#selected-node').data('node');
      if (!nodeVals.length) {
        bootbox.alert({
					size:'small',
					message : '새로운 항목을 입력해주세요.',
					buttons: {
						ok: {
							label: '확인',
							className: "btn-<?php echo $this->config->item($PageNm.'Color')?>"
						}
					}
				});
        return;
      }
      var nodeType = $('input[name="node-type"]:checked');
      if (!nodeType.length) {
        bootbox.alert({
					size:'small',
					message : '추가될 항목의 위치를 선택해주세요.',
					buttons: {
						ok: {
							label: '확인',
							className: "btn-<?php echo $this->config->item($PageNm.'Color')?>"
						}
					}
				});
        return;
      }
      if (nodeType.val() !== 'parent' && !$('.orgchart').length) {
         bootbox.alert({
					size:'small',
					message : '최상위 Root 를 먼저 만드셔야 합니다.',
					buttons: {
						ok: {
							label: '확인',
							className: "btn-<?php echo $this->config->item($PageNm.'Color')?>"
						}
					}
				});
        return;
      }
      if (nodeType.val() !== 'parent' && !$node) {
        bootbox.alert({
					size:'small',
					message : '차트에서 항목을 선택해 주세요.',
					buttons: {
						ok: {
							label: '확인',
							className: "btn-<?php echo $this->config->item($PageNm.'Color')?>"
						}
					}
				});
        return;
      }
      if (nodeType.val() === 'parent') {
        if (!$chartContainer.children('.orgchart').length) {// if the original chart has been deleted
          oc = $chartContainer.orgchart({
            'data' : { 'name': nodeVals[0] },
            'parentNodeSymbol': '',
            'draggable': true,
            'createNode': function($node, data) {
              $node[0].id = data.name + '^'+getId();
            },
            'nodeTemplate': nodeTemplate
          });
          oc.$chart.addClass('view-state');
        } else {
          oc.addParent($chartContainer.find('.node:first'), { 'name': nodeVals[0], 'id': getId() });
        }
      } else if (nodeType.val() === 'siblings') {
        if ($node[0].id === oc.$chart.find('.node:first')[0].id) {
          bootbox.alert({
						size:'small',
						message : 'Root에는 더이상 할 수 없습니다.',
						buttons: {
							ok: {
								label: '확인',
								className: "btn-<?php echo $this->config->item($PageNm.'Color')?>"
							}
						}
					});
          return;
        }
        oc.addSiblings($node, nodeVals.map(function (item) {
            return { 'name': item, 'relationship': '110', 'id': getId() };
          }));
      } else {
        var hasChild = $node.parent().attr('colspan') > 0 ? true : false;
        if (!hasChild) {
          var rel = nodeVals.length > 1 ? '110' : '100';
          oc.addChildren($node, nodeVals.map(function (item) {
              return { 'name': item, 'relationship': rel, 'id': getId() };
            }));
        } else {
          oc.addSiblings($node.closest('tr').siblings('.nodes').find('.node:first'), nodeVals.map(function (item) {
              return { 'name': item, 'relationship': '110', 'id': getId() };
            }));
        }
      }
    	$('[data-toggle="tooltip"]').tooltip();
    });

   

    $('#btn-reset').on('click', function() {
      $('.orgchart').find('.focused').removeClass('focused');
      $('#selected-node').val('');
      $('#new-nodelist').find('input:first').val('').parent().siblings().remove();
      $('#node-type-panel').find('input').prop('checked', false);
    });
		
		
		/* 글 저장 */
		$(document).on('click','.btn_save',function(){
			bootbox.confirm({
				size: "small",
				message: "저장하시겠습니까?", 
				buttons: {
			        confirm: {
			            label: '확인',
			            className: "btn-<?php echo $this->config->item($PageNm.'Color');?>"
			        },
			        cancel: {
			            label: '취소'
			        }
			    },
				callback: function(result){
					if(result == true){
						if($('#chart-container').find('.orgchart').size() > 0){
							
							var ns = $('.node').size();
							for(i=0;i<ns;i++){
								var nsid = $('.node').eq(i).attr('id');
								var nsidSplit = nsid.split('^');
								$('.node').eq(i).attr('id',nsidSplit[0]+'^'+nsidSplit[1]+'^'+nsidSplit[2]+'^'+nsidSplit[3]+'^'+nsidSplit[4]);
							}
							$('#ORG_DATA').text(JSON.stringify(oc.getHierarchy(), null, 2));
						}else{
							$('#ORG_DATA').text('');
						}
						fn_save();
					}
				}
			});
		});
		
		/* 유저검색 */
		//$(document).on('click','.btn_search_emp',function(){
		$('.btn_search_emp').click(function(){
			$('#pop_empSearch').modal('show');
			$('#pop_empSearch').find('option').eq(0).attr('selected','selected');
		});
		
  });
  </script>
  <?php include($_SERVER["DOCUMENT_ROOT"]."/application/views/com/Pop_empSearch.php"); ?>	<!-- 담당자검색 팝업 -->
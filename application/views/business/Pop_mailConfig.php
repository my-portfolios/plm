<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script>
$(function(){
	//저장
	$(document).on('click','#Pop_mailConfig .btn_save',function(){
		
		bootbox.confirm({
			size: "small",
			message: "저장하시겠습니까? ",
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
					$('#frm_pop_mailConfig').attr('action','<?php echo base_url();?>index.php/business/Pop_mailConfig/save');
					$('#frm_pop_mailConfig').submit();
				}
			}
		});	
		
	});
	
});

function fn_getMailConfigData(mc_id){
	var data = {
			"mc_id": mc_id
		};
  	$.ajax({
		type: 'post',
		dataType: 'json',
		url: '<?php echo base_url();?>index.php/business/Pop_mailConfig/getMailConfigData',
		data: data,
		async : false,
		success: function (data) {
			$('#Pop_mailConfig #frm_pop_mailConfig #EMP_ID').val(data.EMP_ID);
			$('#Pop_mailConfig #frm_pop_mailConfig #MC_HOST').val(data.MC_HOST);
			$('#Pop_mailConfig #frm_pop_mailConfig #MC_U_ID').val(data.MC_U_ID);
			$('#Pop_mailConfig #frm_pop_mailConfig #MC_U_PW').val('');
		},
		error: function (request, status, error) {
			console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
		}
	});
}
</script>
<div class="modal fade" id="Pop_mailConfig" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">외부메일 설정</h4>
			</div>
			<div class="modal-body" style="height:500px;overflow:auto">
				<form id="frm_pop_mailConfig" name="frm_pop_mailConfig" data-toggle="validator" role="form" action="" method="post">
					<!--<input type="text" id="MC_ID" name="MC_ID" value="" />-->
					<input type="hidden" id="EMP_ID" name="EMP_ID" value="">
					<div class="form-group required">
						<label for="MC_NM">이름 <span class="req-text" title="필수입력">*</span></label>
						<input type="text" id="MC_NM" name="MC_NM" class="form-control" placeholder="이름" value="">
						<span class="help-block with-errors"></span>
					</div>
					<div class="form-group required">
						<label for="MC_HOST">mail <span class="req-text" title="필수입력">*</span></label>
						<select id="MC_HOST" name="MC_HOST" class="form-control width_100px">
							<option value="gmail.com">gmail</option>
							<option value="naver.com">naver</option>
							<option value="daum.net">daum</option>
						</select>
						<span class="help-block with-errors"></span>
					</div>
					<div class="form-group required">
						<label for="MC_U_ID">ID <span class="req-text" title="필수입력">*</span></label>
						<input type="text" id="MC_U_ID" name="MC_U_ID" class="form-control" placeholder="ID" value="jeungwoong2@gmail.com">
						<span class="help-block with-errors"></span>
					</div>
					<div class="form-group required">
						<label for="MC_U_PW">PW <span class="req-text" title="필수입력">*</span></label>
						<input type="password" id="MC_U_PW" name="MC_U_PW" class="form-control" placeholder="PW" value="code140412##">
						<span class="help-block with-errors"></span>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn_save btn-<?php echo $this->config->item($this->uri->segment(1).'Color')?>">저장</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
			</div>
		</div>
	</div>
</div>


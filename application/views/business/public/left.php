<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script>
	var list = "list";//그리드 아이디
	$(function(){
		$(document).on('click','.btn_board_write',function(){
			location.href="<?php echo site_url();?>/board/Write?board=business";
		});
	});
	function fn_getExMailList(){
		var data = {
			
		};
		$.ajax({
			type: 'post',
			dataType: 'json',
			url: '<?php echo base_url();?>index.php/business/Main/getExMailList',
			data: data,
			async : false,
			success: function (data) {
				$('.ul_ex_mail_list').html('');
				var html = '';
				$.each(data,function(i,v){
					html += "<li id='exMailLi_"+v.MC_ID+"'>";
					html += "<span class='getMail' data='"+v.MC_ID+"'>"+v.MC_NM+"</span>";
					html += "<button type='button' onclick=fn_ex_mail_upd('"+v.MC_ID+"') class='btn btn-default btn-xs'>";
					html += "<span class='glyphicon glyphicon-pencil'></span>";
					html += "</button>";
					html += "<button type='button' onclick=fn_ex_mail_del('"+v.MC_ID+"') class='btn btn-default btn-xs'>";
					html += "<span class='glyphicon glyphicon-trash'></span>";
					html += "</button>";
					html += '</li>';
				});
				$('.ul_ex_mail_list').append(html);
			},
			error: function (request, status, error) {
				console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
			}
		});
	}
	
	//외부메일 수정
	function fn_ex_mail_upd(mc_id){
		$('#Pop_mailConfig #frm_pop_mailConfig #EMP_ID').val("<?php echo $_SESSION['userid'] ?>");
		fn_getMailConfigData(mc_id);
		$('#Pop_mailConfig').modal('show');
	}
	
	//외부메일 삭제
	function fn_ex_mail_del(mc_id){
		bootbox.confirm({
			size: "small",
			message: "삭제하시겠습니까? ",
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
					fn_ex_mail_delete(mc_id);
				}
			}
		});	
	}
	
	function fn_ex_mail_delete(mc_id){
		var data = {
			"mc_id" : mc_id
		};
		$.ajax({
			type: 'post',
			dataType: 'json',
			url: '<?php echo base_url();?>index.php/business/Main/exMailDel',
			data: data,
			async : false,
			success: function (data) {
				if(data){
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
					$('.ul_ex_mail_list').find('#exMailLi_'+mc_id).remove();
				}else{
					alert('오류');
				}
			},
			error: function (request, status, error) {
				console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
			}
		});
	}
	
	function detailSearchSeri(){
		if($('[id=frm_search]').length > 0){
			var frm = $('[id=frm_search]').serialize();
			var frmArr = frm.split('&');
			var obj = {};
			$.each(frmArr,function(i,v){
				var vArr = v.split('=');
				eval ("obj."+ vArr[0] + "= '"+vArr[1]+"'");
			});
			$("#"+list).setGridParam({postData: {"FA_YN":null,"FA_CHECK":null}});
			$("#"+list).setGridParam({
				postData:obj
			});
		}
	}	

</script>
<?php //$this->load->view('/business/Pop_mailConfig.php');
?>
<div id="wp_left" class="gray_border_right">
		
		<?php $this->load->view('/public/userInfo.php');?>
		
		<div class="text-center pt-20 ptb-20">
			<div class="btn-group btn-group-toggle" data-toggle="buttons">
				<button type="button" class="btn btn_board_write btn-dark mt-10">
					 게시글작성
				</button>
			</div>
		</div>
			
		
		
		<!--게시판사용시-->
		<ul class="list-group-c list-group" style="">
			<?php 
				$data['type'] = 'business';
				$this->load->view('/board/Left',$data);
			?>
		</ul>
		
		
</div>
		
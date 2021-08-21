<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script>

	$(function(){
		
		getBoardList();
	//	$('[m=<?php echo $this->input->get("id"); ?>]').addClass('bg-gray');
		$(document).on('click','.btn_board_write',function(){
			location.href="<?php echo site_url();?>/board/Write";
		});
		
		/*왼쪽 게시판 클릭*/
		$(document).on('click','.list-group-c .s_y',function(){
			var board_id = $(this).attr('m');
			
			if(board_id == 'config'){
				location.href="<?php echo site_url();?>/board/Board";
			}else{
				location.href="<?php echo site_url();?>/board/Main?id="+board_id;
			}
		});
		
	});
	
	//게시판 리스트 불러오기
	function getBoardList(){
		
		var data = {
			// "pe_id": pe_id
		};
		
		$.ajax({
			type: 'post',
			dataType: 'json',
			url: '<?php echo base_url();?>index.php/board/Main/getBoardList',
			data: data,
			async:false,
			success: function (data) {
				
				$('.ul-board').html('');
				
				var html = '';
				
				$.each( data, function(i,v){
					html += '<li class="list-group-item s_y" m="'+v.BOARD_ID+'" style="cursor:pointer;" data-toggle="tooltip" data-placement="top" title="'+v.BOARD_TITLE+'">';
					html += v.BOARD_TITLE;
					html += '<i class="pull-right fa fa-ellipsis-h text-default" style="font-size:14px"></i>';
					html += '</li>';
				});
				/*
				html += '<li class="list-group-item s_y" m="config" style="cursor:pointer;" data-toggle="tooltip" data-placement="top" title="게시판 관리">';
				html += '<b>게시판 관리</b>';
				html += '<i class="pull-right fa fa-ellipsis-h text-default" style="font-size:14px"></i>';
				html += '</li>';
				*/
				$('.ul-board').append(html);
				
				fn_setActive();
				
			},
			error: function (request, status, error) {
				console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
			}
		});
		
	}
	
	function fn_setActive(){
		
		var id = "<?php echo $this->input->get('id'); ?>";
		
		if(id == ''){
			var firstId = $('.list-group-item.s_y').eq(0).attr('m');
			//location.href="<?php echo site_url();?>/board/Main?id="+firstId;
		}else{
			$('[m='+id+']').addClass('bg-gray');
		}
		
	}
</script>
<div id="wp_left" class="gray_border_right">
		
		<?php $this->load->view('/public/userInfo.php');?>
		
		<div class="text-center pt-20 ptb-20">
			<div class="btn-group btn-group-toggle" data-toggle="buttons">
				<button type="button" class="btn btn_board_write btn-dark mt-10">
					<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
					 글작성
				</button>
			</div>
		</div>
		
		<ul class="list-group-c list-group ul-board">
			
		</ul>
		
		
</div>
		
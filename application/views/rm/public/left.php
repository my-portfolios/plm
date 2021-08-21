<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script>
	var list = "rm_list";//그리드 아이디
	$(function(){
		
		$(document).on('click','.btn_rm_write',function(){
				location.href="<?php echo site_url();?>/rm/Write";
			
		});
		$(window).bind( 'hashchange', function(e) { 
			hashSet();
		});
		/*왼쪽 메뉴 클릭*/
		var now = "<?php echo $this->uri->segment(2);?>";
		$(document).on('click','.list-group-c .s_y',function(){
			

			if( now != 'Main'){
				location.href = '<?php echo site_url();?>/rm/Main/#'+$(this).attr('m');
			}else{	
				location.hash = '#'+$(this).attr('m');
			}
		});
		if( now != 'Main'){
			hashSet();
		}
		
	});
	
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
	
	function hashSet(){
	
		var hash = window.location.hash;
		var mArr = [ '#all', "#m", "#trash" ];
		
		if($.inArray(hash,mArr) != -1){
			//if ("onhashchange" in window) {
				
				var etcData = {
					"docType":$('[m='+hash.replace('#','')+']').attr('M')
				}
				detailSearchSeri();
				$("#"+list).setGridParam({
				postData:etcData,
				page:1
				}).trigger("reloadGrid");
				
				$('.s_y').removeClass('bg-gray');//메뉴bg삭제
				$('[m='+hash.replace('#','')+']').addClass('bg-gray');//메뉴bg넣기
				
				$('.locas').text($('[m='+hash.replace('#','')+']').text());//타이틀변경
	    	
			//}
		}
		if(!hash){
			$('[m=all').addClass('bg-gray');//메뉴bg넣기
			//location.hash = '#all';
		}
	}	
</script>
<div id="wp_left" class="gray_border_right">
		
		<?php $this->load->view('/public/userInfo.php');?>
		
		<div class="text-center pt-20 ptb-20">
			<div class="btn-group btn-group-toggle" data-toggle="buttons">
				<button type="button" class="btn btn_rm_write btn-dark mt-10">
					<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
					 글작성
				</button>
			</div>
		</div>
		
		<ul class="list-group-c list-group" style="">
			<li class="list-group-item s_y" m="all" style="cursor:pointer;" data-toggle="tooltip" data-placement="top" title="전체 요구사항 보기">
				전체
		    <i class="pull-right fa fa-ellipsis-h text-default" style="font-size:14px"></i>
		  </li>
		  <?php if($this->session->userdata('userauth') != 'user'){ ?>
		  <li class="list-group-item s_y" m="m" style="cursor:pointer;" data-toggle="tooltip" data-placement="top" title="공유받은 요구사항 보기">
				공유받은 요구사항
		    <i class="pull-right fa fa-share-alt text-default" style="font-size:14px"></i>
		  </li>
			<?php } ?>
		  <?php if($this->session->userdata('userauth') == 'admin'){ ?>
		  <li class="list-group-item s_y" m="trash" style="cursor:pointer;" data-toggle="tooltip" data-placement="top" title="삭제된 요구사항 보기">
				휴지통
		    <i class="pull-right fa fa-trash text-default" style="font-size:14px"></i>
		  </li>
		  <?php } ?>
		</ul>
		
		
</div>
		
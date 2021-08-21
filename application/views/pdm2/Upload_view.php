<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script>
function snoteLoad(){
	$('.snote').summernote({
		height: 300,          // 기본 높이값
		minHeight: null,      // 최소 높이값(null은 제한 없음)
		maxHeight: null,      // 최대 높이값(null은 제한 없음)
		focus: true,          // 페이지가 열릴때 포커스를 지정함
		lang: 'ko-KR',         // 한국어 지정(기본값은 en-US)
		disableDragAndDrop: true,
		toolbar: [
    // [groupName, [list of button]]
	    ['style', ['bold', 'italic', 'underline', 'clear']],
	    ['font', ['strikethrough', 'superscript', 'subscript']],
	    ['fontsize', ['fontsize']],
	    ['color', ['color']],
	    ['table', ['table']],
	    ['para', ['ul', 'ol', 'paragraph']],
	    ['height', ['height']]
	  ]
	});
	$('.snote').summernote('disable');
};

$(function(){
	
	$('#PF_KEYWORD').tagsinput();
	
	/* 취소 */
	//$(document).on('click','.btn_cancel',function(){
	$('.btn_cancel').click(function(){
		$('#content_ajax').html('');
		$('.lists').show();
		$(window).trigger('resize');
		window.history.back();
		
	});
	//수정
	$('.btn_modify').click(function(){
		location.hash = 'WRITE_<?php echo $list->PF_ID ?>';
	});
	//삭제
	$('.btn_del').click(function(){
		bootbox.confirm({
			size: "small",
			message: "삭제하시겠습니까?<br /><code>영구삭제는 복구가 불가능합니다.</code> ",
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
					fn_del();
				}
			}
			});	
	});
	
	$('.extIcon').html(extIcon("<?php echo $list->PF_FILE_EXT;?>",'y'));
	
});

//삭제
function fn_del(){
	var data = {
		"id" : '<?php echo $list->PF_ID ?>'
	};
	$.ajax({
		type: 'post',
		dataType: 'json',
		url: '<?php echo base_url();?>index.php/pdm2/Upload_view/del',
		data: data,
		async : false,
		success: function (data) {
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
			window.history.back();
		},
		error: function (request, status, error) {
			console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
		}
	});
}

function wbsView(v){
	PopupCenter("<?php echo site_url()?>/pms/WbsView?id="+v, 'WBSVIEW', '1000', '600');
}
</script>
<style>
.bootstrap-tagsinput input{
	display:none;
}
.bootstrap-tagsinput span span{
	display:none;
}
.label-info{
	background:#b1b1b1
}
.bootstrap-tagsinput{
	border:none;
	box-shadow:none;
	padding: 5px 0
}

/**에디터**/
.note-editor.note-frame{
	
}
.note-resizebar{
	display:none
}
.note-editor{
	box-shadow:none;
}
.panel{
	border-radius: 0!important;
	border-top:none!important;
	border-color:#e5e5e5!important
}
/**/
</style>

<!-- 파일뷰 팝업 -->
<div id="wp_right">
	
	<div class="grid_area">
	<form id="frm_upload_view" class="p-20" data-toggle="validator" role="form" action="" method="post" enctype="multipart/form-data" >
		<div class="gray_border_bottom pb-20">
		  <h3>상세보기 <small>file detail view</small></h3>
		</div>
		<br />
		<?php if( count($list) > 0 ){ ?>
		<input type="hidden" id="PF_ID" name="PF_ID" value="<?php echo $list->PF_ID ?>" />
	  <div class="form-group required ">
	    <label for="PF_NM">파일이름</label>
			<div class="view_inputs gray_border_bottom pb-15">
				<?php echo $list->PF_NM ?>
				<br /><small>작성일 : <?php echo $list->INS_DT;?></small>
				<br /><small>수정일 : <?php echo $list->UPD_DT;?></small>
				<div style="font-size:11px" class="mt-10">
					<script>
						/*
						$(function(){
							getUserIdToNm("<?php echo $list->INS_ID;?>",'insId');
							getUserIdToNm("<?php echo $list->UPD_ID;?>",'updId');
						});
						*/
					</script>
					<?php 
						/*
						$emps1 = '';
						$emps1 .= '작성 : <span class="nav-item dropdown">';
			      $emps1 .= '<a class="nav-link dropdown-toggle btn btn-default btn-xs" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
			      $emps1 .= '<span class="insId"></span> (' . $list->INS_ID . ')&nbsp;&nbsp;<span class="fa fa-sort-down" style="top: -2px;position: relative;"></span>';
			      $emps1 .= '</a> ';
			      $emps1 .= '<div class="dropdown-menu" aria-labelledby="navbarDropdown">';
						$emps1 .= '<a onclick=msgView("'.$list->INS_ID.'") class="dropdown-item">쪽지보내기</a>';
						$emps1 .= '<a onclick=infoView("'.$list->INS_ID.'") class="dropdown-item">정보보기</a>';
						$emps1 .= '</div>';
			      $emps1 .= '</span>';
						
						$emps2 = '';
						$emps2 .= '수정 : <span class="nav-item dropdown">';
			      $emps2 .= '<a class="nav-link dropdown-toggle btn btn-default btn-xs" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
			      $emps2 .= '<span class="updId"></span> (' . $list->UPD_ID . ')&nbsp;&nbsp;<span class="fa fa-sort-down" style="top: -2px;position: relative;"></span>';
			      $emps2 .= '</a> ';
			      $emps2 .= '<div class="dropdown-menu" aria-labelledby="navbarDropdown">';
						$emps2 .= '<a onclick=msgView("'.$list->UPD_ID.'") class="dropdown-item">쪽지보내기</a>';
						$emps2 .= '<a onclick=infoView("'.$list->UPD_ID.'") class="dropdown-item">정보보기</a>';
						$emps2 .= '</div>';
			      $emps2 .= '</span>';
			      
			      echo '<div class="mt-10">'.$emps1.' &nbsp; '.$list->INS_DT.'</div>';
						echo '<div style="margin-top:3px">'.$emps2.' &nbsp; '.$list->UPD_DT.'</div>';
						*/
					?>
				</div>
			</div>
	  </div>
	  
		<div class="form-group required">
			<label for="PFD_PATH">카테고리</label>
			<div class="view_inputs gray_border_bottom pb-15">
				<?php echo $list->PF_PATH ?>
			</div>
			
			<input type="hidden" name="PFD_ID" id="PFD_ID" value="<?php echo $list->PFD_ID ?>" />
		</div>
	<div class="form-group required">
		<label for="PFD_PATH">프로젝트</label>
		<div>
			<?php 
				if( $pmsList != null ){
					$pmss = '';
					$pmsArr = '';
					$i=0;
					$com = '';
					foreach( $pmsList as $data ){
						if($i != 0){
							/*$com = ',';*/
						}
						if($data->DEL_YN == 'Y'){
							
							$pmss .= '<span class="btn btn-default btn-xs disabled">'.$com.$data->PP_NM . '(삭제됨)  </span> ';
							
						}else{
							$pmss .= '<span title="새창열림" class="btn btn-default btn-xs" onclick=wbsView("'.$data->PP_ID.'")>'.$com.$data->PP_NM . '(' . $data->PP_ID . ') &nbsp;&nbsp;<span class="fa fa-window-restore" style="top: 0;position: relative;"></span></span> ';
						}
						$i++;
					}
				} 
			?>
			<div class="view_inputs gray_border_bottom pb-15">
				<?php if($pmsList != null) { echo $pmss; }else{ echo '등록된 프로젝트가 없습니다.';} ?>
			</div>
		</div>
		<input type="hidden" name="PFD_ID" id="PFD_ID" value="<?php echo $list->PFD_ID ?>" />
	</div>	
	<div class="form-group required">
	  <label for="PF_EMP_TEXT">공유</label>
		<div>
			<?php 
				if( $empList != null ){
					$emps = '';
					$empArr = '';
					$i=0;
					$com = '';
					foreach( $empList as $data ){
						if($i != 0){
							$com = ',';
						}
						if($data->DEL_YN == 'Y'){
							$emps .= '<span class="btn btn-default btn-xs disabled">'.$data->EMP_NM . '(' . $data->EMP_ID . ' 삭제됨)</span> ';
						}else{
							$emps .= '<span class="nav-item dropdown">';
				      $emps .= '<a class="nav-link dropdown-toggle btn btn-default btn-xs" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
				      $emps .= $data->EMP_NM . '(' . $data->EMP_ID . ')&nbsp;&nbsp;<span class="fa fa-sort-down" style="top: -2px;position: relative;"></span>';
				      $emps .= '</a> ';
				      $emps .= '<div class="dropdown-menu" aria-labelledby="navbarDropdown">';
							$emps .= '<a onclick=msgView("'.$data->EMP_ID.'") class="dropdown-item">쪽지보내기</a>';
							$emps .= '<a onclick=infoView("'.$data->EMP_ID.'") class="dropdown-item">정보보기</a>';
							$emps .= '</div>';
				      $emps .= '</span>';
						}
						$i++;
					}
				} 
			?>
			
		  
		  <div class="view_inputs gray_border_bottom pb-15">
				<?php if($empList != null) { echo $emps; }else{ echo '등록된 공유자가 없습니다.';} ?>
			</div>
		</div>
		<span class="help-block with-errors"></span>
	</div>	
	<div class="form-group required">
	  <label for="PF_KEYWORD">키워드</label>
		<div>
			<?php 
			
				if( $keywordList != null ){
					foreach( $keywordList as $data ){
						
						?>
						<script>
						$(function(){
							$('#PF_KEYWORD').tagsinput('add', '<?php echo $data->PK_NM; ?>');
						});
						</script>
						<?php
					}
					
				}
				//echo '<div class="view_inputs gray_border_bottom pb-15">등록된 키워드가 없습니다.</div>';
			?>
			
			
		  <input id="PF_KEYWORD" name="PF_KEYWORD" type="text" data-role="tagsinput" class="form-control" placeholder="" aria-label="..." value="" readonly>
		</div>
	</div>
	<div class="form-group required">
		<label for="PF_CONT">내용</label>
		<div style="height:2px;overflow:hidden;" class="bg-<?php echo $this->config->item($this->uri->segment(1).'Color');?>"></div>
		<textarea class="form-control snote" id="PF_CONT" name="PF_CONT"><?php echo $list->PF_CONT ?></textarea>
		<span class="help-block with-errors"></span>
	</div>
	  <div class="form-group required">
	    <label for="PF_FILE">첨부된 파일 : </label>
	    <span class="extIcon"></span>
	    <a href="<?php echo site_url()?>/pdm2/Upload_view/fileDownload?tempName=<?php echo $list->PF_FILE_TEMP_NM ?>&fileName=<?php echo $list->PF_FILE_REAL_NM ?>">
			<?php echo $list->PF_FILE_REAL_NM ?>
			
		</a>
	  </div>
	  
	  <?php } ?>
	  <div class="text-center gray_border_top">
		<?php if( $list->PF_INIT_ID_TYPE == $this->uri->segment(1) ){ ?>
			<?php if($this->session->userdata('userauth') == 'admin' || $this->session->userdata('userid') == $list->INS_ID ){ ?>
				<button type="button" class="btn btn-<?php echo $this->config->item($this->uri->segment(1).'Color');?> btn-sm mt-10 btn_modify">
					수정
				</button>
				<!--
				<button type="button" class="btn btn-<?php echo $this->config->item($this->uri->segment(1).'Color');?> btn-sm mt-10 btn_del">
					삭제
				</button>
				-->
			<?php } ?>
		<?php }else{ ?>
		<a class="btn btn-default btn-sm mt-10" href="<?php echo site_url() ?>/<?php echo $list->VIEW_URL ?><?php echo $list->VIEW_ID; ?>">해당 글 바로가기</a>
		<?php if($this->session->userdata('userauth') == 'admin' || $this->session->userdata('userid') == $list->INS_ID ){ ?>
		<!--
			<button type="button" class="btn btn-<?php echo $this->config->item($this->uri->segment(1).'Color');?> btn-sm mt-10 btn_del">
				삭제
			</button>
		-->
		<?php } ?>
		<!--
			<a class="btn btn-default btn-sm mt-10" href="<?php echo site_url() ?>/<?php echo $list->PF_INIT_ID_TYPE ?>/View?id=<?php echo $list->VIEW_ID; ?>">해당 글 바로가기</a>
			-->
		<?php } ?>
			<button type="button" class="btn btn_cancel btn-default btn-sm mt-10 ">
				목록
			</button>
		</div>
	</form>
	
	<!--파일이력-->
	<?php if( $list->PF_INIT_ID_TYPE == $this->uri->segment(1) ){ ?>
	<div class="p-20">
		<div class="mb-10">
			<h3>파일변경이력</h3>
		</div>
		<div style="height:2px;overflow:hidden;" class="bg-<?php echo $this->config->item($this->uri->segment(1).'Color');?>"></div>
	  <table class="tbl_main table table-hover" id="historygrid">
			<colgroup>
				<col />
				<col style="width:5%" />
				<col style="width:10%" />
				<col style="width:10%" />
				<col style="width:10%" />
			</colgroup>
			<thead>
				<tr>
					<th onclick="sortTable(1,'historygrid')">제목(파일명)</th>
					<th onclick="sortTable(2,'historygrid')">종류</th>
					<th onclick="sortTable(3,'historygrid')">용량</th>
					<th onclick="sortTable(4,'historygrid')">작성자</th>
					<th onclick="sortTable(5,'historygrid')">작성일</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$iv=0;
				if( $versionList != null ){ ?>
				<?php 
				foreach( $versionList as $data ) { ?>
				<tr>
					<td class='text-left'>
						<strong><?php echo $data->PF_NM ?></strong>
						<div style="color:#666">이전 경로 : <?php echo $data->PF_PATH ?></div>
					</td>
					<td class='text-center'>
						<script>
							$(function(){
								$('.extIcon_his<?php echo $iv?>').html(extIcon("<?php echo $data->PF_FILE_EXT;?>","y"));
							});
						</script>
						<span class="extIcon_his<?php echo $iv?>"></span>
						<a href="<?php echo site_url()?>/pdm2/Upload_view/fileDownload?tempName=<?php echo $data->PF_FILE_TEMP_NM ?>&fileName=<?php echo $data->PF_FILE_REAL_NM ?>"><?php echo $data->PF_FILE_EXT ?></a>
					</td>
					<td class='text-center'><?php echo $data->PF_FILE_SIZE ?> kb</td>
					<td class='text-center'><?php echo $data->INS_ID ?></td>
					<td class='text-center'><?php echo $data->INS_DT ?></td>
				</tr>
				<?php 
				$iv++;
				} 
				
				?>
				<?php }
				
				if($iv == 0){
						echo "<tr><td colspan='5' align='center' style='padding: 15px 0'>데이타가 없습니다.</td></tr>";
					}
				
				 ?>
			</tbody>
		</table>
	</div>
	<?php } ?>
	</div>
</div>
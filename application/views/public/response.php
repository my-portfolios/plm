<style type="text/css">
	@media screen and (max-width: 1200px) {
		#wp{
			min-width: 350px!important
		}
    .p_boxw{
    	width: 33.3%!important;
    	border:none!important;
    	float:none!important;
    	display:inline-block!important;
    }
    .dash_main #pdm_list tbody tr{
    	width: 25%!important
    }
    .p_box_50{
    	width: 100%!important
    }
    .p_box{
    	margin-bottom:20px
    }
    .p_box .panel-body{
    	height:auto!important
    }
    .t_style{
    	margin-top:15px
    }
    
	}
	@media screen and (max-width: 910px) {
    .form-control{
    	width:100%!important;
    	margin-bottom:10px
    }
    .search_area{
    	height: auto;
    }
    .searchDetails{
    	display:none!important
    }
    /**/
    .leftProWp .ui-jqgrid{
			width:100%!important;
			overflow-x:scroll;
			overflow-y:hidden;
		}
		.leftProWp .ui-jqgrid-bdiv,
    .leftProWp .ui-jqgrid-view,
    .leftProWp .ui-jqgrid-pager,
    .leftProWp .ui-jqgrid-hdiv,
    .leftProWp .ui-jqgrid-htable,
    .leftProWp .ui-jqgrid-btable{
    	min-width: 350px!important;
    }
    .leftProWp .ui-jqgrid .ui-jqgrid-hbox{
    	width:100%!important;
    }
    .leftProWp .ui-jqgrid .ui-jqgrid-hbox table{
    	display:none
    }
    .leftProWp .ui-jqgrid-hbox:after{
    	content: '목록';
    	display:block;
    	font-size:12px;
    	font-weight:bold;
    	padding:5px 20px 5px 20px;
    	overflow:hidden;
    	text-align:center;
    }
		/**/
	<?php if($this->uri->segment(1) != 'dash'){?>
		
		.ui-jqgrid{
			width:100%!important;
			overflow-x:scroll;
			overflow-y:hidden;
		}
    .ui-jqgrid-bdiv,
    .ui-jqgrid-view,
    .ui-jqgrid-pager,
    .ui-jqgrid-hdiv,
    .ui-jqgrid-htable,
    .ui-jqgrid-btable{
    	min-width: 350px!important;
    }
    .grid_area{
    	overflow-x:hidden
    }
    
    /**
    그리드 항목 CSS 정의
    ***/
    /*요구*/
    [aria-describedby="rm_list_PC_NM"],
    [aria-describedby="rm_list_PR_HOPE_END_DAT"],
    [aria-describedby="rm_list_PR_STATUS"],
    [aria-describedby="rm_list_UPD_DT"]
    {
    	display: none
    }
    #rm_list .jqgfirstrow td:nth-child(4),
    #rm_list .jqgfirstrow td:nth-child(5),
    #rm_list .jqgfirstrow td:nth-child(6),
    #rm_list .jqgfirstrow td:nth-child(8){
    	display:none
    }
    #rm_list .jqgfirstrow td:nth-child(3){
    	width: 60%!important
    }
    #rm_list .jqgfirstrow td:nth-child(7){
    	width: 30%!important
    }
    #rm_list .jqgfirstrow td:nth-child(1){
    	width: 10%!important
    }
    select#PR_STATUS{
    	margin-top:10px
    }
    /*요구끝*/
    
    /*게시*/
    [aria-describedby="main_list_UPD_DT"]
    {
    	display: none
    }
    #main_list .jqgfirstrow td:nth-child(7){
    	display:none
    }
    #main_list .jqgfirstrow td:nth-child(3){
    	width: 60%!important
    }
    #main_list .jqgfirstrow td:nth-child(5){
    	width: 30%!important
    }
    #main_list .jqgfirstrow td:nth-child(1){
    	width: 10%!important
    }
    /*게시끝*/
    
    /*부품*/
    [aria-describedby="Part_list_BP_STD"],
    [aria-describedby="Part_list_BP_MTR"],
    [aria-describedby="Part_list_INS_DT"]
    {
    	display: none
    }
    #Part_list .jqgfirstrow td:nth-child(4),
    #Part_list .jqgfirstrow td:nth-child(5),
    #Part_list .jqgfirstrow td:nth-child(8){
    	display:none
    }
    #Part_list .jqgfirstrow td:nth-child(3){
    	width: 60%!important
    }
    #Part_list .jqgfirstrow td:nth-child(7){
    	width: 30%!important
    }
    #Part_list .jqgfirstrow td:nth-child(1){
    	width: 10%!important
    }
    /*부품끝*/
    
    /*카테고리*/
    [aria-describedby="Cate_list_INS_DT"]
    {
    	display: none
    }
    #Cate_list .jqgfirstrow td:nth-child(5){
    	display:none
    }
    #Cate_list .jqgfirstrow td:nth-child(3){
    	width: 60%!important
    }
    #Cate_list .jqgfirstrow td:nth-child(4){
    	width: 30%!important
    }
    #Cate_list .jqgfirstrow td:nth-child(1){
    	width: 10%!important
    }
    /*카테고리끝*/
    
    /*제품정보*/
    [aria-describedby="Pdt_list_BPD_CD"],
    [aria-describedby="Pdt_list_INS_DT"]
    {
    	display: none
    }
    #Pdt_list .jqgfirstrow td:nth-child(4),
    #Pdt_list .jqgfirstrow td:nth-child(6){
    	display:none
    }
    #Pdt_list .jqgfirstrow td:nth-child(3){
    	width: 60%!important
    }
    #Pdt_list .jqgfirstrow td:nth-child(5){
    	width: 30%!important
    }
    #Pdt_list .jqgfirstrow td:nth-child(1){
    	width: 10%!important
    }
    /*제품정보끝*/
    
    /*양식관리*/
    [aria-describedby="Format_list_INS_DT"]
    {
    	display: none
    }
    #Format_list .jqgfirstrow td:nth-child(5){
    	display:none
    }
    #Format_list .jqgfirstrow td:nth-child(3){
    	width: 60%!important
    }
    #Format_list .jqgfirstrow td:nth-child(4){
    	width: 30%!important
    }
    #Format_list .jqgfirstrow td:nth-child(1){
    	width: 10%!important
    }
    /*양식관리끝*/
    
    /*유저관리*/
    [aria-describedby="User_list_PE_ID"],
    [aria-describedby="User_list_PC_NM"],
    [aria-describedby="User_list_PE_TEL"],
    [aria-describedby="User_list_INS_DT"]
    {
    	display: none
    }
    #User_list .jqgfirstrow td:nth-child(2),
    #User_list .jqgfirstrow td:nth-child(4),
    #User_list .jqgfirstrow td:nth-child(5),
    #User_list .jqgfirstrow td:nth-child(8){
    	display:none
    }
    #User_list .jqgfirstrow td:nth-child(3){
    	width: 60%!important
    }
    #User_list .jqgfirstrow td:nth-child(7){
    	width: 30%!important
    }
    #User_list .jqgfirstrow td:nth-child(1){
    	width: 10%!important
    }
    /*유저관리끝*/
    
    
    /*조직도관리*/
    [aria-describedby="Org_list_ORG_YN"],
    [aria-describedby="Org_list_INS_DT"]
    {
    	display: none
    }
    #Org_list .jqgfirstrow td:nth-child(5),
    #Org_list .jqgfirstrow td:nth-child(7){
    	display:none
    }
    #Org_list .jqgfirstrow td:nth-child(3){
    	width: 60%!important
    }
    #Org_list .jqgfirstrow td:nth-child(6){
    	width: 30%!important
    }
    #Org_list .jqgfirstrow td:nth-child(1){
    	width: 10%!important
    }
    /*조직도관리끝*/
    
    /*거래처관리*/
    [aria-describedby="Comp_list_PC_NUMBER"],
    [aria-describedby="Comp_list_PC_EMP_NM"],
    [aria-describedby="Comp_list_PC_TEL"],
    [aria-describedby="Comp_list_INS_DT"]
    {
    	display: none
    }
    #Comp_list .jqgfirstrow td:nth-child(4),
    #Comp_list .jqgfirstrow td:nth-child(5),
    #Comp_list .jqgfirstrow td:nth-child(6),
    #Comp_list .jqgfirstrow td:nth-child(8){
    	display:none
    }
    #Comp_list .jqgfirstrow td:nth-child(3){
    	width: 60%!important
    }
    #Comp_list .jqgfirstrow td:nth-child(7){
    	width: 30%!important
    }
    #Comp_list .jqgfirstrow td:nth-child(1){
    	width: 10%!important
    }
    /*거래처관리끝*/
    
    /*게시판관리*/
    [aria-describedby="Board_list_BOARD_AUTH"],
    [aria-describedby="Board_list_BOARD_READ_AUTH"],
    [aria-describedby="Board_list_BOARD_WRITE_AUTH"],
    [aria-describedby="Board_list_BOARD_WRITE_AUTH"]
    {
    	display: none
    }
    #Board_list .jqgfirstrow td:nth-child(4),
    #Board_list .jqgfirstrow td:nth-child(5),
    #Board_list .jqgfirstrow td:nth-child(6),
    #Board_list .jqgfirstrow td:nth-child(7){
    	display:none
    }
    #Board_list .jqgfirstrow td:nth-child(3){
    	width: 90%!important
    }
    #Board_list .jqgfirstrow td:nth-child(1){
    	width: 10%!important
    }
    /*게시판관리끝*/
    
    
    /*프로젝트*/
    [aria-describedby="pms_list_PP_ST_DAT"],
    [aria-describedby="pms_list_PP_ED_DAT"],
    
    [aria-describedby="pms_list_UPD_DT"],
    [aria-describedby="pms_list_PC_NM"]
    {
    	display: none
    }
    #pms_list .jqgfirstrow td:nth-child(4),
    #pms_list .jqgfirstrow td:nth-child(5),
    
    #pms_list .jqgfirstrow td:nth-child(7),
    #pms_list .jqgfirstrow td:nth-child(8){
    	display:none
    }
    #pms_list .jqgfirstrow td:nth-child(1){
    	width: 24px!important
    }
    /*프로젝트끝*/
    
    /*파일*/
    [aria-describedby="pdm_list_UPD_DT"],
    [aria-describedby="pdm_list_PC_NM"],
    [aria-describedby="pdm_list_PF_FILE_SIZE"]
    {
    	display: none
    }
    
    #pdm_list .jqgfirstrow td:nth-child(7),
    #pdm_list .jqgfirstrow td:nth-child(5),
    #pdm_list .jqgfirstrow td:nth-child(12){
    	display:none
    }
    #pdm_list .jqgfirstrow td:nth-child(4){
    	width: 60%!important
    }
    #pdm_list .jqgfirstrow td:nth-child(9){
    	width: 30%!important
    }
    #pdm_list .jqgfirstrow td:nth-child(1){
    	width: 10%!important
    }
    <?php if($_COOKIE['gridType'] == 'grid'){?>
    	#pdm_list tbody tr{
    		width: 33.3%!important
    	}
    <?php } ?>
    
    #historygrid colgroup col:nth-child(1){
  		width:70%!important
  	}
  	#historygrid colgroup col:nth-child(2){
  		width:30%!important
  	}
  	
  	#historygrid tr th:nth-child(3),
    #historygrid tr th:nth-child(4),
    #historygrid tr th:nth-child(5),
    #historygrid tr td:nth-child(3),
    #historygrid tr td:nth-child(4),
    #historygrid tr td:nth-child(5){
    	display:none!important;
    }
    
    /*파일끝*/
    
    .ui-jqgrid-hbox:after{
    	content: '목록';
    	display:block;
    	font-size:12px;
    	font-weight:bold;
    	padding:5px 20px 5px 20px;
    	overflow:hidden;
    	text-align:center;
    }
    
    .ui-jqgrid .ui-jqgrid-hbox{
    	width:100%!important;
    }
    .ui-jqgrid .ui-jqgrid-hbox table{
    	display:none
    }
    .gridCnt{
    	margin: 5px 5px 0 0
    }
    
  <?php } ?>
  
  	.searchDetail{
  		display:none
  	}
   
   	.nav-tabs > li.active > a{
   		background: #f6f6f6
   	}
   	
   	/*프로젝트등록*/
   	.modalPopup .bwinPopupd{
   		width: 90%!important;
   		margin-left: 5%!important;
   		margin-right: 5%!important;
   		min-width: auto!important;
   		overflow-x:hidden!important;
   		overflow-y:auto!important
   	}
   	.overTable{
   		overflow: scroll;
   		margin-bottom:10px
   	}
   	.modalPopup .bwinPopupd .taskData{
   		min-width: 400px;
   	}
   	
	}
	@media screen and (max-width: 800px) {
		.p_boxw{
    	width: 50%!important;
    }
    .dash_main #pdm_list tbody tr{
    	width: 33.3%!important
    }
    .top_etc li button{
    	width: 29px!important;
	    overflow: hidden;
	    padding: 6px 8px!important;
	    background-color:none!important;
	    border: none!important;
    }
    .top_etc li button span,
    .top_etc li button i{
    	padding-right:100px
    }
    #wp_top h1{
    	display:none
    }
    .top_menu{
    	text-align:left;
    	padding-left:10px;
    	padding-top:11px;
    }
    .top_menu button{
    	padding:3px 7px!important;
    }
    .top_etc{
    	top: 10px;
    	right:10px
    }
    
    
	}
	
	@media screen and (max-width: 650px) {
		#wp{
			min-width: 300px
		}
		#wp_left{
			left: -250px;
			border:none!important;
			width: 250px!important;
		}
		#wp_right{
			left: 0!important;	
			z-index:1;
		}
		.d_btn,.s_btn{
			display:block!important
		}
		.leftMo{
			display:block!important
		}
		
		#wp_left.on{
			left: 0!important;
			z-index:1048;
			-webkit-transition: all 0.5s ease;
		  -moz-transition: all 0.5s ease;
		  -o-transition: all 0.5s ease;
		  -ms-transition: all 0.5s ease;
		  transition: all 0.5s ease;
		}
		#wp_left.off{
			left: -250px!important;
		}
		
		.search_area{
    	height: 0;
    	overflow: hidden;
    	width: 100%;
    	background:#fff;
    	z-index:2;
    	padding:0!important;
    	box-sizing: border-box;
    }
    
    .dash_main h4{
    	font-size:16px
    }
		
	}
	
	.d_btn,.s_btn{
			display:none;
			position:absolute;
			z-index:4;
			color:white;
			font-size:20px;
			background:#000;
			opacity: 0.3;
			width: 30px;
			height:30px;		
			text-align:center;
			border-radius: 100px;
			cursor:pointer;
		}
		.d_btn{
			bottom: 59px;
	    left: 20px;
		}
		.s_btn{
			top: 59px;
	    right: 30px;
	    width: 33px;
	    height: 33px;
	    z-index:2
		}
		#content_ajax #wp_right{
			z-index:3
		}
		
		.d_btn i,.s_btn i{
			vertical-align:middle
		}
	
	.leftMo{
		display:none
	}
	
	#wp_top{
		z-index:1049;
	}
	
	 .modal {
		  overflow-x: hidden;
		  overflow-y: auto;
		}
	
	
	.height-auto{
		height: auto;
		padding:20px!important;
	}
	
	
</style>

<script>
	$(function(){
			var search_area_size = 	$('.search_area').size();
			if(search_area_size == 0){
				$('.s_btn').remove();
			}else{
				$(document).on('click','.s_btn',function(){
					$('.search_area').addClass('height-auto');
					$('.s_btn').find('.fa').removeClass('fa-search');
					$('.s_btn').find('.fa').addClass('fa-close');
					$('.s_btn').addClass('s_btn_close');
						$(window).trigger('resize');
				});
				$(document).on('click','.s_btn_close',function(){
					$('.search_area').removeClass('height-auto');
					$('.s_btn').find('.fa').addClass('fa-search');
					$('.s_btn').find('.fa').removeClass('fa-close');
					$('.s_btn').removeClass('s_btn_close');
						$(window).trigger('resize');
				});
			}
			
			
			
			$(document).on('click','.list-group-c,.btn,.leftMo',function(){
				if($('.d_btn').css('display') == 'block'){
					$('.d_btn').removeClass('on');
					$('#wp_left').removeClass('on');
					$('#wp_left').addClass('off');
					$('.leftMo').remove();
				}
			});
			$(document).on('click','.d_btn',function(){
				if($('.d_btn').css('display') == 'block'){
					if($(this).hasClass('on')){
						$(this).removeClass('on');
						$('#wp_left').addClass('off');
						$('#wp_left').removeClass('on');
						$('.leftMo').remove();
					}else{
						$(this).addClass('on');
						$('#wp_left').addClass('on');
						$('#wp_left').removeClass('off');
						<?php if($this->session->userdata('userskin') == 'Dark'){?>
							$('body').append('<div class="modal-backdrop in leftMo" style="top:50px"></div>');
						<?php }else{ ?>
							$('body').append('<div class="modal-backdrop in leftMo" style="top:50px;bottom:38px"></div>');
						<?php } ?>
					}
				}
			});
	});
</script>

<div class="d_btn">
	<i class="fa fa-ellipsis-v"></i>
</div>

<div class="s_btn">
	<i class="fa fa-search"></i>
</div>



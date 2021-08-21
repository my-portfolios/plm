<style type="text/css">
		/* 초기화 */
		html,body {overflow:hidden}
		body {margin:0;padding:0;font-size:12px;overflow:auto;background:#fafafa}
		html, h1, h2, h3, h4, h5, h6, form, fieldset, img {margin:0;padding:0;border:0}
		
		ul{margin:0;padding:0;list-style:none}
		label, input, button, select, img {vertical-align:middle}
		button {cursor:pointer}
		textarea {resize: none !important;}
		select {margin:0}
		p {margin:0;padding:0;word-break:break-all}
		hr {display:none}
		pre {overflow-x:scroll;font-size:1.1em}
		a{text-decoration:none;cursor:pointer}
		/*
		a:link, a:visited {color:#000;text-decoration:none}
		a:hover, a:focus, a:active {color:#000;text-decoration:underline}
		*/
		code,
		kbd,
		.codeGreen{
			font-size:12px!important
		}
		/*wp=====================================================*/
		#wp{
			position:relative;
			min-width:1123px;
			
			overflow-x:auto;
			overflow-y:hidden;
			/*visibility: hidden*/
		}
		/*상단=====================================================*/
		#wp_top{
			height: 50px;
			box-sizing: border-box;
			position: relative;
    	z-index: 1;
    	background:#06ba9a!important
		}
			#wp_top h1{
				color:#fff;
				font-size:20px;
				font-weight:normal;
				position:absolute;
				left:20px;
				top: 13px;
				cursor:pointer;
				text-shadow: 1px 1px 0px #09987e
			}
			#wp_top h1 span{
				top:3px
			}
		/*왼쪽=====================================================*/
		#wp_left{
			width: 250px;
			left:0;
	    overflow-y: auto;
	    overflow-x: auto;
	    position: relative;
	    box-sizing: border-box;
	    background: #444444;
	    color: #ebebeb;
		}
		#wp_left .modal{
			color:#333!important
		}
		/*오른쪽=====================================================*/
		#wp_right{
			float:left;
			position:absolute;
			left:250px;
			right:0;
			top: 50px;
			background:#fff;
			/*
			min-height: 400px;
			overflow-x: auto;
			overflow-y: auto;
			
			*/
			box-sizing: border-box;
		}
		
		/*하단=====================================================*/
		#wp_bottom{
			height:0;
			overflow:hidden;
			/*border-top:1px solid #b4b4b4;*/
			/*padding: 10px 0;*/
			text-align:center;
			box-sizing: border-box;
			position:relative;
			z-index:3;
			background:#fff;
			white-space: nowrap;
	    overflow: hidden;
	    text-overflow: ellipsis;
		}
		/*공통=====================================================*/
		.clear{
			clear:both;
		}
		.text_center{
			text-align:center;
		}
		.link{
			color:#337ab7!important;
			cursor:pointer;
			margin-right:10px;
		}
		.link:hover{
			color:#23527c!important;
			text-decoration:underline!important
		}
		.extIcon{
			margin-right:5px;
		}
		.gray_border_bottom{border-bottom:1px solid #e5e5e5;}
		.gray_border_right{border-right:1px solid #e5e5e5;}
		.gray_border_top{border-top:1px solid #e5e5e5;}
		.gray_border_left{border-left:1px solid #e5e5e5;}
		.gray_border_all{border:1px solid #e5e5e5;}
		
		.objhide{display:none}
		
		.p-10{padding:10px!important;}
		.pt-15{padding-top:15px!important;}
		
		.pt-10{padding-top:10px!important;}
		.p-20{padding:20px!important;}
		.m-20{margin:20px!important;}
		.m-15{margin:15px!important;}
		.p-0{padding:0!important;}
		.pl-0{padding-left:0!important;}
		.pr-0{padding-right:0!important;}
		.pt-0{padding-top:0!important;}
		.pt-20{padding-top:20px!important;}
		.pb-20{padding-bottom:20px!important;}
		.pb-30{padding-bottom:30px!important;}
		.pb-15{padding-bottom:15px!important;}
		.ptb-20{padding-top:20px!important;padding-bottom:20px!important;}
		.mtb-20{margin-top:20px!important;margin-bottom:20px!important;}
		.mtb-10{margin-top:10px!important;margin-bottom:10px!important;}
		.width_100px{
			width: 100px;
			display:inline-block;
		}
		.width_200px{
			width: 200px;
			display:inline-block;
		}
		.mt-0{
			margin-top:0!important
		}
		.mt-5{
			margin-top:5px!important
		}
		.mt-10{
			margin-top:10px!important
		}
		.mr-10{
			margin-right:10px!important
		}
		.mt-20{
			margin-top:20px!important
		}
		.mb-10{
			margin-bottom:10px!important
		}
		.mb-5{
			margin-bottom:5px!important
		}
		.m170{
			max-width: 170px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap
		}
		.outline{
			background:transparent;
			border:none
		}
		.bg-gray{
			background: #3b3b3b!important;
			border-right: 3px solid #06ba9a!important;
			border-top: 1px solid #393939!important;
		}
		.bg-none{
			background:none
		}
		.position-relative{
			position:relative;
		}
		.position-absolute{
			position:absolute;
		}
		.codeGreen{
	    padding: 2px 4px;
	    font-size: 90%;
	    color: #317015;
	    background-color: #f2f9f9;
	    border-radius: 4px;
		}
		.codeDef{
	    padding: 2px 4px;
	    font-size: 90%;
	    background-color: #f9f9f9;
	    border-radius: 4px;
		}
		/*topmenu==================================*/
		
		.top_menu{
			padding-top:8px
		}
		.top_menu .glyphicon{
			text-shadow: 1px 1px 2px #07816b;
		}
		
		/*검색영역================================================*/
		.search_area{
			box-sizing:border-box;
		}
		.search_area label{
			display:none
		}
		
		/*Org*/
		.orgchart .node .title{
			background-color:#06ba9a
		}
		.orgchart .node .content{
			border: 1px solid #06ba9a
		}
		.orgchart .lines .leftLine,
		.orgchart .lines .rightLine,
		.orgchart .lines .topLine{
			border-color: #ccc
		}
		.orgchart .lines .downLine{
			background-color:#ccc
		}
		
		/*왼쪽트리================================================*/
		.tree ul:first-child{
			overflow:auto
		}
		.tree span{
			display:block;
		}
		.tree_on{
			font-weight:bold;
		}
		.tree li{
			margin:8px 0;
			padding-left:19px;
			position:relative;
			white-space: nowrap;
		}
		.tree li ul{
			display:none
		}
		.tree li span{
			position:relative;
			padding-left: 22px;
			cursor:pointer;
		}
		.tree li span:hover{
			text-decoration:underline;
		}
		.isfolder{
			
			display:inline-block;
			width: 17px;
	    height: 16px;
	    margin: 0 5px -2px 0;
	    background:url('<?php echo base_url();?>img/img_m.png');
	    background-position: -4px -27px;
    	background-repeat: no-repeat;
    	cursor:pointer;
    	position:relative
    	
		}
		.isfile{
			display:inline-block;
			width: 17px;
	    height: 16px;
	    margin: 0 5px -2px 0;
	    /*background:url('<?php echo base_url();?>img/img_m.png'); 파일은 이미지로 대체*/
	    background: none!important;
	    background-position: -26px -31px;
    	background-repeat: no-repeat;
    	cursor:pointer;
    	position:relative
		}
		
		.folder:before{
			content:'';
			position:absolute;
			left:0;
			width: 17px;
	    height: 16px;
	    margin: -2px 0px 0px;
	    background:url('<?php echo base_url();?>img/img_m.png');
	    background-position: -4px -29px;
    	background-repeat: no-repeat;
		}
		#trash:before{
	    background-position: -5px -55px;
		}
		.file:before{
			content:'';
			position:absolute;
			left:0;
			width: 17px;
	    height: 16px;
	    margin: -2px 0px 0px;
	    background:url('<?php echo base_url();?>img/img_m.png');
	    background-position: -26px -31px;
    	background-repeat: no-repeat;
		}
		.inf{
			width: 11px;
    	height: 11px;
			position:absolute;left:0;top:0;
			background:url('<?php echo base_url();?>img/img_m.png');
			background-position: -20px -4px;
		}
		.inf_off{
			width: 11px;
    	height: 11px;
			position:absolute;left:0;top:0;
			background:url('<?php echo base_url();?>img/img_m.png');
			background-position: -4px -6px;
		}
		
		.folderImg{
    background: url(/img/img_m.png)!important;
    background-position: -0px -26px!important;
		}
		.fileImgs{
	    background:url(/img/img_m.png) !important;
	    background-position: -23px -27px!important;
		}
		#trash_anchor .folderImg{
			background-position: -2px -52px!important;
		}
		
		/*grid_area================================================*/
		.grid_area{
			overflow-x:auto;
			overflow-y:auto;
			box-sizing: border-box;
			height:100%;
		}
		.grid_area th{
			background:#f9f9f9;
			text-align:center;
			cursor:pointer;
			border-width:1px!important
		}
		
		
		/*etc_top=================================================================*/
		.top_etc{
			position:absolute;
			right:0;
			top:0;
		}
		.top_etc li{
			float:left;
		}
		.top_etc li a{
			color:#fff;
		}
		.top_etc li button:focus,
		.top_etc li button:hover,
		.top_etc li button:active,
		.top_etc li button{
			border:0;
			border-left: 1px solid #09987e!important;
			background: transparent!important;
			border-radius=: 0;
			padding: 16px;
			box-shadow: inherit!important;
			text-shadow: 1px 1px 0px #09987e;
			color:#fff!important
		}
		.top_etc li button:hover{
			background: #09987e!important;
		}
		
		/*location========================================*/
		.location{
			color:#000;
			white-space: nowrap;
	    overflow: hidden;
	    text-overflow: ellipsis;
	    font-size:14px
		}
		
		.location b{
			color:black
		}
		/*modal======================================*/
		.modal {
		  text-align: center;
		  padding: 0!important;
		}
		.modal-content{
			border-radius=:0!important
		}
		.modal:before {
		  content: '';
		  display: inline-block;
		  height: 100%;
		  vertical-align: middle;
		  margin-right: -4px;
		}
		
		.modal-dialog {
		  display: inline-block;
		  text-align: left;
		  vertical-align: middle;
		}
		.dropdown a{
			color:#333333!important;
	    text-decoration:none
		}
		.dropdown-menu{
			margin: 3px 0 0;
		}
		.dropdown-menu a{
			cursor:pointer;
	    display: block;
	    width: 100%;
	    box-sizing: border-box;
	    padding: 5px 10px;
	    font-size:12px;
		}
		.dropdown-menu a:hover{
			background:#f7f7f7
		}
		
		/*listIconType*/

		.listIconType{
			width:31px;
			font-size:3.0em;
			float:left;
			text-align:center;
			margin-right:16px;
		}
		

		#jqgh_pdm_list_cb{
				margin-top:-2px
		}
		<?php if(isset($_COOKIE['gridType']) && $_COOKIE['gridType'] =='grid'){?>
			#pdm_list{
				position:relative!important;
				width:100%!important
			}
			#pdm_list tbody tr{
				display:block!important;
				width: 14.2%!important;
				float:left!important;
				height:181px!important;
				margin-bottom:-1px;
				overflow:hidden;
				border:none!important
			}
			#pdm_list tbody tr td{
				border-bottom:none!important;
				display: block;
				width:100%!important;
				height: auto;
				padding:0!important;
				padding: 0 10px!important;
				position:relative;
				border:none!important
			}
			#pdm_list tbody tr td:first-child{
				padding-top:10px!important
			}
			#pdm_list tbody tr td .test1{
				   position: absolute;
			    left: 16px;
			    top: 0;
			    width: 20px;
			}
			#pdm_list tbody tr td span,
			#pdm_list tbody tr td a,
			#pdm_list tbody tr td div{
				display:block!important;
				float:none!important;
				overflow:hidden;
				text-overflow:ellipsis;
				white-space: nowrap;
				text-align:center;
				width:100%
			}
			#pdm_list tbody tr td a{
				margin-top:-10px
			}
			
			#pdm_list tbody tr:first-child{
			width: 1px!important;
			margin-left:-1px
			}
			#pdm_list_PF_NM,
			#pdm_list_PC_NM{
				display:none
			}
			#jqgh_pdm_list_cb:after{
				content:'목록';
				display:inline-block;
				position: relative;
		    top: 2px;
		    left: 5px;
			}
			/*grid type old ver
		.listLinkType{
	    width: 100%;
	    max-width:100px;
	    margin: 0 auto
		}
		.listPath,.listKeyword{
			display:none
		}
		.listIconType{
			width: 100%!important;
			margin:0 auto;
			display:block;
			font-size:3.3em;
		}	
		.listLinkType{
			text-align:center;
			overflow: hidden;
	    width:100%;
	    display: block;
	    text-overflow: ellipsis;
	    white-space: nowrap
		}
			
		.hgrid{
			position:relative;
			width:100%
		}
		.hgrid thead tr th{
			display:none;
			width:100%;
			border:none;
		}
		.hgrid thead tr th:nth-child(1){
			display:inline-block;
			width:100%;
		}
		.hgrid thead tr th:nth-child(1):after{
			content: '목록';
			display:inline-block;
			margin-left:5px;
			cursor:default
		}
		.hgrid thead tr th:nth-child(1) input{
			margin-top:-2px
		}	
		
			
		#listgrid{
			position:relative;
			width:100%
		}
		
		#listgrid thead tr th{
			display:none;
			width:100%;
			border:none;
		}
		#listgrid thead tr th:nth-child(1){
			display:inline-block;
			width:100%;
		}
		#listgrid thead tr th:nth-child(1):after{
			content: '목록';
			display:inline-block;
			margin-left:5px;
			cursor:default
		}
		#listgrid thead tr th:nth-child(1) input{
			margin-top:-2px
		}

		
		#listgrid tbody tr{
			display:block;
			width: 14.2%;
			float:left;
			background:none!important;
			height:137px
		}
		#listgrid tbody tr td{
			display:none;
			width:100%;
			border:none;
		}
		#listgrid tbody tr td:nth-child(1),
		#listgrid tbody tr td:nth-child(2)
		{
			display:inline-block
		}
		#listgrid tbody tr td:nth-child(1){
			padding-bottom:0
		}
		#listgrid tbody tr td:nth-child(2){
			padding: 15px
		}

		#listgrid tbody tr.noDataTr{
			width:100%
		}
		
		#listgrid tbody tr td a.listFi{
			display:block;
			margin-top:8px;
			text-align:center;
			white-space: nowrap; 
	    overflow: hidden;
	    text-overflow: ellipsis;
	    width:100%;
		}
		
		@media screen and (max-width: 1300px) {
			#listgrid tbody tr{
				width: 20%;
			}
			.listIconType{
				font-size:3.0em
			}
		}
		*/
		
		<?php } ?>
		#listgrid tbody tr td a.listFi{
			cursor:pointer;
			position:relative;
		}
		/*tooltip*/
		.tooltip{
			z-index:102
		}
		
		/*추가boostrap*/
		
		.input-group-addon{
			cursor:pointer
		}
		.input-group{
			z-index:9;
		}
		.datetimepicker{
			z-index:501;
		}
		.btn-transparent {
    	background: transparent;
    	color: #F2F2F2;    
    	box-shadow: none!important
		}
		.has-error .form-control{
			border-color: #a94442!important;
		}
		/*
		.progress-bar {
		    -webkit-transition: none;
		    -moz-transition: none;
		    -ms-transition: none;
		    -o-transition: none;
		    transition: none;
		}*/
		.req-readonly{
			background: #eee;
			position:relative!important;
			z-index:-1!important
		}

    .btn-transparent:hover,.btn-transparent:focus,.btn-transparent:active {
        color: white;
    }
    
    .fcnt{
	    display: inline-block;
	    font-size: 11px;
	    padding: 1px 5px;
    }
    .table>thead>tr>th{
    	padding:6px
    }
    
    /*왼쪽메뉴 아이콘*/
    #jstree-tree-left .jstree-disabled i{
    	background-size: 90%!important;
		}
    
   
   /*boostrap 재정의
   	primary(blue),success(green),info(sky),warning(yellow),danger(red)
   */
   .bg-success{
	   	color: #fff;
	    background-color: #28a745;
	    border-color: #28a745;
  	}
  	.bg-danger{
	   	color: #fff;
	    background-color: #d9534f;
	    border-color: #d9534f;
  	}
  		.req-text{
  			color:#d9534f;
  		}
  	.bg-info{
	   	color: #fff;
	    background-color: #5bc0de;
	    border-color: #5bc0de;
  	}
  	.bg-warning{
	   	color: #fff;
	    background-color: #f0ad4e;
	    border-color: #f0ad4e;
  	}
  	
  	
  	.btn-dark{
	   	color: #fff;
	    background-color: #000;
	    border: none;
  	}
  	.btn-dark:hover,
  	.btn-dark:active,
  	.btn-dark:focus{
  		color:#f2f2f2;
  		background: #000
  	}
  	
  	
  	
  	.bookmark{
  		color:orange;
  		cursor:pointer
  	}
  	.notbk{
  		color:#ccc
  	}
  	
  	
  	/*left 프로필*/
  	.leftPic{
  		width:70px;
  		height:70px;
  		margin: 0 auto 20px auto;
  		overflow:hidden;
  		border-radius: 50%;
  	}
  	.leftPro{
  		background:#444444;
  		color:#fff;
  		border-color:#313131;
  	}
  	.leftPro li{
  		overflow:hidden;
  		white-space: nowrap;
  		text-overflow: ellipsis
  	}
  	.leftPro .btn{
  		background:#565656!important;
  		color:#b5b5b5;
  		text-shadow: 1px 1px 0px #333!important;
  		border-color:#313131
  	}
  	.leftPro .btn:hover,
  	.leftPro .btn:active,
  	.leftPro .btn:focus
  	{
  		color:#b5b5b5;
  		border-color: #212121
  	}
  	.btn.btn_add_folder{
  	  background: #565656!important;
	    border-color: #313131;
	    color: #b5b5b5;
	    text-shadow: 1px 1px 0px #313131;
  	}
  	.btn.btn_add_folder:hover,
  	.btn.btn_add_folder:active,
  	.btn.btn_add_folder:focus
  	{
  		color:#b5b5b5;
  		border-color: #212121
  	}
  	
  	
  	/*jqgrid*/
  	.ui-jqgrid-bdiv{
  		background:#f7f7f7
  	}
  	.ui-jqgrid-titlebar{
  		display:none
  	}
  	.ui-jqgrid{
  		border:none!important
  	}
  	.ui-jqgrid tr.jqgrow td{
  		padding: 5px 3px 5px 3px
  	}
  	.ui-jqgrid .ui-jqgrid-htable th{
  		padding: 6px!important
  	}
  	.loading {
  		display:none!important
  	}
  	.ui-jqgrid .ui-widget-content{
  		border-left:none
  	}
  	.ui-jqgrid .ui-jqgrid-btable{
  		margin-top:-1px
  		
  	}
  	.ui-th-ltr, .ui-jqgrid .ui-jqgrid-htable th.ui-th-ltr{
  		background-image: -webkit-linear-gradient(top, #ffffff 0%, #f3f3f3 100%);
		  background-image: -o-linear-gradient(top, #ffffff 0%, #f3f3f3 100%);
		  background-image: -webkit-gradient(linear, left top, left bottom, from(#ffffff), to(#f3f3f3));
		  background-image: linear-gradient(to bottom, #ffffff 0%, #f3f3f3 100%);
		  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffffff', endColorstr='#fff3f3f3', GradientType=0);
		  filter: progid:DXImageTransform.Microsoft.gradient(enabled = false);
		  background-repeat: repeat-x;
		  border:none;
		  text-shadow: 0 1px 0 #fff;
  	}
  	.ui-jqgrid tr.ui-row-ltr td{
  		border:none;
  		padding: 8px!important;
  		border-bottom:1px solid #ddd
  	}
  	.ui-jqgrid .ui-state-hover {
		   background: #eaf0f3;
		}
		
		.ui-jqgrid .ui-jqgrid-hdiv{
			border-color:#ddd
		}
		.ui-state-highlight, .ui-widget-content .ui-state-highlight, .ui-widget-header .ui-state-highlight{
			color:inherit;
			background:#eaf0f3;
			border-color:#ddd
		}
		.ui-jqgrid td,
		.ui-jqgrid th{
			font-size:12px!important;
			overflow:hidden;
			text-overflow: ellipsis;
		}
		.ui-jqgrid th{
			font-weight:bold!important;
		}
		
		
		/*tags-input */
		.bootstrap-tagsinput{
			width:100%;
			min-height:33px
		}
		.bootstrap-tagsinput .tag{
			font-size:12px; 
		}
		.label-info{
			background: #313131
		}
		.label{
			padding: .25em .6em .25em;
		}
		.bootbox-alert{
			z-index: 1052;
		}
		/*upload*/
		.kv-file-zoom,
		.fileinput-upload-button,
		.file-upload-indicator
		{
			display:none
		}
		.file-drop-zone{
			margin:0;
			margin-bottom:10px!important
		}
		.file-preview{
			padding:0;
			border:none;
		}
		.krajee-default.file-preview-frame .kv-file-content,
		.kv-preview-data.file-preview-other-frame{
			height:auto!important
		}
		.file-thumbnail-footer{
			height:40px!important
		}
		
		/*tab*/
	.nav-tabs{
		/*
		margin-bottom:15px
		*/
	}
		
		
		/*snote*/
		.note-editor.note-frame .note-editing-area .note-editable[contenteditable="false"]{
			background:#fff
		}
		.note-editor.note-frame .note-editing-area .note-editable{
			height:auto!important;
			min-height:300px
		}
		
		#wp_left .jstree-default .jstree-clicked,
		#wp_left .jstree-default .jstree-hovered{
			background:#313131!important;
			box-shadow:inherit!important
		}
		/*boostrap listgroup*/
		
		.list-group{
			box-shadow: inherit;
			border-top:1px solid #3a3a3a
		}
		.list-group-c .list-group-item{
			border:none;
			border-bottom:1px solid #3a3a3a;
			border-top: 1px solid #4e4e4e;
			margin: 0;
			border-radius=:0;
			background: #444444;
			border-right: 0px solid #3b3b3b;
			-webkit-transition: border .5s;
			transition: border .5s;
		}
		.list-group-c .list-group-item:hover{
			border-right: 3px solid #06ba9a!important;
			-webkit-transition: border .5s;
			transition: border .5s;
		}
		
		button > .glyphicon,
		button > i,
		span > .glyphicon,
		h6 > .glyphicon,
		h1 > .glyphicon,
		div > .glyphicon,
		a > .glyphicon{
			transition : transform .3s;
		}
		button:hover > .glyphicon,
		button:hover > i,
		span:hover > .glyphicon,
		h1:hover > .glyphicon,
		h6:hover > .glyphicon,
		div:hover > .glyphicon,
		a:hover > .glyphicon{
			-webkit-transform:translatey(-2px);
    	transform:translatey(-2px);
		}
		
		.leftMenuSize{
			text-align:right
		}
		.ui-resizable-e{
			right:0!important;
			width: 7px!important;
			transition: all 1s;
			opacity: .8;
		}
		.ui-resizable-e:hover,
		.ui-resizable-e:focus,
		.ui-resizable-e:active{
			background: #686868;
		}
		
		/*imgpop ie bug*/
		@media all and (-ms-high-contrast: none), (-ms-high-contrast: active) {
		    /* IE10+ CSS */
		    .ekko-lightbox .modal-dialog {
		      flex: 100%!important;
		      margin-left: 0;
		      margin-right: 0;
		      overflow: hidden;
		      -ms-overflow-style: none;
		    }
		}
		@media screen and (max-width : 768px) {
		 .modal-dialog{
		 	width: 90%!important;
		 	margin: 5%!important
			}
		}
		
		/*detail search*/
		.searchDetails{
			display:none;
			border: 1px solid #d0d0d0;
	    padding: 15px;
	    background: #f9f9f9;
	    border-radius:4px;
	    white-spaces: nowrap;
		}
		.searchDetails label{
			display:inline-block;margin:2px 5px 0 10px
		}
		
		
		/*상세보기*/
		.view_inputs{
			font-size:15px
		}
		
		/*로딩바 수정*/
		.progress{
			margin-bottom:0px;
			height:7px;
		}
		
		.form-control{
			
		}
		/*range bar*/
		.rBarWp{
			display:block;
			position:relative
		}
		.rBar{
			background: #dddddd;
    	height: 3px;
    	overflow:hidden
		}
		.rBar span{
			background: #06ba9a;
			display:block;

			height: 3px;
		}
		.lbg{
			position:absolute;
			left: 0;
			top: -17px;
			color:#454545
		}
		.rbg{
			position:absolute;
			right:0;
			top: 5px;
			color:#454545
		}
		.rBarNm{
			position: absolute;
	    height: 5px;
	    border-right: 1px solid #dddddd;
	    top: -5px;
		}
		.rBarNm.r1{
			width: 10%;
		}
		.rBarNm.r2{
			width: 20%;
		}
		.rBarNm.r3{
			width: 30%;
		}
		.rBarNm.r4{
			width: 40%;
		}
		.rBarNm.r5{
			width: 50%;
		}
		.rBarNm.r6{
			width: 60%;
		}
		.rBarNm.r7{
			width: 70%;
		}
		.rBarNm.r8{
			width: 80%;
		}
		.rBarNm.r9{
			width: 90%;
		}
		.rBarNm.r10{
			display:none
		}
		/*color add*/
		.btn,
		.form-control,
		.file-drop-zone,
		.note-editor,
		.input-group-addon,
		.bootstrap-tagsinput,
		.label,
		.searchDetails,
		.nav-tabs > li > a,
		.dropdown-menu{
			border-radius=: 0!important
		}
		.btn-default{
			background:#f7f7f7;
			box-shadow:inherit!important;
			text-shadow: 1px 1px 0px #fff
		}
		.bg-<?php echo $this->config->item($this->uri->segment(1).'Color');?> {
			background :#444444;
			color:#647687
		}
		.btn-<?php echo $this->config->item($this->uri->segment(1).'Color');?> {
		  background:#06ba9a;
		  color:#fff
		}
		.btn-<?php echo $this->config->item($this->uri->segment(1).'Color');?>:hover,
		.btn-<?php echo $this->config->item($this->uri->segment(1).'Color');?>:focus {
		  background:#07aa8d;
		  color:#fff;
		  border-color: #069d82;
		}
		.btn-<?php echo $this->config->item($this->uri->segment(1).'Color');?>:active,
		.btn-<?php echo $this->config->item($this->uri->segment(1).'Color');?>.active {
		  background-color: #02987e;
		  border-color: #00846c;
		  color: #fff!important;
		}
		.btn-<?php echo $this->config->item($this->uri->segment(1).'Color');?>.disabled,
		.btn-<?php echo $this->config->item($this->uri->segment(1).'Color');?>[disabled],
		fieldset[disabled] .btn-<?php echo $this->config->item($this->uri->segment(1).'Color');?>,
		.btn-<?php echo $this->config->item($this->uri->segment(1).'Color');?>.disabled:hover,
		.btn-<?php echo $this->config->item($this->uri->segment(1).'Color');?>[disabled]:hover,
		fieldset[disabled] .btn-<?php echo $this->config->item($this->uri->segment(1).'Color');?>:hover,
		.btn-<?php echo $this->config->item($this->uri->segment(1).'Color');?>.disabled:focus,
		.btn-<?php echo $this->config->item($this->uri->segment(1).'Color');?>[disabled]:focus,
		fieldset[disabled] .btn-<?php echo $this->config->item($this->uri->segment(1).'Color');?>:focus,
		.btn-<?php echo $this->config->item($this->uri->segment(1).'Color');?>.disabled.focus,
		.btn-<?php echo $this->config->item($this->uri->segment(1).'Color');?>[disabled].focus,
		fieldset[disabled] .btn-<?php echo $this->config->item($this->uri->segment(1).'Color');?>.focus,
		.btn-<?php echo $this->config->item($this->uri->segment(1).'Color');?>.disabled:active,
		.btn-<?php echo $this->config->item($this->uri->segment(1).'Color');?>[disabled]:active,
		fieldset[disabled] .btn-<?php echo $this->config->item($this->uri->segment(1).'Color');?>:active,
		.btn-<?php echo $this->config->item($this->uri->segment(1).'Color');?>.disabled.active,
		.btn-<?php echo $this->config->item($this->uri->segment(1).'Color');?>[disabled].active,
		fieldset[disabled] .btn-<?php echo $this->config->item($this->uri->segment(1).'Color');?>.active {
		  background-color: #313131;
		  background-image: none;
		}
		.progress-bar-<?php echo $this->config->item($this->uri->segment(1).'Color');?>{
		    background:#ccc
		}
		.bootstrap-datetimepicker-widget{
			z-index:1001!important;
		}
		.bootstrap-datetimepicker-widget table td.active, 
		.bootstrap-datetimepicker-widget table td.active:hover,
		.bootstrap-datetimepicker-widget table td span.active{
			background-color: #06ba9a!important;
		}
		.bootstrap-datetimepicker-widget table td.today:before{
			border-bottom-color: #06ba9a!important;
		}
		
		
		.topCnt{
			background: red;
	    color: #fff;
	    position: absolute;
	    width: 14px;
	    height: 14px;
	    top: 0;
	    font-size: 11px;
	    margin: 5px 0 0 -15px;
	    border-radius: 2px;
		}
		
		[type=checkbox]{
			margin-top:-1px!important
		}
		
		.modal-title{
			color:#333
		}
		
		/*userinfo left menu*/
		.leftUserMenu{
			padding: 20px 20px 0 20px;
			border-top:1px solid #5b5b5b
		}
		.leftUserMenu:after{
			clear:both;
			display:block;
			content: '';
		}
		.leftUserMenu button{
			  float: left;
			 	padding:0;
		    width: 14.6%;
		    margin: 1%;
		    height: 29px;
		    border: 1px solid #313131;
		    position: relative;
		    color: #b5b5b5;
		    background: #565656;
		    border-radius=: 0;
		}
		.leftUserMenu button:hover{
			color:#b5b5b5;
			border-color: #212121
		}
		.leftUserMenu button.active,
		.leftUserMenu button:focus{
			color:#b5b5b5;
			background:#2a2a2a;
			border: 1px solid #212121;
			z-index:1
		}
		
		.leftUserMenu button .topCnt{
			margin: 0;
			left:-3px;
			top: -3px;
			z-index:1;
		}
		
		.leftMenuClose{
			display:none;
			/*background: #f7f7f7 url('<?php echo base_url();?>img/law.png') no-repeat center center;*/
			background:#f7f7f7;
			height: 100%;
			position:absolute;
			left:0;
			top: 0;
			width: 8px;
			border-right:1px solid #ededed;
		}
		.leftMenuClose:hover,
		.leftMenuClose:focus{
			/*background: #f4f4f4 url('<?php echo base_url();?>img/law.png') no-repeat center center;*/
		}
		
		<?php if($_COOKIE['leftMenuYn']=='Y'){?>
			#wp_right{left:0}
			#wp_left{margin-left:-1000px}
			.leftMenuClose{
				background: #f7f7f7 url('<?php echo base_url();?>img/raw.png') no-repeat center center;
			}
			.leftMenuClose:hover,
			.leftMenuClose:focus{
				background: #f4f4f4 url('<?php echo base_url();?>img/raw.png') no-repeat center center;
			}
		<?php } ?>
	</style>
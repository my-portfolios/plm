<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if($this->session->userdata("userskin") == 'Def'){
	$m_color = $this->config->item($this->uri->segment(1).'Color_bg1');
}else if($this->session->userdata("userskin") == 'Dark'){
	$m_color = '06ba9a';
}else{
	$m_color = $this->config->item($this->uri->segment(1).'Color_bg1');
}
//파일사이즈 확인
//echo ini_get('upload_max_filesize'), ", " , ini_get('post_max_size'), ", " , ini_get('memory_limit');


 	
$df = disk_free_space("/var/www");
$dt = disk_total_space("/var/www");
$du = $dt - $df;
$dp = sprintf('%.2f',($du / $dt) * 100);
$df = formatSize($df);
$du = formatSize($du);
$dt = formatSize($dt);
function formatSize( $bytes )
{
        $types = array( 'B', 'KB', 'MB', 'GB', 'TB' );
        for( $i = 0; $bytes >= 1024 && $i < ( count( $types ) -1 ); $bytes /= 1024, $i++ );
                return( round( $bytes, 2 ) . " " . $types[$i] );
}

//경고 스타일
if($dp >= 90){
	$back_style_color = 'red';
}else if($dp >= 70){
	$back_style_color = 'orange';
}else{
	$back_style_color = '#'.$m_color;
}

?>
<script src="<?php echo base_url();?>js/Chart.bundle.min.js"></script>
<script src="<?php echo base_url();?>js/Chart.min.js"></script>
<script src="<?php echo base_url();?>js/chartjs-plugin-datalabels.js"></script>

<script>
	
	
	function linkGrid(t){//링크 클릭
			if($.type(t) == 'object'){
				var data = $(t).attr('data');
			}else{
				var data = t;
			}
			//location.hash = 'VIEW_'+data;
			location.href = "<?php echo site_url();?>/pdm2/Main#VIEW_"+data;
		}
		
	function formatOpt1(cellvalue, options, rowObject){//즐찾 셋팅 & 제목
			var str ='';
			var imgUrl = '';
			//이미지면 이미지로 보기
			if(rowObject[6] == 'jpg' || rowObject[6] == 'gif' || rowObject[6] == 'png'){
				/*
				imgUrl = "<img style='border:1px solid #ededed; vertical-align:middle;margin-top:-4px' width='34' height='40' src='<?php echo site_url()?>/pdm2/Upload_view/fileDownload?tempName="+rowObject[10]+"&fileName="+rowObject[11]+"' />";
				*/
				var spTh = rowObject[11].split('.');	
				imgUrl = "<div style='width:34px;height:40px;overflow:hidden;margin:0 auto'><a href='/uploads/"+spTh[0]+"."+spTh[1]+"' data-toggle='lightbox'><img class='img-fluid' style='border:1px solid #ededed; vertical-align:middle;margin-top:-4px' width='34' height='' src='/uploads/"+spTh[0]+"_thumb."+spTh[1]+"' /></a></div>";
			}else{
				imgUrl = '';
			}
			
			//확장자가 없으면
			if(rowObject[6] == null || rowObject[6] == ''){
				rowObject[6] = 'none';
			}
			//키워드가 없으면
			//if(rowObject[13] == null || rowObject[13] == ''){
			//	rowObject[13] = '등록된 키워드가 없습니다.';
			//}
			str += "<span class='listIconType'>"+imgUrl+extIcon(rowObject[6])+"<p style='font-size:10px;white-space: nowrap; overflow: hidden; text-overflow: ellipsis;'>"+rowObject[6]+"</p></span> <a title='"+cellvalue+"' class='link' onclick='linkGrid(this)' href='#VIEW_"+rowObject[0]+"' data='"+rowObject[0]+"'>"+cellvalue+"</a><div>"+rowObject[4]+"</div><div>"+rowObject[13]+"</div></span>";
			return str;
		}
		
		//바이트 계산
		function formatBytes(cellvalue, options, rowObject) {
		    if(cellvalue < 1024) return cellvalue + " Bytes";
		    else if(cellvalue < 1048576) return(cellvalue / 1024).toFixed(1) + " KB";
		    else if(cellvalue < 1073741824) return(cellvalue / 1048576).toFixed(1) + " MB";
		    else return(cellvalue / 1073741824).toFixed(1) + " GB";
		};
		
		//prstatus
		function prstatus(cellvalue, options, rowObject){
			if(cellvalue == '1'){
				return '접수완료';
			}
			if(cellvalue == '2'){
				return '조치완료';
			}
			if(cellvalue == '3'){
				return '진행중';
			}
			if(cellvalue == '4'){
				return '반려';
			}
		}
		function parseDate(strDate) {
	    var _strDate = strDate;
	    var _dateObj = new Date(_strDate);
	    if (_dateObj.toString() == 'Invalid Date') {
	        _strDate = _strDate.split('.').join('-');
	        _dateObj = new Date(_strDate);
	    }
	    if (_dateObj.toString() == 'Invalid Date') {
	        var _parts = _strDate.split(' ');
	 
	        var _dateParts = _parts[0];
	        _dateObj = new Date(_dateParts);
	 
	        if (_parts.length > 1) {
	            var _timeParts = _parts[1].split(':');
	            _dateObj.setHours(_timeParts[0]);
	            _dateObj.setMinutes(_timeParts[1]);
	            if (_timeParts.length > 2) {
	                _dateObj.setSeconds(_timeParts[2]);
	            }
	        }
	    }
	 
	    return _dateObj;
	}
		//prtitles
		function prtitles(cellvalue, options, rowObject){
			var str ='';
		  //디데이구하기
			var now = new Date();
			
			var then = new Date(parseDate(rowObject[3])); 
			var gap = then.getTime() - now.getTime();
			gap = Math.floor(gap / (1000 * 60 * 60 * 24) + 1);
			var lines = '<span class="rBarNm r1"></span><span class="rBarNm r2"></span><span class="rBarNm r3"></span><span class="rBarNm r4"></span><span class="rBarNm r5"></span><span class="rBarNm r6"></span><span class="rBarNm r7"></span><span class="rBarNm r8"></span><span class="rBarNm r9"></span><span class="rBarNm r10"></span>';
			if(gap < 0){
				var gapT = (gap - gap) - gap;
				gap =  '<span class="mt-10"><span class="rBarWp">'+lines+'<span class="lbg">0</span><div class="rBar"></div><span class="rbg">+10</span></span><br />완료요청일 '+gapT+ '일 지났습니다.</span>';
			}else{
				if(gap == 0){
					gap =  '<span class="mt-10"><span class="rBarWp">'+lines+'<span class="lbg">0</span><div class="rBar"><span style="width: 5%;background:red"></span></div><span class="rbg">+10</span></span><br />완료요청일 오늘 까지입니다.</span>';
				}else{
					if(gap > 4){
					gap =  '<span class="mt-10"><span class="rBarWp">'+lines+'<span class="lbg">0</span><div class="rBar"><span style="width: '+gap+'0%;"></span></div><span class="rbg">+10</span></span><br />완료요청일 ' +gap+ '일 남았습니다.</span>';
					}else{
						gap =  '<span class="mt-10"><span class="rBarWp">'+lines+'<span class="lbg">0</span><div class="rBar"><span style="width: '+gap+'0%;background:orange"></span></div><span class="rbg">+10</span></span><br />완료요청일 ' +gap+ '일 남았습니다.</span>';
					}
				}
			}
			switch(rowObject[4]) {
			  case '1':
			      gap = gap;
			      break;
			  case '2':
			      gap = '<span class="mt-10"><i class="fa fa-check" style="color:green;font-size:10px"></i>&nbsp;&nbsp;조치 완료된 항목입니다.</span>';
			      break;
			  case '3':
			      gap = gap;
			      break;
			 	case '4':
			      gap = '<span class="mt-10"><i class="fa fa-repeat" style="color:#ccc;font-size:10px"></i>&nbsp;&nbsp;반려된 항목입니다.</span>';
			      break;
			  default:
			      gap = '';
			}
			
			str += "<a class='link' href='<?php echo site_url()?>/rm/View?id="+rowObject[0]+"'>"+cellvalue+"</a><div class='mt-5'> "+gap+"</div></span>";
			return str;
			/*
			return "<a class='link' href='<?php echo site_url()?>/rm/View?id="+rowObject[0]+"'>"+cellvalue+"</a>";
			*/
		}
		
	
	$(function(){
		
		//wbs 클릭
		$(document).on('click','.btn_wbs',function(){
			var ppid = $(this).attr('datas');
			PopupCenter("<?php echo site_url()?>/pms/WbsView?id="+ppid, 'WBSVIEW', '1000', '600');
		});
		
		// 차트 라벨 옵션
		Chart.defaults.global['plugins'] = {
		    datalabels: {
		        display: true,
		        color: '#333', //'#FFFFFF', //'#88a4d4',
		        //backgroundColor: '#88a4d4', //'#FFFFFF',
		        anchor: 'end',
		        align: 'start',
		        offset: -20,
		        //borderRadius: 2,
		        //padding: 5
		    }
		};
		
		var ctx = document.getElementById("chart_a").getContext('2d');
		var backgroundColor = [
		'#<?php echo $m_color; ?>',
		'rgba(142, 142, 142, 0.3)'
		];
		var myChart = new Chart(ctx, {
		    type: 'bar',
		    data: {
		        labels: ["접수완료","조치완료", "진행중","반려"],
		        datasets: [{
		            /*
		            data: [<?php echo $chart_c->receipt;?>, <?php echo $chart_c->progress;?>, <?php echo $chart_c->measure;?>,<?php echo $chart_c->companion;?>,<?php echo $chart_c->linkdoc;?>],
		            */
		            data: [<?php echo $chart_c->receipt;?>,<?php echo $chart_c->measure;?>, <?php echo $chart_c->progress;?>, <?php echo $chart_c->companion;?>],
		            backgroundColor: backgroundColor,
		            borderWidth: 1

		        }]
		    },
		    options: {
		    	'onClick' : function (evt, item) {
		    		if(item[0]){
		    			
		    		var element = this.getElementAtEvent(evt);
            for(var i=0;i<backgroundColor.length;i++){
				        backgroundColor[i] = 'rgba(142, 142, 142, 0.3)';
				    }
			      backgroundColor[element[0]._index] = '#<?php echo $m_color; ?>';
			      this.update();
		    			
            var l = item[0]['_model'].label;
            var a;
	           	if(l == '접수완료'){
								a = '1';
							}
							if(l == '조치완료'){
								a =  '2';
							}
							if(l == '진행중'){
								a =  '3';
							}
							if(l == '반려'){
								a =  '4';
							}
							
							//param초기화
							$("#emp_list_main").setGridParam({
								postData:{
									"TAB": a
								},
								page:1
							}).trigger("reloadGrid");
						}
						
						$('.chartClickText').text(l);
           
          },
			    	legend: {
				        display: false
				    },
		        scales: {
		            yAxes: [{
		                ticks: {
		                		stepSize : 1,
		                    min: 0,
										    beginAtZero: true
		                },
		                gridLines: {
						          color: '#ccc',
						          lineWidth: 0.2
						        }
		            }],
		            xAxes: [{
		                gridLines: {
						          color: '#ffffff',
						          lineWidth: 0.1
						        }
		            }]
		        },
		        title: {
		            display: true,
		            text: ' ',
		        },
		        hover: {
				      onHover: function(e) {
				         var point = this.getElementAtEvent(e);
				         if (point.length) e.target.style.cursor = 'pointer';
				         else e.target.style.cursor = 'default';
				      }
				   },
				   plugins: {
	            datalabels: {
	                formatter: function(value, context) {
	                    //return context.chart.data.labels[context.dataIndex];
	                    return value + ' 건';
	                },
	                offset: -20,
	            }
	        },
	        tooltips: {
	            callbacks: {
	                label: function(tooltipItem, data) {
	                    return data.labels[tooltipItem.index] + ' : ' + data.datasets[0].data[tooltipItem.index] + ' 건';
	                }
	            }
	        }
	        
		    }
		});

		
		<?php $i = 0; foreach ((array)$chart_b as $v) { 
			if($v->PROGRESS > 100){
				$v->PROGRESS = 100;
			}
			if($_SESSION['userauth'] == 'user'){
				$links = $v->PP_NM;;
			}else{
				$links = '<a class="link" href="'.site_url().'/pms/Write?id='.$v->PP_ID.'">'.$v->PP_NM.'</a>';
			}
		?>
			$('.p_boxw_app > .notlist').remove();
 			$('.p_boxw_app').append('<div class="p_boxw"><button data-toggle="tooltip" data-placement="bottom" title="WBS" class="btn btn-default btn-xs btn_wbs" datas="<?php echo $v->PP_ID;?>"><i class="fa fa-tasks"></i></button><strong class="p_boxw_title" title="<?php echo $v->PP_NM;?>"><?php echo $links; ?></strong><canvas id="chart_b<?php echo $i?>"></canvas><br /></div>');
 			
			var ctx2 = document.getElementById("chart_b<?php echo $i?>").getContext('2d');
			var myChart = new Chart(ctx2, {
			    type: 'doughnut',
			    data : {
					    datasets: [{
					        data: ["<?php echo $v->PROGRESS; ?>", "<?php echo 100 - $v->PROGRESS; ?>"],
					        backgroundColor: ["#<?php echo $m_color;?>",'#ededed','#ededed'],
					    }],
					    fontColor: "white",
					
					    // These labels appear in the legend and in the tooltips when hovering different arcs
					    labels: [
					        '진행',
					        '미진행'
					    ],
					},
			    options: {
			        title: {
			            display: false
			        },
			        legend: {
					        display: false
					    },
					    cutoutPercentage: 65,
			        layout: {
					        padding: {
					            left: 0,
					            right: 0,
					            top: 17,
					            bottom: 17
					        }
					    },
					    plugins: {
			            datalabels: {
			                formatter: function(value, context) {
			                    //return context.chart.data.labels[context.dataIndex];
			                    return value + ' %';
			                },
			                offset: -20,
			            }
			        },
			        tooltips: {
			            callbacks: {
			                label: function(tooltipItem, data) {
			                    return data.labels[tooltipItem.index] + ' : ' + data.datasets[0].data[tooltipItem.index] + ' %';
			                }
			            }
			        }
			    }
			});
		<?php $i++; } ?>
		
		//디스크용량
		if($('#chart_disk').size() > 0){
			var ctx_d = document.getElementById("chart_disk").getContext('2d');
				var myChart = new Chart(ctx_d, {
				    type: 'pie',
				    data : {
						    datasets: [{
						        data: ["<?php echo $dp;?>", "<?php echo sprintf('%1.2f' , (100 - $dp));?>"],
						        //data: ["0.00", "100.00"],
						        backgroundColor: ["<?php echo $back_style_color;?>",'#ededed','#ededed'],
						    }],
						    fontColor: "white",
						    labels: [
						        '사용량',
						        '남은량'
						    ],
						},
				    options: {
				        title: {
				        		display: false
				        },
				        legend: {
						        display: false
						    },
				        layout: {
						        padding: {
						            left: 0,
						            right: 0,
						            top: 17,
						            bottom: 17
						        }
						    },
						    responsive: true, 
								maintainAspectRatio: false,
								plugins: {
				            datalabels: {
				                formatter: function(value, context) {
				                    //return context.chart.data.labels[context.dataIndex];
				                    return value + ' %';
				                },
				                offset: -20,
				            }
				        },
				        tooltips: {
				            callbacks: {
				                label: function(tooltipItem, data) {
				                    return data.labels[tooltipItem.index] + ' : ' + data.datasets[0].data[tooltipItem.index] + ' %';
				                }
				            }
				        }
				    }
				});
			}
		
		$('[data-toggle="tooltip"]').tooltip();
		
		
		$("#emp_list_main").jqGrid({//그리드 세팅
	      url:'<?php echo site_url()?>'+'/rm/Main/loadData',      
	      mtype : "POST",             
	      datatype: "json",            
	      colNames:['PR_ID','제목','거래처','완료요청일','진행상태','INS_ID','작성자','최종수정일'],       
	      colModel:[
	          {name:'PR_ID',index:'PR_ID', width:100, align:"center", hidden:true},
	          {name:'PR_TITLE',index:'PR_TITLE', width:300, align:"left", formatter: prtitles},
			  {name:'PC_NM',index:'PC_NM', width:100, align:"center", hidden:true},
	          {name:'PR_HOPE_END_DAT',index:'PR_HOPE_END_DAT', width:150, align:"center", formatter:"date", formatoptions:{newformat:"Y-m-d"}},
	          {name:'PR_STATUS',index:'PR_STATUS', width:100, align:"center", formatter: prstatus},
	          {name:'INS_ID',index:'INS_ID', width:100, align:"center", hidden:true},
			  {name:'INS_NM',index:'INS_NM', width:100, align:"center", hidden:true},
	          {name:'UPD_DT',index:'UPD_DT', width:150, align:"center", formatter:"date", formatoptions:{newformat:"Y-m-d"}, hidden:true}
	      ],
	      postData : {
	      	"TAB": '1'
	      },
	      rowNum:30,
	      rowList:[30,100,500],
	      pager: '#emp_list_main_page',
	      sortname: 'UPD_DT',
		  	sortorder: 'desc',
		  	sorttype: 'date',
		  	shrinkToFit: true,
	      autowidth: true,
	      viewrecords: true,
	      //rownumbers: true,
	      gridview: true,
	      caption:"목록",
	      multiselect: false,
	      multiselectWidth: 60,
	      loadBeforeSend:function(){
	      	//기존 로딩 사요안함 style로 display none
	      	$('#loading').modal("show");
	      },
	      loadComplete:function(data){
	      	setTimeout(function(){
			    	$("#emp_list_main").jqGrid('setGridWidth',$('#gbox_emp_list_main').parent().innerWidth()-30);//넓이지정
						$("#emp_list_main").jqGrid('setGridHeight',$('.p_b1 .panel').innerHeight()-30);//높이지정
					},100);
					$('#loading').modal("hide");
					$('.gridCnt').html('전체 :<strong>'+$("#emp_list_main").getGridParam("records")+'</strong> 건');//카운트 넣기
					$(window).trigger('resize');
					$('[data-toggle="tooltip"]').tooltip();
	    }
		});
		
		//파일목록
		//그리드 세팅
		
		
		var cm = [
        {name:'PF_ID',index:'PF_ID', width:100, align:"center", hidden:true},
        {name:'PFD_ID',index:'PFD_ID', width:100, align:"center", hidden:true},
        {name:'PF_NM',index:'PF_NM', width:200, align:"left",formatter:formatOpt1},
	 			 {name:'PC_NM',index:'PC_NM', width:200, align:"center"},
        {name:'PF_PATH',index:'PF_PATH', width:100, align:"center",hidden:true},
        {name:'PF_FILE_SIZE',index:'PF_FILE_SIZE', width:100, align:"center",formatter:formatBytes, hidden:true},
        {name:'PF_FILE_EXT',index:'PF_FILE_EXT', width:100, align:"center",hidden:true},
        {name:'INS_ID',index:'INS_ID', width:100, align:"center", hidden:true},
        {name:'INS_DT',index:'INS_DT', width:100, align:"center",hidden:true},
        {name:'UPD_ID',index:'UPD_ID', width:100, align:"center",hidden:true},
        {name:'UPD_DT',index:'UPD_DT', width:100, align:"center", hidden:true},
        {name:'PF_FILE_TEMP_NM',index:'PF_FILE_TEMP_NM', width:100, align:"center",hidden: true},
        {name:'PF_FILE_REAL_NM',index:'PF_FILE_REAL_NM', width:100, align:"center",hidden: true},
        {name:'KEYWORD',index:'KEYWORD', width:100, align:"center",hidden: true},
        {name:'PF_CNT',index:'PF_CNT', width:100, align:"center", hidden:true}
    ];
		$("#pdm_list").jqGrid({
      url:'<?php echo site_url()?>'+'/pdm2/Main/loadData',      
      mtype : "POST",             
      postData:{
      	'docType' : 'all'
      },
      datatype: "json",            
      colNames:['PF_ID','PFD_ID','제목','거래처','PF_PATH','파일용량','PF_FILE_EXT','작성자','작성일','UPD_ID','최종수정일','PF_FILE_TEMP_NM','PF_FILE_REAL_NM','KEYWORD','FA_CNT'],       
      colModel:cm,
      height: 166,
      rowNum:4,
      rowList:[4,100,500],
      sortname: 'UPD_DT',
	  	sortorder: 'desc',
	  	sorttype: 'date',
	  	shrinkToFit: true,
      autowidth: true,
      viewrecords: true,
      //rownumbers: true,
      gridview: true,
      caption:"목록",
      multiselect: false,
      multiselectWidth: 60,
      loadBeforeSend:function(){
      	//기존 로딩 사요안함 style로 display none 동기시 작동안할듯
      	$('#loading').modal("show");
      },
      loadComplete:function(data){
      	
      	//넓이지정
      	$("#pdm_list").jqGrid('setGridWidth',$('#gbox_pdm_list').parent().width());
		
				//로딩 하이딩
				$('#loading').modal("hide");
				$(window).trigger('resize');
				$('[data-toggle="tooltip"]').tooltip();
		
      }
	  });
		
	});
	$(window).resize(function(){//리사이즈 이벤트
		setTimeout(function(){
			$("#emp_list_main").jqGrid('setGridWidth',$('#gbox_emp_list_main').parent().innerWidth() -30);//넓이지정
			$("#emp_list_main").jqGrid('setGridHeight',$('.p_b1 .panel').innerHeight()-30);//높이지정 54
			
			$("#pdm_list").jqGrid('setGridWidth',$('#gbox_pdm_list').parent().width());//넓이지정
		},200);
	});
</script>
<style>
	.p_box{
		padding:5px;
	}
	
	body{
		background:#fafafa
	}
	.p_boxw{
		width: 20%;
		float:left;
		border-left:1px dashed #ededed;
		position:relative
	}
	.p_boxw:first-child{
		border: none
	}
	.p_boxw_title{
		display:block;
		text-align:center;
		width: 80%;
		overflow:hidden;
		white-space: nowrap;
		padding: 7px 10% 0 10%;
		text-overflow: ellipsis;
		margin: 0 auto 10px auto;
	}
	#wp_right{
		background:#fafafa;
		max-width: 1700px
	}
	.p_box_50{
		width: 50%;
		float:left
	}
	.p_box_40{
		width: 40%;
		float:left
	}
	.p_box_60{
		width: 60%;
		float:left
	}
	.p_box h4 span{
		font-size:14px
	}
	.no_padding{
		padding: 0!important
	}
	.p_box .ui-jqgrid-bdiv{
		background:#fff
	}
	.ui-jqgrid .ui-jqgrid-hdiv{
		border: none
	}
	.p_boxw_app{
		/*min-height: 181px*/
	}
	.notlist{
		padding:15px
	}
	#pdm_list{
		position:relative!important;
		width:100%!important
	}
	#pdm_list tbody tr{
		display:block!important;
		width: 25%!important;
		float:left!important;
		height:165px!important;
		margin-bottom:-1px;
		overflow:hidden;
		border-left: 1px dashed #ededed;
		padding: 10px 0;
	}
	#pdm_list{
		margin-left:-1px
	}
	#pdm_list tbody tr td{
		border-bottom:none!important;
		display: block;
		width:100%!important;
		height: auto;
		padding:0!important;
		padding: 0 10px!important;
		position:relative;
		
		
	}
	#pdm_list tbody tr td:first-child{
		padding-top:10px!important;
		
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
	.dash_main .ui-jqgrid .ui-jqgrid-pager{
		background: #fefefe;
    border: 1px dashed #e7e7e7;
	}
	.dash_main .ui-jqgrid .ui-state-highlight{
		background:#fff!important
	}
	.dash_main .ui-jqgrid .ui-state-hover{
		background:#fafafa!important
	}
	.btn_wbs{
		position:absolute;
		right: 5%;
    top: 4px;
	}
	.dash_main .panel{
		border-top:1px solid #444!important;
		border-radius: 0;
    box-shadow: none;
    border-left: none;
    border-right: none;
    background: white;
	}
	.dash_main .ui-jqgrid-htable{
		display:none
	}
	.dash_main .ui-jqgrid .ui-pg-input{
		height: 14px;
    font-size: 12px;
    margin: -1px 0 0 0;
    border: none;
    background: transparent;
    text-align: right;
    width: 14px;
        padding: 0;
    line-height: 0;
	}
	.dash_main .ui-jqgrid .ui-pg-selbox{
		display:none;
	}
	.dash_main .ui-jqgrid .ui-paging-info{
		position: absolute;
    left: -10000px;
    overflow: hidden;
    top: -10000px;
	}
	.dash_main .ui-jqgrid .ui-jqgrid-pager{
		position: absolute;
    bottom: -46px;
    z-index: 1;
    background: transparent;
    border: none;
	}
	.dash_main .ui-jqgrid-bdiv table tr:last-child td{
		border-bottom:none
	}
	
	.t_style{
	 		background:#fafafa
		}
	 .t_style li{
		 	padding: 18px 15px;
		 	border-bottom: 1px solid #ededed;
		 	overflow:hidden;
		 	white-space: nowrap;
		 	text-overflow: ellipsis
		}
	
	
	
</style>

<div id="wp_right">
	<div class="grid_area">
		<div class="dash_main p-20">
			<div class="p_box box30">
				<div class="pb-20">
				  <h4><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> 최근 프로젝트 진행률 <small>Project</small><?php if($_SESSION['userauth']!='user'){?><button onclick=location.href="<?php echo site_url().'/pms/Main'; ?>" class="btn btn-default btn-xs pull-right"><i class="fa fa fa-plus"></i> MORE</button><?php } ?></?php></h4>
				</div>
				<div class="panel panel-default r_panel">
				  <div class="panel-body p_boxw_app">
				  	<div class="notlist">내용이 없습니다.</div>
				  </div>
				</div>
			</div>
			<div class="p_box p_box_50 p_b1">
				<div class="pb-20">
				  <h4><span class="glyphicon glyphicon-user" aria-hidden="false"></span> 요구사항 전체 <small>Requirement</small></h4>
				</div>
				<div class="panel panel-default">
				  <div class="panel-body">
				  	<canvas id="chart_a"></canvas>
				  </div>
				</div>
			</div>
			<div class="p_box p_box_50">
				<div class="pb-20">
				  <h4><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> 요구사항 <em style="font-style: normal" class="chartClickText">접수완료</em> <button onclick=location.href="<?php echo site_url().'/rm/Main'; ?>" class="btn btn-default btn-xs pull-right"><i class="fa fa fa-plus"></i> MORE</button></h4>
				</div>
				<div class="panel panel-default">
					<div class="panel-body">
						<table id="emp_list_main"></table>
						<div id="emp_list_main_page"></div>
				  	
					</div>
				</div>
			</div>
			<?php if($_SESSION['userauth']!='user'){?>
			<div class="clear"></div>
			<div class="p_box p_box_50">
				<div class="pb-20">
				  <h4><span class="glyphicon glyphicon-cloud" aria-hidden="false"></span> 최근 등록된 파일 <small>File</small></h4>
				</div>
				<div class="panel panel-default">
				  <div class="panel-body">
				  	<table id="pdm_list"></table>
				  </div>
				</div>
			</div>
			
			<div class="p_box p_box_50">
				<div class="pb-20">
				  <h4><span class="glyphicon glyphicon-hdd" aria-hidden="false"></span> DISK 현황 <small>Disk info</small><button onclick=location.href="<?php echo site_url().'/pdm2/Main'; ?>" class="btn btn-default btn-xs pull-right"><i class="fa fa fa-plus"></i> MORE</button></h4>
				</div>
				<div class="panel panel-default">
				  <div class="panel-body" style="height:196px;position:relative">
				  	<div style="position:absolute;left:10px;top:10px;width:60%;text-align:center">DISK 사용량(%)</div>
				  	<div class="p_box_50" style="margin-top:18px;width: 60%">
				  		<canvas id="chart_disk"></canvas>
				  	</div>
				  	<div class="p_box_50" style="width: 40%">
				  		<ul class="t_style">
				  			<li><i class="fa fa-angle-right"></i>&nbsp;전체용량 : <?php echo $dt;?></li>
				  			<li><i class="fa fa-angle-right"></i>&nbsp;사용량 : <?php echo $du;?></li>
				  			<li><i class="fa fa-angle-right"></i>&nbsp;남은량 : <?php echo $df;?></li>
				  		</ul>
				  	</div>
				  </div>
				</div>
			</div>
			
			<?php } ?>
		</div>
	</div>
</div>		

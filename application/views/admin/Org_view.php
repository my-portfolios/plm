<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$PageNm = 'admin';//seg 1
$PageType = 'Org_view';//seg 2
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

?>
<script>
$(window).load(function(){
});
</script>

<div class="p-20">
	<div class="pb-20">
		  <h3 class="getPdf">조직정보 <small>Organization chart</small></h3>
		</div>
	<div id="chart-container"></div>
</div>



<!--차트-->
<style>
	#wp{
		min-width: 960px
	}
	#chart-container{
		height:535px;
		box-sizing: border-box;
    display: inline-block;
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    background-image: linear-gradient(90deg, rgba(130, 130, 130, 0.15) 10%, rgba(0, 0, 0, 0) 10%), linear-gradient(rgba(130, 130, 130, 0.15) 10%, rgba(0, 0, 0, 0) 10%);
    background-size: 10px 10px;
    border: 1px dashed rgba(0,0,0,0);
    padding: 0 20px;
    width:100%;
    overflow:scroll;
	}
	#newMsg{
		display:none!important
	}
	#chart-container{
	}
	#chart-container .orgchart{width:100%;min-height:200px;background:none}
	.edge { display: none!important; }
	#edit-panel.edit-state>:not(#chart-state-panel) { display: none; }
	#edit-panel.edit-parent-node .selected-node-group { display: none; }
	#edit-panel.edit-parent-node button:not(#btn-add-nodes) { display: none; }
	#edit-panel.edit-parent-node .btn-inputs { display: none; }
	.orgImg img{
		height:50px!important
	}
</style>
<script type="text/javascript">
		$(window).load(function(){
			$('#chart-container').height($(window).height() - 115);
			$( window ).resize(function() {
			  $('#chart-container').height($(window).height() - 115);
			});
		});
    $(function() {
		
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
			var env = "";
			if(name[3] != ''){
				getPic(name[3],'.orgPic_'+name[3]+getIds);
				env = '<div style="margin-top:3px;cursor:pointer" class="msgP" data-toggle="tooltip" data-placement="top" title="쪽지보내기"><i class="fa fa-envelope-o" aria-hidden="true"></i> 쪽지보내기</div>';
			}else{
				env = '<div style="margin-top:3px" data-toggle="tooltip" data-placement="top" title="쪽지불가능"><i class="fa fa-times-circle" aria-hidden="true"></i> 쪽지불가능</div>';
			}
			if(name[0] ==''){name[0] = '-';}
			if(name[1] ==''){name[1] = '-';}
			if(name[2] ==''){name[2] = '-';}
      return '<div style="position:relative" class="orgImg"><div style="left: -33px;top: -15px;position:absolute;width:50px;height:50px;overflow:hidden;border-radius:100px" class="orgPic_'+name[3]+getIds+'"></div><div class="title" style="background:'+name[4]+'">'+name[0]+'</div><div class="content"><div>'+name[1]+'</div><div>'+name[2]+'</div></div><div>'+env+'</div>';
    };

    var oc = $('#chart-container').orgchart({
      'data' : datascource,
      'chartClass': 'edit-state',
      'parentNodeSymbol': '',
      'draggable': false,
      'createNode': function($node, data) {
        $node[0].id = data.name + '^'+getId();
      },
      'nodeTemplate': nodeTemplate,
      'initCompleted' : function(){
      	$('[data-toggle="tooltip"]').tooltip();
      }
      /*
      'exportButton' : true,
      'exportFilename' : '조직정보',
      'exportFileextension' : 'pdf'
      
      'zoom' : true,
      'zoominLimit' : 1.5,
      'zoomoutLimit' : 0.8,
      'exportButton' : true,
      'exportFilename' : 'org',
      'exportFileextension' : 'png'
      */
    });
    
  	if(!datascource.name){
  		$('.orgchart').remove();
  	}
		/*이름클릭시 메세지 보내기*/
		$(document).on('click','.orgImg .msgP',function(){
			var a = $(this).parent().parent().parent().attr('id');
			console.log(a);
			var aSplit = a.split('^');
			if(aSplit[3] != ''){
				msgView(aSplit[3]);
			}
		});
		
  });
  </script>
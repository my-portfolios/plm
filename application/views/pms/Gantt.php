<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!--test gantt -->
 	<link rel=stylesheet href="/lib/gantt/platform.css" type="text/css">
  <link rel=stylesheet href="/lib/gantt/libs/jquery/dateField/jquery.dateField.css" type="text/css">

  <link rel=stylesheet href="/lib/gantt/gantt.css" type="text/css">
  <link rel=stylesheet href="/lib/gantt/ganttPrint.css" type="text/css" media="print">
	
	<!--
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	-->
  <script src="/lib/gantt/libs/jquery/jquery.livequery.1.1.1.min.js"></script>
  <script src="/lib/gantt/libs/jquery/jquery.timers.js"></script>

  <script src="/lib/gantt/libs/utilities.js"></script>
  <script src="/lib/gantt/libs/forms.js"></script>
  <script src="/lib/gantt/libs/date.js"></script>
  <script src="/lib/gantt/libs/dialogs.js"></script>
  <script src="/lib/gantt/libs/layout.js"></script>
  <script src="/lib/gantt/libs/i18nJs.js"></script>
  <script src="/lib/gantt/libs/jquery/dateField/jquery.dateField.js"></script>
  <script src="/lib/gantt/libs/jquery/JST/jquery.JST.js"></script>

  <script type="text/javascript" src="/lib/gantt/libs/jquery/svg/jquery.svg.min.js"></script>
  <script type="text/javascript" src="/lib/gantt/libs/jquery/svg/jquery.svgdom.1.8.js"></script>


  <script src="/lib/gantt/ganttUtilities.js"></script>
  <script src="/lib/gantt/ganttTask.js"></script>
  <script src="/lib/gantt/ganttDrawerSVG.js"></script>
  <script src="/lib/gantt/ganttZoom.js"></script>
  <script src="/lib/gantt/ganttGridEditor.js"></script>
  <script src="/lib/gantt/ganttMaster.js"></script>  
<!--test gantt-->


<style>
	table th{
		vertical-align:middle!important;
		text-align:center!important
	}
	svg:not(:root){
		overflow:inherit
	}
</style>
<div id="workSpace" style="padding:0px; overflow:hidden;border:1px solid #e5e5e5; position:relative;"></div>

<script type="text/javascript">

var ge;
$(function() {
	
	fn_chkId();

	// here starts gantt initialization
	ge = new GanttMaster();
	ge.set100OnClose=true;

	ge.init($("#workSpace"));
	loadI18n(); //overwrite with localized ones

	//in order to force compute the best-fitting zoom level
	delete ge.gantt.zoom;
	
	//var project=loadFromLocalStorage();
	
	//var project=loadGanttFromServer('## echo $list->PP_ID; ##');
	var project=loadGanttFromServer();
	
	if (!project.canWrite)
	$(".ganttButtonBar button.requireWrite").attr("disabled","true");
	
	ge.loadProject(project);
	ge.checkpoint(); //empty the undo stack
	
	// 담당자 선택 시 간트차트 작업자 리스트에 추가
	$(document).on('click','.btnOk_emp',function(){
		fn_getEmp();
	});
	
});


function fn_getEmp(){
	
	ge.resources = [];
	
	var data = $('#emp_left').jqGrid('getRowData');
//	console.log('data',data);
	$.each(data,function(i,v){
		ge.createResource(v.a,v.b);
	});
}

/* get id가 존재하는 프로젝트인지 확인 */
function fn_chkId(){
	var id ="<?php echo $this->input->get('id'); ?>";
	if(id != ''){
		var data = {
			"id": id,
		};
		$.ajax({
			type: 'post',
			dataType: 'json',
			url: '<?php echo base_url();?>index.php/pms/Gantt/chkId',
			data: data,
			async : false,
			success: function (data) {
				if(data.cnt != 1){
					/*location.href= "<?php echo site_url();?>/pms/Main";*/
					var now = "<?php echo $this->uri->segment(1);?>";
					if(now != 'pms'){
						bootbox.alert({
							size:'small',
							message : '프로젝트가 삭제되었습니다.',
						  buttons: {
							ok: {
								label: '확인',
								className: "btn-<?php echo $this->config->item($this->uri->segment(1).'Color')?>"
							}
						}
						});
					}
				}
			},
			error: function (request, status, error) {
				console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
			}
		});
	}
}


function getProject(){
	
	var data = {
		"PP_ID" : "<?php echo $this->input->get('id');?>"
	};
	
  	$.ajax({
		type: 'post',
		dataType: 'json',
		url: '<?php echo base_url();?>index.php/pms/Gantt/getData',
		data: data,
		async : false,
		success: function (data) {
			
			ret = data;
			
		},
		error: function (request, status, error) {
			console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
		}
	});
	
	/*
	
	ret= {
			"tasks":	[
				{"id": -1, "name": "프로젝트", "progress": 0, "progressByWorklog": false, "relevance": 0, "type": "", "typeId": "", "description": "", "code": "", "level": 0, "status": "STATUS_ACTIVE", "depends": "", "canWrite": true, "start": 1396994400000, "duration": 20, "end": 1399586399999, "startIsMilestone": false, "endIsMilestone": false, "collapsed": false, "assigs": [], "hasChild": true},
				{"id": -2, "name": "프로젝트", "progress": 0, "progressByWorklog": false, "relevance": 0, "type": "", "typeId": "", "description": "", "code": "", "level": 0, "status": "STATUS_ACTIVE", "depends": "", "canWrite": true, "start": 1396994400000, "duration": 20, "end": 1399586399999, "startIsMilestone": false, "endIsMilestone": false, "collapsed": false, "assigs": [], "hasChild": true},
				
			]
			, "selectedRow": 0
			, "deletedTaskIds": []
			, "resources": [
			  {"id": "tmp_1", "name": "Resource 1"},
			  {"id": "tmp_2", "name": "Resource 2"},
			  {"id": "tmp_3", "name": "Resource 3"},
			  {"id": "tmp_4", "name": "Resource 4"}
			]
			, "roles":	[
			  {"id": "tmp_1", "name": "Project Manager"},
			  {"id": "tmp_2", "name": "Worker"},
			  {"id": "tmp_3", "name": "Stakeholder"},
			  {"id": "tmp_4", "name": "Customer"}
			]
			, "canWrite": true
			, "canDelete":true
			, "canWriteOnParent": true
			, canAdd:true
			, zoom: "1M"
		};
		*/
    //actualize data
	/*
	if(ret.tasks.length > 0){
    var offset=new Date().getTime()-ret.tasks[0].start;
	console.log(ret.tasks[0].start);
    for (var i=0;i<ret.tasks.length;i++) {
      ret.tasks[i].start = ret.tasks[i].start + offset;
    }
	}
	*/
  return ret;
}



function loadGanttFromServer(taskId, callback) {
	
	var id ="<?php echo $this->input->get('id'); ?>";
	
	var ret=getProject();
//	var ret=getProject();
	
	return ret;
}

//저장
function saveGanttOnServer() {
	
	bootbox.confirm({
		size: "small",
		message: "저장하시겠습니까?", 
		buttons: {
			confirm: {
				label: '확인',
				className: "btn-<?php echo $this->config->item($this->uri->segment(1).'Color');?>"
			},
			cancel: {
				label: '취소'
			}
		},
		callback: function(result){
	
			if(result == true){
				fn_gantt_save(ge.saveProject().tasks, '<?php echo $this->input->get('id');?>');
				//console.log(ge.saveProject().tasks);
			}
		}
	});
	
}

//저장
function fn_gantt_save(tasks,pp_id){
	
	//var a = JSON.stringify(tasks.tasks);
	var data = {
		"tasks": tasks,
		"PP_ID" : pp_id
	};

  	$.ajax({
		type: 'post',
		dataType: 'json',
		url: '<?php echo base_url();?>index.php/pms/Gantt/save',
		data: data,
		async : false,
		success: function (data) {
			//console.log('save',data);
		},
		error: function (request, status, error) {
			console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
		}
	});
	
}

//프로젝트 삭제 초기화 리셋
function newProject(){
  clearGantt();
}

//이하동문
function clearGantt() {
  ge.reset();
}



function editResources(){
  //make resource editor
  var resourceEditor = $.JST.createFromTemplate({}, "RESOURCE_EDITOR");
  var resTbl=resourceEditor.find("#resourcesTable");

  for (var i=0;i<ge.resources.length;i++){
    var res=ge.resources[i];
    resTbl.append($.JST.createFromTemplate(res, "RESOURCE_ROW"))
  }


  //bind add resource
  resourceEditor.find("#addResource").click(function(){
    resTbl.append($.JST.createFromTemplate({id:"new",name:"resource"}, "RESOURCE_ROW"))
  });

  //bind save event
  resourceEditor.find("#resSaveButton").click(function(){
    var newRes=[];
    //find for deleted res
    for (var i=0;i<ge.resources.length;i++){
      var res=ge.resources[i];
      var row = resourceEditor.find("[resId="+res.id+"]");
      if (row.length>0){
        //if still there save it
        var name = row.find("input[name]").val();
        if (name && name!="")
          res.name=name;
        newRes.push(res);
      } else {
        //remove assignments
        for (var j=0;j<ge.tasks.length;j++){
          var task=ge.tasks[j];
          var newAss=[];
          for (var k=0;k<task.assigs.length;k++){
            var ass=task.assigs[k];
            if (ass.resourceId!=res.id)
              newAss.push(ass);
          }
          task.assigs=newAss;
        }
      }
    }

    //loop on new rows
    var cnt=0
    resourceEditor.find("[resId=new]").each(function(){
      cnt++;
      var row = $(this);
      var name = row.find("input[name]").val();
      if (name && name!="")
        newRes.push (new Resource("tmp_"+new Date().getTime()+"_"+cnt,name));
    });

    ge.resources=newRes;

    closeBlackPopup();
    ge.redraw();
  });


  var ndo = createModalPopup(400, 500).append(resourceEditor);
}




  function loadI18n(){
    GanttMaster.messages = {
      "CANNOT_WRITE":"다음 작업을 변경할 권한이 없습니다:",
      "CHANGE_OUT_OF_SCOPE":"부모 작업 업데이트 권한이 없기 때문에 업데이트 할 수 없습니다.",
      "START_IS_MILESTONE":"시작일이 마일스톤으로 되어있습니다.",
      "END_IS_MILESTONE":"종료일이 마일스톤으로 되어있습니다.",
      "TASK_HAS_CONSTRAINTS":"작업에 제약이 있습니다.",
      "GANTT_ERROR_DEPENDS_ON_OPEN_TASK":"오류 : 다른 작업에 대한 의존성이 있습니다.",
      "GANTT_ERROR_DESCENDANT_OF_CLOSED_TASK":"오류 : 하위 작업을 닫아주세요.",
      "TASK_HAS_EXTERNAL_DEPS":"이 작업에는 외부 종속성이 있습니다.",
      "GANNT_ERROR_LOADING_DATA_TASK_REMOVED":"GANNT_ERROR_LOADING_DATA_TASK_REMOVED",
      "CIRCULAR_REFERENCE":"아이디를 확인하세요.",
      "CANNOT_DEPENDS_ON_ANCESTORS":"메인 단위작업에는 의존할 수 없습니다.",
      "INVALID_DATE_FORMAT":"형식에 유효하지 않습니다.",
      "GANTT_ERROR_LOADING_DATA_TASK_REMOVED":"데이터로드 중 오류가 발생했습니다.",
      "CANNOT_CLOSE_TASK_IF_OPEN_ISSUE":"작업을 완료해 주세요.",
      "TASK_MOVE_INCONSISTENT_LEVEL":"연결의 깊이가 달라 작업을 할 수 없습니다.",
      "CANNOT_MOVE_TASK":"CANNOT_MOVE_TASK",
      "PLEASE_SAVE_PROJECT":"PLEASE_SAVE_PROJECT",
      "GANTT_SEMESTER":"반기",
      "GANTT_SEMESTER_SHORT":"반기.",
      "GANTT_QUARTER":"분기",
      "GANTT_QUARTER_SHORT":"분기.",
      "GANTT_WEEK":"주",
      "GANTT_WEEK_SHORT":"주."
    };
  }
  
  
  
</script>

<div id="gantEditorTemplates" style="display:none;">
<div class="__template__" type="GANTBUTTONS"><!--
  <div class="ganttButtonBar noprint">
    <div class="buttons">
      
      <button onclick="$('#workSpace').trigger('undo.gantt');return false;" class="viewOnly button textual icon requireCanWrite" title="실행 취소"><span class="teamworkIcon">&#39;</span></button>
      <button onclick="$('#workSpace').trigger('redo.gantt');return false;" class="viewOnly button textual icon requireCanWrite" title="다시 실행"><span class="teamworkIcon">&middot;</span></button>
      <span class="viewOnly ganttButtonSeparator requireCanWrite requireCanAdd"></span>
      <button onclick="$('#workSpace').trigger('addAboveCurrentTask.gantt');return false;" class="viewOnly button textual icon requireCanWrite requireCanAdd" title="윗줄에 추가"><span class="teamworkIcon">l</span></button>
      <button onclick="$('#workSpace').trigger('addBelowCurrentTask.gantt');return false;" class="viewOnly button textual icon requireCanWrite requireCanAdd" title="아랫줄에 추가"><span class="teamworkIcon">X</span></button>
      <span class="viewOnly ganttButtonSeparator requireCanWrite requireCanInOutdent"></span>
      <button onclick="$('#workSpace').trigger('outdentCurrentTask.gantt');return false;" class="viewOnly button textual icon requireCanWrite requireCanInOutdent" title="들여쓰기 취소"><span class="teamworkIcon">.</span></button>
      <button onclick="$('#workSpace').trigger('indentCurrentTask.gantt');return false;" class="viewOnly button textual icon requireCanWrite requireCanInOutdent" title="들여쓰기"><span class="teamworkIcon">:</span></button>
      <span class="viewOnly ganttButtonSeparator requireCanWrite requireCanMoveUpDown"></span>
      <button onclick="$('#workSpace').trigger('moveUpCurrentTask.gantt');return false;" class="viewOnly button textual icon requireCanWrite requireCanMoveUpDown" title="위로"><span class="teamworkIcon">k</span></button>
      <button onclick="$('#workSpace').trigger('moveDownCurrentTask.gantt');return false;" class="viewOnly button textual icon requireCanWrite requireCanMoveUpDown" title="아래로"><span class="teamworkIcon">j</span></button>
      <span class="viewOnly ganttButtonSeparator requireCanWrite requireCanDelete"></span>
      <button onclick="$('#workSpace').trigger('deleteFocused.gantt');return false;" class="viewOnly button textual icon delete requireCanWrite" title="삭제"><span class="teamworkIcon">&cent;</span></button>
      <span class="viewOnly ganttButtonSeparator"></span>
      <button onclick="$('#workSpace').trigger('expandAll.gantt');return false;" class="viewOnly button textual icon " title="열기"><span class="teamworkIcon">6</span></button>
      <button onclick="$('#workSpace').trigger('collapseAll.gantt'); return false;" class="viewOnly button textual icon " title="닫기"><span class="teamworkIcon">5</span></button>

    <span class="viewOnly ganttButtonSeparator"></span>
      <button onclick="$('#workSpace').trigger('zoomMinus.gantt'); return false;" class="button textual icon " title="축소"><span class="teamworkIcon">)</span></button>
      <button onclick="$('#workSpace').trigger('zoomPlus.gantt');return false;" class="button textual icon " title="확대"><span class="teamworkIcon">(</span></button>
    <span class="ganttButtonSeparator"></span>

      <button onclick="ge.splitter.resize(.1);return false;" class="button textual icon" ><span class="teamworkIcon">F</span></button>
      <button onclick="ge.splitter.resize(50);return false;" class="button textual icon" ><span class="teamworkIcon">O</span></button>
      <button onclick="ge.splitter.resize(100);return false;" class="button textual icon"><span class="teamworkIcon">R</span></button>
      <span class="viewOnly ganttButtonSeparator"></span>
      <button onclick="$('#workSpace').trigger('fullScreen.gantt');return false;" class="viewOnly button textual icon" title="전체화면" id="fullscrbtn"><span class="teamworkIcon">@</span></button>
      

    
    
    <button class="button login" title="login/enroll" onclick="loginEnroll($(this));" style="display:none;">login/enroll</button>
    <button class="button opt collab" title="Start with Twproject" onclick="collaborate($(this));" style="display:none;"><em>collaborate</em></button>
    </div></div>
  --></div>

<div class="__template__" type="TASKSEDITHEAD"><!--
  <table class="gdfTable" cellspacing="0" cellpadding="0">
    <thead>
    <tr style="height:40px">
      <th class="gdfColHeader" style="width:35px; border-right: none"></th>
      <th class="gdfColHeader" style="width:25px;"></th>
      <th class="gdfColHeader" style="width:100px;">단위코드</th>
      <th class="gdfColHeader" style="width:300px;">단위제목</th>
      <th class="gdfColHeader"  align="center" style="width:17px;" title="Start date is a milestone."><span class="teamworkIcon" style="font-size: 8px;">^</span></th>
      <th class="gdfColHeader" style="width:80px;">시작일</th>
      <th class="gdfColHeader"  align="center" style="width:17px;" title="End date is a milestone."><span class="teamworkIcon" style="font-size: 8px;">^</span></th>
      <th class="gdfColHeader" style="width:80px;">종료일</th>
      <th class="gdfColHeader" style="width:50px;">DAY</th>
      <th class="gdfColHeader" align="center" style="width:50px;">%</th>
      <th class="gdfColHeader requireCanSeeDep" style="width:50px;">연결</th>
      <th class="gdfColHeader" style="width:1000px; text-align: left; padding-left: 10px;">작업자</th>
    </tr>
    </thead>
  </table>
  --></div>

<div class="__template__" type="TASKROW"><!--
  <tr id="tid_(#=obj.id#)" taskId="(#=obj.id#)" class="taskEditRow (#=obj.isParent()?'isParent':''#) (#=obj.collapsed?'collapsed':''#)" level="(#=level#)">
    <th class="gdfCell edit" align="right" style="cursor:pointer;"><span class="taskRowIndex">(#=obj.getRow()+1#)</span> <span class="teamworkIcon" style="font-size:12px;" >e</span></th>
    <td class="gdfCell noClip" align="center"><div class="taskStatus cvcColorSquare" status="(#=obj.status#)"></div></td>
    <td class="gdfCell"><input type="text" name="code" value="(#=obj.code?obj.code:''#)" placeholder="ex P1-1"></td>
    <td class="gdfCell indentCell" style="padding-left:(#=obj.level*10+18#)px;">
      <div class="exp-controller" align="center"></div>
      <input type="text" name="name" value="(#=obj.name#)" placeholder="제목을 입력해주세요.">
    </td>
    <td class="gdfCell" align="center"><input type="checkbox" name="startIsMilestone"></td>
    <td class="gdfCell"><input type="text" name="start"  value="" class="date"></td>
    <td class="gdfCell" align="center"><input type="checkbox" name="endIsMilestone"></td>
    <td class="gdfCell"><input type="text" name="end" value="" class="date"></td>
    <td class="gdfCell"><input type="text" name="duration" autocomplete="off" value="(#=obj.duration#)"></td>
    <td class="gdfCell"><input type="text" name="progress" class="validated" entrytype="PERCENTILE" autocomplete="off" value="(#=obj.progress?obj.progress:''#)" (#=obj.progressByWorklog?"readOnly":""#)></td>
    <td class="gdfCell requireCanSeeDep"><input type="text" name="depends" autocomplete="off" value="(#=obj.depends#)" (#=obj.hasExternalDep?"readonly":""#)></td>
    <td class="gdfCell taskAssigs">(#=obj.getAssigsString()#)</td>
  </tr>
  --></div>

<div class="__template__" type="TASKEMPTYROW"><!--
  <tr class="taskEditRow emptyRow" >
    <th class="gdfCell" align="right"></th>
    <td class="gdfCell noClip" align="center"></td>
    <td class="gdfCell"></td>
    <td class="gdfCell"></td>
    <td class="gdfCell"></td>
    <td class="gdfCell"></td>
    <td class="gdfCell"></td>
    <td class="gdfCell"></td>
    <td class="gdfCell"></td>
    <td class="gdfCell"></td>
    <td class="gdfCell requireCanSeeDep"></td>
    <td class="gdfCell"></td>
  </tr>
  --></div>

<div class="__template__" type="TASKBAR"><!--
  <div class="taskBox taskBoxDiv" taskId="(#=obj.id#)" >
    <div class="layout (#=obj.hasExternalDep?'extDep':''#)">
      <div class="taskStatus" status="(#=obj.status#)"></div>
      <div class="taskProgress" style="width:(#=obj.progress>100?100:obj.progress#)%; background-color:(#=obj.progress>100?'red':'rgb(153,255,51);'#);"></div>
      <div class="milestone (#=obj.startIsMilestone?'active':''#)" ></div>

      <div class="taskLabel"></div>
      <div class="milestone end (#=obj.endIsMilestone?'active':''#)" ></div>
    </div>
  </div>
  --></div>


<div class="__template__" type="CHANGE_STATUS"><!--
    <div class="taskStatusBox">
    <div class="taskStatus cvcColorSquare" status="STATUS_ACTIVE" title="Active"></div>
    <div class="taskStatus cvcColorSquare" status="STATUS_DONE" title="Completed"></div>
    <div class="taskStatus cvcColorSquare" status="STATUS_FAILED" title="Failed"></div>
    <div class="taskStatus cvcColorSquare" status="STATUS_SUSPENDED" title="Suspended"></div>
    <div class="taskStatus cvcColorSquare" status="STATUS_WAITING" title="Waiting" style="display: none;"></div>
    <div class="taskStatus cvcColorSquare" status="STATUS_UNDEFINED" title="Undefined"></div>
    </div>
  --></div>




<div class="__template__" type="TASK_EDITOR"><!--
  <div class="ganttTaskEditor">
    <h2 class="taskData mb-10">수정</h2>
    <div class="overTable">
    <table  cellspacing="1" cellpadding="5" width="100%" class="taskData table" border="0">
          <tr>
        <td width="200" style="height: 80px"  valign="top">
          <label for="code">단위코드</label><br>
          <input type="text" name="code" id="code" value="" size=15 class="form-control" autocomplete='off' maxlength=255 style='width:100%' oldvalue="1" placeholder="ex P1-1">
        </td>
        <td colspan="3" valign="top"><label for="name" class="required">단위제목</label><br><input type="text" name="name" id="name"class="form-control" autocomplete='off' maxlength=255 style='width:100%' value="" required="true" oldvalue="1" placeholder="단위 작업 제목을 입력해주세요."></td>
          </tr>


      <tr class="dateRow">
      <td nowrap="" >
          <label for="duration" class=" ">DAY</label><br>
          <input type="text" name="duration" id="duration" size="4" class="form-control validated durationdays" title="Duration is in working days." autocomplete="off" maxlength="255" value="" oldvalue="1" entrytype="DURATIONDAYS">&nbsp;
        </td>
        <td nowrap="">
          <div style="position:relative">
            <label for="start">시작일</label>&nbsp;&nbsp;
            <input type="checkbox" id="startIsMilestone" style="margin: -4px 0 0 0;" name="startIsMilestone" value="yes"> &nbsp;<label for="startIsMilestone">마일스톤</label>&nbsp;
            <br>
          	<div class="">  
	            <div class='input-group'>
			            <input type="text" name="start" id="start" size="8" class="form-control dateField validated date datetimepicker" autocomplete="off" maxlength="255" value="" oldvalue="1" entrytype="DATE">
			            <span class="input-group-addon">
			                <span title="calendar" id="starts_inputDate" class="teamworkIcon openCalendar" onclick="$(this).dateField({inputField:$(this).parent().prevAll(':input:first'),isSearchField:false});">m</span>
			            </span>
			        </div>
		        </div>
          </div>
        </td>
        <td nowrap="">
          <label for="end">종료일</label>&nbsp;&nbsp;
          <input type="checkbox" id="endIsMilestone" style="margin: -4px 0 0 0;" name="endIsMilestone" value="yes"> &nbsp;<label for="endIsMilestone">마일스톤</label>&nbsp;
          <br>
          
          <div class="">
           <div class='input-group'>
		            <input type="text" name="end" id="end" size="8" class="width_200px form-control dateField validated date" autocomplete="off" maxlength="255" value="" oldvalue="1" entrytype="DATE">
		            <span class="input-group-addon">
		                <span title="calendar" id="ends_inputDate" class="teamworkIcon openCalendar" onclick="$(this).dateField({inputField:$(this).parent().prevAll(':input:first'),isSearchField:false});">m</span>
		            </span>
		        </div>
		       </div>
        </td>
        
      </tr>

      <tr>
        <td>
          <label for="status" class=" ">상태</label><br>
          <select id="status" name="status" class="form-control" status="(#=obj.status#)"  onchange="$(this).attr('STATUS',$(this).val());">
            <option value="STATUS_ACTIVE" class="taskStatus" status="STATUS_ACTIVE" >진행중</option>
            <option value="STATUS_WAITING" class="taskStatus" status="STATUS_WAITING" >연결1</option>
            <option value="STATUS_SUSPENDED" class="taskStatus" status="STATUS_SUSPENDED" >연결2</option>
            <option value="STATUS_DONE" class="taskStatus" status="STATUS_DONE" >완료</option>
            <option value="STATUS_FAILED" class="taskStatus" status="STATUS_FAILED" >실패</option>
            <option value="STATUS_UNDEFINED" class="taskStatus" status="STATUS_UNDEFINED" >없음</option>
          </select>
        </td>

        <td valign="top" nowrap>
          <label>진행률</label><br>
          <input type="text" name="progress" id="progress" size="7" class="form-control validated percentile width_100px text-right" autocomplete="off" maxlength="255" value="" oldvalue="1" entrytype="PERCENTILE">
          %
        </td>
        <td></td>
      </tr>

          </tr>
          <tr>
            <td colspan="4">
              <label for="description">설명</label><br>
              <textarea rows="3" cols="30" id="description" name="description" class="form-control" style="width:100%"></textarea>
            </td>
          </tr>
        </table>
		</div>
    <h2 class="mb-10">
    	작업자 <button id="addAssig" class="btn btn-default btn-xs pull-right">작업자추가</button>
    </h2>
    <div style="height:2px;overflow:hidden;" class="bg-<?php echo $this->config->item($this->uri->segment(1).'Color');?>"></div>
   <div style="max-height:103px;overflow-x:hidden;overflow-y:auto">
  <table  cellspacing="1" cellpadding="0" width="100%" id="assigsTable">
  </table>
  </div>

  <div style="text-align: right; padding-top: 20px">
    
    
    <button type="button" id="saveButton" class="btn btn-<?php echo $this->config->item($this->uri->segment(1).'Color');?> btn-sm mt-10 " onClick="$(this).trigger('saveFullEditor.gantt');">
			확인
		</button>
    
    
  </div>

  </div>
  --></div>



<div class="__template__" type="ASSIGNMENT_ROW"><!--
  <tr taskId="(#=obj.task.id#)" assId="(#=obj.assig.id#)" class="assigEditRow" >
    <td style="padding:8px">
    	<select name="resourceId"  class="form-control width_100px" (#=obj.assig.id.indexOf("tmp_")==0?"":"disabled"#) ></select>
    	&nbsp;작업자를 선택해 주세요.
    </td>
    
    <td align="center"><span class="teamworkIcon delAssig del" style="cursor: pointer">d</span></td>
  </tr>
  --></div>



<div class="__template__" type="RESOURCE_EDITOR"><!--
  <div class="resourceEditor" style="padding: 5px;">

    <h2>Project team</h2>
    <table  cellspacing="1" cellpadding="0" width="100%" id="resourcesTable">
      <tr>
        <th style="width:100px;">name</th>
        <th style="width:30px;" id="addResource"><span class="teamworkIcon" style="cursor: pointer">+</span></th>
      </tr>
    </table>

    <div style="text-align: right; padding-top: 20px"><button id="resSaveButton" class="button big">Save</button></div>
  </div>
  --></div>



<div class="__template__" type="RESOURCE_ROW"><!--
  <tr resId="(#=obj.id#)" class="resRow" >
    <td ><input type="text" name="name" value="(#=obj.name#)" style="width:100%;" class="form-control"></td>
    <td align="center"><span class="teamworkIcon delRes del" style="cursor: pointer">d</span></td>
  </tr>
  --></div>


</div>



<script>
	$.JST.loadDecorator("RESOURCE_ROW", function(resTr, res){
    resTr.find(".delRes").click(function(){$(this).closest("tr").remove()});
  });

  $.JST.loadDecorator("ASSIGNMENT_ROW", function(assigTr, taskAssig){
    var resEl = assigTr.find("[name=resourceId]");
    var opt = $("<option>");
    resEl.append(opt);
    for(var i=0; i< taskAssig.task.master.resources.length;i++){
      var res = taskAssig.task.master.resources[i];
      opt = $("<option>");
      opt.val(res.id).html(res.name);
      if(taskAssig.assig.resourceId == res.id)
        opt.attr("selected", "true");
      resEl.append(opt);
    }
	

    if(taskAssig.task.master.permissions.canWrite && taskAssig.task.canWrite){
      assigTr.find(".delAssig").click(function(){
        var tr = $(this).closest("[assId]").fadeOut(200, function(){$(this).remove()});
      });
    }

  });
	function createNewResource(el) {
		var row = el.closest("tr[taskid]");
		var name = row.find("[name=resourceId_txt]").val();
		var url = contextPath + "/applications/teamwork/resource/resourceNew.jsp?CM=ADD&name=" + encodeURI(name);

		openBlackPopup(url, 700, 320, function (response) {
		  //fillare lo smart combo
		  if (response && response.resId && response.resName) {
			//fillare lo smart combo e chiudere l'editor
			row.find("[name=resourceId]").val(response.resId);
			row.find("[name=resourceId_txt]").val(response.resName).focus().blur();
		  }

		});
	  }
</script>
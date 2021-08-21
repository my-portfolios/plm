var testCount=0;
function enqueueNewTest() {
  var test = ganttTestUnits.shift();
  if (!test)
    return;


  //ci si registra per gli eventi di refresh

  ge.element.one("gantt.redrawCompleted", function () {
    //si registra l'evento di validazione al refresh
    ge.element.one("gantt.redrawCompleted", function () {
      if (test.assertOk())
        console.debug("------------------------- OK!");
      else
        console.error("Test "+testCount+ " \""+test.name+"\"------------------------- FAILED!");

      //si passa al test successivo
      setTimeout(enqueueNewTest, 200);
      //enqueueNewTest();
    });

    //si chiama la funzione di preparazione del test
    console.debug("Test "+testCount+ " \""+test.name+"\"");
    test.prepareTest();
    testCount++;
  });


  //se nel test ci sono i task si resetta il gantt
  if (test.tasks) {
    //si resetta tutto
    ge.reset();

    //si prepara un progetto
    var prj = {
      tasks:                      test.tasks,
      resources:                  [],
      roles:                      [],
      //permessi
      canWriteOnParent:           true,
      canWrite:                   true,
      canAdd:                     true,
      canInOutdent:               true,
      canMoveUpDown:              true,
      canSeePopEdit:              true,
      canSeeFullEdit:             true,
      canSeeDep:                  true,
      canSeeCriticalPath:         true,
      canAddIssue:                false,
      cannotCloseTaskIfIssueOpen: false
    };

    //si carica il progetto
    ge.loadProject(prj);


    // se i task non ci sono si parte dallo stato lasciato dall'ultimo test
  } else {
    //si lancia l'evento facendo finta di avere caricato tutti itask
    ge.element.trigger("gantt.redrawCompleted");
  }

}


$(function () {
  console.debug("Gantt test unit activated");
  $("#workSpace").one("gantt.redrawCompleted", function () {
    setTimeout(enqueueNewTest, 1000);
  });
});


//---------------------------------------------------------------------  TEST UNIT DEFINITIONS ------------------------------------------------------------------------------------

var ganttTestUnits = [];
// 22 --------------------------------------------------------------------------------------------------------------
ganttTestUnits.push({name: "Always shrink flag OFF: accorciando la durata di un figlio da 2 a 1 il padre deve rimanere a 4",
  tasks: [
    {"id":"tmp_1","name":"p","progress":0,"progressByWorklog":false,"relevance":0,"type":"","typeId":"","description":"","code":"T3547","level":0,"status":"STATUS_ACTIVE","depends":"","start":1512946800000,"duration":4,"end":1513292399999,"startIsMilestone":false,"endIsMilestone":false,"collapsed":false,"canWrite":true,"canAdd":true,"canDelete":true,"canAddIssue":true,"assigs":[],"loadComplete":false,"statusColor":"#3BBF67","tags":"","color":"","typeCode":"","totalWorklog":0,"totalEstimated":0,"totalEstimatedFromIssues":0,"totalIssues":0,"openIssues":0,"lastModified":1512984195515,"lastModifier":"System Manager","budget":0,"totalCosts":0},
    {"id":"tmp_2","name":"a","progress":0,"progressByWorklog":false,"relevance":0,"type":"","typeId":"","description":"","code":"T3547.01","level":1,"status":"STATUS_ACTIVE","depends":"","start":1512946800000,"duration":2,"end":1513119599999,"startIsMilestone":false,"endIsMilestone":false,"collapsed":false,"canWrite":true,"canAdd":true,"canDelete":true,"canAddIssue":true,"assigs":[],"loadComplete":false,"statusColor":"#3BBF67","color":"","typeCode":"","totalWorklog":0,"totalEstimated":0,"totalEstimatedFromIssues":0,"totalIssues":0,"openIssues":0,"lastModified":1512984195646,"lastModifier":"System Manager","parentId":"3547","budget":0,"totalCosts":0},
    {"id":"tmp_3","name":"b","progress":0,"progressByWorklog":false,"relevance":0,"type":"","typeId":"","description":"","code":"T3547.02","level":1,"status":"STATUS_WAITING","depends":"2","start":1513119600000,"duration":2,"end":1513292399999,"startIsMilestone":false,"endIsMilestone":false,"collapsed":false,"canWrite":true,"canAdd":true,"canDelete":true,"canAddIssue":true,"assigs":[],"loadComplete":false,"statusColor":"#F79136","color":"","typeCode":"","totalWorklog":0,"totalEstimated":0,"totalEstimatedFromIssues":0,"totalIssues":0,"openIssues":0,"lastModified":1512984195682,"lastModifier":"System Manager","parentId":"3547","budget":0,"totalCosts":0}
  ],
  prepareTest:             function () {
    ge.shrinkParent=false;
    ge.tasks[1].rowElement.find("[name=duration]").val(1).trigger("blur");
  },
  assertOk:                function () {
    var ret = ge.tasks[0].rowElement.find("[name=duration]").val() == 4 ;
    return ret;
  }
});




//ganttTestUnits=ganttTestUnits.slice(0,2)
//ganttTestUnits=[ganttTestUnits[21]]

// esegue sono ultimo test
//ganttTestUnits = [ganttTestUnits[ganttTestUnits.length - 1]]

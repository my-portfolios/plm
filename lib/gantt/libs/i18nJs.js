/*
 Copyright (c) 2012-2017 Open Lab
 Permission is hereby granted, free of charge, to any person obtaining
 a copy of this software and associated documentation files (the
 "Software"), to deal in the Software without restriction, including
 without limitation the rights to use, copy, modify, merge, publish,
 distribute, sublicense, and/or sell copies of the Software, and to
 permit persons to whom the Software is furnished to do so, subject to
 the following conditions:

 The above copyright notice and this permission notice shall be
 included in all copies or substantial portions of the Software.

 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */


function dateToRelative(localTime){
  var diff=new Date().getTime()-localTime;
  var ret="";

  var min=60000;
  var hour=3600000;
  var day=86400000;
  var wee=604800000;
  var mon=2629800000;
  var yea=31557600000;

  if (diff<-yea*2)
    ret ="in ## years".replace("##",(-diff/yea).toFixed(0));

  else if (diff<-mon*9)
    ret ="in ## months".replace("##",(-diff/mon).toFixed(0));

  else if (diff<-wee*5)
    ret ="in ## weeks".replace("##",(-diff/wee).toFixed(0));

  else if (diff<-day*2)
    ret ="in ## days".replace("##",(-diff/day).toFixed(0));

  else if (diff<-hour)
    ret ="in ## hours".replace("##",(-diff/hour).toFixed(0));

  else if (diff<-min*35)
    ret ="in about one hour";

  else if (diff<-min*25)
    ret ="in about half hour";

  else if (diff<-min*10)
    ret ="in some minutes";

  else if (diff<-min*2)
    ret ="in few minutes";

  else if (diff<=min)
    ret ="just now";

  else if (diff<=min*5)
    ret ="few minutes ago";

  else if (diff<=min*15)
    ret ="some minutes ago";

  else if (diff<=min*35)
    ret ="about half hour ago";

  else if (diff<=min*75)
    ret ="about an hour ago";

  else if (diff<=hour*5)
    ret ="few hours ago";

  else if (diff<=hour*24)
    ret ="## hours ago".replace("##",(diff/hour).toFixed(0));

  else if (diff<=day*7)
    ret ="## days ago".replace("##",(diff/day).toFixed(0));

  else if (diff<=wee*5)
    ret ="## weeks ago".replace("##",(diff/wee).toFixed(0));

  else if (diff<=mon*12)
    ret ="## months ago".replace("##",(diff/mon).toFixed(0));

  else
    ret ="## years ago".replace("##",(diff/yea).toFixed(0));

  return ret;
}

//override date format i18n

Date.monthNames = ["1월","2월","3월","4월","5월","6월","7월","8월","9월","10월","11월","12월"];
// Month abbreviations. Change this for local month names
Date.monthAbbreviations = ["1","2","3","4","5","6","7","8","9","10","11","12"];
// Full day names. Change this for local month names
Date.dayNames =["일요일","월요일","화요일","수요일","목요일","금요일","토요일"];
// Day abbreviations. Change this for local month names
Date.dayAbbreviations = ["일","월","화","수","목","금","토"];
// Used for parsing ambiguous dates like 1/2/2000 - default to preferring 'American' format meaning Jan 2.
// Set to false to prefer 'European' format meaning Feb 1
Date.preferAmericanFormat = false;

Date.firstDayOfWeek =0;
//Date.defaultFormat = "M/d/yyyy";
Date.defaultFormat = "yyyy-MM-dd";
Date.masks = {
  fullDate:       "EEEE, MMMM d, yyyy",
  shortTime:      "h:mm a"
};
Date.today="Today";

Number.decimalSeparator = ".";
Number.groupingSeparator = ",";
Number.minusSign = "-";
Number.currencyFormat = "###,##0.00";



var millisInWorkingDay =28800000;
var workingDaysPerWeek =5;

function isHoliday(date) {
  var friIsHoly =false;
  var satIsHoly =true;
  var sunIsHoly =true;

  var pad = function (val) {
    val = "0" + val;
    return val.substr(val.length - 2);
  };

  var holidays = "##";

  var ymd = "#" + date.getFullYear() + "_" + pad(date.getMonth() + 1) + "_" + pad(date.getDate()) + "#";
  var md = "#" + pad(date.getMonth() + 1) + "_" + pad(date.getDate()) + "#";
  var day = date.getDay();

  return  (day == 5 && friIsHoly) || (day == 6 && satIsHoly) || (day == 0 && sunIsHoly) || holidays.indexOf(ymd) > -1 || holidays.indexOf(md) > -1;
}



var i18n = {
  YES:                 "네",
  NO:                  "아니요",
  FLD_CONFIRM_DELETE:  "삭제하시겠습니까?",
  INVALID_DATA:        "입력된 데이터가 필드 형식에 유효하지 않습니다.",
  ERROR_ON_FIELD:      "입력란 오류",
  OUT_OF_BOUDARIES:      "허용치:",
  CLOSE_ALL_CONTAINERS:"전부 닫겠습니까?",
  DO_YOU_CONFIRM:      "확인 하시겠습니까?",
  ERR_FIELD_MAX_SIZE_EXCEEDED:      "입력란 최대 크기 초과",
  WEEK_SHORT:      "주.",

  FILE_TYPE_NOT_ALLOWED:"허용되지 않는 파일 유형.",
  FILE_UPLOAD_COMPLETED:"파일업로드가 완료되었습니다..",
  UPLOAD_MAX_SIZE_EXCEEDED:"최대 파일 크기 초과",
  ERROR_UPLOADING:"업로드 되지 않았습니다.",
  UPLOAD_ABORTED:"업로드가 중단되었습니다.",
  DROP_HERE:"파일을 여기에 놓으세요",

  FORM_IS_CHANGED:     "페이지에 저장되지 않은 데이터가 있습니다.!",

  PIN_THIS_MENU: "PIN_THIS_MENU",
  UNPIN_THIS_MENU: "UNPIN_THIS_MENU",
  OPEN_THIS_MENU: "OPEN_THIS_MENU",
  CLOSE_THIS_MENU: "CLOSE_THIS_MENU",
  PROCEED: "Proceed?",

  PREV: "이전",
  NEXT: "다음",
  HINT_SKIP: "힌트 스킵",

  WANT_TO_SAVE_FILTER: "필터 저장",
  NEW_FILTER_NAME: "새로운 필터 이름",
  SAVE: "저장",
  DELETE: "삭제",
  HINT_SKIP: "힌트 스킵",

  COMBO_NO_VALUES: "사용할 수있는 값이 없습니다....?",

  FILTER_UPDATED:"업데이트 완료.",
  FILTER_SAVED:"저장 완료."

};



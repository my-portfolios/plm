<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- bom 팝업 -->
<script>
$(document).ready(function(){

    //load_data();

    function load_data()
    {
    $.ajax({
    url:'<?php echo site_url()?>'+'/<?php echo $PageNm?>/<?php echo $PageType?>/fetch',
    method:"POST",
        success:function(data){
            $('#customer_data').html(data);
        },
        error:function(request,status,error){
            console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }
    })
    }

    $('#import_form').on('submit', function(event){
        event.preventDefault();
        $.ajax({
            url:'<?php echo site_url()?>'+'/<?php echo $PageNm?>/<?php echo $PageType?>/import?userid=<?php echo $_SESSION['userid'];?>',
            method:"POST",
            data:new FormData(this),
            contentType:false,
            cache:false,
            processData:false,
            success:function(data){
                $('#file').val('');
                //load_data();
                console.log(data);
                if(data=='0') alert('형식에 맞지 않습니다.\n형식에 맞는 엑셀파일을 업로드 해주세요');
                $('#excel_upload').modal('hide');
                location.reload();
            },
            error:function(request,status,error){
                console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
            }
        });
    });

});
</script>
<div class="modal fade" id="excel_upload" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"  aria-hidden="true">
  <div class="modal-dialog" role="document">
	<div class="modal-content">
	  <div class="modal-header">
        <h4 class="modal-title">엑셀파일로 업로드하기</h4>
        <a href="<?php echo('http://'.$_SERVER['HTTP_HOST']);?>/BP_UPLOAD_SAMPLE.xlsx">샘플파일</a>
	  </div>
	  <div class="modal-body" style="height:90px;overflow:auto">
        <form method="post" id="import_form" enctype="multipart/form-data">
        <p>
        <input type="file" name="file" id="file" class="form-control-file" required accept=".xls, .xlsx"  style="flaot:left;"/><input type="submit" name="import" value="Import" class="btn btn-info" style="float:right"/></p>
        </form>
        <br />
        <div class="table-responsive" id="customer_data">

        </div>
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
	  </div>
	</div>
  </div>
</div>

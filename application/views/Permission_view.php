<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script>
	bootbox.alert({
		size:'small',
		message : '권한이 없습니다.<br />이전 화면으로 돌아갑니다.',
		backdrop: false,
		escape: false,
		buttons: {
			ok: {
				label: '확인',
				className: "btn-default"
			}
		},
		callback: function() {
        history.back();
    }
	});
</script>





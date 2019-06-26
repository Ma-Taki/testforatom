function batch(btn, url) {
	btn.disabled = true;
	$.ajaxSetup({cache: false});

	$.get(
		url,
		null,
		function(data, status) {
			if (status == 'success') {
				alert(btn.value + 'が完了しました。');
			} else {
				alert(btn.value + 'に失敗しました。');
			}
			btn.disabled = false;
		}
	);
}
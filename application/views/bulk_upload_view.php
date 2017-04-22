<form enctype="multipart/form-data" method="post" id="bulk-upload-customer-form" />
	<button id="bulk-upload-customer" class="btn btn-primary" type="button" >
		<span class="glyphicon glyphicon-upload"></span>
		Bulk Upload
	</button>
	<input type="file" id="choose-file" name="file" style="display:none;"/> 
	<span id="chosen-file-name" class="btn" style="display:none;"></span >
	<button type="submit" class="btn btn-primary" id="upload-button" style="display:none;">Upload</button >
</form>
<script >
(function() {
	/**
	 * Event: Bulk upload Button "Click"
	 */
	$('#bulk-upload-customer-form #bulk-upload-customer, #bulk-upload-customer-form #chosen-file-name').click(function() {
		$('#bulk-upload-customer-form #choose-file').trigger('click');
	});
	
	/**
	 * Event: Input type = file on change event 
	 */
	$('#bulk-upload-customer-form #choose-file').change(function () {
		var file = $('#bulk-upload-customer-form #choose-file')[0].files[0];
		if (file) {
			$('#bulk-upload-customer-form #chosen-file-name').text(file.name);
			$('#bulk-upload-customer-form #chosen-file-name, #bulk-upload-customer-form #upload-button').show();
		}else {
			$('#bulk-upload-customer-form #chosen-file-name, #bulk-upload-customer-form #upload-button').hide();
		}
	});
})();
</script>
$(document).ready(function() {
	$('#pho').hide(); // Hide them all initially
	$('#lap').hide();

	$("[name='category']").change(function() {
		var cat= $(this).val();
		if(cat == 'none') {
			$('#pho').hide();
			$('#lap').hide();
		} else if(cat == 'telefony') {
			$('#pho').show();
			$('#lap').hide();
		} else if(cat == 'laptopy') {
			$('#pho').hide();
			$('#lap').show();
		}
	});
});
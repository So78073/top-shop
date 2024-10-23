<script type="text/javascript">
	var Table;
	function geAllDisputes(d){
		if (Table instanceof $.fn.dataTable.Api) {
			Table.destroy();
		}
		Table = $('#'+d).DataTable({
		    processing: false,
		    ajax: {
		        url: 'reports/fetch/'+d,
		    },
		    ordering: true,
		    initComplete:function(){
		    	$(":input,:button,:checkbox").removeClass('disabled');
	            $(":input,:button,:checkbox").prop('disabled', false);
	            $('input').removeClass('is-valid');
	            $('input').removeClass('is-invalid');
		    }
		});
	}
	geAllDisputes('open');
</script>
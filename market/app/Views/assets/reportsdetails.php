	
	<script type="text/javascript">
		$("#DZ_W_TimeLine").animate({ scrollTop: $('#DZ_W_TimeLine').prop("scrollHeight")}, 1000);
		function manerefund(){
			var data = 'id='+<?php echo @$report['id'] ?>;
			$.ajax({
				url:'<?php echo base_url()?>/manerefund',
				type:'POST',
				data:data,
				dataType:'JSON',
				success:(response)=>{
					if(typeof response.error !== 'undefined'){
						var html = `<div class="card bg-gray-200">
									<div class="card-body">
										<p class="card-title ${response.class}"><b>${response.username}</b></p>							 
										<p class="card-subtitle">${response.date}</p>
										<p class="card-text">${response.message}</p>
									</div>
								</div>`;
			            $("#reportimeline").append(html);
			            $("#buttonscontrols").html('');
			            $("#statustext").html(response.statustext);
			            <?php if(session()->get('suser_groupe') == '0'){ ?>
			                if(typeof response.balance !== 'undefined'){
			                	$('#balance').html(response.balance)+' +';
			                }
			            <?php } ?>
			            <?php if(session()->get('suser_groupe') == '1'){ ?>
			                if(typeof response.sellerbalance !== 'undefined'){
			                	$('#sellerbalance').html(response.sellerbalance)+' -';
			                }
			            <?php } ?>
			            $('#clock').remove();
			            $("#chatform").remove();
			            $("#proofpannel").remove();
			            $("#DZ_W_TimeLine").animate({ scrollTop: $('#DZ_W_TimeLine').prop("scrollHeight")}, 1000);
					}
					else{
						$("#modalcontent").html('');
						$("#modalcontent").html(response.modal);
						let modal = new bootstrap.Modal(document.getElementById('bsModal'), {
							keyboard: false,
			  				backdrop: "static",
						});
						modal.show();	
					}
					
					$(":input,:button,:checkbox").removeClass('disabled');
			        $(":input,:button,:checkbox").prop('disabled', false);
			        $('input').removeClass('is-valid');
			        $('input').removeClass('is-invalid');
				}
			})
		}
		<?php if(session()->get('suser_groupe') == '9' || session()->get('suser_groupe') == '4'  || session()->get('suser_id') == @$report['buyerid']){ ?>
			function closedispute(d){
				var data = 'id='+<?php echo @$report['id'] ?>;
				$.ajax({
					url:'<?php echo base_url()?>/closedispute',
					type:'POST',
					data:data,
					dataType:'JSON',
					success:(response)=>{
						if(typeof response.error !== 'undefined'){
							var html = `<li>
	                                        <div class="timeline-badge primary"></div>
	                                        <div class="timeline-panel">
	                                            <span>${response.username}-${response.date}</span>
	                                            <h6 class="mb-0">${response.message}</h6>
	                                        </div>
	                                    </li>`;
			                $("#reportimeline").append(html);
			                $("#buttonscontrols").html('');
			                $("#statustext").html(response.statustext);
			                <?php if(session()->get('suser_groupe') == '0'){ ?>
				                if(typeof response.balance !== 'undefined'){
				                	$('#balance').html(response.balance)+' +';
				                }
				            <?php } ?>
				            <?php if(session()->get('suser_groupe') == '1'){ ?>
				                if(typeof response.sellerbalance !== 'undefined'){
				                	$('#sellerbalance').html(response.sellerbalance)+' -';
				                }
				            <?php } ?>
				            $("#clock").html('');
				            $("#chatform").remove('');
				            $("#proofpannel").remove('');
				            $("#DZ_W_TimeLine").animate({ scrollTop: $('#DZ_W_TimeLine').prop("scrollHeight")}, 1000);
						}
						else{
							$("#modalcontent").html('');
							$("#modalcontent").html(response.modal);
							let modal = new bootstrap.Modal(document.getElementById('bsModal'), {
								keyboard: false,
				  				backdrop: "static",
							});
							modal.show();	
						}
						
						$(":input,:button,:checkbox").removeClass('disabled');
			            $(":input,:button,:checkbox").prop('disabled', false);
			            $('input').removeClass('is-valid');
			            $('input').removeClass('is-invalid');
					}
				})
			}


		<?php } ?>

		function handleDzScroll() {
			jQuery('.dz-scroll').each(function(){
				var scroolWidgetId = jQuery(this).attr('id');
				const ps = new PerfectScrollbar('#'+scroolWidgetId, {
				  wheelSpeed: 2,
				  wheelPropagation: true,
				  minScrollbarLength: 20
				});
			})
		}
		handleDzScroll();
	</script>
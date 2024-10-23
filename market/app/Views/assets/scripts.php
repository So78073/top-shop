	<script src="<?php echo base_url() ?>/assets/css/bs/bs5.js"></script>
    <script src="<?php echo base_url() ?>/assets/lib/jquery/jquery.js"></script>
    <script src="<?php echo base_url() ?>/assets/lib/popper.js/popper.js"></script> 
    <script src="<?php echo base_url() ?>/assets/css/bs/bootstrap.min.js"></script>
    <script src="<?php echo base_url() ?>/assets/lib/perfect-scrollbar/js/perfect-scrollbar.js"></script>
    <script src="<?php echo base_url() ?>/assets/lib/jquery-toggles/toggles.min.js"></script>
	<script src="<?php echo base_url() ?>/assets/lib/simplebar/js/simplebar.min.js"></script>
	<script src="<?php echo base_url() ?>/assets/js/jsyamel.js"></script>
	<script src="<?php echo base_url() ?>/assets/lib/smart-wizard/js/jquery.smartWizard.min.js"></script>
	<script src="<?php echo base_url() ?>/assets/lib/select2/js/select2.min.js"></script>
	<script src="<?php echo base_url() ?>/assets/lib/datatables/jquery.dataTables.js"></script>
	<script src="<?php echo base_url() ?>/assets/lib/datatables-responsive/dt4respo.js"></script>
	<script src="<?php echo base_url() ?>/assets/lib/datatables-responsive/dataTables.responsive.js"></script>
	<script src="<?php echo base_url() ?>/assets/js/tiny.js"></script>
    <script src="<?php echo base_url() ?>/assets/lib/lobibox/js/lobibox.min.js"></script>
    <script src="<?php echo base_url() ?>/assets/js/lightbox.js"></script>
    <script src="<?php echo base_url() ?>/assets/lib/lobibox/js/notifications.min.js"></script>
	<script src="<?php echo base_url() ?>/assets/js/slider.js"></script>
	<script src="<?php echo base_url() ?>/assets/lib/chartjs/js/Chart.min.js"></script>
	<script src="<?php echo base_url() ?>/assets/js/paper.js"></script>
	<script src="<?php echo base_url() ?>/assets/js/amanda.js"></script>

	<script>
		$(document).ready(function () {
			$("#show_hide_password a").on('click', function (event) {
				event.preventDefault();
				if ($('#show_hide_password input').attr("type") == "text") {
					$('#show_hide_password input').attr('type', 'password');
					$('#show_hide_password i').addClass("bx-hide");
					$('#show_hide_password i').removeClass("bx-show");
				} else if ($('#show_hide_password input').attr("type") == "password") {
					$('#show_hide_password input').attr('type', 'text');
					$('#show_hide_password i').removeClass("bx-hide");
					$('#show_hide_password i').addClass("bx-show");
				}
			});
			if($("#chart2").length){
					var ctx = document.getElementById("chart2").getContext('2d');
					var myChart = new Chart(ctx, {
						type: 'bar',
						data: {
							<?php 
								if(isset($sectionName) && $sectionName == "Admin Dashboard"){
									$dataChart = [];
									$LabelsChart = [];
									foreach($sections as $key => $val){
										$LabelsChart[] = $val["sectionlable"]; 
										$dataChart[] = "{
											label: '".ucfirst($val["sectionlable"])."',
											data: [".$val["sectionrevenue"]."],
											barPercentage: .5,
											backgroundColor: \"rgba(".rand(1,255).", ".rand(1,255).", ".rand(1,255).", 0.24)\"
										}";
									}
								}
									 
							?>
			
							datasets: [

								<?php 
									if(isset($sectionName) && $sectionName == "Admin Dashboard"){
										foreach ($dataChart as $keychart => $valuechart){
											echo $valuechart.',';
										}
									}
								?>

							]
							
							
						},
						options: {
							maintainAspectRatio: false,
							legend: {
								display: true,
								labels: {
									fontColor: '#fff',
									boxWidth: 40
								}
							},
							tooltips: {
								enabled: true
							},
							scales: {
								xAxes: [{
									ticks: {
										beginAtZero: true,
										fontColor: '#fff'
									},
									gridLines: {
										display: true,
										color: "rgba(255, 255, 255, 0.24)"
									},
								}],
								yAxes: [{
									ticks: {
										beginAtZero: true,
										fontColor: 'rgba(255, 255, 255, 0.64)'
									},
									gridLines: {
										display: true,
										color: "rgba(255, 255, 255, 0.24)"
									},
								}]
							}
						}
					});
			}
		});
	</script>

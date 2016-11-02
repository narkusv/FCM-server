<?php

	$allNewApps = $helper->GetAllMoreApps();
?>
<html>
	<head>
		
	
		<script>
			$( document ).ready(function() {		
				$("#btn_add_gma").click(function(){ 
					console.log("clicked");
					var userURL =  $("#input_GMAURL").val();
					if(userURL.length > 0){
						$.ajax({
							type: 'post',
							url: 'gma_controller.php',
							data: {GMAURL : userURL,
									action: 'addNewGMAApp'},
							success: function () {
								location.reload();
							}
						});				
					}else{
						
						alert('GMA URL is empty');
					}
				});
			});
		</script>
	
		
	</head>

	<body>
	<!-- Notification sending form -->
		<div class="col-md-12" >
			<form id="notifForm" class="form-horizontal" name="form">
				<div class="col-md-8 form-group">
					<label class="control-label col-md-4 text-left" for="input_GMAURL">App store URL:</label>
					 <div class="col-md-8">
						<input type="text" class="form-control" placeholder="App URL" id="input_GMAURL" name="notifTitle"/>
					</div>
					
				</div>
				
				<button type="button" id="btn_add_gma" class="col-md-2 btn btn-default">Submit</button>
				
				<div class="row"/>
				
				
				
			
			</form>
			<div class="col-md-12">
				<ul class="list-group">
					<?php foreach ($allNewApps as &$app) {	 ?>
						<li class="list-group-item col-md-8">
								<img class="col-md-2"  src="<?php echo $app[2]?>"/>
								<div class="col-md-9"><a href="<?php echo $app[1]?>"><h3> <?php echo $app[0]?>  </h3> </a></div>
							</li>
						<?php } ?>
					</ul>
				
			</div>
		</div>
		
	</body>



</html>
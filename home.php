<?php
	require 'DB.php';
	$helper = new DB();
	$appList = $helper -> getAllApps();
	$iconList = $helper-> getAllIcons();
?>
<html>
	<head>
		<title> Home page of GCM regSystem </title>
		<style>
			.form-horizontal .control-label.text-left{
				text-align: left;
			}
		</style>
	     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script> 
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjxsfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	
	
		<script>
			$( document ).ready(function() {
				$("#img_selected_icon").attr("src", $( "#select_notifIcon option:selected" ).val());	
				
				$( "#select_notifIcon" ).change(function() {
					console.log( $( "#select_notifIcon option:selected" ).text());
					 $("#img_selected_icon").attr("src", $( "#select_notifIcon option:selected" ).val());
				});
				
			});
			
		</script>
		
	</head>

	<body>
	<!-- Notification sending form -->
		<div class="col-xs-8" >
			<form class="form-horizontal">
				<div class="col-xs-12 form-group">
					<label class="control-label col-xs-2 text-left" for="select_notifApps">Apps to send:</label>
						<div class="col-xs-3">
							<select multiple class="form-control" id="select_notifApps">
								<option>Send to all apps</option>
								
								
									<?php foreach ($appList as &$app) { ?>
										<option> <?php echo $app[1];?>  </option>
									<?php } ?>
								
							</select>
						</div>
				</div>
				
				<div class="col-xs-12 form-group">
					<label class="control-label col-xs-2 text-left" for="select_notifIcon">Icon to use:</label>
					 <div class="col-xs-3">
						<select class="form-control" id="select_notifIcon">
							<?php foreach ($iconList as &$icon) { ?>
								<option value="<?php echo $icon[1] ?>"> <?php echo $icon[2];?>  </option>
							<?php } ?>
						</select>
					</div>
					
					<div class="col-xs-2">
						<img id="img_selected_icon" width="32" height="32" src="./icons/3.jpg"/>
					</div>
				</div>
				
				<div class="col-xs-12 form-group">
					<label class="control-label col-xs-2 text-left" for="input_notifTitle">Notification Title:</label>
					 <div class="col-xs-3">
						<input type="text" class="form-control" placeholder="Notification title" id="input_notifTitle"/>
					</div>
				</div>
				
				<div class="col-xs-12 form-group">
					<label class="control-label col-xs-2 text-left" for="input_notifMsg">Notification message:</label>
					<div class="col-xs-3">
						<textarea class="form-control" placeholder="Notification message" id="input_notifMsg"></textarea>
					</div>
				</div>
				
					<div class="col-xs-12 form-group">
					<label class="control-label col-xs-2 text-left" for="input_notifUrl">Notification URL:</label>
					 <div class="col-xs-3">
					 	<input type="text" class="form-control" placeholder="Notification URL" id="input_notifUrl"/>
					</div>
				</div>
			
				<button type="submit" class="col-xs-1 col-xs-push-2 btn btn-default">Submit</button>
			
			</form>
		
		</div>
		
	
	
	
	
	</body>



</html>
	<style>          
	  #map { 
		height: 400px;    
		width: 985px;             
	  }
	  .page-inner{
		overflow-x: hidden;
		overflow-y: scroll;
	  }
	 .break{
		word-break: normal;
	  }
	</style>     
	<div class="page-inner">
		<div class="page-title">
			<h3 class="breadcrumb-header">Find Map</h3>
		</div>
			<form method="post" action="" class="form-horizontal">
				<div class="panel panel-default">
				<div class="form-group">
					<label class="col-md-3 control-label break">Enter Your Street name,</br>City state,Country:</label>  
					<div class="col-md-5">
						<textarea name='address' placeholder='Street name,City state,Country' class="form-control" required></textarea>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-10">
						<button type="submit" name="submit_address" class="btn btn-success">Get Coordinates</button>
					</div>
				</div>
				<?php
				if(isset($_POST['submit_address']))
				{
					$address =$_POST['address']; // Google HQ
					$prepAddr = str_replace(' ','+',$address);
					$geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
					$output= json_decode($geocode);
					if(isset($output->results[0])){
						$lat = $output->results[0]->geometry->location->lat;
						$long = $output->results[0]->geometry->location->lng;?>
							<?php 
							echo "<b>The Address =</b>  ".$address; 
							echo '</br>';
							echo "<b>The latitude =</b>  ".$lat; 
							echo '</br>';
							echo "<b>The longitude =</b>  ".$long; ?>
						<?php $geolocation = $lat.','.$long;
						//echo $geolocation;exit;
						$request = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.$geolocation.'&sensor=false';
						//echo $request;exit;
					}
					else{
						echo 'No Address Found';
					}
				}
				?>
			</form>
			<?php if(isset($_POST['submit_address'])){?>
			<h3 class="breadcrumb-header">Map</h3>
			<div id="map"></div>
			<?php }else{?>
			<body onload="getLocation()">
			<h3 class="breadcrumb-header">Current Location Map</h3>
			<div style="padding:10px">
			<p id="demo"></p>
			<div id="map"></div>
			</body>
	</div>			<?php }?>
			<div id="map" style="display:none;"></div>
    </div>
    </div>
	<script type="text/javascript">
	     var map;
        function initMap() {
            var latitude = <?php echo $lat;?>; // YOUR LATITUDE VALUE
			//alert(latitude);return false;
            var longitude = <?php echo $long;?>; // YOUR LONGITUDE VALUE
            var myLatLng = {lat: latitude, lng: longitude};            
            map = new google.maps.Map(document.getElementById('map'), {
              center: myLatLng,
              zoom: 15                   
            });     
            var marker = new google.maps.Marker({
              position: myLatLng,
              map: map,
              //title: 'Hello World'
              title: latitude + ', ' + longitude 
            });            
        }
	</script>
	<script type="text/javascript">

		var x = document.getElementById("demo");
	function getLocation(){
		//alert('here');return false;
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(showPosition);
		}else { 
			x.innerHTML = "Geolocation is not supported by this browser.";
		}
	}
	function showPosition(position) {
		console.log(position);
		var latitude =position.coords.latitude
		var longitude =position.coords.longitude
		geolocation=latitude + ', ' + longitude;
		x.innerHTML = "The Latitude= " + position.coords.latitude + 
		"<br>The Longitude= " + position.coords.longitude;
		var myLatLng = {lat: latitude, lng: longitude};
        map = new google.maps.Map(document.getElementById('map'), {
        center: myLatLng,
        zoom: 14                    
        });            
        var marker = new google.maps.Marker({
        position: myLatLng,
              map: map,
              title: latitude + ', ' + longitude 
            });  
	}	
    </script>
    <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCzjeZ1lORVesmjaaFu0EbYeTw84t1_nek&callback=initMap"></script> -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBxBLnWXhi0YdTQITGWlOZrQI5MEPv2O3E&callback=initMap"></script>
<!-- <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script> -->
    
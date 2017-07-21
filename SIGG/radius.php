<?php
  include "koneksi.php";
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../favicon.ico">

    <title>HOME</title>

    <!-- Bootstrap core CSS -->
    <link href="../../akademik/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="../../akademik/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../../akademik/css/navbar-fixed-top.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <!--script src="../../akademik/js/ie-emulation-modes-warning.js"></script-->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

<script src="js/jquery.min.js"></script>
<script>
	$(document).ready(function() {
		$('#namafasilitas').change(function(){
			var var_fasilitas_id = $(this).val();
			$.ajax({
				type:'POST',
				url: 'lenmark_ajax.php',
				data: 'no_fas='+ var_fasilitas_id,
				success: function(response){
					$('#namalenmark').html(response);
				}
			});
		})
	});
</script>
	
    <style>
      #map-canvas {
        width: 1000px;
        height: 500px;
        color : black;
      }
    </style>

	
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDU9JOIiWlqtJp_fQ8F4FdwoAWP-9QVvQw&sensor=true&libraries=places"></script>
    <script>
    var marker;
      function initialize() {
        var mapCanvas = document.getElementById('map-canvas');
        var mapOptions = {
          mapTypeId: google.maps.MapTypeId.ROADMAP
        }     
        var map = new google.maps.Map(mapCanvas, mapOptions);
        var infoWindow = new google.maps.InfoWindow;      
        var bounds = new google.maps.LatLngBounds();
 
 
        function bindInfoWindow(marker, map, infoWindow, html) {
          google.maps.event.addListener(marker, 'click', function() {
            infoWindow.setContent(html);
            infoWindow.open(map, marker);
          });
        }
 
          function addMarker(lat, lng, info) {
            var pt = new google.maps.LatLng(lat, lng);
            bounds.extend(pt);
            var marker = new google.maps.Marker({
                map: map,
                position: pt,
                icon: 'location.png',
				animation: google.maps.Animation.DROP

            });       
            map.fitBounds(bounds);
            bindInfoWindow(marker, map, infoWindow, info);
          }
		  
          <?php
            $query = mysql_query("select * from tb_lenmark");
          while ($data = mysql_fetch_array($query)) {
            $lat = $data['lat_lenm'];
            $lon = $data['long_lenm'];
			$nama = $data['nama_lenm'];
            echo ("addMarker($lat, $lon, '<b> $nama </b>');\n");                        
          }
          ?>
        }
      google.maps.event.addDomListener(window, 'load', initialize);
    </script>
  </head>

  <body>
      
     <!-- Fixed navbar -->
        <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="../../bismillah/SIG2/pastiberhasil4.php">Aplikasi AHP Spasial DCKTR</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
          <!--class active untuk menunjukkan kelas yg sekarang-->
      
      <li class="dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Cari Reklame<span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="peta.php">Cari Reklame -> Jenis Reklame</a></li>
        <li><a href="peta2.php">Cari Reklame -> Ketegori Produk</a></li>
        <li><a href="peta3.php">Cari Reklame -> Jenis & Ketegori Produk</a></li>
        <li><a href="radius.php">Cari Reklame -> Radius</a></li>
              </ul>
            </li>
            <li><a href="../../bismillah/SIG2/peta.php"> Saran Lokasi Reklame</a></li>
            
      
          </ul>
      
          <ul class="nav navbar-nav navbar-right">
      <li class="dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Administrator<span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="#">Profil</a></li>
                <li role="separator" class="divider"></li>
                <li class="dropdown-header">Nav header</li>
              </ul>
      </li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="../../akademik/logout.php">logout</a></li>
              </ul>
            </li>
           
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">

      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
        <h1>Aplikasi AHP Berbasis Spasial</h1>
        <h3>untuk Menentukan Lokasi Reklame di Surabaya</h3>
        <p>Pencarian Menurut Radius</p>
	
	<!-- kelas form method AJAX-->
		<div class="btn-group">
		<form method="post" action="berhasilradius.php">        
			<div class="form-group">
				<select name="namafasilitas" id="namafasilitas" class="form-control">
					<option>--Pilih Fasilitas--</option>
					<?php
					$fasilitas = mysql_query("SELECT * FROM tb_fasilitas ORDER BY id_fas");
					while($fs=mysql_fetch_array($fasilitas)){
					echo "<option value=\"$fs[id_fas]\">$fs[nama_fas]</option>\n";
					}	
					?>
				</select>
			</div>
		</div>
		
		
		
		<div class="btn-group">
			<div class="form-group">
				<select name="namalenmark" id="namalenmark" class="form-control" style="width:100%;">
					<option>-- Pilih LENMARK --</option>
				</select>
			</div>
		</div>
		
		<div class="btn-group">
		<div class="form-group">
			<button type="submit" name="tambah" class="btn btn-primary" onclick="bindInfoWindow()">Tampil</button>
			
		</div>
	    </div>
</form>

 <div id="map-canvas"></div>
 
 <div class="btn-group">
		<form method="post" action="cek.php">        
			<div class="form-group">
			<br/>
			<p></p>
            <select id="jarak" name="jarak" class="form-control" style="width:600px">
              <option value="">-- Silahkan Pilih Radius / Jarak --</option>
              <option value="1">1 KM</option>
              <option value="2">2 KM</option>
              <option value="3">3 KM</option>
              <option value="4">4 KM</option>
              <option value="5">5 KM</option>
              <option value="6">6 KM</option>
              <option value="7">7 KM</option>
              <option value="8">8 KM</option>
              <option value="9">9 KM</option>
              <option value="10">10 KM</option>
              <option value="11">11 KM</option>
              <option value="12">12 KM</option>
              <option value="13">13 KM</option>
              <option value="14">14 KM</option>
              <option value="15">15 KM</option>
              <option value="16">16 KM</option>
              <option value="17">17 KM</option>
              <option value="18">18 KM</option>
              <option value="19">19 KM</option>
              <option value="20">20 KM</option>
              <option value="21">21 KM</option>
              <option value="22">22 KM</option>
              <option value="23">23 KM</option>
              <option value="24">24 KM</option>
              <option value="25">25 KM</option>
              <option value="26">26 KM</option>
              <option value="27">27 KM</option>
              <option value="28">28 KM</option>
              <option value="29">29 KM</option>
              <option value="30">30 KM</option>
            </select>
			</div>
		</div>
			
			<div class="btn-group">
			<div class="form-group">
			<p></p>
			<br/>
            <button id="cari" type="button" class="btn btn-primary" onclick="showPlaces();">Cari Reklame Sekitar</button>
          </div>
		  </div>
		  
	
	  

	  
      
      <footer id="colophon" class="site-footer" role="contentinfo">
			<div class="footer-bottom">
			<div class="container">
				<div class="sixteen columns">
					<div class="site-info">
					<p></p>
						Dinas Perumahan Rakyat dan Kawasan Permukiman, Cipta Karya dan Tata Ruang.						
                        <p>Kontak Kami :</p>
						<p><ul>
							<li>Jl Taman Surya No 1 Surabaya 60272</li>
							<li>Tlp : 5312144 . ext. 130/534</li>
							<li>Fax : (031) 5458031</li>
							<li>Email : cktr@surabaya.go.id/cktr.sby@gmail.com </li>
						</ul></p>
					</div><!-- .site-info -->
				</div>
			</div>
		</div>
	</footer><!-- #colophon -->


    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="./js/vendor/jquery.min.js"><\/script>')</script>
    <script src="../../akademik/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../akademik/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>

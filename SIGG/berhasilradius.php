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
    <!-- // <script src="../../akademik/js/ie-emulation-modes-warning.js"></script> -->

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
        color: black;
      }
    </style>
  <script>
  $(document).ready(function() {
    $('#lenmark').change(function(){
      var jenis_lenm_id = $(this).val();
      $.ajax({
        type:'POST',
        url: 'kota.php',
        data: 'jenis_lenm='+ jenis_lenm_id,
        success: function(response){
          $('#').html(response);
        }
      });
    })
  });
  </script>
  
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDU9JOIiWlqtJp_fQ8F4FdwoAWP-9QVvQw&sensor=true&libraries=places&callBack=initMap"></script>
 
  <!-- Nampilin Landmark-->
  <script>
    var marker;
      function initialize() {
        var mapCanvas = document.getElementById('map-canvas');
        var mapOptions = {
      zoom: 15,
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
                icon: 'property.png',
        animation: google.maps.Animation.DROP
        

            });       
            map.fitBounds(bounds);
            bindInfoWindow(marker, map, infoWindow, info);
          }
      
          <?php
          $fasilitas=$_POST['namafasilitas'];
          $lenmark=$_POST['namalenmark'];
            $query = mysql_query("SELECT nama_lenm, jenis_lenm, id_lenm,lat_lenm, long_lenm 
            FROM tb_lenmark L, tb_fasilitas F WHERE L.no_fas = F.id_fas and F.id_fas = '$fasilitas' and L.id_lenm = '$lenmark'");
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





 



  <script>
      function initMap() {
        var map = new google.maps.Map(document.getElementById('map-canvas'), {
          center: {lat:  -7.284604, lng:  112.733932},
          zoom: 13
        });

        // Menggunakan fungsi HTML5 geolocation.
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(posisireklame) {
            var pos = {lat:  <?php echo $lat ?>, lng:  <?php  echo $lon ?>};

           //  SUKSES 1 YEaaay
            marker = new google.maps.Marker({
        
              position: pos,
              map: map,
              icon: 'property.png',
              title: 'Posisi Kamu',
              animation: google.maps.Animation.DROP,//7878..787,878.787 {90.099,09808.98}
            });

            map.setCenter(pos);

            //var user_location = <?php echo $lat ?>+","+<?php  echo $lon ?> ;
            var url = "tampil.php";
            var jarak = document.getElementById("jarak").value;
            var info = [];
            $.ajax({
                url: url,
                data: "posisireklame="+encodeURI(pos)+"&jarak="+jarak,
                dataType: 'json',
                cache: true,
                success: function(msg){
                  for(i=0; i < msg.data.niken.length;i++){
                    var point = new google.maps.LatLng(parseFloat(msg.data.niken[i].latitude),parseFloat(msg.data.niken[i].longitude));
                    tanda = new google.maps.Marker({
                        position: point,
                        map: map,
                        icon: "place.png",
                        animation: google.maps.Animation.DROP,
                        title: msg.data.niken[i].SIPR
                    });
                  }
                }
            });

          }, function() {
            handleLocationError(true, map.getCenter());
          });
        } else {
          handleLocationError(false, map.getCenter());
        }
      }

      function showPlaces() {
        var map = new google.maps.Map(document.getElementById('map-canvas'), {
          center: {lat: -7.284604, lng: 112.733932},
          zoom: 13
        });

         marker = new google.maps.Marker({
        
              position: {lat:  <?php echo $lat ?>, lng:  <?php  echo $lon ?>},
              map: map,
              icon: 'property.png',
              title: 'Posisi Kamu',
              animation: google.maps.Animation.DROP,
            });

            

        // Menggunakan fungsi HTML5 geolocation.
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(posisireklame) {
      
             //var pos = {lat:  <?php echo $lat ?>, lng:  <?php  echo $lon ?>};
             var pos = <?php echo $lat ?>+","+<?php  echo $lon ?>;
            // var user_location = <?php echo $lat ?>+","+<?php  echo $lon ?> ;
            // var user_location = -7.284604+","+112.733932;
      
            var url = "tampil.php";
            var jarak = document.getElementById("jarak").value;
           

             $.ajax({
                url: url,
                data: "posisireklame="+encodeURI(pos)+"&jarak="+jarak,
                dataType: 'json',
                cache: true,
                success: function(msg){
                  for(i=0; i < msg.data.niken.length;i++){
                    var point = new google.maps.LatLng(parseFloat(msg.data.niken[i].lat_reklame),parseFloat(msg.data.niken[i].long_reklame));
                    tanda = new google.maps.Marker({
                        position: point,
                        map: map,
                        icon: "place.png",
                        animation: google.maps.Animation.DROP,
                        title: msg.data.niken[i].nama_reklame
                    });
                  }
                }
            });
          }, function() {
            handleLocationError(true, map.getCenter());
          });
        } else {
          handleLocationError(false, map.getCenter());
        }
      }
    
    
      function handleLocationError(browserHasGeolocation, pos) {
        var map = new google.maps.Map(document.getElementById('map-canvas'), {
          center: {lat:  -7.284604, lng: 112.733932},
          zoom: 13
        });
        var infoWindow = new google.maps.InfoWindow({map: map});
        infoWindow.setPosition(pos);
        infoWindow.setContent(browserHasGeolocation ?
                              'Error: The Geolocation service failed.' :
                              'Error: Your browser doesn\'t support geolocation.');
      }

      google.maps.event.addDomListener(window, 'load', initMap);
    </script>


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
    <form method="post" action="">   
    <div class="btn-group">
         
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
    
    &nbsp;
    <div class="btn-group">
      <div class="form-group">
        <select name="namalenmark" id="namalenmark" class="form-control" style="width:100%;">
          <option>-- Pilih LENMARK --</option>
        </select>
      </div>
    </div>
    
    &nbsp;
    <div class="btn-group">
      <div class="form-group">
        <button type="submit" name="tambah" class="btn btn-primary" onclick="bindInfoWindow()">Tampil</button>
      </div>
    </div>

&nbsp;
    <div class="btn-group">
      <div class="form-group">
      <!-- <label>Radius /  Jarak</label> -->
            <select id="jarak" name="jarak" class="form-control" style="width:200px">
              <option value="">-- Silahkan Pilih Radius / Jarak --</option>
              <option value="0.1">100 meter</option>
              <option value="0.2">200 meter</option>
              <option value="0.3">300 meter</option>
              <option value="0.4">400 meter</option>
              <option value="0.5">500 meter</option>
              <option value="0.6">600 meter</option>
              <option value="0.7">700 meter</option>
              <option value="0.8">800 meter</option>
              <option value="0.9">900 meter</option>
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
        &nbsp;&nbsp;

<div class="btn-group">
      <div class="form-group">
            <!--    <button id="cari" type="submit" class="btn btn-primary" onclick="bindInfoWindow();">Cari Reklame Sekitar</button>  -->
          <button id="cari" type="button" class="btn btn-primary" onclick="showPlaces()">Cari Reklame Sekitar</button>  
          <!--  <button id="cari" type="submit" class="btn btn-primary" onclick=alert("showPlaces");>Cari Reklame Sekitar</button>-->
      </div>
            </div>
<br>
<div id="map-canvas"></div>
 
 



 <div class="container">
<h3>Tabel Reklame </h3>
<h4>Tabel dibawah merupakan tabel hasil pencarian </h4>

<div class="panel panel-default" width="100%">
  <!-- Default panel contents -->
<div class="panel-heading">TABEL REKLAME</div>
<!--  <div class="panel-body">
<p>Tabel dibawah merupakan tabel hasil perhitungan dari radius</p>
</div> -->

<div class="table-responsive" >          
  <table class="table table-bordered" border="1">
    <thead>
      <tr class="info">
        <th>SIPR</th>
        <th>Alamat</th>
        <!-- <th>Teks Iklan</th> -->
        <th>Latitude</th>
        <th>Longtitude</th>
      </tr>
    </thead>

</div>

    <tbody>

        <?php
            // $kategorireklame=$_POST['kategorireklame'];
            // $query = mysql_query("select SIPR, alamat, jenis_reklame, kategori_produk, id_kategori_produk, teks_reklame, id_reklame, lat_reklame, long_reklame 
            // FROM tbl_reklame WHERE id_kategori_produk = '$kategorireklame'");
            // while ($data = mysql_fetch_array($query)) {
            
            // // $data   = mysql_query($query);

                
            // // if($y = mysql_fetch_array($data)){


            //  $nama = $data['SIPR'];
            // $alamat = $data['alamat'];
            // $lat = $data['lat_reklame'];
            // $lon = $data['long_reklame'];
            // $konten = $data['teks_reklame'];

            // $databaru   = mysql_query($query);


$sql = "SELECT id_reklame, SIPR, alamat, teks_reklame, lat_reklame, long_reklame,
    (6371 * acos(cos(radians(".$lat.")) 
                    * cos(radians(lat_reklame)) * cos(radians(long_reklame) 
                    - radians(".$lon.")) + sin(radians(".$lat.")) 
                    * sin(radians(lat_reklame)))) 
                    AS jarak 
    FROM tb_reklame2
    HAVING jarak <= ".$_POST['jarak']." 
    ORDER BY jarak";

$data   = mysql_query($sql);

if (!($data)) {
  $json = '{"data": {';
  $json .= '"niken":[ ';
  while($x = mysql_fetch_array($data)){
      $json .= '{';
      $json .= '"id_reklame":"'.$x['id_reklame'].'",
           "SIPR":"'.htmlspecialchars_decode($x['SIPR']).'",
           "alamat":"'.htmlspecialchars_decode($x['alamat']).'",
           "lat_reklame":"'.$x['lat_reklame'].'",
           "long_reklame":"'.$x['long_reklame'].'",
           "jarak":"'.$x['jarak'].'"
               },';
  // }
 
  // $json = substr($json,0,strlen($json)-1);
  // $json .= ']';
  // $json .= '}}';
   
  // echo $json;
               $nama = $data['SIPR'];
            $alamat = $data['alamat'];
            $lat = $data['lat_reklame'];
            $lon = $data['long_reklame'];
            $konten = $data['teks_reklame'];


  //           if(!($data)){
                    while($x = mysql_fetch_array($data)){
                                      

                                   echo "<tr><td>".$nama."</td>";
                                   echo "<td>".$alamat."</td>";
                                   echo "<td>".$konten."</td>";
                                   echo "<td>".$lat."</td>";
                                   echo "<td>".$lon."</td><tr>";
                                   

                    }//=============while 2
                                    
                }
            }
                        // }
                                  
                         //========if 
          ?>

    </tbody>
  </table>
</div>
</div>
</div>
</div>




     <!-- <div class="form-group">
            <label>Reklame Sejenis</label>
            <select id="reklamesejenis" name="reklamesejenis" class="form-control" style="width:500px">
              <option value="">-- Silahkan Pilih Reklame yang diinginkan --</option>
              <?php
              include "koneksi.php";
              $query = "select * from tb_jenis_produk order by id_produk ASC";
              $hasil = mysql_query($query);
              while ($qtabel = mysql_fetch_assoc($hasil))
              {
              echo '<option value="'.$qtabel['id_produk'].'">'.$qtabel['jenis_produk'].'</option>';       
              }
              ?>
            </select>
          </div> -->

      
    
</form>
      
      <footer id="colophon" class="site-footer" role="contentinfo">
      <div class="footer-bottom">
      <div class="container">
        <div class="sixteen columns">
          <br>
          <div class="site-info">
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

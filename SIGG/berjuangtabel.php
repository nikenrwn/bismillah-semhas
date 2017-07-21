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
<!--    <link rel="icon" href="../../../favicon.ico"> -->

    <title>HOME</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/navbar-fixed-top.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <!-- // <script src="../../akademik/js/ie-emulation-modes-warning.js"></script> -->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

<script src="js/jquery.min.js"></script>

  
    <style>
      #map-canvas {
        width: 1000px;
        height: 500px;
      }
    </style>

  
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDU9JOIiWlqtJp_fQ8F4FdwoAWP-9QVvQw&sensor=true&libraries=places&callBack=initMap"></script>
 
  <!-- Nampilin Landmark-->
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

            if(isset($_POST['namafasilitas'])) {
              $fasilitas=$_POST['namafasilitas'];
              
              $query = mysql_query("SELECT nama_lenm, jenis_lenm, id_lenm,lat_lenm, long_lenm,no_fas 
                FROM tb_lenmark L, tb_fasilitas F 
                WHERE L.no_fas = F.id_fas and F.id_fas = '$fasilitas'");
                $count = 0;
                while ($data = mysql_fetch_array($query)) {

                $lat = $data['lat_lenm'];
                $lon = $data['long_lenm'];
                $nama = $data['nama_lenm'];
                // $posisi = explode(',', trim(urldecode($_GET['posisireklame'])));

                $sql = "SELECT DISTINCT(id_reklame), SIPR, status, id_status, lat_reklame, long_reklame,
                    (6371 * acos(cos(radians(".$lat.")) 
                    * cos(radians(lat_reklame)) * cos(radians(long_reklame) 
                    - radians(".$lon.")) + sin(radians(".$lat.")) 
                    * sin(radians(lat_reklame)))) 
                    AS jarak 
                    FROM tb_reklame2
                    HAVING jarak <= 0.4 AND id_status=2
                    ORDER BY jarak";
                    $data   = mysql_query($sql);
                    echo ("addMarker($lat, $lon, '<b>$nama</b>');\n");    

                      //$json = "";
                      $json = 'var data= {';
                      $json .= '"niken":[ ';
                      $json2 = "";
                        while($x = mysql_fetch_array($data)){
                          $json .= '{';
                          $json .= '"id_reklame":"'.$x['id_reklame'].'",
                               "SIPR":"'.htmlspecialchars_decode($x['SIPR']).'",
                               "status":"'.htmlspecialchars_decode($x['status']).'",
                               "lat_reklame":"'.$x['lat_reklame'].'",
                               "long_reklame":"'.$x['long_reklame'].'",
                               "jarak":"'.$x['jarak'].'"
                                   },';
                          
                                
                         if (!empty($data)) {
                          $lat_reklame = $x['lat_reklame'];
                          $long_reklame =  $x['long_reklame'];
                          $kategori = $_POST['kategorireklame'];
                          

                          $sql2 = "SELECT id_reklame, SIPR, alamat,teks_reklame,kategori_produk,id_kategori_produk, lat_reklame, long_reklame,
                                  (6371 * acos(cos(radians(".$lat_reklame.")) 
                                  * cos(radians(lat_reklame)) * cos(radians(long_reklame) 
                                  - radians(".$long_reklame.")) + sin(radians(".$lat_reklame.")) 
                                  * sin(radians(lat_reklame)))) 
                                  AS jarak2 
                                  FROM tb_reklame2
                                  HAVING jarak2 <= 0.01 and id_reklame != ".$x['id_reklame']." AND id_kategori_produk=".$kategori."
                                  ORDER BY jarak2";
                                  $databaru   = mysql_query($sql2);
                                  echo "\n";
//                                    echo $json;

                          

                                  $json2 = 'var data2= {';
                                  $json2 .= '"niken2":[ ';
                                    while($y = mysql_fetch_array($databaru)){

                                      $json2 .= '{';
                                      $json2 .= '"id_reklame":"'.$y['id_reklame'].'",
                                           "SIPR":"'.htmlspecialchars_decode($y['SIPR']).'",
                                           "teks":"'.htmlspecialchars_decode($y['teks_reklame']).'",
                                           "lat_reklame":"'.$y['lat_reklame'].'",
                                           "long_reklame":"'.$y['long_reklame'].'",
                                           "jarak":"'.$y['jarak2'].'"
                                               },';
                                    }//=============while 2
                                    $json2 = substr($json2,0,strlen($json2)-1);
                                    $json2 .= ']';
                                    $json2 .= '}';
//                                    echo $json2;




                                     
                            

                          } //========if

                          $json .= $json2;
                          // echo "---jos";
                          //             echo $json;
                          //           echo $json2;
                          // echo "---jos2";

                          
                          
                          
                       }//=========whilw 1

                       echo $json;
                       echo $json2;



                          $json = substr($json,0,strlen($json)-1);
                          $json .= ']';
                         
                          // echo $json2;
                          $json .= '}'; 
                          echo "\n";
                          echo "\n";
   
                                    //$json = "";
                                    $json = 'var data= {';
                                    $json .= '"niken":[ ';
                                      while($x = mysql_fetch_array($data)){
                                        $json .= '{';
                                        $json .= '"id_reklame":"'.$x['id_reklame'].'",
                                             "SIPR":"'.htmlspecialchars_decode($x['SIPR']).'",
                                             "status":"'.htmlspecialchars_decode($x['status']).'",
                                             "lat_reklame":"'.$x['lat_reklame'].'",
                                             "long_reklame":"'.$x['long_reklame'].'",
                                             "jarak":"'.$x['jarak'].'"
                                                 },';


                          if (!empty($data)) {
                          $lat_reklame = $x['lat_reklame'];
                          $long_reklame =  $x['long_reklame'];

                          $sql3 = "SELECT id_jalan, nama_jalan, lat_rungkut, long_rungkut,
                                      (6371 * acos(cos(radians(".$lat_reklame.")) 
                                      * cos(radians(lat_rungkut)) * cos(radians(long_rungkut) 
                                      - radians(".$long_reklame.")) + sin(radians(".$lat_reklame.")) 
                                      * sin(radians(lat_rungkut)))) 
                                      AS jarak3 
                                      FROM lhs_rungkut
                                      HAVING jarak3 <= 1
                                      ORDER BY jarak3";
                                      
                                      $datajalan   = mysql_query($sql3);
                                      echo "\n";
                                      echo $json3;


                                                     $json3 = 'var data3= {';
                                                     $json3 .= '"niken3":[ ';
                                                      while($z = mysql_fetch_array($datajalan)){
                                                          $json3 .= '{';
                                                          $json3 .= '"id_jalan":"'.$z['id_jalan'].'",
                                                                    "nama_jalan":"'.htmlspecialchars_decode($z['nama_jalan']).'",
                                                                    "lat_rungkut":"'.$z['lat_rungkut'].'",
                                                                    "long_rungkut":"'.$z['long_rungkut'].'",
                                                                    "jarak":"'.$z['jarak3'].'"
                                                                    },';
                                                                
                                              }//=============while 3
                                              $json3 = substr($json3,0,strlen($json3)-1);
                                              $json3 .= ']';
                                              $json3 .= '}';
                                               echo $json3;
                                        }//============= if
                                      }//=========== while 1
                                        $json = substr($json,0,strlen($json)-1);
                                        $json .= ']';
                                       
                                        // echo $json2;
                                        $json .= '}'; 
                                        echo "\n";
                                        echo "\n";
                                         
                        
                    
                   /* echo "\nfor(i=0; i < data.niken.length;i++){
                    var pt = new google.maps.LatLng(parseFloat(data.niken[i].latitude),parseFloat(data.niken[i].longitude));
                    //bounds.extend(pt);
                    var marker = new google.maps.Marker({
                        map: map,
                        position: pt,
                        icon: 'place.png',
                        animation: google.maps.Animation.DROP

                    });       
                    //map.fitBounds(bounds);
                   // bindInfoWindow(marker, map, infoWindow, 'lemot');
                    
                  }";  */                 
              
              }
            }

            
          ?>
        
      google.maps.event.addDomListener(window, 'load', initialize);
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
          <a class="navbar-brand" href="#">Aplikasi AHP Spasial DCKTR</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
          <!--class active untuk menunjukkan kelas yg sekarang-->
            <li><a href="../../bismillah/SIG/index.php">Home</a></li>
      <li><a href="../../bismillah/SIG2/forminput.php">Daftar</a></li>
      <li class="dropdown">
              <a href="peta3.php" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Peta Reklame<span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="peta.php">Cari Reklame -> Jenis Reklame</a></li>
        <li><a href="peta2.php">Cari Reklame -> Ketegori Produk</a></li>
        <li><a href="peta3.php">Cari Reklame -> Jenis & Ketegori Produk</a></li>
        <li><a href="radius.php">Cari Reklame -> Radius</a></li>
              </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Input Data<span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="#">Data Nilai</a></li>
                <li><a href="#">Data Kriteria</a></li>
                <li><a href="#">Data Alternatif</a></li>
              </ul>
            </li>
      <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Analisis Data<span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="#">Analisis Krteria</a></li>
                <li><a href="#">Analisis Alternatif</a></li>
                <li><a href="#">Rangking</a></li>
        <li><a href="#">Laporan</a></li>
                <li role="separator" class="divider"></li>
                <li class="dropdown-header">Nav header</li>
              </ul>
            </li>
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
        <h1>Tabel Bobot Kriteria</h1>
        <p></p>
  
<div class="panel panel-primary">
<div class="panel-heading">  <p></p></div>
  <!-- kelas form method AJAX-->
</br>

  <!-- -->
<!--   <p><h1> Tabel Prioritas Bobot Kriteria</h1></p> -->



<form id="form1" name="form1" method="post" action="">

<footer id="colophon" class="site-footer" role="contentinfo">
<div class="footer-bottom">
<div class="container">

<div class="panel panel-default" width="100%" >
  <!-- Default panel contents -->
<div class="panel-heading">Matriks Perbandingan</div>

<table width="500" border="1" cellspacing="0" cellpadding="1">
    <!DOCTYPE html>
<html>
<head>
<!--<style>
table, th, td {
    border: 1px solid black;
}
th, td {
    padding: 3px;
    text-align: center;
}
</style>-->
</head>
<body>
<?php
$kriteria1 = $_POST['C1'];
$kriteria2 = $_POST['C2'];
$kriteria3 = $_POST['C3'];
$b2k1 = 1/$_POST['C1'];
$b3k1 = 1/$_POST['C2'];
$b3k2 = 1/$_POST['C3'];
$jumk1 = $b2k1+$b3k1+1;
$jumk2 = $kriteria1+1+$b3k2;
$jumk3 = $kriteria2+$kriteria3+1;

?>

<form>
<table class="table table-bordered">
  <tr class="info">
    <th>Kriteria</th>
    <th>Lokasi</th>
    <th>Reklame Sejenis</th>
    <th>Kepadatan Jalan</th>
  </tr>
  <tr>
    <td><b>Lokasi</b></td>
    <td>1</td>
    <td><?php echo $kriteria1 ?></td>
    <td><?php echo $kriteria2 ?></td>
  </tr>
  <tr>
    <td><b>Reklame Sejenis</b></td>
    <td><?php echo $b2k1 ?></td>
    <td>1</td>
    <td><?php echo $kriteria3 ?></td>
  </tr>
    <tr>
    <td ><b>Kepadatan Reklame</b></td>
    <td><?php echo $b3k1 ?></td>
    <td><?php echo $b3k2 ?></td>
    <td>1</td>
  </tr>
  <tr>
    <td><b>Jumlah</b></td>
    <td><?php echo $jumk1 ?></td>
    <td><?php echo $jumk2 ?></td>
    <td><?php echo $jumk3 ?></td>
  </tr>
</table>
</form>
</div>


<div class="panel panel-default">
<div class="panel-heading"> Matriks Nilai Kriteria</div>
<form>
<table class="table table-bordered">
  <!-- <label> Matriks Nilai Kriteria</label> -->
  <?php 
  $m11 = 1/$jumk1;
  $m12 = $kriteria1/$jumk2;
  $m31 = $b3k1/$jumk1;

  $m21 = $b2k1/$jumk1;
  $m22 = 1/$jumk2;
  $m32 = $b3k2/$jumk2;

  $m13 = $kriteria2/$jumk3;
  $m23 = $kriteria3/$jumk3;
  $m33 = 1/$jumk3;

  $jumb1 = $m11+$m12+$m13;
  $jumb2 = $m21+$m22+$m23;
  $jumb3 = $m31+$m32+$m33;
  $prio1 = $jumb1/3;
  $prio2 = $jumb2/3;
  $prio3 = $jumb3/3;

  ?>
  <tr class="info">
    <th>Kriteria</th>
    <th>Lokasi</th>
    <th>Reklame Sejenis</th>
    <th>Kepadatan Jalan</th>
    <td><b>Jumlah</b></td>
    <td><b> Prioritas </b></td>
  </tr>
  <tr>
    <td><b>Lokasi</b></td>
    <td><?php echo $m11 ?></td>
    <td><?php echo $m12 ?></td>
    <td><?php echo $m13 ?></td>
    <td><?php echo $jumb1 ?></td>
    <td><b><?php echo $prio1 ?></b></td>
  </tr>
  <tr>
    <td><b>Reklame Sejenis</b></td>
    <td><?php echo $m21 ?></td>
    <td><?php echo $m22 ?></td>
    <td><?php echo $m23 ?></td>
    <td><?php echo $jumb2 ?></td>
    <td><b><?php echo $prio2 ?></b></td>
  </tr>
    <tr>
    <td><b>Kepadatan Jalan</b></td>
    <td><?php echo $m31 ?></td>
    <td><?php echo $m32 ?> </td>
    <td><?php echo $m33 ?> </td>
    <td><?php echo $jumb3 ?></td>
    <td><b><?php echo $prio3 ?></b></td>
  </tr>
</table>
</form>
</div>



<div class="panel panel-default">
<div class="panel-heading"> Matriks Nilai Kriteria</div>
<form>
<table class="table table-bordered">
  <?php 


  $kali11 = $prio1*1;
  $kali21 = $prio1*$b2k1;
  $kali31 = $prio1*$b3k1;

  $kali12 = $prio2*$kriteria1;
  $kali22 = $prio2*1;
  $kali32 = $prio2*$b3k2;

  $kali13 = $prio3*$kriteria2;
  $kali23 = $prio3*$kriteria3;
  $kali33 = $prio3*1;

  $jumkali1 = $kali11+$kali12+$kali13;
  $jumkali2 = $kali21+$kali22+$kali23;
  $jumkali3 = $kali31+$kali32+$kali33;


  ?>
 <tr class="info">
    <th>Kriteria</th>
    <th>Lokasi</th>
    <th>Reklame Sejenis</th>
    <th>Kepadatan Reklame</th>
    <td><b> Jumlah</b> </td>
  </tr>
  <tr>
    <td><b>Lokasi</b></td>
    <td><?php echo $kali11 ?></td>
    <td><?php echo $prio2*$kriteria1 ?></td>
    <td><?php echo $kali13 ?></td>
    <td><?php echo $jumkali1 ?></td>
  </tr>
  <tr>
    <td><b>Reklame Sejenis</b></td>
    <td><?php echo $kali21 ?></td>
    <td><?php echo $kali22 ?></td>
    <td><?php echo $kali23 ?></td>
    <td><?php echo $jumkali2 ?></td>
  </tr>
    <tr>
    <td><b>Kepadatan Jalan</b></td>
    <td><?php echo $kali31 ?></td>
    <td><?php echo $kali32 ?> </td>
    <td><?php echo $kali33 ?> </td>
    <td><?php echo $jumkali3 ?></td>
  </tr>
</table>
</form>
</div>


<div class="panel panel-default">
<div class="panel-heading"> Perhitungan Rasio Konsistensi </div>
<form>
<table class="table table-bordered">
  <!-- <label> Perhitungan Rasio Konsistensi</label> -->
<?php 
  $m11 = 1/$jumk1;
  $m12 = $kriteria1/$jumk2;
  $m31 = $b3k1/$jumk1;
  $m21 = $b2k1/$jumk1;
  $m22 = 1/$jumk2;
  $m32 = $b3k2/$jumk2;
  $m13 = $kriteria2/$jumk3;
  $m23 = $kriteria3/$jumk3;
  $m33 = 1/$jumk3;
  $jumb1 = $m11+$m12+$m13;
  $jumb2 = $m21+$m22+$m23;
  $jumb3 = $m31+$m32+$m33;
  $prio1 = $jumb1/3;
  $prio2 = $jumb2/3;
  $prio3 = $jumb3/3;
  $kali11 = $prio1/1;
  $kali21 = $prio1/$b2k1;
  $kali31 = $prio1/$b3k1;
  $kali12 = $prio2/$kriteria1;
  $kali22 = $prio2/1;
  $kali32 = $prio2/$b3k2;
  $kali13 = $prio3/$kriteria2;
  $kali23 = $prio3/$kriteria3;
  $kali33 = $prio3/1;

  $hasil1 = $jumkali1+$prio1;
  $hasil2 = $jumkali2+$prio2;
  $hasil3 = $jumkali3+$prio3;
  $total = $hasil1+$hasil2+$hasil3;
  $lambdamaks = $total/3;
  $CI = ($lambdamaks-3)/3;
  $CR = $CI/0.58;
  ?>

  <tr class="info">
    <th></th>
    <th>Jumlah</th>
    <th>Prioritas</th>
    <th>Hasil</th>
  </tr>
  <tr>
    <td><b>Lokasi</b></td>
    <td><?php echo $jumkali1 ?></td>
    <td><?php echo $prio1 ?></td>
    <td><?php echo $hasil1 ?></td>
  </tr>
  <tr>
    <td><b>Reklame Sejenis</b></td>
    <td><?php echo $jumkali2 ?></td>
    <td><?php echo $prio2 ?></td>
    <td><?php echo $hasil2 ?></td>
  </tr>
    <tr>
    <td><b>Kepadatan Jalan</b></td>
    <td><?php echo $jumkali3 ?></td>
    <td><?php echo $prio3 ?> </td>
    <td><?php echo $hasil3 ?> </td>

  </tr>
  <tr>
    <td></td>
    <td></td>
    <td><b> Jumlah </b></td>
    <td><?php echo $total ?> </td>
  </tr>
</table>
</div>

<br>
<label>Jumlah (hasil penjumlahan dari niai-nilai hasil) = <?php  echo $total ?></label>
<br>
<label>N (jumlah kriteria λ ) = 3 </label>
<br>
<label>λmaks (jumlah/n) = <?php  echo $lambdamaks ?> </label>
<br>
<label>CI ( λmaks - N)/N ) =  <?php  echo $CI ?>  </label>
<br>
<label>CR (CI/IR) =  <?php  echo $CR ?> </label>
<br>
<label> <h3>
<label><p>Hasil menunjukkan bahwa nilai : </p></label>

<?php 
if($CR <= 0.1) {
  echo "Matriks konsisten";
}else{
echo " Matriks tidak konsisten"; 
}
?> 
</h3></label>
</form>




</div>
</div>
     </div>    







<!-- AWAL TABEL KRITERIA -->


      
<footer id="colophon" class="site-footer" role="contentinfo">
<div class="footer-bottom">
<div class="container">

        <div class="sixteen columns">
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

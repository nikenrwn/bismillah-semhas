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
        <h1>Aplikasi AHP Berbasis Spasial</h1>
        <p></p>
  

<!-- AWAL TABEL KRITERIA -->
<form id="form1" name="form1" method="post" action="berjuangtabel.php">
 <h2>Input Nilai Kriteria</h2>          
  <table class="table table-bordered" align="center">
    <thead >
      <tr align="center" class="info" >
        <td>
          Kriteria
        </td>
        <td>
          Nilai 
        </td>
        <td>
          Kepadatan Jalan
        </td>
      </tr>
    </thead>


    <tbody>
      <tr>
        <td>
          <p align="center">Lokasi</p>
        </td>
        <td>
            <select name="C1" id="C1" class="form-control" >
              <option>--Pilih Nilai Kriteria--</option>
              <option value="1">Sama Penting Dengan Kualitas (Nilai=1)</option>
              <option value="3">Sedikit Lebih Penting Dari Kualitas (Nilai=3)</option>
              <option value="5">Lebih Penting Dari Kualitas (Nilai=5)</option>
              <option value="7">Lebih Mutlak Penting Dari Kualitas (Nilai=7)</option>
              <option value="9">Mutlak Penting Dari Kualitas (Nilai=9)</option>
              <option value="2">Nilai Berdekatan Dengan Kualitas (Nilai=2)</option>
              <option value="0.333333333333333">Kualitas Sedikit Lebih Penting Dari Harga (Nilai=1/3)</option>
              <option value="0.2">Kualitas Lebih Penting Dari Harga (Nilai=1/5)</option>
              <option value="0.142857142857143">Kualitas Lebih Mutlak Penting Dari Harga (Nilai=1/7)</option>
              <option value="0.111111111111111">Kualitas Mutlak Penting Dari Harga (Nilai=1/9)</option>
              <option value="0.5">Kualitas Nilai Berdekatan Dengan Harga (Nilai=1/2)</option>
            </select>
        </td>
        <td>
            <p align="center">Reklame Sejenis</p>
        </td>
        </tr>
        <tr >
        <td>
            <p align="center">Lokasi</p>
        </td>
        <td>
            <select name="C2" id="C2" class="form-control" >
              <option>--Pilih Nilai Kriteria--</option>
              <option value="1">Sama Penting Dengan Kualitas (Nilai=1)</option>
              <option value="3">Sedikit Lebih Penting Dari Kualitas (Nilai=3)</option>
              <option value="5">Lebih Penting Dari Kualitas (Nilai=5)</option>
              <option value="7">Lebih Mutlak Penting Dari Kualitas (Nilai=7)</option>
              <option value="9">Mutlak Penting Dari Kualitas (Nilai=9)</option>
              <option value="2">Nilai Berdekatan Dengan Kualitas (Nilai=2)</option>
              <option value="0.333333333333333">Kualitas Sedikit Lebih Penting Dari Harga (Nilai=1/3)</option>
              <option value="0.2">Kualitas Lebih Penting Dari Harga (Nilai=1/5)</option>
              <option value="0.142857142857143">Kualitas Lebih Mutlak Penting Dari Harga (Nilai=1/7)</option>
              <option value="0.111111111111111">Kualitas Mutlak Penting Dari Harga (Nilai=1/9)</option>
              <option value="0.5">Kualitas Nilai Berdekatan Dengan Harga (Nilai=1/2)</option>
            </select>
        </td>
        <td>
            <p align="center">Kepadatan Jalan</p>
        </td>
        </tr>
        <tr>
        <td>
            <p align="center">Reklame Sejenis</p>
        </td>
        <td>
            <select name="C3" id="C3" class="form-control" >
              <option>--Pilih Nilai Kriteria--</option>
              <option value="1">Sama Penting Dengan Kualitas (Nilai=1)</option>
              <option value="3">Sedikit Lebih Penting Dari Kualitas (Nilai=3)</option>
              <option value="5">Lebih Penting Dari Kualitas (Nilai=5)</option>
              <option value="7">Lebih Mutlak Penting Dari Kualitas (Nilai=7)</option>
              <option value="9">Mutlak Penting Dari Kualitas (Nilai=9)</option>
              <option value="2">Nilai Berdekatan Dengan Kualitas (Nilai=2)</option>
              <option value="0.333333333333333">Kualitas Sedikit Lebih Penting Dari Harga (Nilai=1/3)</option>
              <option value="0.2">Kualitas Lebih Penting Dari Harga (Nilai=1/5)</option>
              <option value="0.142857142857143">Kualitas Lebih Mutlak Penting Dari Harga (Nilai=1/7)</option>
              <option value="0.111111111111111">Kualitas Mutlak Penting Dari Harga (Nilai=1/9)</option>
              <option value="0.5">Kualitas Nilai Berdekatan Dengan Harga (Nilai=1/2)</option>
            </select>
        </td>
        <td>
          <p align="center">Kepadatan Jalan</p>
        </td>
        </tr>
    </tbody>
  </table>
  <div align="center"><input type="submit" class="btn btn-primary" name="button" id="button" value="Proses" /></div>
  </form>
</div>
</div>
<!-- PENUTUP TABEL KRITERIA -->

      
<footer id="colophon" class="site-footer" role="contentinfo">
<div class="footer-bottom">
<div class="container">


<!-- Akhir Tabel -->


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

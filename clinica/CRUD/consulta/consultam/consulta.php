<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title></title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<link href="../../../assets/img/favicon.png" rel="icon">
		<link href="../../../assets/img/apple-touch-icon.png" rel="apple-touch-icon">

		<!-- Google Fonts -->
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

		<!-- Vendor CSS Files -->
		<link href="../../../assets/vendor/aos/aos.css" rel="stylesheet">
		<link href="../../../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="../../../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
		<link href="../../../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
		<link href="../../../assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
		<link href="../../../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
		<link href="../../../assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

		<!-- Template Main CSS File -->
		<link href="../../../assets/css/style.css" rel="stylesheet">

</head>
<body>



	<?php
	    include_once("../../constantes.php");
		require_once("class/class.consulta.php");
		
		$cn = conectar();
		$v = new consulta($cn);
		//vehiculo::MetodoEstatico();	
		
    // Codigo necesario para realizar pruebas.
		if(isset($_GET['d'])){
		
			// 2.1 PETICION GET
			// $dato = $_GET['d'];
			
			// 2.2 DETALLE id
			$dato = base64_decode($_GET['d']); //DECODIFICA
			$tmp = explode("/", $dato);
			$op = $tmp[0];
			$id = $tmp[1];
			
			if($op == "det"){
				echo $v->get_detail_consulta($id);
			}elseif($op == "act"){
				echo $v->get_form($id);
			}elseif($op == "new"){
				echo $v->get_form();
			}elseif($op == "del"){
				echo $v->delete_consulta($id); // BORRAR TODOS LOS REGISTROS DE LA BASE DE DATOS
			}
			
        }else{
			//echo "<br>PETICION POST <br><br>";
				
				
		    if(isset($_POST['Guardar']) && $_POST['op']=="new"){
				$v->save_consulta();
			}elseif(isset($_POST['Guardar']) && $_POST['op']=="update"){
				$v->update_consulta();
			}else{
				echo $v->get_list();
			}
			
		}
				

		
//*******************************************************
		function conectar(){
			
			$page =
			'  <header id="header" class="fixed-top d-flex align-items-center">
			<div class="container d-flex align-items-center justify-content-between">
		
			<div class="logo">
				<h1><a href=""></a></h1>
			</div>
		
			<nav id="navbar" class="navbar">
			<ul>
				<li><a class="nav-link scrollto active" href="">Consulta</a></li>
				<li><a class="nav-link scrollto " href="../../receta/recetam/receta.php">Recetas</a></li>
				<form method="post" action="../../../Sesiones/cerrar.php">
					<button class="getstarted scrollto" type="submit">Cerrar Sesión</button>
				</form>
			</ul>
				<i class="bi bi-list mobile-nav-toggle"></i>
			</nav><!-- .navbar -->
		
			</div>
			</header>
			<main id="main">

			<!-- ======= Breadcrumbs Section ======= -->
			<section class="breadcrumbs">
				<div class="container">
					<div class="d-flex justify-content-between align-items-center">
						<h2>Consulta</h2>
					</div>
				</div>
			</section>
			<section class="inner-page">
    			<div class="container">';
			//echo "<br> CONEXION A LA BASE DE DATOS<br>";
			//error_reporting(0);
			$c = new mysqli(SERVER,USER,PASS,BD);
			
			if($c->connect_errno) {
				
				die($page);
			}else{
				//echo "La conexión tuvo éxito .......<br><br>";
				
			}
			$page.='</div>
			</section>
			</main>';
			echo $page;
			$c->set_charset("utf8");
			return $c;
		}
//**********************************************************
		
		
	?>

<br>
<script src="../../assets/js/main.js"></script>

</body>
</html>
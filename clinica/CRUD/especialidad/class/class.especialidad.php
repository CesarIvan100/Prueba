<?php
class especialidad{
	private $id;
	private $nombre;
	private $dias;
	private $hi;
	private $hf;
	private $con;//conexion
	
	function __construct($cn){
		$this->con = $cn;
		//echo "<br><br> <center>EJECUTANDOSE EL CONSTRUCTOR CONSULTA<center><br><br>";
	}
		
		
//*********************** 3.1 METODO update_vehiculo() **************************************************	
	
	public function update_especialidad(){
		$this->id = $_POST['id'];
		$this->nombre = $_POST['nombre'];
		$this->dias = $_POST['dias'];
		$this->hi = $_POST['hi'];
		$this->hf = $_POST['hf'];
		//$this->marca = $_POST['marcaCMB'];
		//$this->color = $_POST['colorCMB'];
		//$this->combustible = $_POST['combustibleRBT'];
		
		//if ($this->_validar_genero_especialidad($this->paciente, $this->medico)) {
			$sql = "UPDATE especialidades SET
												Descripcion = '$this->nombre' ,
												Dias = '$this->dias',
												Franja_HI = '$this->hi',
												Franja_HF = '$this->hf'";
			//echo $sql;
			//exit;
		
			$sql .= " WHERE IdEsp=$this->id;";
			//echo $sql;
			if($this->con->query($sql)){
				echo $this->_message_ok(" modificó ");
			}else{
				echo $this->_message_error(" al modificar ");
			}								
		//} else {
            // Mostrar mensaje de error
            //echo $this->_message_error(" guardar");
           // exit;
       // }
	}
	

//*********************** 3.2 METODO save_vehiculo() **************************************************	

	public function save_especialidad(){
		
		
		$this->id = $_POST['id'];
		$this->nombre = $_POST['nombre'];
		$this->dias = $_POST['dias'];
		$this->hi = $_POST['hi'];
		$this->hf = $_POST['hf'];
		
			$sql = "INSERT INTO especialidades VALUES(NULL,
												'$this->nombre',
												'$this->dias',
												'$this->hi',
												'$this->hf');";
			//echo $sql;
			//exit;
			if($this->con->query($sql)){
				echo $this->_message_ok(" guardó ");
			}else{
				echo $this->_message_error(" guardar ");
			}								
										
	}


//*********************** 3.3 METODO _get_name_File() **************************************************	
	
	/*private function _get_name_file($nombre_original, $tamanio){
		$tmp = explode(".",$nombre_original); //Divido el nombre por el punto y guardo en un arreglo
		$numElm = count($tmp); //cuento el número de elemetos del arreglo
		$ext = $tmp[$numElm-1]; //Extraer la última posición del arreglo.
		$cadena = "";
			for($i=1;$i<=$tamanio;$i++){
				$c = rand(65,122);
				if(($c >= 91) && ($c <=96)){
					$c = NULL;
					 $i--;
				 }else{
					$cadena .= chr($c);
				}
			}
		return $cadena . "." . $ext;
	}*/

	private function _get_name_file($nombre_original) {
		return $nombre_original;
	}
	
	
//*************************************** PARTE I ************************************************************
	
	    
	/*Aquí se agregó el parámetro:  $defecto*/
	private function _get_combo_db($tabla,$valor,$etiqueta,$nombre,$defecto){
		$html = '<select name="' . $nombre . '" class="form-control">';
		$sql = "SELECT $valor,$etiqueta FROM $tabla;";
		$res = $this->con->query($sql);
		while($row = $res->fetch_assoc()){
			//ImpResultQuery($row);
			$html .= ($defecto == $row[$valor])?'<option value="' . $row[$valor] . '" selected>' . $row[$etiqueta] . '</option>' . "\n" : '<option value="' . $row[$valor] . '">' . $row[$etiqueta] . '</option>' . "\n";
		}
		$html .= '</select>';
		return $html;
	}




	
	/*Aquí se agregó el parámetro:  $defecto*/
	private function _get_combo_anio($nombre,$anio_inicial,$defecto){
		$html = '<select name="' . $nombre . '" class="form-control" >';
		$anio_actual = date('Y');
		for($i=$anio_inicial;$i<=$anio_actual;$i++){
			$html .= ($i == $defecto)? '<option value="' . $i . '" selected>' . $i . '</option>' . "\n":'<option value="' . $i . '">' . $i . '</option>' . "\n";
		}
		$html .= '</select>';
		return $html;
	}
	
	private function _get_input_fecha($nombre, $fechaDefecto = null, $rangoAnios = 10) {
		// Obtenemos la fecha actual o la proporcionada como predeterminada
		$fechaActual = $fechaDefecto ? $fechaDefecto : date('Y-m-d');
	
		// Inicializamos el rango de años
		$anioActual = date('Y', strtotime($fechaActual));
		$rangoAnios = range($anioActual, $anioActual + $rangoAnios);
	
		// Generamos el campo de entrada date
		$htmlFecha = '<input type="date" name="' . $nombre . '" class="form-control" value="' . $fechaActual . '"';
	
		// Agregamos el rango de años
		$htmlFecha .= ' min="' . min($rangoAnios) . '" max="' . max($rangoAnios) . '"';
	
		$htmlFecha .= '>';
	
		return $htmlFecha;
	}



	/*Aquí se agregó el parámetro:  $defecto*/
	private function _get_radio($arreglo,$nombre,$defecto){
		
		$html = '
		<table border=0 align="left">';
		
		//CODIGO NECESARIO EN CASO QUE EL USUARIO NO SE ESCOJA UNA OPCION
		
		foreach($arreglo as $etiqueta){
			$html .= '
			<tr>
				<td>' . $etiqueta . ' </td>
				<td>';
				
				if($defecto == NULL){
					// OPCION PARA GRABAR UN NUEVO VEHICULO (id=0)
					$html .= '<input type="radio" class="form-check-input" value="' . $etiqueta . '" name="' . $nombre . '" checked/></td>';
				
				}else{
					// OPCION PARA MODIFICAR UN VEHICULO EXISTENTE
					$html .= ($defecto == $etiqueta)? '<input type="radio" class="form-check-input"  value="' . $etiqueta . '" name="' . $nombre . '" checked/></td>' : '<input type="radio" value="' . $etiqueta . '" name="' . $nombre . '"/></td>';
				}
			
			$html .= '</tr>';
		}
		$html .= '
		</table>';
		return $html;
	}
	
	

//*******************GET FORM****************/


public function get_form($id=NULL){
		
	if($id == NULL){
			$this-> nombre = NULL;	
			$this-> dias = NULL;
			$this-> hi = NULL;	
			$this-> hf = NULL;	
			$flag = NULL;
			$op = "new";

	}else{

		$sql = "SELECT Descripcion AS nombre, Dias as dias, Franja_hi as hi, franja_hf as hf
		FROM especialidades WHERE IdEsp=$id;";
		$res = $this->con->query($sql);
		$row = $res->fetch_assoc();
		
		$num = $res->num_rows;
		if($num==0){
			$mensaje = "tratar de actualizar el medicamento con id= ".$id;
			echo $this->_message_error($mensaje);
		}else{             
                
				// ***** TUPLA ENCONTRADA *****
				/*echo "<br>REGISTRO A MODIFICAR: <br>";
					echo "<pre>";
						print_r($row);
					echo "</pre>";
				*/
		
             // ATRIBUTOS DE LA CLASE ROL   
                $this->nombre = $row['nombre'];
				$this->dias = $row['dias'];
				$this->hi = $row['hi'];
				$this->hf = $row['hf'];
                //$flag = "disabled";
				$flag = "enable";
                $op = "update"; 
            }
	}
    
		$html = '<center>
		<a href="especialidad.php">Regresar a Lista de especialidad</a>
		<br>
		<center>
		<div class="card w-50">
		<div class="card-header text-white bg-info mb-3">DATOS ESPECIALIDAD</div>
		<form name="Form_rol" method="POST" action="especialidad.php" enctype="multipart/form-data">
		<!---Lineas Agregadas-->
		<input type="hidden" name="id" value="' . $id  . '">
		<input type="hidden" name="op" value="' . $op  . '">
			<table border="0" align="center" class="table">
				<tr>
					<td>Nombre de Especialidad:</td>
					<td><input class="form-control" type="text" size="15" name="nombre" value="' . $this->nombre . '"></td>
				<tr/>
				<tr>
					<td>Dias:</td>
					<td><input class="form-control" type="text" size="15" name="dias" value="' . $this->dias . '"></td>
				<tr/>
				<tr>
					<td>Hora de Inicio:</td>
					<td><input class="form-control" type="time" size="15" name="hi" value="' . $this->hi . '"></td>
				<tr/>
				<tr>
					<td>Hora de Fin:</td>
					<td><input class="form-control" type="time" size="15" name="hf" value="' . $this->hf . '"></td>
				<tr/>
				<tr>
					<th colspan="2"><center><input type="submit" class="btn btn-primary mb-2" name="Guardar" value="GUARDAR"></center></th>
				</tr>												
			</table>
			</div>
			</div>
			</center>
			<br>';
		return $html;

		
	}
	
	
	
	public function get_list(){
		$d_new = "new/0";                           //Línea agregada
        $d_new_final = base64_encode($d_new);       //Línea agregada CODIFICA
				
		$html = ' 
		<div class="container text-center">
		<div class="table-responsive">
		<table border="1" align="center" class="table table-hover">
			<tr>
				<th colspan="8">Lista de Especialidad</th>
			</tr>
			<tr>
				<th colspan="8"><a href="especialidad.php?d=' . $d_new_final . '">Nuevo</a></th>
			</tr>
			<tbody class="table-group-divider">
			<tr>
				<th>ID</th>
				<th>Especialidad</th>
				<th>Dias</th>
				<th colspan="3">Acciones</th>
			</tr>
			<tbody class="table-group-divider">';
		$sql = "SELECT IdEsp AS id, Descripcion AS especialidad, Dias as dias, Franja_HI as hi, Franja_hf as hf
				FROM especialidades;";	
		$res = $this->con->query($sql);		
		
		// VERIFICA si existe TUPLAS EN EJECUCION DEL Query
		$num = $res->num_rows;
        if($num != 0){
		
		    while($row = $res->fetch_assoc()){ //saca registros
			/*
				echo "<br>VARIALE ROW ...... <br>";
				echo "<pre>";
						print_r($row);
				echo "</pre>";
			*/
		    		
				// URL PARA BORRAR
				$d_del = "del/" . $row['id'];
				$d_del_final = base64_encode($d_del);
				
				// URL PARA ACTUALIZAR
				$d_act = "act/" . $row['id'];
				$d_act_final = base64_encode($d_act);
				
				// URL PARA EL DETALLE
				$d_det = "det/" . $row['id'];
				$d_det_final = base64_encode($d_det);	
				
				$html .= '
					<tr>
						<td>' . $row['id'] . '</td>
						<td>' . $row['especialidad'] . '</td>
						<td>' . $row['dias'] . '</td>
						<td><a class="btn btn-danger" href="especialidad.php?d=' . $d_del_final . '">Borrar</a></td>
						<td><a class="btn btn-warning" href="especialidad.php?d=' . $d_act_final . '">Actualizar</a></td>
						<td><a class="btn btn-info" href="especialidad.php?d=' . $d_det_final . '">Detalle</a></td>
						
					</tr>';
			
		    }
		}else{
			$mensaje = "Tabla Especialidad" . "<br>";
            echo $this->_message_BD_Vacia($mensaje);
			echo "<br><br><br>";
		}
		$html .= '</table>';
		return $html;
		
	}
	
	

	
	
//****************************************** NUEVO CODIGO *****************************************

public function get_detail_especialidad($id){
	$sql = "SELECT IdEsp as id, Descripcion as especialidad, Dias as dias, Franja_HI as hi, Franja_HF as hf
        FROM especialidades
		WHERE IdEsp = $id;";

		
		$res = $this->con->query($sql);
		$row = $res->fetch_assoc();
		// VERIFICA SI EXISTE id
		$num = $res->num_rows;
        
	if($num == 0){
        $mensaje = "desplegar el detalle de la especialidad con id= ". $id . "<br>";
        echo $this->_message_error($mensaje);
				
    }else{ 
	
	    /*echo "<br>TUPLA<br>";
	    echo "<pre>";
				print_r($row);
		echo "</pre>";*/

		$html = '
		<center>
			<div class="card w-75">
				<div class="container">
					<div class="row">
						<div class="col">
							<table border="1" class="table" align="center">
								<tr>
									<th colspan="2">DATOS DE ESPECIALIDAD</th>
								</tr>
								<tr>
									<td>ID: </td>
									<td>'. $row['id'] .'</td>
								</tr>
								<tr>
									<td>Nombre de la Especialidad: </td>
									<td>'. $row['especialidad'] .'</td>
								</tr>
								<tr>
									<td>Dias de Disponibilidad: </td>
									<td>'. $row['dias'] .'</td>
								</tr>
								<tr>
									<td>Hora de Inicio: </td>
									<td>'. $row['hi'] .'</td>
								</tr>
								<tr>
									<td>Hora de Fin: </td>
									<td>'. $row['hf'] .'</td>
								</tr>
								<tr>
									<th colspan="2"><br><a href="especialidad.php">Regresar</a></th>
								</tr>							
							</table> 
						</div>
					</div>
				</div>
			</div>
		</center>';
		
		return $html;
	}	
	
}


	public function delete_especialidad($id){
		
		$sql = "DELETE FROM especialidades WHERE IdEsp=$id;";
		if($this->con->query($sql)){
			echo $this->_message_ok("eliminó");
		}else{
			echo $this->_message_error("eliminar<br>");
		}
	
	}


	
//***************************************************************************************	
	
	private function _calculo_matricula($avaluo){
		return number_format(($avaluo * 0.10),2);
	}

	private function _get_genero_paciente($valor) {
		$sql = "SELECT Genero FROM pacientes WHERE PacienteID = $valor;";
		$result = $this->con->query($sql);
		if ($result) {
			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();
				return $row['Genero'];
			}else {
				return 'Desconocido';
			}
		} 
	}

	private function _get_especialidad_medico($valor) {
		$sql = "SELECT especialidad FROM medicos WHERE medicoID = $valor;";
		$result = $this->con->query($sql);
		if ($result) {
			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();
				return $row['especialidad'];
			}else {
				return 'Desconocido';
			}
		} 
	}

	private function _validar_genero_especialidad($pacienteID, $medicoID) {
        $generoPaciente = $this->_get_genero_paciente($pacienteID);
		echo $generoPaciente;
        $especialidadMedico = $this->_get_especialidad_medico($medicoID);
		echo $especialidadMedico;
        return (($generoPaciente !== 'Masculino' && $especialidadMedico === 'Ginecología')||($generoPaciente === 'Masculino' && $especialidadMedico !== 'Ginecología'));
    }
	
//***************************************************************************************************************************
	
	private function _message_error($tipo){
		$html = '
		<table border="0" align="center">
			<tr>
				<th>Error al ' . $tipo . 'Favor contactar a .................... </th>
			</tr>
			<tr>
				<th><a href="especialidad.php">Regresar</a></th>
			</tr>
		</table>';
		return $html;
	}
	
	
	private function _message_BD_Vacia($tipo){
		$html = '
		<table border="0" align="center">
			<tr>
				<th> NO existen registros en la ' . $tipo . '. Favor contactar a .................... </th>
			</tr>
	
		</table>';
		return $html;
	
	
	}
	
	private function _message_ok($tipo){
		$html = '
		<table border="0" align="center">
			<tr>
				<th>El registro se  ' . $tipo . ' correctamente</th>
			</tr>
			<tr>
				<th><a href="especialidad.php">Regresar</a></th>
			</tr>
		</table>';
		return $html;
	}
//************************************************************************************************************************************************


}
?>


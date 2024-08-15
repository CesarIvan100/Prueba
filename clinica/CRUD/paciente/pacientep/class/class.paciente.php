<?php
class paciente{
	private $id;
	private $nombre;
	private $apellido;
	private $direccion;
	private $telefono;
	private $con;//conexion
	
	function __construct($cn){
		$this->con = $cn;
		
	}
		
		
	public function update_paciente(){
		$this->id = $_POST['id'];
		$this->nombre = $_POST['nombre'];
		$this->apellido = $_POST['apellido'];
		$this->direccion = $_POST['direccion'];
		$this->telefono = $_POST['telefono'];
			
			$sql = "UPDATE pacientes SET paciente=$this->paciente,
										Nombre='$this->nombre',
										apellido=$this->apellido,
										direccion='$this->direccion',
										telefono='$this->telefono',

		
			$sql .= " WHERE IdPaciente=$this->id;";
			echo $sql;
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
	

	public function save_paciente(){
		
		$this->id = $_POST['id'];
		$this->nombre = $_POST['nombre'];
		$this->apellido = $_POST['apellido'];
		$this->direccion = $_POST['direccion'];
		$this->telefono = $_POST['telefono'];
		

		$sql = "INSERT INTO pacientes VALUES(NULL,
												$this->usuario,
												'$this->nombre',
												'$this->apellido',
												'$this->direccion',
												'$this->telefono',

			if($this->con->query($sql)){
				echo $this->_message_ok(" guardó");
			}else{
				echo $this->_message_error(" guardar, no cumple con la condición");
			}							
		
	}


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



public function get_form($id=NULL){
		
	if($id == NULL){
			$this->nombre = NULL;
			$this->apellido = NULL;
			$this->direccion = NULL;
			$this->telefono = NULL;

	}else{

		$sql = "SELECT * FROM pacientes WHERE IdPaciente=$id;";
		$res = $this->con->query($sql);
		$row = $res->fetch_assoc();
		
		$num = $res->num_rows;
		if($num==0){
			$mensaje = "tratar de actualizar el paciente con id= ".$id;
			echo $this->_message_error($mensaje);
		}else{             
                
				// ***** TUPLA ENCONTRADA *****
				echo "<br>REGISTRO A MODIFICAR: <br>";
					echo "<pre>";
						print_r($row);
					echo "</pre>";
			
		
                $this->nombre = $row['nombre'];
                $this->apellido = $row['apellido'];
                $this->direccion = $row['direccion'];
				$this->telefono = $row['telefono'];
				
                //$flag = "disabled";
				$flag = "enable";
                $op = "update"; 
            }
	}
    
		$html = '
		<a href="paciente.php">Regresar a Lista de Pacientes</a>
		<br>
		<div class="card w-50">
		<div class="card-header text-white bg-info mb-3">DATOS DE PACIENTES</div>
		<form name="Form_vehiculo" method="POST" action="paciente.php" enctype="multipart/form-data">
		<!---Lineas Agregadas-->
		<input type="hidden" name="id" value="' . $id  . '">
		<input type="hidden" name="op" value="' . $op  . '">
			<table border="0" align="center" class="table">
				<tr>
					<td>Nombre:</td>
					<td><input class="form-control" type="text" size="15" name="nombre" value="' . $this->nombre . '"></td>
				</tr>
				<tr>
					<td>Apellido:</td>
					<td><input class="form-control" type="text" size="15" name="apellido" value="' . $this->apellido . '"></td>
				</tr>
				<tr>
					<td>Direccion:</td>
					<td><input class="form-control" type="text" size="15" name="direccion" value="' . $this->direccion . '"></td>
				</tr>
				<tr>
					<td>Telefono:</td>
					<td><input class="form-control" type="text" size="15" name="telefono" value="' . $this->telefono . '"></td>
				</tr>
				
				<tr>
					<th colspan="2"><center><input type="submit" class="btn btn-primary mb-2" name="Guardar" value="GUARDAR"></center></th>
				</tr>												
			</table>
			</div>
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
				<th colspan="8">Lista de Pacientes</th>
			</tr>
			<tr>
			</tr>
			<tbody class="table-group-divider">
			<tr>
				<th>Id</th>
				<th>Nombre</th>
				<th>Apellido</th>
				<th>Direccion</th>
				<th>Telefono</th>
				<th colspan="3">Acciones</th>
			</tr>
			<tbody class="table-group-divider">';
		$sql = "SELECT p.IdPaciente AS id, p.nombre as nombre, p.apellido as apellido, p.direccion as direccion, p.telefono as telefono
				FROM pacientes
				WHERE u.idpaciente = p.idpaciente
				ORDER BY id";	
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
						<td>' . $row['nombre'] . '</td>
						<td>' . $row['cedula'] . '</td>
						<td>' . $row['edad'] . '</td>
						<td><a class="btn btn-info" href="paciente.php?d=' . $d_det_final . '">Detalle</a></td>
						
					</tr>';
			
		    }
		}else{
			$mensaje = "Tabla Pacientes" . "<br>";
            echo $this->_message_BD_Vacia($mensaje);
			echo "<br><br><br>";
		}
		$html .= '</table>';
		return $html;
		
	}
	
	

	
	
//****************************************** NUEVO CODIGO *****************************************

public function get_detail_paciente($id){
	$sql = "SELECT p.IdPaciente AS id, u.Nombre as usuario, p.nombre as nombre, p.cedula as cedula,
			p.edad as edad, p.genero as genero, p.estatura as estatura,p.peso as peso, u.foto as foto
			FROM pacientes p, usuarios u
			WHERE u.idusuario = p.idusuario AND p.IdPaciente = $id";

		
		$res = $this->con->query($sql);
		$row = $res->fetch_assoc();
		// VERIFICA SI EXISTE id
		$num = $res->num_rows;
        
	if($num == 0){
        $mensaje = "desplegar el detalle de la consulta con id= ". $id . "<br>";
        echo $this->_message_error($mensaje);
				
    }else{ 
	
	    /*echo "<br>TUPLA<br>";
	    echo "<pre>";
				print_r($row);
		echo "</pre>";*/
	
		$html = '
		<div class="card w-75">
			<div class="container">
				<div class="row">
    				<div class="col">
						<table border="1" class="table" align="center">
							<tr>
								<th colspan="2">DATOS DE PACIENTE</th>
							</tr>
							<tr>
								<td>ID: </td>
								<td>'. $row['id'] .'</td>
							</tr>
							<tr>
								<td>Nombre: </td>
								<td>'. $row['nombre'] .'</td>
							</tr>
							<tr>
								<td>Cedula: </td>
								<td>'. $row['cedula'] .'</td>
							</tr>
							<tr>
								<td>Usuario: </td>
								<td>'. $row['usuario'] .'</td>
							</tr>
							<tr>
								<td>Genero: </td>
								<td>'. $row['genero'] .'</td>
							</tr>
							<tr>
								<td>Edad: </td>
								<td>'. $row['edad'] .'</td>
							</tr>
							
							<tr>
								<td>Estatura: </td>
								<td>'. $row['estatura'] .'</td>
							</tr>
							<tr>
								<td>Peso: </td>
								<td>'. $row['peso'] .'</td>
							</tr>
							
						</table> 
					</div>
					<div class="col">
						<table> 			
							<tr>
								<th colspan="2"><br><br><img src="../../../imagenes/Usuarios/' . $row['foto'] . '" width="350px"/></th>
							</tr>	
							<tr>
								<th colspan="2"><br><br><a href="paciente.php">Regresar</a></th>
							</tr>
						</table>
					</div>
				</div>
				</div>
		</div>';
		
		return $html;
	}	
	
}


	public function delete_paciente($id){
		
		$sql = "DELETE FROM pacientes WHERE IdPaciente=$id;";
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
				<th><a href="paciente.php">Regresar</a></th>
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
				<th><a href="paciente.php">Regresar</a></th>
			</tr>
		</table>';
		return $html;
	}
//************************************************************************************************************************************************


}
?>


<?php
class medico{
	private $id;
	private $nombre;
	private $especialidad;
	private $telefono;
	private $email;
	private $con;//conexion
	
	function __construct($cn){
		$this->con = $cn;
		echo "<br><br> <center>EJECUTANDOSE EL CONSTRUCTOR MEDICO<center><br><br>";
	}

	
	public function update_medico(){
		$this->id = $_POST['id'];
		$this->nombre = $_POST['nombre'];
		$this->especialidad = $_POST['especialidadCMB'];
		
		
		//if ($this->_validar_genero_especialidad($this->paciente, $this->medico)) {
			$sql = "UPDATE medicos SET Nombre='$this->nombre',
					Especialidad=$this->especialidad WHERE Idmedico=$this->id;";
			echo $sql;
			if($this->con->query($sql)){
				echo $this->_message_ok(" modificó ");
			}else{
				echo $this->_message_error(" al modificar ");
			}								
	}


	public function save_medico(){
		
		$this->nombre = $_POST['nombre'];
		$this->especialidad = $_POST['especialidadCMB'];
		$this->telefono = $_POST['telefonoCMB'];
		$this->email = $_POST['emailCMB'];

			$sql = "INSERT INTO medicos VALUES(NULL,
												'$this->nombre',
												$this->especialidad,
												$this->telefono,
												$this->email);";
			echo $sql;
			//exit;
			if($this->con->query($sql)){
				echo $this->_message_ok(" guardó ");
			}else{
				echo $this->_message_error(" guardar, no cumple con la condición ");
			}
									
										
	}

	private function _get_name_file($nombre_original) {
		return $nombre_original;
	}
	

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
			$this->especialidad = NULL;
			$this->telefono = NULL;
			$this->exif_thumbnail  = NULL;
			$flag = NULL;
			$op = "new";

	}else{

		$sql = "SELECT  m.IdMedico as id, m.nombre, e.Descripcion AS especialidad, u.Nombre
				FROM Medicos m, especialidades e
				WHERE m.Especialidad = e.IdEsp AND m.IdMedico=$id;";
		$res = $this->con->query($sql);
		$row = $res->fetch_assoc();
		
		$num = $res->num_rows;
		if($num==0){
			$mensaje = "tratar de actualizar el medico con id= ".$id;
			echo $this->_message_error($mensaje);
		}else{             
                
				// ***** TUPLA ENCONTRADA *****
				echo "<br>REGISTRO A MODIFICAR: <br>";
					echo "<pre>";
						print_r($row);
					echo "</pre>";
			
		
             // ATRIBUTOS DE LA CLASE CONSULTA   
                $this->nombre = $row['nombre'];
                $this->especialidad = $row['especialidad'];
				$this->telefono = $row['telefono'];
				$this->email = $row['email'];
				$flag = "enable";
                $op = "update"; 
            }
	}
    
		$html = '
		<a href="medico.php">Regresar a Lista de Medicos</a>
		<br>
		<div class="card w-50">
		<div class="card-header text-white bg-info mb-3">DATOS DE MEDICOS</div>
		<form name="Form_Medico" method="POST" action="medico.php" enctype="multipart/form-data">
		<!---Lineas Agregadas-->
		<input type="hidden" name="id" value="' . $id  . '">
		<input type="hidden" name="op" value="' . $op  . '">
			<table border="0" align="center" class="table">
				<tr>
					<td>Nombre:</td>
					<td><input class="form-control" type="text" size="15" name="nombre" value="' . $this->nombre . '"></td>
				</tr>
				
				<tr>
				<td>Especialidad Médico:</td>
					<td>' . $this->_get_combo_db("especialidades","IdEsp","Descripcion","especialidadCMB",$this->especialidad) . '</td>
				</tr>
				<td>Usuario:</td>
					<td>' . $this->_get_combo_db("usuarios","IdUsuario","Nombre","usuarioCMB",$this->usuario) . '</td>
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
				<th colspan="8">Lista de Medicos</th>
			</tr>
			<tr>
				<th colspan="8"><a href="medico.php?d=' . $d_new_final . '">Nuevo</a></th>
			</tr>
			<tbody class="table-group-divider">
			<tr>
				<th>Nombre</th>
				<th>Especialidad</th>
				<th colspan="3">Acciones</th>
			</tr>
			<tbody class="table-group-divider">';
		$sql = "SELECT m.IdMedico as id, m.nombre, e.Descripcion AS especialidad, u.Nombre AS usuario
		FROM Medicos m, especialidades e, usuarios u
		WHERE m.Especialidad = e.IdEsp AND u.idUsuario = m.idUsuario
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
						<td>' . $row['nombre'] . '</td>
						<td>' . $row['especialidad'] . '</td>
						
						<td><a class="btn btn-danger" href="medico.php?d=' . $d_del_final . '">Borrar</a></td>
						<td><a class="btn btn-warning" href="medico.php?d=' . $d_act_final . '">Actualizar</a></td>
						<td><a class="btn btn-info" href="medico.php?d=' . $d_det_final . '">Detalle</a></td>
						
					</tr>';
			
		    }
		}else{
			$mensaje = "Tabla Medicos" . "<br>";
            echo $this->_message_BD_Vacia($mensaje);
			echo "<br><br><br>";
		}
		$html .= '</table>';
		return $html;
		
	}
	
	

	
	
//****************************************** NUEVO CODIGO *****************************************

public function get_detail_medico($id){
	$sql = "SELECT m.IdMedico as id, m.nombre AS nombre, e.Descripcion AS especialidad, u.Nombre AS usuario,
	u.foto as foto
	FROM Medicos m, especialidades e, usuarios u
	WHERE m.Especialidad = e.IdEsp AND u.idUsuario = m.idUsuario AND m.IdMedico = $id";

		
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
								<th colspan="2">DATOS DE Medico</th>
							</tr>
							<tr>
								<td>Id: </td>
								<td>'. $row['id'] .'</td>
							</tr>
							<tr>
								<td>Nombre: </td>
								<td>'. $row['nombre'] .'</td>
							</tr>
							<tr>
								<td>Especialidad: </td>
								<td>'. $row['especialidad'] .'</td>
							</tr>
							<tr>
								<td>Usuario: </td>
								<td>'. $row['usuario'] .'</td>
							</tr>
						</table> 
					</div>
					<div class="col">
						<table> 			
							<tr>
								<th colspan="2"><br><br><img src="../../imagenes/Usuarios/' . $row['foto'] . '" width="350px"/></th>
							</tr>	
							<tr>
								<th colspan="2"><br><br><a href="medico.php">Regresar</a></th>
							</tr>
						</table>
					</div>
				</div>
				</div>
		</div>';
		
		return $html;
	}	
	
}


	public function delete_medico($id){
		
		$sql = "DELETE FROM medicos WHERE IdMedico=$id;";
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
				<th><a href="medico.php">Regresar</a></th>
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
				<th><a href="medico.php">Regresar</a></th>
			</tr>
		</table>';
		return $html;
	}
//************************************************************************************************************************************************


}
?>


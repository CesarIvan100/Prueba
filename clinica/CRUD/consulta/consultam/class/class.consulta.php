<?php
class consulta{
	private $id;
	private $medico;
	private $paciente;
	private $fecha;
	private $diagnostico;
	private $con;//conexion
	
	function __construct($cn){
		$this->con = $cn;
	}

	
	public function update_consulta(){
		$this->id = $_POST['id'];
		$this->medico = $_POST['medicoCMB'];
		$this->paciente = $_POST['pacienteCMB'];
		$this->fecha = $_POST['fecha'];
		$this->diagnostico = $_POST['diagnostico'];
			

			$sql = "UPDATE consultas SET IdMedico=$this->medico,
										IdPaciente=$this->paciente,
										FechaConsulta='$this->fecha',
										Diagnostico='$this->diagnostico'
										";

		
			$sql .= " WHERE IdConsulta=$this->id;";
			echo $sql;
			if($this->con->query($sql)){
				echo $this->_message_ok(" modificó ");
			}else{
				echo $this->_message_error(" al modificar ");
			}								


	public function save_consulta(){
		
		$this->medico = $_POST['medicoCMB'];
		$this->paciente = $_POST['pacienteCMB'];
		$this->fecha = $_POST['fecha'];
		$this->diagnostico = $_POST['diagnostico'];
		
		
		//exit;
		//las imagenes se guardan en el temp, esta funcion la saca de alli y la coloca en la caprtea que deseo
		
		
			$sql = "INSERT INTO consultas VALUES(NULL,
												$this->medico,
												$this->paciente,
												'$this->fecha',
												'$this->diagnostico');";
			//echo $sql;
			//exit;
			if($this->con->query($sql)){
				echo $this->_message_ok(" guardó");
			}else{
				echo $this->_message_error(" guardar");
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
			$html .= ($defecto == $row[$valor])?'<option value="' . $row[$valor] . '" selected>' . $row[$etiqueta] . '</option>' . "\n" : '<option value="' . $row[$valor] . '">' . $row[$etiqueta] . '</option>' . "\n";
		}
		$html .= '</select>';
		return $html;
	}


public function get_form($id=NULL){
		
	if($id == NULL){
			$this->medico = NULL;
			$this->paciente = NULL;
			$this->fecha = NULL;
			$this->diagnostico = NULL;
			
			$flag = NULL;
			$op = "new";

	}else{

		$sql = "SELECT * FROM consultas WHERE IdConsulta=$id;";
		$res = $this->con->query($sql);
		$row = $res->fetch_assoc();
		
		$num = $res->num_rows;
		if($num==0){
			$mensaje = "tratar de actualizar la consulta con id= ".$id;
			echo $this->_message_error($mensaje);
		}else{             
                
				// ***** TUPLA ENCONTRADA *****
				echo "<br>REGISTRO A MODIFICAR: <br>";
					echo "<pre>";
						print_r($row);
					echo "</pre>";
			
		
             // ATRIBUTOS DE LA CLASE profesor   
                $this->medico = $row['IdMedico'];
                $this->paciente = $row['IdPaciente'];
                $this->fecha = $row['fechaConsulta'];
				$this->diagnostico = $row['diagnostico'];

				
                //$flag = "disabled";
				$flag = "enable";
                $op = "update"; 
            }
	}
    
		$html = '
		<a href="consulta.php">Regresar a Lista de consultas</a>
		<br>
		<div class="card w-50">
		<div class="card-header text-white bg-info mb-3">DATOS DE LA CONSULTA</div>
		<form name="Form_vehiculo" method="POST" action="consulta.php" enctype="multipart/form-data">
		<!---Lineas Agregadas-->
		<input type="hidden" name="id" value="' . $id  . '">
		<input type="hidden" name="op" value="' . $op  . '">
			<table border="0" align="center" class="table">
				<tr>
					<td>Nombre del Paciente:</td>
					<td> ' . $this->_get_combo_db("pacientes","idPaciente","Nombre","pacienteCMB",$this->paciente) . '</td>
				</tr>
				
				<tr>
				<td>Nombre del Medico:</td>
					<td>' . $this->_get_combo_db("medicos","IdMedico","Nombre","medicoCMB",$this->medico) . '</td>
				</tr>
				<tr>
					<td>Fecha de Consulta:</td>
					<td><input class="form-control" type="date" size="15" name="fecha" value="' . $this->fecha . '"></td>
				</tr>
				<tr>
					<td>Diagnostico:</td>
					<td><input class="form-control" type="text" size="15" name="diagnostico" value="' . $this->diagnostico . '"></td>
				</tr>
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
				<th colspan="8">Lista de Consultas</th>
			</tr>
			<tr>
				<th colspan="8"><a href="consulta.php?d=' . $d_new_final . '">Nuevo</a></th>
			</tr>
			<tbody class="table-group-divider">
			<tr>
				<th>ID</th>
				<th>Paciente</th>
				<th>Medico</th>
				<th>Fecha</th>
				<th colspan="3">Acciones</th>
			</tr>
			<tbody class="table-group-divider">';
		$sql = "SELECT c.IdConsulta AS id, m.Nombre AS medico, p.Nombre AS paciente, c.FechaConsulta AS fecha
				FROM consultas c, medicos m, pacientes p
				WHERE c.IdMedico = m.IdMedico AND p.IdPaciente = c.IdPaciente
				ORDER BY id;";	
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
						<td>' . $row['paciente'] . '</td>
						<td>' . $row['medico'] . '</td>
						<td>' . $row['fecha'] . '</td>
						<td><a class="btn btn-warning" href="consulta.php?d=' . $d_act_final . '">Actualizar</a></td>
						<td><a class="btn btn-info" href="consulta.php?d=' . $d_det_final . '">Detalle</a></td>
						
					</tr>';
			
		    }
		}else{
			$mensaje = "Tabla Consulta" . "<br>";
            echo $this->_message_BD_Vacia($mensaje);
			echo "<br><br><br>";
		}
		$html .= '</table>';
		return $html;
		
	}
	
	

	
	
//****************************************** NUEVO CODIGO *****************************************

public function get_detail_consulta($id){
	$sql = "SELECT c.IdConsulta AS id, m.Nombre AS medico, p.Nombre AS paciente, p.cedula AS cedula,
			p.edad AS edad, c.FechaConsulta AS fecha,
			c.HI AS hinicio, c.HF AS hfinal, c.Diagnostico AS diagnostico
			FROM consultas c, medicos m, pacientes p
			WHERE c.IdMedico = m.IdMedico AND p.IdPaciente = c.IdPaciente AND c.IdConsulta = $id;";

		
		$res = $this->con->query($sql);
		$row = $res->fetch_assoc();
		// VERIFICA SI EXISTE id
		$num = $res->num_rows;
        
	if($num == 0){
        $mensaje = "desplegar el detalle de consulta con id= ". $id . "<br>";
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
								<th colspan="2">DATOS DE LA CONSULTA</th>
							</tr>
							<tr>
								<td>ID: </td>
								<td>'. $row['id'] .'</td>
							</tr>
							<tr>
								<td>Paciente: </td>
								<td>'. $row['paciente'] .'</td>
							</tr>
							<tr>
								<td>Cedula: </td>
								<td>'. $row['cedula'] .'</td>
							</tr>
							<tr>
								<td>Edad:  </td>
								<td>'. $row['edad'] .'</td>
							</tr>
							<tr>
								<td>Fecha de Consulta: </td>
								<td>'. $row['fecha'] .'</td>
							</tr>
							<tr>
								<td>Medico: </td>
								<td>'. $row['medico'] .'</td>
							</tr>
							<tr>
								<td>Hora: </td>
								<td>'. $row['hinicio'] .'</td>
							</tr>
							<tr>
								<td>Diagnostico: </td>
								<td>'. $row['diagnostico'] .'</td>
							</tr>
							<tr>
								<th colspan="2"><br><br><a href="consulta.php">Regresar</a></th>
							</tr>
							
						</table> 
					</div>
				</div>
				</div>
		</div>';
		
		return $html;
	}	
	
}


	public function delete_consulta($id){
		
		$sql = "DELETE FROM consultas WHERE IdConsulta=$id;";
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
				<th><a href="consulta.php">Regresar</a></th>
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
				<th><a href="consulta.php">Regresar</a></th>
			</tr>
		</table>';
		return $html;
	}
//************************************************************************************************************************************************


}
?>


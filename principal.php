<?php
require_once('./dll/config.php');
require_once('./dll/class_mysqli.php');
//coneccion a la base de datos
$miconexion = new clase_mysqli;
$miconexion->conectar(DBHOST, DBUSER, DBPASS, DBNAME);

//transformacion al archivo de presentacion en formato json
header('content-type:application/json');

//actionName es en nombre de la caja de texto y selecpost la variable que indica que va a buscar algo
$actionName = $_POST["actionName"];

if($actionName == "selectPost"){
	$seachKey = isset($_POST["seachKey"]) ? $_POST["seachKey"] : '';

	if(!empty($seachKey)){
		$miconexion->consulta("SELECT * FROM inscritos where apellidos like '%$seachKey%' ORDER BY apellidos DESC");
	}else{
		$miconexion->consulta("SELECT * FROM inscritos ORDER BY id DESC");
	}
	$miconexion->consulta_busqueda_json();
}

if($actionName == "insertPost"){
	$nombres = isset($_POST["nombres"]) ? $_POST["nombres"] : '';
	$apellidos = isset($_POST["apellidos"]) ? $_POST["apellidos"] : '';
	$correo = isset($_POST["correo"]) ? $_POST["correo"] : '';
	$dni = isset($_POST["dni"]) ? $_POST["dni"] : '';
	$pais = isset($_POST["pais"]) ? $_POST["pais"] : '';
	$sexo = isset($_POST["sexo"]) ? $_POST["sexo"] : '';
	
	if(!empty($nombres) && !empty($apellidos) && !empty($dni)){
		$query = "";

		$status=$miconexion->consulta("INSERT INTO inscritos(nombres, apellidos, correo, dni, pais, sexo) 
		VALUES('$nombres', '$apellidos','$correo','$dni', '$pais', '$sexo')");
		if($status){
			$resultData = array('status' => true, 'message' => 'New Register Inserted Successfully...');

			$urlPortion= '&resultado='.urlencode(serialize($resultData));
  			header("Location: result.php?".$urlPortion);

	    }else{
	    	$resultData = array('status' => false, 'message' => 'Can\'t able to insert new register...');
	    }
	}
	else{
    	$resultData = array('status' => false, 'message' => 'Please enter register details...');
    }
    echo json_encode($resultData);
}

if($actionName == "updatePost"){
	$id = isset($_POST["id"]) ? $_POST["id"] : '';
	$nombres = isset($_POST["nombres"]) ? $_POST["nombres"] : '';
	$apellidos = isset($_POST["apellidos"]) ? $_POST["apellidos"] : '';
	$correo = isset($_POST["correo"]) ? $_POST["correo"] : '';
	$dni = isset($_POST["dni"]) ? $_POST["dni"] : '';
	$pais = isset($_POST["pais"]) ? $_POST["pais"] : '';
	$sexo = isset($_POST["sexo"]) ? $_POST["sexo"] : '';
	
		$query = "";

		$status=$miconexion->consulta("UPDATE inscritos SET nombres = '$nombres', apellidos = '$apellidos', correo = '$correo', dni = '$dni', pais = '$pais', sexo = '$sexo' WHERE id = '$id'");
		if($status){
			$resultData = array('status' => true, 'message' => 'Register Updated Successfully...');

			$urlPortion= '&resultado='.urlencode(serialize($resultData));
  			header("Location: result.php?".$urlPortion);

	    }else{
	    	$resultData = array('status' => false, 'message' => 'Can\'t able to update register...');
	    }

    echo json_encode($resultData);
}


if($actionName == "deletePost"){
	$id = isset($_POST["id"]) ? $_POST["id"] : '';

		$status=$miconexion->consulta("DELETE FROM inscritos WHERE id = $id"); 
		if($status){
			$resultData = array('status' => true, 'message' => 'Register Deleted Successfully...');

			$urlPortion= '&resultado='.urlencode(serialize($resultData));
  			header("Location: result.php?".$urlPortion);

	    }else{
	    	$resultData = array('status' => false, 'message' => 'Can\'t able to delete register...');
	    }
	echo json_encode($resultData);
}

?>
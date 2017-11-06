<?php
########################################################################
#				  TSI - EMPLOYMENT PORTAL. Version 2			       #
#																	   #
#	Url: http://www.empleo.tsiberia.es					               #
#	Author: Vicente Catala											   #
#	Date: 16/06/2017 							                       #
#																	   #
########################################################################

// Notificar todos los errores excepto los NOTICE
error_reporting(E_ALL ^ E_NOTICE);

/** Clases necesarias para trabajar con la biblioteca PHPExcel */
require_once('./Classes/PHPExcel.php');
require_once('./Classes/PHPExcel/Reader/Excel2007.php');

// Reseteamos la variable sobre informacion del proceso al usuario
$errores= '';
$enviado ='';
// Comprobamos si se ha enviado el formulario, comentarios no funciona.
if (isset($_POST["boton_enviar"])){
//if (isset($_REQUEST['boton_enviar'])) {
//if($_POST){

	// Comprobar si el Captcha es correcto
	session_start();
	if(isset($_POST["captcha"]) && $_POST["captcha"]!="" && $_SESSION["code"]==$_POST["captcha"])
	{
		//Guardamos en variables la info de los datos adjuntos
		//Info archivo CV
		$nombre_archivo = $_FILES['CV']['name'];
		$trozos = explode(".", $nombre_archivo);
		$tipo_archivo = end($trozos);
		$tamano_archivo = $_FILES['CV']['size'];

		//Info archivo Foto/Imagen	
		$nombre_archivo2 = $_FILES['Foto']['name'];
		$trozos = explode(".", $nombre_archivo2);
		$tipo_archivo2 = end($trozos);
		$tamano_archivo2 = $_FILES['Foto']['size'];
			
		//Preparo varibles para asignar nombres a los archivos de CV y Fotos/Imagenes
		$var = 1;
		$nombre = $_POST['Nombre'];
		//Elimino posibles espacios existentes a inicio y final de la cadena de caracteres.
		$nombre = trim($nombre);
		//Elimino los espacios sobrantes
		$nombre = preg_replace('/\s+/', ' ', $nombre);
		$nombre2 = $nombre;				
		//Sustituyo el espacio entre palabras por '_' para la url final.
		$nombre = str_replace(" ", "_", $nombre,$var);

		
		$apellidos = $_POST['Apellidos'];
		//Elimino posibles espacios existentes a inicio y final de la cadena de caracteres.
		$apellidos = trim($apellidos);
		//Elimino los espacios sobrantes
		$apellidos = preg_replace('/\s+/', ' ', $apellidos);
		$apellidos2 = $apellidos;
		//Sustituyo el espacio entre apellidos por '_' para la url final.
		$apellidos = str_replace(" ", "_", $apellidos);

		//Guardo ruta donde guardar CVs
		$path = "./cv/";
		//Guardo ruta donde guardar Fotos/Imagenes 	
		$path2 = "./fotos/";
		//Separador entre variable nombre y apellidos, para que no existan espacios
		$barra = "_";
		$punto = ".";
		
		//Compruebo si cumple condiciones el fichero adjunto Foto/imagen
		if (!(($tipo_archivo2 == "png" || $tipo_archivo2 == "jpeg" || $tipo_archivo2 == "jpg") && ($tamano_archivo2 < 500000))){
			$errores = 'nok_file'; // Error
		}else{
			//Asigno extension foto/imagen
			$extension_foto = $tipo_archivo2;
		
			//Variable que almacena la ruta, nombre y extension de la foto/imagen
			$ruta2 = "$path2" . "$nombre" . "$barra" . "$apellidos" . "$punto" .  "$extension_foto";	
			//Subimos el archivo Foto/Imagen al servidor
			if (move_uploaded_file($_FILES['Foto']['tmp_name'],$ruta2)){
				
				//Compruebo si cumple condiciones el fichero adjunto CV
				if (!(($tipo_archivo == "pdf") && ($tamano_archivo < 500000))){
					$errores = 'nok_file'; // Error
				}else{
					//Asigno extension CV
					$extension_cv = $tipo_archivo;
					
					//Variable que almacena la ruta, nombre y extension del CV
					$ruta = "$path" . "$nombre" . "$barra" . "$apellidos" . "$punto" . "$extension_cv";
					//Subimos el archivo CV al servidor
					if (move_uploaded_file($_FILES['CV']['tmp_name'],$ruta)){

						// Cargando el archivo excel
						$objReader = new PHPExcel_Reader_Excel2007();
						$nombre_archivo="./bbdd/cv.xlsx"; 
						$objPHPExcel = $objReader->load("$nombre_archivo");
						
						// Asignar hoja de calculo activa
						$objPHPExcel->setActiveSheetIndex(0);	
						
						/* Inicializamos las variables columna y fila */
						$col = 0;
						$row = 0;
						//Uso del bucle for
						$i = 0;
	
						// Obtenemos el numero maximo de filas y columnas
						$highestRow = $objPHPExcel->getActiveSheet()->getHighestRow();
						//$highestColumn = $objPHPExcel->getActiveSheet()->getHighestColumn();
						
						// Obtener y guardar en array todos los DNI de archivo excel
						for ($row = 2; $row <= $highestRow; $row++) {
							
							$array_dni[$i] = $objPHPExcel->getActiveSheet()->getCell('D'.$row)->getValue();
							$i++;
						}
						// Comprobar si el DNI introducido ya está registrado en el archivo Excel
						foreach ($array_dni as $valor) {
							// User ya registrado
							if ($valor == $_POST['DNI']){
								$errores = 'nok_dni';
							}
						}
						//User no registrado
						if (!($errores == 'nok_dni')){

							$Cad_DNI = $_POST['Cad_DNI'];
							//Arreglamos formato fecha en caso de que el año aparezca al principio(formato date default html5 y bbdd).
							$buffer = substr($Cad_DNI,2,1);
							if ($buffer != '-'){
								$ano = substr($Cad_DNI,0,4);
								$mes = substr($Cad_DNI,5,2);
								$dia = substr($Cad_DNI,8,2);
								$Cad_DNI = $dia . "-". $mes . "-". $ano;
							}
							
							$F_Nacimiento = $_POST['F_Nacimiento'];
							//Arreglamos formato fecha en caso de que el año aparezca al principio(formato date default html5 y bbdd).
							$buffer = substr($F_Nacimiento,2,1);
							if ($buffer != '-'){
								$ano = substr($F_Nacimiento,0,4);
								$mes = substr($F_Nacimiento,5,2);
								$dia = substr($F_Nacimiento,8,2);
								$F_Nacimiento = $dia . "-". $mes . "-". $ano;
							}

							if ($_POST['Cad_B1'] != ""){
								$Cad_B1 = $_POST['Cad_B1'];
								//Arreglamos formato fecha en caso de que el año aparezca al principio(formato date default html5 y bbdd).
								$buffer = substr($Cad_B1,2,1);
								if ($buffer != '-'){
									$ano = substr($Cad_B1,0,4);
									$mes = substr($Cad_B1,5,2);
									$dia = substr($Cad_B1,8,2);
									$Cad_B1 = $dia . "-". $mes . "-". $ano;
								}
							}else{
								$Cad_B1 = "";
							}

							//Elimino posibles espacios existentes a inicio y final de las cadenas de caracteres.
							$Direccion = trim($_POST['Direccion']);
							$Poblacion = trim($_POST['Poblacion']);


							// Asignar celdas para guardar datos
							//Posición de fila donde introduciremos los nuevos datos
							$highestRow++;
							$f_entrada = "A".$highestRow;
							$name = "B".$highestRow;
							$surname = "C".$highestRow;
							$dni = "D".$highestRow;
							$cad_dni = "E".$highestRow;
							$f_nacimiento = "F".$highestRow;
							$direccion = "G".$highestRow;
							$poblacion = "H".$highestRow;
							$cp = "I".$highestRow;
							$email = "J".$highestRow;
							$tlf = "K".$highestRow;
							$b1 = "L".$highestRow;
							$cad_b1 = "M".$highestRow;
							$lopd = "N".$highestRow;
							$foto_ = "O".$highestRow;
							$cv_ = "P".$highestRow;
							
							//Asignar fecha de entrada registro
							$fecha = date("d") . "-" . date("m") . "-" . date("Y");	
							
							$foto = "http://www.empleo.tsiberia.es/fotos/" . "$nombre" . "$barra" . "$apellidos" . "$punto" . "$extension_foto";
							$CV = "http://www.empleo.tsiberia.es/cv/" . "$nombre" . "$barra" . "$apellidos" . "$punto" . "$extension_cv";	
							
							// Asignar data
							$objPHPExcel->getActiveSheet()->setCellValue($f_entrada, $fecha);
							$objPHPExcel->getActiveSheet()->setCellValue($name, $nombre2);
							$objPHPExcel->getActiveSheet()->setCellValue($surname, $apellidos2);
							$objPHPExcel->getActiveSheet()->setCellValue($dni, $_POST['DNI']);
							$objPHPExcel->getActiveSheet()->setCellValue($cad_dni, $Cad_DNI);
							$objPHPExcel->getActiveSheet()->setCellValue($f_nacimiento, $F_Nacimiento);
							$objPHPExcel->getActiveSheet()->setCellValue($direccion, $Direccion);
							$objPHPExcel->getActiveSheet()->setCellValue($poblacion, $Poblacion);
							$objPHPExcel->getActiveSheet()->setCellValue($cp, $_POST['CP']);
							$objPHPExcel->getActiveSheet()->setCellValue($email, $_POST['EMAIL']);
							$objPHPExcel->getActiveSheet()->setCellValue($tlf, $_POST['TLF']);
							$objPHPExcel->getActiveSheet()->setCellValue($b1, $_POST['Carnet_B1']);
							$objPHPExcel->getActiveSheet()->setCellValue($cad_b1, $Cad_B1);
							$objPHPExcel->getActiveSheet()->setCellValue($lopd, $_POST['LOPD']);
							$objPHPExcel->getActiveSheet()->setCellValue($foto_, $foto);
							$objPHPExcel->getActiveSheet()->setCellValue($cv_, $CV);							
								
							//Guardamos el archivo en formato Excel 2007
							//Si queremos trabajar con Excel 2003, basta cambiar el 'Excel2007' por 'Excel5' y el nombre del archivo de salida cambiar su formato por '.xls'
							$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
							$objWriter->save("$nombre_archivo");	

							//Contador personal registrado en Excel
							$archivo = "./files/contador.txt";
							$contador = 0;
							//Leemos archivo contador.txt para saber conteo actual.
							$fp = fopen($archivo,"r");
							$contador = fgets($fp, 26);
							fclose($fp);

							++$contador;

							$fp = fopen($archivo,"w+");
							fwrite($fp, $contador, 26);
							fclose($fp);

							//Si el contador es multiplo de 10 se envia un email informativo a RRHH.
							if(fmod($contador, 10) == 0){
								// Enviar el email				
								$mail = "robot@tsiberia.es";

								$header = 'From: ' . $mail . " \r\n";
								$header .= "X-Mailer: PHP/" . phpversion() . " \r\n";
								$header .= "Mime-Version: 1.0 \r\n";
								//$header .= "Content-Type: text/plain";
								$header .= "Content-Type: text/html; charset=utf-8";

								$mensaje = '<html>' . '<head><title>Email</title><style type="text/css"> h2 { color: black; font-family: Impact,Haettenschweiler,Franklin Gothic Bold,Charcoal,Helvetica Inserat,Bitstream Vera Sans Bold,Arial Black,sans serif; }</style></head>' . '<body><h2><b>TSI Empleo Website</b></h2><br />' . '<b>Diez nuevos registros en nuestro sistema.</b>'. '<br /><hr>'. 'Por favor, no responda a este correo lo envia un robot autom&aacute;ticamente.'. '<br />Enviado el ' . date('d/m/Y', time()) . '</body></html>';

								$para = 'rrhh@tsiberia.es';
								$copia= 'vicente.catala.g@ts-iberica.com';
								$asunto = 'Diez nuevos registros en EMPLEO.TSIBERIA.ES';

								mail("$para,$copia", $asunto, utf8_decode($mensaje), $header);
							}

							$enviado = 'true';
						}
					//Error al subir el CV
					}else{
						$errores = 'nok_file'; 
					}
				}
			//Error al subir la foto/imagen
			}else{
				$errores = 'nok_file';
			}
		}
	}
	else
	{
	$errores = 'nok_captcha';
	//die("Código Captcha erroneo");
	}
}
require_once('./index.view.php');
?>
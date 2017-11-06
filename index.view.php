<?php
	//Reconocimiento idioma
	require('./languages/languages.php');
	$lang = "es";
	if ( isset($_GET['lang']) ){
		$lang = $_GET['lang'];
	}
//HTML	
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html" charset="utf-8"> 
	<title><?php echo __('Portal Empleo', $lang) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css" type="text/css" media="all">
	<link rel="shortcut icon" href="./files/favicon.ico" type="image/x-icon">
	<link rel="icon" href="./files/favicon.ico" type="image/x-icon">
	<script src="/pace/pace.js"></script>
    <link href="/pace/themes/pace-theme-center-radar.css" rel="stylesheet">

    <script>
        function habilitar(value)
        {
            if(value=="SI"){
                document.getElementById("Cad_B1").disabled=false;
            }else if(value =="NO"){
                 document.getElementById("Cad_B1").disabled=true;
            }
        }
    </script>

</head>
<body>
<header>
<span class="izquierda">
	<a  href= "http://www.tsiberia.es"><img src="./files/logo.png" alt="logo TSI" title="Logo TSI" width="100" height="75" /></a>
</span>

<span class="derecha">
	<a href= "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?lang=es"><img src="./languages/Spain-flag.png" alt="Spanish" title="Spanish" width="30" height="30"/></a>&nbsp;
	<a href= "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?lang=en"><img src="./languages/United-kingdom-flag.png" alt="English" title="English" width="30" height="30"/></a>
	<!-- <a href= "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?lang=de"><img src="./languages/Germany-flag.png" alt="German" title="German"width="30" height="30" /></a> -->
</span>

<br /><br /><br /><br />
<h2><?php echo __('Solicitud de empleo', $lang) ?></h2>
</header>

<?php
//Respuesta formulario
if($enviado){
	?>
	<div class="alert success"><center>
	<?php echo __('Formulario enviado correctamente', $lang) ?>
    </center></div>
<?php
}else if($errores == 'nok'){
	?>
    <div class="alert error"><center>
	<?php echo __('Error al enviar el formulario, por favor intentelo de nuevo', $lang) ?>
    </center></div>
<?php
}else if($errores == 'nok_file'){
	?>
    <div class="alert error"><center>
	<?php echo __('La extensión o el tañamo de algún archivo no es correcto, por favor intentelo de nuevo', $lang) ?>
    </center></div>
<?php
}else if($errores == 'nok_captcha'){
	?>
    <div class="alert error"><center>
	<?php echo __('Error en la Verificación Anti-Robots, por favor intentelo de nuevo.', $lang) ?>
    </center></div>
<?php
}else if($errores == 'nok_dni'){
	?>
    <div class="alert error"><center>
	<?php echo __('Sus datos ya se encuentran en nuestros registros. Para cualquier cambio contacte con el departamento de RRRHH, rrhh@tsiberia.es.', $lang) ?>
    </center></div>
<?php
}
//COMIENZO FORMULARIO
?>
<br /><b>&nbsp;&nbsp;&nbsp;<?php echo __('(*) Campos obligatorios', $lang) ?></b><br />&nbsp;&nbsp;&nbsp;<?php echo __('Por favor, todo en MAYÚSCULAS', $lang) ?><br /><br />
<div class="two-columns">
    <form class="contact_form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?lang=<?php echo $lang; ?>" method="post" enctype="multipart/form-data" >
        <ul>
            <li>
               	 <label for="Nombre"><?php echo __('Nombre (*)', $lang) ?></label>
               	 <input type="text" name="Nombre" placeholder="<?php echo __('NOMBRE', $lang) ?>" title="Max. 30 caracteres y en may&uacute;sculas, los simbolos no est&aacute;n permitidos." pattern="[A-ZÑÁÉÍÓÚÜÀ' ]{1,30}" required value="<?php if(!$enviado && isset($_POST['Nombre'])) echo $_POST['Nombre'] ?>" />
            </li>
            <li>
               	 <label for="Apellidos"><?php echo __('Apellidos (*)', $lang) ?></label>
               	 <input type="text" name="Apellidos" placeholder="<?php echo __('APELLIDO1 APELLIDO2', $lang) ?>" title="Max. 30 caracteres y en may&uacute;sculas, los simbolos no est&aacute;n permitidos." pattern="[A-ZÑÁÉÍÓÚÜÀ' ]{1,30}" required value="<?php if(!$enviado && isset($_POST['Apellidos'])) echo $_POST['Apellidos'] ?>" />
            </li>
             <li>
                 <label for="DNI"><?php echo __('DNI/NIE (*)', $lang) ?></label>
                 <input type="text" name="DNI" placeholder="123456789A" title="Debe contener 9 caracteres (sin guiones y letras may&uacute;sculas)" pattern="(([X-Z]{1})(\d{7})([A-Z]{1}))|((\d{8})([A-Z]{1}))" required value="<?php if(!$enviado && isset($_POST['DNI'])) echo $_POST['DNI'] ?>" />
            </li>
             <li>
           <label for="Cad_DNI"><?php echo __('Caducidad DNI/NIE (*)', $lang) ?></label>
                 <input type="date" name="Cad_DNI" placeholder="16-05-2020" title="Debe seguir el siguente formato (00-00-0000)" pattern="[0-9]{2}-[0-9]{2}-[1-2]{1}[0-9]{3}" required value="<?php if(!$enviado && isset($_POST['Cad_DNI'])) echo $_POST['Cad_DNI'] ?>"/>
            </li>                       
            <li>
                 <label for="F_Nacimiento"><?php echo __('Fecha nacimiento (*)', $lang) ?></label>
                 <input type="date" name="F_Nacimiento"  placeholder="27-07-1982" title="Debe seguir el siguente formato (00-00-0000)" pattern="[0-9]{2}-[0-9]{2}-[1-2]{1}[0-9]{3}" required value="<?php if(!$enviado && isset($_POST['F_Nacimiento'])) echo $_POST['F_Nacimiento'] ?>" />
            </li>
            <li>
                 <label for="Direccion"><?php echo __('Dirección (*)', $lang) ?></label>
                 <input type="text" name="Direccion" placeholder="<?php echo __('BLASCO IBAÑEZ 10', $lang) ?>" title="Letras en may&uacute;sculas, simbolos permitidos ( / . , - º ª)" pattern="[A-ZÑÁÉÍÓÚÜÀ0-9 /.,-ºª]{1,30}" required value="<?php if(!$enviado && isset($_POST['Direccion'])) echo $_POST['Direccion'] ?>" />
            </li>
            <li>
                 <label for="Poblacion"><?php echo __('Población (*)', $lang) ?></label>
                 <input type="text" name="Poblacion" placeholder="<?php echo __('VALENCIA', $lang) ?>" title="Letras en may&uacute;sculas" pattern="[A-ZÑÁÉÍÓÚÜÀ ]{1,30}" required value="<?php if(!$enviado && isset($_POST['Poblacion'])) echo $_POST['Poblacion'] ?>"/>
            </li>
            <li>
                 <label for="CP"><?php echo __('CP (*)', $lang) ?></label>
                 <input type="text" name="CP" placeholder="46000" title="Debe contener 5 caracteres num&eacute;ricos" pattern="[0-9]{5}" required value="<?php if(!$enviado && isset($_POST['CP'])) echo $_POST['CP'] ?>" />
            </li>                   
            <li>
                 <label for="EMAIL"><?php echo __('Email', $lang) ?></label>
                 <input type="email" name="EMAIL" placeholder="NAME@DOMAIN.COM" title="Email con formato v&aacute;lido y en may&uacute;sculas" pattern="^[A-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[A-Z0-9-]+(?:\.[A-Z0-9-]+)*$" value="<?php if(!$enviado && isset($_POST['EMAIL'])) echo $_POST['EMAIL'] ?>" />
            </li>
            <li>
                 <label for="TLF"><?php echo __('Tlf Contacto (*)', $lang) ?></label>
                 <input type="tel" name="TLF" placeholder="612345678" title="Debe contener 9 caracteres num&eacute;ricos" pattern="[0-9]{9}" required value="<?php if(!$enviado && isset($_POST['TLF'])) echo $_POST['TLF'] ?>" />
            </li>             
            <li>
                 <label for="Carnet_B1"><?php echo __('Permiso de conducir B', $lang) ?></label>
           <input type="radio" name="Carnet_B1" value="SI" onchange="habilitar(this.value);" > SI&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
           <input type="radio" name="Carnet_B1" value="NO" onchange="habilitar(this.value);" checked> NO<br>
            </li>
            <li>
                 <label for="Cad_B1"><?php echo __('Caducidad permiso B', $lang) ?></label>
                 <input type="date" id="Cad_B1" name="Cad_B1" min="2017-07-01" placeholder="15-05-2022" title="Debe seguir el siguente formato (00-00-0000)" pattern="[0-9]{2}-[0-9]{2}-[1-2]{1}[0-9]{3}" disabled=true required />
            </li>
            <li>
                 <label for="Foto"><?php echo __('Foto (*)', $lang) ?></label>
                 <input type="file" name="Foto" accept="image/png,image/jpg" required /> <br /><i><font face="sans-serif" size=1><?php echo __('Solo archivos PNG / JPEG / JPG y Max. 0,5Mb', $lang) ?></font></i>
            </li>
            <li>
                 <label for="CV"><?php echo __('CV (*)', $lang) ?></label>
                 <input type="file" name="CV" required /><br /><i><font face="sans-serif" size=1><?php echo __('Solo archivos PDF y Max. 0,5Mb', $lang) ?></font></i>
            </li>        
</div>
        <br /><br /><center><?php echo __('Verificación Anti-Robots', $lang) ?><br /><b><?php echo __('(*) Introduce el numero de la imagen ', $lang) ?><input name="captcha" type="text" required > <img src="captcha.php" align="absmiddle"/></b>&nbsp;&nbsp;<br><br>
		<input type="checkbox" name="LOPD" value="SI" required /><?php echo __('(*) He leído y acepto está ', $lang) ?><a href="./files/POLITICA_PRIVACIDAD_TSI.pdf" target="_blank"><?php echo __('Política de Privacidad', $lang) ?></a>
        <br /><br /><br /><button name="boton_enviar" class="submit" type="submit"><?php echo __('Enviar', $lang) ?></button></center><br />
        <div class="footer">
        <center><h5>Copyright © <?php echo date("Y"); ?>&nbsp;Transport Service & Releasing Iberia, S.L. All Rights Reserved.<br /><i>Version 2.0 - Developed by VCatala</i></h5></center>
        </div>        
    </ul>
</form>

</body>
</html>
<?php
include "assets/php/main_admin.php";
include "assets/php/comprobar_periodo.php";
include "assets/php/comprobar_catalago.php";
include "assets/php/comprobar_plaza.php";

if(!in_array(1, rol())){
	header("location: ./perfil");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<title>Consulta Nómina</title>
   <?php include "assets/layouts/header.php"?>
</head>

<body>
	<div class="wrapper ">
		<?php include "assets/layouts/sidebar.php"; ?>
		<div class="main-panel">
			<?php include "assets/layouts/navbar.php"?>
			
			<div class="content">
				<div class="container-fluid">
                        
                    <div class="card">
                        <div class="card-body">
                            <div class="msn-mostrar">
                                <?php
                                if(in_array(2, rol())){
                                    echo '<label id="nuevo-empleado" class="btn-mostrar"><i class="material-icons">add_circle_outline</i>Nuevo empleado</label>';
                                }else{
                                    echo '<label onclick="bloqueo();" class="btn-mostrar"><i class="material-icons">add_circle_outline</i>Nuevo empleado</label>';
                                }
                                ?>

                                
                                <?php
                                if(in_array(4, rol())){
                                    echo '
                                    <label onclick="generar_empleados();" class="btn-mostrar">
                                        <i class="material-icons">file_download</i>Exportar
                                    </label>
                                    ';
                                }else{
                                    echo '<label onclick="bloqueo();" class="btn-mostrar"><i class="material-icons">add_circle_outline</i>Exportar</label>';
                                }
                                ?>

                                <input type="file" id="importar-empleado" accept=".xlsx" />
                                <input type="file" id="importar-empleado-puesto" accept=".xlsx" />
                                <input type="file" id="actualizar" accept=".xlsx" />
                                
                                <?php
                                if(in_array(3, rol())){
                                    echo '
                                    <span class="dropdown">
                                        <div class="btn-mostrar" data-toggle="dropdown">
                                            <i class="material-icons">upload</i>Importar empleados
                                        </div>
                                        <div class="dropdown-menu">
                                            <label class="dropdown-item" for="importar-empleado"> <i class="material-icons">check</i> Por # de plaza</label>
                                            <label class="dropdown-item" for="importar-empleado-puesto"> <i class="material-icons">check</i> Por puesto y departamento</label>
                                            <a href="assets/docs/empleado/importar/empleado.xlsx?v=3.8.7" class="dropdown-item"> <i class="material-icons" >check</i>Plantilla por # de plaza</a>
                                            <a href="assets/docs/empleado/actualizar/empleado.xlsx?v=3.8.7" class="dropdown-item"> <i class="material-icons" >check</i>Plantilla por puesto y departamento</a>
                                        </div>
                                    </span>
                                    ';
                                }else{
                                    echo '<label onclick="bloqueo();" class="btn-mostrar"><i class="material-icons">upload</i>Importar empleados</label>';
                                }
                                ?>
                                
                                <!-- <?php
                                if(in_array(5, rol())){
                                    echo '
                                    <span class="dropdown">
                                        <div class="btn-mostrar" data-toggle="dropdown">
                                            <i class="material-icons">refresh</i>Actualizar empleados
                                        </div>
                                        <div class="dropdown-menu">
                                            <label class="dropdown-item" for="actualizar"> <i class="material-icons" >check</i>Actualizar</label>
                                            <a href="assets/docs/empleado/actualizar/empleado.xlsx?v=3.8.7" class="dropdown-item"> <i class="material-icons" >check</i>Plantilla</a>
                                        </div>
                                    </span>
                                    ';
                                }else{
                                    echo '<label onclick="bloqueo();" class="btn-mostrar"><i class="material-icons">refresh</i>Actualizar</label>';
                                }
                                ?> -->

                                <?php
                                if(in_array(12, rol())){
                                    echo '
                                    <span class="dropdown">
                                        <div class="btn-mostrar" data-toggle="dropdown">
                                            <i class="material-icons">contact_page</i>Importar expediente
                                        </div>
                                        <div class="dropdown-menu">
                                            <label onclick="cargar_expediente(\'constancia\');" class="dropdown-item"> <i class="material-icons">check</i>Constancia de situación fiscal</label>
                                        </div>
                                    </span>
                                    ';
                                }else{
                                    echo '<label onclick="bloqueo();" class="btn-mostrar"><i class="material-icons">refresh</i>Actualizar</label>';
                                }
                                ?>

                              
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive adp-hide">
                        <div class="opciones_tabla">
                            <select id="estado">
                                <option value="alta">ALTA</option>
                                <option value="baja">BAJA</option>
                            </select>
                        </div>
                        <table id="tabla-empleado" class="table table-striped" style="width:100%">
                            <thead class="text-primary">
                                <tr>
                                    <th class="">Empleado</th>
                                    <th class="">Nombre</th>
                                    <th class="oculto">RFC</th>
                                    <th class="oculto">Puesto</th>
                                    <th class="oculto">Categoría</th>
                                    <th class="">Opciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

				</div>
			</div>

			<?php include 'assets/layouts/modal.php'?>
			<footer class="footer"></footer>
		</div>
	</div>

   <?php include 'assets/layouts/scripts.php' ?>
   <script src="assets/js/registrar.js?v=3.8.7"></script>
   <script>$("#tab-empleados").addClass("active");</script>

</body>
</html>
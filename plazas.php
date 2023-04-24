<?php
include "assets/php/main_admin.php";
include "assets/php/comprobar_periodo.php";

if(!in_array(35, rol())){
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
                        <!-- <div class="card-header text-center">
                            <h6 class="card-category text-gray">PLAZAS</h6>
                        </div> -->
                        <div class="card-body">
                            <div class="msn-mostrar">
                                <input type="file" id="importar-plazas" accept=".xlsx" />

                                <?php
                                if(in_array(36 , rol())){
                                    echo '<label onclick="nueva_plaza();" class="btn-mostrar"><i class="material-icons">add_circle_outline</i>Nueva plaza</label>';
                                }else{
                                    echo '<label onclick="bloqueo();" class="btn-mostrar"><i class="material-icons">add_circle_outline</i>Nueva plaza</label>';
                                }

                                if(in_array( 37, rol())){
                                    echo '
                                        <label class="btn-mostrar" for="importar-plazas"><i class="material-icons">file_upload</i>Importar</label>
                                        <a href="./assets/docs/plazas.xlsx?v=3.8.7" download class="btn-mostrar"><i class="material-icons">line_style</i>Plantilla</a>
                                    ';
                                }else{
                                    echo '<label onclick="bloqueo();" class="btn-mostrar"><i class="material-icons">file_upload </i>Importar</label>';
                                }
                                ?>
                              
                                <!-- <label onclick="exportar_plazas();" class="btn-mostrar"><i class="material-icons">file_download</i>Exportar</label> -->
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive adp-hide">
                        <div class="opciones_tabla">
                            <select id="id_puesto">
                                <option value="0" selected>TODOS LOS PUESTOS</option>
                                <?php
                                    $conexion = conexion();
                                    $sql = "SELECT 
                                    id_puesto, 
                                    Puesto.nombre AS puesto,
                                    Departamento.nombre AS departamento 
                                    FROM Puesto LEFT JOIN Departamento ON Puesto.id_departamento = Departamento.id_departamento 
                                    ORDER BY puesto ASC";
                                    $consulta = $conexion->query($sql);
                                    if($consulta && (mysqli_num_rows($consulta)) > 0){
                                        while($res = mysqli_fetch_array($consulta)){
                                            echo '<option data-description="'.$res["departamento"].'" value="'.$res[0].'">'.$res[1].'</option>';
                                        }	
                                    }else{
                                        echo '<option selected value="">NO HAY OPCIONES DISPONIBLES</option>';
                                    }
                                    $conexion->close();
                                ?>
                            </select>
                            <select id="estado">
                                <option value="0">TODAS LAS PLAZAS</option>
                                <option value="1">PLAZAS OCUPADAS</option>
                                <option value="2">PLAZAS DISPONIBLES</option>
                            </select>
                        </div>
                        <table id="tabla-plaza" class="table table-striped" style="width:100%">
                            <thead class="text-primary">
                                <tr>
                                    <th class="">ID</th>
									<th class="">Puesto</th>
                                    <th class="">Trabajador</th>
                                    <th class="oculto">Ocupados</th>
                                    <th class="oculto">Desocupados</th>
                                    <th class="oculto">Por ejercer</th>
                                    <th class="oculto">Presupuestados</th>
									<th class="oculto">Eliminar</th>
                                    <th class="">Estado</th>

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
   <script src="assets/js/plazas.js?v=3.8.7"></script>
   <script>$("#tab-plazas").addClass("active");</script>

</body>
</html>
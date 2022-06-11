<?php

$foto = "./assets/img/user.png";
if ($varFoto != null) {
    $foto = $varFoto;
}

$empleados = '
<li id="tab-empleados" class="nav-item">
    <a class="nav-link" href="./registrar">
        <i class="material-icons">people</i>
        <p>Empleados</p>
    </a>
</li>
';

$usuarios = '
<li id="tab-usuarios" class="nav-item">
    <a class="nav-link" href="./usuarios">
        <i class="material-icons">groups</i>
        <p>Usuarios</p>
    </a>
</li>
';

$perfil = '
<li id="tab-perfil" class="nav-item">
    <a class="nav-link" href="./perfil">
        <i class="material-icons">person_pin</i>
        <p>Perfil</p>
    </a>
</li>
';

$prenomina = '
<li id="tab-prenomina" class="nav-item">
    <a class="nav-link" href="./prenomina">
        <i class="material-icons">receipt_long</i>
        <p>Prenómina</p>
    </a>
</li>
';

$cfdi = '
<li id="tab-subir" class="nav-item">
    <a class="nav-link" href="./subir">
        <i class="material-icons">cloud_upload</i>
        <p>Impotar CFDI</p>
    </a>
</li>
';

$nominas = '
<li id="tab-nominas" class="nav-item">
    <a class="nav-link" href="./consultar">
        <i class="material-icons">text_snippet</i>
        <p>Nóminas</p>
    </a>
</li>
';

$catalogos = '
<li id="tab-catalogos" class="nav-item">
    <a class="nav-link" href="./catalogos">
        <i class="material-icons">table_view</i>
        <p>Catálogos</p>
    </a>
</li>
';

$plazas = '
<li id="tab-plazas" class="nav-item">
    <a class="nav-link" href="./plazas">
        <i class="material-icons">auto_awesome_motion</i>
        <p>Plazas</p>
    </a>
</li>
';

$mensajes = '
<li id="tab-mensajes" class="nav-item">
    <a class="nav-link" href="./mensajes">
        <i class="material-icons">message</i>
        <p>Mensajes <span class="material-icons comprobar_mensajeria animate__animated animate__swing animate__infinite animate__slower hide">markunread</span>
        </p>
    </a>
</li>
';

$reportes = '
<li id="tab-reportes" class="nav-item">
    <a class="nav-link" href="./reportes">
        <i class="material-icons">summarize</i>
        <p>Reportes</p>
    </a>
</li>
';

$sesion = '
<li class="nav-item" id="cerrar-btn">
    <a class="nav-link">
        <i class="material-icons">exit_to_app</i>
        <p>Cerrar sesión</p>
    </a>
</li>
';


$nav = '
<ul class="nav">' .
    $empleados .
    $perfil .
    $usuarios .
    $prenomina .
    $cfdi .
    $nominas .
    $catalogos .
    $plazas .
    $mensajes .
    $reportes .
    $sesion .
'</ul>';

$sidebar = '
<div class="sidebar" data-color="purple" data-background-color="white">
    <div class="municipio">MUNICIPIO DE ' . get_municipio() . '</div>
    <div class="avatar">
        <a href="./perfil"><img src="'. $foto.'"></a>
        <p>' . $varName . '</p>
        <a href="mailto:">' . $varEmail . '</a>
    </div>
    <div class="sidebar-wrapper">' . $nav . '</div>
</div>
';

echo $sidebar;

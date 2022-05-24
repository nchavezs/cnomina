<?php

echo '
<nav class="navbar navbar-expand-lg navbar-absolute fixed-top">
    <div class="container-fluid">
        <div class="navbar-wrapper">
        '.numero_periodo().'
        <a href="#" class="ml-3 nombre_periodo oculto">'.tipo_periodo().'</a>
        <a href="#" class="ml-3 nombre_periodo oculto"><i class="material-icons mr-2">bubble_chart</i>'.nombre_periodo().'</a>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a id="cerrar" class="nav-link" href="#">
                        <i class="material-icons">exit_to_app</i>
                        Cerrar sesión
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
';
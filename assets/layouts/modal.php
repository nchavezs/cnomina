<?php
echo '
<div class="modal" id="modal" data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title modal_titulo text-primary negrita"></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body"></div>
    <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-sm btn-primary" id="modal_aceptar">Si</button>
    </div>
    </div>
    </div>
</div>
';
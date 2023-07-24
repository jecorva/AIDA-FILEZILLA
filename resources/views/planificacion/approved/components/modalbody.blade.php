<?php
$disabled = '';
if( $changes != '2' ) $disabled = 'disabled';
?>

<form id="mdlFromSave" method="post">
    <h6 class="ml-1 mb-0">Planificación actual:</h6>
    <div class="row border m-1">
        <div class="col-md-4">
            <label class="mb-0 pb-0 text-muted fs-13">Ubicación(es): </label>
            <p class="fs-14"><?php echo $ubicaciones ?></p>
        </div>
        <div class="col-md-4 fs-14">
            <label class="mb-0 pb-0 text-muted fs-13">Categoría Implemento: </label><br>
            {{ $cat_implemento }}<br>
            <label class="mb-0 pb-0 text-muted fs-13">Implemento: </label><br>
            {{ $implemento }}
        </div>
        <div class="col-md-4 fs-14">
            <label class="mb-0 pb-0 text-muted fs-13">Categoría Maquinaria: </label><br>
            {{ $cat_maquinaria }}<br>
            <label class="mb-0 pb-0 text-muted fs-13">Maquinaria: </label><br>
            {{ $maquinaria }}
        </div>
    </div>
    <h6 class="mt-3 ml-1 mb-0">Detalle solicitud de cambio:</h6>
    <div class="row border m-1 border-secondary">
        <div class="col-md-12">
            <label class="mb-0 pb-0 text-muted fs-13">Mensaje Observación: </label><span class="ml-2 text-uppercase">{{ $msg }}</span>
        </div>
    </div>
    <div class="row border m-1">
        <div class="col-md-4">
            <label class="mb-0 pb-0 text-muted fs-13">Ubicación(es): </label>
            <p class="fs-14"><?php echo $ubicaciones_sol ?></p>
        </div>
        <div class="col-md-4">
            <label class="mb-0 pb-0 text-muted fs-13">Categoría Implemento: </label><br>
            {{ $cat_implemento_sol }}
            <!-- <label class="mb-0 pb-0 text-muted fs-13">Implemento: </label> -->
        </div>
        <div class="col-md-4">
            <label class="mb-0 pb-0 text-muted fs-13">Categoría Maquinaria: </label><br>
            {{ $cat_maquinaria_sol }}
            <!-- <label class="mb-0 pb-0 text-muted fs-13">Maquinaria: </label> -->
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <div class="float-right">
                <button type="submit" class="btn btn-outline-success btn-sm app-button" {{ $disabled }}>
                    <i class="fas fa-check-square "></i>  Aprobar</a>
                </button>
                <button type="submit" class="btn btn-outline-danger btn-sm app-button" {{ $disabled }}>
                    <i class="fas fa-window-close"></i>  Rechazar</a>
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal"><i class="fas fa-sign-out-alt mr-2"></i>Cerrar</button>
            </div>
        </div>
    </div>
</form>

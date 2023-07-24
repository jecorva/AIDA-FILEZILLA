<?php
//////////////////////////////// Tabla Inicial para el detalle del Plan /////////////////////////

$fecha = $dtll_plan->date_inicio;
$fecha = new DateTime($fecha);
$fecha_inicio = $fecha->format('d-m-Y');
$fecha_actual = date($fecha_inicio);
$dia =  date("d-m-Y", strtotime($fecha_actual . "+ 1 days"));
if( isset($dtll_plan->id) ) $dp = $dtll_plan->id;
else $dp = "";
?>

<style>
    .modal-open {
        overflow: auto !important;
        padding-right: 0 !important;
    }

</style>

<div id="nueva-labor" class="card elevation-0 border" style="display: none">
    <div class="card-body">
        <form id="saveLaborForm">
            <input id="id_planificacion" value="{{ $dp }}" type="hidden" />
        </form>
    </div>
</div>

<div class="sourcesans">
    <div id="content-table-labores">

    </div>
</div>

<script>

var loading ="<div class='text-center'><span class='fa-stack fa-lg'>\n\
                    <i class='fa fa-spinner fa-spin fa-stack fa-fw'></i>\n\
                </span>&emsp;Cargando ...</div>"

    $(document).ready(function() {
        listLaboresPlan();
    })

    async function listLaboresPlan() {
        $("#content-table-labores").html(loading);

        var id_planificacion = $("#id_planificacion").val();
        var data = { id_planificacion: id_planificacion };

        await getComponent('/api/change-list-labor', data, 'content-table-labores')
    }

    $("#btn-show-modal").on("click", function() {
        //$("#mdl-add-labor").modal("show");
        $("#nueva-labor").addClass("d-block");
    });

    $("#btn-hiden-nuevo").on("click", function() {
        $("#nueva-labor").removeClass("d-block");
        ''
    });

    async function getComponent(url, data, id) {
        let uri = "{!! url('') !!}";
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: uri + url,
            type: 'POST',
            data: data,
        }).done(function(component) {
            $("#" + id).html(component)
        })
    }

    $(function() {
        $('.select2').select2({
            dropdownCssClass: "sourcesans fs-13",
            language: {
                noResults: function() {
                    return "Sin resultados";
                },
                searching: function() {
                    return "Buscando..";
                }
            }
        });
    });
</script>

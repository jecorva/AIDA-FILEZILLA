<div class="row">
    <div class="col-md-12">
        <div id="div-table" class="font-weight-normal">
            <table
                id="table"
                class="table-sm"
                data-toggle="bootstrap-table"
                data-search="true"
                >
            </table>
        </div>
    </div>
</div>

<style>
    #table tbody {
        font-size: 13px !important;
    }

    .fixed-table-header {
        margin-right: 17px !important;
    }

    [data-field="nro"] .th-inner {
        width: 20px;
        font-weight: bold;
    }

</style>
<script>
    var $table = $('#table');

    $table.bootstrapTable('destroy')
        .bootstrapTable({
            data: [],
            pagination: true,
            height: 420,
            locale:'es_ES',
            columns: [
                {
                    field: 'nro',
                    title: 'Nro',
                    align: 'center',
                    formatter: indice
                },
                {
                    field: 'dia',
                    title: 'Fecha',
                    align: 'center',
                    //width: 50,
                    //formatter: diaRow
                },
                // {
                //     field: 'labor',
                //     title: 'Nombre de labor',
                //     sortable: true,
                //     //width: 550,
                //     //widthUnit: 'px'
                // },
                {
                    field: 'nombre_turno',
                    title: 'Supervisor - Requerimiento',
                    sortable: true,
                },
                {
                    field: 'implemento',
                    title: 'Implemento',
                    //formatter: selectImplement,

                },
                {
                    field: 'maquinarias',
                    title: 'Maquinaria',
                    //formatter: selectMaquinaria,

                },
                {
                    field: 'estado',
                    title: 'Estado',
                    //formatter: ubicaciones
                },
                {
                    field: 'operacion',
                    title: 'Operación',
                    //formatter: selectOperacion,
                    align: 'center'
                }
            ]
        });

    function indice(value, row, index) {
        return index + 1;
    }
</script>

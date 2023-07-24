@extends('adminlte::page')
<?php
$_MENU_ITEM = $menu[0]['KeyMenu'];
$_SUBMENU_ITEM = $menu[0]['KeySubmenu'];
?>

@section('title', 'SGC | Permisos')

@section('content_header')

@stop

@section('content')
<br>
<div class="alert bg-bluedark text-white h4 d-flex justify-content-between">
    <span class=" mt-1">Permisos del Sistema</span>

</div>

<div class="row">
    <div class="col-md-8">
        <div class="border rounded bg-white fs-14 px-3 py-2">
            <table style="margin-top: -49.5px;"
                class="table-sm"
                id="table-user"
                data-toggle="bootstrap-table"
                data-search="true"
                data-pagination="true"
                data-buttons-class="primary"
                data-single-select="true"
                data-click-to-select="true"
                data-url="{!! url('api/user-list-permission') !!}">
                <thead class="" style="background-color:#eeeeee;"></thead>
                <tbody height='35px  '></tbody>
            </table>
        </div>
    </div>
    <div class="col-md-4 fs-14">
        <div class="row">
            <div class="col-md-12">
                <div class="border bg-white border-ligth rounded mb-3">
                    <div class="card-header ">
                        <h4 class="card-title app-title font-weight-bold mr-1">Accesos</h4>[ <span class="text-primary" id="user-access"></span> ]
                        <div class="card-tools">
                            <!--
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>

                            <button type="button" class="btn btn-secondary btn-sm" title="Guardar">
                                <i class="fas fa-save"></i>
                            </button>-->
                        </div>
                    </div>
                    <div class="card-body px-4" id="card-menu">
                        <div class="list-group">
                            <button type="button" class="list-group-item list-group-item-action list-group-item-secondary" aria-current="true"><i class="fas fa-link mr-1"></i>Home(default)</button>
                        </div>
                    </div>

                    <div class="mx-4 rounded mb-3">
                        <div class="d-flex justify-content-between border py-2 px-3">
                            <h4 class="fs-14 vertical app-title font-weight-bold mt-2"><i class="fas fa-link mr-1"></i>
                                <span id="title-submenu">Sub-Menú</span>
                            </h4>
                            <button type="button" class="btn btn-secondary btn-sm" id="btn-save-permisos" title="Guardar">
                                <i class="fas fa-save fs-14"></i> Guardar
                            </button>
                        </div>
                        <div class="card-body border border-ligth " id="card-submenu">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

@section('css')

@stop

@section('js')
<script>
    var $tableUser = $("#table-user");
    var $btnSave = $("#btn-save-permisos");
    var keyMay = false;
    var keyNum = false;
    var keyPass = false;

    var userId = '';
    var menuIdSlt = '';

    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 5000
    });

    $(document).ready(function() {
        $tableUser.on('click-row.bs.table', async function(e, row, $element) {
            userId = row.id
            var data = {};
            $("#title-submenu").html('Sub-Menú')
            $("#card-submenu").html('');
            $("#user-access").html( row.nombres + ' ' + row.apel_pat )
            const response = await getComponent('/api/component-card-menu', data, 'card-menu')
        });

        $tableUser.bootstrapTable('destroy').bootstrapTable({
            height: 420,
            locale: 'es-ES',
            columns: [{
                    checkbox: true,
                    align: 'center',
                    valign: 'middle'
                },
                {
                    title: 'Nro',
                    align: 'center',
                    valign: 'middle',
                    formatter: indice
                },
                {
                    title: 'D.N.I.',
                    field: 'dni',
                    align: 'center',
                    valign: 'middle',
                },
                {
                    title: 'Apellido Paterno',
                    field: 'apel_pat',
                    valign: 'middle',
                },
                {
                    title: 'Apellido Materno',
                    field: 'apel_mat',
                    valign: 'middle',
                },
                {
                    title: 'Nombres',
                    field: 'nombres',
                    align: 'center',
                    valign: 'middle',
                },
                {
                    title: 'Email',
                    field: 'email',
                    align: 'center',
                    valign: 'middle',
                },
            ]
        });
    })

    function indice(value, row, index) {
        //return [row.email];
        return index + 1;
    }

    async function btnMenu(menuId, nameMenu) {
        var data = {
            'MenuID': menuId,
            'userId': userId,
            'name': nameMenu
        }
        menuIdSlt = menuId;

        console.log(data);
        await getComponent('/api/component-card-submenu', data, 'card-submenu')
    }

    $btnSave.on('click', async function() {
        let valoresCheck = [];
        $("input[data-menu=submenu]:checked").each(function(){
            valoresCheck.push(this.value);
        });
        var data = {
            'userId': userId,
            'menuId': menuIdSlt,
            'menusubmenus': valoresCheck,
        };
        console.log(data);
        const response = await myAPIRest('/api/permisos-save', data)
        console.log(response)
        if( response.status == 200 ) {
            Toast.fire({
                icon: 'success',
                title: 'Permisos actualizados.'
            })
        }

    })

    ///////////////////////////////////////////////////////////////

    async function myAPIRest(url, data) {
        let uri = "{!! url('') !!}";
        var result = await $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: uri + url,
            type: 'POST',
            data: data,
        })
        return result
    }

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
        $('li a').removeClass('active');
        $('.menu-is-opening').removeClass('menu-is-opening');
        $('.menu-open').removeClass('menu-open');
        $('#{!! $_MENU_ITEM !!}').addClass('menu-is-opening');
        $('#{!! $_MENU_ITEM !!}').addClass('menu-open');
        $('#{!! $_MENU_ITEM !!} .title').addClass('active');
        $('#{!! $_SUBMENU_ITEM !!}').addClass('active');
    });
</script>
@stop

@extends('adminlte::page')
<?php
$_MENU_ITEM = $menu[0]['KeyMenu'];
$_SUBMENU_ITEM = $menu[0]['KeySubmenu'];
?>
@section('title', 'Planificación - Maquinarias')

@section('content_header')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
        <span class="p-0 m-0 sourcesans h4">Gestión de requerimientos</span>
        <!--<button class="btn btn-app fs-12">
            <i class="fas fa-plus"></i>
        </button>-->
    </div>
</div>
@stop
@section('content')

<br>
<nav class="w-100 sourcesans fs-14">
    <div class="nav nav-tabs" id="machinery-tab" role="tablist">
        <a class="nav-item nav-link active" id="machinery-list-tab" data-toggle="tab" href="#machinery-list-desc" role="tab" aria-controls="machinery-list-desc" aria-selected="true"><i class="fas fa-list mr-1"></i>Lista</a>
        <!-- <a class="nav-item nav-link" id="machinery-create-tab" data-toggle="tab" href="#machinery-create-desc" role="tab" aria-controls="machinery-create-desc" aria-selected="false">[ <i class="fas fa-plus mr-1"></i>Nuevo ]</a> -->
        <a class="nav-item nav-link" id="machinery-plan-tab" data-toggle="tab" href="#machinery-plan-desc" role="tab" aria-controls="machinery-plan-desc" aria-selected="false">[ <i class="fas fa-calendar-alt mr-1"></i>Planificar ]</a>
    </div>
</nav>

<div class="tab-content bg-white p-3 border rounded" id="nav-tabContent">
    <div class="tab-pane fade active show" id="machinery-list-desc" role="tabpanel" aria-labelledby="machinery-list-tab">
        <div class="alert bg-bluedark opensans text-white">
            <i class="fas fa-info-circle mr-2"></i>Tabla requerimientos por semana
        </div>
        <div class="sourcesans" id="tab-machinery-search"></div>
        <div class="sourcesans" id="tab-machinery-list"></div>
    </div>
    <div class="tab-pane fade" id="machinery-create-desc" role="tabpanel" aria-labelledby="machinery-create-tab">
        <span class="p-0 m-0 sourcesans text-label h5">Crear un nuevo machinery</span>
        <br>
        <br>
        <div id="tab-machinery-create"></div>
    </div>
    <div class="tab-pane fade" id="machinery-plan-desc" role="tabpanel" aria-labelledby="machinery-plan-tab">
        <div class="p-0 m-0 sourcesans text-label h5 ml-2">Registrar labores
            <button id='btn-show-modal' class='btn btn-link m-0 p-0 mb-1 sourcesans'>
                [ <i class='fas fa-plus fs-12'></i> Nuevo ]
            </button>
        </div>
        <br>
        <div id="tab-machinery-plan"></div>
    </div>
</div>
<br><br>
@stop

@section('js')
<script>
    var loading ="<div class='text-center'><span class='fa-stack fa-lg'>\n\
                    <i class='fa fa-spinner fa-spin fa-stack fa-fw'></i>\n\
                </span>&emsp;Cargando ...</div>"

    $(document).ready(function() {
        searchWidget();
        hiddenOptionMachinery(0);
    });

    async function searchWidget() {
        $("#tab-machinery-search").html(loading);
        const response = await getRequest('/api/request-machinery-search')
        $("#tab-machinery-search").html(response);
    }

    async function listMachinery(arg1, arg2) {
        $("#tab-machinery-list").html(loading);
        var data = {
            anio: arg1,
            nro_semana: arg2,
        };

        await getComponent('/api/request-machinery-list', data, 'tab-machinery-list')
    }

    async function newmachinery() {
        // $.get('../api/machinery-create',
        //     function(data) {
        //         $("#tab-machinery-create").html(data);
        //     });
        $("#tab-machinery-create").html(loading)
        const response = await getRequest('/api/machinery-create')
        $("#tab-machinery-create").html(response);
    }

    function hiddenOptionMachinery(param) {
        if (param == 1) {
            $("#machinery-plan-tab").removeClass('invisible');
            $("#machinery-plan-tab").addClass('visible active');
            $("#machinery-plan-desc").addClass('active show');

            $("#machinery-list-tab").removeClass('active');
            $("#machinery-list-desc").removeClass('active show');
        }
        if (param == 0) {
            $("#machinery-plan-tab").removeClass('visible active');
            $("#machinery-plan-desc").removeClass('active show');
            $("#machinery-plan-tab").addClass('invisible');
        }
    }

    $("#machinery-list-tab").on("click", function() {
        hiddenOptionmachinery(0);
    })

    $("#machinery-create-tab").on("click", function() {
        hiddenOptionmachinery(0);
    });

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    async function postRequest(url, data) {
        let uri = "{!! url('') !!}";
        var result = await $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: uri + url,
            type: 'POST',
            data: data,
        })
        return result;
    }

    async function getRequest(url) {
        let uri = "{!! url('') !!}";
        var result = await $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: uri + url,
            type: 'GET',
        })
        return result;
    }

    async function getComponent(url, data, id) {
        let uri = "{!! url('') !!}";
        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: uri + url,
            type: 'POST',
            data: data,
        }).done(function(component) {
            $("#" + id).html(component)
        })
    }

    $(function() {
        $('.select2').select2({
            dropdownCssClass: "opensans fs-14",
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

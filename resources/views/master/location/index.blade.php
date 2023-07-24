@extends('adminlte::page')

@section('title', 'Ubicaciones')

@section('content_header')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
        <span class=" p-0 m-0 sourcesans h4">Ubicaciones - Gestión de ubicaciones</span>
        <!--<button class="btn btn-app fs-12">
            <i class="fas fa-plus"></i>
        </button>-->
    </div>
</div>
@stop
@section('content')

<br><br>

<nav class="w-100 sourcesans fs-14">
    <div class="nav nav-tabs" id="location-tab" role="tablist">
        <a class="nav-item nav-link active" id="location-list-tab" data-toggle="tab" href="#location-list-desc" role="tab" 
            aria-controls="location-list-desc" aria-selected="true"><i class="fas fa-list mr-1"></i>Lista</a>
        <!--<a class="nav-item nav-link" id="location-create-tab" data-toggle="tab" href="#location-create-desc" role="tab" aria-controls="location-create-desc" aria-selected="false">CREAR</a>-->
        <a class="nav-item nav-link" id="location-edit-tab" data-toggle="tab" href="#location-edit-desc" role="tab" aria-controls="location-edit-desc" aria-selected="false">[ <i class="fas fa-edit mr-1"></i>Editar ]</a>
    </div>
</nav>

<div class="tab-content bg-white p-3 border rounded" id="nav-tabContent">
    <div class="tab-pane fade active show" id="location-list-desc" role="tabpanel" aria-labelledby="location-list-tab">
        <div class="alert bg-bluedark RobotoC text-white">
            <i class="fas fa-table mr-2"></i>Tabla Ubicaciones
        </div> 
        <div id="tab-location-list"></div>
    </div>
    <!--<div class="tab-pane fade" id="location-create-desc" role="tabpanel" aria-labelledby="location-create-tab">
        <span class=" p-0 m-0 raleway-title h5">Crear un nuevo usuario</span>
        <hr>
        <div id="tab-location-create"></div>
    </div>-->
    <div class="tab-pane fade" id="location-edit-desc" role="tabpanel" aria-labelledby="location-edit-tab">
        <div class="alert bg-bluedark RobotoC text-white"><i class="fas fa-pencil-alt mr-2"></i>Editar Ubicación</div>
        <div id="tab-location-edit"></div>
    </div>
</div>
<br><br>
@stop

@section('js')
<script>
    var loading =
        "<table class='table'>" +
            "<thead class='text-secondary table-secondary text-input'>" +
                "<tr>" +
                    "<th>Columna I</th>" +
                    "<th>Columna II</th>" +
                    "<th>Columna III</th>" +
                    "<th>Columna IV</th>" +                    
                "</tr>" +
            "</thead>" +
            "<tbody>" +
                "<tr>" +
                    "<td colspan='4'>" +
                        "<div class='text-secondary sourcesansrg text-center'>" +
                        "<img src='{!! url('img/preloader.gif') !!}' width='40' />" +
                        "Cargando..." +
                        "</div>"
                    "</td>" +
                "</tr>" +
             "</tbody>" +
        "</table>";

    $(document).ready(function() {
        hiddenLocation(0);
        loadListLocation();
    });

    function loadListLocation() {
        $("#tab-location-list").html(loading);
        $.get("{!! url('api/location-list') !!}",
            function (data) {
                $("#tab-location-list").html(data);
            });
    }

    function hiddenLocation(param) {
        if( param == 1 ) {
            $("#location-edit-tab").removeClass('invisible');
            $("#location-edit-tab").addClass('visible active');
            $("#location-edit-desc").addClass('active show');        
            
            $("#location-list-tab").removeClass('active');        
            $("#location-list-desc").removeClass('active show');
        }
        if( param == 0 ) {
            $("#location-edit-tab").removeClass('visible active');
            $("#location-edit-desc").removeClass('active show');
            $("#location-edit-tab").addClass('invisible');
        }
        
    }

    $("#location-list-tab").on("click", function() {
        hiddenLocation(0);
    })

    $(function () {
        $('li a').removeClass('active');
        $('.menu-is-opening').removeClass('menu-is-opening');
        $('.menu-open').removeClass('menu-open');
        $('#lisSetting').addClass('menu-is-opening');
        $('#liSetting').addClass('menu-open');
        $('#liSetting .title').addClass('active');
        $('#liSetting-location').addClass('active');
    })
</script>
@stop
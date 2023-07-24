@extends('adminlte::page')

@section('title', 'Operarios')

@section('content_header')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
        <span class="p-0 m-0 sourcesans h4">Operarios - Gestión de habilidades</span>
        <!--<button class="btn btn-app fs-12">
            <i class="fas fa-plus"></i>
        </button>-->
    </div>
</div>
@stop
@section('content')

<br><br>

<nav class="w-100 sourcesans fs-14">
    <div class="nav nav-tabs" id="operario-tab" role="tablist">
        <a class="nav-item nav-link active" id="operario-list-tab" data-toggle="tab" href="#operario-list-desc" role="tab"
            aria-controls="operario-list-desc" aria-selected="true"><i class="fas fa-list mr-1"></i>Lista</a>
        <a class="nav-item nav-link" id="operario-create-tab" data-toggle="tab" href="#operario-create-desc" role="tab"
            aria-controls="operario-create-desc" aria-selected="false">[ <i class="fas fa-plus mr-1"></i>Nuevo ]</a>
        <a class="nav-item nav-link" id="operario-edit-tab" data-toggle="tab" href="#operario-edit-desc" role="tab"
            aria-controls="operario-edit-desc" aria-selected="false">[ <i class="fas fa-edit mr-1"></i>Editar ]</a>
    </div>
</nav>

<div class="tab-content bg-white p-3 border rounded" id="nav-tabContent">
    <div class="tab-pane fade active show" id="operario-list-desc" role="tabpanel" aria-labelledby="operario-list-tab">
        <div class="alert bg-bluedark RobotoC text-white">
            <i class="fas fa-table mr-2"></i>Tabla Operarios - Habilidades
        </div>
        <div id="tab-operario-list"></div>
    </div>
    <div class="tab-pane fade" id="operario-create-desc" role="tabpanel" aria-labelledby="operario-create-tab">
        <div class="alert bg-bluedark RobotoC text-white"><i class="fas fa-plus mr-2"></i>Crear una nueva hablidad</div>        
        <div id="tab-operario-create"></div>
    </div>
    <div class="tab-pane fade" id="operario-edit-desc" role="tabpanel" aria-labelledby="operario-edit-tab">
        <span class=" p-0 m-0 sourcesans text-label h5">Editar operario</span>
        <br><br>
        <div id="tab-operario-edit"></div>
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
                        "<img src='../../img/preloader.gif' width='40' />" +
                        "Cargando..." +
                        "</div>"
                    "</td>" +
                "</tr>" +
             "</tbody>" +
        "</table>";
    
    var loadingSpinner = 
        "<center class='sourcesans'>"+
            "<div class='border pt-2 rounded' style='width: 120px;'>"+
                "<div class='spinner-border text-secondary mr-2' role='status'></div>"+
                "<br>Cargando..."+
            "</div>"+
        "</center>";

    $(document).ready(function() {
        listSkill();
        //newSkill();
        hiddenOptionSkill(0);
    });

    function listSkill() {
        $("#tab-operario-list").html(loading);
        $.get("{!! url('api/operario-list') !!}",
            function (data) {
                $("#tab-operario-list").html(data);
            });
    }

    function hiddenOptionSkill(param) {
        if( param == 1 ) {
            $("#operario-edit-tab").removeClass('invisible');
            $("#operario-edit-tab").addClass('visible active');
            $("#operario-edit-desc").addClass('active show');        
            
            $("#operario-list-tab").removeClass('active');        
            $("#operario-list-desc").removeClass('active show');
        }
        if( param == 0 ) {
            $("#operario-edit-tab").removeClass('visible active');
            $("#operario-edit-desc").removeClass('active show');
            $("#operario-edit-tab").addClass('invisible');
        }    
    }

    $("#operario-create-tab").on('click', function() {
        newSkill();
    });

    function newSkill() {
        $("#tab-operario-create").html(loadingSpinner);
        $.get("{!! url('api/operario-create') !!}",
            function (data) {
               $("#tab-operario-create").html(data);
            });
    }

    $(function () {
        $('li a').removeClass('active');
        $('.menu-is-opening').removeClass('menu-is-opening');
        $('.menu-open').removeClass('menu-open');
        $('#liPlan').addClass('menu-is-opening');
        $('#liPlan').addClass('menu-open');
        $('#liPlan .title').addClass('active');
        $('#liPlan-operator').addClass('active');
    })
</script>
@stop
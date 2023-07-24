@extends('adminlte::page')

@section('title', 'Usuarios')

@section('content_header')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
        <span class=" p-0 m-0 sourcesans h4">Roles - Gestión de roles</span>
        <!--<button class="btn btn-app fs-12">
            <i class="fas fa-plus"></i>
        </button>-->
    </div>
</div>
@stop

@section('content')

<br><br>

<nav class="w-100 sourcesans fs-14">
    <div class="nav nav-tabs" id="rol-tab" role="tablist">
        <a class="nav-item nav-link active" id="rol-list-tab" data-toggle="tab" href="#rol-list-desc" role="tab" aria-controls="rol-list-desc" aria-selected="true"><i class="fas fa-list mr-1"></i>Lista</a>
        <a class="nav-item nav-link" id="rol-edit-tab" data-toggle="tab" href="#rol-edit-desc" role="tab" aria-controls="rol-edit-desc" aria-selected="false">[ <i class="fas fa-edit mr-1"></i>Editar ]</a>
    </div>
</nav>

<div class="tab-content p-3 border rounded bg-white" id="nav-tabContent">
    <div class="tab-pane fade active show" id="rol-list-desc" role="tabpanel" aria-labelledby="rol-list-tab">
        <div class="alert bg-bluedark RobotoC text-white">
            <i class="fas fa-table mr-2"></i>Tabla de Roles
        </div>
        <div id="tab-rol-list"></div>
    </div>
    <!-- <div class="tab-pane fade" id="rol-create-desc" role="tabpanel" aria-labelledby="rol-create-tab">
        <span class=" p-0 m-0  sourcesans text-label h5">Crear un nuevo rol</span>
        <br><br>
        <div id="tab-rol-create"></div>
    </div> -->
    <div class="tab-pane fade" id="rol-edit-desc" role="tabpanel" aria-labelledby="rol-edit-tab">
        <div class="alert bg-bluedark RobotoC text-white"><i class="fas fa-pencil-alt mr-2"></i>Editar Rol</div>        
        <div id="tab-rol-edit"></div>
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
        getListaRoles();
        hiddenTabEditarRol(0);
    });

    // Listar roles
    function getListaRoles() {
        /**"<center><img class='img-thumbnail mt-5 text-secondary font-weight-light fs-13' src='../../img/preloader.gif' width='40' /><br><br>Cargando...</center>" */
        $("#tab-rol-list").html(loading);
        $.get("{!! url('api/rol-list') !!}",
            function(data) {
                $("#tab-rol-list").html(data);
            });
    }

    // Ocultar 
    function hiddenTabEditarRol(param) {
        if (param == 1) {
            $("#rol-edit-tab").removeClass('invisible');
            $("#rol-edit-tab").addClass('visible active');
            $("#rol-edit-desc").addClass('active show');

            $("#rol-list-tab").removeClass('active');
            $("#rol-list-desc").removeClass('active show');
        }
        if (param == 0) {
            $("#rol-edit-tab").removeClass('visible active');
            $("#rol-edit-desc").removeClass('active show');
            $("#rol-edit-tab").addClass('invisible');
        }

    }

    $("#rol-list-tab").on("click", function() {
        hiddenTabEditarRol(0);
    })

    $(function () {
        $('li a').removeClass('active');
        $('.menu-is-opening').removeClass('menu-is-opening');
        $('.menu-open').removeClass('menu-open');
        $('#liUser').addClass('menu-is-opening');
        $('#liUser').addClass('menu-open');
        $('#liUser .title').addClass('active');
        $('#liUser-rols').addClass('active');
    })
</script>
@stop
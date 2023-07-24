<?php
$url = url('api/planificacion-list/'.$nro_sem.'/'.$anio);

use App\Models\Person;
$supervisores = Person::select(
    'people.id',
    'users.nombres',
    'users.apel_pat',
    'users.apel_mat'
)   ->join('person_types', 'person_types.id', '=', 'people.typeperson_id')
    ->join('users', 'users.id', '=', 'people.user_id')
    ->where('people.typeperson_id', '=', 3)
    ->get();

 function selectSupervisor() {    

    $html = '<select class="form-control">
                <option>Seleccion</option>';
        
    $html.= "</select>";
    return $html;
}
?>

<div class="row">
    <div class="col-md-12">
        <div class="font-weight-normal">
            <table 
                id="table"
                class=""
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
    .text-sm .select2-container--default .select2-selection--single .select2-selection__rendered, select.form-control-sm~.select2-container--default .select2-selection--single .select2-selection__rendered{
        margin-top: -0.2rem !important;
        font-size: 13px !important;
    }
    .fixed-table-header {
        margin-right: 17px !important;
    }
</style>

<script>    
    var $table = $('#table');

    $table.bootstrapTable('destroy')
        .bootstrapTable({
            url: '{!! $url !!}',
            pagination: true, 
            height: 720,
            locale:'es_ES',
            columns: [
                {
                    field: 'nro',
                    title: 'Nro',  
                    align: 'center',
                    width: 30,
                },
                {
                    field: 'labor',
                    title: 'Nombre de labor',                    
                    sortable: true,
                    width: '550',
                    widthUnit: 'px'
                }, 
                {
                    field: 'dia',
                    title: 'Fecha',                    
                    align: 'center',
                    width: '100',
                    formatter: diaRow
                }, 
                {
                    field: 'nombre_turno',
                    title: 'Turno',
                    sortable: true,
                },
                {
                    field: 'ubicaciones',
                    title: 'Ubicaciones',
                },
                {
                    field: 'implementos',
                    title: 'Implementos',
                    formatter: selectImplement,
                    
                },
                {
                    field: 'maquinarias',
                    title: 'Maquinarias',
                    formatter: selectMaquinaria,
                    
                },
                {
                    field: 'operarios',
                    title: 'Operarios',
                    formatter: selectOperario,
                    
                },
                {
                    field: 'operacion',
                    title: 'Operación',
                    formatter: selectOperacion,
                    align: 'center'
                }
            ]
        });

    $table.on('post-header.bs.table', function () {
        $('.select2').select2({            
            dropdownCssClass: "fs-12 text-uppercase",
            language: {
                noResults: function() {
                    return "Sin resultados";
                },
                searching: function() {
                    return "Buscando..";
                }
            }
        });
        // $('.select2').on('select2:close', function(){
        //     $table.bootstrapTable('refresh');
        // });
    });
    

    function item(value, row, index) {       
        return index +1;
    }

    function diaRow(value, row, index) {
        return '<input style="border:none" class="bg-white" disabled type="text" data-dia="dia" value="'+value+'" />';
    }

    function selectImplement(value, row, index) {
        let option ='';
        let html = '';
        let resp = [];
        if( value.nro_maquinas != '' ) {
            for(let i=0; i<value.timplementos; i++) {
                value.implementos.forEach( function(element) {
                    let selected = value.imp_id == element.id ? 'selected' : '';
                    option += '<option value="'+  element.id +'" '+ selected +'>'+ element.nombre+'</option>';                
                });
                html = [
                    '<select onchange="sltImp(\''+index+'\', \''+value.dia+'\', \''+i+'\')" data-select="imp" id="sltImp_'+ index +'_'+i+'" class="select2">',
                        '<option value="0" selected>Seleccionar</option>',                    
                        option,
                    '</select>'
                ].join('');
                resp.push(html)
            }             
            
        }       

        return resp;
    }

    function sltImp(index, dia, i) {
        let valoresImp = [];
        let valoresFec = [];
        $("select[data-select=imp] option:selected").each(function(){
            valoresImp.push(this.value);            
        }); 
        $("input[data-dia=dia]").each(function(){
            valoresFec.push(this.value);
        });       

         console.log(valoresImp)
         console.log(valoresFec)

        let implement_id = $("#sltImp_"+index+"_"+i).val();

        if( implement_id != '0') {

            let contador = 0;
            let contador2 = 0;
            
            for (let i = 0; i < valoresImp.length; i++) {                
                if( valoresImp[i] == implement_id && valoresFec[i] == dia ) {
                    contador++;
                }
            }

            if( contador >= 2) {
                Swal.fire({
                    title: 'Implemento ocupado',
                    text: 'desea continuar con la operación?',
                    showDenyButton: true,                
                    confirmButtonText: 'Si, continuar',
                    denyButtonText: `Cancelar`,
                }).then((result) => {            
                    if (result.isConfirmed) {
                        
                    } else if (result.isDenied) {
                        $("#sltImp_"+index+"_"+i).select2("val", "0");
                    }
                });
            }
            
        }
        
    }

    function selectMaquinaria(value, row, index) {
        let option ='';
        let html = '';
        if( value.nro_maquinas != '' ) {
            value.maquinarias.forEach( function(element) {
                let selected = value.maq_id == element.id ? 'selected' : '';
                option += '<option value="'+  element.id +'" '+ selected +'>'+ element.nombre+'</option>';                
            });
            html = [
                '<select onchange="sltMaq(\''+index+'\', \''+value.dia+'\')" data-select="maq" id="sltMaq_'+ index +'" class="select2">',
                    '<option value="0" selected>Seleccionar</option>',                    
                    option,
                '</select>'
            ].join('')
        }       

        return html;
    }

    function sltMaq(index, dia) {
        let valores = [];
        let valoresFec = [];
        $("select[data-select=maq] option:selected").each(function(){
            valores.push(this.value);            
        }); 
        $("input[data-dia=dia]").each(function(){
            valoresFec.push(this.value);
        });       

        let maquinaria_id = $("#sltMaq_"+index).val();
        
        if( maquinaria_id != '0' ) {

            let contador = 0;
            let contador2 = 0;
            
            for (let i = 0; i < valores.length; i++) {
                if( valores[i] == maquinaria_id && valoresFec[i] == dia ) {
                    contador++;
                }
            }

            if( contador >= 2 ) {
                Swal.fire({
                    title: 'Maquinaria ocupada',
                    text: 'desea continuar con la operación?',
                    showDenyButton: true,                
                    confirmButtonText: 'Si, continuar',
                    denyButtonText: `Cancelar`,
                }).then((result) => {            
                    if (result.isConfirmed) {
                        
                    } else if (result.isDenied) {
                        $("#sltMaq_"+index).select2("val", "0");
                    }
                });
            } 
        }
    }

    function selectOperario(value, row, index) {
        let option ='';
        let html = '';
        if( value.nro_maquinas != '' ) {
            value.operarios.forEach( function(element) {
                let selected = value.ope_id == element.id ? 'selected' : '';
                let oper = element.apel_pat + ' ' + element.apel_mat + ' - ' + element.nombres
                option += '<option value="'+  element.id +'" '+ selected +'>'+ oper.toUpperCase() +'</option>';
            });            
            html = [
                '<select onchange="sltOpe(\''+index+'\', \''+value.dia+'\')" data-select="ope" id="sltOpe_'+ index +'" class="select2">',
                    '<option value="0" selected>Seleccionar</option>',                    
                    option,
                '</select>',                
            ].join('');
        }       

        return html;
    }

    function sltOpe(index, dia) {
        let valores = [];
        let valoresFec = [];
        $("select[data-select=ope] option:selected").each(function(){
            valores.push(this.value);            
        }); 
        $("input[data-dia=dia]").each(function(){
            valoresFec.push(this.value);
        });       

        let operario_id = $("#sltOpe_"+index).val();
        
        if( operario_id != '0' ) {

            let contador = 0;   

            for (let i = 0; i < valores.length; i++) {
                if( valores[i] == operario_id && valoresFec[i] == dia ) {
                    contador++;
                }
            }

            if( contador >= 2 ) {
                Swal.fire({
                    title: 'Operario ocupado',
                    text: 'desea continuar con la operación?',
                    showDenyButton: true,                
                    confirmButtonText: 'Si, continuar',
                    denyButtonText: `Cancelar`,
                }).then((result) => {            
                    if (result.isConfirmed) {
                        
                    } else if (result.isDenied) {
                        $("#sltOpe_"+index).select2("val", "0");
                    };
                })
            } 
        }
    }
    
    function selectOperacion(value, row, index) {
        let labor_id = value.labor_id
        let location_id = value.location_id
        let rdt_id = value.rdt_id
        let nimp = value.timplementos

        return '<button onclick="saveRow(\''+index +'\', \''+labor_id+'\', \''+location_id+'\', \''+rdt_id+'\', \''+nimp+'\')" class="btn btn-success btn-sm">Guardar</button>';
    }

    async function saveRow(index, labor_id, location_id, rdt_id, nimp) {
        let imp = [];
        let sumi= 0;
        for(let i=0; i<nimp; i++) {
            let implemento = $("#sltImp_"+index+"_"+i).val()
            if(implemento == '0') sumi++;
            imp.push(implemento)
        }        
        let maquinaria = $("#sltMaq_"+index).val()
        let operario = $("#sltOpe_"+index).val()
        var param = {
            implemento: imp,
            maquinaria: maquinaria,
            operario: operario,
            rdt_id: rdt_id,
            labor_id: labor_id,
            location_id: location_id,            
        }

        console.log(param)
        if( sumi == "0" && maquinaria != "0" && operario != "0" ) {
            var response = await httpMyRequest('/api/planificacion-save-row', param)
            console.log(response)
            if( response.status == 201 ) {
                ToastTimer.fire({
                    'icon': 'success',
                    'title': 'Tareo registrado.'
                })
            }
        }else {
            ToastTimer.fire({
                icon: 'info',
                title: 'Seleccione requerimientos'
            });
        }

    }

    async function httpMyRequest(url, data) {
        let uri = "{!! url('') !!}";
        var result = await $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: uri + url,
            type: 'POST',
            data: data,
        })
        return result;
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

    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: true,
        //timer: 3000
    });

    var ToastTimer = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    });
    
</script>
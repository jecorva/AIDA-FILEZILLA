<?php

use Illuminate\Support\Facades\Crypt;
?>
<form id="frm-save-user-edit">
    <input type="hidden" id="dni-edit-old" value="{{ $usuario->dni }}" />
    <input type="hidden" id="email-edit-old" value="{{ $usuario->email }}" />
    <input type="hidden" id="key-edit" value="{{ $id }}" />
    <div class="row">
        <div class="col-md-6">
            <div class="card elevation-0 border">
                <div class="card-header py-2 bg-body text-secondary">
                    <label class="sourcesansrg h6 p-0 m-0">Registrar datos personales</label>
                </div>
                <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-6 text-secondary">
                        <label class="mb-0">D.N.I.</label><span class="text-danger font-weight-bold">*</span>
                        <input value="{{ $usuario->dni }}" autocomplete="off" id="dni-edit" placeholder="Número de documento" type="text" class="form-control sourcesansrg " maxlength="8" onKeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="mb-0 text-secondary">Nombre(s)</label><span class="text-danger font-weight-bold">*</span>
                        <input value="{{ $usuario->nombres }}" autocomplete="off" id="nombre-edit" placeholder="" type="text" class="form-control sourcesansrg " required>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class="mb-0 text-secondary">Apellido Paterno</label><span class="text-danger font-weight-bold">*</span>
                        <input value="{{ $usuario->apel_pat }}" autocomplete="off" id="apel-pat-edit" placeholder="" type="text" class="form-control sourcesansrg " required>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="mb-0 text-secondary">Apellido Materno</label><span class="text-danger font-weight-bold">*</span>
                        <input value="{{ $usuario->apel_mat }}" autocomplete="off" id="apel-mat-edit" placeholder="" type="text" class="form-control sourcesansrg " required>
                    </div>
                </div>
                <div class="form-group  mb-0 ">
                    <label class="mb-0 text-secondary">Correo electrónico</label><span class="text-danger font-weight-bold">*</span>
                    <input value="{{ $usuario->email }}" autocomplete="off" id="email-edit" placeholder="" type="email" class="form-control sourcesansrg " required>
                </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card elevation-0 border">
                <div class="card-header py-2 bg-body text-secondary">
                    <label class="sourcesansrg h6 p-0 m-0">Registrar datos inicio sesión</label>
                </div>
                <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="mb-0 text-secondary">Rol usuario</label><span class="text-danger font-weight-bold">*</span>
                            <select class="form-control sourcesansrg" id="slt-rol-edit">
                                <option>--- Seleccionar rol ---</option>
                                @foreach( $roles as $rol )
                                @if( $rol->id != '1')
                                <option value="{{ $rol->id }}" <?php if ($rol->id == $usuario->rol_id) echo "selected"; ?>> {{ $rol->nombre }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- <input type="hidden" id="key-edit" value="{{ $usuario->password }}" /> -->
                    <div class="form-group mb-0 col-md-6">
                        <label class="mb-0  mb-0 ">Contraseña</label><span class="text-danger font-weight-bold">*</span>
                        <input autocomplete="off" id="password-edit" placeholder="" type="password" class="form-control sourcesansrg ">
                    </div>
                    <div class="form-group mb-0 col-md-6">
                        <label class="mb-0  mb-0">Confirme contraseña</label><span class="text-danger font-weight-bold">*</span>
                        <input autocomplete="off" id="re-password-edit" placeholder="" type="password" class="form-control sourcesansrg ">
                    </div>
                </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="text-right">
                        <button type="submit" class="btn btn-bluedark text-white sourcesansrg fs-13 m-0 rounded-1">
                            <i class="fas fa-save mr-1"></i></i>Guardar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    $("#frm-save-user-edit").on("submit", function (e) {
        e.preventDefault();
        var pass = $("#password-edit").val();
        var repass = $("#re-password-edit").val();

        if (pass.length != 0 && repass.length != 0) {
            // Actualizar clave
            if (pass == repass) {
                // Actualizar datos
            var dni = $("#dni-edit").val();
            var dni_old = $("#dni-edit-old").val();
            var nombres = $("#nombre-edit").val();
            var apel_pat = $("#apel-pat-edit").val();
            var apel_mat = $("#apel-mat-edit").val();
            var email = $("#email-edit").val();
            var email_old = $("#email-edit-old").val();
            var rol = $("#slt-rol-edit").val();
            var key_edit = $("#key-edit").val();

            var data = {
                dni: dni,
                dni_old: dni_old,
                nombres: nombres,
                apel_pat: apel_pat,
                apel_mat: apel_mat,
                email: email,
                email_old: email_old,
                rol: rol,
                key: key_edit,
                pass: pass
            };

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: "{!! url('api/user-update-pass') !!}",
                data: data,
            }).done(function (msg) {
                console.log(msg);
                if (msg == '402') {
                    getListarUsuarios();
                    hiddenTabEditar(0);

                    $("#user-edit-desc").removeClass('active');
                    $("#user-edit-desc").removeClass('active show');

                    $("#user-list-tab").addClass('active');
                    $("#user-list-desc").addClass('active show');

                    toastr.success('Se actualizó correctamente.', 'AIDA',{ "positionClass": "toast-top-right" });
                    $("#dni-edit").val("");
                    $("#nombre-edit").val("");
                    $("#apel-pat-edit").val("");
                    $("#apel-mat-edit").val("");
                    $("#email-edit").val("");
                    $("#slt-rol-edit option[value='']").attr('selected', true);
                    $("#password-edit").val("");
                    $("#re-password-edit").val("");
                }

                if (msg == '101') {
                    toastr.warning('Email registrado.', 'AIDA', { "positionClass": "toast-top-right" });
                    $("#email-edit").focus();
                }

                if (msg == '202') {
                    toastr.warning('Dni registrado.', 'AIDA', { "positionClass": "toast-top-right" });
                    $("#dni-edit").focus();
                }

                if (msg == '303') {
                    toastr.error('Error al guardar.', 'AIDA', { "positionClass": "toast-top-right" });
                    $("#dni").focus();
                }

            }).fail(function (jqXHR, textStatus, errorThrown) {
                // Mostramos en consola el mensaje con el error que se ha producido
                $("#consola").html("The following error occured: " + textStatus + " " + errorThrown);
            });

            } else {
                toastr.error('Las contraseñas son diferentes.', 'AIDA',{ "positionClass": "toast-top-right" });
                $("#re-password-edit").val("");
                $("#re-password-edit").focus();
            }
        } else {
            // Actualizar datos
            var dni = $("#dni-edit").val();
            var dni_old = $("#dni-edit-old").val();
            var nombres = $("#nombre-edit").val();
            var apel_pat = $("#apel-pat-edit").val();
            var apel_mat = $("#apel-mat-edit").val();
            var email = $("#email-edit").val();
            var email_old = $("#email-edit-old").val();
            var rol = $("#slt-rol-edit").val();
            var key_edit = $("#key-edit").val();

            var data = {
                dni: dni,
                dni_old: dni_old,
                nombres: nombres,
                apel_pat: apel_pat,
                apel_mat: apel_mat,
                email: email,
                email_old: email_old,
                rol: rol,
                key: key_edit
            };

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: "{!! url('api/user-update') !!}",
                data: data,
            }).done(function (msg) {
                console.log(msg);
                if (msg == '402') {
                    getListarUsuarios();
                    hiddenTabEditar(0);

                    $("#user-edit-desc").removeClass('active');
                    $("#user-edit-desc").removeClass('active show');

                    $("#user-list-tab").addClass('active');
                    $("#user-list-desc").addClass('active show');

                    toastr.success('Se actualizó correctamente.', 'AIDA',{ "positionClass": "toast-top-right" });
                    $("#dni-edit").val("");
                    $("#nombre-edit").val("");
                    $("#apel-pat-edit").val("");
                    $("#apel-mat-edit").val("");
                    $("#email-edit").val("");
                    $("#slt-rol-edit option[value='']").attr('selected', true);
                    $("#password-edit").val("");
                    $("#re-password-edit").val("");
                }

                if (msg == '101') {
                    toastr.warning('Email registrado.', 'AIDA', { "positionClass": "toast-top-right" });
                    $("#email-edit").focus();
                }

                if (msg == '202') {
                    toastr.warning('Dni registrado.', 'AIDA', { "positionClass": "toast-top-right" });
                    $("#dni-edit").focus();
                }

                if (msg == '303') {
                    toastr.error('Error al guardar.', 'AIDA', { "positionClass": "toast-top-right" });
                    $("#dni").focus();
                }

            }).fail(function (jqXHR, textStatus, errorThrown) {
                // Mostramos en consola el mensaje con el error que se ha producido
                $("#consola").html("The following error occured: " + textStatus + " " + errorThrown);
            });
        }
    });
</script>

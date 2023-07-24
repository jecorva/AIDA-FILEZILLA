<form id="frm-save-user">
    <div class="row">
        <div class="col-md-6">
            <div class="card elevation-0 border">
                <div class="card-header py-2 bg-body text-secondary">
                    <label class="sourcesansrg h6 p-0 m-0">Registrar datos personales</label>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-6 text-label">
                            <label class="mb-0">D.N.I.</label><span class="text-danger font-weight-bold">*</span>
                            <input autocomplete="off" id="dni" placeholder="Número de documento" type="text"
                                class="form-control " maxlength="8"
                                onKeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;"
                                required>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="mb-0 text-label">Nombre(s)</label><span
                                class="text-danger font-weight-bold">*</span>
                            <input autocomplete="off" autocapitalize id="nombre" placeholder="" type="text"
                                class="form-control " required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="mb-0 text-label">Apellido Paterno</label><span
                                class="text-danger font-weight-bold">*</span>
                            <input autocomplete="off" autocapitalize id="apel-pat" placeholder="" type="text"
                                class="form-control " required>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="mb-0 text-label">Apellido Materno</label><span
                                class="text-danger font-weight-bold">*</span>
                            <input autocomplete="off" autocapitalize id="apel-mat" placeholder="" type="text"
                                class="form-control " required>
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label class="mb-0 text-label">Correo electrónico</label><span
                            class="text-danger font-weight-bold">*</span>
                        <input autocomplete="off" id="email" placeholder="" type="email" class="form-control " required>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-label elevation-0 border">
                <div class="card-header py-2 bg-body text-secondary">
                    <label class="sourcesansrg h6 p-0 m-0">Registrar datos inicio sesión</label>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="mb-0 text-label">Rol usuario</label><span
                                    class="text-danger font-weight-bold">*</span>
                                <select class="form-control opensans fs-14" id="slt-rol" required>
                                    <option selected>Seleccionar</option>
                                    @foreach( $roles as $rol )
                                    @if( $rol->id != '1' )
                                    <option value="{{ $rol->id }}"> {{ $rol->nombre }}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group mb-0 col-md-6">
                            <label class="mb-0  ">Contraseña</label><span class="text-danger font-weight-bold">*</span>
                            <input autocomplete="off" id="password" placeholder="" type="password" class="form-control "
                                required>
                        </div>
                        <div class="form-group mb-0 col-md-6">
                            <label class="mb-0   ">Confirme contraseña</label><span
                                class="text-danger font-weight-bold">*</span>
                            <input autocomplete="off" id="re-password" placeholder="" type="password"
                                class="form-control " required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="text-right">
                        <button type="submit" class="btn btn-bluedark text-white sourcesansrg fs-13 m-0 rounded-1">
                            <i class="fas fa-save mr-1"></i></i>Registrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $("#frm-save-user").on("submit", function (e) {
        e.preventDefault();
        var pass = $("#password").val();
        var repass = $("#re-password").val();

        if (pass == repass) {
            var dni = $("#dni").val();
            var nombres = $("#nombre").val();
            var apel_pat = $("#apel-pat").val();
            var apel_mat = $("#apel-mat").val();
            var email = $("#email").val();
            var rol = $("#slt-rol").val();

            var data = {
                dni: dni,
                nombres: nombres,
                apel_pat: apel_pat,
                apel_mat: apel_mat,
                email: email,
                password: pass,
                rol: rol
            };

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: "{!! url('api/user-save') !!}",
                data: data,
            }).done(function (msg) {
                console.log(msg);
                if (msg == '402') {
                    getListarUsuarios();

                    $("#user-tab-create").removeClass('active');
                    $("#user-create-desc").removeClass('active show');

                    $("#user-tab-list").addClass('active');
                    $("#user-list-desc").addClass('active show');

                    // toastr.success('Creado exitosamente.', 'Usuario - Nuevo', { "positionClass": "toast-bottom-right" });
                    toastr.success('Usuario registrado.', 'AIDA',  { "positionClass": "toast-top-right" });
                    $("#dni").val("");
                    $("#nombre").val("");
                    $("#apel-pat").val("");
                    $("#apel-mat").val("");
                    $("#email").val("");
                    $("#slt-rol option[value='']").attr('selected', true);
                    $("#password").val("");
                    $("#re-password").val("");
                }

                if (msg == '101') {
                    toastr.warning('Email registrado.', 'AIDA', { "positionClass": "toast-top-right" });
                    $("#email").focus();
                }

                if (msg == '202') {
                    toastr.warning('Dni registrado.', 'AIDA', { "positionClass": "toast-top-right" });
                    $("#dni").focus();
                }

                if (msg == '303') {
                    toastr.error('Error al guardar.', 'AIDA', { "positionClass": "toast-top-right" });
                    $("#dni").focus();
                }

            }).fail(function (jqXHR, textStatus, errorThrown) {
                // Mostramos en consola el mensaje con el error que se ha producido
                // $("#consola").html("The following error occured: " + textStatus + " " + errorThrown);
            });

        } else {
            toastr.error('Las contraseñas son diferentes.', '',{ "positionClass": "toast-bottom-right" });
            $("#re-password").val("");
            $("#re-password").focus();
        }
    });
</script>

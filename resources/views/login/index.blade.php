<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="shortcut icon" type="image/png" href="{!! url('img/favicon.png') !!}" />
    <!-- Revisar link fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.1/css/all.min.css" integrity="sha512-gMjQeDaELJ0ryCI+FtItusU9MkAifCZcGq789FrzkiM49D8lbDhoaUaIX4ASU187wofMNlgBJ4ckbrXM9sE6Pg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title> AIDA | Iniciar Sesión</title>
</head>

<body class="body m-0 vh-100 row justify-content-center align-items-center">        
    <div id="contenedor" class="container bg-white seccion border border-5 border-primary montserrat">        
        <div class="row align-items-stretch">
            <div class="col bg-login d-none d-lg-block">

            </div>
            <div class="col p-3 border-left">                
                <div class="text-center">
                    <img src="{!! url('img/logo.png') !!}" width="100" alt="" class="img-thumbnail p-2">
                </div>                
                <p class="fw-bold text-center h3 sourcesans  mt-2 mb-0 border-letter text-danger">AIDA</p>
                <p class="c-grey text-center fs-14 sourcesans mt-0 text-secondary">[ Administración Integrada De Aplicaciones ]</p>
                <!-- [ LOGIN ] -->
                <form method="POST" class="px-3" id="form-login">
                    @csrf
                    <div class="mb-2">
                        <label for="email" class="fs-15 sourcesans fa-14 text-secondary">Correo electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text rounded-0" id="basic-addon1"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control opensans fs-14 fw-500" placeholder="usuario@ecosac.com.pe" name="email" id="email" value="{{ old('email') }}" autofocus>
                        </div>
                        @error('email')
                        <div class="text-danger sourcesans fs-12 mt-1"><i class="fas fa-info-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-2">
                        <label for="password" class="fs-15 text-secondary sourcesans">Contraseña</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text rounded-0" id="basic-addon1"><i class="fas fa-key"></i></span>
                            <input type="password" class="form-control opensans fw-500 fs-14" placeholder="Ingrese clave" name="password" id="password" value="{{ old('password') }}" autofocus>
                        </div>
                        @error('password')
                        <div class="text-danger sourcesans fs-12 mt-1"><i class="fas fa-info-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>                    
                    
                    <div class="row mt-3">
                        <div class="col-md-6 pt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" name="remenber">
                                <label class="form-check-label sourcesans" for="invalidCheck">
                                    Recuerdame
                                </label>
                                <div class="invalid-feedback">
                                    You must agree before submitting.
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <div class="d-grid">
                                    <button id="btnLogin" type="submit" class="btn btn-amber border border-1 border-dark sourcesans fw-bold fs-16"><i class="fas fa-sign-in-alt me-2"></i>Login</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center  text-secondary sourcesans">
                        <!-- <label class="c-grey fs-13">Sistema Web de Control de Tareas de Mantenimiento</label> -->
                        <label class="c-grey fs-13">&copy; 2023 Copyright | Derechos reservados <b>ECOSAC</b></label>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- [ JS ] -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>
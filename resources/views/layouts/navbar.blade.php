<nav class="navbar navbar-default navbar-static-top">
    <div class="container-fluid">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/') }}">
                Valición SIGA
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
          @if (!Auth::guest())

            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
              @if(Auth::user()->rol<3)

                <li><a href="{{ route('base.incompletos.index') }}"><i class="fa fa-warning text-danger"></i> Incompletos</a></li>
                @if(Auth::user()->rol <2)
                <li><a href="{{ route('base.ensasa.index') }}"><i class="fa fa-copy"></i> Ya en SASA</a></li>
                <li><a href="{{ route('base.validos.index') }}"><i class='fa fa-clock-o text-primary'></i> Pendientes de finalizar</a></li>
                <li><a href="{{ route('base.finalizados.index') }}"><i class="fa fa-paper-plane text-success"></i> Proceso finalizado</a></li>
                @endif
                @if(Auth::user()->rol==0)
                <li><a href="{{ route('importar.base') }}"><i class="fa fa-upload"></i> Importar validación</a></li>
                @endif
                @endif
                @if(Auth::user()->rol==0 || Auth::user()->rol==3)
                <li><a href="{{ url('/emision?emitido=0') }}"><i class="fa fa-credit-card"></i> Emisión de certificados</a></li>
                @if(Auth::user()->rol==3)
                <li><a href="{{ route('importar.certificados') }}"><i class="fa fa-upload"></i> Importar certificados</a></li>
                @endif
                @endif

            </ul>
            @endif
            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                @if (Auth::guest())
                    <li><a href="{{ url('/login') }}"> <i class="fa fa-btn fa-sign-in"></i>Iniciar sesión</a></li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <i class="fa fa-user"></i> {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('/user/password') }}"><i class="fa fa-key"></i> Cambiar contraseña</a></li>
                        </ul>
                    </li>
                    <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i> Salir</a></li>
                @endif
            </ul>
        </div>
    </div>
</nav>

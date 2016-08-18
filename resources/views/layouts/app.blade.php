<!DOCTYPE html>
<html lang="en">
  @include('layouts.header')
  <body id="app-layout">
    @include('layouts.navbar')
    @yield('content')
    @include('layouts.footer')
    @yield('scripts')
    <footer>
      <hr>
      <p class="text-center">
        <b>Instituto Veracruzano de Educación para los Adultos</b>
        <br>
        Sistema de validación de registros del SIGA
        <br>
        Desarrollado en el Depto. de Tecnologías de la Información, <b>2016</b>.
      </p>
    </footer>
  </body>
</html>

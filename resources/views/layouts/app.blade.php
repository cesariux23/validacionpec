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
        Sistema de validación de registros del SIGA, desarrollado en el Depto. de TI. por César Encarnación Mendoza, <b>2016</b>
      </p>
    </footer>
  </body>
</html>

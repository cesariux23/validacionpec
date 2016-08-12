@extends('layouts.app')
@section('content')
<div class="container">
  {!! Form::open(array('method' => 'get')) !!}
  <div>
    <div class="pull-right">
      <!--  -->
      <a href="{{route('base.export',['valido' => $valido,'cRFE' => old('cRFE'),'iCveCZ' => old('iCveCZ'),'cNombre' => old('cNombre'),'cPaterno' => old('cPaterno'),'cMaterno' => old('cMaterno'),'cNivel' => old('cNivel')])}}" class="btn btn-default" target="_blank"><i class="fa fa-download"></i> Exportar</a>
      <a href="{{route($ruta)}}" class="btn btn-danger"><i class="fa fa-times"></i> Limpiar</a>
      <button type="submit" class="btn btn-info"><i class="fa fa-filter"></i> Filtar</button>
    </div>
    <h1>{!! $titulo !!}</h1>
  </div>
  <div class="row">
    @if(Auth::user()->czs->count()!='1')
    <div class="form-group col-md-2">
      <div class="form-group">
        <label>CZ</label>
        {!! Form::select('iCveCZ', $coordinaciones,null,array('class'=>'form-control','onchange'=>"this.form.submit()")) !!}
      </div>
    </div>
    @endif
    <div class="form-group col-md-2">
      <div class="form-group">
        <label>Nivel</label>
        {!! Form::select('cNivel', array(''=>'-- Todos --', 'primaria' => 'primaria', 'secundaria' => 'secundaria'),null,array('class'=>'form-control','onchange'=>"this.form.submit()")) !!}
      </div>
    </div>
    <div class="form-group col-md-2">
      <div class="form-group">
        <label>RFE</label>
        {!! Form::text('cRFE',null,array('class'=>'form-control'))!!}
      </div>
    </div>
    <div class="form-group col-md-2">
      <div class="form-group">
        <label>Nombre</label>
        {!! Form::text('cNombre',null,array('class'=>'form-control'))!!}
      </div>
    </div>
    <div class="form-group col-md-2">
      <div class="form-group">
        <label>Paterno</label>
        {!! Form::text('cPaterno',null,array('class'=>'form-control'))!!}
      </div>
    </div>
    <div class="form-group col-md-2">
      <div class="form-group">
        <label>Materno</label>
        {!! Form::text('cMaterno',null,array('class'=>'form-control'))!!}
      </div>
    </div>
  </div>
  {!! Form::close() !!}
  <p>
    Resultados: {{$base->total()}} registros.
  </p>
  <table class="table">
    <thead>
      <tr>
        <th>
          Alta
        </th>
        <th>
          CZ
        </th>
        <th>
          RFE
        </th>
        <th>
          Nombre
        </th>
        <th>
          Nivel
        </th>
        <th>
          Calificación
        </th>
        <!-- <th>
          Estado del registro
        </th> -->
        <th>
          Acciones
        </th>
      </tr>
    </thead>
    <tbody>
      @foreach ($base as $registro)
        <tr>
          @include('partials.detalleregistro')
          <td id="{{$registro->cRFE}}">
            @if($registro->getEstado())
              @if($registro->valido==1 && $registro->verificado==1)
              @if($registro->emisioncertificado)
              <span class="label label-default">Procesado</span>
              @else
              <a href="{{route('validacion.edit', $registro->idValidacion)}}" class="btn btn-info ventanavalidar">Ver</a>
              @endif
              @elseif($registro->valido==1 && $registro->verificado==0)
              <a href="{{route('validacion.edit', $registro->idValidacion)}}" class="btn btn-info ventanavalidar">Verificar</a>
              @elseif($registro->valido>1)
              <a href="{{route('validacion.edit', $registro->idValidacion)}}" class="btn btn-info ventanavalidar">Detalles</a>
              @else
              <a href="{{route('validacion.edit', $registro->idValidacion)}}" class="btn btn-info ventanavalidar">Continuar</a>
              @endif
              <br>
              @if($registro->valido==1 && $registro->verificado==1)
              <span class="label label-success">Finalizado</span>
              @elseif($registro->valido==1)
              <span class="label label-default">Validado</span>
              @elseif($registro->valido==2)
              <span class="label label-danger">Incompleto</span>
              @elseif($registro->valido==3)
              <span class="label label-warning">En SASA</span>
              @endif
            @else
            <a href="{{route('base.pendientes.show', $registro->id)}}" class="btn btn-success ventanavalidar">Validar</a>
            @endif
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

  {!! $base->appends(['cRFE' => old('cRFE'),'cNombreCZ' => old('iCveCZ'),'cNombre' => old('cNombre'),'cPaterno' => old('cPaterno'),'cMaterno' => old('cMaterno'),'cNivel' => old('cNivel')])->render() !!}
</div>
@endsection
@section('scripts')
<script type="text/javascript">

$('.ventanavalidar').on('click',function(event){
    event.preventDefault(event);
    e=event.currentTarget;
    //var myWindow = window.open(e.href,'Validación de registro', "width=200, height=100,width=750,height=780");
    var myWindow = window.open(e.href,'Validación de registro', "width=750,height=600");

    $(e).parent().html("Trabajando ..");
    //return false;
  });
</script>
@endsection

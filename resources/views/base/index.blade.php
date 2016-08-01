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
    <div class="form-group col-md-2">
      <div class="form-group">
        <label>CZ</label>
        {!! Form::select('iCveCZ', $coordinaciones,null,array('class'=>'form-control','onchange'=>"this.form.submit()")) !!}
      </div>
    </div>
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
  <hr>
  <p>
    Resultados: {{$base->total()}} registros.
  </p>
  <table class="table">
    <thead>
      <tr>
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
          Oportunidad
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
          <td>
            <b>{{$registro->iCveCZ}}</b> -- {{$registro->cNombreCZ}}
          </td>
          <td>
            {{$registro->cRFE}}
          </td>
          <td>
            {{$registro->cNombre}}
            {{$registro->cPaterno}}
            {{$registro->cMaterno}}
          </td>
          <td>
            <h4>
            <span class=" label
              @if($registro->cNivel =='primaria')
              {{'label-info'}}
              @else
              {{'label-primary'}}
              @endif
              ">
              {{$registro->cNivel}}
            </span>
          </h4>

          </td>
          <td>
            <b class="
              @if($registro->cOportunidad =='Primera')
              {{'text-success'}}
              @else
              {{'text-warning'}}
              @endif
              ">
              {{$registro->cOportunidad}}
            </b>
          </td>
          <td>
            @if($registro->dCalFinal)
              <h4>
                @if($registro->dCalFinal >=6)
                  <span class="label label-default">
                @else
                  <pan  class="label label-warning">
                @endif
                {{$registro->dCalFinal}}
                </span>
              </h4>
            @endif
          </td>
          <td id="{{$registro->cRFE}}">
            @if($registro->getEstado())
              @if($registro->valido>=1)
              <a href="{{route('validacion.edit', $registro->idValidacion)}}" class="btn btn-info" onclick="nuevaVentana()">Ver</a>
              @else
              <a href="{{route('validacion.edit', $registro->idValidacion)}}" class="btn btn-info" onclick="nuevaVentana()">Continuar</a>
              @endif
              <br>
              @if($registro->valido==1)
              <span class="label label-success">Validado</span>
              @elseif($registro->valido==2)
              <span class="label label-danger">Incompleto</span>
              @elseif($registro->valido==3)
              <span class="label label-warning">En SASA</span>
              @endif
            @else
            <a href="{{route('base.pendientes.show', $registro->id)}}" class="btn btn-success" onclick="nuevaVentana()">Validar</a>
            @endif
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

  {!! $base->appends(['cRFE' => old('cRFE'),'cNombreCZ' => old('iCveCZ'),'cNombre' => old('cNombre'),'cPaterno' => old('cPaterno'),'cMaterno' => old('cMaterno'),'cNivel' => old('cNivel')])->render() !!}
</div>
<script type="text/javascript">
  function nuevaVentana() {
    event.preventDefault()
    e=event.currentTarget;
    var myWindow = window.open(e.href,'Validación de registro', "width=200, height=100,width=750,height=850");
    $(e).parent().html("Trabajando ..");
  }


</script>
@endsection

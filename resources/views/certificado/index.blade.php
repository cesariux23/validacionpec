@extends('layouts.app')

@section('content')
<div class="container">
  {!! Form::open(array('method' => 'get')) !!}
  <div>
    <div class="pull-right">
      <a href="{{route('emision.export',['rfe' => old('rfe'),'nombrecz' => old('nombrecz'),'emitido' => old('emitido'),'calificacion' => old('nivel'),'fecha' => old('fecha')])}}" class="btn btn-default" target="_blank"><i class="fa fa-download"></i> Exportar</a>
      <a href="{{url('/emision?emitido=0')}}" class="btn btn-danger"><i class="fa fa-times"></i> Limpiar</a>
      <button type="submit" class="btn btn-info"><i class="fa fa-filter"></i> Filtar</button>
    </div>
    <h1>Emisión de certificados SIGA</h1>
  </div>
  <div class="row">
    <div class="form-group col-md-3">
      <div class="form-group">
        <label>CZ</label>
        {!! Form::select('nombrecz', $coordinaciones,null,array('class'=>'form-control','onchange'=>"this.form.submit()")) !!}
      </div>
    </div>
    <div class="form-group col-md-2">
      <div class="form-group">
        <label>Nivel</label>
        {!! Form::select('nivel', array(''=>'-- Todos --', 'primaria' => 'primaria', 'secundaria' => 'secundaria'),null,array('class'=>'form-control','onchange'=>"this.form.submit()")) !!}
      </div>
    </div>
    <div class="form-group col-md-2">
      <div class="form-group">
        <label>estado del certificado</label>
        {!! Form::select('emitido', array('t'=>'-- Todos --', '0' => 'Pendiente', '1' => 'Emitido','2' => 'Cancelado'),null,array('class'=>'form-control','onchange'=>"this.form.submit()")) !!}
      </div>
    </div>
    <div class="form-group col-md-2">
      <div class="form-group">
        <label>Fecha de conclusión</label>
        {!! Form::select('fecha', $fechas,null,array('class'=>'form-control','onchange'=>"this.form.submit()")) !!}
      </div>
    </div>
    <div class="form-group col-md-3">
      <div class="form-group">
        <label>RFE</label>
        {!! Form::text('rfe',null,array('class'=>'form-control'))!!}
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
          Nivel/<br>
          Oportunidad
        </th>
        <th>
          Calificación
        </th>
        <th>
          Fecha de <br> conclusión
        </th>
        <th>
          Estado del <br> certificado
        </th>
        <th width="100px">
          Acciones
        </th>
        <!-- <th>
          <i class="fa fa-check-square-o fa-2x"></i>
        </th> -->
      </tr>
    </thead>
    <tbody>
      @foreach ($base as $registro)
        <tr>
          <td>
            <span class="text-muted">{{date_format( date_create($registro->fFechaAlta), 'd/M/y')}}</span>
          </td>
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
              @if(strpos(strtolower($registro->cNivel),'primaria')!== false)
              {{'label-info'}}
              @else
              {{'label-primary'}}
              @endif
              ">
              {{$registro->cNivel}}
            </span>
          </h4>

              @if(strpos(strtolower($registro->cOportunidad),'primera')!== false)
              <b class="text-success">Primera
              @else
              <b class="text-warning">{{$registro->cOportunidad}}
              @endif
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
          <td>
            {{date_format( date_create($registro->fConclusion), 'd/M/y')}}
          </td>
          @if($registro->idValidacion)
          <td>
            {{$registro->cEstatusCertificado}}
            <h4 id="span_{{$registro->idValidacion}}">
              @if($registro->emisioncertificado==1)
              <span class="label label-success">Emitido</span>
              @elseif($registro->emisioncertificado==2)
              <span class="label label-danger">Canelado</span>
              @else
              <span class="label label-warning">Pendiente</span>
              @endif
            </h4>
          </td>
          <td id="$registro->idValidacion">
            {!! Form::open(array('route' => array('emision.update', $registro->idValidacion), 'method'=>'put')) !!}
            @if($registro->emisioncertificado)
            <button type="submit" class="btn btn-danger" title="Cancelar el certificado" name="emision" value="2"> <i class="fa fa-times"></i></button>
            <button type="submit" class="btn btn-info" title="Reimprimir el certificado" name="emision" value="0"> <i class="fa fa-refresh"></i></button>
            @else
            <button type="submit" class="btn btn-success" title="marcar como emitido" name="emision" value="1"> <i class="fa fa-check"></i></button>
            @endif
            {!! Form::close() !!}
          </td>
          @elseif($registro->cEstatusCertificado!='')
          <td>
            <h4>
              @if(strtolower($registro->cEstatusCertificado)=='emitido')
              <span class="label label-success">Emitido</span>
              <br>
              <small>{{$registro->cFolioCertificado}}</small>
              @elseif(strtolower($registro->cEstatusCertificado)=='cancelado')
              <span class="label label-danger">Canelado</span>
              @else
              <span class="label label-warning">Pendiente</span>
              @endif
            </h3>
          </td>
          <td>
            <span class="text-muted">Registro de Power BI</span>
          </td>
          @else
          <td colspan="2">
            <span class="text-muted">Registro por validar</span>
          </td>
          @endif
          <!-- <td>
            <input type="checkbox" id="{{$registro->id}}" value="1">
          </td> -->
        </tr>
      @endforeach
    </tbody>
  </table>
  {!! $base->appends(['rfe' => old('rfe'),'nombrecz' => old('nombrecz'),'emitido' => old('emitido'),'calificacion' => old('nivel'),'fecha' => old('fecha')])->render() !!}
</div>
@endsection

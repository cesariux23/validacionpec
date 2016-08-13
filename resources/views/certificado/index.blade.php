@extends('layouts.app')
@section('content')
<div class="container">
  {!! Form::open(array('method' => 'get')) !!}
  <div>
    <div class="pull-right">
      <a href="{{route('emision.export',['rfe' => old('rfe'),'nombrecz' => old('nombrecz'),'emitido' => old('emitido'),'nivel' => old('nivel'),'fechaemision' => old('fechaemision'),'fechaconclusion' => old('fechaconclusion'),'rfe' => old('rfe'),'nombre' => old('nombre'),'paterno' => old('paterno'),'materno' => old('materno')])}}" class="btn btn-default" target="_blank"><i class="fa fa-download"></i> Exportar</a>
      <a href="{{url('/emision?emitido=0')}}" class="btn btn-danger"><i class="fa fa-times"></i> Limpiar</a>
      <button type="submit" class="btn btn-info"><i class="fa fa-filter"></i> Filtar</button>
    </div>
    <h1>Emisión de certificados SIGA</h1>
  </div>
  <div class="row">
    <div class="form-group col-md-3">
      <div class="form-group">
        <label>CZ</label>
        {!! Form::select('nombrecz', $coordinaciones,null,array('class'=>'form-control','onchange'=>"return post(event)")) !!}
      </div>
    </div>
    <div class="form-group col-md-2">
      <div class="form-group">
        <label>Nivel</label>
        {!! Form::select('nivel', array(''=>'-- Todos --', 'primaria' => 'primaria', 'secundaria' => 'secundaria'),null,array('class'=>'form-control','onchange'=>"return post(event)")) !!}
      </div>
    </div>
    <div class="form-group col-md-2">
      <div class="form-group">
        <label>estado del certificado</label>
        {!! Form::select('emitido', array('t'=>'-- Todos --', '0' => 'Pendiente', '1' => 'Emitido','2' => 'Cancelado'),null,array('class'=>'form-control','onchange'=>"return post(event)")) !!}
      </div>
    </div>
    <div class="form-group col-md-2">
      <div class="form-group">
        <label>Fecha de conclusión</label>
        {!! Form::select('fechaconclusion', $fechas,null,array('class'=>'form-control','onchange'=>"return post(event)",'id'=>'fechaconclusion')) !!}
      </div>
    </div>
    <div class="form-group col-md-2">
      <div class="form-group">
        <label>Fecha de emision</label>
        {!! Form::text('fechaemision',null,array('class'=>'form-control','id'=>'fechaemision'))!!}
      </div>
    </div>

  </div>
  <div class="row">
    <div class="form-group col-md-3">
      <div class="form-group">
        {!! Form::text('rfe',null,array('class'=>'form-control','placeholder'=>'RFE'))!!}
      </div>
    </div>
    <div class="form-group col-md-3">
      <div class="form-group">
        {!! Form::text('nombre',null,array('class'=>'form-control','placeholder'=>'Nombre'))!!}
      </div>
    </div>
    <div class="form-group col-md-3">
      <div class="form-group">
        {!! Form::text('paterno',null,array('class'=>'form-control','placeholder'=>'Paterno'))!!}
      </div>
    </div>
    <div class="form-group col-md-3">
      <div class="form-group">
        {!! Form::text('materno',null,array('class'=>'form-control','placeholder'=>'Materno'))!!}
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
          @include('partials.detalleregistro')
          <td>
            {{date_format( date_create($registro->fConclusion), 'd/M/y')}}
          </td>
          @if($registro->cEstatusCertificado!='')
          <td>
            <h4>
              @if(strtolower($registro->cEstatusCertificado)=='emitido')
              <span class="label label-success">Emitido</span>
              @elseif(strtolower($registro->cEstatusCertificado)=='entregado')
              <span class="label label-default">Entregado</span>
              @elseif(strtolower($registro->cEstatusCertificado)=='cancelado')
              <span class="label label-danger">Canelado</span>
              @else
              <span class="label label-warning">Pendiente</span>
              @endif
              @if($registro->fEmisionCertificado)
                <br>
                <small>
                  {{date_format( date_create($registro->fEmisionCertificado), 'd/M/y')}}
                  @if($registro->cFolioCertificado)
                  <br>
                  <b>{{$registro->cFolioCertificado}}</b>
                  @endif
                </small>
              @endif
            </h3>
          </td>
          <td>
            <span class="text-muted">Registro de Power BI</span>
          </td>
          @elseif($registro->idValidacion)
          <td>
            <h4 id="span_{{$registro->idValidacion}}">
              @if($registro->emisioncertificado==1)
              <span class="label label-success">Emitido</span>
                @if($registro->fechaemision)
                <br>
                <small>
                  {{$registro->fechaemision->format('d/M/y')}}
                  @if($registro->folio)
                  <br>
                  <b>{{$registro->folio}}</b>
                  @endif
                </small>
              @endif
              @elseif($registro->emisioncertificado==2)
              <span class="label label-danger">Canelado</span>
              @else
              <span class="label label-warning">Pendiente</span>
              <br>
              <small class="text-muted">{{date_format( date_create(), 'd/M/y')}}</small>
              @endif
            </h4>
          </td>
          <td id="$registro->idValidacion">
            {!! Form::open(array('route' => array('emision.update', $registro->idValidacion), 'method'=>'put')) !!}
            @if(Auth::user()->rol==3)
            @if($registro->emisioncertificado)
            <button type="submit" class="btn btn-danger" title="Cancelar el certificado" name="emision" value="2"> <i class="fa fa-times"></i></button>
            <button type="submit" class="btn btn-info" title="Reimprimir el certificado" name="emision" value="0"> <i class="fa fa-refresh"></i></button>
            @else
            <button type="submit" class="btn btn-success" title="marcar como emitido" name="emision" value="1"> <i class="fa fa-check"></i></button>
            @endif
            @else
            <span class="text-muted">No permitido</span>
            @endif
            {!! Form::close() !!}
          </td>
          @else
          <td colspan="2">
            <span class="text-muted">Registro pendiente de validar</span>
          </td>
          @endif
          <!-- <td>
            <input type="checkbox" id="{{$registro->id}}" value="1">
          </td> -->
        </tr>
      @endforeach
    </tbody>
  </table>
  {!! $base->appends(['rfe' => old('rfe'),'nombrecz' => old('nombrecz'),'emitido' => old('emitido'),'nivel' => old('nivel'),'fechaemision' => old('fechaemision'),'fechaconclusion' => old('fechaconclusion'),'rfe' => old('rfe'),'nombre' => old('nombre'),'paterno' => old('paterno'),'materno' => old('materno')])->render() !!}
</div>
@endsection
@section('scripts')
  <script type="text/javascript">
  function post(event) {
    console.log('hola!!');
    var obj=event.currentTarget;
    if(obj.name=='nombrecz' || obj.name=='emitido'){
      $('#fechaconclusion').val('');
      $('#fechaemision').val('');
    }
    obj.form.submit();
  }
  </script>
@endsection

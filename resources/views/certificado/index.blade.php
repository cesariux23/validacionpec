@extends('layouts.app')

@section('content')
<div class="container">
  {!! Form::open(array('method' => 'get')) !!}
  <div>
    <div class="pull-right">
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
        {!! Form::select('emitido', array(''=>'-- Todos --', '0' => 'Pendiente', '1' => 'Emitido','2' => 'Cancelado'),null,array('class'=>'form-control','onchange'=>"this.form.submit()")) !!}
      </div>
    </div>
    <div class="form-group col-md-2">
      <div class="form-group">
        <label>RFE</label>
        {!! Form::text('rfe',null,array('class'=>'form-control'))!!}
      </div>
    </div>
  </div>
  {!! Form::close() !!}
  <hr>
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
        <th>
          Estado del certificado
        </th>
        <th>
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
            <b>{{$registro->cz}}</b> -- {{$registro->nombrecz}}
          </td>
          <td>
            {{$registro->rfe}}
          </td>
          <td>
            {{$registro->nombre}}
            {{$registro->paterno}}
            {{$registro->materno}}
          </td>
          <td>
            <h4>
            <span class=" label
              @if($registro->nivel =='primaria')
              {{'label-info'}}
              @else
              {{'label-primary'}}
              @endif
              ">
              {{$registro->nivel}}
            </span>
          </h4>

          </td>
          <td>
            <b class="
              @if($registro->oportunidad =='Primera')
              {{'text-success'}}
              @else
              {{'text-warning'}}
              @endif
              ">
              {{$registro->oportunidad}}
            </b>
          </td>
          <td>
            @if($registro->calificacion)
              <h4>
                @if($registro->calificacion >=6)
                  <span class="label label-default">
                @else
                  <pan  class="label label-warning">
                @endif
                {{$registro->calificacion}}
                </span>
              </h4>
            @endif
          </td>
          <td>
            <h4 id="span_{{$registro->id}}">
              @if($registro->emisioncertificado==1)
              <span class="label label-success">Emitido</span>
              @elseif($registro->emisioncertificado==2)
              <span class="label label-danger">Canelado</span>
              @else
              <span class="label label-warning">Pendiente</span>
              @endif
            </h4>
          </td>
          <td>
            {!! Form::open(array('route' => array('emision.update', $registro->id), 'method'=>'put')) !!}
            @if($registro->emisioncertificado)
            <button type="submit" class="btn btn-danger" title="Cancelar el certificado" name="emision" value="2"> <i class="fa fa-times"></i></button>
            <button type="submit" class="btn btn-info" title="Reimprimir el certificado" name="emision" value="0"> <i class="fa fa-refresh"></i></button>
            @else
            <button type="submit" class="btn btn-success" title="marcar como emitido" name="emision" value="1"> <i class="fa fa-check"></i></button>
            @endif
            {!! Form::close() !!}
          </td>
          <!-- <td>
            <input type="checkbox" id="{{$registro->id}}" value="1">
          </td> -->
        </tr>
      @endforeach
    </tbody>
  </table>
  {!! $base->appends(['rfe' => old('rfe'),'nombrecz' => old('nombrecz'),'emitido' => old('emitido'),'calificacion' => old('nivel')])->render() !!}
</div>
@endsection

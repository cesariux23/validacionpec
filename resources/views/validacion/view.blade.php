@extends('layouts.only-content')
@section('content')
<div class="container">
    <h2>
    <div class="pull-right">
      @if($validacion->valido)
        @if($validacion->valido==1)
        <span class="label label-success"><i class="fa fa-check"></i> Validado</span>
        @elseif($validacion->valido==2)
        <span class="label label-danger"><i class="fa fa-warning"></i> Expediente incompleto</span>
        @else
        <span class="label label-warning"><i class="fa fa-copy"></i> Ya existente en SASA</span>
        @endif
      @else
      <span class="label label-default"><i class="fa fa-clock-o"></i> Pendiente</span>
      @endif
    </div>
    {{$validacion->rfe}}
  </h2>
  <h3><b class="text-muted">{{$validacion->nombre." ".$validacion->paterno." ".$validacion->materno}}</b></h3>
    CZ {{$validacion->nombrecz}} --
    <span class=" label
      @if(strtolower($validacion->nivel) =='primaria')
      {{'label-info'}}
      @else
      {{'label-primary'}}
      @endif
      ">
      {{$validacion->nivel}}
    </span>
  <hr>

{!! Form::open(array('route' => array('validacion.update', $validacion->id), 'method'=>'put')) !!}
@if($validacion->valido)
  <div>
    <div class="alert alert-success">
      <p>
        Validadado por: <b>{{$validacion->validadopor}}</b>.
      </p>
    </div>
  @if(Auth::user()->rol==0)
    <div class="form-group">
      <button type="submit" name="valido" value="0" class="btn btn-info btn-lg"><i class="fa fa-external-link"></i> Validar de nuevo</button>

    </div>
  @endif
  </div>
  <table class="table">
    <thead>
      <tr>
        <th>
          #
        </th>
        <th>
          Categoria
        </th>
        <th>
          Correcto
        </th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>
          1
        </td>
        <td>
          Datos personales
        </td>
        <td>
          @if($validacion->datospersonales==1)
          <span class="text-success"> <i class="fa fa-check"></i></span>
          @else
          <span class="text-danger"> <i class="fa fa-times"></i></span>
          @endif
        </td>
      </tr>
      <tr>
        <td>
          2
        </td>
        <td>
          CURP
        </td>
        <td>
          @if($validacion->curp==1)
          <span class="text-success"> <i class="fa fa-check"></i></span>
          @else
          <span class="text-danger"> <i class="fa fa-times"></i></span>
          @endif
        </td>
      </tr>
        @if($validacion->nivel=='secundaria')
      <tr>
        <td>
          3
        </td>
        <td>
          Certificado
        </td>
        <td>
          @if($validacion->certificado==1)
          <span class="text-success"> <i class="fa fa-check"></i></span>
          @else
          <span class="text-danger"> <i class="fa fa-times"></i></span>
          @endif
        </td>
      </tr>
      @endif
      <tr>
        <td>
          4
        </td>
        <td>
          Foto
        </td>
        <td>
          @if($validacion->foto==1)
          <span class="text-success"> <i class="fa fa-check"></i></span>
          @else
          <span class="text-danger"> <i class="fa fa-times"></i></span>
          @endif
        </td>
      </tr>
      <tr>
        <td>
          5
        </td>
        <td>
          Rubrica Autoevaluación
        </td>
        <td>
          @if($validacion->autoevaluacion==1)
          <span class="text-success"> <i class="fa fa-check"></i></span>
          @else
          <span class="text-danger"> <i class="fa fa-times"></i></span>
          @endif
        </td>
      </tr>
      <tr>
        <td>
          6
        </td>
        <td>
          Rubrica Terceros
        </td>
        <td>
          @if($validacion->terceros==1)
          <span class="text-success"> <i class="fa fa-check"></i></span>
          @else
          <span class="text-danger"> <i class="fa fa-times"></i></span>
          @endif
        </td>
      </tr>
      <tr>
        <td>
          7
        </td>
        <td>
          Rubrica aprendizaje no formal
        </td>
        <td>
          @if($validacion->aprendizaje==1)
          <span class="text-success"> <i class="fa fa-check"></i></span>
          @else
          <span class="text-danger"> <i class="fa fa-times"></i></span>
          @endif
        </td>
      </tr>
    </tbody>
  </table>
  @else
  <div class="row">
    <div class="alert alert-info col-xs-10">
      <p>
        <b>Importante:</b>
        Al finalizar la validación, este registro estará listo para la impresión del certificado, los apartados siguientes ya no podrán ser modificados.
      </p>
    </div>
    <div class="col-xs-2">
      <button type="button" class="btn btn-primary" onclick="marcarTodo()" id="todos"> <i class="fa fa-check-square-o fa-3x"></i></button>
    </div>
  </div>

  <table class="table">
    <thead>
      <tr>
        <th>
          #
        </th>
        <th>
          Categoria
        </th>
        <th>
          Correcto
        </th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>
          1
        </td>
        <td>
          Datos personales
        </td>
        <td>
          <button type="button" name="datospersonales" class="btn {{$validacion->datospersonales? 'btn-success':'btn-default'}} check" value="{{$validacion->datospersonales}}" onclick="postValidacion()"><i class="fa fa-{{$validacion->datospersonales? 'check':'square-o'}}"></i></button>
        </td>
      </tr>
      <tr>
        <td>
          2
        </td>
        <td>
          CURP
        </td>
        <td>
          <button type="button" name="curp" class="btn {{$validacion->curp? 'btn-success':'btn-default'}} check" value="{{$validacion->curp}}" onclick="postValidacion()"><i class="fa fa-{{$validacion->curp? 'check':'square-o'}}"></i></button>
        </td>
      </tr>
      @if(strtolower($validacion->nivel)=='secundaria')
      <tr>
        <td>
          3
        </td>
        <td>
          Certificado
        </td>
        <td>
          <button type="button" name="certificado" class="btn {{$validacion->certificado? 'btn-success':'btn-default'}} check" value="{{$validacion->certificado}}" onclick="postValidacion()"><i class="fa fa-{{$validacion->certificado? 'check':'square-o'}}"></i></button>
        </td>

      </tr>
      @endif
      <tr>
        <td>
          4
        </td>
        <td>
          Foto
        </td>
        <td>
          <button type="button" name="foto" class="btn {{$validacion->foto? 'btn-success':'btn-default'}} check" value="{{$validacion->foto}}" onclick="postValidacion()"><i class="fa fa-{{$validacion->foto? 'check':'square-o'}}"></i></button>
        </td>
      </tr>
      <tr>
        <td>
          5
        </td>
        <td>
          Rubrica Autoevaluación
        </td>
        <td>
          <button type="button" name="autoevaluacion" class="btn {{$validacion->autoevaluacion? 'btn-success':'btn-default'}} check" value="{{$validacion->autoevaluacion}}" onclick="postValidacion()"><i class="fa fa-{{$validacion->autoevaluacion? 'check':'square-o'}}"></i></button>
        </td>
      </tr>
      <tr>
        <td>
          6
        </td>
        <td>
          Rubrica Terceros
        </td>
        <td>
          <button type="button" name="terceros" class="btn {{$validacion->terceros? 'btn-success':'btn-default'}} check" value="{{$validacion->terceros}}" onclick="postValidacion()"><i class="fa fa-{{$validacion->terceros? 'check':'square-o'}}"></i></button>
        </td>
      </tr>
      <tr>
        <td>
          7
        </td>
        <td>
          Rubrica aprendizaje no formal
        </td>
        <td>
          <button type="button" name="aprendizaje" class="btn {{$validacion->aprendizaje? 'btn-success':'btn-default'}} check" value="{{$validacion->aprendizaje}}" onclick="postValidacion()"><i class="fa fa-{{$validacion->aprendizaje? 'check':'square-o'}}"></i></button>
        </td>
      </tr>
    </tbody>
  </table>
  @endif

  @if($validacion->valido != 1)
    <div class="form-group">
      <label>Observaciones</label>
      <div>
        {!! Form::textarea('observaciones',$validacion->observaciones,['class'=>'form-control','rows'=>'3']) !!}
      </div>
    </div>
    <div class="form-group">
    @endif
    @if($validacion->valido)
    @if($validacion->valido>1)
    <button type="submit" class="btn btn-default"><i class="fa fa-refresh"></i> Actualizar observaciones</button>
    @endif
    @else
    <button type="submit" id="valido" name="valido" value="1" class="btn btn-success" {{($validacion->datospersonales && $validacion->curp && $validacion->terceros && $validacion->autoevaluacion && $validacion->certificado && $validacion->foto && $validacion->aprendizaje) ? "":"disabled"}}><i class="fa fa-save"></i> Finalizar validación</button>
    <button type="submit" id="incompleto" name="valido" value="2" class="btn btn-danger" {{($validacion->datospersonales && $validacion->curp && $validacion->terceros && $validacion->autoevaluacion && $validacion->certificado && $validacion->foto && $validacion->aprendizaje) ? "disabled":""}}><i class="fa fa-warning"></i> Expediente incompleto</button>
    <button type="submit" name="valido" value="3" class="btn btn-warning" {{ $validacion->valido ? "disabled" :""}}><i class="fa fa-copy"></i> Ya existente en SASA</button>
    @endif
  </div>
{!! Form::close() !!}

</div>
@endsection

@section('scripts')
<script type="text/javascript">
  var url="{{route('validacion.update', $validacion->id)}}";
  function postValidacion(e) {
    e=event.currentTarget;
    var data={};
    if(e.value==0){
      e.value=1;
    }
    else {
      e.value=0;
    }
    data[e.name]=e.value;
    console.log(e.value);

    $(e).removeClass('btn-success');
    $(e).removeClass('btn-default');
    $(e).addClass('btn-info');
    $(e).prop( "disabled", true );
    $(e).html('<i class="fa fa-circle-o-notch fa-spin"></i>');
    $.ajax({
      method: 'PUT',
      url: url,
      context: e,
      data: data
    })
    .done(function( data ) {
      $(e).prop( "disabled", false );
      $(e).removeClass('btn-info');
      if($(e).val()==0){
        $(e).html('<i class="fa fa-square-o"></i>');
        $(e).addClass('btn-default');
      }
      else {
        $(e).html('<i class="fa fa-check"></i>');
        $(e).addClass('btn-success');
      }
       $('#valido').prop('disabled',!data.valido);
       $('#incompleto').prop('disabled',data.valido);
    });
  }

  function marcarTodo() {
    $('.check').each(  function (i,b) {
      console.log(b);
      b.click();
    });
  }
</script>
@endsection

@extends('layouts.only-content')
@section('content')
<div class="container-fluid">
  <div id="msj">
  </div>
{!! Form::open(array('route' => array('validacion.update', $validacion->id), 'method'=>'put')) !!}
    <div>
        <h2>
        <div class="pull-right">
          @if($validacion->valido)
            @if($validacion->valido==1)
              @if($validacion->verificado==1)
              <span class="label label-success"><i class="fa fa-check"></i> Finalizado</span>
              @else
              <span class="label label-info"><i class="fa fa-clock-o"></i> Pendiente de finalizar</span>
              @endif
            @elseif($validacion->valido==2)
            <span class="label label-danger"><i class="fa fa-warning"></i> Expediente incompleto</span>
            @else
            <span class="label label-warning"><i class="fa fa-copy"></i> Ya existente en SASA</span>
            @endif
          @else
          <span class="label label-default"><i class="fa fa-clock-o"></i> Pendiente</span>
          <br><br>
          <button type="submit" class="btn btn-default"  name="todos" value="1"> <i class="fa fa-check text-success"></i> Marcar todos</button>
          @endif
          @if($validacion->emisioncertificado=='1')
          <br>
          <br>
          <span class="text-success">Emitido</span>
          @elseif($validacion->emisioncertificado=='2')
          <br>
          <br>
          <span class="text-danger">Cancelado</span>
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
        @if($validacion->valido)
        <br><br>
          @if($validacion->valido>=1 && $validacion->validadopor!=null)
            <span class="label label-success"><i class="fa fa-check"></i> Validado por {{$validacion->validador->name}}</span>
            @if($validacion->verificado==1 && $validacion->verificadopor!=null)
              <span class="label label-default"><i class="fa fa-check-circle-o"></i> Finazizado por {{$validacion->verificador->name}}</span>
            @endif
          @endif
        @endif
    </div>
    <hr>
@if($validacion->valido)
  <div class="row">
    <div class="col-xs-6">
      <table class="table table-bordered table-condensed">
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
            <th>
              1
            </th>
            <td>
              Datos personales
            </td>
            <td>
              <span class="{{$validacion->datospersonales==1? 'text-success':'text-danger'}}"> <i class="fa fa-{{$validacion->datospersonales==1? 'check':'times'}}"></i></span>
            </td>
          </tr>
          <tr>
            <th>
              2
            </th>
            <td>
              CURP
              <br><b>{{$base->cCURP}}</b>
            </td>
            <td>
              <span class="{{$validacion->curp==1? 'text-success':'text-danger'}}"> <i class="fa fa-{{$validacion->curp==1? 'check':'times'}}"></i></span>
            </td>
          </tr>
            @if(strtolower($validacion->nivel)=='secundaria')
          <tr>
            <th>
              3
            </th>
            <td>
              Certificado
            </td>
            <td>
              <span class="{{$validacion->certificado==1? 'text-success':'text-danger'}}"> <i class="fa fa-{{$validacion->certificado==1? 'check':'times'}}"></i></span>
            </td>
          </tr>
          @endif
          <tr>
            <th>
              4
            </th>
            <td>
              Foto
            </td>
            <td>
              <span class="{{$validacion->foto==1? 'text-success':'text-danger'}}"> <i class="fa fa-{{$validacion->foto==1? 'check':'times'}}"></i></span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="col-xs-6">
      <table class="table table-bordered table-condensed">
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
            <th>
              5
            </th>
            <td>
              Rubrica Autoevaluación
            </td>
            <td>
              <span class="{{$validacion->autoevaluacion==1? 'text-success':'text-danger'}}"> <i class="fa fa-{{$validacion->autoevaluacion==1? 'check':'times'}}"></i></span>
            </td>
          </tr>
          <tr>
            <th>
              6
            </th>
            <td>
              Rubrica Terceros
            </td>
            <td>
              <span class="{{$validacion->terceros==1? 'text-success':'text-muted'}}"> <i class="fa fa-{{$validacion->terceros==1? 'check':'minus'}}"></i></span>
            </td>
          </tr>
          <tr>
            <th>
              7
            </th>
            <td>
              Rubrica aprendizaje no formal
            </td>
            <td>
              <span class="{{$validacion->aprendizaje==1? 'text-success':'text-muted'}}"> <i class="fa fa-{{$validacion->aprendizaje==1? 'check':'minus'}}"></i></span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  @else
  <div class="row">
    <div class="col-xs-6">
      <table class="table table-bordered table-condensed">
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
              <button type="button" name="datospersonales" class="btn {{$validacion->datospersonales? 'btn-success':'btn-default'}} check" value="{{$validacion->datospersonales}}" onclick="postValidacion(event)"><i class="fa fa-{{$validacion->datospersonales? 'check':'square-o'}}"></i></button>
            </td>
          </tr>
          <tr>
            <td>
              2
            </td>
            <td>
              CURP
              <br><b>{{$base->cCURP}}</b>
            </td>
            <td>
              <button type="button" name="curp" class="btn {{$validacion->curp? 'btn-success':'btn-default'}} check" value="{{$validacion->curp}}" onclick="postValidacion(event)"><i class="fa fa-{{$validacion->curp? 'check':'square-o'}}"></i></button>
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
              <button type="button" name="certificado" class="btn {{$validacion->certificado? 'btn-success':'btn-default'}} check" value="{{$validacion->certificado}}" onclick="postValidacion(event)"><i class="fa fa-{{$validacion->certificado? 'check':'square-o'}}"></i></button>
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
              <button type="button" name="foto" class="btn {{$validacion->foto? 'btn-success':'btn-default'}} check" value="{{$validacion->foto}}" onclick="postValidacion(event)"><i class="fa fa-{{$validacion->foto? 'check':'square-o'}}"></i></button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="col-xs-6">
      <table class="table table-bordered table-condensed">
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
              5
            </td>
            <td>
              Rubrica Autoevaluación
            </td>
            <td>
              <button type="button" name="autoevaluacion" class="btn {{$validacion->autoevaluacion? 'btn-success':'btn-default'}} check" value="{{$validacion->autoevaluacion}}" onclick="postValidacion(event)"><i class="fa fa-{{$validacion->autoevaluacion? 'check':'square-o'}}"></i></button>
            </td>
          </tr>
          <tr>
            <th>
              6
            </th>
            <td>
              Rubrica Terceros
            </td>
            <td>
              <button type="button" name="terceros" class="btn {{$validacion->terceros? 'btn-success':'btn-default'}} check" value="{{$validacion->terceros}}" onclick="postValidacion(event)"><i class="fa fa-{{$validacion->terceros? 'check':'square-o'}}"></i></button>
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
              <button type="button" name="aprendizaje" class="btn {{$validacion->aprendizaje? 'btn-success':'btn-default'}} check" value="{{$validacion->aprendizaje}}" onclick="postValidacion(event)"><i class="fa fa-{{$validacion->aprendizaje? 'check':'square-o'}}"></i></button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  @endif

    <div class="form-group">
      <label>Observaciones</label>
      <div>
        {!! Form::textarea('observaciones',$validacion->observaciones,['id'=>'observaciones', 'class'=>'form-control','rows'=>'2']) !!}
      </div>
    </div>
    <div class="form-group">
    @if($validacion->valido)
      @if($validacion->emisioncertificado==0)

          @if(Auth::user()->rol<=1 && $validacion->valido==1 && $validacion->verificado==0)
          <button type="submit" name="verificado" value="1" class="btn btn-info sub"><i class="fa fa-paper-plane"></i> Finalizar proceso</button>
          <button type="submit" name="valido" value="3" class="btn btn-warning sub"><i class="fa fa-copy"></i> Ya existente en SASA</button>
          @endif
          @if(Auth::user()->rol<2 && ($validacion->verificado || ($validacion->valido==3)) && !$validacion->emisioncertificado)
          <button type="submit" name="verificado" value="0" class="btn btn-primary"><i class="fa fa-external-link"></i> Verificar nuevamente</button>
          @endif
          @if( $validacion->valido<3 && !$validacion->verificado)
            <button type="submit" name="valido" value="0" class="btn btn-primary"><i class="fa fa-external-link"></i> Revalidar</button>
          @endif
      @endif
    <button type="submit" class="btn btn-default"><i class="fa fa-refresh"></i> Actualizar observaciones</button>
    @else
    <button type="submit" id="valido" name="valido" value="1" class="btn btn-success sub" {{($validacion->datospersonales && $validacion->curp && ( $validacion->aprendizaje || $validacion->terceros) && $validacion->autoevaluacion && $validacion->certificado && $validacion->foto) ? "":"disabled"}}><i class="fa fa-save"></i> Validar registro</button>
    <button type="submit" id="incompleto" name="valido" value="2" class="btn btn-danger sub" {{($validacion->datospersonales && $validacion->curp && ($validacion->aprendizaje || $validacion->terceros) && $validacion->certificado && $validacion->foto) ? "disabled":""}}><i class="fa fa-warning"></i> Expediente incompleto</button>
    @endif
  </div>
{!! Form::close() !!}
</div>
@endsection

@section('scripts')
<script src="/sockets/socket.io/socket.io.js"></script>
    <script>
      var socket = io.connect("{{URL::to('/')}}", {resource: 'sockets'});
      var registro = "{{$validacion->id}}";
      socket.on('connect', function() {

         socket.emit('join', registro);
      });

      socket.on('join',function (usuarios) {
        console.log(usuarios);
        if(usuarios>1){
          document.getElementById('msj').innerHTML='<div class="alert alert-danger">Existen <b>'+usuarios+' </b> usuarios validando este registro.</div>';
        }else{
          document.getElementById('msj').innerHTML="";
        }
      })
    </script>
<script type="text/javascript">
  var rfe='{{$validacion->rfe}}';
  var estado='{{$validacion->valido}}';
  var finalizado='{{$validacion->verificado}}';
  var url="{{route('validacion.update', $validacion->id)}}";
  function postValidacion(event) {
    e=event.currentTarget;
    var data={};
    if(e.value==0){
      e.value=1;
    }
    else {
      e.value=0;
    }
    data[e.name]=e.value;
    data['observaciones']=$('#observaciones').val();

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

  (function() {
    mensaje="Validando..";
    switch (estado) {
      case '0':
        mensaje="<i class='fa fa-clock-o'></i> Pendiente";
        break;
      case '1':
        mensaje="<i class='fa fa-check'></i> Validado";
        break;
      case '2':
        mensaje="<i class='fa fa-warning'></i> Incompleto";
        break;
      case '3':
        mensaje="<i class='fa fa-copy'></i> Ya en SASA";
        break;
      default:

    }
    if(finalizado=='1'){
      mensaje="<i class='fa fa-paper-plane'></i> Finalizado";
    }
    socket.emit('cambia estado', {rfe:rfe, mensaje:mensaje});

  })();
</script>
@endsection

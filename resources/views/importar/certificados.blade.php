@extends('layouts.app')

@section('content')
<div class="container">
  <h1 class="text-center"><i class="fa fa-credit-card text-muted"></i> Asistente de validación masiva de <b>certificados</b></h1>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Importar archivo</div>

                <div class="panel-body">
                  @if(isset($resultado))
                  <div class="alert alert-{{$resultado->tipo}}">
                    {!! $resultado->mensaje !!}
                  </div>
                  @endif
                  <div class="alert alert-info">
                    El archivo debe contener maximo 3 columnas:<br>
                    <ul>
                      <li>RFE</li>
                      <li>Nivel</li>
                      <li>Folio (opcional)</li>
                    </ul>
                  </div>
                      {!! Form::open(
                        array(
                            'route' =>'importar.certificados',
                            'class' => 'form',
                            'novalidate' => 'novalidate',
                            'files' => true)) !!}
                      <div class="form-group">
                        <label>Archivo CSV</label>
                        <input type="file" name="archivo" value="">
                      </div>
                      <div class="form-group">
                        <label>estado de la validación de los archivos</label>
                        <select class="form-control" name="emisioncertificado">
                          <option value="1">Emitido</option>
                          <option value="2">Cancelado</option>
                        </select>
                      </div>
                      <div class="form-group">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Importar registros</button>
                      </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

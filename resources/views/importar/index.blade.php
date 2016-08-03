@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Importar archivo</div>

                <div class="panel-body">
                  <h3>Asistente de validación masiva</h3>
                  @if(isset($resultado))
                  <div class="alert alert-{{$resultado->tipo}}">
                    {!! $resultado->mensaje !!}
                  </div>
                  @endif
                  <div class="alert alert-info">
                    El archivo debe contener maximo 2 columnas:<br>
                    <ul>
                      <li>RFE</li>
                      <li>Observaciones</li>
                    </ul>
                  </div>
                      {!! Form::open(
                        array(
                            'url' =>'/importar',
                            'class' => 'form',
                            'novalidate' => 'novalidate',
                            'files' => true)) !!}
                      <div class="form-group">
                        <label>Archivo CSV</label>
                        <input type="file" name="archivo" value="">
                      </div>
                      <div class="form-group">
                        <label>estado de la validación de los archivos</label>
                        <select class="form-control" name="valido">
                          <option value="1">valido</option>
                          <option value="2">incompleto</option>
                          <option value="2">Ya existe SASA</option>
                        </select>
                      </div>
                      <div class="form-group">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Importar validación</button>
                      </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

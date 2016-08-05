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
@if($registro->veces>1)
<span class="text-muted"><b>{{$registro->veces}}</b> registros.</span>
@endif

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

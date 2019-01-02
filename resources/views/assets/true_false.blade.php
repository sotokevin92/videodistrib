@if(is_null($valor))
<i title="No se puede determinar" class="fa fa-lg fa-exclamation-triangle text-warning"></i>
@else
    @php
    $valor = boolval($valor);
    @endphp
    @if ($valor === true)
    <i class="fa fa-lg fa-check text-success"></i>
    @else
    <i class="fa fa-lg fa-times text-danger"></i>
    @endif
@endif
@if(is_null($retrato))
<i title="No se puede determinar" class="fa fa-lg fa-exclamation-triangle text-warning"></i>
@else
    @php
    $retrato = boolval($retrato);
    @endphp
    @if ($retrato === true)
    <i title="Retrato" class="fa fa-arrows-v"></i>
    @else
    <i title="Apaisado" class="fa fa-arrows-h"></i>
    @endif
@endif

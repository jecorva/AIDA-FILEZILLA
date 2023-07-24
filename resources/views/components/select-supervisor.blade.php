<option value="0">Seleccionar</option>
@foreach( $values as $value )
    <?php
    $supervisor = $value->apel_pat. ' ' .$value->apel_mat. ' ' .$value->nombres;
    ?>
    <option value="{{ $value->id }}" class="text-uppercase">{{ $supervisor }}</option>
@endforeach

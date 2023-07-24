<option value="0">Seleccionar</option>
@foreach( $implements as $implement )
    <?php 
    $abby = '';
    if( $option == 'implements' ) {
        $abby = $implement->code_abby.' - ';
    }
    ?>
    <option value="{{ $implement->id }}" class="text-uppercase">{{ $abby }}{{ $implement->nombre }}</option>
@endforeach

<option value="0">Seleccionar</option>
@foreach( $subareas as $subarea )
    <option value="{{ $subarea->id }}" class="text-uppercase">{{ $subarea->nombre }}</option>
@endforeach

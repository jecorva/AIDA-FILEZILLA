<?php $nro = 1;
$opciones = json_decode($opMenu);
?>
@foreach($submenus as $submenu)
<div class="icheck-success">
    <?php
    $submenuId = $submenu->id;
    $checked = '';
    foreach($opciones as $opcion) {
        if( $submenuId == $opcion->id ) {
            if( $opcion->permission == '1' ) {
                $checked = 'checked';
            }
        }
    }
    ?>
    <input data-menu='submenu' type="checkbox" name="checkbox{{$nro}}" id="checkbox{{$nro}}" value="{{ $submenu->MenuSubmenuID }}" {{ $checked }}>
    <label for="checkbox{{$nro}}">
        <i class="fas fa-link mr-1"></i>{{ $submenu->name }}
    </label>
</div>
<?php $nro++ ?>
@endforeach

<script>
    $("#title-submenu").html('{!! $name !!}')
</script>

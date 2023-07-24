<div class="list-group">
    <button type="button" class="list-group-item list-group-item-action list-group-item-secondary" aria-current="true">
        <i class="fas fa-link mr-1"></i>Dashboard(default)
    </button>
    @foreach($menus as $menu )
        <button onclick="btnMenu('{{ $menu->id }}', '{{ $menu->name }}')" type="button" class="list-group-item list-group-item-action">
            <i class="fas fa-link mr-1"></i>{{ $menu->name }}
        </button>
    @endforeach
</div>

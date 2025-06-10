@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<form id="menuForm" action="{{ route('menus.store') }}" method="POST" enctype="multipart/form-data" class="menu-form">
    @csrf
    <div class="form-group">
        <label for="name">Nome do Menu:</label>
        <input type="text" name="name" id="name" required>
    </div>
    <div class="form-group">
        <label for="parent_id">Menu Pai:</label>
        <select name="parent_id" id="parent_id">
            <option value="">Nenhum</option>
            @foreach($menus as $menu)
                <option value="{{ $menu->id }}">{{ $menu->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="link">URL do Link:</label>
        <input type="url" name="link" id="link" required placeholder="https://exemplo.com">
    </div>
    <button type="submit" class="btn btn-primary">Adicionar Menu</button>
</form>

<h3>Menus Cadastrados</h3>
<ul class="menu-list-manage">
    @foreach($menus as $menu)
        <li>
            <div class="menu-info">
                <strong>{{ $menu->name }}</strong>
                <div class="menu-type">Link: {{ $menu->link }}</div>
                @if($menu->parent)
                    <div class="menu-type">Menu Pai: {{ $menu->parent->name }}</div>
                @endif
            </div>
            <div class="menu-actions">
                <button onclick="editMenu({{ $menu->id }})" class="btn btn-edit">Editar</button>
                <button onclick="deleteMenu({{ $menu->id }})" class="btn btn-danger">Excluir</button>
            </div>
        </li>
        @if($menu->children->count() > 0)
            <ul class="submenu-manage">
                @foreach($menu->children as $submenu)
                    <li>
                        <div class="menu-info">
                            <strong>{{ $submenu->name }}</strong>
                            <div class="menu-type">Link: {{ $submenu->link }}</div>
                        </div>
                        <div class="menu-actions">
                            <button onclick="editMenu({{ $submenu->id }})" class="btn btn-edit">Editar</button>
                            <button onclick="deleteMenu({{ $submenu->id }})" class="btn btn-danger">Excluir</button>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    @endforeach
</ul>

<script>
    function loadLinkMenuScripts() {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Configurar formulário de edição
        document.addEventListener('submit', function(e) {
            if (e.target.matches('form[action*="/menus/"]')) {
                e.preventDefault();
                
                const formData = new FormData(e.target);
                const method = e.target.querySelector('input[name="_method"]')?.value || 'POST';
                
                fetch(e.target.action, {
                    method: method,
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        reloadLinkModal();
                        reloadMenu();
                    }
                })
                .catch(error => {
                    console.error('Erro ao salvar menu:', error);
                    alert('Erro ao salvar menu. Por favor, tente novamente.');
                });
            }
        });
    }
</script> 
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Menu Principal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            background: #2c3e50;
            color: white;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        .sidebar-header {
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-header h1 {
            margin: 0;
            font-size: 1.5em;
            color: #ecf0f1;
        }
        .menu-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .menu-list li {
            margin-bottom: 10px;
            display: block;
            width: 100%;
        }
        .menu-list a {
            color: #ecf0f1;
            text-decoration: none;
            display: block;
            padding: 8px;
            border-radius: 4px;
            transition: background 0.3s;
        }
        .menu-list a:hover {
            background: rgba(255,255,255,0.1);
        }
        .submenu {
            list-style: none;
            padding-left: 20px;
            margin-top: 5px;
            width: 100%;
            display: block;
        }
        .submenu li {
            margin-bottom: 5px;
            width: 100%;
            display: block;
        }
        .submenu a {
            color: #bdc3c7;
            font-size: 0.9em;
        }
        .content {
            flex: 1;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-left: 20px;
            min-height: 400px;
            display: flex;
            flex-direction: column;
        }
        .content-header {
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .content-header h2 {
            margin: 0;
            color: #333;
            font-size: 24px;
        }
        .content-body {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .content-body img {
            max-width: 100%;
            max-height: 500px;
            object-fit: contain;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background 0.3s;
            cursor: pointer;
            border: none;
        }
        .btn:hover {
            background: #2980b9;
        }
        /* Estilos do Modal */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        .modal-overlay.show {
            display: flex;
        }
        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 800px;
            max-height: 80vh;
            overflow-y: auto;
            position: relative;
            margin: 0 auto;
            top: 50px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .modal-header h2 {
            margin: 0;
            color: #2c3e50;
        }
        .close-button {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #666;
        }
        .menu-form {
            margin-bottom: 20px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 4px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .menu-list-manage {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .menu-list-manage li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        .menu-list-manage li:last-child {
            border-bottom: none;
        }
        .submenu-manage {
            padding-left: 20px;
            border-left: 2px solid #eee;
            margin-left: 10px;
        }
        .menu-info {
            flex: 1;
        }
        .menu-actions {
            display: flex;
            gap: 5px;
        }
        .menu-type {
            font-size: 0.8em;
            color: #666;
            margin-top: 4px;
        }
        .btn-primary {
            background: #4CAF50;
            color: white;
        }
        .btn-primary:hover {
            background: #45a049;
        }
        .btn-edit {
            background: #2196F3;
            color: white;
        }
        .btn-edit:hover {
            background: #1976D2;
        }
        .btn-danger {
            background: #f44336;
            color: white;
        }
        .btn-danger:hover {
            background: #d32f2f;
        }
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .alert-success {
            background: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
        }
        .alert-danger {
            background: #ffebee;
            color: #c62828;
            border: 1px solid #ffcdd2;
        }
        .floating-buttons {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            z-index: 1000;
        }

        .floating-button {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }

        .floating-button:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        }

        .floating-button i {
            font-size: 24px;
        }

        .floating-button.image-button {
            background: #28a745;
        }

        .floating-button.link-button {
            background: #17a2b8;
        }

        .tooltip {
            position: absolute;
            right: 60px;
            background: rgba(0,0,0,0.8);
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            white-space: nowrap;
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }

        .floating-button:hover .tooltip {
            opacity: 1;
        }

        /* Estilos para o drag and drop */
        .drag-handle {
            cursor: move;
            color: #95a5a6;
            margin-right: 10px;
            opacity: 0.5;
            transition: opacity 0.2s;
        }

        .drag-handle:hover {
            opacity: 1;
        }

        .sortable-ghost {
            opacity: 0.4;
            background: #34495e;
        }

        .menu-item {
            display: block;
            width: 100%;
            padding: 0;
        }

        .menu-item > a {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            margin-left: 0;
            gap: 8px;
            min-width: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .menu-item > .submenu {
            width: 100%;
            margin-top: 10px;
            padding-left: 25px;
        }
    </style>
    @section('head')
        @parent
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    @endsection
</head>
<body>
        <div class="sidebar">
            <div class="sidebar-header">
                <h1>Menu Principal</h1>
            </div>
            <ul class="menu-list">
                <li>
                    <a href="javascript:void(0)" onclick="returnToHome()" style="color: #3498db; font-weight: bold;">
                        <i class="fas fa-home"></i> Início
                    </a>
                </li>
                <li style="margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px;">
                    <strong style="color: #95a5a6; font-size: 0.9em; display: block; margin-bottom: 10px;">MENUS CADASTRADOS</strong>
                </li>
            @forelse($parentMenus as $menu)
                <li class="menu-item" data-menu-id="{{ $menu->id }}">
                    <a href="javascript:void(0)" onclick="handleMenuClick('{{ $menu->link }}', '{{ $menu->name }}')">
                        <i class="fas fa-grip-vertical drag-handle"></i>
                        {{ $menu->name }}
                    </a>
                    @if($menu->children->count() > 0)
                        <ul class="submenu">
                            @foreach($menu->children as $submenu)
                                <li class="menu-item" data-menu-id="{{ $submenu->id }}">
                                    <a href="javascript:void(0)" onclick="handleMenuClick('{{ $submenu->link }}', '{{ $submenu->name }}')">
                                        <i class="fas fa-grip-vertical drag-handle"></i>
                                        {{ $submenu->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @empty
                <li>Nenhum menu cadastrado</li>
            @endforelse
            </ul>
        </div>
    <div class="content">
        <h1>Sistema de Protótipos</h1>
        <p>Use o menu lateral para navegar entre as páginas.</p>
    </div>
        
    <!-- Modal de Gerenciamento de Menus de Imagens -->
    <div id="menuModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Gerenciar Menus de Imagens</h2>
                <button class="close-button" onclick="closeMenuModal()">&times;</button>
            </div>
            <div id="modalContent">
                <!-- O conteúdo será carregado aqui via AJAX -->
            </div>
            </div>
        </div>

    <!-- Modal de Gerenciamento de Menus por Link -->
    <div id="linkMenuModal" class="modal-overlay">
            <div class="modal-content">
                <div class="modal-header">
                <h2>Gerenciar Menus por Link</h2>
                <button class="close-button" onclick="closeLinkMenuModal()">&times;</button>
                </div>
            <div id="linkModalContent">
                <!-- O conteúdo será carregado aqui via AJAX -->
            </div>
        </div>
    </div>

    <!-- Substituir os botões antigos pelos novos ícones flutuantes -->
    <div class="floating-buttons">
        <button onclick="openMenuModal()" class="floating-button image-button">
            <i class="fas fa-image"></i>
            <span class="tooltip">Gerenciar Menus de Imagens</span>
        </button>
        <button onclick="openLinkMenuModal()" class="floating-button link-button">
            <i class="fas fa-link"></i>
            <span class="tooltip">Gerenciar Menus por Link</span>
        </button>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        // Função global para mostrar conteúdo
        function showContent(url, title) {
            try {
                const contentDiv = document.querySelector('.content');
                // Verifica se a URL é uma imagem (termina com extensões de imagem comuns)
                const isImage = /\.(jpg|jpeg|png|gif|webp)$/i.test(url);
                
                if (isImage) {
                    contentDiv.innerHTML = `
                        <div class="content-header">
                            <h2>${title}</h2>
                        </div>
                        <div class="content-body">
                            <img src="${url}" alt="${title}" style="max-width: 100%; height: auto;">
                        </div>
                    `;
                } else {
                    // Abre o link diretamente em uma nova aba
                    window.open(url, '_blank');
                }
            } catch (error) {
                console.error('Erro ao mostrar conteúdo:', error);
                window.location.href = '/';
            }
        }

        // Função para lidar com cliques no menu
        function handleMenuClick(url, title) {
            try {
                showContent(url, title);
            } catch (error) {
                console.error('Erro ao processar clique no menu:', error);
                window.location.href = '/';
            }
        }

        // Funções globais do menu
        function openMenuModal() {
            fetch('/menus/manage')
                .then(response => response.text())
                .then(html => {
                    document.getElementById('modalContent').innerHTML = html;
                    const modal = document.getElementById('menuModal');
                    modal.style.display = 'flex';
                    // Carregar o script após o conteúdo ser inserido
                    loadMenuScripts();
                })
                .catch(error => {
                    console.error('Erro ao carregar o modal:', error);
                });
        }

        function closeMenuModal() {
            const modal = document.getElementById('menuModal');
            modal.style.display = 'none';
            document.getElementById('modalContent').innerHTML = '';
            reloadMenu();
        }

        function reloadMenu() {
            fetch('/')
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newMenu = doc.querySelector('.menu-list');
                    document.querySelector('.menu-list').innerHTML = newMenu.innerHTML;
                    initializeDragAndDrop(); // Garante drag and drop após reload
                })
                .catch(error => {
                    console.error('Erro ao recarregar o menu:', error);
                });
        }

        // Função para inicializar o drag and drop
        function initializeDragAndDrop() {
            // Inicializa Sortable em todas as listas de menus e submenus
            document.querySelectorAll('.menu-list, .submenu').forEach(function(list) {
                new Sortable(list, {
                    animation: 150,
                    handle: '.drag-handle',
                    ghostClass: 'sortable-ghost',
                    onEnd: function(evt) {
                        const items = Array.from(list.querySelectorAll(':scope > li.menu-item'));
                        const newOrder = items.map((item, index) => ({
                            id: item.dataset.menuId,
                            order: index
                        }));
                        // Enviar a nova ordem para o servidor
                        updateMenuOrder(newOrder);
                    }
                });
            });
        }

        // Função para atualizar a ordem dos menus no servidor
        function updateMenuOrder(newOrder) {
            fetch('/menus/reorder', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ order: newOrder })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Ordem atualizada com sucesso');
                } else {
                    console.error('Erro ao atualizar ordem:', data.message);
                }
            })
            .catch(error => {
                console.error('Erro ao atualizar ordem:', error);
            });
        }

        // Modificar a função loadMenuScripts para incluir a inicialização do drag and drop
        function loadMenuScripts() {
            initializeDragAndDrop();
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Função para editar menu
            window.editMenu = function(id) {
                fetch(`/menus/${id}/edit`)
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('modalContent').innerHTML = html;
                    })
                    .catch(error => {
                        console.error('Erro ao carregar formulário de edição:', error);
                    });
            }

            // Função para excluir menu
            window.deleteMenu = function(id) {
                if (confirm('Tem certeza que deseja excluir este menu?')) {
                    console.log('Tentando excluir menu:', id);
                    
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    
                    fetch(`/menus/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        console.log('Resposta do servidor:', response.status);
                        if (!response.ok) {
                            return response.json().then(data => {
                                throw new Error(data.message || 'Erro ao excluir menu');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Recarregar o conteúdo da modal atual
                            const modalContent = document.getElementById('modalContent');
                            const linkModalContent = document.getElementById('linkModalContent');
                            
                            if (modalContent) {
                                reloadModal();
                            }
                            if (linkModalContent) {
                                reloadLinkModal();
                            }
                            
                            // Recarregar o menu lateral
                            reloadMenu();
                        } else {
                            throw new Error(data.message || 'Erro ao excluir menu');
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao excluir menu:', error);
                        alert('Erro ao excluir menu. Por favor, tente novamente.');
                    });
                }
            }

            // Função para recarregar o modal
            window.reloadModal = function() {
                fetch('/menus/manage')
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('modalContent').innerHTML = html;
                        loadMenuScripts(); // Recarregar os scripts após atualizar o conteúdo
                    })
                    .catch(error => {
                        console.error('Erro ao recarregar o modal:', error);
                    });
            }

            // Configurar área de colagem de imagens
            const pasteArea = document.getElementById('pasteArea');
            if (pasteArea) {
                pasteArea.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const items = (e.clipboardData || e.originalEvent.clipboardData).items;
                    
                    for (let i = 0; i < items.length; i++) {
                        if (items[i].type.indexOf('image') !== -1) {
                            const blob = items[i].getAsFile();
                            const reader = new FileReader();
                            
                            reader.onload = function(event) {
                                const img = document.createElement('img');
                                img.src = event.target.result;
                                
                                const preview = document.getElementById('imagePreview');
                                preview.innerHTML = '';
                                preview.appendChild(img);
                                
                                const file = new File([blob], 'pasted-image.png', { type: 'image/png' });
                                const dataTransfer = new DataTransfer();
                                dataTransfer.items.add(file);
                                document.getElementById('imageInput').files = dataTransfer.files;
                            };
                            
                            reader.readAsDataURL(blob);
                        }
                    }
                });

                pasteArea.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    this.style.borderColor = '#2196F3';
                });

                pasteArea.addEventListener('dragleave', function(e) {
                    e.preventDefault();
                    this.style.borderColor = '#ccc';
                });

                pasteArea.addEventListener('drop', function(e) {
                    e.preventDefault();
                    this.style.borderColor = '#ccc';
                    
                    const files = e.dataTransfer.files;
                    if (files.length > 0 && files[0].type.indexOf('image') !== -1) {
                        const reader = new FileReader();
                        
                        reader.onload = function(event) {
                            const img = document.createElement('img');
                            img.src = event.target.result;
                            
                            const preview = document.getElementById('imagePreview');
                            preview.innerHTML = '';
                            preview.appendChild(img);
                            
                            document.getElementById('imageInput').files = files;
                        };
                        
                        reader.readAsDataURL(files[0]);
                    }
                });
            }

            // Configurar formulários
            const menuForm = document.getElementById('menuForm');
            if (menuForm) {
                menuForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    
                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(data => {
                                throw new Error(data.message || 'Erro ao salvar menu');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            reloadMenu();
                            reloadModal();
                        } else {
                            throw new Error(data.message || 'Erro ao salvar menu');
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao salvar menu:', error);
                        alert(error.message || 'Erro ao salvar menu. Por favor, tente novamente.');
                    });
                });
            }

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
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            reloadMenu();
                            reloadModal();
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao salvar menu:', error);
                    });
                }
            });

            function renderMenu(menu) {
                const menuDiv = document.createElement('div');
                menuDiv.className = 'menu-item';
                menuDiv.innerHTML = `
                    <div class="menu-content" onclick="showContent('${menu.link}', '${menu.name}')">
                        <img src="${menu.link}" alt="${menu.name}" class="menu-image">
                        <span class="menu-name">${menu.name}</span>
                    </div>
                `;
                return menuDiv;
            }
        }

        // Fechar o modal quando clicar fora dele
        window.onclick = function(event) {
            const menuModal = document.getElementById('menuModal');
            const linkMenuModal = document.getElementById('linkMenuModal');
            if (event.target == menuModal) {
                closeMenuModal();
            }
            if (event.target == linkMenuModal) {
                closeLinkMenuModal();
            }
        }

        // Remover o evento de clique do botão de fechar que estava causando o redirecionamento
        document.querySelectorAll('.close-button').forEach(button => {
            button.onclick = function(e) {
                e.preventDefault();
                const modal = this.closest('.modal-overlay');
                if (modal.id === 'menuModal') {
                    closeMenuModal();
                } else if (modal.id === 'linkMenuModal') {
                    closeLinkMenuModal();
                }
            }
        });

        // Funções para o modal de links
        function openLinkMenuModal() {
            fetch('/menus/manage-links')
                .then(response => response.text())
                .then(html => {
                    document.getElementById('linkModalContent').innerHTML = html;
                    const modal = document.getElementById('linkMenuModal');
                    modal.style.display = 'flex';
                    // Carregar o script após o conteúdo ser inserido
                    loadLinkMenuScripts();
                })
                .catch(error => {
                    console.error('Erro ao carregar o modal de links:', error);
                });
        }

        function closeLinkMenuModal() {
            const modal = document.getElementById('linkMenuModal');
            modal.style.display = 'none';
            document.getElementById('linkModalContent').innerHTML = '';
            reloadMenu();
        }

        // Funções globais
        function reloadLinkModal() {
            fetch('/menus/manage-links')
                .then(response => response.text())
                .then(html => {
                    document.getElementById('linkModalContent').innerHTML = html;
                    loadLinkMenuScripts();
                })
                .catch(error => {
                    console.error('Erro ao recarregar o modal de links:', error);
                });
        }

        function submitMenuForm(form) {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const formData = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Erro ao salvar menu');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    form.reset();
                    reloadLinkModal();
                    reloadMenu();
                    closeLinkMenuModal();
                } else {
                    throw new Error(data.message || 'Erro ao salvar menu');
                }
            })
            .catch(error => {
                console.error('Erro ao salvar menu:', error);
                alert(error.message || 'Erro ao salvar menu. Por favor, tente novamente.');
            });
        }

        function loadLinkMenuScripts() {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Configurar formulário
            const menuForm = document.getElementById('menuForm');
            if (menuForm) {
                menuForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    submitMenuForm(this);
                });
            }

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

        // Função para retornar à página inicial
        function returnToHome() {
            const contentDiv = document.querySelector('.content');
            contentDiv.innerHTML = `
                <div class="content-header">
                    <h2>Sistema de Protótipos</h2>
                </div>
                <div class="content-body">
                    <p>Use o menu lateral para navegar entre as páginas.</p>
                </div>
            `;
        }
    </script>
    <script>
        // Inicializa o drag and drop ao carregar a página
        document.addEventListener('DOMContentLoaded', function() {
            initializeDragAndDrop();
        });
    </script>
</body>
</html> 
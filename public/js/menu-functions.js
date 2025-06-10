// Configurar o CSRF token para todas as requisições AJAX
const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Função para editar menu
function editMenu(id) {
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
function deleteMenu(id) {
    if (confirm('Tem certeza que deseja excluir este menu?')) {
        fetch(`/menus/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (typeof reloadMenu === 'function') {
                    reloadMenu();
                }
                if (typeof reloadModal === 'function') {
                    reloadModal();
                }
                // Se estiver na página de gerenciamento, recarregar a página
                if (window.location.pathname === '/menus/manage') {
                    window.location.reload();
                }
            }
        })
        .catch(error => {
            console.error('Erro ao excluir menu:', error);
        });
    }
}

// Função para recarregar o modal
function reloadModal() {
    fetch('/menus/manage')
        .then(response => response.text())
        .then(html => {
            document.getElementById('modalContent').innerHTML = html;
        })
        .catch(error => {
            console.error('Erro ao recarregar o modal:', error);
        });
}

// Manipular o envio do formulário
document.addEventListener('DOMContentLoaded', function() {
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
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (typeof reloadMenu === 'function') {
                        reloadMenu();
                    }
                    if (typeof reloadModal === 'function') {
                        reloadModal();
                    }
                    // Se estiver na página de gerenciamento, recarregar a página
                    if (window.location.pathname === '/menus/manage') {
                        window.location.reload();
                    }
                }
            })
            .catch(error => {
                console.error('Erro ao salvar menu:', error);
            });
        });
    }

    // Manipular o envio do formulário de edição
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
                    if (typeof reloadMenu === 'function') {
                        reloadMenu();
                    }
                    if (typeof reloadModal === 'function') {
                        reloadModal();
                    }
                    // Se estiver na página de gerenciamento, recarregar a página
                    if (window.location.pathname === '/menus/manage') {
                        window.location.reload();
                    }
                }
            })
            .catch(error => {
                console.error('Erro ao salvar menu:', error);
            });
        }
    });

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
}); 
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gerenciar Menus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 20px;
            background: #f8f9fa;
        }
        .menu-form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .menu-list-manage {
            list-style: none;
            padding: 0;
        }
        .menu-list-manage li {
            background: white;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .menu-info {
            flex: 1;
        }
        .menu-actions {
            display: flex;
            gap: 10px;
        }
        .submenu-manage {
            margin-left: 20px;
            border-left: 3px solid #2196F3;
        }
        .menu-type {
            font-size: 0.8em;
            color: #666;
            margin-top: 5px;
        }
        .btn-edit {
            background: #2196F3;
            color: white;
        }
        .image-input-container {
            margin-top: 10px;
        }
        .paste-area {
            border: 2px dashed #ccc;
            padding: 20px;
            text-align: center;
            margin: 10px 0;
            min-height: 100px;
            background: #f8f9fa;
            cursor: pointer;
        }
        .paste-area:hover {
            border-color: #2196F3;
        }
        .image-preview {
            margin-top: 10px;
            max-width: 200px;
        }
        .image-preview img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
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

    <form action="{{ route('menus.store') }}" method="POST" enctype="multipart/form-data" class="menu-form" id="menuForm">
        @csrf
        <div class="form-group">
            <label>Tipo de Menu:</label>
            <select name="parent_id" class="form-control">
                <option value="">Menu Principal</option>
                @foreach($menus->whereNull('parent_id') as $menu)
                    <option value="{{ $menu->id }}">Submenu de: {{ $menu->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Nome do Menu:</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Imagem:</label>
            <div class="image-input-container">
                <input type="file" name="image" accept="image/*" id="imageInput">
                <div class="paste-area" id="pasteArea" contenteditable="true">
                    <p>Cole a imagem aqui (Ctrl+V) ou arraste uma imagem</p>
                </div>
                <div id="imagePreview" class="image-preview"></div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Adicionar Menu</button>
    </form>

    <ul class="menu-list-manage">
        @foreach($menus->whereNull('parent_id') as $menu)
            <li>
                <div class="menu-info">
                    <strong>{{ $menu->name }}</strong>
                    <br>
                    <a href="{{ $menu->link }}" target="_blank">{{ $menu->link }}</a>
                    <div class="menu-type">Menu Principal</div>
                </div>
                <div class="menu-actions">
                    <button onclick="editMenu({{ $menu->id }})" class="btn btn-edit">Editar</button>
                    <button onclick="deleteMenu({{ $menu->id }})" class="btn btn-danger">Excluir</button>
                </div>
            </li>
            @foreach($menu->children as $submenu)
                <li class="submenu-manage">
                    <div class="menu-info">
                        <strong>{{ $submenu->name }}</strong>
                        <br>
                        <a href="{{ $submenu->link }}" target="_blank">{{ $submenu->link }}</a>
                        <div class="menu-type">Submenu de: {{ $menu->name }}</div>
                    </div>
                    <div class="menu-actions">
                        <button onclick="editMenu({{ $submenu->id }})" class="btn btn-edit">Editar</button>
                        <button onclick="deleteMenu({{ $submenu->id }})" class="btn btn-danger">Excluir</button>
                    </div>
                </li>
            @endforeach
        @endforeach
    </ul>

</body>
</html> 
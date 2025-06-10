<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Editar Menu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
        .menu-form {
            margin-bottom: 30px;
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
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary {
            background: #4CAF50;
            color: white;
        }
        .btn-secondary {
            background: #666;
            color: white;
        }
        .current-image {
            margin-top: 10px;
        }
        .current-image img {
            max-width: 200px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Editar Menu</h1>

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

        <form action="{{ route('menus.update', $menu) }}" method="POST" enctype="multipart/form-data" class="menu-form">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Tipo de Menu:</label>
                <select name="parent_id" class="form-control">
                    <option value="">Menu Principal</option>
                    @foreach($menus->whereNull('parent_id') as $parentMenu)
                        @if($parentMenu->id !== $menu->id)
                            <option value="{{ $parentMenu->id }}" {{ $menu->parent_id == $parentMenu->id ? 'selected' : '' }}>
                                Submenu de: {{ $parentMenu->name }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Nome do Menu:</label>
                <input type="text" name="name" class="form-control" value="{{ $menu->name }}" required>
            </div>
            <div class="form-group">
                <label>Link:</label>
                <input type="url" name="link" class="form-control" value="{{ $menu->link }}" required>
            </div>
            <div class="form-group">
                <label>Nova Imagem:</label>
                <input type="file" name="image" accept="image/*">
                @if($menu->image)
                    <div class="current-image">
                        <p>Imagem atual:</p>
                        <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}">
                    </div>
                @endif
            </div>
            <button type="submit" class="btn btn-primary">Atualizar Menu</button>
            <button type="button" class="btn btn-secondary" onclick="reloadModal()">Cancelar</button>
        </form>

        <a href="{{ route('menus.manage') }}" class="btn btn-secondary">Voltar</a>
    </div>
</body>
</html> 
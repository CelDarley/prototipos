<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $menu->name }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #f5f6fa;
        }
        .image-container {
            max-width: 90%;
            max-height: 90vh;
            text-align: center;
        }
        .image-container img {
            max-width: 100%;
            max-height: 80vh;
            object-fit: contain;
        }
        .image-title {
            margin-top: 20px;
            font-family: Arial, sans-serif;
            color: #2c3e50;
            font-size: 1.5em;
        }
    </style>
</head>
<body>
    <div class="image-container">
        <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}">
        <div class="image-title">{{ $menu->name }}</div>
    </div>
</body>
</html> 
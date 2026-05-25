<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin Blog</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(120deg, #f5f7fa, #e4ecf5);
            min-height: 100vh;
            display: grid;
            place-items: center;
            color: #1f2937;
        }
        .card {
            width: min(420px, 92vw);
            background: #fff;
            padding: 28px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }
        h1 {
            margin-top: 0;
            margin-bottom: 18px;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-size: 14px;
        }
        input {
            width: 100%;
            box-sizing: border-box;
            margin-bottom: 14px;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
        }
        button {
            width: 100%;
            border: 0;
            border-radius: 8px;
            background: #1d4ed8;
            color: #fff;
            font-weight: 600;
            padding: 10px;
            cursor: pointer;
        }
        .error {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 14px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <main class="card">
        <h1>Administration du blog</h1>

        @if ($errors->any())
            <div class="error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf
            <label for="email">Email</label>
            <input id="email" type="email" name="email" required value="{{ old('email') }}">

            <label for="password">Mot de passe</label>
            <input id="password" type="password" name="password" required>

            <button type="submit">Se connecter</button>
        </form>
    </main>
</body>
</html>

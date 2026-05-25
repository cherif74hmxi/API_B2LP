<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier billet</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            color: #111827;
        }
        main {
            max-width: 800px;
            margin: 32px auto;
            background: #fff;
            border-radius: 12px;
            padding: 22px;
            box-shadow: 0 10px 28px rgba(0, 0, 0, 0.06);
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-size: 14px;
        }
        input, textarea {
            width: 100%;
            box-sizing: border-box;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            margin-bottom: 14px;
            font-family: inherit;
        }
        textarea {
            min-height: 160px;
            resize: vertical;
        }
        .actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .btn {
            border: 0;
            border-radius: 8px;
            padding: 9px 12px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary { background: #1d4ed8; color: #fff; }
        .btn-secondary { background: #e5e7eb; color: #111827; }
        .error {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 14px;
        }
    </style>
</head>
<body>
<main>
    <h1>Modifier le billet</h1>

    @if ($errors->any())
        <div class="error">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('admin.billets.update', $billet) }}">
        @csrf
        @method('PUT')

        <label for="BIL_DATE">Date</label>
        <input id="BIL_DATE" type="date" name="BIL_DATE" value="{{ old('BIL_DATE', $billet->BIL_DATE) }}" required>

        <label for="BIL_TITRE">Titre</label>
        <input id="BIL_TITRE" type="text" name="BIL_TITRE" maxlength="255" value="{{ old('BIL_TITRE', $billet->BIL_TITRE) }}" required>

        <label for="BIL_CONTENU">Contenu</label>
        <textarea id="BIL_CONTENU" name="BIL_CONTENU" required>{{ old('BIL_CONTENU', $billet->BIL_CONTENU) }}</textarea>

        <div class="actions">
            <button class="btn btn-primary" type="submit">Enregistrer</button>
            <a class="btn btn-secondary" href="{{ route('admin.dashboard') }}">Retour</a>
        </div>
    </form>
</main>
</body>
</html>

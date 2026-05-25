<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin Blog</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            color: #1f2937;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 20px;
            background: #111827;
            color: #fff;
        }
        main {
            max-width: 1050px;
            margin: 24px auto;
            padding: 0 16px 24px;
        }
        .panel {
            background: #fff;
            border-radius: 12px;
            padding: 18px;
            margin-bottom: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
        }
        .grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px;
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
            font-family: inherit;
        }
        textarea {
            min-height: 120px;
            resize: vertical;
        }
        .actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 12px;
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
        .btn-danger { background: #dc2626; color: #fff; }
        .status {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 16px;
        }
        .error {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 16px;
        }
        .commentaires {
            margin-top: 10px;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
        .comment-item {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 8px;
        }
        .muted {
            color: #6b7280;
            font-size: 13px;
        }
    </style>
</head>
<body>
<header>
    <strong>Panel Admin Blog</strong>
    <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button class="btn btn-secondary" type="submit">Se deconnecter</button>
    </form>
</header>

<main>
    @if (session('status'))
        <div class="status">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="error">{{ $errors->first() }}</div>
    @endif

    <section class="panel">
        <h2>Creer un billet</h2>
        <form method="POST" action="{{ route('admin.billets.store') }}">
            @csrf
            <div class="grid">
                <div>
                    <label for="BIL_DATE">Date</label>
                    <input id="BIL_DATE" type="date" name="BIL_DATE" value="{{ old('BIL_DATE') }}" required>
                </div>
                <div>
                    <label for="BIL_TITRE">Titre</label>
                    <input id="BIL_TITRE" type="text" name="BIL_TITRE" maxlength="255" value="{{ old('BIL_TITRE') }}" required>
                </div>
                <div>
                    <label for="BIL_CONTENU">Contenu</label>
                    <textarea id="BIL_CONTENU" name="BIL_CONTENU" required>{{ old('BIL_CONTENU') }}</textarea>
                </div>
            </div>
            <div class="actions">
                <button class="btn btn-primary" type="submit">Creer</button>
            </div>
        </form>
    </section>

    <section class="panel">
        <h2>Billets</h2>
        @forelse ($billets as $billet)
            <article class="panel">
                <h3>{{ $billet->BIL_TITRE }}</h3>
                <p class="muted">Date: {{ $billet->BIL_DATE }}</p>
                <p>{{ $billet->BIL_CONTENU }}</p>

                <div class="actions">
                    <a class="btn btn-secondary" href="{{ route('admin.billets.edit', $billet) }}">Modifier</a>
                    <form method="POST" action="{{ route('admin.billets.destroy', $billet) }}" onsubmit="return confirm('Supprimer ce billet ?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger" type="submit">Supprimer</button>
                    </form>
                </div>

                <div class="commentaires">
                    <strong>Commentaires ({{ $billet->commentaires->count() }})</strong>
                    @forelse ($billet->commentaires as $commentaire)
                        <div class="comment-item">
                            <p class="muted">{{ $commentaire->COM_DATE }} - {{ $commentaire->user->name ?? 'Auteur inconnu' }}</p>
                            <p>{{ $commentaire->COM_CONTENU }}</p>
                            <form method="POST" action="{{ route('admin.commentaires.destroy', $commentaire) }}" onsubmit="return confirm('Supprimer ce commentaire ?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" type="submit">Supprimer le commentaire</button>
                            </form>
                        </div>
                    @empty
                        <p class="muted">Aucun commentaire.</p>
                    @endforelse
                </div>
            </article>
        @empty
            <p>Aucun billet.</p>
        @endforelse
    </section>
</main>
</body>
</html>

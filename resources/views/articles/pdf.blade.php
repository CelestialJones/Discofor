<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>{{ $article->title }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1f2937;
            font-size: 12px;
            line-height: 1.7;
            margin: 0;
        }

        .page {
            padding: 40px 48px;
        }

        .header {
            border-bottom: 2px solid #dbe4f0;
            margin-bottom: 24px;
            padding-bottom: 16px;
        }

        .brand {
            color: #0f62fe;
            font-size: 14px;
            font-weight: bold;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        h1 {
            font-size: 26px;
            line-height: 1.25;
            margin: 0 0 10px 0;
        }

        .meta {
            color: #5b6c84;
            font-size: 11px;
        }

        .tags {
            margin: 18px 0 24px;
        }

        .tag {
            display: inline-block;
            background: #eef5ff;
            color: #0f62fe;
            border: 1px solid #d6e7ff;
            border-radius: 999px;
            padding: 4px 10px;
            margin-right: 6px;
            margin-bottom: 6px;
            font-size: 10px;
        }

        .content {
            white-space: pre-line;
            text-align: justify;
        }

        .attachment {
            margin-top: 28px;
            padding: 14px 16px;
            border: 1px solid #dbe4f0;
            background: #f8fbff;
            border-radius: 8px;
        }

        .footer {
            margin-top: 32px;
            font-size: 10px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 12px;
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <div class="brand">Discofor</div>
            <h1>{{ $article->title }}</h1>
            <div class="meta">
                Autor: {{ $article->user->name }}<br>
                Publicado em: {{ $article->created_at->format('d/m/Y H:i') }}<br>
                Visualizacoes: {{ $article->views }}
            </div>
        </div>

        @if($article->tags->isNotEmpty())
            <div class="tags">
                @foreach($article->tags as $tag)
                    <span class="tag">{{ $tag->name }}</span>
                @endforeach
            </div>
        @endif

        <div class="content">{{ $article->content }}</div>

        @if($article->attachment)
            <div class="attachment">
                PDF anexado ao artigo: {{ $article->attachment->original_name }}
            </div>
        @endif

        <div class="footer">
            Documento gerado automaticamente pela plataforma Discofor em {{ now()->format('d/m/Y H:i') }}.
        </div>
    </div>
</body>
</html>

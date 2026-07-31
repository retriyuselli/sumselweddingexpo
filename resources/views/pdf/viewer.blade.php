<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Preview PDF' }}</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, sans-serif;
            background: #f3f4f6;
            color: #111827;
        }
        .toolbar {
            position: sticky;
            top: 0;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 16px;
            background: #111827;
            color: #fff;
        }
        .toolbar h1 {
            margin: 0;
            font-size: 14px;
            font-weight: 600;
            line-height: 1.3;
        }
        .toolbar .sub {
            margin: 2px 0 0;
            font-size: 12px;
            color: #d1d5db;
            font-weight: 400;
        }
        .actions {
            display: flex;
            gap: 8px;
            flex-shrink: 0;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            border: 1px solid transparent;
        }
        .btn-back {
            background: #fff;
            color: #111827;
        }
        .btn-download {
            background: #003D79;
            color: #fff;
        }
        .viewer {
            height: calc(100vh - 58px);
            padding: 12px;
        }
        .viewer iframe,
        .viewer embed {
            width: 100%;
            height: 100%;
            border: 0;
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,.08);
        }
        @media (max-width: 640px) {
            .toolbar { flex-direction: column; align-items: stretch; }
            .actions { width: 100%; }
            .btn { flex: 1; justify-content: center; }
            .viewer { height: calc(100vh - 110px); }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <div>
            <h1>{{ $title ?? 'Preview PDF' }}</h1>
            @if (! empty($subtitle))
                <div class="sub">{{ $subtitle }}</div>
            @endif
        </div>
        <div class="actions">
            <a class="btn btn-back" href="{{ $backUrl }}">← Kembali</a>
            <a class="btn btn-download" href="{{ $downloadUrl }}">Unduh PDF</a>
        </div>
    </div>
    <div class="viewer">
        <iframe src="{{ $pdfUrl }}" title="{{ $title ?? 'PDF' }}"></iframe>
    </div>
</body>
</html>

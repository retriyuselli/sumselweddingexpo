<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>{{ $title ?? 'Preview PDF' }}</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "DM Sans", ui-sans-serif, system-ui, sans-serif;
            background: #faf7f2;
            color: #35252a;
        }
        .toolbar {
            position: sticky;
            top: 0;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: max(12px, env(safe-area-inset-top)) 16px 12px;
            background: #641b2a;
            color: #fff8f3;
        }
        .titles { min-width: 0; flex: 1; }
        .toolbar h1 {
            margin: 0;
            font-size: 15px;
            font-weight: 600;
            line-height: 1.3;
            overflow-wrap: anywhere;
        }
        .toolbar .sub {
            margin: 3px 0 0;
            font-size: 12px;
            color: #f0d6bd;
            overflow-wrap: anywhere;
        }
        .actions {
            display: flex;
            gap: 8px;
            flex-shrink: 0;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            min-height: 40px;
            padding: 8px 14px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
        }
        .btn-back { background: #fff8f3; color: #641b2a; }
        .btn-download { background: #b78a54; color: #fff; }
        .stage {
            height: calc(100dvh - 64px);
            padding: 16px;
            padding-bottom: max(16px, env(safe-area-inset-bottom));
        }
        .frame {
            width: min(920px, 100%);
            height: 100%;
            margin: 0 auto;
            background: #fff;
            border: 1px solid #e6d8c8;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 16px 40px #3b202812;
        }
        .frame iframe,
        .frame embed {
            width: 100%;
            height: 100%;
            border: 0;
            background: #fff;
        }
        @media (max-width: 640px) {
            .toolbar { flex-direction: column; align-items: stretch; }
            .actions { width: 100%; }
            .btn { flex: 1; width: 100%; }
            .stage { height: calc(100dvh - 132px); padding: 10px; }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <div class="titles">
            <h1>{{ $title ?? 'Preview PDF' }}</h1>
            @if (! empty($subtitle))
                <div class="sub">{{ $subtitle }}</div>
            @endif
        </div>
        <div class="actions">
            <a class="btn btn-back" href="{{ $backUrl }}">Kembali</a>
            <a class="btn btn-download" href="{{ $downloadUrl }}">Unduh PDF</a>
        </div>
    </div>
    <div class="stage">
        <div class="frame">
            <iframe src="{{ $pdfUrl }}" title="{{ $title ?? 'PDF' }}"></iframe>
        </div>
    </div>
</body>
</html>

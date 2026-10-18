@php
    $visitorPrizes = collect($expo->visitor_prizes ?? [])->filter(fn ($prize) => is_array($prize) && filled($prize['name'] ?? null));
@endphp
@if ($expo->visitor_prizes_enabled && $visitorPrizes->isNotEmpty())
    <section class="visitor-prizes" aria-labelledby="visitor-prizes-heading">
        <span class="eyebrow">KEJUTAN UNTUK ANDA</span>
        <h3 id="visitor-prizes-heading">Kunjungan istimewa,<br>kesempatan membawa pulang hadiah.</h3>
        <p>Bagi pengunjung yang beruntung, hadiah menarik berikut menanti Anda:</p>
        <ul>
            @foreach ($visitorPrizes as $prize)
                <li><span class="prize-mark" aria-hidden="true">✦</span><div><strong>{{ $prize['name'] }}</strong>@if (filled($prize['description'] ?? null))<p>{{ $prize['description'] }}</p>@endif</div>@if ((int) ($prize['quantity'] ?? 0) > 0)<span class="prize-quantity">{{ (int) $prize['quantity'] }} hadiah</span>@endif</li>
            @endforeach
        </ul>
        @if (filled($expo->visitor_prize_terms))<div class="prize-terms"><strong>Ketentuan hadiah</strong><p>{{ $expo->visitor_prize_terms }}</p></div>@endif
    </section>
@endif

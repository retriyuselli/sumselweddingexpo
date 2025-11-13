@extends('layouts.app')

@section('title', $article['title'] . ' — WeddingExpo Blog')
@push('head')
    <meta name="description" content="{{ $article['excerpt'] }}">
@endpush

@section('content')
    <main class="min-h-screen bg-white">

        <!-- Breadcrumb -->
        <section class="pt-24 pb-6 bg-gray-50">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <nav class="flex items-center gap-2 text-xs text-gray-600">
                    <a href="/" class="hover:text-rose-600">Home</a>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <a href="/blog" class="hover:text-rose-600">Blog</a>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <span class="text-gray-900">{{ $article['breadcrumb'] ?? $article['title'] }}</span>
                </nav>
            </div>
        </section>

        <!-- Main Content with Sidebar -->
        <div class="py-8">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-3 gap-8">

                    <!-- Main Article Content -->
                    <article class="lg:col-span-2">

                        <div class="mb-3 text-sm">
                            <span
                                class="inline-block px-3 py-1 bg-{{ $article['category_color'] }}-600 text-white text-xs font-semibold rounded-full">{{ $article['category'] }}</span>
                        </div>

                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-4 leading-tight">
                            {{ $article['title'] }}
                        </h1>

                        <div class="flex flex-wrap items-center gap-4 text-xs text-gray-600 pb-4 border-b border-gray-200">
                            <div class="flex items-center gap-2">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($article['author']['name']) }}&background={{ str_replace('#', '', $article['author']['color']) }}&color=fff"
                                    alt="Author" class="w-8 h-8 rounded-full">
                                <div>
                                    <div class="font-semibold text-gray-900 text-sm">{{ $article['author']['name'] }}</div>
                                    <div class="text-xs">{{ $article['author']['role'] }}</div>
                                </div>
                            </div>
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                {{ $article['date'] }}
                            </span>
                            <span>{{ $article['read_time'] }} menit baca</span>
                        </div>

                        @if (isset($article['image']))
                            <div class="my-6 rounded-xl overflow-hidden">
                                <img src="{{ $article['image'] }}" alt="{{ $article['title'] }}" class="w-full h-auto">
                            </div>
                        @endif

                        <style>
                            .blog-content {
                                font-size: 0.875rem;
                                /* text-sm */
                                line-height: 1.625;
                                /* leading-relaxed */
                                color: #374151;
                                /* text-gray-700 */
                            }

                            .blog-content h1 {
                                font-size: 1.5rem;
                                font-weight: 700;
                                color: #111827;
                                margin: 1.5rem 0 1rem;
                            }

                            .blog-content h2 {
                                font-size: 1.25rem;
                                font-weight: 700;
                                color: #111827;
                                margin: 1.5rem 0 1rem;
                            }

                            .blog-content h3 {
                                font-size: 1.125rem;
                                font-weight: 700;
                                color: #111827;
                                margin: 1.25rem 0 0.75rem;
                            }

                            .blog-content h4 {
                                font-size: 1rem;
                                font-weight: 700;
                                color: #111827;
                                margin: 1rem 0 0.5rem;
                            }

                            .blog-content p {
                                margin-bottom: 1rem;
                                line-height: 1.75;
                            }

                            .blog-content a {
                                color: #f43f5e;
                                text-decoration: none;
                            }

                            .blog-content a:hover {
                                text-decoration: underline;
                            }

                            .blog-content strong,
                            .blog-content b {
                                font-weight: 600;
                                color: #111827;
                            }

                            .blog-content em,
                            .blog-content i {
                                font-style: italic;
                            }

                            /* Ordered List - Numbered */
                            .blog-content ol {
                                list-style-type: decimal;
                                margin-left: 1.5rem;
                                margin-top: 1rem;
                                margin-bottom: 1rem;
                                padding-left: 0.5rem;
                            }

                            .blog-content ol li {
                                padding-left: 0.5rem;
                                margin-bottom: 0.5rem;
                                line-height: 1.75;
                            }

                            /* Unordered List - Bullets */
                            .blog-content ul {
                                list-style-type: disc;
                                margin-left: 1.5rem;
                                margin-top: 1rem;
                                margin-bottom: 1rem;
                                padding-left: 0.5rem;
                            }

                            .blog-content ul li {
                                padding-left: 0.5rem;
                                margin-bottom: 0.5rem;
                                line-height: 1.75;
                            }

                            /* Nested Lists */
                            .blog-content ul ul,
                            .blog-content ol ul {
                                list-style-type: circle;
                            }

                            .blog-content ol ol,
                            .blog-content ul ol {
                                list-style-type: lower-alpha;
                            }

                            .blog-content blockquote {
                                border-left: 4px solid #f43f5e;
                                padding-left: 1rem;
                                font-style: italic;
                                color: #6b7280;
                                margin: 1.5rem 0;
                            }

                            .blog-content code {
                                background-color: #f3f4f6;
                                padding: 0.125rem 0.375rem;
                                border-radius: 0.25rem;
                                font-size: 0.875rem;
                                color: #f43f5e;
                                font-family: monospace;
                            }

                            .blog-content pre {
                                background-color: #1f2937;
                                color: #f3f4f6;
                                padding: 1rem;
                                border-radius: 0.5rem;
                                overflow-x: auto;
                                margin: 1rem 0;
                            }

                            .blog-content pre code {
                                background-color: transparent;
                                color: inherit;
                                padding: 0;
                            }

                            .blog-content img {
                                border-radius: 0.5rem;
                                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                                margin: 1.5rem 0;
                            }

                            .blog-content table {
                                width: 100%;
                                border-collapse: collapse;
                                font-size: 0.875rem;
                                margin: 1.5rem 0;
                            }

                            .blog-content th {
                                background-color: #f3f4f6;
                                border: 1px solid #d1d5db;
                                padding: 0.5rem;
                                font-weight: 600;
                            }

                            .blog-content td {
                                border: 1px solid #d1d5db;
                                padding: 0.5rem;
                            }
                        </style>
                        <div class="blog-content">
                            {!! $article['content'] !!}
                        </div>

                        <!-- Author Bio -->
                        <div class="mt-8 p-4 bg-gray-50 rounded-xl">
                            <div class="flex items-start gap-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($article['author']['name']) }}&background={{ str_replace('#', '', $article['author']['color']) }}&color=fff&size=60"
                                    alt="{{ $article['author']['name'] }}" class="w-16 h-16 rounded-full">
                                <div>
                                    <h3 class="text-base font-bold mb-1">{{ $article['author']['name'] }}</h3>
                                    <p class="text-xs text-gray-600 mb-2">{{ $article['author']['role'] }}</p>
                                    <p class="text-gray-700 leading-relaxed text-sm">
                                        {{ $article['author']['bio'] }}
                                    </p>
                                </div>
                            </div>
                        </div>

                    </article>

                    <!-- Sidebar -->
                    @include('blog.partials.sidebar')

                </div>
            </div>
        </div>

    </main>
@endsection

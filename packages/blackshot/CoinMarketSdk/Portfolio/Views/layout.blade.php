@extends('blackshot::layouts.app')

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/js/standalone/selectize.min.js"></script>
    <script src="{{ asset('js/portfolio-charts.js') }}"></script>
@endpush

@section('content')
    <section class="portfolio">
        @if (isset($portfolios) && $portfolios->count())
            @include('portfolio::portfolio.navigation')
            <div class="portfolio__info">
                @include('portfolio::parts.statistics', ['changePrice24' => $changePrice24])

                {{--  --}}
                @if ($portfolio->items())
                    @include('portfolio::parts.assets-table', ['portfolio' => $portfolio->items()])
                @endif
            </div>
        @else
            @include('portfolio::parts.no-portfolio')
        @endif
    </section>
@endsection

@if ($price > 0.1)
    ${{ number_format($price, 2) }}
@else
    @if ($price > 0.01)
        ${{ number_format($price, 3) }}
    @else
        ${{ number_format($price, 5) }}
    @endif
@endif

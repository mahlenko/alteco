@if ($percent)
    <div class="item-content__block item-content__block_{{ $percent > 0 ? 'green' : 'red' }}">
        <p>{{ number_format(abs($percent), 2) }}</p>
        @if ($percent < 0)
            <img src="{{ asset('images/arr.svg') }}" class="arrow-icon" alt="">
        @else
            <img src="{{ asset('images/arr.svg') }}" class="arrow-icon" alt="">
        @endif
    </div>
@endif

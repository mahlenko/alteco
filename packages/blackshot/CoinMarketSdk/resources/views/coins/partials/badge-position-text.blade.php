@if ($percent)
    <span class="{{ $percent < 0 ? 'text-danger' : 'text-success' }} text-nowrap ms-3">
        {!! $percent < 0 ? '<i class="fas fa-arrow-down"></i>' : '<i class="fas fa-arrow-up"></i>' !!}
        {{ number_format(abs($percent), 2) }}
    </span>
@endif

@if ($percent)
    <span class="badge {{ $percent < 0 ? 'bg-danger' : 'bg-success' }} ms-3">
        {!! $percent < 0 ? '<i class="fas fa-arrow-down"></i>' : '<i class="fas fa-arrow-up"></i>' !!}
        {{ number_format(abs($percent), 2) }}
    </span>
@endif

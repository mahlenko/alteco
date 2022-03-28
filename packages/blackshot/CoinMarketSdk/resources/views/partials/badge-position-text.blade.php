@if ($position)
    <span class="{{ $position < 0 ? 'text-danger' : 'text-success' }} text-nowrap">
        {!! $position < 0 ? '<i class="fas fa-arrow-down"></i>' : '<i class="fas fa-arrow-up"></i>' !!}
        {{ $position }}
    </span>
@else
    ---
@endif

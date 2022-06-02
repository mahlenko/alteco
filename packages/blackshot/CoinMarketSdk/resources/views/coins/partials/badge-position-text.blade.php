@if ($percent)
    <p class="table__num">
        {{ number_format(abs($percent), 2) }}
    </p>
@endif

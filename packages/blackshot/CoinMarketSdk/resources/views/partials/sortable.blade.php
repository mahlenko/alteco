@php($link = array_merge($current_params, ['sortable' => $column .','. ($sortable['direction'] == 'desc' ? 'asc' : 'desc')]))

@isset($default)
    @if($sortable['column'] != $column)
        @php($link = array_merge($current_params, ['sortable' => $column .','. $default]))
    @endif
@endif

@if ($sortable['column'] == $column)
    <a href="{{ \Illuminate\Support\Facades\URL::route('coins.home', $link) }}"
       class="sortable-link {{ $sortable['direction'] == 'desc' ? 'sortable-desc' : null }}">
        <img src="{{ asset('css/img/table/arr1.svg') }}" alt="" class="about__arr svg">
    </a>
@else
    <a href="{{ \Illuminate\Support\Facades\URL::route('coins.home', $link) }}" class="sortable-link">
        <img src="{{ asset('css/img/table/arr1.svg') }}" alt="" class="about__arr svg">
    </a>
@endif

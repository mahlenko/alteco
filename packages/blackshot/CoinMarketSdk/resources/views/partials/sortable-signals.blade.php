@php($data = ['sortable' => [$table_name => $column.':'. ($sortable->direction == 'asc' ? 'desc' : 'asc') ]])

@if ($sortable->column == $column)
    <a href="{{ \Illuminate\Support\Facades\URL::route('signals.home', $data) }}"
       class="sortable-link {{ $sortable->direction == 'desc' ? 'sortable-desc' : null }}">
        <img src="{{ asset('css/img/table/arr1.svg') }}" alt="" class="about__arr svg">
    </a>
@else
    <a href="{{ \Illuminate\Support\Facades\URL::route('signals.home', $data) }}" class="sortable-link">
        <img src="{{ asset('css/img/table/arr1.svg') }}" alt="" class="about__arr svg">
    </a>
@endif

@php($current_params = \Illuminate\Support\Facades\Request::input())
@php($link = array_merge($current_params, ['sortable' => $column .',asc']))

@if ($sortable['column'] == $column)
    @if($sortable['direction'] == 'asc')
        @php($link = array_merge($current_params, ['sortable' => $column .',desc']))
        <a href="{{ \Illuminate\Support\Facades\URL::route('coins.home', $link) }}" class="text-decoration-none">
            <i class="fas fa-sort-down text-primary"></i>
        </a>
    @elseif($sortable['direction'] == 'desc')
        <a href="{{ \Illuminate\Support\Facades\URL::route('coins.home', $link) }}" class="text-decoration-none">
            <i class="fas fa-sort-up text-primary"></i>
        </a>
    @endif
@else
    <a href="{{ \Illuminate\Support\Facades\URL::route('coins.home', $link) }}" class="text-decoration-none">
        <i class="fas fa-sort text-secondary"></i>
    </a>
@endif

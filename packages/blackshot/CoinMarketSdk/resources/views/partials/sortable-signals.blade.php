@if ($sortable->column == $column)
    @if($sortable->direction == 'asc')
        <a href="{{ \Illuminate\Support\Facades\URL::route('signals.home', ['sortable' => [$table_name => $column.':desc' ]]) }}" class="text-decoration-none">
            <i class="fas fa-sort-down text-primary"></i>
        </a>
    @elseif($sortable->direction == 'desc')
        <a href="{{ \Illuminate\Support\Facades\URL::route('signals.home', ['sortable' => [$table_name => $column.':asc' ]]) }}" class="text-decoration-none">
            <i class="fas fa-sort-up text-primary"></i>
        </a>
    @endif
@else
    <a href="{{ \Illuminate\Support\Facades\URL::route('signals.home', ['sortable' => [$table_name => $column.':asc' ]]) }}" class="text-decoration-none">
        <i class="fas fa-sort text-secondary"></i>
    </a>
@endif

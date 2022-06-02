@if (Breadcrumbs::exists(Route::current()->getName()))
    <section class="bread" id="bread">
        <div class="container">
            {{ Breadcrumbs::render(Route::current()->getName(), get_defined_vars()['__data'] ?? []) }}
        </div>
    </section>
@endif

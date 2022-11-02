<div class="box-modal">
    <div class="box-modal_header">
        <div class="global-flex gap-x-2">
            @isset($titleIconUrl)
                <div class="icon-wrap">
                    <img src="{{ $titleIconUrl }}" class="icon" alt="">
                </div>
            @endif
            <h3 class="box-modal_title">{{ $title ?? null }}</h3>
        </div>

        <span class="box-modal_close arcticmodal-close">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </span>
    </div>

    <form action="{{ $form }}" method="post" class="{{ $formClass }}">
        <div class="box-modal_content">
            {{ csrf_field() }}

            <div class="messages" data-id="message"></div>

            @yield('form-body')
        </div>

        <div class="box-modal_footer">
            @stack('footer')
        </div>
    </form>
</div>

<div class="banner__item {{ $promo->color_scheme }}" style="background-image: url({{ asset($promo->pictureUrl()) }})">
    <div class="banner__item-content">
        <h3 class="banner__item-title">{{ $promo->title }}</h3>
        <div class="banner__item-body">{!! $promo->body !!}</div>
        @if ($promo->button_text)
            <a href="{{ $promo->button_url }}" class="banner__item-button">
                <span>{{ $promo->button_text }}</span>
            </a>
        @endif
    </div>
</div>

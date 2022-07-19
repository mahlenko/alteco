<div class="banner__item scheme-{{ $banner->color_scheme }}" style="background-image: url({{ asset($banner->pictureUrl()) }})">
    <div class="banner__item-content">
        <h3 class="banner__item-title">{{ $banner->title }}</h3>

        <div class="banner__item-body">{!! $banner->body !!}</div>

        @if ($banner->button_text)
        <a href="{{ $banner->button_url }}" class="banner__item-button">
            <span>{{ $banner->button_text }}</span>
        </a>
        @endif
    </div>
</div>

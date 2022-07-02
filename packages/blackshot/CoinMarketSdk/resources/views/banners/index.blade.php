@extends('blackshot::layouts.app')

@section('title', 'Баннеры')
@section('title-extend')
    <a href="{{ route('banners.edit') }}" class="btn btn2">
        Добавить баннер
    </a>
@endsection

@section('content')
    <table class="table">
        <thead class="table-secondary">
            <tr>
                <th></th>
                <th>Описание</th>
                <th>
                    <span class="flex flex-center">
                        <span>Начало показов</span>
                        <span class="ignore-offsets" title="Вы можете запланировать показ банера указав ему дату начало показов.">
                            <svg xmlns="http://www.w3.org/2000/svg" class="table-icon opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                    </span>
                </th>
                <th>
                    <span class="flex flex-center">
                        <span>Завершение показов</span>
                        <span class="ignore-offsets" title="После этой даты включая ее, система перестанет показывать баннер">
                            <svg xmlns="http://www.w3.org/2000/svg" class="table-icon opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                    </span>
                </th>
                <th>Тип</th>
                <th>Активен</th>
                <th>Просмотров</th>
                <th>Добавил</th>
                <th>Создан</th>
                <th>Обновлен</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @if($banners->count())
            @foreach($banners as $banner)
                <tr>
                    <td>
                        @if ($banner->picture)
                            <img src="{{ $banner->pictureUrl() }}" alt="{{ $banner->name }}" style="max-width: 40px; border-radius: .4rem">
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('banners.edit', $banner) }}" class="link">
                            <strong>
                                {{ \Illuminate\Support\Str::limit($banner->title, 30) }}
                            </strong>
                        </a>
                        @if ($banner->body)
                            <br>
                            <small>
                                {{ \Illuminate\Support\Str::limit(strip_tags($banner->body), 50) }}
                            </small>
                        @endif
                    </td>
                    <td>{{ $banner->start->translatedFormat('j F Y') }}</td>
                    <td>
                        {{ $banner->end?->translatedFormat('j F Y') ?? '- остановка в ручную -' }}
                    </td>
                    <td>{{ \Blackshot\CoinMarketSdk\Enums\BannerTypes::toString($banner->type) }}</td>
                    <td>{{ $banner->is_active }}</td>
                    <td>{{ $banner->views }}</td>
                    <td>{{ $banner->creater->name }}</td>
                    <td>{{ $banner->created_at->translatedFormat('j F Y') }}</td>
                    <td>{{ $banner->updated_at->translatedFormat('j F Y') }}</td>
                    <td></td>
                </tr>
            @endforeach
            @else
                <td colspan="11">
                    Еще нет ни одного банера. Добавьте и настройте баннеры на сайте.
                </td>
            @endif
        </tbody>
    </table>
@endsection

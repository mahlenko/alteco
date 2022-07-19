@extends('blackshot::layouts.app')

@section('title', 'Настройка баннера')

@section('content')
    <div class="row">
        <div class="column">
            <form action="{{ route('banners.store') }}" method="post" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="uuid" value="{{ $banner->uuid ?? null }}">
                <input type="hidden" name="type" value="{{ \Blackshot\CoinMarketSdk\Enums\BannerTypes::static->name }}">

                <table class="table-setting">
                    <tbody>
{{--                        <tr>--}}
{{--                            <td>--}}
{{--                                <label for="">Тип</label>--}}
{{--                                <span class="required">*</span>--}}
{{--                            </td>--}}
{{--                            <td>--}}

{{--                                @php($type_static = \Blackshot\CoinMarketSdk\Enums\BannerTypes::static)--}}
{{--                                @php($type_modal = \Blackshot\CoinMarketSdk\Enums\BannerTypes::modal)--}}

{{--                                <div class="d-flex" style="align-items: flex-start; column-gap: 1.5rem">--}}
{{--                                    <div class="d-flex" style="align-items: flex-start; column-gap: .5rem">--}}
{{--                                        <input type="radio"--}}
{{--                                               name="type"--}}
{{--                                               id="{{ $type_static->name }}"--}}
{{--                                               value="{{ $type_static->name }}"--}}
{{--                                               {{ old('type', $banner->type) === $type_static->name ? 'checked' : null }}--}}
{{--                                        >--}}
{{--                                        <label for="{{ $type_static->name }}" style="display: flex; flex-direction: column; line-height: 1rem">--}}
{{--                                            <strong>{{ $type_static->value }}</strong>--}}
{{--                                            <small style="color: gray;">Статичный банер для встраивания на страницу</small>--}}
{{--                                        </label>--}}
{{--                                    </div>--}}

{{--                                    <div class="d-flex" style="align-items: flex-start; column-gap: .5rem">--}}
{{--                                        <input type="radio"--}}
{{--                                               name="type"--}}
{{--                                               id="{{ $type_modal->name }}"--}}
{{--                                               value="{{ $type_modal->name }}"--}}
{{--                                               {{ old('type', $banner->type) === $type_modal->name ? 'checked' : null }}--}}
{{--                                        >--}}
{{--                                        <label for="{{ $type_modal->name }}" style="display: flex; flex-direction: column; line-height: 1rem">--}}
{{--                                            <strong>{{ $type_modal->value }}</strong>--}}
{{--                                            <small style="color: gray;">Всплывающее окно с формой для заполнения контактов.</small>--}}
{{--                                        </label>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </td>--}}
{{--                        </tr>--}}

                        <tr>
                            <td>
                                <label for="amount">Изображение</label><br>
                                <small>Не обязательно если планируется текстовый баннер</small>
                            </td>
                            <td>
                                @if ($banner && !empty($banner->picture))
                                    <img src="{{ $banner->pictureUrl() }}" style="max-width: 150px; border-radius: .7rem; margin-bottom: .5rem;" alt="">
                                    <br>
                                @endif

                                <input type="file"
                                       name="picture"
                                       style="padding: 0; border: none;"
                                       accept="image/*"
                                       size="40"
                                       placeholder="0">
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <label for="title">Заголовок</label>
                                <span class="required">*</span>
                            </td>
                            <td>
                                <input name="title"
                                       id="title"
                                       value="{{ old('title', $banner->title) }}" size="35">
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2">
                                <textarea name="body" id="editor" rows="10">{!! old('body', $banner->body) !!}</textarea>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <strong>Цвет текста на баннере</strong>
                                <span class="required">*</span>
                            </td>
                            <td>
                                <div class="d-flex" style="column-gap: 1.5rem; align-items: center">
                                    <div class="d-flex" style="flex-grow: 1; align-items: flex-start; column-gap: .5rem">
                                        <input type="radio"
                                               name="color_scheme"
                                               id="color_scheme_default"
                                               {{ old('color_scheme', $banner->color_scheme ?? 'default') == 'default' ? 'checked' : null }}
                                               value="default">
                                        <label for="color_scheme_default" style="display: flex; flex-direction: column; line-height: 1rem">
                                            Темный (по-умолчанию)
                                        </label>
                                    </div>

                                    <div class="d-flex" style="flex-grow: 1; align-items: flex-start; column-gap: .5rem">
                                        <input type="radio"
                                               name="color_scheme"
                                               id="color_scheme_white"
                                               {{ old('color_scheme', $banner->color_scheme) == 'white' ? 'checked' : null }}
                                               value="white">
                                        <label for="color_scheme_white" style="display: flex; flex-direction: column; line-height: 1rem">
                                            Светлый
                                        </label>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <label for="button_text">Текст на кнопке</label>
                                <span class="required">*</span>
                            </td>
                            <td>
                                <input name="button_text"
                                       id="button_text"
                                       value="{{ old('button_text', $banner->button_text) }}" size="35">
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <label for="button_url">Ссылка на кнопке</label>
                                <span class="required">*</span>
                            </td>
                            <td>
                                <input name="button_url"
                                       id="button_url"
                                       value="{{ old('button_url', $banner->button_url) }}" size="35">
                            </td>
                        </tr>

{{--                        <tr>--}}
{{--                            <td style="line-height: 140% !important">--}}
{{--                                <label for="lead_form">Лид форма (script)</label>--}}
{{--                                <span class="required">*</span><br>--}}
{{--                                <small>--}}
{{--                                    <strong>Пример:</strong><br>--}}
{{--                                    <span style="color: slategray">--}}
{{--                                        &lt;script id="538e161fdd842f816b64fda570ea8d72314d42d3" src="https://altecoschool.ru/pl/lite/widget/script?id=697587"&gt;&lt;/script&gt;--}}
{{--                                    </span>--}}
{{--                                </small>--}}
{{--                            </td>--}}
{{--                            <td>--}}
{{--                                <input name="button_text"--}}
{{--                                       id="button_text"--}}
{{--                                       value="{{ old('button_text', $banner->button_text) }}" size="35">--}}
{{--                            </td>--}}
{{--                        </tr>--}}

                        <tr>
                            <td>
                                <label for="is_active">Активный</label>
                            </td>
                            <td style="line-height: 1rem !important;">
                                <input type="checkbox"
                                    id="is_active"
                                    name="is_active"
                                    value="1"
                                    {{ old('is_active', $banner->is_active ?? 0) ? 'checked' : null }}
                                />
                                <label for="is_active">Да</label><br>

                                <small style="display: block; margin-top: .5rem;">
                                    Только активные баннеры будут показаны пользователям
                                </small>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                Начало показов
                            </td>
                            <td style="line-height: 1rem !important;">
                                <input type="date"
                                       id="start"
                                       name="start"
                                       value="{{ old('start', $banner->start ? $banner->start->format('Y-m-d') : (new DateTimeImmutable())->modify('+1 hour')->format('Y-m-d')) }}"
                                />

                                <small style="display: block; margin-top: .5rem;">
                                    Укажите дату и время начала показов баннера.
                                </small>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                Завершить показы
                            </td>
                            <td style="line-height: 1rem !important;">
                                <input type="date"
                                       id="end"
                                       name="end"
                                       value="{{ old('end', $banner->end ? $banner->end->format('Y-m-d') : null) }}"
                                />

                                <small style="display: block; margin-top: .5rem;">
                                    Дата и время когда баннер прекратить отображаться.<br>
                                    <strong>Не обязательное поле</strong>
                                </small>
                            </td>
                        </tr>

{{--                        <tr>--}}
{{--                            <td>--}}
{{--                                <label for="delay_seconds">Показать через (секунд)</label>--}}
{{--                            </td>--}}
{{--                            <td>--}}
{{--                                <input type="number"--}}
{{--                                       id="delay_seconds"--}}
{{--                                       name="delay_seconds"--}}
{{--                                       value="{{ old('delay_seconds', $banner->delay_seconds ?? 0) }}"--}}
{{--                                />--}}

{{--                                <small style="display: block; margin-top: .5rem;">--}}
{{--                                    Через заданное количество секунд пользователю откроется "Модальное окно".--}}
{{--                                </small>--}}
{{--                            </td>--}}
{{--                        </tr>--}}

{{--                        <tr>--}}
{{--                            <td>--}}
{{--                                <label for="not_disturb_hours">Не беспокоить (часов)</label>--}}
{{--                            </td>--}}
{{--                            <td>--}}
{{--                                <input type="number"--}}
{{--                                       id="not_disturb_hours"--}}
{{--                                       name="not_disturb_hours"--}}
{{--                                       value="{{ old('not_disturb_hours', $banner->not_disturb_hours ?? 0) }}"--}}
{{--                                />--}}

{{--                                <small style="display: block; margin-top: .5rem; max-width: 400px">--}}
{{--                                    <strong>Например</strong>: если указали "не беспокоить" в течении 1 часа.--}}
{{--                                    После закрытия модального окна пользователем, сайт только через 1 час повторно покажет модальное окно.--}}
{{--                                    Если указано "0", то 1 раз за 1 обновление/открытие страницы,--}}
{{--                                    через указанное в поле "Показать через" секунд.--}}
{{--                                </small>--}}
{{--                            </td>--}}
{{--                        </tr>--}}
                    </tbody>
                </table>

                <p>
                    <button type="submit" class="btn btn2">Сохранить</button>
                </p>
            </form>
        </div>
    </div>

    <style>
        .row {
            display: flex;
            flex-direction: column;
            row-gap: 4rem;
            column-gap: 4rem;
        }

        .row .column {
            max-width: 100%;
        }

        @media screen and (min-width: 1024px) {
            .row {
                flex-direction: row;
            }

            .row .column {
                width: 50%;
            }
        }

        .ck ul li {
            margin-left: 2.5rem;
            list-style: disc;
        }

    </style>

    @push('scripts', '<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>')
    <script>
        document.addEventListener("DOMContentLoaded", (event) => {
            ClassicEditor
                .create(document.querySelector('#editor'), {
                    height: '200px',
                    toolbar: {
                        items: [
                            'numberedList', 'bulletedList', '|', 'bold', 'italic', 'underline', 'strikethrough', '|', 'link', '|', 'undo', 'redo'
                        ]
                    },
                })
                .then( editor => {
                    console.log( editor );
                } )
                .catch( error => {
                    console.error( error );
                } );
        });
    </script>
@endsection

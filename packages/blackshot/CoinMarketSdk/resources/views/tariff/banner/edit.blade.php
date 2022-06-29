@extends('blackshot::layouts.app')

@section('title', 'Настройка баннера')

@section('content')
    <div class="row">
        <div class="column">
            <form action="{{ route('tariffs.banners.store') }}" method="post" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="uuid" value="{{ $banner->uuid ?? null }}">
                <input type="hidden" name="tariff_id" value="{{ $tariff->id ?? null }}">

                <table class="table-setting">
                    <tbody>
                        <tr>
                            <td>
                                <label for="name"></label>
                            </td>
                            <td>
                                <strong>
                                    Баннер для тарифа "{{ $tariff->name }}"
                                </strong>
                            </td>
                        </tr>

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
                            <td colspan="2">
                                <textarea name="body" id="editor" rows="10">{!! old('body', $banner->body) !!}</textarea>
                            </td>
                        </tr>

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
                                Начало завершения
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

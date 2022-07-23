@extends('blackshot::layouts.app')

@section('title', $coin->name)

@section('content')
    <div class="row">
        <div class="column">
            <form action="{{ route('coins.store') }}" method="post">
                @csrf

                <input type="hidden" name="uuid" value="{{ $coin->uuid }}">

                <table class="table-setting">
                    <tbody>
                        <tr>
                            <td>
                                <label for="name">Рейтинг AltEco</label>
                            </td>
                            <td>
                                <input type="text"
                                       value="{{ old('name', $coin->alteco) }}"
                                       name="alteco"
                                       id="alteco"
                                       size="40"
                                       placeholder="1 - 100">
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2">
                                <p>
                                    Категории оценки для рейтинга ALTECO:
                                </p>

                                @if (!$coin->alteco_desc)
                                    <small style="color: red">Категории оценки пока не заполнены, ниже готовый шаблон для заполнения. Очистите поле, если нужно убрать текст у
                                        {{ $coin->name }}.</small>
                                @endif

                                @php($default_desc = "<b>Категории оценки для рейтинга ALTECO</b>:
Инвесторы:
Команда:
Продукт:
Социальные активности:
Дорожная карта: ")
                                <textarea name="alteco_desc"
                                          data-type="editor"
                                          rows="10">{!! old('alteco_desc', $coin->alteco_desc ?? nl2br($default_desc)) !!}</textarea>
                            </td>
                        </tr>


                        <tr>
                            <td colspan="2" style="padding-top: 2rem;">
                                <div class="d-flex flex-center">
                                    @if(!empty($coin->info->logo))
                                        <img src="{{ $coin->info->logo }}" class="table__logo" alt="">
                                    @endif
                                    <strong>Описание {{ $coin->name }}</strong>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <textarea name="description" data-type="editor" rows="10">{!! old('description', $coin->info?->description) !!}</textarea>
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
    @push('scripts', '<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>')
    <script>
        document.addEventListener("DOMContentLoaded", (event) => {
            document.querySelectorAll('[data-type="editor"]').forEach(editor => {
                ClassicEditor
                    .create(editor, {
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
            })
        });
    </script>
@endsection

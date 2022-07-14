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
                                    <strong>Описание</strong>
                                </p>
                                <textarea name="description" id="editor" rows="10">{!! old('description', $coin->info?->description) !!}</textarea>
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

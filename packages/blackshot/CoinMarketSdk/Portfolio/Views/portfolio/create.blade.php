@extends('blackshot::layouts.modal-form', [
    'title' => 'Создать портфолио',
    'form' => route('api.portfolio.create'),
    'formClass' => 'portfolio-form'
])

@push('footer', '<button type="submit" class="btn btn1">Создать портфолио</button>')

@section('form-body')
<input type="hidden" name="user_id" value="{{ Auth::id() }}">

<div class="fields-row">
    <div class="field-control">
        <label for="quantity">Название портфолио</label>
        <input type="text"
               value="{{ Auth::user()->portfolios()->count() ? '' : 'Портфолио' }}"
               name="name"
               maxlength="32"
               size="40"
               placeholder="Название"
               autofocus>
    </div>
</div>
@endsection

<?php

use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

// Home
Breadcrumbs::for('home', function ($trail) {
    $trail->push('Криптосканер', route('coins.home'));
});

// Home > Coins
Breadcrumbs::for('coins.home', function ($trail) {
//    $trail->parent('home');
    $trail->push('Криптосканер', route('coins.home'));
});

// Home > Coins > {Coin}
Breadcrumbs::for('coins.view', function ($trail, $data = []) {
    $trail->parent('coins.home');
    $trail->push($data['coin']->name, route('coins.view', $data['coin']->uuid));
});

// Home > Coins > {Coin}
Breadcrumbs::for('coins.edit', function ($trail, $data = []) {
    $trail->parent('coins.home');
    $trail->push($data['coin']->name, route('coins.view', $data['coin']->uuid));
    $trail->push('Редактирование', route('coins.edit', $data['coin']->uuid));
});

// Home > Signals
Breadcrumbs::for('signals.home', function ($trail, $data = []) {
    $trail->parent('coins.home');
    $trail->push('Сигналы', route('signals.home'));
});

// Home > Tariffs
Breadcrumbs::for('tariffs.home', function ($trail) {
    $trail->parent('home');
    $trail->push('Тарифы', route('tariffs.home'));
});

Breadcrumbs::for('tariffs.edit', function ($trail, $data = []) {
    $name = key_exists('breadcrumb_data', $data) && $data['breadcrumb_data']
        ? $data['breadcrumb_data']->name
        : 'Новый тариф';

    $trail->parent('tariffs.home');
    $trail->push($name, route('tariffs.home'));
});

Breadcrumbs::for('banners.home', function ($trail, $data = []) {
//    $trail->parent('home');
    $trail->push('Баннеры', route('banners.home'));
});

// Home > Portfolio
Breadcrumbs::for('portfolio', function ($trail, $data = []) {
    $trail->parent('home');
    $trail->push('Портфолио', route('portfolio.home'));
});

Breadcrumbs::for('banners.edit', function ($trail, $data = []) {

    if (key_exists('breadcrumb_data', $data) && $data['breadcrumb_data']) {
        //
        $banner = key_exists('banner', $data['breadcrumb_data'])
            ? $data['breadcrumb_data']['banner']
            : null;
    }

    $trail->parent('banners.home');
    $trail->push($banner->title ?? 'Добавить баннер', route('banners.edit', $banner));
});


// Home > Users
Breadcrumbs::for('users.home', function ($trail) {
    $trail->parent('home');
    $trail->push('Пользователи', route('users.home'));
});

Breadcrumbs::for('users.edit', function ($trail) {
    $trail->parent('users.home');
    $trail->push('Редактировать пользователя', route('users.edit'));
});

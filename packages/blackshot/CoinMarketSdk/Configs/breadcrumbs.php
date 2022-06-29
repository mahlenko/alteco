<?php

use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

// Home
Breadcrumbs::for('home', function ($trail) {
    $trail->push('Главная', route('home'));
});

// Home > Coins
Breadcrumbs::for('coins.home', function ($trail) {
    $trail->parent('home');
    $trail->push('Монеты', route('coins.home'));
});

// Home > Coins > {Coin}
Breadcrumbs::for('coins.view', function ($trail, $data = []) {
    $trail->parent('coins.home');
    $trail->push($data['coin']->name, route('coins.view', $data['coin']->uuid));
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

Breadcrumbs::for('tariffs.banners.edit', function ($trail, $data = []) {

    $tariff = null;
    $banner = null;

    if (key_exists('breadcrumb_data', $data) && $data['breadcrumb_data']) {
        //
        $tariff = key_exists('tariff', $data['breadcrumb_data'])
            ? $data['breadcrumb_data']['tariff']
            : null;

        //
        $banner = key_exists('banner', $data['breadcrumb_data'])
            ? $data['breadcrumb_data']['banner']
            : null;
    }

    $trail->parent('tariffs.home');
    $trail->push($tariff->name, route('tariffs.edit', $tariff));
    $trail->push($banner->uuid ?? 'Добавить баннер', route('tariffs.banners.edit', $tariff, $banner));
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

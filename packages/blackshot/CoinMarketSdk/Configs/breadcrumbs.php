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


// Home > Users
Breadcrumbs::for('users.home', function ($trail) {
    $trail->parent('home');
    $trail->push('Пользователи', route('users.home'));
});

Breadcrumbs::for('users.edit', function ($trail) {
    $trail->parent('users.home');
    $trail->push('Редактировать пользователя', route('users.edit'));
});

<?php

use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

// Home
Breadcrumbs::for('home', function ($trail) {
    $trail->push('Home', route('home'));
});

// Home > Coins
Breadcrumbs::for('coins.home', function ($trail) {
    $trail->parent('home');
    $trail->push('Coins', route('coins.home'));
});

// Home > Coins > {Coin}
Breadcrumbs::for('coins.view', function ($trail, $data = []) {
    $trail->parent('coins.home');
    $trail->push($data['coin']->name, route('coins.view', $data['coin']->uuid));
});

// Home > Signals
Breadcrumbs::for('signals.home', function ($trail, $data = []) {
    $trail->parent('coins.home');
    $trail->push('Signals', route('signals.home'));
});


// Home > Users
Breadcrumbs::for('users.home', function ($trail) {
    $trail->parent('home');
    $trail->push('Users', route('users.home'));
});

Breadcrumbs::for('users.edit', function ($trail) {
    $trail->parent('users.home');
    $trail->push('Edit user', route('users.edit'));
});

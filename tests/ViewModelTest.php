<?php

use StackTrace\Inertia\View\Models\ArrayViewModel;
use StackTrace\Inertia\ViewModel;

function model(array $model): ViewModel {
    return ArrayViewModel::make($model);
}

it('should render to view', function () {
    $model = model([
        'first_name' => 'Peter',
        'lastName' => 'Stovka',
    ]);

    expect($model->toView())->toMatchArray([
        'first_name' => 'Peter',
        'lastName' => 'Stovka',
    ]);
});

it('should convert to camel case when passing to view through toArray', function () {
    expect(model([
        'first_name' => 'Peter',
        'lastName' => 'Stovka',
    ]))->toArray()->toMatchArray([
        'firstName' => 'Peter',
        'lastName' => 'Stovka',
    ]);
});

it('should convert to snake case when passing to json through jsonSerialize', function () {
    expect(model([
        'first_name' => 'Peter',
        'lastName' => 'Stovka',
    ]))->jsonSerialize()->toMatchArray([
        'first_name' => 'Peter',
        'last_name' => 'Stovka',
    ]);
});

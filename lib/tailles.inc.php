<?php
function get_taille($slug) {
    if(!$slug) {
        $slug='medium';
    }
    return get_tailles()[$slug]??false;
}
function get_tailles() {
    return [
        'micro' => [
            'slug'=>'micro',
            'width'=>20,
            'quality'=>100
        ],
        'thumbnail' => [
            'slug'=>'smal',
            'width'=>100,
            'quality'=>80
        ],
        'small' => [
            'slug'=>'smal',
            'width'=>300,
            'quality'=>90
        ],
        'medium' => [
            'slug'=>'medium',
            'width'=>500,
            'quality'=>80
        ],
        'big'=> [
            'slug'=>'big',
            'width'=>false,
            'quality'=>80
        ]
    ];
}
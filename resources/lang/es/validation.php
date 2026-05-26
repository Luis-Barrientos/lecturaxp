<?php

return [
    'required'             => 'El campo :attribute es obligatorio.',
    'email'                => 'El campo :attribute debe ser un correo válido.',
    'min'                  => ['string' => 'El campo :attribute debe tener al menos :min caracteres.'],
    'max'                  => ['string' => 'El campo :attribute no puede tener más de :max caracteres.'],
    'unique'               => 'Este :attribute ya está registrado.',
    'confirmed'            => 'La confirmación de :attribute no coincide.',
    'string'               => 'El campo :attribute debe ser texto.',

    'attributes' => [
        'name'                  => 'nombre',
        'email'                 => 'correo electrónico',
        'password'              => 'contraseña',
        'password_confirmation' => 'confirmación de contraseña',
    ],
];
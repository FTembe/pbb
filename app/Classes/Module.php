<?php

namespace App\Classes;

class Module
{
    public function get(): array
    {


        return [
            'shop' => [
                'Usuario' => [
                    'create'=>'criar',
                    'read'=>'ver',
                    'update'=>'remover',
                    'delete'=>'actualizar',
                ],
                
                'nivel de acesso' => [
                    'create'=>'criar',
                    'read'=>'ver',
                    'update'=>'remover',
                    'delete'=>'actualizar',
                ]
                ,
                
                'Marcas' => [
                    'create'=>'criar',
                    'read'=>'ver',
                    'update'=>'remover',
                    'delete'=>'actualizar',
                ]
            ]
        ];
    }
}

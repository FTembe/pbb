<?php

namespace App\Classes;

class Messages
{

    public $set = null;

    public function get($key, $personalize = '')
    {
        if ($this->set) {

            return $this->set;
        }

        $collections =  $this->collections();

        if (!key_exists($key, $collections))
            return 'O erro detectado é desconhecido , informe o seguinte codigo ao administrador do sistema ' . $key;
        return $collections[$key];
    }

    public function collections()
    {
        return
            [
                /* ACCESS GLOBAL MESSAGE */
                'not_found' => 'A sua pesquisa  não encontrou nenhum resultado.',
                's_update' => 'Actualizado com sucesso',
                'e_update' => 'Erro de actualização',
                's_create' => 'Criado com sucesso',
                'e_create' => 'Ocorreu um erro ao tentar salvar os dados',
                'exist' => 'O dado introduzido faz parte da lista de registros',
                's_remove' => 'Removido com sucesso',
                'error' => 'Ocorreu um erro ao tentar executar a operação',
                
                /*PRODUCTS*/
                
                'e_create_only_product_category' => 'Ocorreu um erro ao adicionar as categorias',
                'e_create_only_supply' => 'Ocorreu um erro ao criar o stock',
                'e_create_only_info' => 'Ocorreu um erro ao adicionar informações',

                /* ACCESS LEVEL MESSAGE */
                'cr_access_level' => 'Nivel de acesso criado com sucesso',
                'up_access_level' => 'Error ao tentar criar',
                'eup_access_level' => 'Error ao tentar actualizar o nivel de acesso',
                'ecr_access_level' => 'Error ao tentar criar',
                'rm_access_level' => 'Nivel de acesso removido com sucesso.',
                'ras_access_level' => 'Este Nivel de acesso, encontra-se associado a pelo menos 1 usuario ',
            ];
    }
}

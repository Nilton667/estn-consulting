<?php

include_once '../conexao.php';

Class Motorista{

    private $id, $motorista_id, $set_id, $senha, $data;

	function __construct()
	{
        $this->id = Components\getSession('maestro_adm', 'id', 1,  true);

        $this->motorista_id  = post('motorista_id', false)
        ? filterVar(post('motorista_id'))
        : DEFAULT_INT;

        $this->set_id        = post('set_id', false)
        ? filterInt(post('set_id')) 
        : DEFAULT_INT;

        $this->senha        = post('senha', false)
        ? filterVar(post('senha'))  
        : '';

        $this->data          = date('d/m/Y');
	}

    function removeData() //Remover usuario
    {
        $this->motorista_id = array_map('intval' ,explode(',', $this->motorista_id));
        $key = array_search('', $this->motorista_id);

        if($key!==false){
            unset($this->motorista_id[$key]);
        }

        $delete = DB\Mysql::delete(
            "DELETE FROM uber_motoristas WHERE id IN(".implode(',', $this->motorista_id).")",
            []
        );
        if (is_numeric($delete) && $delete > 0){
            return 1;
        }else{
            return $delete;
        }

    }

    //Cadastrar usuario
    function setCadastro()
    {
        //Verificar se o motorista ja foi registado
        if(is_array(DB\Mysql::select(
            "SELECT id_usuario FROM uber_motoristas WHERE id_usuario = :set_id",
            ['set_id' => $this->set_id]
            ))
        ): return 'Este motorista ja se encontra registado!'; endif;

        //Verificar se a conta de usuario existe
        if(!is_array(DB\Mysql::select(
            "SELECT id FROM usuarios WHERE id = :id",
            ['id' => $this->set_id]
        ))): return 'Usuário não encontrado!'; endif;

        $ref = '#'.date('dmyHis');

        $insert = DB\Mysql::insert(
            "INSERT INTO uber_motoristas (ref, id_usuario, senha, registo) VALUES (:ref, :id_usuario, :senha, :registo)",
            [
               'ref'        => $ref,
               'id_usuario' => $this->set_id,
               'senha'      => $this->senha,
               'registo'    => $this->data
            ]
        );

        if(is_numeric($insert) && $insert > 0){
            return 1;
        }else{
            return 'Serviço indisponível!';
        }
    }

}

if(post('remove_motorista')):

    $data = new Motorista();
    eco($data->removeData(), true);
    exit();

elseif(post('cadastro')):

    $data = new Motorista();
    eco($data->setCadastro(), true);
    exit();

endif;

?>
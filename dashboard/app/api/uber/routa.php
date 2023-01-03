<?php

include_once '../conexao.php';

Class Routa{

    private $id, $routa_id;

	function __construct()
	{
        $this->id = Components\getSession('maestro_adm', 'id', 1,  true);

        $this->routa_id   = post('routa_id', false)
        ? filterVar(post('routa_id'))  
        : DEFAULT_INT;
	}

    function removeData() //Remover reserva
    {

        $this->routa_id = array_map('intval' ,explode(',', $this->routa_id));
        $key = array_search('', $this->routa_id);

        if($key!==false){
            unset($this->routa_id[$key]);
        }

        $status = 1; 

        foreach ($this->routa_id as $key => $value) {
            $delete = DB\Mysql::delete(
                "DELETE FROM uber_routas WHERE id = :id",
                ['id' => $value]
            );
            if($delete <= 0){
                $status++;
                break;
            }
        }

        return $status;

    }

}

if(post('remove_routa')):

    $data = new Routa();
    eco($data->removeData(), true);
    exit();

endif;

?>
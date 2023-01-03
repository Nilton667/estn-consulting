<?php

include_once '../conexao.php';

Class Podcast{

    private $id, $id_album, $id_artista, $titulo, $descricao, $podcast_id, $file, $old_img, $old_audio, $origem, $audio, $audioFile, $data_lancamento, $registo;

    //PASTA
    private $folder       = '../../../../publico/img/podcast/';
    private $folderAudio  = '../../../../publico/transmicao/podcast/';

	function __construct()
	{
        $this->id = Components\getSession('maestro_adm', 'id', 1,  true);

        $this->podcast_id   = post('podcast_id', false)
        ? filterVar(post('podcast_id'))  
        : DEFAULT_INT;

        $this->id_artista   = post('id_artista', false)
        ? filterVar(post('id_artista'))  
        : DEFAULT_INT;

        $this->id_album     = post('id_album', false)
        ? filterVar(post('id_album'))  
        : DEFAULT_INT;

        $this->titulo       = post('titulo', false)
        ? filterVar(post('titulo'))  
        : DEFAULT_STRING;

        $this->descricao    = post('descricao', false)
        ? filterVar(post('descricao'))  
        : DEFAULT_STRING;

        $this->origem       = post('origem', false)
        ? filterVar(post('origem'))  
        : '';

        $this->audio        = post('audio', false)
        ? trim(post('audio')) 
        : '';

        $this->old_img    = post('old_img', false)
        ? filterVar(post('old_img'))  
        : DEFAULT_STRING;

        $this->old_audio  = post('old_audio', false)
        ? base64_decode(filterVar(post('old_audio')))
        : DEFAULT_STRING;

        $this->data_lancamento = post('data_lancamento', false)
        ? filterVar(post('data_lancamento'))  
        : DEFAULT_STRING;

        $this->registo   = date('d/m/Y');

        $this->file      = _file('img');

        $this->audioFile = _file('audioFile');
    }
    
    function verificacao(){
        if(is_array(DB\Mysql::select(
            'SELECT id FROM podcast_audio WHERE titulo = :titulo AND descricao = :descricao',
            [
                'titulo'    => $this->titulo, 
                'descricao' => $this->descricao
            ]
        ))){ return 'Este podcast já se encontra registado!'; }else{ return 0; }
    }

    function setPodcast() //Criar podcast
    {
        $verificacao     = $this->verificacao();
        if($verificacao !== 0): return $verificacao; endif;

        //Upload de arquivo de audio
        if($this->audioFile !== false && $this->origem == 'file'){
            $uploadAudio = Components\uploadFile::upload(
                $this->audioFile, 
                $this->folderAudio, 
                ['audio/mp3', 'audio/mpeg'],
                (1024 * 1024 * 20) // 20MB
            );

            if(is_array($uploadAudio) && isset($uploadAudio[0])){
                $this->audio = $uploadAudio[0]['name'];
            }else{
                return $uploadAudio;
            }
        }

        if ($this->file === false){
            $insert = DB\Mysql::insert(
                'INSERT podcast_audio (id_album, id_artista, titulo, descricao, origem, audio, data_lancamento, registo) VALUES (:id_album, :id_artista, :titulo, :descricao, :origem, :audio, :data_lancamento, :registo)',
                [
					'id_album'        => $this->id_album,
					'id_artista'      => $this->id_artista,
                    'titulo'          => $this->titulo,
                    'descricao'       => $this->descricao,
					'origem'          => $this->origem,
					'audio'           => $this->audio,
					'data_lancamento' => $this->data_lancamento,
                    'registo'         => $this->registo
                ]
            );
            return $insert;
        }else{
            //Adicionando imagem ao artista
            $upload = Components\uploadFile::upload(
                $this->file,
                $this->folder,
                ['image/png', 'image/jpg', 'image/jpeg'],
                (1024 * 1024 * 2) // 2MB
            );

            if(is_array($upload) && isset($upload[0])){
                $insert = DB\Mysql::insert(
                    'INSERT podcast_audio (id_album, id_artista, titulo, imagem, descricao, origem, audio, data_lancamento, registo) VALUES (:id_album, :id_artista, :titulo, :imagem, :descricao, :origem, :audio, :data_lancamento, :registo)',
                    [
						'id_album'        => $this->id_album,
						'id_artista'      => $this->id_artista,
						'titulo'          => $this->titulo,
						'imagem'          => $upload[0]['name'],
						'descricao'       => $this->descricao,
						'origem'          => $this->origem,
						'audio'           => $this->audio,
						'data_lancamento' => $this->data_lancamento,
						'registo'         => $this->registo
                    ]
                );
                return $insert;
            }else{
                return $upload;
            }
        }
    }

    function editPodcast() //Editar podcast
    {

        //Upload de arquivo de audio
        if($this->audioFile !== false && $this->origem == 'file'){
            $uploadAudio = Components\uploadFile::upload(
                $this->audioFile,
                $this->folderAudio,
                ['audio/mp3', 'audio/mpeg'],
                (1024 * 1024 * 20) // 20MB
            );
            if(is_array($uploadAudio) && isset($uploadAudio[0])){
                $this->audio = $uploadAudio[0]['name'];
            }else{
                return $uploadAudio;
            }
        }

        if($this->audio == ''){
            $this->audio = $this->old_audio;
        }
        
        if ($this->file === false){
            $update = DB\Mysql::update(
                'UPDATE podcast_audio SET id_album = :id_album, id_artista = :id_artista, titulo = :titulo, descricao = :descricao, origem = :origem, audio = :audio, data_lancamento = :data_lancamento WHERE id = :id',
                [
                    'id'              => $this->podcast_id, 
					'id_album'        => $this->id_album,
					'id_artista'      => $this->id_artista,
					'titulo'          => $this->titulo,
					'descricao'       => $this->descricao,
					'origem'          => $this->origem, 
					'audio'           => $this->audio,
					'data_lancamento' => $this->data_lancamento
                ]
            );

            if(is_numeric($update) && $update > 0){
                if($this->audioFile !== false && $this->origem == 'file'){
                    @unlink($this->folderAudio.$this->old_audio);
                }else if($this->origem == 'link' || $this->origem == 'soundcloud' && is_file($this->folderAudio.$this->old_audio)){
                    @unlink($this->folderAudio.$this->old_audio);
                }
                return 1;
            }else{
                return $update;
            }

        }else{
            //Alterando imagem do podcast
            $upload = Components\uploadFile::upload(
                $this->file,
                $this->folder,
                ['image/png', 'image/jpg', 'image/jpeg'],
                (1024 * 1024 * 2) // 2MB
            );
            if(is_array($upload) && isset($upload[0])){
                $update = DB\Mysql::update(
                    'UPDATE podcast_audio SET id_album = :id_album, id_artista = :id_artista, titulo = :titulo, imagem = :imagem, descricao = :descricao, origem = :origem, audio = :audio, data_lancamento = :data_lancamento WHERE id = :id',
                    [
						'id'              => $this->podcast_id, 
						'id_album'        => $this->id_album,
						'id_artista'      => $this->id_artista,
						'titulo'          => $this->titulo,
						'imagem'          => $upload[0]['name'],
						'descricao'       => $this->descricao,
						'origem'          => $this->origem, 
						'audio'           => $this->audio,
						'data_lancamento' => $this->data_lancamento
                    ]
                );
                if(is_numeric($update) && $update > 0){
                    @unlink($this->folder.$this->old_img);
                    if($this->audioFile !== false && $this->origem == 'file'){
                        @unlink($this->folderAudio.$this->old_audio);
                    }else if($this->origem == 'link' || $this->origem == 'soundcloud' && is_file($this->folderAudio.$this->old_audio)){
                        @unlink($this->folderAudio.$this->old_audio);
                    }
                    return 1;
                }else{
                    return $update;
                }
            }else{
                return $upload;
            }
        }

    }

    function removeData() //Remover podcast
    {
        $this->podcast_id = array_map('intval' ,explode(',', $this->podcast_id));
        $key = array_search('', $this->podcast_id);

        if($key!==false){
            unset($this->podcast_id[$key]);
        }

        $status = 1;

        foreach ($this->podcast_id as $key => $value){
            $select = DB\Mysql::select(
                'SELECT id, imagem, audio FROM podcast_audio WHERE id = :id',
                ['id' => $value]
            );
            if(is_array($select)){
                $delete = DB\Mysql::delete(
                    "DELETE FROM podcast_audio WHERE id = :id",
                    ['id' => $value]
                );
                if($delete <= 0){
                    $status = 0;
                    break;
                }else{
                    @unlink($this->folder.$select[0]['imagem']);
					@unlink($this->folderAudio.$select[0]['audio']);
                }
            }else{
                $status++;
                break;
            }
        }

        return $status;
    }

}

if(post('add_podcast')):

    $data = new Podcast();
    eco($data->setPodcast(), true);
    exit();

elseif(post('edit_podcast')):

    $data = new Podcast();
    eco($data->editPodcast(), true);
    exit();

elseif(post('remove_podcast')):

    $data = new Podcast();
    eco($data->removeData(), true);
    exit();

endif;

?>
<?php

include_once '../conexao.php';

Class Transmicao{

    private $id, $id_album, $id_artista, $titulo, $descricao, $transmicao_id, $file, $old_img, $old_video, $origem, $video, $videoFile, $data_lancamento, $registo;

    //PASTA
    private $folder       = '../../../../publico/img/podcast/';
    private $folderVideo  = '../../../../publico/transmicao/video/';

	function __construct()
	{
        $this->id = Components\getSession('maestro_adm', 'id', 1,  true);

        $this->transmicao_id   = post('transmicao_id', false)
        ? filterVar(post('transmicao_id'))  
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

        $this->video        = post('video', false)
        ? trim(post('video')) 
        : '';

        $this->old_img    = post('old_img', false)
        ? filterVar(post('old_img'))  
        : DEFAULT_STRING;

        $this->old_video  = post('old_video', false)
        ? base64_decode(filterVar(post('old_video')))
        : DEFAULT_STRING;

        $this->data_lancamento = post('data_lancamento', false)
        ? filterVar(post('data_lancamento'))  
        : DEFAULT_STRING;

        $this->registo   = date('d/m/Y');

        $this->file      = _file('img');

        $this->videoFile = _file('videoFile');
    }
    
    function verificacao(){
        if(is_array(DB\Mysql::select(
            'SELECT id FROM podcast_video WHERE titulo = :titulo AND descricao = :descricao',
            [
                'titulo'    => $this->titulo, 
                'descricao' => $this->descricao
            ]
        ))){ return 'Este podcast já se encontra registado!'; }else{ return 0; }
    }

    function setTransmicao() //Criar Transmicao
    {
        $verificacao     = $this->verificacao();
        if($verificacao !== 0): return $verificacao; endif;

        //Upload de arquivo de video
        if($this->videoFile !== false && $this->origem == 'file'){
            $uploadVideo = Components\uploadFile::upload(
                $this->videoFile, 
                $this->folderVideo, 
                ['video/mp4', 'video/ogg'], 
                (1024 * 1024 * 20) // 20MB
            );

            if(is_array($uploadVideo) && isset($uploadVideo[0])){
                $this->video = $uploadVideo[0]['name'];
            }else{
                return $uploadVideo;
            }
        }

        if ($this->file === false){
            $insert = DB\Mysql::insert(
                'INSERT podcast_video (id_album, id_artista, titulo, descricao, origem, video, data_lancamento, registo) VALUES (:id_album, :id_artista, :titulo, :descricao, :origem, :video, :data_lancamento, :registo)',
                [
					'id_album'        => $this->id_album,
					'id_artista'      => $this->id_artista,
                    'titulo'          => $this->titulo,
                    'descricao'       => $this->descricao,
					'origem'          => $this->origem,
					'video'           => $this->video,
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
                    'INSERT podcast_video (id_album, id_artista, titulo, imagem, descricao, origem, video, data_lancamento, registo) VALUES (:id_album, :id_artista, :titulo, :imagem, :descricao, :origem, :video, :data_lancamento, :registo)',
                    [
						'id_album'        => $this->id_album,
						'id_artista'      => $this->id_artista,
						'titulo'          => $this->titulo,
						'imagem'          => $upload[0]['name'],
						'descricao'       => $this->descricao,
						'origem'          => $this->origem,
						'video'           => $this->video,
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

    function editTransmicao() //Editar Transmicao
    {

        //Upload de arquivo de video
        if($this->videoFile !== false && $this->origem == 'file'){
            $uploadVideo = Components\uploadFile::upload(
                $this->videoFile,
                $this->folderVideo,
                ['video/mp4', 'video/ogg'], 
                (1024 * 1024 * 20) // 20MB
            );
            if(is_array($uploadVideo) && isset($uploadVideo[0])){
                $this->video = $uploadVideo[0]['name'];
            }else{
                return $uploadVideo;
            }
        }

        if($this->video == ''){
            $this->video = $this->old_video;
        }
        
        if ($this->file === false){
            $update = DB\Mysql::update(
                'UPDATE podcast_video SET id_album = :id_album, id_artista = :id_artista, titulo = :titulo, descricao = :descricao, origem = :origem, video = :video, data_lancamento = :data_lancamento WHERE id = :id',
                [
                    'id'              => $this->transmicao_id, 
					'id_album'        => $this->id_album,
					'id_artista'      => $this->id_artista,
					'titulo'          => $this->titulo,
					'descricao'       => $this->descricao,
					'origem'          => $this->origem, 
					'video'           => $this->video,
					'data_lancamento' => $this->data_lancamento
                ]
            );

            if(is_numeric($update) && $update > 0){
                if($this->videoFile !== false && $this->origem == 'file'){
                    @unlink($this->folderVideo.$this->old_video);
                }else if($this->origem == 'link'){
                    @unlink($this->folderVideo.$this->old_video);
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
                    'UPDATE podcast_video SET id_album = :id_album, id_artista = :id_artista, titulo = :titulo, imagem = :imagem, descricao = :descricao, origem = :origem, video = :video, data_lancamento = :data_lancamento WHERE id = :id',
                    [
						'id'              => $this->transmicao_id, 
						'id_album'        => $this->id_album,
						'id_artista'      => $this->id_artista,
						'titulo'          => $this->titulo,
						'imagem'          => $upload[0]['name'],
						'descricao'       => $this->descricao,
						'origem'          => $this->origem, 
						'video'           => $this->video,
						'data_lancamento' => $this->data_lancamento
                    ]
                );
                if(is_numeric($update) && $update > 0){
                    @unlink($this->folder.$this->old_img);
                    if($this->videoFile !== false && $this->origem == 'file'){
                        @unlink($this->folderVideo.$this->old_video);
                    }else if($this->origem == 'link'){
                        @unlink($this->folderVideo.$this->old_video);
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
        $this->transmicao_id = array_map('intval' ,explode(',', $this->transmicao_id));
        $key = array_search('', $this->transmicao_id);

        if($key!==false){
            unset($this->transmicao_id[$key]);
        }

        $status = 1;

        foreach ($this->transmicao_id as $key => $value){
            $select = DB\Mysql::select(
                'SELECT id, imagem, video FROM podcast_video WHERE id = :id',
                ['id' => $value]
            );
            if(is_array($select)){
                $delete = DB\Mysql::delete(
                    "DELETE FROM podcast_video WHERE id = :id",
                    ['id' => $value]
                );
                if($delete <= 0){
                    $status = 0;
                    break;
                }else{
                    @unlink($this->folder.$select[0]['imagem']);
					@unlink($this->folderVideo.$select[0]['video']);
                }
            }else{
                $status++;
                break;
            }
        }

        return $status;
    }

}

if(post('add_transmicao')):

    $data = new Transmicao();
    eco($data->setTransmicao(), true);
    exit();

elseif(post('edit_transmicao')):

    $data = new Transmicao();
    eco($data->editTransmicao(), true);
    exit();

elseif(post('remove_transmicao')):

    $data = new Transmicao();
    eco($data->removeData(), true);
    exit();

endif;

?>
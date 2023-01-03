<?php
	if(isset($_GET['edit'])){
		$data = new Util();
		$data = $data->getTransmicao();
		$data = json_decode($data);
	}
?>
<div class="row justify-content-center">
    <div class="col-12 mt-2">
        <div class="row">
            <?php
            if(isset($_GET['edit'])):
                if(is_file('../publico/img/podcast/'.$data[0]->imagem)){
                    ?>
                    <div class="col-12 col-sm-6">
                        <p class="lead"><b>Imagem</b></p>
                        <div class="row edit-img">
                            <div class="col-6 col-sm-7 col-md-5">
                                <div>
                                    <a class="image-link" href="../publico/img/podcast/<?= $data[0]->imagem ?>">
                                        <img src="../publico/img/podcast/<?= $data[0]->imagem ?>">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }

                if(isset($_GET['edit']) && isset($data[0]->id) && $data[0]->origem == 'file' || isset($_GET['edit']) && isset($data[0]->id) && $data[0]->origem == 'link'){
                    ?>
                    <div class="col-12 col-sm-6">
                        <p class="lead"><b>Video</b></p>
                        <div>
                            <?php
                                if($data[0]->origem == 'link'){
                                    ?>
                                    <video
                                    style="width: 100%;  border-radius: 8px;"
                                    controls
                                    src="<?= $data[0]->video; ?>"></video>
                                    <?php
                                }else{
                                    ?>
                                    <video
                                    style="width: 100%;  border-radius: 8px;"
                                    controls
                                    src="../publico/transmicao/video/<?= $data[0]->video; ?>"></video>
                                    <?php
                                }
                            ?>
                        </div>
                    </div>
                    <?php
                }

            endif;
            ?>
        </div>
        <form id="form-transmicao" method="POST" enctype="multipart/form-data">
            <div class="row pb-2">
                <div class="col-12 text-right">
                    <?php
                        if (isset($_GET['edit'])){
                            ?>
                                <button type="button" id="edit-transmicao" class="btn btn-primary"><i class="las la-edit"></i></button>
                            <?php
                        }else{
                            ?>
                                <button type="reset" id="reset-transmicao" class="btn btn-danger">
                                    <i class="las la-redo-alt"></i>
                                </button>
                                <button type="button" id="add-transmicao" class="btn btn-primary">
                                    <i class="las la-paper-plane"></i>
                                </button>
                            <?php
                        }
                        $edit = isset($data[0]->id) ? true : false;
                    ?>
                </div>
            </div>
            
            <div class="row">

                <?php
					if (isset($_GET['edit'])){
						?>
                            <input type="hidden" name="edit_transmicao" value="true">
                            <input type="hidden" name="old_img" value="<?= $data[0]->imagem; ?>">
                            <input type="hidden" name="old_video" value="<?= base64_encode($data[0]->video); ?>">
                            <input type="hidden" name="transmicao_id" value="<?= $data[0]->id; ?>">
                        <?php
                        
					}else{
						?><input type="hidden" name="add_transmicao" value="true"><?php
					}
				?>

                <div class="col-12">
                    <div class="form-group">
                        <label for="transmicao-titulo">Título</label>
                        <input style="outline: none!important; box-shadow: unset!important;" type="text" name="titulo" class="form-control border-top-0 border-left-0 border-right-0 rounded-0 shadow-none" id="transmicao-titulo" placeholder="Ex: News..." value="<?php if($edit){ echo $data[0]->titulo; } ?>">
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <label>Imagem</label>
                    <div class="input-group" style="margin-bottom: 15px;">
                        <div class="custom-file">
                            <input type="file" name="img[]" onchange="fileName(this.value)" class="custom-file-input" id="input-image">
                            <label id="input-file-label" class="custom-file-label" for="input-image">Escolher arquivo</label>
                        </div>
                    </div>
                </div>
                
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label>Artista</label>
                        <select class="custom-select" id="transmicao-id_artista" name="id_artista">
                        <option value="0">-- Selecione um artista --</option>
                        <?php
                            if (is_array($artistas)) {
                                foreach ($artistas as $key => $value) {
                                    ?>
                                        <option 
                                            value="<?= $value['id']; ?>"
                                            <?php 
                                                if($edit){ 
                                                    if($data[0]->id_artista == $value['id']){ 
                                                        echo "selected"; 
                                                    } 
                                                } 
                                            ?>>
                                            <?php echo $value['nome']; ?>
                                        </option>
                                    <?php
                                }
                            }
                        ?>
                        </select>
                    </div>
                </div>
                
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label>Album</label>
                        <select class="custom-select" id="transmicao-id_album" name="id_album">
                        <option value="0">-- Selecione um album --</option>
                        <?php
                            if (is_array($album)) {
                                foreach ($album as $key => $value) {
                                    ?>
                                        <option 
                                            value="<?= $value['id']; ?>"
                                            <?php 
                                                if($edit){ 
                                                    if($data[0]->id_album == $value['id']){ 
                                                        echo "selected"; 
                                                    } 
                                                } 
                                            ?>>
                                            <?php echo $value['nome']; ?>
                                        </option>
                                    <?php
                                }
                            }
                        ?>
                        </select>
                    </div>
                </div>
                
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label>Origem</label>
                        <select class="custom-select" id="transmicao-origem" name="origem">
                            <option value="">-- Selecione a origem --</option>
                            <option <?= isset($data[0]->origem) && $data[0]->origem == 'file'       ? 'selected' : ''; ?> value="file">Arquivo de video</option>
                            <option <?= isset($data[0]->origem) && $data[0]->origem == 'link'       ? 'selected' : ''; ?> value="link">Link Direto</option>
                        </select>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="transmicao-video">Video</label>
                        <?php
                            if($edit && $data[0]->origem == 'link'){
                                ?>
                                    <input type="url" name="video" class="form-control" id="transmicao-video" placeholder="Link de video" value="<?php if($edit && $data[0]->origem != 'file'){ echo $data[0]->video; } ?>">
                                <?php
                            }else if($edit && $data[0]->origem == 'file'){
                                ?>
                                    <input type="file" name="video" class="form-control" id="transmicao-video" placeholder="Selecione um arquivo de video">
                                <?php
                            }else{
                                ?>
                                <input type="url" name="video" class="form-control" id="transmicao-video" placeholder="Link de video">
                                <?php
                            }
                        ?>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="transmicao-data">Data de lançamento</label>
                        <input type="date" name="data_lancamento" class="form-control" id="transmicao-data" placeholder="Ex: 06/06/2021" value="<?php if($edit){ echo $data[0]->data_lancamento; } ?>">
                    </div>
                </div>

                <div class="col-12">
                    <input type="hidden" name="descricao" id="transmicao-descricao"/>
                    <div id="transmicao-text"><?php if($edit){ echo $data[0]->descricao; } ?></div>
                </div>
        
            </div>

        </form>
    </div>
</div>

<!-- Upload pogress -->
<div data-backdrop="static" data-keyboard="false" class="modal fade" id="progress" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="las la-upload"></i></h5>
      </div>
	  
	  <div class="modal-body">
	  	<div class="progress mb-2">
			<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
		</div>
		<p><small>Estamos publicando a sua transmição evite atualizar a paginar ou efetuar qualquer alteração.</small></p>
	  </div>

    </div>
  </div>
</div>

<script src="assets/ckeditor/ckeditor.js"></script>
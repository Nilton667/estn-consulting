<?php
	$artistas = DB\Mysql::select('SELECT * FROM podcast_artista');
	$album    = DB\Mysql::select('SELECT * FROM podcast_album');
?>

<div class="content-wrapper-before" style="height: 120px!important;"></div>
<div class="content-header row">
    <div class="content-header-left col-md-4 col-12 mb-2">
        <h3 class="content-header-title mt-2 mb-0">
			<?php
				if(isset($_GET['edit'])){
					eco('Editar podcast');
				}else if(isset($_GET['new'])){
					eco('Adicionar podcast');
				}else{
					eco('Audio podcast');
				}
			?>
		</h3>
    </div>
</div>
<div class="content-body">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                	<div class="container">
                    <div class="heading-elements-ignore d-flex justify-content-end">
                        <ul class="list-inline mb-0">
							<?php
								if(isset($_GET['edit']) || isset($_GET['new'])){
									?>
									<li>
										<a href="?podcast" class="mr-2">
											<i class="ft-list"></i>
										</a>
                            		</li>
									<?php
								}else{
									?>
									<li>
										<a href="?podcast&new" class="mr-2">
											<i class="ft-plus"></i>
										</a>
                            		</li>
                        			<li><a id="modal-delete" class="mr-2"><i class="ft-trash"></i></a></li>
									<?php
								}
							?>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                        </ul>
                    </div>
                	</div>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">                        
						<?php
							if (isset($_GET['new']) || isset($_GET['edit'])):
								include_once 'app/pg/transmicao/podcast_new.php';
							else:
								
								?>
								<div class="container">
									<div class="row m-0 justify-content-center">
										<div class="col-12 col-md-5">
											<form method="GET">
											<div class="input-group mb-2 card-body-search">
												<input type="hidden" name="podcast">
												<input type="text" class="form-control" name="filtro" value="<?php if(isset($_GET['filtro'])): echo trim($_GET['filtro']); endif; ?>" placeholder="Procure aqui...">
												<div class="input-group-append">
													<button type="submit" class="input-group-text pointer"><i class="las la-search"></i></button>
												</div>											
											</div>
											</form>
										</div>
									</div>
									<div class="row justify-content-center">
										<div class="col-12 mt-2 p-0">
											<?php
											//Definindo a paginação
											if(isset($_GET['pg']) && is_numeric(trim($_GET['pg'])) && trim($_GET['pg']) > 0):           
												$pg = trim($_GET['pg']); 
											else: 
												$pg = 1; 
											endif;

											//Quantidade a mostrar
											@$quantidade = 30;
											@$inicio     = ($pg * $quantidade) - $quantidade;
											@$limit      = ' LIMIT :inicio, :quantidade';

											try{

											if (isset($_GET['filtro']) && $_GET['filtro'] !=''):
												$busca  = filter_var(trim(strip_tags($_GET['filtro'])), FILTER_SANITIZE_STRING);
												$select = "SELECT * from podcast_audio WHERE titulo LIKE '%$busca%'";
												$select.= " OR registo LIKE '%$busca%' ORDER BY id DESC";
											else:
												$select = 'SELECT * from podcast_audio ORDER BY id DESC';
											endif;

											$result = $conexao->getCon(1)->prepare($select.$limit);
											$result->bindParam(':inicio', $inicio, PDO::PARAM_INT);
											$result->bindParam(':quantidade', $quantidade, PDO::PARAM_INT);
											$result ->execute();
											$contar = $result->rowCount();
											if($contar > 0){
												$table        = '<div class="table-responsive">';
												$table       .= '<table class="table table-striped table-hover">';
												$table       .= '<thead>';
												$table       .= '<tr>';
												
												$tableCheck   = trim(
												'<div class="custom-control custom-checkbox">
													<input type="checkbox" class="custom-control-input" id="podcast-id">
													<label class="custom-control-label" for="podcast-id"></label>
												</div>'
												);

												$table.= trim('
													<th scope="col">#</th>
													<th scope="col">Título</th>
													<th scope="col">Descrição</th>
													<th scope="col">Origem</th>
													<th scope="col">Registo</th>
													<th scope="col">'.$tableCheck.'</th>
												');

												$table       .= '</tr>';
												$table       .= '</thead>';
												$table       .= '<tbody>';
												$tableClose   = '<tbody></table></div>';
												echo $table;
												while($mostra = $result->FETCH(PDO::FETCH_OBJ)){
												?>
												<tr>
													<th scope="row"><?php echo $mostra->id; ?></th>
													<td id="podcast-titulo-<?php echo $mostra->id; ?>">
														<?php echo $mostra->titulo;  ?>
													</td>
													<td><?php echo $mostra->descricao;  ?></td>
													<td><?php echo strtoupper($mostra->origem);  ?></td>
													<td><?php echo $mostra->registo;  ?></td>
													<th>
														<div class="d-flex w-100 align-items-center">
															<div class="custom-control custom-checkbox d-inline-block">
																<input 
																type="checkbox" 
																class="custom-control-input"
																podcast-select="<?php echo $mostra->id; ?>"
																id="podcast-<?php echo $mostra->id; ?>">
																
																<label 
																class="custom-control-label" 
																for="podcast-<?php echo $mostra->id; ?>"></label>
															</div>
															
															<a href="?podcast&edit=<?php echo $mostra->id; ?>">
																<i class="las la-edit" data-id="<?php echo $mostra->id; ?>"></i>
															</a>	                                        	

															<?php
																if(trim($mostra->audio) != ''){
																	?>
																	<i class="las la-play reproduzir-audio"
																	data-origem="<?php eco($mostra->origem); ?>"
																	data-audio="<?php eco(base64_encode($mostra->audio)); ?>"
																	data-id="<?php echo $mostra->id; ?>"></i>
																	<?php
																}
																if(is_file('../publico/img/podcast/'.$mostra->imagem)):
																	?>
																	<i class="las la-image visualizar-imagem"
																	data-imagem="<?php eco('../publico/img/podcast/'.$mostra->imagem); ?>"
																	data-id="<?php echo $mostra->id; ?>"></i>
																	<?php
																endif;
															?>

														</div>
													</th>
												</tr>
												<?php
												}
												
												echo $tableClose;

												//Paginação
												$paginacao = new Paginacao();
												$paginacao->queryString = 'podcast';
												$paginacao->select      = $select;
												$paginacao->quantidade  = $quantidade;
												$paginacao->pg          = $pg;
												$paginacao->getPaginacao();

											}else{
												if (isset($pg) && $pg > 1):
													echo '<script type="text/javascript">location.href = "./?podcast";</script>';
													exit(); 
												endif;
												echo '<p class="text-center lead">Nenhum resultado encontrado!</p>';
											}

											}catch(Exception $error){
												echo '<p class="text-center lead">'.$error.'!</p>';
											}
											?>
										</div>
									</div>
								</div>
								<?php

							endif;
						?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Visualizar imagem -->
<div class="modal fade" id="image-test" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="las la-image"></i></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

      	<img id="view-image" src="" class="w-100" style="height: auto;">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

<!-- Reproduzir audio -->
<div class="modal fade" id="audio-test" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="las la-play"></i></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

      	<div id="view-audio"></div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

<!-- Eliminar podcast -->
<div class="modal fade" id="modal-remove-item" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
	  <div class="modal-content">
		<div class="modal-header">
		  <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
			<span aria-hidden="true">&times;</span>
		  </button>
		</div>
  
		<div class="modal-body text-center lead" id="remove-content"></div>
  
		<div class="modal-footer">
		  <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
		  <button type="button" class="btn btn-danger" id="remove-item">Remover</button>
		</div>
	  </div>
	</div>
</div>

<script src="assets/js/transmicao/podcast.js"></script>
<div class="content-wrapper-before" style="height: 120px!important;"></div>
<div class="content-header row">
    <div class="content-header-left col-md-4 col-12 mb-2">
        <h3 class="content-header-title mt-2 mb-0">Compras pendentes</h3>
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
                        	<li><a id="modal-delete" class="mr-2"><i class="ft-trash"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                        </ul>
                    </div>
                	</div>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">

                        <div class="container">
                            <div class="row m-0 justify-content-center">
								<div class="col-12 col-md-5">
									<form method="GET">
										<div class="input-group mb-2 card-body-search">
											<input type="hidden" name="pendentes">
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
										$select = "SELECT * from fatura WHERE id LIKE '%$busca%' AND estado = 0";
										$select.= " OR id_factura LIKE '%$busca%' AND estado = 0 ORDER BY id DESC";
									else:
										$select = 'SELECT * from fatura WHERE estado = 0 ORDER BY id DESC';
									endif;

                                    $result = DB\Mysql::select(
                                        $select.$limit,
                                        [
                                            'inicio'     => $inicio,
                                            'quantidade' => $quantidade
                                        ]
                                    );

									if(is_array($result)){
										$table        = '<div class="table-responsive">';
										$table       .= '<table class="table table-striped table-hover">';
										$table       .= '<thead>';
										$table       .= '<tr>';
										
										$tableCheck   = trim(
										'<div class="custom-control custom-checkbox">
											<input type="checkbox" class="custom-control-input" id="pendente-id">
											<label class="custom-control-label" for="pendente-id"></label>
										</div>'
										);

										$table.= trim('
										    <th scope="col">#</th>
                                            <th scope="col">Ref</th>
                                            <th scope="col">Cliente</th>
										    <th scope="col">Registo</th>
										    <th scope="col">'.$tableCheck.'</th>
										');

										$table       .= '</tr>';
										$table       .= '</thead>';
										$table       .= '<tbody>';
										$tableClose   = '<tbody></table></div>';
										echo $table;
										foreach($result as $key => $value){
										?>
										<tr>
											<th scope="row"><?php echo $value['id']; ?></th>
                                            <td><?php echo $value['id_factura'];  ?></td>
                                            <td><?php echo $value['nome_do_cliente'];  ?></td>
											<td><?php echo $value['registo'];  ?></td>
											<th>
												<div class="d-flex w-100 align-items-center">
													<div class="custom-control custom-checkbox d-inline-block">
														<input 
														type="checkbox" 
														class="custom-control-input"
														pendente-select="<?php echo $value['id']; ?>"
														id="pendente-<?php echo $value['id']; ?>">
														
														<label 
														class="custom-control-label" 
														for="pendente-<?php echo $value['id']; ?>"></label>
													</div>
													
													<i class="las la-check-circle"
													id="modal-confirm"
													data-ref="<?= $value['id_factura']; ?>" 
                                                    data-id="<?php echo $value['id']; ?>"></i>

                                                    <i class="las la-info-circle"
                                                    data-toggle="modal" data-target="#visualizar-fatura<?php echo $value['id']; ?>"
                                                    data-id="<?php echo $value['id']; ?>"></i>
                                                    
												</div>
											</th>
                                        </tr>       

										<?php
										}
										
										echo $tableClose;

										//Paginação
										$paginacao = new Paginacao();
										$paginacao->queryString = 'pendentes';
										$paginacao->select      = $select;
										$paginacao->quantidade  = $quantidade;
										$paginacao->pg          = $pg;
										$paginacao->getPaginacao();

									}else{
										if (isset($pg) && $pg > 1):
											echo '<script type="text/javascript">location.href = "./?pendentes";</script>';
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

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    if(is_array($result)){
        foreach ($result as $key => $value) {
            ?>
            <!-- Visualizar factura -->
            <div class="modal fade" id="visualizar-fatura<?php echo $value['id']; ?>" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Fatura nº <?php echo $value['id']; ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <p style="font-weight: 500;">Dados de entrega</p>
                            <div style="border: #ebe9ee 1px solid; border-radius: 5px;" class="mb-1 p-1">
                                <p>
                                    <span style="font-weight: 500;">Morada:</span> <?php echo $value['morada'];  ?>
                                </p>
                                <p class="m-0">
                                    <span style="font-weight: 500;">Contacto:</span> 
                                    <?= json_decode(getCliente($value['id_cliente'], 'telemovel'), true);  ?>
                                </p>
                            </div>

                            <p style="font-weight: 500;">Artigo(s)</p>
                            <?php
                                $selectVendas = "SELECT * FROM vendas WHERE id_de_compra = :id ORDER BY id DESC";
                                $resultVendas = $conexao->getCon(1)->prepare($selectVendas);
                                $resultVendas->bindParam(':id', $value['id_factura'], PDO::PARAM_INT);
                                $resultVendas->execute();
                                $contarVendas = $resultVendas->rowCount();
                                if ($contarVendas > 0) {
                                    $totalDeVendas = 0;
                                    $inforesult    = $resultVendas->fetchAll();
                                    foreach ($inforesult as $key => $artigoData) {

                                        $get_name = "SELECT nome from artigos WHERE id = :id_objecto LIMIT 1";
                                        $get      = $conexao->getCon(1)->prepare($get_name);
                                        $get->bindParam(':id_objecto', $artigoData['id_objecto'], PDO::PARAM_INT);
                                        $get->execute();
                                        if(($get->rowCount()) > 0):
                                            while ($GET = $get->FETCH(PDO::FETCH_OBJ)) {
                                                $inforesult[$key]['name'] = isset($GET->nome) ? $GET->nome : 'n/a';
                                            }
                                        else:
                                            $inforesult[$key]['name'] = 'n/a';
                                        endif;
                                        ?>
                                        <div style="border: #ebe9ee 1px solid; border-radius: 5px;" class="mb-1 p-1">
                                            <p><?php echo "<span style='font-weight: 500;'>Descrição:</span> ".$inforesult[$key]['name']; ?></p>
                                            <p><?php echo "<span style='font-weight: 500;'>Cor:</span> ".($inforesult[$key]['cor'] != '' ? $inforesult[$key]['cor']  : 'n/a'); ?></p>
                                            <p><?php echo "<span style='font-weight: 500;'>Tamanho:</span> ".($inforesult[$key]['tamanho'] != '' ? $inforesult[$key]['tamanho']  : 'n/a'); ?></p>
                                            <p><?php echo "<span style='font-weight: 500;'>Preço:</span> ".get_moeda($artigoData['preco']); ?></p>
                                            <p><?php echo "<span style='font-weight: 500;'>Quantidade:</span> ".$artigoData['quantidade']; ?></p>
                                            <p style="font-weight: 500;" class="text-right">
                                                <?php 
                                                    echo "Total: ".get_moeda($artigoData['preco'] * $artigoData['quantidade']);
                                                    $totalDeVendas += $artigoData['preco'] * $artigoData['quantidade'];
                                                ?>
                                            </p>
                                        </div>
                                        <?php
                                    }
                                    ?>

                                    <p style="font-weight: 500;" class="text-right">
                                        Total: <?= get_moeda($totalDeVendas); ?>
                                    </p>

                                    <p style="font-weight: 500;" class="text-right">
                                        Taxa de entrega: <?= get_moeda($value['taxa_deliver']); ?>
                                    </p>
                                    <?php
                                        if($value['desgnacao_deliver'] != ''){
                                        ?>
                                            <p style="font-weight: 500;" class="text-right">
                                                Designação: <?= $value['desgnacao_deliver']; ?>
                                            </p>
                                        <?php
                                        }
                                    ?>
                                    <p class="mb-3 text-right red-text">
                                        <b>
                                            <?php
                                            if($value['estado'] == 1){
                                                ?>
                                                <b>Total pago: <?= get_moeda($totalDeVendas + $value['taxa_deliver']); ?></b>  
                                                <?php
                                            }else{
                                                ?>
                                                <b>Total a pagar: <?= get_moeda($totalDeVendas + $value['taxa_deliver']); ?></b>  
                                                <?php
                                            }
                                            ?>
                                        </b>
                                    </p>

                                    <?php
                                }else{
                                    echo '<p class="text-center lead">Nenhum artigo adicionado!</p>';
                                }
                            ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Visualizar factura -->
            <?php
        }
    }
?>

<!-- Eliminar compra pendente -->
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

<!-- Confirmar compra pendente -->
<div class="modal fade" id="modal-confirm-item" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
      	<h5>Confirmar compra</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="modal-body">
            <input type="hidden" id="confirm-pendente-id">
            <p class="lead">Estas prestes a confirmar esta compra!</p>
            <ul class="list-group">
                <li class="list-group-item p-1">Fatura nº <span id="confirm-fatura"></span></li>
                <li class="list-group-item p-1">Ref: <span id="confirm-ref"></span></li>
            </ul>
            <p style="font-size: 13.0px;" class="mt-1">
                Ao confirmar garante que esta factura ja foi paga, e sera transferida para o histórico de vendas!
            </p>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            <button type="button" class="btn btn-primary" id="confirm-pendente">Confirmar</button>
        </div>
    </div>
  </div>
</div>

<script src="assets/js/vendas/pendente.js"></script>
<footer>
	<div class="container">
		<div class="row mt-2 mb-1">
			<div class="col-12 col-sm-6 col-md-3">
				<br>
				<center>
					<img width="200px" class="img-fluid" src="publico/img/estnconsultoria.png" alt="Logo">
				</center>
			</div>
			<div class="col-12 col-sm-6 col-md-3">
				<br>
				<h5 class="mb-2">CONTACTO</h5>
				<p class="mb-1">Luanda, <br> Avenida Talatona, Condomíno Belas Business Park V Torre Cuanza Sul, 6° andar<p>
				<!--<p>Segunda - Sexta (8H / 16H)</p>
				<p>Sábado (8H / 12H)</p>-->
				<p class="mb-1"><a href="mailto:geral@estn-consulting.ao" style="color: #6d8be3!important;"><i style="font-size: 1.3em;" class="las la-envelope"></i> geral@estn-consulting.ao</a></p>
				<p class="mb-1"><a href="tel:+244948792936" style="color: #6d8be3!important;"><i style="font-size: 1.3em; color: #6d8be3!important;" class="lab la-whatsapp"></i> (+244) 948 792 936</a></p>
            </div>
			<div class="col-12 col-sm-6 col-md-3">
				<br>
				<h5 class="mb-2">ORGANIZAÇÃO</h5>
				<p class="mb-1"><i style="font-size: 1.3em;" class="las la-check-circle"></i> 
					<a href="<?= trim(current($get_current_page)) == '' ? '#sobre' : './#sobre'; ?>">Sobre Nós</a>
				</p>
				<p class="mb-1"><i style="font-size: 1.3em;" class="las la-bullseye"></i> 
					<a href="<?= trim(current($get_current_page)) == '' ? '#missao' : './#missao'; ?>">Missão</a>
				</p>
				<p class="mb-1"><i style="font-size: 1.3em;" class="las la-eye"></i> 
					<a href="<?= trim(current($get_current_page)) == '' ? '#visao' : './#visao'; ?>">Visão</a>
				</p>
				<p class="mb-1"><i style="font-size: 1.3em;" class="las la-user-graduate"></i> 
					<a href="<?= trim(current($get_current_page)) == '' ? '#valores' : './#valores'; ?>">Valores</a>
				</p>
			</div>
			<div class="col-12 col-sm-6 col-md-3">
				<br>
				<h5 class="mb-2">SERVIÇOS</h5>
				<p class="mb-1"><a href="<?= trim(current($get_current_page)) == 'solucoes' ? '#consultoria'   : './solucoes#projectos-sociais'; ?>">Empreendedorismo social</a></p>
				<p class="mb-1"><a href="<?= trim(current($get_current_page)) == 'solucoes' ? '#consultoria-projectos' : './solucoes#consultoria-projectos'; ?>">Consultoria em Projetos</a></p>
				<p class="mb-1"><a href="<?= trim(current($get_current_page)) == 'solucoes' ? '#consultoria-projectos' : './solucoes#gestao-projetos'; ?>">Gestão de Projetos</a></p>
				<p class="mb-1"><a href="<?= trim(current($get_current_page)) == 'solucoes' ? '#consultoria-projectos' : './solucoes#consultoria-bpm'; ?>">Consultoria em Gestão de Processos (BPM)</a></p>
				<p class="mb-1"><a href="<?= trim(current($get_current_page)) == 'solucoes' ? '#consultoria-gestao-projetos'    : './solucoes#consultoria-estrategica'; ?>">Consultoria Estratégica</a></p>
				<p class="mb-1"><a href="<?= trim(current($get_current_page)) == 'solucoes' ? '#consultoria-gestao-projetos'    : './solucoes#consultoria-ambiental'; ?>">Consultoria Ambiental</a></p>
                <br>
                <!--<h5>REDES SOCIAIS</h5>
                <div>
				    <a href="" target="_blank"><i style="font-size: 3em; color: #2697fb;" class="lab la-facebook"></i></a>
					<a href="" target="_blank"><i style="font-size: 3em; color: #ad61f3;" class="lab la-instagram"></i></a>
					<a href="https://wa.me/244940583115?text=Grupo%20Construvision%20Lda" style="color: #6d8be3!important;" target="_blank"><i style="font-size: 3em;" class="lab la-whatsapp"></i></a>
			    </div>-->
			</div>
		</div>
		<br>
		<hr class="m-1">
		<div class="footer">
            <div class="row m-0">
                <div class="col-12 col-md-6 pl-0 p-0">
                    <p>&copy;<?= date('Y'); ?> ESTN Consulting Ltd. Todos os direitos reservados 
                        · <a href="./privacidade">Privacidade</a> 
                        · <a href="./termos">Termos</a>
                    </p>
                </div>
            </div>
		</div>
	</div>
</footer>
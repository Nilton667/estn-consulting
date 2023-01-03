<?php
#Tipo de documento a gera Xml
header("Content-Type: application/xml; charset=ISO-8859-1");

#Declaramos a data e hora de expiração deste documento (esta como sendo 26/07/1997 para forçar a leitura deste PHP sem que ele esteja em cache)
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

#Declaramos a data e hora da última modificação deste documento (sempre sendo a data e hora que ele estiver sendo acessado)
header("Last-Modified: ". gmdate("D, d M Y H:i:s") ." GMT");

#Declaramos os controles de cache para não permitir nenhum tipo de cache e para forçar a leitura deste PHP sem que ele esteja em cache
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
#encodings/charset="ISO-8859-1"
#Declaramos a versão do XML utilizada e seu encoding
echo '<' . '?xml version="1.0" encoding="ISO-8859-1" ?' . '>';
?>
<rss version="2.0">
	<channel>
		<title>Grupo Construvision - Obras Públicas e Construção Civil, Lda</title>
		<link>https://stn-consulting.ao/</link>
		<description>Grupo Construvision, é uma empresa de direito angolano, N.I.F. 5417027960, com sede em Luanda no Município de Belas, Bairro Benfica, Via Expressa, Shopping Benfica 2o Piso-Sala 9, matriculada na conservatória de Registo Comercial de Luanda, 2a Secção, Guiché Único, sob o número 2098-11/110913.</description>
		<language>pt-pt</language>
		<webMaster>nilton667@gmail.com</webMaster>
		<item>
			<title>Grupo Construvision - Angola</title>
			<link>https://stn-consulting.ao/home</link>
			<description>Munida dos mais recentes equipamentos, com uma equipa técnica multidisciplinar, dinâmica, e comungando da experiência acumulada que formam o Grupo Construvision – Obras Públicas e Construção Civil, Lda.</description>
		</item>
		<item>
			<title>Grupo Construvision - Sobre</title>
			<link>https://stn-consulting.ao/sobre</link>
			<description>Munida dos mais recentes equipamentos, com uma equipa técnica multidisciplinar, dinâmica, e comungando da experiência acumulada que formam o Grupo Construvision – Obras Públicas e Construção Civil, Lda.</description>
		</item>
		<item>
			<title>Grupo Construvision - Serviços</title>
			<link>https://stn-consulting.ao/servicos</link>
			<description>Um edifício de média ou grande envergadura tem, hoje em dia, características, complexidade e exigências operacionais que apelam a uma gestão técnica rigorosa, nomeadamente, da sua manutenção.</description>
		</item>
		<item>
			<title>Grupo Construvision - Portfólio</title>
			<link>https://stn-consulting.ao/galeria</link>
			<description>Explore o nosso banco de imagens.</description>
		</item>
		<item>
			<title>Grupo Construvision - Contacto</title>
			<link>https://stn-consulting.ao/contacto</link>
			<description>Email: geral@stn-consulting.ao, Tel: (+244) 940 583 115.</description>
		</item>
	</channel>
</rss>
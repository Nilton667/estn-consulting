<?php

  //Informações de cabeçario

  $header_title       = '';
  $header_description = '';

  $get_current_page = explode('/', get('url', false) ? get('url') : '');

  $is_home            = false; 

  switch (trim(current($get_current_page))):

    case 'contacto':
      $header_title       = 'ESTN Consulting - Contacto';
      $header_description = 'Email: geral@estn-consulting.ao, Tel: (+244) 948 792 936.';
      break;

    case 'sobre':
      $header_title       = 'ESTN Consulting - Sobre';
      $header_description = 'A ESTN CONSULTING, é uma empresa de direito Angolano, registada na Conservatória do Registo Comercial de Luanda, com o nif: 500792195, liderada pela Dra. Etelvina Tomás, com uma equipa qualificada que procura ajudar Angola, no sentido de prestar serviços de qualidade, para o mercado Angolano.';
      break;

    case 'privacidade':
      $header_title       = 'Termos de serviços & privacidade';
      $header_description = 'A sua privacidade é importante para nós. É política da ESTN Consulting, respeitar a sua privacidade em relação a qualquer informação sua que possamos coletar no site.';
      break;

      case 'termos':
        $header_title       = 'Termos de serviços & privacidade';
        $header_description = 'A sua privacidade é importante para nós. É política da ESTN Consulting, respeitar a sua privacidade em relação a qualquer informação sua que possamos coletar no site.';
        break;

    case 'solucoes':
      $header_title       = 'ESTN Consulting - Soluções';
      $header_description = 'Um edifício de média ou grande envergadura tem, hoje em dia, características, complexidade e exigências operacionais que apelam a uma gestão técnica rigorosa, nomeadamente, da sua manutenção.';
    break;

    case 'portfolio':
      $header_title       = 'ESTN Consulting - Portfólio';
      $header_description = 'Explore o nosso banco de imagens.';
      break;

    case 'certificacoes':
      $header_title       = 'ESTN Consulting - Certificações';
      $header_description = '';
      break;

    case 'parceiros':
      $header_title       = 'ESTN Consulting - parceiros';
      $header_description = '';
      break;

    case '':
      $header_title       = 'ESTN Consulting';
      $header_description = 'A ESTN CONSULTING, é uma empresa de direito Angolano, registada na Conservatória do Registo Comercial de Luanda, com o nif: 500792195, liderada pela Dra. Etelvina Tomás, com uma equipa qualificada que procura ajudar Angola, no sentido de prestar serviços de qualidade, para o mercado Angolano.';
      $is_home = true;
      break;

    default:
      $header_title       = '404';
      $header_description = 'Document error 404';
      break;

  endswitch;

?>
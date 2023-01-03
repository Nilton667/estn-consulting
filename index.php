<?php
  include_once 'app/control.php';
  $control     = new Control('index');

  //Controller
  include_once 'app/controller.php';
  //Contador de visitas
  ( new Visitas() )->checkUser(true, false);
  
?>
<!DOCTYPE html>
<html>
    <head>
        <base href="<?= $_SERVER["REQUEST_SCHEME"]; ?>://<?= $_SERVER['SERVER_NAME']; ?>/estnconsulting/">
        <meta charset="utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <link rel="shortcut icon" href="favicon.png"/>

        <title><?= $header_title; ?></title>
        <meta name="title" content="<?= $header_title; ?>">
        <meta name="description" content="<?= $header_description; ?>">
        <meta name="author" content="Rubro, Ldt"/>

        <link rel="stylesheet" type="text/css" href="publico/css/owl.carousel.min.css"/>
        <link rel="stylesheet" href="publico/css/aos.css">
        <link rel="stylesheet" type="text/css" href="publico/css/owl.theme.default.min.css"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <link rel="stylesheet" href="publico/css/bootstrap.min.css"/>
        <link rel="stylesheet" href="publico/css/maestro.css"/>
        <link rel="stylesheet" href="publico/css/magnific-popup.css">
        <link rel="stylesheet" href="publico/css/jquery.toast.min.css"/>
        <link rel="stylesheet" type="text/css" href="publico/icon/css/line-awesome.min.css">
        <script src="publico/js/jquery.min.js"></script>
        <script src="publico/js/jquery.toast.min.js"></script>
        <script src="publico/js/jquery.magnific-popup.min.js"></script>
    </head>
    <body>
        
        <div class="preload">
            <div>
                <center>
                    <img src="favicon.png">
                </center>			
            </div>
        </div>

        <div id="inicio" class="onload">

            <?php
                include_once 'models/header.php';

                switch (trim(current($get_current_page))):

                    case 'contacto':
                        include_once 'pg/contacto.php';
                      break;
                
                    case 'termos':
                        include_once 'pg/termos.php';
                      break;
        
                    case 'privacidade':
                      include_once 'pg/termos.php';
                    break;
                
                    case 'solucoes':
                        include_once 'pg/solucoes.php';
                      break;
    
                    case 'portfolio':
                      include_once 'pg/galeria.php';
                      break;

                    case 'certificacoes':
                      include_once 'pg/certificacoes.php';
                      break;

                    case 'parceiros':
                      include_once 'pg/parceiros.php';
                      break;

                    case 'sobre':
                      include_once 'pg/sobre.php';
                    break;

                    case 'home':
                      include_once 'pg/home.php';
                    break;

                    case '':
                        include_once 'pg/home.php';
                      break;
    
                    default:
                        include_once 'pg/404.php';
                      break;
                
                  endswitch;

                include_once 'models/footer.php';
            ?>

            <div class="document-whatsapp pointer" onclick="window.open('https://api.whatsapp.com/send?1=pt_PT&amp;phone=244948792936', '_blank')">
              <i class="lab la-whatsapp"></i>
            </div>

            <!-- Scroll top -->
            <div class="document-top">
                <i class="las la-angle-up"></i>
            </div>

        </div>

        <script src="publico/js/bootstrap.bundle.min.js"></script>
        <script src="publico/js/maestro.js"></script>
        <script src="publico/js/loadMore.js"></script>
        <script src="publico/js/newsletter.js"></script>
        <script src="publico/js/aos.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                AOS.init();
            });
        </script>
    </body>
</html>
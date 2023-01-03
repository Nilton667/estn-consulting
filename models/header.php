<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm p-0 fixed-top">
  <div class="container-fluid">
    <button class="navbar-toggler rigth" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="./">
        <img style="height: 50px;" class="p-1" src="publico/img/estnconsultoria_right.png">
    </a>
    <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item p-2 <?= trim(current($get_current_page)) != '' ? '' : 'active'; ?>">
                <a class="nav-link" href="./">HOME</a>
            </li>
            <li class="nav-item p-2 <?= trim(current($get_current_page)) == 'sobre' ? 'active' : ''; ?>">
                <a class="nav-link" href="./sobre">ESTN CONSULTING</a>
            </li>
            <li class="nav-item p-2 <?= trim(current($get_current_page)) == 'solucoes' ? 'active' : ''; ?>">
                <a class="nav-link" href="./solucoes">SOLUÇÕES</a>
            </li>
            <li class="nav-item p-2 <?= trim(current($get_current_page)) == 'portfolio' ? 'active' : ''; ?>">
                <a class="nav-link" href="./portfolio">PORTFÓLIO</a>
            </li>
            <li class="nav-item p-2 <?= trim(current($get_current_page)) == 'certificacoes' ? 'active' : ''; ?>">
                <a class="nav-link" href="./certificacoes">CERTIFICAÇÕES</a>
            </li>
            <li class="nav-item p-2 <?= trim(current($get_current_page)) == 'parceiros' ? 'active' : ''; ?>">
                <a class="nav-link" href="./parceiros">PARCEIROS</a>
            </li>
            <li class="nav-item p-2 <?= trim(current($get_current_page)) == 'contacto' ? 'active' : ''; ?>">
                <a class="nav-link" href="./contacto">CONTACTOS</a>
            </li>
        </ul>
    </div>
  </div>
</nav>
<div style="height: 60px;"></div>
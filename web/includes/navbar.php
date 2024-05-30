<nav class="navbar navbar-expand-lg bg-body-tertiary">

  <div class="container">

    <a class="navbar-brand" href="index.php"> APM Terminals </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

        <li class="nav-item">
          <?php 
          
          if (isset($_SESSION['usuario'])){

            echo '<a class="nav-link" href="web/login.php"> Cerrar Sesión </a>';
          }
          else {
            echo '<a class="nav-link" href="web/login.php"> Login </a>';
          }
          
          ?>
        </li>
        
        
      </ul>
    </div>
  </div>
</nav>
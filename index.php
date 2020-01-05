<?php
  session_start(); 	//A sessão deve ser iniciada em todas as páginas
  if (isset($_SESSION['email'])) {		//Verifica se há seções
    header("Location: menu.php"); 
    exit;	//Redireciona o visitante para o menu
  }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Entrar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="./css/bootstrap.css" />

    <style>
      html,
      body {
        height: 100%;
      }

      body {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-align: center;
        align-items: center;
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
      }
      .form-signin {
    width: 100%;
    max-width: 330px;
    padding: 15px;
    margin: auto;
  }
  .form-signin .form-control {
    position: relative;
    box-sizing: border-box;
    height: auto;
    padding: 10px;
    font-size: 16px;
  }
  .form-signin .form-control:focus {
    z-index: 2;
  }
  .form-signin input[type="email"] {
    margin-bottom: -1px;
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 0;
  }
  .form-signin input[type="password"] {
    margin-bottom: 10px;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
  }

  .form-cadastro {
    width: 100%;
    max-width: 550px;
    padding: 15px;
    margin: auto;
  }
    </style>

  </head>
<body class="">
      <form class="form-signin text-center" method="POST" id="login">

      <div class="alert alert-dismissible fade show" role="alert" id="alert" style="display: none;">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <h1 class="h3 mb-3 font-weight-normal">Entrar</h1>
      <label for="inputEmail" class="sr-only">Usuário</label>
      <input type="text" id="email" name="email" class="form-control" placeholder="Email" required autofocus>
      <label for="inputPassword" class="sr-only">Senha</label>
      <input type="password" id="senha" name='senha'class="form-control" placeholder="Senha" required>
      <button class="btn btn-lg btn-success btn-block" type="submit">Entrar</button>
      <hr class="my-3">
      <a class="btn btn-outline-success" href="cadastro.php">Cadastrar</a>
  
    </form>
    <script type="text/javascript" src="./js/jquery.js"></script>
    <script type="text/javascript" src="./js/bootstrap.js"></script>
    <script type="text/javascript" src="./js/crud.js"></script>
  </body>
</html>
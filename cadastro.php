<?php
  session_start(); 	//A seção deve ser iniciada em todas as páginas
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
    <title>Cadastro</title>
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
      .form-cadastro {
        width: 100%;
        max-width: 550px;
        padding: 15px;
        margin: auto;
  }
    </style>

  </head>
<body class="">
      <form class="form-cadastro text-center" method="POST" id="cadastro_usuario">

      <div class="alert alert-dismissible fade show" role="alert" id="alert" style="display: none;">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <h1 class="h3 mb-3 font-weight-normal">Cadastre-se</h1>
   
      <div class="form-group">
        <label>Nome</label>
        <input type="text" id="nome" name="nome" class="form-control" placeholder="Nome" required autofocus> 
      </div>
      <div class="form-group">
        <label>Email</label>
        <input type="email" id="email" name="email" class="form-control" placeholder="Email" required>
      </div>
      <div class="form-group">
        <label>Senha</label>
        <input type="password" class="form-control" id="senha" name="senha" placeholder="Senha" required>

    </div>
    <br>
      <button class="btn btn-lg btn-success btn-block" type="submit">Cadastrar</button>
      <hr class="my-3">
      <a class="btn btn-outline-success" href="index.php">Voltar</a>
    </form>
    <script type="text/javascript" src="./js/jquery.js"></script>
    <script type="text/javascript" src="./js/bootstrap.js"></script>
    <script type="text/javascript" src="./js/crud.js"></script>
  </body>
</html>
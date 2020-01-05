<?php
  session_start(); 	//A sessão deve ser iniciada em todas as páginas

  if (!isset($_SESSION['email'])) {		//Verifica se há seções
    header("Location: index.php"); 
    exit;	//Redireciona o visitante para o menu
  }

  if (!isset ($_SESSION['vagas'])){
    $vagas = 100;
    $_SESSION['vagas'] = $vagas;
  }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Estacionamento</title>
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
      .conteudo {
        width: 100%;
        max-width: 500px;
        padding: 15px;
        margin: auto;
        text: center;
      }

      .logout-btn {
          position: absolute;
          top: 10px;
          right: 10px;
          border: none;
      }
    </style>
</head>
<body>
    <button class="btn btn-outline-dark logout-btn" id="sair">Sair</button>
    <div class="conteudo text-center" id="conteudo">
  
    
    <h1 class="display-4">Estacionamento</h1>
        <p class="font-weight-light text-muted"><?php echo $_SESSION['vagas'] ?> vagas livres</p>
        <hr class="my4">
        <button class="btn btn-block btn-success" id="entrada-carro">Entrada</button>
        <button class="btn btn-block btn-info" id="saida">Saída</button>
        <hr class="my4">
        <button class="btn btn-outline-secondary btn-sm"id="tickets">Ver tickets</button>
    </div>
    <script type="text/javascript" src="./js/jquery.js"></script>
    <script type="text/javascript" src="./js/bootstrap.js"></script>
    <script type="text/javascript" src="./js/crud.js"></script>
</body>
</html>
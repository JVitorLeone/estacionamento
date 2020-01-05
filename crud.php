<?php
const SERVIDOR = "localhost:3306";
const BANCO = "estacionamento";
const USUARIO = "root";
const SENHA = "";

session_start();

if (!isset($_POST['acao'])) {
    print json_encode(0);
    return;
}



switch ($_POST['acao']) {

    case 'login':

        $qUsuario = "SELECT * FROM usuario WHERE email=? AND senha=?";

        try {
            $conexao = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . BANCO, USUARIO, SENHA);
            $pre = $conexao->prepare($qUsuario);
            $pre->execute([
                $_POST['email'],
                md5($_POST['senha']),
            ]);
            $rUsuario = $pre->fetchAll(PDO::FETCH_OBJ);

            if (count($rUsuario) > 0) {
                $_SESSION['email'] = $_POST['email'];
                $_SESSION['senha'] = md5($_POST['senha']);
                $_SESSION['id_usuario'] = $rUsuario[0]->id;
                echo json_encode($rUsuario[0]);
            } else {
                $arr = array('erro' => "Usuário e/ou senha inválido(s)");
                echo json_encode($arr, JSON_UNESCAPED_UNICODE);
            }
        } catch (PDOException $e) {
            $arr = array('erro' => "Erro: " . $e->getMessage());
            echo json_encode($arr, JSON_UNESCAPED_UNICODE);
        } finally {
            $conexao = null;
        }

        break;

    case 'cadastro':
        $qUsuario = "SELECT * FROM usuario WHERE email = ?";
        try {
            $conexao = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . BANCO, USUARIO, SENHA);
            $pre = $conexao->prepare($qUsuario);
            $pre->execute([$_POST['email']]);
            $rUsuario = $pre->fetchAll(PDO::FETCH_OBJ);
            if (count($rUsuario) > 0) {
                $arr = array('erro' => "Email já cadastrado");
                echo json_encode($arr, JSON_UNESCAPED_UNICODE);
            } else {
                $qUsuario = "INSERT INTO usuario (nome, email, senha) VALUES (?,?,?)";

                $pre = $conexao->prepare($qUsuario);
                $pre->execute([
                    $_POST['nome'],
                    $_POST['email'],
                    md5($_POST['senha']),
                ]);
                echo json_encode(0);
            }
        } catch (PDOException $e) {
            $arr = array('erro' => "Erro: " . $e->getMessage());
            echo json_encode($arr, JSON_UNESCAPED_UNICODE);
        } finally {
            $conexao = null;
        }
        break;

    case 'logout':

        unset($_SESSION['email']);
        unset($_SESSION['senha']);
        unset($_SESSION['id_usuario']);
        echo json_encode(0);
        break;

    case 'get_usuario':
        $qUsuario = "SELECT * FROM usuario WHERE username = ?";
        try {
            $conexao = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . BANCO, USUARIO, SENHA);
            $pre = $conexao->prepare($qUsuario);
            $pre->execute([$_SESSION['username']]);
            $rUsuario = $pre->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($rUsuario[0]);
        } catch (PDOException $e) {
            $arr = array('erro' => "Erro: " . $e->getMessage());
            echo json_encode($arr, JSON_UNESCAPED_UNICODE);
            die();
        } finally {
            $conexao = null;
        }
        break;

    case 'update_usuario':
        $qUsuario = "UPDATE usuario SET nome=?, email=?, telefone=?, cpf=?, data_nascimento=? WHERE id = ?";
        try {
            $conexao = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . BANCO, USUARIO, SENHA);
            $pre = $conexao->prepare($qUsuario);
            $pre->execute([$_POST['nome'],
                $_POST['email'],
                $_POST['telefone'],
                $_POST['cpf'],
                $_POST['data_nasc'],
                $_SESSION['id_usuario']]);

            $qUsuario = "SELECT * FROM usuario WHERE id = ?";
            $pre = $conexao->prepare($qUsuario);
            $pre->execute([$_SESSION['id_usuario']]);
            $rUsuario = $pre->fetchAll(PDO::FETCH_OBJ);

            if (count($rUsuario) < 1) {
                $arr = array('erro' => "houve um erro");
                echo json_encode($arr, JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode($rUsuario[0]);
            }
            
        } catch (PDOException $e) {
            $arr = array('erro' => "Erro: " . $e->getMessage());
            echo json_encode($arr, JSON_UNESCAPED_UNICODE);
            die();
        } finally {
            $conexao = null;
        }
        break;

    case 'cria_ticket':
        $qTicket = "INSERT INTO tickets (data_entrada, placa, nome_prop, telefone_prop) VALUES (?,?,?,?)";

        try {
            $conexao = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . BANCO, USUARIO, SENHA);
            $pre = $conexao->prepare($qTicket);
            $pre->execute([
                date('Y-m-d H:i:s', strtotime('-4 hours')),
                $_POST['placa'],
                $_POST['nome'],
                $_POST['telefone']
            ]);
        
            $_SESSION['vagas'] = $_SESSION['vagas'] - 1;
            echo json_encode(0);

        } catch (PDOException $e) {
            $arr = array('erro' => "Erro: " . $e->getMessage());
            echo json_encode($arr, JSON_UNESCAPED_UNICODE);
        } finally {
            $conexao = null;
        }
        break;
    
    case 'lista_tickets':
        $qTicket = "SELECT * FROM tickets";

        try {
            $conexao = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . BANCO, USUARIO, SENHA);
            $pre = $conexao->prepare($qTicket);
            $pre->execute();
            $rTickets = $pre->fetchAll(PDO::FETCH_OBJ);

            echo json_encode($rTickets);

        } catch (PDOException $e) {
            $arr = array('erro' => "Erro: " . $e->getMessage());
            echo json_encode($arr, JSON_UNESCAPED_UNICODE);
        } finally {
            $conexao = null;
        }
        break;

    case 'procura_ticket':
        $qTicket = "SELECT * FROM tickets WHERE placa = ?";

        try {
            $conexao = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . BANCO, USUARIO, SENHA);
            $pre = $conexao->prepare($qTicket);
            $pre->execute([$_POST['placa']]);
            $rTicket = $pre->fetchAll(PDO::FETCH_OBJ);

            if (count($rTicket) < 1){
                $arr = array('erro' => "Placa não encontrada");
                echo json_encode($arr, JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode($rTicket[0]);
            }
            
        } catch (PDOException $e) {
            $arr = array('erro' => "Erro: " . $e->getMessage());
            echo json_encode($arr, JSON_UNESCAPED_UNICODE);
        } finally {
            $conexao = null;
        }
        break;
    
    case 'retira_ticket':
        $qTicket = "DELETE FROM tickets WHERE id = ?";

        try {
            $conexao = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . BANCO, USUARIO, SENHA);
            $pre = $conexao->prepare($qTicket);
            $pre->execute([$_POST['id']]);

            $_SESSION['vagas'] = $_SESSION['vagas'] + 1;

            echo json_encode(0);
            
        } catch (PDOException $e) {
            $arr = array('erro' => "Erro: " . $e->getMessage());
            echo json_encode($arr, JSON_UNESCAPED_UNICODE);
        } finally {
            $conexao = null;
        }
        break;
}

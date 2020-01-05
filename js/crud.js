$(function () {

    $(document).on('submit', 'form#login', function (e) {
        e.preventDefault();
        entrar();
    });

    $(document).on('submit', 'form#cadastro_usuario', function (e) {
        e.preventDefault();
        cadastrar();
    });

    $(document).on('click', 'button#sair', function () {
        sair();
    });

    $(document).on('click', 'button#entrada-carro', function () {
        showFormEntrada();
    });

    $(document).on('submit', 'form#cria-ticket', function (e) {
        e.preventDefault();
        form = $(this).serialize() + '&acao=cria_ticket';
        cria_ticket(form);
    });

    $(document).on('click', 'button#tickets', function (){
        lista_tickets();
    });

    $(document).on('click', "button#saida", function () {
        showFormSaida();
    });

    $(document).on('submit', "form#procura_ticket", function(e) {
        e.preventDefault();
        form = $(this).serialize() + '&acao=procura_ticket'
        console.log(form);
        procura_ticket(form);

    });

    $(document).on('click', 'button#retira', function () {
        placa = { 
            placa: $(this).attr('ticket_placa'),
            acao:  'procura_ticket'
        };
        procura_ticket(placa);
    });

    $(document).on('click', 'button#confirma_retirada', function () {
        dados = {
            id: $(this).attr('ticket_id'),
            acao: 'retira_ticket',
        };
        retira_ticket(dados)
    })

});

function entrar() {
    data = $("#login").serialize() + "&acao=login"
    $.post('crud.php', data,
        function (dados) {
        console.log(dados);
        dados = JSON.parse(dados);
        if (dados.hasOwnProperty('erro')){
            alert("Email ou senha incorretos!");
        } else {
            window.location.replace("menu.php");
        }
    });
}

function cadastrar() {
    data = $("#cadastro_usuario").serialize() + "&acao=cadastro"
    $.post('crud.php', data, 
        function (dados) {
        dados = JSON.parse(dados);
        if (dados.hasOwnProperty('erro')){
            alert("Email já cadastrado!");
        } else {
            alert("Usuário cadastrado com sucesso!");
            window.location.replace("index.php");
        }
    });
}

function sair() {
    $.post('crud.php', {
       acao: 'logout', 
    }, function (dados) {
        window.location.replace("index.php");
    });
}

function showFormEntrada() {
    var dt = new Date();
    var time = ("0" + dt.getHours()).slice(-2)   + ":" + ("0" + dt.getMinutes()).slice(-2);

    form = '<h2 class="">Entrada de carro</h2>';
    form += '<hr class="my4">';
    form += '<form id="cria-ticket">';
    form += '<div class="form-group">';
    form += '<div class="form-group">';
    form += '<label>Hora da entrada</label>';
    form += '<input type="time" class="form-control" value="' + time + '" readonly>';
    form += '</div>';
    form += '<label for="placa" >Placa do veículo</label>';
    form += '<input type="text" id="placa" name="placa" class="form-control" placeholder="Placa do veículo" required/>';
    form += '</div>';
    form += '<div class="form-group">';
    form += '<label>Nome do proprietário</label>';
    form += '<input class="form-control" type="text" id="nome" name="nome" placeholder="Nome" required>';
    form += '</div>';
    form += '<div class="form-group">';
    form += '<label>Telefone do proprietário</label>';
    form += '<input class="form-control" type="text" id="telefone" name="telefone" placeholder="Telefone" required>';
    form += '</div>';
    form += '<button type="submit" class="btn btn-block btn-success">Gerar ticket</button>';
    form += '</form>';
    form += '<hr class="my-3">';
    form += '<a class="btn btn-outline-success" href="menu.php">Voltar</a>';

    $("div#conteudo").html(form);
}

function cria_ticket(form) {
    console.log(form);
    $.post('crud.php', form, 
    function(dados) {
        console.log(dados);
        dados = JSON.parse(dados);
        lista_tickets();
    });
}
function lista_tickets(){
    $.post('crud.php', {
        acao : 'lista_tickets',
    }, function (dados) {
        console.log(dados);
        dados = JSON.parse(dados);
        mostra_lista(dados)
    })
}

function mostra_lista(tickets){
    table = '<h2 class="">Tickets</h2>';
    table += '<table class="table table-bordered">';

    table += '<thead>';
    table += '<tr>';
    table += '<th scope="col">N°</th>';
    table += '<th scope="col">Placa</th>';
    table += '<th scope="col">Hora Entrada</th>';
    table += '<th scope="col">';
    table += '</th>';
    table += '</tr>';
    table += '</thead>';
    table += '<tbody>';

    $.each( tickets, 
        function (i, ticket) {
            table += '<tr>';
            table += '<th scope="row">' + i + '</th>';
            table += '<td>' + ticket.placa + '</td>';
            table += '<td>' + ticket.data_entrada + '</td>';
            table += '<td><button class="btn btn-danger btn-sm" id="retira" ticket_placa="'+ ticket.placa + '">Retirar</button></td>';
            table += '</tr>';
        }
    );

    table+= '</tbody></table>'
    table += '<a class="btn btn-outline-secondary" href="menu.php">Voltar</a>';

    $("div#conteudo").html(table);
} 

function showFormSaida(){
    form = '<h2 class="">Saída de veículo</h2>';
    form += '<hr class="my4">';
    form += '<form id="procura_ticket">';
    form += '<div class="form-group">';
    form += '<label for="placa" >Placa do veículo</label>';
    form += '<input type="text" id="placa" name="placa" class="form-control" placeholder="Placa do veículo" required/>';
    form += '</div>';
    form += '<button type="submit" class="btn btn-block btn-info">Buscar ticket</button>';
    form += '</form>';
    form += '<hr class="my-3">';
    form += '<a class="btn btn-outline-info" href="menu.php">Voltar</a>';

    $("div#conteudo").html(form);
} 

function procura_ticket(form){
    $.post('crud.php', form,
        function (dados) {
            dados = JSON.parse(dados);
            if (dados.hasOwnProperty('erro')){
                alert("Placa não encontrada");
            } else {
                showTicket(dados);
            }
            
        }      
    )
}

function showTicket(ticket) {
    var horas = calcHoras(ticket.data_entrada);
    var valor;

    if (horas > 23) {
        var dias = Math.floor(horas / 24);
        var horas = horas % 24;
        valor = dias * 20 + horas * 2.50;
    } else {
        valor = horas * 2.50;
    }

    table = '<h2 class="">Ticket n° ' + ticket.id + '</h2>';
    table += '<table class="table table-bordered">';
    table += '<tbody>';
    table += '<tr>';
    table += '<th scope="row" class="">Placa</th>';
    table += '<td class="">' + ticket.placa + '</td>';
    table += '</tr>';
    table += '<tr>';
    table += '<th scope="row" class="">Proprietario</th>';
    table += '<td class="">' + ticket.nome_prop + '</td>';
    table += '</tr>';
    table += '<tr>';
    table += '<th scope="row" class="">Telefone</th>';
    table += '<td class="">' + ticket.telefone_prop + '</td>';
    table += '</tr>';
    table += '<tr>';
    table += '<th scope="row" class="">Data de entrada</th>';
    table += '<td class="">' + ticket.data_entrada + '</td>';
    table += '</tr>';
    table += '<tr>';
    table += '<th scope="row" class="">Tempo de estacionamento</th>';
    table += '<td class="">' + horas + 'h </td>';
    table += '</tr>';
    table += '<tr>';
    table += '<th scope="row" class="">Valor <small>(R$ 20/dia e R$ 2,50/h)</small></th>';
    table += '<td class="">R$ ' + valor + '</td>';
    table += '</tr>';
    table += '</tbody></table>';
    table += '<button class="btn btn-block btn-danger" id="confirma_retirada" ticket_id="' + ticket.id + '">Retirar ticket</button>';
    table += '<hr clas="my-3">';
    table += '<a class="btn btn-outline-danger" href="menu.php">Voltar</a>';

    $("div#conteudo").html(table);
}

function tira_ticket(ticket){
    $.post('crud.php', form,
        function (dados) {
            dados = JSON.parse(dados);
            showTicket(dados);
        }      
    )
} 

function calcHoras(date) {
    var now = new Date();
    date = new Date(date);

    var milisHora = 1000 * 60 * 60;
    var milisDiff = now.getTime() - date.getTime();
    var horas = milisDiff / milisHora;

    console.log(Math.floor(horas));
    return Math.floor(horas);
}

function retira_ticket(dados) {
    $.post('crud.php', dados, 
        function (dados) {
            dados = JSON.parse(dados);
            if (dados.hasOwnProperty('erro')){
                alert("Desculpe, houve um erro");
            } else {
                alert("Ticket retirado!");
                window.location.replace("menu.php");
            }
        });

}



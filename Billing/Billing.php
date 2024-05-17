<?php





?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOTEL RESERVA</title>
    <link rel="stylesheet" href="billing.css">
</head>

<body>
    <div class="reservation-form">
        <h2>Reserva de um Quarto</h2>
        <form action="Billing.php" method="post">
            <fieldset>
                <legend>Informação Pessoal</legend>
                <label for="name">Nome:</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="phone">Número de Telefone:</label>
                <input type="tel" id="phone" name="phone" required>
            </fieldset>

            <fieldset>
                <legend>Detalhes Da Reserva</legend>
                <label for="room-type">Tipo do Quarto:</label>
                <select id="room-type" name="room-type">
                    <option value="single">Single</option>
                    <option value="double">Double</option>
                    <option value="suite">Suite</option>
                    <option value="suite">Deluxe</option>
                </select>

                <label for="check-in">Check-in:</label>
                <input type="date" id="check-in" name="check-in" required>

                <label for="check-out">Check-out:</label>
                <input type="date" id="check-out" name="check-out" required>

                <label for="guests">Numero de Pessoas:</label>
                <input type="number" id="guests" name="guests" min="1" max="8" required>
            </fieldset>

            <fieldset>
                <legend>Faturação</legend>
                <label for="card-name">Nome do cartão:</label>
                <input type="text" id="card-name" name="card-name" required>

                <label for="card-number">Número do cartão:</label>
                <input type="text" id="card-number" name="card-number" required>

                <label for="card-expiry">Data de Expiração:</label>
                <input type="month" id="card-expiry" name="card-expiry" required>

                <label for="card-cvc">CVC:</label>
                <input type="text" id="card-cvc" name="card-cvc" required>
            </fieldset>
            <a href="../Quartos/Quartos.php" class="avoltar">Voltar</a>
            <button type="submit">Reserve Agora</button>
        </form>
    </div>
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link rel="stylesheet" href="payment.css">
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const reservationData = JSON.parse(sessionStorage.getItem('reservationData'));
            if (reservationData) {
                document.getElementById('package_name').textContent = reservationData.package_name
                document.getElementById('price_package').textContent = reservationData.price_package
                document.getElementById('room-name').textContent = reservationData.room_name;
                document.getElementById('room-price').textContent = reservationData.price + " EUR / Noite";

                const checkInDate = new Date(reservationData.check_in);
                const checkOutDate = new Date(reservationData.check_out);
                const timeDiff = Math.abs(checkOutDate.getTime() - checkInDate.getTime());
                const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));

                document.getElementById('check-in').textContent = reservationData.check_in;
                document.getElementById('check-out').textContent = reservationData.check_out;
                document.getElementById('num-nights').textContent = daysDiff;
                document.getElementById('num-guests').textContent = reservationData.num_guests;

                const totalPrice = daysDiff * parseFloat(reservationData.price);
                document.getElementById('total-price').textContent = totalPrice.toFixed(2) + " EUR";
            }
        });
    </script>
</head>

<body>
    <!-- partial:index.partial.html -->
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/bb515311f9.js" crossorigin="anonymous"></script>
    <title>Credit Card Checkout</title>

    <div class="centered-container">
        <div class="right-side">
            <div class="receipt">
                <h2 class="receipt-heading">Resumo da Fatura</h2>
                <div>
                    <table class="table">
                        <tr>
                            <td><strong>Quarto:</strong> <span id="room-name"></span>\-------/</td>
                            <td><strong>Preço por Noite:</strong> <span id="room-price"></span></td>
                        </tr>
                        <tr>
                            <td><strong>Nome Do Pacote:</strong> <span id="package_name"></span></td>
                        </tr>
                        <tr>
                            <td><strong>Preço Do Pacote:</strong> <span id="price_package"></span></td>
                        </tr>
                        <tr>
                            <td><strong>Check-in Data:</strong> <span id="check-in"></span></td>
                        </tr>
                        <tr>
                            <td><strong>Check-out Data:</strong> <span id="check-out"></span></td>
                        </tr>
                        <tr>
                            <td><strong>Número de Noites:</strong> <span id="num-nights"></span></td>
                        </tr>
                        <tr>
                            <td><strong>Número de Hóspedes:</strong> <span id="num-guests"></span></td>
                        </tr>
                        <tr>

                        </tr>
                        <tr class="total">
                            <td>Total</td>
                            <td class="price" id="room-total">
                            <td>Preço total: <span id="total-price"></span></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="payment-info">
                <h3 class="payment-heading">Informação do Pagamento</h3>
                <form class="form-box" enctype="text/plain" method="get" target="_blank">
                    <div>
                        <label for="full-name">Nome Completo</label>
                        <input id="full-name" name="full-name" placeholder="Seu Nome Completo" required type="text" />
                    </div>

                    <div>
                        <label for="credit-card-num">Número do Cartão
                            <span class="card-logos">
                                <i class="card-logo fa-brands fa-cc-visa"></i>
                                <i class="card-logo fa-brands fa-cc-amex"></i>
                                <i class="card-logo fa-brands fa-cc-mastercard"></i>
                                <i class="card-logo fa-brands fa-cc-discover"></i>
                            </span>
                        </label>
                        <input id="credit-card-num" name="credit-card-num" placeholder="1111-2222-3333-4444" required
                            type="text" />
                    </div>

                    <div>
                        <p class="expires">Expire em:</p>
                        <div class="card-expiration">
                            <label for="expiration-month">Mês</label>
                            <select id="expiration-month" name="expiration-month" required>
                                <option value="">Month:</option>
                                <option value="January">January</option>
                                <option value="February">February</option>
                                <option value="March">March</option>
                                <option value="April">April</option>
                                <option value="May">May</option>
                                <option value="June">June</option>
                                <option value="July">July</option>
                                <option value="August">August</option>
                                <option value="September">September</option>
                                <option value="October">October</option>
                                <option value="November">November</option>
                                <option value="December">December</option>
                            </select>

                            <label class="expiration-year">Ano</label>
                            <select id="expiration-year" name="expiration-year" required>
                                <option value="">Year:</option>
                                <option value="2024">2024</option>
                                <option value="2025">2025</option>
                                <option value="2026">2026</option>
                                <option value="2027">2027</option>
                                <option value="2028">2028</option>
                                <option value="2029">2029</option>
                                <option value="2030">2030</option>
                                <option value="2031">2031</option>
                                <option value="2032">2032</option>
                                <option value="2033">2033</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="security-code">CVC</label>
                        <input id="security-code" name="security-code" placeholder="Security Code" required
                            type="password" />
                    </div>
                    <button class="btn" type="submit">Check Out</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
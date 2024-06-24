<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>CodePen - Daily UI 002 - Checkout</title>
  <link rel="stylesheet" href="payment.css">

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const reservationData = JSON.parse(sessionStorage.getItem('reservationData'));
      if (reservationData) {
        document.getElementById('room-name').textContent = reservationData.type_of_room;
        document.getElementById('room-price').textContent = reservationData.room_price + " USD / 1 night";

        const checkInDate = new Date(reservationData.check_in);
        const checkOutDate = new Date(reservationData.check_out);
        const timeDiff = Math.abs(checkOutDate.getTime() - checkInDate.getTime());
        const numNights = Math.ceil(timeDiff / (1000 * 3600 * 24));

        document.getElementById('num-nights').textContent = numNights + " nights";
        document.getElementById('check-in-date').textContent = reservationData.check_in;
        document.getElementById('check-out-date').textContent = reservationData.check_out;

        // Calculate and display detailed price breakdown
        const subtotal = reservationData.room_price * numNights;
        document.getElementById('room-total').textContent = subtotal.toFixed(2) + " USD";
      }
    });
  </script>
</head>

<body>
  <!-- partial:index.partial.html -->
  <!-- Font Awesome -->
  <script src="https://kit.fontawesome.com/bb515311f9.js" crossorigin="anonymous"></script>
  <title>Day 002 - Credit Card Checkout</title>

  <body>
    <div class="checkout-container">
      <div class="left-side">
        <div class="text-box">
          <h1 class="home-heading" id="room-name">Modern Home</h1>
          <p class="home-price"><em id="room-price"> USD </em>/ 1 night</p>
          <hr class="left-hr" />
          <p class="home-desc"><em>Entire home </em>for <em id="room-capacity">2 guest</em></p>
          <p class="home-desc" id="stay-dates"></p>
        </div>
      </div>

      <div class="right-side">
        <div class="receipt">
          <h2 class="receipt-heading">Receipt Summary</h2>
          <div>
            <table class="table">
              <tr class="total">
                <td>Total</td>
                <td class="price" id="room-total">546.41 USD</td>
              </tr>
            </table>
          </div>
        </div>

        <div class="payment-info">
          <h3 class="payment-heading">Payment Information</h3>
          <form class="form-box" enctype="text/plain" method="get" target="_blank">
            <div>
              <label for="full-name">Full Name</label>
              <input id="full-name" name="full-name" placeholder="Satoshi Nakamoto" required type="text" />
            </div>

            <div>
              <label for="credit-card-num">Card Number
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
              <p class="expires">Expires on:</p>
              <div class="card-experation">
                <label for="expiration-month">Month</label>
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

                <label class="expiration-year">Year</label>
                <select id="experation-year" name="experation-year" required>
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
              <label for="security-code">Security Code</label>
              <input id="security-code" name="security-code" placeholder="Security Code" required type="password" />
            </div>
            <button class="checkout-btn" type="submit">Check Out</button>
          </form>
        </div>
      </div>
    </div>

    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const reservationData = JSON.parse(sessionStorage.getItem('reservationData'));
        if (reservationData) {

          document.getElementById('full-name').value = reservationData.name;
          // You can use the rest of reservationData to populate other fields or display information as needed
          document.getElementById('room-capacity').innerText = `${reservationData.num_guests} guest${reservationData.num_guests > 1 ? 's' : ''}`;
          document.getElementById('stay-dates').innerText = `From ${reservationData.check_in} to ${reservationData.check_out}`;
        }
      });
    </script>

  </body>

</html>
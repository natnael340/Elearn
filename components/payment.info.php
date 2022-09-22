<div class="col-12">
    <div class="header">
        <h3>Payment Info</h3>
            <a onclick="chng_display(1);" href="#">Edit Payment Detail</a>
        </div>
        <hr>
        <h3>Cardholder Name: <?php echo ucwords($user['fname']); ?></h3>
        <h3>Card Number: <?php echo $user['card_num'] ?></h3>
            <h3>Expiration Date:<?php echo $user['cvv'] ?></h3>
            <h3>CVV: <?php echo $user['exp'] ?></h3>
            <h3>Balance: <?php echo $user['balance'] ?>$</h3>
            <br>
            <br>
        </div>


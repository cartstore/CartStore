
<div class="moduletable-cart">
<div class="cart-box">My Cart: <br /> <span><?php echo $cart->count_contents();?></span> Items - <span><?php echo $currencies->format($cart->show_total());?></span></div>
<a class="checkout-btn" href="checkout_shipping.php"><i class="fa fa-shopping-cart"></i> Checkout</a>
</div>

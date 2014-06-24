<div class="block block-list">
	<div class="block-title">
		<strong> <span>NEWSLETTER</span> </strong>
	</div>

	<div class="block-content">

		<form name="maillist" method="get" action="newsletter.php">
			<label>Enter Your Name</label>

			<input name="list_firstname" type="text" value="" class="form-control" style="width:100%;" />

			<label>Enter Email ID</label>

			<input name="list_email_add" type="text" value="" class="form-control" style="width:100%;"/>

			Subscribe
			<input type="radio" name="list_on_off" value="1" checked="checked" />

			Unsubscribe
			<input type="radio" name="list_on_off" value="0" />
			<input type="hidden" name="check" value="20" />
			<input type="submit" value="subscribe" class="btn btn-default btn-sm" />

			<div class="clear"></div>
		</form>

		<hr>
	</div>
</div>
		<hr>
		<footer id="footer">   
			<div class="row">
				<div class="col col-sm-6">
					<p>
						<i class="icon-heart"></i> Powered by <a href='https://github.com/TomHetmer/Runtime'>Runtime</a>.
					</p>
				</div>
				<div class="col col-sm-6">
					<form method="POST">
						<input type="hidden" name="page" value="<?php echo $_GET['page']; if(isset($_GET['id'])) {echo '?id='.$_GET['id'];} ?>">
						<select class="form-control pull-right" name="locale">
							<option value="cs" <?php if($_GET['locale'] == 'cs') echo "selected" ?>>česky</option>
							<option value="en" <?php if($_GET['locale'] == 'en') echo "selected" ?>>english</option>
						</select>
						<input type="submit" class="js-hide">
					</form>

				</div>
			</div>
		</footer>
</div>

<script src='//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.0.0/js/bootstrap.min.js'></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-timeago/1.1.0/jquery.timeago.min.js"></script>
<script src='/static/live-form-validation.js'></script>

<script>
	$(document).on('change', 'select[name="locale"]', function() {
		$(this).parents('form').submit();
	});
</script>

<?php
if($_GET['locale'] == 'cs') {
?>
<script>
$(document).ready(function() {
	jQuery.timeago.settings.strings = {
  		prefixAgo: "před",
	  	prefixFromNow: null,
	  	suffixAgo: null,
	  	suffixFromNow: null,
	  	seconds: "méně než minutou",
	  	minute: "minutou",
	  	minutes: "%d minutami",
	  	hour: "hodinou",
	  	hours: "%d hodinami",
	  	day: "1 dnem",
	  	days: "%d dny",
	  	month: "1 měsícem",
	  	months: "%d měsíci",
	  	year: "1 rokem",
	  	years: "%d roky"
	};
});
</script>
<?php
}
?>

<script>
	$(document).ready(function() {
	  	$("time.timeago").timeago();
		$('.js-hide').hide();
	});
</script>

</body>
</html>

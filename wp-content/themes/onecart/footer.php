<?php wp_footer(); ?>

</div>
<!-- Please call pinit.js only once per page -->
<script type="text/javascript" async src="//assets.pinterest.com/js/pinit.js"></script> 
<!-- co2 add  -->
<link rel="stylesheet" href="<?php bloginfo('template_directory')?>/css/less.css">
<script type='text/javascript' src='<?php bloginfo('template_directory')?>/js/newsletter.js'></script>
<script type='text/javascript' src='<?php bloginfo('template_directory')?>/js/scrollFix.js'></script>

<script type='text/javascript'>
	$("#the-fix").scrollFix();

</script>

<div id="email-all">
	<div id="email-bg"></div>
	<div id="email-content">
		<div id="email-in">
			<!-- <div id="closebtn"></div> -->
			<div id="email-logo"></div>
			<p id="email-p">sign up to be the first to know about our exclusive offers, new arrivals and styling tips</p>
 			<form method="post" action="http://dress4club.com/wp-content/plugins/newsletter/do/subscribe.php" onsubmit="return newsletter_check(this)">
			<table>	
				<tbody>
					<tr>
						<td><input type="email" placeholder="Email Address" name="ne" size="20" required="" id="email-box"></td>
					</tr>
					<tr>
						<td class="newsletter-td-submit">
							<input class="newsletter-submit" type="submit" value="SUBMIT">
							<!-- <a href="javascript:void(0)" id="closebtn_">no thanks</a> -->
						</td>
					</tr>
				</tbody>
			</table>
			</form>
		</div>
	</div>
</div>
<!-- co2 add  -->

</body>
</html>
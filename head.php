<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<?php
	if($_GET['locale'] == 'cs' && $_GET['page'] == 'index')
		echo '<title>Runtime Monitoring dostupnosti • Stav webů a měření dostupnosti serverů</title>';
	elseif($_GET['page'] == 'index')
		echo '<title>Runtime Uptime Monitoring • Website status and server availability</title>';
	else
		echo '<title>'.str_replace('-', ' ', ucfirst($_GET['page'])).' • Runtime </title>';
	?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/3.2.1/css/font-awesome.min.css" rel="stylesheet">
	<link href="//fonts.googleapis.com/css?family=Ubuntu|Play&amp;subset=latin,latin-ext" rel="stylesheet">
	<link href="/static/app.css" rel="stylesheet">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
	<link rel="alternate" hreflang="x-default" href="http://<?php echo $_SERVER['SERVER_NAME']; ?>/">
	<link rel="alternate" hreflang="en" href="http://<?php echo $_SERVER['SERVER_NAME']; ?>/en/<?php echo $_GET['page'] ?>">
	<link rel="alternate" hreflang="cs" href="http://<?php echo $_SERVER['SERVER_NAME']; ?>/cs/<?php echo $_GET['page'] ?>">
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
	<link rel="icon" href="/favicon.ico" type="image/x-icon">
</head>

<body>
	<div class="navbar-wrapper">
		<div class="container">
			<nav class="navbar"  role="navigation">
				<div class="container">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle navbar-default" data-toggle="collapse" data-target=".navbar-ex1-collapse">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<a class="navbar-brand" href="index"><i class="icon-time"></i> runtime <sup>uptime</sup></a>
					</div>
					<div class="collapse navbar-collapse navbar-ex1-collapse">
						<ul class="nav navbar-nav">
							<?php if(!$user->isLoggedIn()) { ?>
							<li><a href="index"><i class="icon-home"></i> <?= _('Welcome')?></a></li>
							<li><a href="public"><i class="icon-globe"></i> <?= _('Public measurements')?></a></li>
						</ul>
						<ul class="nav navbar-nav navbar-right pull-right">
							<li><a href="login"><i class="icon-signin"></i> <?= _('Sign up or login')?></a></li>
							<?php } else { ?>
							<li><a href="services"><i class="icon-sitemap"></i> <?= _('Services')?></a></li>
							<li><a href="outages"><i class="icon-frown"></i> <?= _('Incidents')?></a></li>
							<li><a href="status"><i class="icon-bar-chart"></i> <?= _('Status page (API)')?></a></li>
							<li><a href="public"><i class="icon-globe"></i> <?= _('Public measurements')?></a></li>
							<?php } ?>
						</ul>
						<?php if($user->isLoggedIn()) { ?>
						<ul class="nav navbar-nav navbar-right pull-right">
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-user"></i> <?php echo $user->getIdentity()->email; ?> <b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li><a href="user"><i class="icon-smile"></i> <?= _('User profile')?></a></li>
									<li><a href="changepw"><i class="icon-lock"></i> <?= _('Change password')?></a></li>
									<li class="divider"></li>
									<li><a href="logout"><i class="icon-signout"></i> <?= _('Log out')?></a></li>
								</ul>
							</li>
						</ul>
						<?php } ?>
					</div>
				</div>
			</nav>
		</div>
	</div>


	<div class="container">

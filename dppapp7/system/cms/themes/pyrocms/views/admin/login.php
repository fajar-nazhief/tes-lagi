<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta name=viewport content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
	<title><?php echo $this->settings->site_name; ?> - <?php echo lang('login_title');?></title>

	<base href="<?php echo base_url(); ?>"/>
	<meta name="robots" content="noindex, nofollow"/>

	<?php Asset::css('workless/workless.css'); ?>
	<?php Asset::css('workless/application.css'); ?>
	<?php Asset::css('workless/responsive.css'); ?>
	<?php Asset::css('animate/animate.css'); ?>

	<?php Asset::js('jquery/jquery.js'); ?>
	<?php Asset::js('admin/login.js'); ?>

	<?php echo Asset::render() ?>
</head>

<body id="login-body">

<div id="container" class="login-screen">
	<section id="content">
		<div id="content-body">

			<div class="animated fadeInDown" id="login-logo"></div>
			<?php $this->load->view('admin/partials/notices') ?>
				<?php echo form_open('admin/login'); ?>
				<div class="form_inputs">
					<ul>
						<li>
							<div class="input animated fadeInDown" id="login-un"><input type="text" name="email" placeholder="<?php echo lang('global:email'); ?>"/></div>
						</li>

						<li>
							<div class="input animated fadeInDown" id="login-pw"><input type="password" name="password" placeholder="<?php echo lang('global:password'); ?>"/></div>
						</li>
						 
					</ul>
					<div class="animated fadeIn" id="login-action">
						<div class="buttons padding-top" id="login-buttons">
							<button id="login-submit" class="btn" ontouchstart="" type="submit" name="submit" value="<?php echo lang('login_label'); ?>">
								<span><?php echo lang('login_label'); ?></span>
							</button>
						</div>
					</div>
					<label for="remember-check" id="login-remember">
								 
								<a href="<?php echo base_url()?>register">Register</a>
							</label>
					<!-- </div> -->
				<?php echo form_close(); ?>
			</div>
		</div>
	</section>
</div>
<footer id="login-footer">
	<div class="wrapper animated fadeInUp" id="login-credits">
		Copyright &copy; 2009 - <?php echo date('Y'); ?> 
		<br><span id="version"><?php echo CMS_VERSION.' '.CMS_EDITION; ?></span>
	</div>
</footer>
</body>
</html>
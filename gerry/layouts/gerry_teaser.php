<?php
	if (empty($page_title_parameters))
	{
		$page_title = $this->evaluate(BabelFish::Get($PAGETITLE));
		$urlencoded_page_title = urlencode($page_title);
	}
	else
	{
		$page_title = sprintf('<phrase id="%s" %s quiet="true"/>',
			$PAGETITLE,
			phrase_parameters(empty($page_title_parameters)?$this->get_variables():$page_title_parameters)
		);
		$urlencoded_page_title = sprintf('<phrase id="%s" %s quiet="true" urlencode="true"/>',
			$PAGETITLE,
			phrase_parameters(empty($page_title_parameters)?$this->get_variables():$page_title_parameters)
		);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xml:lang="<?=Session::Get('language')?>" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<base href="<?=FrontController::GetAbsoluteURI()?>" />
	<title><?=$page_title?></title>
	<link rel="icon" href="<?=Assets::Image('youserbase_favicon.gif')?>" type="image/gif" />
<?php
	Assets::Add('base', 'basemod', 'base.*', 'content', 'navslidingdoor', 'print');
	Assets::Add('jquery.*', 'yb.*', 'tipsy', 'shadowbox', 'sprites*');
	Assets::Glob('./controllers/*/css/*.css');
	Assets::Glob('./controllers/*/plugins/css/*.css');
	Assets::Combine(Config::Get('system', 'harvest_mode'));
	Assets::Minify(Config::Get('system', 'harvest_mode'));
	Assets::Render('CSS', 'youserbase');
?>
<style type="text/css">
html,body {
	height: 100% !important;
	margin: 0;
}
#stage {
	height: 230px;
	margin-top: -100px;
	position: absolute;
	text-align: center;
	top: 50%;
	width: 100%;
}
form#login {
	position: relative;
	padding-top: 70px;
}
form#login div.kernel {
	position: relative;
	margin: auto;
	text-align: left;
	width: 300px;
}
form#login p {
	margin-top: 13px;
	text-align: center;
}
form#login label {
	display: inline-block;
	text-align: right;
	width: 105px;
	margin: 6px;
}
form#login p {
	margin: 0;
}
#logo.closed_beta {
        left: -85px;
		top: -49px;
}
#logo.closed_beta:hover {
	background-position: 0 0;
}
#join_youserbase {
	margin-top: 34px;
}
#join_header {
	font-size: 9px;
}
#join_header.aspired {
	color: green;
}
#join_email {
	position: relative;
	left: -100px;
}
#join_send {
	position: relative;
	left: 15px;
}
#sigin {

	padding: 5px 30px 20px;
}
#sigin_w {
	display: none;
	background: white;
	margin-top: -4px;
}
</style>
</head>
<body>
<div id="stage">
<?php foreach ($dobber as $type=>$messages): ?>
	<?php if (!empty($dobber[$type])): ?>
	<div class="dobber <?=$type?>">
		<div class="label">
			<phrase id="DOBBER_<?=strtoupper($type)?>_LABEL"/>:
		</div>
		<ul>
		<?php foreach ((array)$messages as $message): ?>
            <li>
				<?=$message?>!
			</li>
		<?php endforeach; ?>
        </ul>
	</div>
	<?php endif; ?>
<?php endforeach; ?>
	<?=$CONTENT?>
</div>

	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js" type="text/javascript"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7/jquery-ui.min.js" type="text/javascript"></script>
	<script src="translations.<?=BabelFish::GetLanguage()?>.js<?=Youser::May('investigate')?'':'?minify'?>" type="text/javascript"></script>
<?php
	Assets::Add('shadowbox.jquery', 'shadowbox.2.0', 'shadowbox.skin', 'shadowbox.html');
	Assets::Add('jquery.*', 'youserbase.*', 'yb.*', 'pixastic.*');
	Assets::Glob('./controllers/*/js/*.js');
	Assets::Glob('./controllers/*/plugins/js/*.js');
	Assets::Without('*.load.*');
	Assets::Without('jquery.1.*', 'jquery.ui.*');
	Assets::Combine(Config::Get('system', 'harvest_mode'));
	Assets::Minify(Config::Get('system', 'harvest_mode'));
	Assets::Render('JS', 'youserbase');

	Assets::Add('yb.load.*');
	Assets::Glob('./controllers/*/js/*.load.js');
	Assets::Glob('./controllers/*/plugin/js/*.load.js');
	Assets::Wrap("jQuery(function($){\n%s\n});");
	Assets::Combine(Config::Get('system', 'harvest_mode'));
	Assets::Minify(Config::Get('system', 'harvest_mode'));
	Assets::Render('js', 'youserbase_load');
?>
	<script type="text/javascript">
	//<![CDATA[
		var pageTracker = null;
		jQuery(window).load(function () {
			jQuery.getScript(
				(("https:"==document.location.protocol)?"https://ssl.":"http://www.")+'google-analytics.com/ga.js',
				function () {
					try { pageTracker = _gat._getTracker("UA-7390638-1"); pageTracker._trackPageview();	} catch(err) {}
				}
			);
		});
	//]]>
	</script>
</body>
</html>
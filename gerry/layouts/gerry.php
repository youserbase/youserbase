<?php
	if (empty($page_title_parameters))
	{
		$__title = $this->evaluate(BabelFish::Get($PAGETITLE));
		$page_title = htmlspecialchars($__title, ENT_NOQUOTES, 'UTF-8');
		$urlencoded_page_title = urlencode($__title);
	}
	else
	{
		$this->assign($page_title_parameters);
		$page_title = sprintf('<phrase id="%s" %s quiet="true" entities="true"/>',
			$PAGETITLE,
			phrase_parameters(empty($page_title_parameters)?$this->get_variables():$page_title_parameters)
		);
		$urlencoded_page_title = sprintf('<phrase id="%s" %s quiet="true" urlencode="true"/>',
			$PAGETITLE,
			phrase_parameters(empty($page_title_parameters)?$this->get_variables():$page_title_parameters)
		);
	}
	$meta_description = $this->evaluate(Sitemap::GetMetaDescription(FrontController::GetLocationHash(), BabelFish::GetLanguage()));
	$meta_keywords = $this->evaluate(Sitemap::GetMetaKeywords(FrontController::GetLocationHash(), BabelFish::GetLanguage()));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xml:lang="<?=BabelFish::GetLanguage()?>" xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">
<?php if (Session::Get('debugmode')): ?>
<!-- <?=implode('/', FrontController::GetLocation())?> -->
<!-- <?=FrontController::GetURL()?> -->
<!-- <?=FrontController::GetLink()?> -->
<!-- <?=$BROWSER_STRING?> -->
<?php endif; ?>
	<head>
		<title><?=$page_title?> &lt; youserbase</title>
		<meta name="DC.title" lang="<?=BabelFish::GetLanguage()?>" content="<?=$page_title?>"/>
		<link rel="icon" href="<?=Assets::Image('youserbase_favicon.ico')?>" type="image/ico" />
		<base href="<?=FrontController::GetAbsoluteURI()?>" />
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
		<meta http-equiv="Content-Language" content="<?=BabelFish::GetLanguage()?>" />
		<meta name="y_key" content="3a8a91383cf60bc2"/>
	<?php if ($meta_description): ?>
		<meta name="description" content="<?=$meta_description?>" />
	<?php endif; ?>
	<?php if ($meta_keywords): ?>
		<meta name="keywords" content="<?=$meta_keywords?>"/>
	<?php endif; ?>

	<?php if (Youser::Id()): ?>
		<meta name="PollAdress" content="<?=FrontController::GetLink('User', 'AJAX', 'Poll')?>" />
		<meta name="PollFrequency" content="15000" />
	<?php endif; ?>
<?php
	if (empty($GLOBALS['ASSETS_CACHE']['CSS'][$BROWSER_STRING])):
		$GLOBALS['STORE_ASSETS_CACHE'] = true;
		Assets::Add('base', 'base.*', 'basemod', 'basemod.*', 'content', 'navslidingdoor', 'print');
		Assets::Add('jquery.*', 'yb.*', 'tipsy', 'shadowbox', 'sprites*');
		Assets::Glob('./controllers/*/css/*.css');
		Assets::Glob('./controllers/*/plugins/css/*.css');
		Assets::Add('browser.'.$BROWSER_STRING.'');
		Assets::Combine(Config::Get('system', 'harvest_mode'));
		Assets::Minify(Config::Get('system', 'harvest_mode'));
		if (empty($GLOBALS['ASSETS_CACHE']))
			$GLOBALS['ASSETS_CACHE'] = array('CSS'=>array());
		if (empty($GLOBALS['ASSETS_CACHE']['CSS']))
			$GLOBALS['ASSETS_CACHE']['CSS'] = array();
		$GLOBALS['ASSETS_CACHE']['CSS'][$BROWSER_STRING] = Assets::Render('CSS', 'youserbase.'.$BROWSER_STRING);
	endif;
		print $GLOBALS['ASSETS_CACHE']['CSS'][$BROWSER_STRING];
?>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery<?=Config::Get('system', 'harvest_mode')?'.min':''?>.js" type="text/javascript"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7/jquery-ui<?=Config::Get('system', 'harvest_mode')?'.min':''?>.js" type="text/javascript"></script>
		<script src="http://ajax.microsoft.com/ajax/jquery.validate/1.7/jquery.validate.min.js" type="text/javascript"></script>
<?php
	if (empty($GLOBALS['ASSETS_CACHE']['JS']['JQUERY_FAILOVER'])):
		$GLOBALS['STORE_ASSETS_CACHE'] = true;
		Assets::Add('jquery-1.4.2.js');
		Assets::Add('jquery-ui.1.7.js');
		Assets::Combine(true);
		if (empty($GLOBALS['ASSETS_CACHE']))
			$GLOBALS['ASSETS_CACHE'] = array('JS'=>array());
		if (empty($GLOBALS['ASSETS_CACHE']['JS']))
			$GLOBALS['ASSETS_CACHE']['JS'] = array();
		$GLOBALS['ASSETS_CACHE']['JS']['JQUERY_FAILOVER'] = Assets::Render('js', 'yb.jquery');
	endif;
?>
		<script type="text/javascript">
		//<![CDATA[ jQuery CDN failover
		if (typeof jQuery === 'undefined')
			document.write('<?=preg_replace('~<(/?)script(>?)~', "<$1scr'+'ipt$2", trim($GLOBALS['ASSETS_CACHE']['JS']['JQUERY_FAILOVER']))?>');
		//]]>
		</script>

<?php
	if (empty($GLOBALS['ASSETS_CACHE']['JS']['LOAD'])):
		$GLOBALS['STORE_ASSETS_CACHE'] = true;
		Assets::Add('yb.load.*');
		Assets::Add('./controllers/system/js/languages.load.js');
		Assets::Glob('./controllers/*/js/*.load.js');
		Assets::Glob('./controllers/*/plugins/js/*.load.js');
		Assets::Wrap("jQuery(function($){\n%s\n});");
		Assets::Combine(Config::Get('system', 'harvest_mode'));
		Assets::Minify(Config::Get('system', 'harvest_mode'));
		if (empty($GLOBALS['ASSETS_CACHE']))
			$GLOBALS['ASSETS_CACHE'] = array('JS'=>array());
		if (empty($GLOBALS['ASSETS_CACHE']['JS']))
			$GLOBALS['ASSETS_CACHE']['JS'] = array();
		$GLOBALS['ASSETS_CACHE']['JS']['LOAD'] = Assets::Render('js', 'youserbase_load');
	endif;
		print $GLOBALS['ASSETS_CACHE']['JS']['LOAD'];
?>
<?php
	if (empty($GLOBALS['ASSETS_CACHE']['JS_LOAD'])):
		Assets::Add('jquery.debug');
		Assets::Add('yb.babelfish');
		Assets::Add('json_parse');
		Assets::Add('shadowbox.jquery', 'shadowbox.2.0', 'shadowbox.skin', 'shadowbox.html');
		Assets::Add('jquery.*', 'youserbase.*', 'yb.*' /*, 'pixastic.*' */);
		Assets::Glob('./controllers/*/js/*.js');
		Assets::Glob('./controllers/*/plugins/js/*.js');
		Assets::Without('*.load.*');
		Assets::Without('jquery.1.*', 'jquery.ui.*');
		Assets::Combine(Config::Get('system', 'harvest_mode'));
		Assets::Minify(Config::Get('system', 'harvest_mode'));
		if (empty($GLOBALS['ASSETS_CACHE']))
			$GLOBALS['ASSETS_CACHE'] = array('JS'=>array());
		if (empty($GLOBALS['ASSETS_CACHE']['JS']))
			$GLOBALS['ASSETS_CACHE']['JS'] = array();
		$GLOBALS['ASSETS_CACHE']['JS'][BabelFish::GetLanguage()] = Assets::Render('js', 'youserbase.'.BabelFish::GetLanguage());
	endif;
		print $GLOBALS['ASSETS_CACHE']['JS'][BabelFish::GetLanguage()];
?>
	<?php if (!empty($optional_headers)): ?>
		<?=$optional_headers?>
	<?php endif; ?>
	</head>
    <body id="<?=$body_id?>" <?=Session::Get('Youser', 'old_id')?'class="logged_in_as_different_youser"':''?>>

    <div id="dock"><?=$this->render_partial('dock', compact('PAGETITLE'))?></div>

	<div id="stage">
    	<?=$this->render_partial('gerry.outdated')?>
    	<noscript>
    		<div id="javascript_required">
    			<phrase id="WARNING_JAVASCRIPT_REQUIRED"/>
    		</div>
    	</noscript>
    	<div id="bulk">
   			<navigation id="bulk"/>
    	</div>
		<div id="page_margins">
		<?php if (!Youser::May('zap')): ?>
			<div id="advertisement_header">
				<advertisement id="top"/>
			</div>
		<?php endif; ?>
			<div id="top_bar">
				<a id="logo" class="beta" href="<?=FrontController::GetAbsoluteURI()?>" title_phrase="BACK_TO_HOME"><img src="<?=Assets::Image('youserbase_logo_topwest.gif')?>" alt="gerry, youserbase development" /></a>
				<div id="nav_top">
					<ul id="nav_top_k">
						<li><a href="<?=FrontController::GetLink('System','System','Index')?>"><phrase id="TOPNAVI_STARTPAGE" quiet="true"/></a><div></div></li>
						<li><a href="<?=FrontController::GetLink('Datasheets', 'Catalogue', 'Index')?>"><phrase id="TOPNAVI_CATALOG" quiet="true"/></a><div></div></li>
<?php /*
						<li><a href="<?=FrontController::GetLink('Devices', 'Consultant', 'Index')?>"><phrase id="TOPNAVI_CONSULTANT" quiet="true"/></a><div></div></li>
*/ ?>
						<li><a href="<?=FrontController::GetLink('Devices', 'Tags', 'Index')?>"><phrase id="TOPNAVI_TAGS" quiet="true"/></a><div></div></li>
						<li><a href="<?=FrontController::GetLink('Devices', 'Media', 'All_Videos')?>"><phrase id="TOPNAVI_VIDEOS" quiet="true"/></a><div></div></li>
						<li class="plain right languages">
							<form id="language_switch" style="display: inline;" action="<?=FrontController::GetURL()?>" method="post">
								<fieldset>
									<select name="redirect_uri">
									<?php foreach (BabelFish::GetSpokenLanguages() as $code): ?>
										<option class="stored:language:<?=$code?>" value="<?=FrontController::GetURL($code)?>"<?=BabelFish::GetLanguage()==$code?' selected="selected"':''?>><phrase id="LANGUAGE_<?=$code?>" language="<?=$code?>" quiet="true" /></option>
									<?php endforeach; ?>
									</select>
									<input type="submit" class="button" value_phrase="CHANGE"/>
								</fieldset>
							</form>
						</li>
						<li class="right top_slider" style="display: none;"><div></div><a href="<?=FrontController::GetURL()?>" class="languages"><span class=" flags-sprite front <?=BabelFish::GetLanguage()?>"><phrase id="LANGUAGE_<?=BabelFish::GetLanguage()?>"/></span></a></li>
						<li class="right top_slider" style="display: none;"><div></div><a href="<?=FrontController::GetURL()?>" class="devicehistory"><phrase id="plugin_DeviceHistory"/></a></li>
						<li class="right plain">
							<div></div>
						<?php if (Youser::Get()===false): ?>
							<a class="requires_login" href="<?=FrontController::GetLink('User', 'Access', 'Login')?>"><phrase id="LOGIN"/></a>
						<?php else: ?>
							<phrase id="LOGGED_IN_AS"/>
							<a href="<?=FrontController::GetLink('User', 'Profile', 'Index')?>"><youser id="<?=Youser::Id()?>" link="false"/></a>
							<a title="logout" class="red" href="<?=FrontController::GetLink('User', 'Access', 'Logout', array('return_to'=>FrontController::GetURL()))?>">[x]</a>
						<?php endif; ?>
						</li>
					</ul>
				</div>
				<div id="top_slider">
					<div class="label">
						<span class="devicehistory"><phrase id="plugin_DeviceHistory"/></span>
						<span class="languages" style="display: none;"><phrase id="LANGUAGES"/></span>
					</div>
					<div class="devicehistory" style="display: none;">
						<devicehistory limit="6"/>
					</div>
					<div class="languages" style="display: none;"></div>
				</div>
				<?=Controller::Render('System', 'System_Search', 'Index')?>
			</div>
			<div id="main">
			<?php foreach ($dobber as $type=>$messages): ?>
				<?php if (!empty($dobber[$type])): ?>
				<div class="dobber <?=$type?>">
					<div class="label">
						<phrase id="DOBBER_<?=strtoupper($type)?>_LABEL"/>:
					</div>
					<ul>
					<?php foreach ((array)$messages as $message): ?>
                        <li>
                        <?php if (is_string($message)): ?>
							<?=$message?>!
                        <?php else: ?>
							<phrase id="<?=$message['message']?>" <?=phrase_parameters($message['parameters'])?>/>!
                        <?php endif; ?>
						</li>
					<?php endforeach; ?>
                    </ul>
				</div>
				<?php endif; ?>
			<?php endforeach; ?>

		<?php // LEGACY CODE BELOW // ?>
			<?php if (isset($tabs)): ?>
				<?=$this->render_partial('gerry.tabs', compact('tabs', 'POSITION', 'CONTENT'))?>
			<?php endif; ?>
		<?php // LEGACY CODE ABOVE // ?>
			<?php if (count(Sitemap::GetTabs(FrontController::GetLocationHash()))): ?>
				<?=$this->render_partial('gerry.tabs', array('tabs'=>Sitemap::GetTabs(FrontController::GetLocationHash()), 'POSITION'=>$POSITION, 'CONTENT'=>$CONTENT))?>
			<?php endif; ?>

			<?php if (isset($PREPEND_CONTENT)): ?>
				<?=$PREPEND_CONTENT?>
			<?php endif; ?>
			<?php if (Sitemap::GetNavigation(FrontController::GetLocationHash()) or Sitemap::GetPlugins(FrontController::GetLocationHash())): ?>
				<div id="col2">
			<?php endif; ?>
			<?php if (Sitemap::GetNavigation(FrontController::GetLocationHash())): ?>
				<div class="controller_navigation rbox">
					<?=Navigation::Render(Sitemap::GetNavigation(FrontController::GetLocationHash()))?>
				</div>
			<?php endif; ?>
	<?php // *********** PLUGINS *********** // ?>
			<?=PluginEngine::Engage(Sitemap::GetPlugins(FrontController::GetLocationHash()))?>
	<?php // *********** PLUGINS *********** // ?>
			<?php if (Sitemap::GetNavigation(FrontController::GetLocationHash()) or Sitemap::GetPlugins(FrontController::GetLocationHash())): ?>
				</div>
				<div id="col3">
			<?php endif; ?>
				<div id="content">
<?php // LEGACY CODE BELOW // ?>
				<?php if (isset($tabs)): ?>
					<?=$this->render_partial('gerry.tabcontent', compact('tabs'))?>
				<?php endif; ?>
<?php // LEGACY CODE ABOVE // ?>
			<?=PluginEngine::Engage(Sitemap::GetPlugins(FrontController::GetLocationHash(), 'before'))?>
				<?php if (count(Sitemap::GetTabs(FrontController::GetLocationHash()))): ?>
					<?=$this->render_partial('gerry.tabcontent', array('tabs'=>Sitemap::GetTabs(FrontController::GetLocationHash())))?>
				<?php elseif (!$POSITION): ?>
					<?=$CONTENT?>
				<?php endif; ?>
			<?=PluginEngine::Engage(Sitemap::GetPlugins(FrontController::GetLocationHash(), 'after'))?>
				</div>
				<?php if (Sitemap::GetNavigation(FrontController::GetLocationHash()) or Sitemap::GetPlugins(FrontController::GetLocationHash())): ?>
				<?php //if (isset($CONTROLLER_NAVIGATION) or isset($plugins)): ?>
				</div>
				<?php endif; ?>
				<div class="clr"></div>
			</div>
		</div>
    </div>
	<div id="footer" class="clr">
		<div>
			<p class="first-child">
				&copy; 2008 &ndash; <?=date('Y')?> by youserbase
				: v<?=YB_VERSION?>
				: <?=$page_title?>
			</p>
			<ul class="links">
				<li class="badges-sprite front wordpress">
					<a href="http://www.youserblog.com/">blog</a>
				</li>
				<li class="badges-sprite front digg">
					<a href="http://digg.com/users/youserbase">digg</a>
				</li>
				<li class="badges-sprite front facebook">
					<a href="http://www.facebook.com/group.php?gid=65964772033">facebook</a>
				</li>
				<li class="badges-sprite front twitter">
					<a href="http://twitter.com/youserbase">twitter</a>
				</li>
			</ul>
<?php /*
			<ul class="support">
				<li>
					<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
						<fieldset>
							<input type="hidden" name="cmd" value="_s-xclick" />
							<input type="hidden" name="hosted_button_id" value="6197642" />
							<input type="image" src="https://www.paypal.com/de_DE/DE/i/btn/btn_donate_SM.gif" name="submit" alt="Jetzt einfach, schnell und sicher online bezahlen - mit PayPal." />
							<img alt="" src="https://www.paypal.com/de_DE/i/scr/pixel.gif" width="1" height="1" />
						</fieldset>
					</form>
				</li>
			</ul>
*/ ?>
		</div>
	</div>

<?php /* if (Youser::Id()): ?>
	<?=Controller::RenderAndDisplay('Beta', 'Feedback', 'Display')?>
<?php endif; */ ?>
<?php if (!Youser::Id('god, root')): ?>
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
<?php endif; ?>
<?php if (false): ?>
	<script src="http://static.ak.connect.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php" type="text/javascript"></script>
	<script type="text/javascript">FB.init('6ce82cc65d8acfbf4e0f2bfba168ce41', './xd_receiver.htm', {"ifUserConnected" : auth_using_fb});</script>
<?php endif; ?>
<div id="fb-root"></div>
<script>
  window.fbAsyncInit = function() {
    FB.init({appId: '113687002732', status: true, cookie: true,
             xfbml: true});
  };
  (function() {
    var e = document.createElement('script');
    e.type = 'text/javascript';
    e.src = document.location.protocol +
      '//connect.facebook.net/de_DE/all.js';
    e.async = true;
    document.getElementById('fb-root').appendChild(e);
  }());
</script>
<script type="text/javascript" src="http://widgets.amung.us/tab.js"></script>

<script type="text/javascript">WAU_tab('hi8r8dmlayz2', 'left-middle')</script>

</body>
</html>

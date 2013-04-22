<form id="searchbar" action="<?=FrontController::GetLink('Search', 'Search', array())?>" method="get">
	<div>
		<input type="text" minlength="2" hint="<?=BabelFish::Get('SEARCHPHRASE')?>" id="query" class="text" name="needle" value="<?=isset($needle)?$needle:''?>"/>
		<button type="submit" class="search">
			<span>
				<phrase id="SEARCH"/>
			</span>
		</button>
	</div>
</form>
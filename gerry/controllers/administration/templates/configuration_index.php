<form action="<?=FrontController::GetLink()?>" method="post" class="validate yform columnar">
	<fieldset>
		<legend><phrase id="CONFIGURATION"/></legend>

		<div id="configuration" class="accordion">
		<?php foreach ($config as $section=>$items): ?>
			<div class="ui-accordion-group">
				<h3 class="ui-accordion-header">
					<a class="head" href="<?=FrontController::GetLink()?>#config_<?=$section?>">
						<phrase id="CONFIG_<?=strtoupper($section)?>"/>
					</a>
				</h3>
				<div class="ui-accordion-content">
				<?php foreach ($items as $key=>$parameters): ?>
					<?=$this->render_partial('option', compact('parameters', 'section', 'key'))?>
				<?php endforeach; ?>
				</div>
			</div>
		<?php endforeach; ?>
		</div>
	</fieldset>

	<div class="type-button">
		<label>&nbsp;</label>
		<button type="submit">
			<span><phrase id="SAVE"/></span>
		</button>
		<button type="reset" class="cancel">
			<span><phrase id="CANCEL"/></span>
		</button>
	</div>
</form>

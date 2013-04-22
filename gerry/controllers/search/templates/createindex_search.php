<?=$this->render_partial('search', array('needle' => isset($needle)?$needle:''))?>
<?php if (isset($exact_result)):?>
	<div id="search_results" class="rbox">
		<h3> <?=isset($common_result['total'])?$exact_result['total']+$common_result['total']:$exact_result['total']?> <phrase id="SEARCH_RESULT"/></h3>
		<div class="results">
			<ul class="found">
			<?php if($exact_result['total'] > 0):?>
				
					<li class="products">
					<?php if(!empty($exact_result['matches'])):?>
							<b><phrase id="DEVICES"/></b>
						<ul class="products">
						<?php foreach ($exact_result['matches'] as $id => $content):?>
							<li>
								<device id="<?=$content['device_id']?>"/><br/>
							</li>
						<?php endforeach;?>
						</ul>
					</li>
					<li class="manufacturers">
						<b><phrase id="MANUFACTURERS"/></b>
						<?php if(isset($exact_manu)):?>
						<ul class="manufacturers">
							<?php foreach ($exact_manu as $id):?>
							<li>
								<manufacturer id="<?=$id?>"/><br/>
							</li>
							<?php endforeach;?>
						</ul>
						<?php endif;?>
					<?php endif;?>
					</li>
			<?php endif;?>
		
			<?php if (isset($common_result)):?>
			<?php if($common_result['total'] > 0):?>
		
					<li class="products">
							<b><phrase id="DEVICES"/></b>
						<ul class="products">
						<?php foreach ($common_result['matches'] as $id => $content):?>
							<li>
								<device id="<?=$content['device_id']?>"/><br/>
							</li>
						<?php endforeach;?>
						</ul>
					</li>
					<li class="manufacturers">
						<b><phrase id="MANUFACTURERS"/></b>
						<?php if(isset($common_manu)):?>
						<ul class="manufacturers">
							<?php foreach ($common_manu as $id):?>
							<li>
								<manufacturer id="<?=$id?>"/><br/>
							</li>
							<?php endforeach;?>
						</ul>
						<?php endif;?>
					<?php endif;?>
					</li>
				
			<?php endif;?>
			<?php if(!empty($yousers)):?>
				<li class="yousers">
					<b><phrase id="YOUSERS"/></b>
					<ul class="yousers">
						<?php foreach ($yousers as $youser):?>
							<li><youser id="<?=$youser?>"/></li>
						<?php endforeach;?>
					</ul>
				</li>
			<?php endif;?>
		
			</ul>
		</div>
	</div>
<?php endif;?>

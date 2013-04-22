<div id="search_results" class="rbox">
	<h3> <?=isset($common_result['total'])?$exact_result['total']+$common_result['total']:$exact_result['total']+$youser_count?> <phrase id="SEARCH_RESULT"/>
	</h3>
	<div class="results">
		<ul class="found">
		<?php if(isset($common_result)):?>
			<?=$this->render_partial('search_results', array('result' => $common_result, 'youser_count' => $youser_count, 'manu' => $common_manu, 'skip' => $skip, 'limit' => $limit, 'needle' => $needle))?>
		<?php elseif($exact_result['total'] > 0):?>
			<?=$this->render_partial('search_results', array('result' => $exact_result, 'youser_count' => $youser_count, 'manu' => $exact_manu, 'skip' => $skip, 'limit' => $limit, 'needle' => $needle))?>
		<?php else:?>
			<phrase id="NO_RESULTS"/> <?=$needle?>
			</div>
		<?php endif;?>
		<?php if(isset($yousers) && $youser_count>0):?>
			<li class="devices">
				<b>
				<phrase id="YOUSERS"/>
				</b>
				<ul class="products">
					<?php foreach ($yousers as $id):?>
						<li>
							<youser id="<?=$id?>" type="avatar"/> <br>
							<youser id="<?=$id?>"/> <br>
							
								<a href="<?=FrontController::GetLink('user', 'Network', 'SendRequest', array('youser_id' => $id))?>">
									<phrase id="SEND_FRIENDREQUEST"/>
								</a>
							
						</li>
					<?php endforeach;?>
				</ul>
			</li>
		<?php endif;?>
		</ul>
	</div>
</div>

<div class="advertisement"></div>

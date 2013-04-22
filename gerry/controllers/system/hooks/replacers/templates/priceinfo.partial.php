<div class="price_offers rbox">
	<h3 class="green"><phrase id="LOWEST_PRICES"/></h3>
	<table class="offers">
		<tbody>
		<?php foreach ($prices as $price):?>
			<tr class="price_shop">
				<td>
					<a href="<?=$price['store_url']?>">
						<img class="price_shop_image" src="<?=$price['store_logo']?>" alt="<?=$price['store_name']?>" />
					</a>
				</td>
				<td>
					<a href="<?=$price['store_url']?>">
						<phrase id="PRICE"/> <?=$price['price']?> <phrase id="<?=$price['currency']?>"/><br/>
						<phrase id="SHIPPING"/> <?=$price['shipping']?> <phrase id="<?=$price['currency']?>"/>
					</a>
				</td>
			</tr>
		<?php endforeach;?>
		</tbody>
	</table>
</div>
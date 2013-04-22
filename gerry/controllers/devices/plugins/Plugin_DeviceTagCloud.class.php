<?php
class Plugin_DeviceTagCloud extends Plugin
{
	public static $options = array(
		'display_limit:range:1,50',
		'display_sizes:range:1,10',
	);

	public function fill_template(&$template)
	{
		$tag_sizes = 8;

		$tags = DBManager::Get()->limit($this->get_config('display_limit'))->query("SELECT tag, COUNT(*) AS quantity FROM youser_device_tags GROUP BY tag ORDER BY quantity DESC")->to_array('tag', 'quantity');

		if (empty($tags) and !Youser::Id())
		{
			return false;
		}

		$tag_min = count($tags)?min($tags):0;
		$tag_max = count($tags)?max($tags):0;
		$tag_sum = count($tags)?array_sum($tags):0;
		$tag_delta = ($tag_max-$tag_min)/$tag_sizes;

		$tag_thresholds = array();
		for ($i=1; $i<=$tag_sizes; $i++)
		{
//			$tag_thresholds[$i] = $tag_min + $i * $tag_delta;
			$tag_thresholds[$i] = 100 * log($tag_min + $i * $tag_delta + 2);
		}

		foreach ($tags as $tag=>$count)
		{
			$found = false;
			for ($i=1; $i<=$tag_sizes, !$found; $i++)
			{
//				if ($count < $tag_thresholds[$i])
				if (100 * log($count + 2) <= $tag_thresholds[$i])
				{
					$tags[$tag] = array(
						'class'=>$i
					);
					$found = true;
				}
			}
		}

		uksort($tags, 'strnatcasecmp');

		$template->assign(compact('tags', 'tag_sizes', 'tag_min', 'tag_max', 'tag_sum', 'tag_delta', 'tag_thresholds'));

		return true;
	}
}
?>
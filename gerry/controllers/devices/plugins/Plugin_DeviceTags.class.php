<?php
class Plugin_DeviceTags extends Plugin
{
	public static $options = array(
		'display_limit:range:1,50',
		'display_sizes:range:1,10',
	);

	public function AddTag()
	{
		$tags = array_unique(array_filter(array_map('trim', preg_split('/[ ,]/', $_REQUEST['tag']))));
		foreach ($tags as $tag)
		{
			DBManager::Query("INSERT INTO youser_device_tags (youser_id, device_id, tag, timestamp) VALUES (?, ?, ?, NOW()) ON DUPLICATE KEY UPDATE timestamp=VALUES(timestamp)",
				Youser::Id(),
				$_REQUEST['device_id'],
				$tag
			);
		}
	}

	public function RemoveTag()
	{
		DBManager::Query("DELETE FROM youser_device_tags WHERE youser_id=? AND device_id=? AND tag=?",
			Youser::Id(),
			$_REQUEST['device_id'],
			$_REQUEST['tag']
		);
	}

	public function fill_template(&$template)
	{
		if (!isset($_REQUEST['device_id']))
		{
			return false;
		}

		$tag_sizes = 8;

		$template->assign('device_id', $_REQUEST['device_id']);

		$tags = DBManager::Get()->limit($this->get_config('display_limit'))->query("SELECT tag, COUNT(*) AS quantity FROM youser_device_tags WHERE device_id=? GROUP BY tag ORDER BY quantity DESC",
			$_REQUEST['device_id']
		)->to_array('tag', 'quantity');

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

		if (Youser::Id())
		{
			$my_tags = DBManager::Query("SELECT tag FROM youser_device_tags WHERE device_id=? AND youser_id=? ORDER BY tag ASC",
				$_REQUEST['device_id'],
				Youser::Id()
			)->to_array(null, 'tag');
			foreach ($my_tags as $tag)
			{
				if (isset($tags[$tag]))
				{
					$tags[$tag]['yoused'] = true;
				}
				else
				{
					$tags[$tag] = array(
						'yoused'=>true,
						'class'=>1
					);
				}
			}

		}

		uksort($tags, 'strnatcasecmp');

		$template->assign(compact('tags', 'tag_sizes', 'tag_min', 'tag_max', 'tag_sum', 'tag_delta', 'tag_thresholds'));

		return true;
	}
}
?>
<?php
/**
 * Rudimentärer RSS-Reader-Ansatz
 *
 * Bislang nicht viel mehr als ein Wrapper für SimplePie
 */
class RSS
{
	public static function Read($url)
	{
		$feed = new SimplePie();
		$feed->set_feed_url($url);
		$feed->set_cache_location( Cache::GetDirectory('simplepie') );
		$feed->init();

		$type = null;
		if ($feed->get_type() & SIMPLEPIE_TYPE_RSS_RDF)
		{
			$type = 'RDF';
		}
		elseif ($feed->get_type() & SIMPLEPIE_TYPE_RSS_ALL)
		{
			$type = 'RSS';
		}
		elseif ($feed->get_type() & SIMPLEPIE_TYPE_ATOM_ALL)
		{
			$type = 'Atom';
		}

		if (count($feed->get_items())==0)
		{
			$feed->__destruct();
			return false;
		}

		$result = array(
			'link' => $feed->get_permalink(),
			'type' => $type,
			'title' => $feed->get_title(),
			'description' => $feed->get_description(),
			'image' => $feed->get_favicon(),
			'language' => map_language(substr($feed->get_language(), 0, 2)),
			'items' => array(),
		);

		foreach ($feed->get_items() as $item)
		{
			array_push($result['items'], array(
				'id' => $item->get_id(true),
				'title' => $item->get_title(),
				'description' => $item->get_description(),
				'content' => $item->get_content(),
				'author' => $item->get_author()
					? $item->get_author()->get_name()
					: null,
				'link' => $item->get_permalink(),
				'timestamp' => $item->get_date('U'),
			));
		}
		$feed->__destruct();

		return $result;
	}
}
?>
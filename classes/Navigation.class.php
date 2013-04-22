<?php
class Navigation
{
	private static $cache = array();
	private static $navigation_ids = null;

	public static function Render($navigation_id, $current_location=null, $folded=true, $text_only=false)
	{
		if ($current_location===null)
		{
			$current_location = FrontController::GetLocation();
		}

		$role = 'none';
		if ($youser = Youser::Get())
		{
			$role = $youser->role;
		}

		$data = self::Get($navigation_id);
		$data = self::SelectByRole($data, $role);
		$data = self::RemoveInvalid($data);

		$navigation = '';

		foreach ($data as &$section)
		{
			$plugin_content = false;

			foreach ($section['data'] as $index=>&$item)
			{
				if (!$item['external'] and strpos($item['link_original'], ':')!==false)
				{
					$item['plugin_content'] = Event::Dispatch('grab', 'Navigation:Plugin', $item['link_original']);
					if ($item['plugin_content']===false)
					{
						unset($section['data'][$index]);
					}
				}
			}

			$template = new Template('./layouts/navigations/'.$navigation_id);
			$template->assign('section_name', $section['name']);
			$template->assign('data', $section['data']);
			$navigation .= $template->render();
		}

		return $navigation;
	}

	public static function Get($navigation_id)
	{
		if (isset(self::$cache[$navigation_id]))
		{
			return self::$cache[$navigation_id];
		}

		$result = DBManager::Get()->query("SELECT navigation_id, section_id, unique_id, title, link, type, parent, previous, roles FROM navigations ORDER BY navigation_id, section_id, unique_id");
		$navigations = array();
		while ($row = $result->fetch_array())
		{
			if (!isset($navigations[$row['navigation_id']]))
			{
				$navigations[$row['navigation_id']] = array();
			}
			array_push($navigations[$row['navigation_id']], $row);
		}
		$result->release();

		$result = DBManager::Get()->query("SELECT navigation_id, section_id, position FROM navigation_order ORDER BY navigation_id, position ASC");
		$order = array();
		while ($row = $result->fetch_array())
		{
			if (!isset($order[$row['navigation_id']]))
			{
				$order[$row['navigation_id']] = array();
			}
			array_push($order[$row['navigation_id']], $row);
		}
		$result->release();

		foreach ($navigations as $id=>$rows)
		{
			$navigation = array();
			foreach ($rows as $row)
			{
				$row['roles'] = explode(',', $row['roles']);
				$row['external'] = true;
				$row['link_original'] = $row['link'];
				if (!preg_match('/^https?:/i', $row['link']) and $row['type']!='page')
				{
					$items = array_filter(explode('/', $row['link']));
					$link = call_user_func_array(array('FrontController', 'GetLink'), $items);
					$row['link'] = $link;
					$row['external'] = false;
				}

				if (!isset($navigation[$row['section_id']]))
				{
					$navigation[$row['section_id']] = array();
				}
				$navigation[$row['section_id']][$row['unique_id']] = $row;
			}

			foreach ($navigation as $index=>$section)
			{
				$section_data = array();

				// Find root element
				$root = false;
				foreach ($section as $item)
				{
					if (empty($item['parent']) and empty($item['previous']))
					{
						$root = $item;
						break;
					}
				}
				if ($root===false)
				{
					$navigation[$index] = 'No data';
					continue;
				}

				array_push($section_data, $root);
				unset($section[$root['unique_id']]);

				$previous_id = $root['unique_id'];
				while (!empty($section))
				{
					$previous = reset($section);
					foreach ($section as $item)
					{
						if ($item['previous']==$previous_id)
						{
							$previous = $item;
							break;
						}
					}

					array_push($section_data, $item);

					unset($section[$item['unique_id']]);
					$previous_id = $item['unique_id'];
				}

				$navigation[$index] = $section_data;
			}

			$temp_navigation = array();
			foreach ($order[$id] as $row)
			{
				if (!isset($navigation[$row['section_id']]))
				{
					continue;
				}
				$temp_navigation[$row['position']] = array(
					'name'=>$row['section_id'],
					'data'=>$navigation[$row['section_id']]
				);
			}

			self::$cache[$id] = $temp_navigation;

		}

		return self::$cache[$navigation_id];
	}

	public static function SelectByRole($navigation, $role)
	{
		foreach ($navigation as $index=>$section)
		{
			foreach ($section['data'] as $inner_index=>$item)
			{
				if (!in_array($role, $item['roles']))
				{
					unset($section['data'][$inner_index]);
				}
			}
			$navigation[$index]['data'] = array_merge($section['data']);
			if (empty($navigation[$index]['data']))
			{
				unset($navigation[$index]);
			}
		}
		array_merge($navigation);
		return $navigation;
	}

	public static function RemoveInvalid($navigation)
	{
		foreach ($navigation as $index=>$section)
		{
			foreach ($section['data'] as $inner_index=>$item)
			{
				if (!$item['external'] and strpos($item['link_original'], ':')===false and !FrontController::CheckLocation($location, $item['link_original']))
				{
					unset($section['data'][$inner_index]);
				}
			}
			$navigation[$index]['data'] = array_merge($section['data']);
			if (empty($navigation[$index]['data']))
			{
				unset($navigation[$index]);
			}
		}
		array_merge($navigation);
		return $navigation;
	}

	public static function RenameSection($navigation_id, $section_id, $new_name)
	{
		DBManager::Get()->query("UPDATE navigation_order SET section_id=TRIM(?) WHERE section_id=? AND navigation_id=?",
			$new_name,
			$section_id,
			$navigation_id
		);
		DBManager::Get()->query("UPDATE navigations SET section_id=TRIM(?) WHERE section_id=? AND navigation_id=?",
			$new_name,
			$section_id,
			$navigation_id
		);
	}

	public static function RemoveSection($navigation_id, $section_id)
	{
		DBManager::Get()->query("DELETE FROM navigation_order WHERE navigation_id=? AND section_id=?",
			$navigation_id,
			$section_id
		);
		DBManager::Get()->query("DELETE FROM navigations WHERE navigation_id=? AND section_id=?",
			$navigation_id,
			$section_id
		);
		self::Reorder($navigation_id);
	}

	public static function CreateSection($navigation_id, $section_id)
	{
		DBManager::Get()->query("INSERT INTO navigation_order (navigation_id, section_id, position) VALUES (?, ?, 1000000)",
			$navigation_id,
			$section_id
		);
		self::Reorder($navigation_id);
	}

	private static function Reorder($navigation_id)
	{
		$order = array();
		$result = DBManager::Get()->query("SELECT section_id FROM navigation_order WHERE navigation_id=? ORDER BY position ASC",
			$navigation_id
		);
		$order = $result->to_array(null, 'section_id');

		foreach ($order as $index=>$section_id)
		{
			DBManager::Get()->query("UPDATE navigation_order SET position=? WHERE navigation_id=? AND section_id=?",
				$index,
				$navigation_id,
				$section_id
			);
		}
	}

	public static function CreateItem($navigation_id, $section_id, $type, $title, $link, $roles)
	{
		$previous = DBManager::Get()->query("SELECT MAX(unique_id) FROM navigations WHERE navigation_id=? AND section_id=?",
			$navigation_id,
			$section_id
		)->fetch_item();

		DBManager::Get()->query("INSERT INTO navigations (navigation_id, section_id, type, title, link, roles, previous) VALUES (?,?,?,TRIM(?),TRIM(?),?,?)",
			$navigation_id,
			$section_id,
			$type,
			$title,
			$link,
			implode(',', $roles),
			$previous
		);
	}

	public static function RemoveItem($navigation_id, $section_id, $item_id)
	{
		$result = DBManager::Get()->query("SELECT previous FROM navigations WHERE navigation_id=? AND section_id=? AND unique_id=?",
			$navigation_id,
			$section_id,
			$item_id
		);
		if ($result->is_empty())
		{
			return;
		}
		$previous_id = $result->fetch_item('previous');

		DBManager::Get()->query("UPDATE navigations SET previous=? WHERE navigation_id=? AND section_id=? AND previous=?",
			$previous_id,
			$navigation_id,
			$section_id,
			$item_id
		);
		DBManager::Get()->query("DELETE FROM navigations WHERE navigation_id=? AND section_id=? AND unique_id=?",
			$navigation_id,
			$section_id,
			$item_id
		);

		$result = DBManager::Get()->query("SELECT 1 FROM navigations WHERE navigation_id=? AND section_id=?",
			$navigation_id,
			$section_id
		);
		if (!$result->is_present())
		{
			self::RemoveSection($section_id);
		}
	}

	public static function Set($navigation_id, $section_id, $item_id, $type, $title, $link, $roles)
	{
		DBManager::Get()->query("UPDATE navigations SET title=TRIM(?), link=TRIM(?), type=?, roles=? WHERE navigation_id=? AND section_id=? AND unique_id=?",
			$title,
			$link,
			$type,
			implode(',', $roles),
			$navigation_id,
			$section_id,
			$item_id
		);
	}

	public static function GetNavigationIds()
	{
		if (self::$navigation_ids===null)
		{
			self::$navigation_ids = DBManager::Get()->query("SELECT DISTINCT navigation_id FROM navigation_order")->to_array(null, 'navigation_id');
		}

		return self::$navigation_ids;
	}

	public static function GetRoles()
	{
		$roles = array();

		$result = DBManager::Get()->query("DESC navigations");
		while ($row = $result->fetch_array())
		{
			if ($row['Field']!='roles')
			{
				continue;
			}
			eval('$roles = '.str_replace(array('enum(', 'set('), 'array(', $row['Type']).';');
		}
		$result->release();

		return $roles;
	}

	public static function MoveSection($navigation_id, $section_id, $direction)
	{
		$navigation = self::Get($navigation_id);
		$position = false;

		foreach ($navigation as $pos=>$section)
		{
			if ($section_id==$section['name'])
			{
				$position = $pos;
			}
		}
		if ($position===false)
		{
			throw new Exception(sprintf('Section "%s" not found in navigation "%s"',
				$section_id,
				$navigation_id
			));
		}

		$new_section = false;
		$new_position = -1;
		if ($direction=='up' or $direction>0)
		{
			foreach ($navigation as $pos=>$section)
			{
				if ($pos==$position)
				{
					break;
				}
				$new_position = $pos;
				$new_section = $section['name'];
			}
		}
		elseif ($direction=='down' or $direction<0)
		{
			foreach ($navigation as $pos=>$section)
			{
				$last_section = $new_section;
				$last_position = $new_position;
				$new_section = $section['name'];
				$new_position = $pos;
				if ($last_position==$position)
				{
					break;
				}
			}
		}
		if ($new_section!==false)
		{
			DBManager::Get()->query("UPDATE navigation_order SET position=?-position WHERE section_id IN (?)",
				$position+$new_position,
				array($section_id, $new_section)
			);
		}
	}

	public static function RelocateItem($navigation_id, $section_id, $item_id, $new_previous)
	{
		$result = DBManager::Get()->query("SELECT previous FROM navigations WHERE navigation_id=? AND section_id=? AND unique_id=?",
			$navigation_id,
			$section_id,
			$item_id
		);
		if ($result->is_empty())
		{
			return;
		}
		$previous_id = $result->fetch_item('previous');

		DBManager::Get()->query("UPDATE navigations SET previous=? WHERE navigation_id=? AND section_id=? AND previous=?",
			$previous_id,
			$navigation_id,
			$section_id,
			$item_id
		);
		DBManager::Get()->query("UPDATE navigations SET previous=? WHERE navigation_id=? AND section_id=? AND unique_id=?",
			$new_previous,
			$navigation_id,
			$section_id,
			$item_id
		);
	}

	public static function MoveItemToSection($navigation_id, $section_id, $item_id, $new_section_id)
	{
		$result = DBManager::Get()->query("SELECT previous FROM navigations WHERE navigation_id=? AND section_id=? AND unique_id=?",
			$navigation_id,
			$section_id,
			$item_id
		);
		$previous = $result->fetch_item('previous');

		DBManager::Get()->query("UPDATE navigations SET previous=? WHERE navigation_id=? AND section_id=? AND previous=?",
			$previous,
			$navigation_id,
			$section_id,
			$item_id
		);

		$result = DBManager::Get()->query("SELECT MAX(unique_id) FROM navigations WHERE navigation_id=? AND section_id=?",
			$navigation_id,
			$new_section_id
		);
		$unique_id = $result->fetch_item();

		$result = DBManager::Get()->query("SELECT unique_id FROM navigations WHERE navigation_id=? AND section_id=? AND unique_id NOT IN (SELECT previous FROM navigations WHERE navigation_id = ? AND section_id = ? AND previous IS NOT NULL)",
			$navigation_id,
			$new_section_id,
			$navigation_id,
			$new_section_id
		);
		$previous = $result->fetch_item();

		DBManager::Get()->query("UPDATE navigations SET unique_id=?, previous=?, section_id=? WHERE navigation_id=? AND section_id=? AND unique_id=?",
			$unique_id+1,
			$previous,
			$new_section_id,
			$navigation_id,
			$section_id,
			$item_id
		);
	}

	public static function MoveSectionItem($navigation_id, $section_id, $item_id, $direction)
	{
		$navigation = self::Get($navigation_id);
		$section = false;
		foreach ($navigation as $sec)
		{
			if ($sec['name']==$section_id)
			{
				$section = $sec['data'];
			}
		}
		if ($section===false)
		{
			throw new Exception(sprintf('Unknown section "%s" in navigation "%s"',
				$section_id,
				$navigation_id
			));
		}
		$current_item = false;
		foreach ($section as $item)
		{
			if ($item['unique_id']==$item_id)
			{
				$current_item = $item;
			}
		}
		if ($current_item===false)
		{
			echo 'foo';
			throw new Exception('Unknown item id');
		}

		if ($direction=='up' or $direction>0)
		{
			$first_item = $current_item;
			$second_item = false;
			foreach ($section as $item)
			{
				if ($item['unique_id']==$first_item['previous'])
				{
					$second_item = $item;
				}
			}
		}
		elseif ($direction=='down' or $direction<0)
		{
			$second_item = $current_item;
			$first_item = false;
			foreach ($section as $item)
			{
				if ($item['previous']==$second_item['unique_id'])
				{
					$first_item = $item;
				}
			}
		}
		DBManager::Get()->query("UPDATE navigations SET previous=? WHERE navigation_id=? AND section_id=? AND previous=?",
			$second_item['unique_id'],
			$navigation_id,
			$section_id,
			$first_item['unique_id']
		);
		DBManager::Get()->query("UPDATE navigations SET previous=? WHERE navigation_id=? AND section_id=? AND unique_id=?",
			$first_item['unique_id'],
			$navigation_id,
			$section_id,
			$first_item['previous']
		);
		DBManager::Get()->query("UPDATE navigations SET previous=? WHERE navigation_id=? AND section_id=? AND unique_id=?",
			$second_item['previous'],
			$navigation_id,
			$section_id,
			$first_item['unique_id']
		);
	}
}
?>
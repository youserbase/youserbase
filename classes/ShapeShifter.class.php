<?php
/**
 * Shapeshifter class
 *
 */
class ShapeShifter
{
	private static $exporters = array(
		'php' => array('self', 'export_as_php'),
		'json' => array('self', 'export_as_json'),
		'xml' => array('self', 'export_as_xml'),
	);

	public static function Transform($data, $type)
	{
		$type = strtolower($type);
		if (!isset(self::$exporters[$type]))
			throw new Exception('No handler defined for export type "'.$type.'"');

		$arguments = array_slice(func_get_args(), 2);
		array_unshift($arguments, $data);

		return call_user_func_array(self::$exporters[$type], $arguments);
	}

	private static function export_as_php($data)
	{
		return serialize($data);
	}

	private static function export_as_json($data)
	{
		return json_encode($data);
	}

	private static function export_as_xml($data, $parameters=array(), $pretty_print = true)
	{
		$dom = null;
		self::array_to_xml($data, $dom, $parameters);
		$xml = $dom->asXML();
		$xml = str_replace(array('&lt;![CDATA[', ']]&gt;'), array('<![CDATA[', ']]>'), $xml);

		if ($pretty_print)
		{

			// Pretty print, taken and adapted from google
			$level = 1;
			$indent = 0;
			$pretty = array();

			$xml = explode("\n", preg_replace('/>\s*</', ">\n<", $xml));

			if (count($xml) and preg_match('/^<\?\s*xml/', reset($xml)))
			{
				array_push($pretty, array_shift($xml));
			}

			foreach ($xml as $node)
			{
				if (preg_match('/^<[\w]+[^>\/]*>$/U', $node))
				{
					array_push($pretty, str_repeat("\t", $indent) . $node);
					$indent += $level;
				}
				else
				{
			 		if (preg_match('/^<\/.+>$/', $node))
						$indent -= $level;

					if ($indent < 0)
						$indent += $level;

					array_push($pretty, str_repeat("\t", $indent) . $node);
				}
			}
			$xml = implode("\n", $pretty);
		}
		return $xml;
	}

	private static function array_to_xml($array, &$node, $parameters)
	{
		if ($node === null)
		{
			$root_node = isset($parameters['root_node']) ? $parameters['root_node'] : 'root';
			$root_attributes = isset($parameters['root_attributes']) ? $parameters['root_attributes'] : array();

			$attributes = '';
			foreach ($root_attributes as $key=>$value)
				$attributes .= ' '.$key.'="'.$value.'"';

			$node = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><'.$root_node.$attributes.' />');
		}

		foreach ($array as $key => $value)
		{
			$index = is_numeric($key)
				? 'item'
				: $key;

			if (is_array($value))
			{
				$subnode = $node->addChild($index);
				self::array_to_xml($value, $subnode, $parameters);
			}
			else
				$subnode = $node->addChild($index, $value);

			if (!empty($parameters['add_id_index']) and is_numeric($key))
				$subnode->addAttribute('id', $key);
		}
	}
}
?>
<?php
// Heavily optimization required
class Message
{
	private $data = array(
		'message_id'=>null,
		'sender_id'=>null,
		'receiver_id'=>null,
		'subject'=>null,
		'message'=>null,
		'timestamp'=>null,
		'read_timestamp'=>null,
		'seen_timestamp'=>null,
		'reply_to'=>null
	);

	public function __construct($message_id = null, $user_id=null)
	{
		$this->data['message_id'] = $message_id;
		if ($this->data['message_id']!==null)
		{
			$this->load($user_id);
		}
	}

	// Ugly as hell, i know
	public function set_data($data)
	{
		$this->data = $data;
	}

	private function load($user_id=null)
	{
		$result = $user_id===null
			? DBManager::Get()->query("SELECT message_id, youser_id AS sender_id, recipient_id AS receiver_id, subject, message, UNIX_TIMESTAMP(timestamp) AS timestamp, UNIX_TIMESTAMP(read_timestamp) AS read_timestamp, UNIX_TIMESTAMP(seen_timestamp) AS seen_timestamp, reply_to FROM youser_messages WHERE message_id=? LIMIT 1", $this->data['message_id'])
			: DBManager::Get()->query("SELECT message_id, youser_id AS sender_id, recipient_id AS receiver_id, subject, message, UNIX_TIMESTAMP(timestamp) AS timestamp, UNIX_TIMESTAMP(read_timestamp) AS read_timestamp, UNIX_TIMESTAMP(seen_timestamp) AS seen_timestamp, reply_to FROM youser_messages WHERE message_id=? AND ? IN (youser_id, recipient_id) LIMIT 1", $this->data['message_id'], $user_id);
		if (!$result)
		{
			throw new Exception('Malformed data '.__METHOD__);
		}
		$this->data = $result->fetch_array();
		$result->release();

		if ($this->data['seen_timestamp']===null)
		{
			$this->data['seen_timestamp'] = time();
		}
	}

	public function save()
	{
		DBManager::Get()->query("INSERT INTO youser_messages (message_id, youser_id, recipient_id, subject, message, timestamp, read_timestamp, seen_timestamp, reply_to) VALUES (?, ?, ?, TRIM(?), TRIM(?), NOW(), FROM_UNIXTIME(?), FROM_UNIXTIME(?), ?) ON DUPLICATE KEY UPDATE read_timestamp=VALUES(read_timestamp), seen_timestamp=VALUES(seen_timestamp)",
			$this->data['message_id'],
			$this->data['sender_id'],
			$this->data['receiver_id'],
			trim($this->data['subject']),
			trim($this->data['message']),
			$this->data['read_timestamp'],
			$this->data['seen_timestamp'],
			$this->data['reply_to']
		);

		if ($this->data['message_id']===null)
		{
			$this->data['message_id'] = DBManager::Get()->get_inserted_id();
		}
		return $this->data['message_id'];
	}

	public function __set($name, $value)
	{
		if (!in_array($name, array_keys($this->data)))
		{
			throw new Exception('Illegal access to field "'.$name.'" in '.__METHOD__);
		}
		$this->data[$name] = $value;
	}

	public function __get($name)
	{
		if (!in_array($name, array_keys($this->data)))
		{
			throw new Exception('Illegal access to field "'.$name.'" in '.__METHOD__);
		}
		return $this->data[$name];
	}

	public function __isset($name)
	{
		return isset($this->data[$name]);
	}

	public function toArray()
	{
		return $this->data;
	}

/* STATIC FUNCTIONS *********************************************************/

	public static function Read($message_id, $recipient_id)
	{
		self::ToggleRead($message_id, $recipient_id);
		$message = new Message($message_id, $recipient_id);
		return $message->toArray();
	}

	public static function ToggleRead($message_id, $recipient_id)
	{
		DBManager::Get()->query("UPDATE youser_messages SET read_timestamp=NOW() WHERE message_id IN (?) AND recipient_id=?",
			$message_id,
			$recipient_id
		);
	}

	public static function ToggleUnread($message_id, $recipient_id)
	{
		DBManager::Get()->query("UPDATE youser_messages SET read_timestamp=NULL WHERE message_id IN (?) AND recipient_id=?",
			$message_id,
			$recipient_id
		);
		return DBManager::Get()->affected_rows();
	}

	public static function DeleteInbound($youser_id, $message_id)
	{
		DBManager::Get()->query("UPDATE youser_messages SET recipient_deleted=1 WHERE recipient_id=? AND message_id IN (?)", $youser_id, $message_id);
		return DBManager::Get()->affected_rows()>=0;
	}

	public static function DeleteOutbound($youser_id, $message_id)
	{
		DBManager::Get()->query("UPDATE youser_messages SET sender_deleted=1 WHERE youser_id=? AND message_id IN (?)", $youser_id, $message_id);
		return DBManager::Get()->affected_rows()>=0;
	}

	private static $inbound_counts = array();
	private static $outbound_counts = array();

	public static function GetInboundCount($youser_id)
	{
		if (!isset(self::$inbound_counts[$youser_id]))
		{
			self::GetInOutboundCounts($youser_id);
		}
		return self::$inbound_counts[$youser_id];
	}

	public static function GetOutboundCount($youser_id)
	{
		if (!isset(self::$outbound_counts[$youser_id]))
		{
			self::GetInOutboundCounts($youser_id);
		}
		return self::$outbound_counts[$youser_id];
	}

	private static function GetInOutboundCounts($youser_id)
	{
		$counts = DBManager::Get()->query("SELECT SUM(recipient_id=? AND recipient_deleted=0) AS inbound, SUM(youser_id=? AND sender_deleted=0) AS outbound FROM youser_messages WHERE ? IN (youser_id, recipient_id)", $youser_id, $youser_id, $youser_id)->fetch_item(array('inbound', 'outbound'));
		self::$inbound_counts[$youser_id] = $counts['inbound'];
		self::$outbound_counts[$youser_id] = $counts['outbound'];
	}

	public static function GetBundle($message_ids, $youser_id)
	{
		if (count($message_ids)==0)
		{
			return array();
		}
		$messages = DBManager::Get()->query("SELECT message_id, youser_id AS sender_id, recipient_id AS receiver_id, subject, message, UNIX_TIMESTAMP(timestamp) AS timestamp, UNIX_TIMESTAMP(read_timestamp) AS read_timestamp, UNIX_TIMESTAMP(seen_timestamp) AS seen_timestamp, reply_to FROM youser_messages WHERE message_id IN (?) AND ? IN (youser_id, recipient_id)", $message_ids, $youser_id)->to_array('message_id');

		$result = array();
		foreach ($message_ids as $message_id)
		{
			$message = new Message();
			$message->set_data($messages[$message_id]);
			$result[$message_id] = $message->toArray();
		}

		return $result;
	}

	public static function GetInbound($youser_id, $skip=null, $limit=null)
	{
		$query = "SELECT message_id FROM youser_messages WHERE recipient_id=? AND recipient_deleted=0 ORDER BY timestamp DESC";
		$message_ids = DBManager::Get()->skip($skip)->limit($limit)->query($query, $youser_id)->to_array(null, 'message_id');

		$messages = self::GetBundle($message_ids, $youser_id);

		if (count($message_ids))
		{
			DBManager::Get()->query("UPDATE youser_messages SET seen_timestamp=NOW() WHERE recipient_id=? AND message_id IN (?)",
				$youser_id,
				$message_ids
			);
		}

		return $messages;
	}

	public static function GetOutbound($youser_id, $skip=null, $limit=null)
	{
		$query = "SELECT message_id FROM youser_messages WHERE youser_id=? AND sender_deleted=0 ORDER BY timestamp DESC";

		$message_ids = DBManager::Get()->skip($skip)->limit($limit)->query($query, $youser_id)->to_array(null, 'message_id');
		$messages = self::GetBundle($message_ids, $youser_id);

		return $messages;
	}

	public static function GetUnseenMessages($youser_id)
	{
		return DBManager::Get()->query("SELECT COUNT(*) FROM youser_messages WHERE recipient_id=? AND recipient_deleted=0 AND seen_timestamp IS null", $youser_id)->fetch_item();
	}

	public static function GetUnreadMessages($youser_id)
	{
		return DBManager::Get()->query("SELECT COUNT(*) FROM youser_messages WHERE recipient_id=? AND recipient_deleted=0 AND read_timestamp IS NULL", $youser_id)->fetch_item();
	}
}
?>
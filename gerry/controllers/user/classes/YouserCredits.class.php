<?php
class YouserCredits
{
	public static function GetCredits($youser_id = null)
	{
		if ($youser_id === null)
		{
			$youser_id = Youser::Id();
		}
		return DBManager::Get()->query("SELECT credits FROM youser_credits WHERE youser_id=?",
			$youser_id
		)->fetch_item();
	}

	public static function GetUnseenCount($youser_id = null)
	{
		if ($youser_id === null)
		{
			$youser_id = Youser::Id();
		}
		return DBManager::Get()->query("SELECT COUNT(*) FROM youser_credit_transactions WHERE youser_id=? AND seen_timestamp IS NULL",
			$youser_id
		)->fetch_item();
	}

	public static function Reward($youser_id, $amount, $sender, $purpose)
	{
		DBManager::Get()->query("INSERT INTO youser_credits (youser_id, credits, total_credits) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE credits=credits+VALUES(credits), total_credits=total_credits+VALUES(total_credits)",
			$youser_id,
			$amount,
			$amount
		);

		DBManager::Get()->query("INSERT INTO youser_credit_transactions (youser_id, amount, sender_id, purpose, timestamp) VALUES (?, ?, ?, ?, NOW())",
			$youser_id,
			$amount,
			$sender,
			$purpose
		);
	}
}
?>
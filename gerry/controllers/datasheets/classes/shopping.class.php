<?php
class shopping
{
	protected static $instance = null;
	protected static $manufacturer = null;
	protected static $device = null;
	private static $xml;

	public function Get($device, $manufacturer, $device_id)
	{
		if (self::$instance == null && (self::$manufacturer == null && $device == null) || (self::$manufacturer!= $manufacturer || self::$device != $device))
		{
			self::$instance = new self();
			self::$manufacturer = $manufacturer;
			self::$device = $device;
			self::$xml = self::get_prices($manufacturer, $device, $device_id);
		}
		return self::$xml;
	}

	public function __construct()
	{
	}


	public function __clone()
	{
		throw new Exception('Cannot duplicate a singleton.');
	}

	private static function get_prices($manu, $device, $device_id)
	{
		$date = date('Y-m-d');
		$prices = DBManager::Get('devices')->query("SELECT store_url, store_name, store_logo, price, shipping, currency FROM affiliate_partners WHERE language = ? AND device_id = ? AND timestamp = ? ORDER BY price+shipping ASC;", BabelFish::GetLanguage(), $device_id, $date)->to_array();
		if(empty($prices))
		{
			$xml = self::ask_api($manu, $device);
			if($xml === false)
			{
				return false;
			}
			$prices = $xml->categories->category->items->product->offers;
			$prices = self::store_prices($device_id, $prices);
		}
		return $prices;
	}

	private static function store_prices($device_id, $prices)
	{
		$date = date('Y-m-d');
		$device_id_int = DBManager::Get('devices')->query("SELECT device_id_int FROM device WHERE device_id = ?;", $device_id)->fetch_item();
		$result = array();
		if($prices == null)
		{
			return false;
		}
		foreach ($prices->offer as $price)
		{
			$url = (string)$price->offerURL;
			$name = (string)$price->store->name;
			$logo = (string)$price->store->logo->sourceURL;
			$prix = (string)$price->basePrice;
			$shipping = (string)$price->shippingCost;
			$currency = reset(reset($price->basePrice));
			DBManager::Get('devices')->query("INSERT INTO affiliate_partners (device_id, device_id_int, language, store_url, store_name, store_logo, price, shipping, currency, timestamp) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", $device_id, $device_id_int, BabelFish::GetLanguage(), $url, $name, $logo, $prix, $shipping, $currency, $date);
			$result[] = array('store_url' => $url, 'store_name' => $name, 'store_logo' => $logo, 'price' => $prix, 'shipping' => $shipping, 'currency' => $currency);
		}
		return $result;
	}


	private static function ask_api($manu, $device)
	{
		$url = 'publisher.usa.api.shopping.com';
		if(Youser::Is('administrator', 'root', 'god'))
		{
			$url = 'sandbox.api.shopping.com';
		}
		switch (BabelFish::GetLanguage())
		{
			case 'de':
				$key = 'bf715187-cdc4-4885-bc0f-50dc9b35f737';
				$id = '8056620';
				break;
			case 'uk':
				$key = 'c1a5c9eb-04d0-47fc-977a-9b8365d82fe5';
				$id = '8058104';
				break;
			case 'us':
				$key = 'c8b58a24-527c-47e4-955c-9919bee54ac9';
				$id = '8057400';
				break;
			case 'fr':
				$key = '27a4acc1-50a3-4e6e-a80d-6687ab0c330e';
				$id = '8057588';
				break;
			case 'au':
				return false;
				break;
			default:
				return false;
				break;

		}
		$request = 'http://'.$url.'/publisher/3.0/rest/GeneralSearch?apiKey='.$key.'&trackingId='.$id.'&categoryId=93767&numAttributes=0&numItems=3&offerSortType=price&offerSortOrder=asc&showProductsWithoutOffers=true&keyword='.$device.'+'.$manu;

		$result = HTTP::Fetch($request);
		$xml = simplexml_load_string($result);
		$count = reset($xml->categories);
		if($count['returnedCategoryCount'] > 0)
		{
			return $xml;
		}
		return false;
	}

}
?>
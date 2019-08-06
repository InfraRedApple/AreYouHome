<?php
/**
 * Created by PhpStorm.
 * User: adammcelhaney
 * Date: 9/11/18
 * Time: 1:04 PM
 */

namespace Core;


class Utilities
{
	public static function randomPassword()
	{
		$words = ["Apple", "Banana", "Cupcake", "Donut", "Eclair", "Froyo", "Gingerbread", "Honeycomb", "IceCream", "JellyBean", "KitKat", "Lollipop", "Marshmallow", "Nougat", "Oreo", "Pie", "Quiche", "Rolo", "Snicker", "Taffy", "Umbrella", "Victory", "Water", "Xray", "Yogurt", "Zebra"];
		$numbers = '0123456789';
		$characters = '!@#$%?';
		
		shuffle($words);
		
		return $words[0] . substr(str_shuffle($characters), 0, 1) . substr(str_shuffle($numbers), 2, 3);
		
		
	}
	
	public static function get_client_ip()
	{
		$ipaddress = '';
		if (isset($_SERVER['HTTP_CLIENT_IP']))
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if (isset($_SERVER['HTTP_X_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		else if (isset($_SERVER['HTTP_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		else if (isset($_SERVER['REMOTE_ADDR']))
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}
	
	public static function time_ago($date)
	{
		$timestamp = strtotime($date);
		
		$strTime = ["second", "minute", "hour", "day", "month", "year"];
		$length = ["60", "60", "24", "30", "12", "10"];
		
		$currentTime = time();
		if ($currentTime >= $timestamp) {
			$diff = time() - $timestamp;
			for ($i = 0; $diff >= $length[$i] && $i < count($length) - 1; $i++) {
				$diff = $diff / $length[$i];
			}
			
			$diff = round($diff);
			return $diff . " " . $strTime[$i] . "(s) ago ";
		}
	}
}
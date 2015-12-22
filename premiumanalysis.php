<?php
require_once 'phplot.php';


	//OKCoin DEMO 入口
	$client = new OKCoin(new OKCoin_ApiKeyAuthentication(API_KEY, SECRET_KEY));
	
	//获取OKCoin行情（盘口数据）
	// $params = array('symbol' => 'btc_usd');
//	$result = $client -> tickerApi($params);
 // print_r($result);

$btcffrjson = file_get_contents('https://api.bitfinex.com/v1/lendbook/BTC?limit_bids=0&limit_asks=1');
$btcffrarray = json_decode($btcffrjson, true);
if (isset($btcffrarray)) {
$btcffr = $btcffrarray['asks'][0]['rate'];
} else {
$btcffr = "N/A";
}
echo "<br>";
print_r("Bitfinex BTC yield (APY):".$btcffr."%");

	$getokcindex = array('symbol' => 'btc_usd', 'type' => '1day', 'contract_type' => 'this_week', 'size' => 5);
	$okcindex = $client -> getFutureIndexFutureApi($getokcindex);
    $theokcindex = $okcindex->future_index;




	$getokcweekly = array('symbol' => 'btc_usd', 'contract_type' => 'this_week');
	$okcweekly = $client -> tickerFutureApi($getokcweekly);
$theokcweekly = $okcweekly->ticker->last;
$weeklyspread = $theokcweekly - $theokcindex;
$weeklyspreadperc = ($weeklyspread / $theokcindex)*100;
$weeklyspreadperc2 = round($weeklyspreadperc, 2);

$dayofweek = date('w', strtotime(getdate()));
if ($dayofweek == 6) {
$okcwdays = 7;
$okcbdays = 14;
} else {
$okcwdays = 6-$dayofweek;
$okcbdays = $okcwdays + 7;
}
$weeklyspreadpa2 = (pow(($theokcweekly/$theokcindex), (365/$okcwdays))-1)*100;
$weeklyspreadpa = round($weeklyspreadpa2, 2);

	$getokcbiweekly = array('symbol' => 'btc_usd', 'contract_type' => 'next_week');
	$okcbiweekly = $client -> tickerFutureApi($getokcbiweekly);
$theokcbiweekly = $okcbiweekly->ticker->last;
$biweeklyspread = $theokcbiweekly - $theokcindex;
$biweeklyspreadperc = ($biweeklyspread / $theokcindex)*100;
$biweeklyspreadperc2 = round($biweeklyspreadperc, 2);
$biweeklyspreadpa2 = (pow(($theokcbiweekly/$theokcindex), (365/$okcbdays))-1)*100;
$biweeklyspreadpa = round($biweeklyspreadpa2, 2);

	$getokcquarterly = array('symbol' => 'btc_usd', 'contract_type' => 'quarter');
	$okcquarterly = $client -> tickerFutureApi($getokcquarterly);
$theokcquarterly = $okcquarterly->ticker->last;
$quarterlyspread = $theokcquarterly - $theokcindex;

$quarterlyspreadperc = ($quarterlyspread / $theokcindex)*100;
$quarterlyspreadperc2 = round($quarterlyspreadperc, 2);


$okcqdate = mktime(0, 0, 0, 3, 25, 2016, 0);
$today = time();
$difference = $okcqdate - $today;
if ($difference < 0) { $difference = 0; }
$okcqdays = floor($difference/60/60/24);

$quarterlyspreadpa2 = (pow(($theokcquarterly/$theokcindex), (365/$okcqdays))-1)*100;
$quarterlyspreadpa = round($quarterlyspreadpa2, 2);


// bitmex stuff

$dailyjson = file_get_contents('https://www.bitmex.com/api/v1/instrument?symbol=XBT24H&count=1&start=0&reverse=false');
$dailyarray = json_decode($dailyjson, true);
$bitmexdailyprice = $dailyarray[0]['lastPrice'];
$bitmexdailymark = $dailyarray[0]['fairPrice'];
$bitmexindicative = $dailyarray[0]['indicativeSettlePrice'];
$bitmexdailyspread = $bitmexdailyprice - $bitmexindicative;
$bitmexdailyspreadperc = round(($bitmexdailyspread / $bitmexindicative)*100, 2);
$bitmexdailyspreadpa2 = (pow(($bitmexdailyprice/$bitmexindicative), (365))-1)*100;
$bitmexdailyspreadpa = round($bitmexdailyspreadpa2,2);


$weeklyjson = file_get_contents('https://www.bitmex.com/api/v1/instrument?symbol=XBT7D&count=1&start=0&reverse=false');
$weeklyarray = json_decode($weeklyjson, true);
$bitmexweeklyprice = $weeklyarray[0]['lastPrice'];
$bitmexweeklymark = $weeklyarray[0]['fairPrice'];
$bitmexwkindicative = $weeklyarray[0]['indicativeSettlePrice'];
$bitmexweeklyspread = $bitmexweeklyprice - $bitmexwkindicative;
$bitmexweeklyspreadperc = round(($bitmexweeklyspread / $bitmexindicative)*100, 2);
$bitmexweeklyspreadpa2=(pow(($bitmexweeklyprice/$bitmexindicative), (365/$okcwdays))-1)*100;
$bitmexweeklyspreadpa=round($bitmexweeklyspreadpa2,2);


$dec25json = file_get_contents('https://www.bitmex.com/api/v1/instrument?symbol=XBTZ15&count=1&start=0&reverse=false');
$dec25array = json_decode($dec25json, true);
$bitmexdec25price = $dec25array[0]['lastPrice'];
$bitmexdec25mark = $dec25array[0]['fairPrice'];
$bitmexdec25indicative = $dec25array[0]['indicativeSettlePrice'];
$bitmexdec25spread = $bitmexdec25price - $bitmexdec25indicative;
$bitmexdec25spreadperc = round(($bitmexdec25spread / $bitmexindicative)*100, 2);

$bitmex2date = mktime(0, 0, 0, 12, 25, 2015, 0);
$difference2 = $bitmex2date - $today;
if ($difference2 < 0) { $difference2 = 0; }
$bitmex2days = floor($difference2/60/60/24);
$bitmexdec25spreadpa2=(pow(($bitmexdec25price/$bitmexdec25indicative), (365/$bitmex2days))-1)*100;
$bitmexdec25spreadpa=round($bitmexdec25spreadpa2,2);


$mar25json = file_get_contents('https://www.bitmex.com/api/v1/instrument?symbol=XBTH16&count=1&start=0&reverse=false');
$mar25array = json_decode($mar25json, true);
$bitmexmar25price = $mar25array[0]['lastPrice'];
$bitmexmar25mark = $mar25array[0]['fairPrice'];
$bitmexmar25indicative = $mar25array[0]['indicativeSettlePrice'];
$bitmexmar25spread = $bitmexmar25price - $bitmexmar25indicative;
$bitmexmar25spreadperc = round(($bitmexmar25spread / $bitmexindicative)*100, 2);

$bitmexmar25spreadpa2=(pow(($bitmexmar25price/$bitmexmar25indicative), (365/$okcqdays))-1)*100;
$bitmexmar25spreadpa=round($bitmexmar25spreadpa2,2);


// CryptoFacilities stuff

$cfbpijson = file_get_contents('https://www.cryptofacilities.com/derivatives/api/cfbpi');
$cfbpiarray = json_decode($cfbpijson, true);
if (isset($cfbpiarray)) {
if ($cfbpiarray['result'] == "success") {
$cfbpi = $cfbpiarray['cf-bpi'];
} else {
$cfbpi = "0";
return;
} 
} else {
$cfbpi = "0";
return;
}

// cf contract weekly

$cfdec15json = file_get_contents('https://www.cryptofacilities.com/derivatives/api/ticker?tradeable=F-XBT:USD-Dec15-W4&unit=USD');
$cfdec15array = json_decode($cfdec15json, true);

if (isset($cfdec15array)) {
if ($cfdec15array['result'] == "success") {
$cfdec15price = $cfdec15array['last'];
$cfdec15spread = $cfdec15price - $cfbpi;
$cfdec15spreadperc = round(($cfdec15spread / $cfbpi)*100, 2);

$cfdec15spreadpa2=(pow(($cfdec15price/$cfbpi), (365/$okcwdays))-1)*100;
$cfdec15spreadpa=round($cfdec15spreadpa2,2);

} else {
$cfbpi = "0";
return;
} 
} else {
$cfbpi = "0";
return;
}



// cf contract biweekly

$cfdec15w4json = file_get_contents('https://www.cryptofacilities.com/derivatives/api/ticker?tradeable=F-XBT:USD-Jan16-W1&unit=USD');
$cfdec15w4array = json_decode($cfdec15w4json, true);

if (isset($cfdec15w4array)) {
if ($cfdec15w4array['result'] == "success") {
$cfdec15w4price = $cfdec15w4array['last'];
$cfdec15w4spread = $cfdec15w4price - $cfbpi;
$cfdec15w4spreadperc = round(($cfdec15w4spread / $cfbpi)*100, 2);

$cfdec15w4spreadpa2=(pow(($cfdec15w4price/$cfbpi), (365/($okcwdays+7)))-1)*100;
$cfdec15w4spreadpa=round($cfdec15w4spreadpa2,2);

} else {
$cfbpi = "0";
return;
} 
} else {
$cfbpi = "0";
return;
}


// cf triweekly


$cfjan16w1json = file_get_contents('https://www.cryptofacilities.com/derivatives/api/ticker?tradeable=F-XBT:USD-Jan16-W2&unit=USD');
$cfjan16w1array = json_decode($cfjan16w1json, true);

if (isset($cfjan16w1array)) {
if ($cfjan16w1array['result'] == "success") {

$cfjan16w1price = $cfjan16w1array['last'];
$cfjan16w1spread = $cfjan16w1price - $cfbpi;
$cfjan16w1spreadperc = round(($cfjan16w1spread / $cfbpi)*100, 2);

$cfjan16w1spreadpa2=(pow(($cfjan16w1price/$cfbpi), (365/($okcwdays+14)))-1)*100;
$cfjan16w1spreadpa=round($cfjan16w1spreadpa2,2);

} else {
$cfbpi = "0";
return;
} 
} else {
$cfbpi = "0";
return;
}





// cf quadraweekly contract


$cfjan16w2json = file_get_contents('https://www.cryptofacilities.com/derivatives/api/ticker?tradeable=F-XBT:USD-Jan16-W3&unit=USD');
$cfjan16w2array = json_decode($cfjan16w2json, true);

if (isset($cfjan16w2array)) {
if ($cfjan16w2array['result'] == "success") {

$cfjan16w2price = $cfjan16w2array['last'];
$cfjan16w2spread = $cfjan16w2price - $cfbpi;
$cfjan16w2spreadperc = round(($cfjan16w2spread / $cfbpi)*100, 2);

$cfjan16w2spreadpa2=(pow(($cfjan16w2price/$cfbpi), (365/($okcwdays+21)))-1)*100;
$cfjan16w2spreadpa=round($cfjan16w2spreadpa2,2);
} else {
$cfbpi = "0";
return;
} 
} else {
$cfbpi = "0";
return;
}


// quarterly 

$cfmar16json = file_get_contents('https://www.cryptofacilities.com/derivatives/api/ticker?tradeable=F-XBT:USD-Mar16&unit=USD');
$cfmar16array = json_decode($cfmar16json, true);

if (isset($cfmar16array)) {
if ($cfmar16array['result'] == "success") {

$cfmar16price = $cfmar16array['last'];
$cfmar16spread = $cfmar16price - $cfbpi;
$cfmar16spreadperc = round(($cfmar16spread / $cfbpi)*100, 2);

$cfmar16spreadpa2=(pow(($cfmar16price/$cfbpi), (365/$okcqdays))-1)*100;
$cfmar16spreadpa=round($cfmar16spreadpa2,2);

} else {
$cfbpi = "0";
return;
} 
} else {
$cfbpi = "0";
return;
}

// cf semiannual contracts 

$cfjun16json = file_get_contents('https://www.cryptofacilities.com/derivatives/api/ticker?tradeable=F-XBT:USD-Jun16&unit=USD');
$cfjun16array = json_decode($cfjun16json, true);

if (isset($cfjun16array)) {
if ($cfjun16array['result'] == "success") {

$cfjun16price = $cfjun16array['last'];
$cfjun16spread = $cfjun16price - $cfbpi;
$cfjun16spreadperc = round(($cfjun16spread / $cfbpi)*100, 2);

$cfsemidate = mktime(0, 0, 0, 6, 24, 2016, 0);
$difference3 = $cfsemidate - $today;
if ($difference3 < 0) { $difference3 = 0; }
$cfsemidays = floor($difference3/60/60/24);
$cfjun16spreadpa2=(pow(($cfjun16price/$cfbpi), (365/$cfsemidays))-1)*100;
$cfjun16spreadpa=round($cfjun16spreadpa2,2);


} else {
$cfbpi = "0";
return;
} 
} else {
$cfbpi = "0";
return;
}



echo "<br>";
echo "<img src='okcplot.php'>";
echo "<br>";
echo "<a href='http://bit.ly/1SL6WKO'><h1>OKCoin</h1></a>\n";
echo "Index: $".$theokcindex;
echo "<style type=\"text/css\">\n";
echo ".tg  {border-collapse:collapse;border-spacing:0;}\n";
echo ".tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}\n";
echo ".tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}\n";
echo ".tg .tg-c9cr{font-style:italic}\n";
echo ".tg .tg-e3zv{font-weight:bold}\n";
echo ".tg .tg-yw4l{vertical-align:top}\n";
echo "</style>\n";
echo "<table class=\"tg\">\n";
echo "  <tr>\n";
echo "    <th class=\"tg-c9cr\">Type</th>\n";
echo "    <th class=\"tg-yw4l\">Price</th>\n";
echo "    <th class=\"tg-c9cr\">Delta (Price - Index) ($)</th>\n";
echo "    <th class=\"tg-c9cr\">Delta (Price - Index) (%)</th>\n";
echo "    <th class=\"tg-c9cr\">Annualized Delta (%)</th>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td class=\"tg-e3zv\">Weekly</td>\n";
echo "    <td class=\"tg-yw4l\">$".$theokcweekly."</td>\n";
echo "    <td class=\"tg-031e\">$".$weeklyspread."</td>\n";
echo "    <td class=\"tg-031e\">".$weeklyspreadperc2."%</td>\n";
echo "    <td class=\"tg-031e\">".$weeklyspreadpa."%</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td class=\"tg-e3zv\">Biweekly</td>\n";
echo "    <td class=\"tg-yw4l\">$".$theokcbiweekly."</td>\n";
echo "    <td class=\"tg-031e\">$".$biweeklyspread."</td>\n";
echo "    <td class=\"tg-031e\">".$biweeklyspreadperc2."%</td>\n";
echo "    <td class=\"tg-031e\">".$biweeklyspreadpa."%</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td class=\"tg-e3zv\">Quarterly (Mar25-16)</td>\n";
echo "    <td class=\"tg-yw4l\">$".$theokcquarterly."</td>\n";
echo "    <td class=\"tg-031e\">$".$quarterlyspread."</td>\n";
echo "    <td class=\"tg-031e\">".$quarterlyspreadperc2."%</td>\n";
echo "    <td class=\"tg-031e\">".$quarterlyspreadpa."%</td>\n";
echo "  </tr>\n";

echo "</table>";
echo "<br>";
echo "Buy and store Gold easily with bitcoin or credit card:  <a href='http://bit.ly/1PQMJUD'>Get 0.25g gold FREE using this link at BitGold.</a>\n";
echo "<br>";
echo "<img src='bitmexplot.php'>";
echo "<br>";
echo "<a href='http://bit.ly/1OUUsm1'><h1>BitMEX</h1></a>\n";
echo "Index: $".$bitmexindicative;
echo "<style type=\"text/css\">\n";
echo ".tg  {border-collapse:collapse;border-spacing:0;}\n";
echo ".tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}\n";
echo ".tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}\n";
echo ".tg .tg-c9cr{font-style:italic}\n";
echo ".tg .tg-e3zv{font-weight:bold}\n";
echo ".tg .tg-yw4l{vertical-align:top}\n";
echo "</style>\n";
echo "<table class=\"tg\">\n";
echo "  <tr>\n";
echo "    <th class=\"tg-c9cr\">Type</th>\n";
echo "    <th class=\"tg-yw4l\">Price</th>\n";
echo "    <th class=\"tg-c9cr\">Delta (Price - Index) ($)</th>\n";
echo "    <th class=\"tg-c9cr\">Delta (Price - Index) (%)</th>\n";
echo "    <th class=\"tg-c9cr\">Annualized Delta (%)</th>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td class=\"tg-e3zv\">Daily</td>\n";
echo "    <td class=\"tg-yw4l\">$".$bitmexdailyprice."</td>\n";
echo "    <td class=\"tg-031e\">$".$bitmexdailyspread."</td>\n";
echo "    <td class=\"tg-031e\">".$bitmexdailyspreadperc."%</td>\n";
echo "    <td class=\"tg-031e\">".$bitmexdailyspreadpa."%</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td class=\"tg-e3zv\">Weekly</td>\n";
echo "    <td class=\"tg-yw4l\">$".$bitmexweeklyprice."</td>\n";
echo "    <td class=\"tg-031e\">$".$bitmexweeklyspread."</td>\n";
echo "    <td class=\"tg-031e\">".$bitmexweeklyspreadperc."%</td>\n";
echo "    <td class=\"tg-031e\">".$bitmexweeklyspreadpa."%</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td class=\"tg-e3zv\">Dec25-15</td>\n";
echo "    <td class=\"tg-yw4l\">$".$bitmexdec25price."</td>\n";
echo "    <td class=\"tg-031e\">$".$bitmexdec25spread."</td>\n";
echo "    <td class=\"tg-031e\">".$bitmexdec25spreadperc."%</td>\n";
echo "    <td class=\"tg-031e\">".$bitmexdec25spreadpa."%</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td class=\"tg-e3zv\">Mar25-16</td>\n";
echo "    <td class=\"tg-yw4l\">$".$bitmexmar25price."</td>\n";
echo "    <td class=\"tg-031e\">$".$bitmexmar25spread."</td>\n";
echo "    <td class=\"tg-031e\">".$bitmexmar25spreadperc."%</td>\n";
echo "    <td class=\"tg-031e\">".$bitmexmar25spreadpa."%</td>\n";
echo "  </tr>\n";
echo "</table>";

echo "<br>";
echo "Buy and store Gold easily with bitcoin or credit card:  <a href='http://bit.ly/1PQMJUD'>Get 0.25g gold FREE using this link at BitGold.</a>\n";

echo "<br>";
echo "<img src='cryptoplot.php'>";
echo "<br>";
echo "<a href='http://bit.ly/1l9bAaE'><h1>CryptoFacilities</h1></a>\n";
echo "Index: $".$cfbpi;
echo "<style type=\"text/css\">\n";
echo ".tg  {border-collapse:collapse;border-spacing:0;}\n";
echo ".tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}\n";
echo ".tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}\n";
echo ".tg .tg-c9cr{font-style:italic}\n";
echo ".tg .tg-e3zv{font-weight:bold}\n";
echo ".tg .tg-yw4l{vertical-align:top}\n";
echo "</style>\n";
echo "<table class=\"tg\">\n";
echo "  <tr>\n";
echo "    <th class=\"tg-c9cr\">Type</th>\n";
echo "    <th class=\"tg-yw4l\">Price</th>\n";
echo "    <th class=\"tg-c9cr\">Delta (Price - Index) ($)</th>\n";
echo "    <th class=\"tg-c9cr\">Delta (Price - Index) (%)</th>\n";
echo "    <th class=\"tg-c9cr\">Annualized Delta (%)</th>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td class=\"tg-e3zv\">Weekly</td>\n";
echo "    <td class=\"tg-yw4l\">$".$cfdec15price."</td>\n";
echo "    <td class=\"tg-031e\">$".$cfdec15spread."</td>\n";
echo "    <td class=\"tg-031e\">".$cfdec15spreadperc."%</td>\n";
echo "    <td class=\"tg-031e\">".$cfdec15spreadpa."%</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td class=\"tg-e3zv\">Biweekly</td>\n";
echo "    <td class=\"tg-yw4l\">$".$cfdec15w4price."</td>\n";
echo "    <td class=\"tg-031e\">$".$cfdec15w4spread."</td>\n";
echo "    <td class=\"tg-031e\">".$cfdec15w4spreadperc."%</td>\n";
echo "    <td class=\"tg-031e\">".$cfdec15w4spreadpa."%</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td class=\"tg-e3zv\">Triweekly</td>\n";
echo "    <td class=\"tg-yw4l\">$".$cfjan16w1price."</td>\n";
echo "    <td class=\"tg-031e\">$".$cfjan16w1spread."</td>\n";
echo "    <td class=\"tg-031e\">".$cfjan16w1spreadperc."%</td>\n";
echo "    <td class=\"tg-031e\">".$cfjan16w1spreadpa."%</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td class=\"tg-e3zv\">Quadraweekly</td>\n";
echo "    <td class=\"tg-yw4l\">$".$cfjan16w2price."</td>\n";
echo "    <td class=\"tg-031e\">$".$cfjan16w2spread."</td>\n";
echo "    <td class=\"tg-031e\">".$cfjan16w2spreadperc."%</td>\n";
echo "    <td class=\"tg-031e\">".$cfjan16w2spreadpa."%</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td class=\"tg-e3zv\">Mar25-16</td>\n";
echo "    <td class=\"tg-yw4l\">$".$cfmar16price."</td>\n";
echo "    <td class=\"tg-031e\">$".$cfmar16spread."</td>\n";
echo "    <td class=\"tg-031e\">".$cfmar16spreadperc."%</td>\n";
echo "    <td class=\"tg-031e\">".$cfmar16spreadpa."%</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td class=\"tg-e3zv\">Jun24-16</td>\n";
echo "    <td class=\"tg-yw4l\">$".$cfjun16price."</td>\n";
echo "    <td class=\"tg-031e\">$".$cfjun16spread."</td>\n";
echo "    <td class=\"tg-031e\">".$cfjun16spreadperc."%</td>\n";
echo "    <td class=\"tg-031e\">".$cfjun16spreadpa."%</td>\n";
echo "  </tr>\n";
echo "</table>";

?>


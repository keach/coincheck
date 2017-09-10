<?php
require_once "./define.php";

//
// 各API呼び出し
//

function get_rate($pair = "btc_jpy"){
	// 販売レートの取得API

	// HTTP REQUEST
	// GET /api/rate/[pair]

	// PARAMETERS
	// *pair 通貨ペア ( "btc_jpy" "eth_jpy" "etc_jpy" "dao_jpy" "lsk_jpy" "fct_jpy" "xmr_jpy" "rep_jpy" "xrp_jpy" "zec_jpy" "xem_jpy" "ltc_jpy" "dash_jpy" "bch_jpy" "eth_btc" "etc_btc" "lsk_btc" "fct_btc" "xmr_btc" "rep_btc" "xrp_btc" "zec_btc" "xem_btc" "ltc_btc" "dash_btc" "bch_btc" )

	$contents = file_get_contents(
		API_REQ_URL . API_RATE . "/" . $pair, 
		false, 
		stream_context_create(get_public_header_options())
	);

	return $contents;
}

function get_order_rate_by_amount($order_type = "sell", $pair = "btc_jpy", $amount = 1){
	// 売買レートの取得API

	// HTTP REQUEST
	// GET /api/exchange/orders/rate

	// PARAMETERS
	// *order_type 注文のタイプ（"sell" or "buy"）
	// *pair 取引ペア。現在は "btc_jpy" のみです。
	// amount 注文での量。（例）0.1
	// price 注文での金額。（例）28000

	$data = array(
		"order_type" => $order_type,
		"pair" => $pair,
		"amount" => $amount,
	);

	$query = http_build_query($data);

	$contents = file_get_contents(
		API_REQ_URL . API_ORDER_RATE . "?" . $query, 
		false, 
		stream_context_create(get_public_header_options())
	);

	return $contents;
}

function get_balance(){
	// 残高の取得API

	// HTTP REQUEST
	// GET /api/accounts/balance

	$contents = file_get_contents(
		API_REQ_URL . API_BALANCE, 
		false, 
		stream_context_create(get_private_header_options(API_REQ_URL . API_BALANCE))
	);

	return $contents;
}

function get_transactions(){
	// 取引履歴の取得API

	// HTTP REQUEST
	// GET /api/exchange/orders/transactions

	$contents = file_get_contents(
		API_REQ_URL . API_TRANSACTIONS, 
		false, 
		stream_context_create(get_private_header_options(API_REQ_URL . API_TRANSACTIONS))
	);

	return $contents;
}

function get_transactions_p($limit = 25, $order = "desc", $starting_after = 1, $ending_before = NULL){
	// 取引履歴の取得API(ページネーション版)

	// HTTP REQUEST
	// GET /api/exchange/orders/transactions_pagination

	// PARAMETERS
	// limit 1ページあたりの取得件数を指定できます。
	// order "desc", "asc" を指定できます。
	// starting_after IDを指定すると絞り込みの開始位置を設定できます。
	// ending_before IDを指定すると絞り込みの終了位置を設定できます。

	$data = array(
		"limit" => $limit,
		"order" => $order,
		"starting_after" => $starting_after,
		"ending_before" => $ending_before,
	);

	$query = http_build_query($data);

	$contents = file_get_contents(
		API_REQ_URL . API_TRANSACTIONS_P . "?" . $query, 
		false, 
		stream_context_create(get_private_header_options(API_REQ_URL . API_TRANSACTIONS_P . "?" . $query, $data))
	);

	return $contents;
}

//
// headerのoption生成
//

function get_public_header_options(){
	$options = array(
		"http" => array(
			"method" => "GET",
		),
	);

	return $options;
}

function get_private_header_options($request_url, $request_data = array()){
	$nonce = time();

	$message  = $nonce;
	$message .= $request_url;
	$message .= http_build_query($request_data);

	$signature = hash_hmac("sha256", $message, API_SECRET_KEY);

	$header = array(
		"Content-Type: application/x-www-form-urlencoded",
		"ACCESS-KEY: " . API_ACCESS_KEY,
		"ACCESS-NONCE: " . $nonce,
		"ACCESS-SIGNATURE: " . $signature,
	);

	$options = array(
		"http" => array(
			"method" => "GET",
			"header" => implode("\r\n", $header),
			"content" => http_build_query($request_data),
		),
	);

	return $options;
}

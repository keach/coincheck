<?php

require_once "./api_func.php";

date_default_timezone_set("Asia/Tokyo");
mb_language("ja");
mb_internal_encoding("utf-8");

define("LOG_FILE", "rate_btc_jpy.log");

define("ALERT_DIFF", 1.0);
define("MAIL_FROM", "hoge@example.com");	// CHANGE ME
define("MAIL_TO", "hoge@example.com");	// CHANGE ME
define("MAIL_SUBJECT", "BTC/JPYの価格変動が " . ALERT_DIFF . " %を超えました");



// -----------------
// main
//

// 今のレート(BTC/JPY)を取得
$rate_json = get_rate();
$rate_obj = json_decode($rate_json);
$rate = $rate_obj->rate;


// ファイルが存在するか確認
$exsist = file_exists(LOG_FILE);

if($exsist){
// ファイルが存在する場合
	// ログファイルの中身を取得
	$log_rate = file_get_contents(LOG_FILE);

	// 通知対象かチェック
	rate_alert($rate, $log_rate);
}
else{
// ファイルが存在しない場合
	// ファイルを作成
	touch(LOG_FILE);
}

// 今のレートをログファイルに書き込み
file_put_contents(LOG_FILE, $rate);


// -----------------
// functions
//
function rate_alert($now_rate, $before_rate){
	// 上昇・下落率を計算
	$rate_diff = round(abs(($now_rate - $before_rate) / $before_rate) * 100, 5);

	// 上昇・下落率がしきい値(ALERT_DIFF %)以上だったら、メールで通知
	if($rate_diff >= ALERT_DIFF){
		
		$message  = "現在の日時: " . date("Y/m/d H:i:s") . "\n";
		$message .= "現在のレート: " . $now_rate . "\n";
		$message .= "前回のレート: " . $before_rate . "\n";
		$message .= "変動率: " . $rate_diff . "%\n";

		$message = mb_convert_encoding($message, "iso-2022-jp", "utf-8");

		$subject  = ($now_rate - $before_rate > 0) ? "【上昇】" : "【下落】";
		$subject .= MAIL_SUBJECT;

		$add_headers  = "Mime-Version: 1.0\r\n";
		$add_headers .= "Content-Type: text/plain; charset=iso-2022-jp\r\n";
		$add_headers .= "From:" . MAIL_FROM;


		mb_send_mail(MAIL_TO, $subject, $message, $add_headers, "-f " . MAIL_FROM);

	}
}
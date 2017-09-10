<?php 
// 
// 定数定義
// 

// APIアクセスキー
define("API_ACCESS_KEY", "xxxxxxxxxxxxxxxxx");	// CHANGE ME
// APIシークレットアクセスキー
define("API_SECRET_KEY", "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx");	// CHANGE ME

// APIリクエスト先
define("API_REQ_URL", "https://coincheck.com");

// APIエンドポイント(販売レートの取得) [Public API]
define("API_RATE", "/api/rate/");
// APIエンドポイント(売買レートの取得) [Public API]
define("API_ORDER_RATE", "/api/exchange/orders/rate");

// APIエンドポイント(残高の取得) [Private API]
define("API_BALANCE", "/api/accounts/balance");
// APIエンドポイント(取引履歴の取得) [Private API]
define("API_TRANSACTIONS", "/api/exchange/orders/transactions");
// APIエンドポイント(取引履歴の取得-ページネーション版) [Private API]
define("API_TRANSACTIONS_P", "/api/exchange/orders/transactions_pagination");

# coincheck API の練習


## 1) alert.php - BTC/JPY の変動幅がしきい値を超えたらメールでお知らせ

coincheck API で取得した BTC/JPY のレートが、前回取得時と比べて、指定した%以上の上昇・下落をした場合に、指定したアドレスにメールで通知します

### 設定
alert.php の中の定数を変える

* ALERT_DIFF : 何%以上変動したらお知らせするか
* MAIL_FROM : メールの送信元
* MAIL_TO : メールの送信先

### 使い方

``` $ php alert.php
```

で、うまく動いたら、cron で何分おきに動かすか、設定


## その他やりたいこと

* push 通知、LINE での通知
* 自分のポートフォリオと今のレートから収支を計算
* stream API での常時取得
* Webでの管理できるようにする(ポートフォリオ、チャートの表示)
* ・・・
* 売買ルールを設定して自動取引

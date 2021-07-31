<?php
  // ライブラリ読み込み
  require_once __DIR__ . '/vendor/autoload.php';
  require_once 'functions.php';

  // POST内容表示
  $inputString = file_get_contents('php://input');
  error_log($inputString);

  $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv('CHANNEL_ACCESS_TOKEN'));

  $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => getenv('CHANNEL_SECRET')]);

  $signature = $_SERVER['HTTP_' . \LINE\LINEBot\Constant\HTTPHeader::LINE_SIGNATURE];

  $events = $bot->parseEventRequest(file_get_contents('php://input'), $signature);

  foreach($events as $event) {
    if ($event instanceof \LINE\LINEBot\Event\FollowEvent) {
      replyTextMessage($bot, $event->getReplyToken(), "Follow受信\nフォローありがとうございます");
      continue;
    } elseif ($event instanceof \LINE\LINEBot\Event\PostbackEvent) {
      replyTextMessage($bot, $event->getReplyToken(), 'Postback受信「' . $event->getPostbackData() . '」');
      continue;
    }
    // テキストを返信
    // replyTextMessage($bot, $event->getReplyToken(), 'TextMessage');

    // 画像を返信
    // replyImageMessage($bot, $event->getReplyToken(),
    //                       'https://' . $_SERVER['HTTP_HOST'] . '/imgs/original.jpg',
    //                       'https://' . $_SERVER['HTTP_HOST'] . '/imgs/preview.jpg');

    // 位置情報を返信
    // replyLocationMessage($bot, $event->getReplyToken(), 'CirKit ロゴス',
    //                       '石川県野々市市 金沢工業大学 扇が丘キャンパス',
    //                       36.5308217, 136.6270967);

    // スタンプを返信
    // replyStickerMessage($bot, $event->getReplyToken(), 11538, 51626498);

    // 動画を返信
    // replyVideoMessage($bot, $event->getReplyToken(),
    //                       'https://' . $_SERVER['HTTP_HOST'] . '/videos/sample.mp4',
    //                       'https://' . $_SERVER['HTTP_HOST'] . '/videos/sample.jpg');

    // オーディオを返信
    // replyAudioMessage($bot, $event->getReplyToken(),
    //                       'https://' . $_SERVER['HTTP_HOST'] . '/audios/sample2.m4a', 244000);

    // 複数のメッセージを返信
    // replyMultiMessage($bot, $event->getReplyToken(),
    //     new \LINE\LINEBot\MessageBuilder\TextMessageBuilder('返信テスト'),
    //     new \LINE\LINEBot\MessageBuilder\ImageMessageBuilder('https://' . $_SERVER['HTTP_HOST'] . '/imgs/original.jpg',
    //                                                           'https://' . $_SERVER['HTTP_HOST'] . '/imgs/preview.jpg'),
    //     new \LINE\LINEBot\MessageBuilder\StickerMessageBuilder(11538, 51626498));

    // Buttonテンプレートメッセージを返信
    // replyButtonTemplate(
    //   $bot,
    //   $event->getReplyToken(),
    //   '天気のお知らせ - 今日の天気予報',
    //   'https://' . $_SERVER['HTTP_HOST'] . '/imgs/template.jpg',
    //   '天気の知らせ',
    //   '今日の天気予報は晴れ',
    //   new LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder ('明日の天気', 'tomorrow'),
    //   new LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder('週末の天気', 'weekend'),
    //   new LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder('webで見る', 'https://google.jp')
    // );

    // Confirmテンプレートを返信
    replyConfirmTemplate(
      $bot,
      $event->getReplyToken(),
      'webで詳しく見ますか?',
      'webで詳しく見ますか?',
      new LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder (
        '見る', 'http://google.jp'),
      new LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder (
        '見ない', 'ignore')
    );
  }
?>
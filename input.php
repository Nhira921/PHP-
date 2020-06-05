<?php
session_start();
//csrf対策①セッション使いますよという宣言

require 'validation.php';
//バリデーションファイルをimport

header('X-FRAME-OPTIONS: DENY');
//clickjacking対策

function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
//XSS対策




// echo '<pre>';
// var_dump($_POST);
// echo '<pre>';
// echo $_GET['name'];
//スーパーグローバル変数
//全9種類ある
//連想配列になっている
//[]の中にはinputタグのキー（name="キー"）を入力
//値がどうなっているかを確認できる


$pageFlag = 0;
//1ページで入力→確認→完了まで表示する場合
//pageFlagという変数を使って遷移させる

$error = validation($_POST);
//バリデーションのエラー表示を受け取る変数

if(!empty($_POST['btn_confirm']) && empty($error)){
  $pageFlag = 1;
}
//確認ボタンが空じゃない且つ、エラーメッセージが空だったらページを変える


if(!empty($_POST['btn_submit'])){
  $pageFlag = 2;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <title>Document</title>
</head>
<body>

<style>
.form-group{
  width:90%;
  max-width:500px;
  margin:0 auto;
}

</style>

<?php if($pageFlag === 0) : ?>
<!-- 入力画面 -->

<?php
if(!isset($_SESSION['csrfToken'])){
  $csrfToken = bin2hex(random_bytes(32));
  $_SESSION['csrfToken'] = $csrfToken;
}
$token = $_SESSION['csrfToken'];
?>
<!--csrf対策② 合言葉の設定 -->

<?php if(!empty($_POST['btn_confirm']) && !empty($error)) :?>
<!-- 確認ボタンが空ではなく、且つエラーが空ではなかったら -->
<ul>
<?php foreach ($error as $value) :?>
<!-- $errorは連想配列なのでforeachで分解していく -->
<li><?php echo $value ; ?></li>
<!-- 分解したエラー文をlistの中に表示していく -->
<?php endforeach ;?>
</ul>
<?php endif ;?>


  <form method="POST" action="input.php">
    <!-- method="GET"または"POST"を記入する -->
    <!-- action="処理をするファイル" -->
    <div class="form-group">
    <h1>お問い合わせ</h1>
      <label for="name">名前</label>
      <input type="text" name="your_name" id="name" class="form-control" value="<?php echo h($_POST['your_name']) ; ?>">

      <label for="email">メールアドレス</label>
      <input type="email" name="email" id="email" class="form-control" value="<?php echo h($_POST['email']) ; ?>">

      <label for="url">ホームページ</label>
      <input type="url" name="url" id="url" class="form-control" value="<?php echo h($_POST['url']) ; ?>">

      <br>
      <p>性別</p>
      <!-- <label for="women">女</label> -->
      <input type="radio" name="gender" id="women" class="form-check-inline" value="0">女
      <!-- <label for="men">男</label> -->
      <input type="radio" name="gender" id="men" class="form-check-inline" value="1">男
      <br>

      <br>
      <label for="age">年齢</label>
      <select name="age" id="age" class="form-control">
      <option value="">選択してください</option>
      <option value="1">10代</option>
      <option value="2">20代</option>
      <option value="3">30代</option>
      <option value="4">40代</option>
      <option value="5">50代</option>
      <option value="6">60代</option>
      </select>
      <br>

      <label for="message">お問い合わせ内容</label>
      <textarea name="message" id="message" class="form-control" value="<?php echo h($_POST['message']) ; ?>"></textarea>
      <br>

      <input type="checkbox" name="caution" id="caution" class="form-check-inline" value="1">注意事項にチェックする
      <br>



      <br>
      <input type="submit" name="btn_confirm" value="確認" class="btn btn-primary mb-2">
      <input type="hidden" name="csrf" value="<?php echo $token; ?>">
      <!-- csrf対策③ inputタグに合言葉を設定しておく -->

    </div>
  </form>
<?php endif; ?>

<?php if($pageFlag === 1) : ?>
<!-- 確認画面 -->

<?php if($_POST['csrf'] === $_SESSION['csrfToken']) : ?>
  <!-- csrf対策④ 入力できたcsrfと$_SESSIONの情報があってるか確認する-->

  <form method="POST" action="input.php" class="form-group">
  <!-- method="GET"または"POST"を記入する -->
  <!-- action="処理をするファイル" -->

  <br>
  <label for="name">名前:</label>
  <?php echo h($_POST['your_name']) ; ?>
  <br>

  <br>
  <label for="email">メールアドレス:</label>
  <?php echo h($_POST['email']) ; ?>
  <br>

  <br>
  <label for="url">ホームページ:</label>
  <?php echo h($_POST['url']) ; ?>
  <br>

  <br>
  <span>性別:</span>
  <?php
  if($_POST['gender'] === '0'){echo '女性';}
  if($_POST['gender'] === '1'){echo '男性';}
  ?>
  <br>

  <br>
  <label for="age">年齢:</label>
  <?php
  if($_POST['age'] === '1'){echo '10代';}
  elseif($_POST['age'] === '2'){echo '20代';}
  elseif($_POST['age'] === '3'){echo '30代';}
  elseif($_POST['age'] === '4'){echo '40代';}
  elseif($_POST['age'] === '5'){echo '50代';}
  elseif($_POST['age'] === '6'){echo '60代';}
  ?>
  <br>

  <br>
  <label for="message">お問い合わせ内容:</label>
  <?php echo h($_POST['message']) ; ?>

  <br>
  <input type="submit" name="btn_submit" value="送信" class="btn btn-primary mb-2">
  <input type="submit" name="back" value="戻る" class="btn btn-primary mb-2">

  <input type="hidden" name="your_name" value="<?php echo h($_POST['your_name']) ; ?>">
  <input type="hidden" name="email" value="<?php echo h($_POST['email']) ; ?>">
  <input type="hidden" name="url" value="<?php echo h($_POST['url']) ; ?>">
  <input type="hidden" name="gender" value="<?php echo h($_POST['gender']) ; ?>">
  <input type="hidden" name="age" value="<?php echo h($_POST['age']) ; ?>">
  <input type="hidden" name="message" value="<?php echo h($_POST['message']) ; ?>">

  <input type="hidden" name="csrf" value="<?php echo h($_POST['csrf']) ; ?>">
  <!-- csrf対策⑤ページが入力→確認に変わるタイミングでcsrfの値が消えてしまうのでtype="hidden"のinputで値を保持させておく -->

  <!-- 消えては困る情報を面には出さないが保持しておく行 -->



  </form>

<?php endif; ?>
<?php endif; ?>

<?php if($pageFlag === 2) : ?>
<!-- 完了画面 -->
<?php if($_POST['csrf'] === $_SESSION['csrfToken']) : ?>
<!-- csrf対策⑥完了画面でも合言葉があってるか確認する -->

送信が完了しました。

<?php unset($_SESSION['csrfToken']); ?>
<!-- csrf対策⑦合言葉を削除する -->

<?php endif; ?>
<?php endif; ?>

</body>
</html>

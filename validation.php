<?

function validation($data){

  $error = [];

  //名前
  if(empty($data['your_name']) || 20 < mb_strlen($data['your_name'])){
    $error[] ='名前は20字以内で入力してください。';
  }
  //未入力、且つ、20字以上の場合はエラー表示

  //メールアドレス
  if(empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
    $error[] ='メールアドレスは正しい形式で入力してください。';
  }
  //未入力、且つ、filter_varのメール形式に引っかかったらエラー表示

  //url
  if(!empty($data['url'])){
    if(!filter_var($data['url'], FILTER_VALIDATE_URL)){
      $error[] ='ホームページは正しい形式で入力してください。';
    }
  }
  //もし入力があれば
  //filter_varのurl形式に引っかかったらエラー表示

  //性別
  if(!isset($data['gender'])){
    $error[] = '性別は必ず入力してください。';
  }
  //issetでラジオボタンの入力チェック

  //年齢
  if(empty($data['age']) || 6 < $data['age']){
    $error[] ='年齢を入力してください。';
  }
  //未選択、且つ、6より大きい数字の場合はエラー表示

  //お問い合わせ内容の文字数チェック
  if(empty($data['message']) || 200 < mb_strlen($data['message'])){
    $error[] ='お問い合わせ内容は200字以内で入力してください。';
  }
  //未入力、且つ、200文字以上の場合はエラー表示

  //注意事項
  if($data['caution'] !== '1'){
    $error[] ='注意事項をご確認ください。';
  }
  //チェックがついてなかったらエラー表示


  return $error;
}

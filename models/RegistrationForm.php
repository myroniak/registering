<?php
namespace app\models;

use Yii;
use yii\base\Model;



 class RegistrationForm extends Model
 {
	 public $login;
	 public $pass;
	 public $conf_pass;
	 public $name;
	 public $surname;
	 public $email;
	 public $verifyCode;
	 
	 public function rules()
	 {
		 return
		 [
		 [['login', 'pass', 'conf_pass', 'name', 'surname', 'email'], 'required', 'message' => 'Не залишайте поле пустим'],
		 ['verifyCode', 'captcha', 'message' => 'Введіть текст з картинки'],
		 ['email','email', 'message' => 'Не коректний емейл адрес'],
		 
		 
		 ];
	 }
	 
	 public function attributeLabels()
{
    return [
        'login' => 'Логін*',
        'pass' => 'Пароль*',
        'conf_pass' => 'Підтвердити пароль*',
        'name' => "Ім'я*",
        'surname' => 'Прізвище*',
        'email' => 'E-mail*',
        'verifyCode' => 'Перевірка*',
    ];
}
 }
?>
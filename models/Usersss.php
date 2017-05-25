<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Usersss extends ActiveRecord
{
	  public static function getDb() {
       return Yii::$app->get('db2'); // second database
   }
}



?>
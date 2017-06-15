<?php
namespace backend\models;

use yii\base\Model;
use yii\db\ActiveQuery;

class GoodsSearchForm extends Model
{
    //定义字段属性
    public $name;
    public $sn;
    public $minPrice;
    public $maxPrice;

    //定义规则
    public function rules()
    {
        return [
           [['name','sn'],'string','max'=>50],
           [['minPrice','maxPrice'],'double'],
        ];
    }


    public function search(ActiveQuery $query){
        //接受表单传输过来的数据
        $this->load(\Yii::$app->request->get());
        $query->andFilterWhere(['like','name',$this->name]);
        $query->andFilterWhere(['like','sn',$this->sn]);
        $query->andFilterWhere(['>=','market_price',$this->minPrice]);
        $query->andFilterWhere(['<=','market_price',$this->maxPrice]);
    }


}


?>
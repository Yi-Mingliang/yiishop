<?php
use yii\helpers\Html;
?>


<?php
foreach ($goods_categories as $goods_category){ ?>
    <div class="cat">
        <h3><?=Html::a("$goods_category->name",['goods/index','id'=>$goods_category->id]);?> <b></b></h3>
        <div class="cat_detail">
            <?php
            if($models=$goods_category->getChildren($goods_category->id)){
            foreach ($models as $model){
            ?>

            <dl class="dl_1st">
                <dt><?=Html::a("$model->name",[''])?></dt>
                <?php
                if($children =$model->getChildren($model->id)){
                    foreach ($children as $child){
                        ?>
                        <dd><?=Html::a("$child->name",['goods-detail/detail','goods_category_id'=>$child->id])?></dd>
                        <?php
                    }
                }
                }
                }
                ?>
            </dl>
        </div>
    </div>
    <?php
}
?>

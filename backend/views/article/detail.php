<h2><?=$article->name?></h2>
<div>
    <span>创建时间：<?=date('Y-m-d H:i:s',$article->create_time)?></span>
    <span>所属分类：<?=$article->articleCategory->name?></span>
</div>
<?=$detail->content?>
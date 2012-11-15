<div id="dataset_quickstart">
<h3>Template Quickstart</h3>

<p>When you store data, the fields used are generated from a <em>Template</em>. To help you get started quickly, please select from these common data templates that many developers use.</p>


<style>
.default_box {
	display: none;
}	

</style>
<div id="default_box">
<div>


<div class="default_header"><h4>COMMON</h4></div>
<div class="default_box" style="display: block;">
<ul>
<?php
//$GLOBALS['debug']->printr($default_datasets);
foreach($default_datasets as $cat => $v){
	foreach($v as $subcat => $sets){
		foreach($sets as $ds){
			if(!$ds['common']) continue;
		
?>
<li id="default_<?php echo $ds['id']?>"><?php echo $ds['name']?></li>
<?php
		}		
	}	
	
}
?>

</ul>
</div>

</div>
<?php
foreach($default_datasets as $k => $v){
if($k == 'empty') continue;
?>
<div>
<div class="default_header"><h4><?php echo strtoupper($k)?></h4></div>
<div class="default_box">
<?php if(isset($v['empty'])){ ?>
<ul>
	<?php foreach($v['empty'] as $key => $value){ ?>
	<li id="default_<?php echo $value['id']?>"><?php echo $value['name']?></li>	
	<?php } ?>
</ul>
<?php } else { ?>
<?php foreach($v as $key => $value){ ?>
<h4><?php echo $key?></h4>
	<ul>
		<?php foreach($value as $nk => $nv){ ?>
		<li id="default_<?php echo $nv['id']?>"><?php echo $nv['name']?></li>	
		<?php } ?>
	</ul>	
	<?php } ?>
<?php } ?>
</div>
</div>
<?php } 
echo Site::drawSubmit('quickstart_save', 'Save Defaults');
	
?>


</div>

</div>
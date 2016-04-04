<?php
/* @var $this DefaultController */

$this->breadcrumbs=array(
	$this->module->id,
);

$this->menu=array(
		array('label'=>'Reporting Tool', 'url'=>array('index')),
		array('label'=>'Referrer Define', 'url'=>array('//systems/referrerDefine/admin')),
		array('label'=>'Referrer Log', 'url'=>array('//systems/referrerLog/admin')),
);
?>
<h1>Reporting Tool</h1>
<form id="filter-form" action="<?php echo $this->createUrl('/ReportingTool/default') ?>" method="get">
	From: <input type="text" id="from" name="from" />
	To: <input type="text" id="to" name="to" />
	<input type="submit" value="View" class="button">
</form>
<img id="grid-loading" src="<?php echo Yii::app()->theme->baseUrl; ?>/resources/images/grid_loading.gif" style="display: none;">
<div id="report-list">
	<?php $this->renderPartial("_view", array('report'=>$report, 'total'=>$total)) ?>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
<script>
	$(function() {
		$( "#from, #to" ).datepicker({dateFormat: 'dd-mm-yy'}).datepicker("setDate", new Date());
		$('#filter-form').submit(function(){
			$('#grid-loading').show();
			var formValue = {
				from: $('#from').val().split("/").join("-"),
				to: $('#to').val().split("/").join("-")
			};
			var url = $(this).attr('action');
			$.get( url, formValue ).done(function( data ) {
			    $('#report-list').html(data);
			    $('#grid-loading').hide();
			});
			return false;
		});
	});
</script>
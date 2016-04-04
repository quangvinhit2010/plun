<?php
/**
 * roleAjax.php
 *
 * @author Spyros Soldatos <spyros@valor.gr>
 * @link http://code.google.com/p/srbac/
 */

/**
 * The assigning task to roles listboxes
 *
 * @author Spyros Soldatos <spyros@valor.gr>
 * @package srbac.views.authitem.tabViews
 * @since 1.0.0
 */
 ?>
<table width="100%">
  <tr>
    <th><?php echo Helper::translate('srbac','Assigned Roles')?></th>
    <th>&nbsp;</th>
    <th><?php echo Helper::translate('srbac','Not Assigned Roles')?></th>
  </tr>
  <tr><td width="45%">
      <?php echo SHtml::activeDropDownList($model,'name[revoke]',
      SHtml::listData(
      $data["roleAssignedRoles"], 'name', 'name'),
      array('size'=>$this->module->listBoxNumberOfLines,'multiple'=>'multiple','class'=>'dropdown')) ?>
    </td>
    <td width="10%" align="center">
      <?php
      $urlRedirect = Yii::app()->createAbsoluteUrl('/srbac/authitem/GetRolesToRolesList');
      $ajax =
          array(
          'type'=>'POST',
          'update'=>'#rolesToRoles',
        'beforeSend' => 'function(){
                      $("#loadMessRole").addClass("srbacLoading");
                  }',
        'complete' => 'function(){
                      $("#loadMessRole").removeClass("srbacLoading");
                      $.ajax({
						  url: "'.$urlRedirect.'",
						}).done(function(data) {
						  $("#rolesToRolesList").html(data);
						});
                  }');
      echo  SHtml::ajaxSubmitButton('<<',array('assign','assignRolesToRole'=>1),$ajax,$data['assign']); ?>
      <?php
      $ajax =
          array(
          'type'=>'POST',
          'update'=>'#rolesToRoles',
        'beforeSend' => 'function(){
                      $("#loadMessRole").addClass("srbacLoading");
                  }',
        'complete' => 'function(){
                      $("#loadMessRole").removeClass("srbacLoading");
                      $.ajax({
						  url: "'.$urlRedirect.'",
						}).done(function(data) {
						  $("#rolesToRolesList").html(data);
						});
                  }');
      echo  SHtml::ajaxSubmitButton('>>',array('assign','revokeRolesToRole'=>1),$ajax,$data['revoke']); ?>
    </td>
    <td width="45%">
      <?php echo SHtml::activeDropDownList($model,'name[assign]',
      SHtml::listData(
      $data["roleNotAssignedRoles"], 'name', 'name'),
      array('size'=>$this->module->listBoxNumberOfLines,'multiple'=>'multiple','class'=>'dropdown')); ?>
    </td></tr>
</table>
<div id="loadMessRole" class="message">
  <?php echo "&nbsp;".$message ?>
</div>

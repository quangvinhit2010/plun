<?php
/**
 * taskToRole.php
 *
 * @author Spyros Soldatos <spyros@valor.gr>
 * @link http://code.google.com/p/srbac/
 */

/**
 * The tab view for assigning roles to roles
 *
 * @author Spyros Soldatos <spyros@valor.gr>
 * @package srbac.views.authitem.tabViews
 * @since 1.0.0
 */
 ?>
<!-- ROLES -> ROLES -->
<?php

?>
<div class="srbac">
  <?php echo SHtml::beginForm(); ?>
  <?php echo SHtml::errorSummary($model); ?>
  <table width="100%">
    <tr><th colspan="2"><?php echo Helper::translate('srbac','Assign Roles to Roles') ?></th></tr>
    <tr>
      <th width="50%">
      <?php echo SHtml::label(Helper::translate('srbac',"Role"),'role'); ?></th>
      <td width="50%" rowspan="2">
        <div id="rolesToRoles">
          <?php
          $this->renderPartial('tabViews/roleToRoleAjax',
              array('model'=>$model,'data'=>$data,'message'=>$message));
          ?>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td id="rolesToRolesList">
      	<?php $this->renderPartial('tabViews/roleToRoleList');?>
      </td>
    </tr>
  </table>
  <br />
  <?php echo SHtml::endForm(); ?>
</div>
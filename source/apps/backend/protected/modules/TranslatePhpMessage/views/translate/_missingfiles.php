<h6>These files don't exist:</h6><br/>

<table>
    <tbody>
        <?php
        $missingfiles = $module->missingFiles($languageid);

        if (count($missingfiles) > 0) {
            foreach ($missingfiles as $key => $value) {
                $link = CHtml::ajaxLink('Initialize', Yii::app()->createUrl('TranslatePhpMessage/translate/initfile'), array(// ajaxOptions
                            'type' => 'POST',
                            'data' => array('fileid' => $key, 'languageid' => $languageid,),
                            'update' => '#translationfiles',
                                ), array(//htmlOptions
                            'href' => '#'
                        ));

                echo <<<TABLE
            <tr>
                <td>$value</td>
                <td>$link</td>
            </tr>
TABLE;
            }
        } else {
            echo 'No files missing';
        }
        ?>

    </tbody>
</table>
<h6>These files where found:</h6><br/>

<table>
    <tbody>
        <?php
        $existingfiles = $module->existingFiles($languageid);

        foreach ($existingfiles as $key => $value) {

            $missing = $module->countTranslation($value, $languageid);
            $complete = $missing[0] > 0 ? $missing[0] . ' missing' : 'Complete';
            $class = $missing[0] > 0 ? 'class="error"' : 'class="sucess"';
            
            $link = CHtml::Link(
                            'Translate', Yii::app()->createUrl('TranslatePhpMessage/translate/translate', array('filename' => $value, 'languageid' => $languageid,)));

            echo <<<TABLE
            <tr>
                <td>$value</td>
                <td>$link</td>
                <td><span $class>$complete</span> ($missing[1] total)</td>
            </tr>
TABLE;
        }
        ?>

    </tbody>
</table>


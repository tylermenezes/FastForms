<form method="post" action="<?=$action?>">
    <?php foreach ($fields as $field) : ?>
        <?php if ($field->form_type === 'hidden') : ?>
            <input
                type="<?=$field->form_type?>"
                id="<?=$field->form_name?>"
                name="<?=$field->form_name?>"
                value="<?=$field->value?>"
             />
        <?php else : ?>
            <div class="control-group">
                <label class="control-label" for="<?=$field->form_name?>"><?=$field->display_name?></label>
                <div class="controls">
                    <?php if ($field->form_type == 'textarea') : ?>
                        <textarea name="<?=$field->form_name?>" id="<?=$field->form_name?>" placeholder="<?=$field->placeholder?>"><?=$field->value?></textarea>
                    <?php elseif($field->form_type == 'select') : ?>
                        <select name="<?=$field->form_name?>" id="<?$field->form_name?>">
                            <?php foreach ($field->values as $val) : ?>
                                <option <?php if ($val == $field->value) echo 'selected="true"'; ?> value="<?=$val?>"><?=$val?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php elseif($field->form_type == 'checkbox') : ?>
                        <input
                            type="checkbox"
                            id="<?=$field->form_name?>"
                            name="<?=$field->form_name?>"
                            value="true"
                            <?php if ($field->value) echo 'checked="true"' ?> />
                    <?php else : ?>
                        <input
                            type="<?=$field->form_type?>"
                            id="<?=$field->form_name?>"
                            name="<?=$field->form_name?>"
                            value="<?=$field->value?>"
                            placeholder="<?=$field->placeholder?>" />
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
    <input type="submit" />
</form>

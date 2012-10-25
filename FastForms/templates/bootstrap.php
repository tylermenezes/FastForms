<?php foreach ($fields as $field) : ?>
    <?php if ($field->form_type === 'hidden') : ?>
        <input
            type="<?=$field->form_type?>"
            id="<?=$field->form_name?>"
            name="<?=$field->form_name?>"
            value="<?=$field->value?>"
            class="<?=$field->class?>"
         />
    <?php else : ?>
        <div class="control-group">
            <label class="control-label" for="<?=$field->form_name?>"><?=$field->display_name?></label>
            <div class="controls">
                <?php if ($field->form_type == 'textarea') : ?>
                    <textarea name="<?=$field->form_name?>" id="<?=$field->form_name?>" placeholder="<?=$field->placeholder?>" class="<?=$field->class?>"><?=$field->value?></textarea>
                <?php elseif($field->form_type == 'select') : ?>
                    <select name="<?=$field->form_name?>" id="<?$field->form_name?>" class="<?=$field->class?>">
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
                        class="<?=$field->class?>"
                        <?php if ($field->value) echo 'checked="true"' ?> />
                <?php elseif($field->form_type == 'date') : ?>
                    <input
                        type="date"
                        id="<?=$field->form_name?>_date"
                        name="<?=$field->form_name?>_date"
                        value="<?=$field->value_date?>"
                        class="<?=$field->class?>"
                        placeholder="YYYY-MM-DD" />
                    <input
                        type="text"
                        id="<?=$field->form_name?>_time"
                        name="<?=$field->form_name?>_time"
                        value="<?=$field->value_time?>"
                        class="<?=$field->class?>"
                        placeholder="HH:MM AM" />
                <?php else : ?>
                    <input
                        type="<?=$field->form_type?>"
                        id="<?=$field->form_name?>"
                        name="<?=$field->form_name?>"
                        value="<?=$field->value?>"
                        class="<?=$field->class?>"
                        placeholder="<?=$field->placeholder?>" />
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
<?php endforeach; ?>

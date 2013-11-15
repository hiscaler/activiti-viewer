
<div class="grid-view">

    <table id="table-columns" class="items">     
        <thead>
            <tr>
                <th>Column Name</th>
                <th>Display?</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $viewColumns = array_flip(Activiti::getTableViewColumns($table));
            foreach ($columns as $key => $column):
                if (isset($viewColumns[$column])) {
                    $value = 1;
                    $checked = ' checked="checked"';
                } else {
                    $value = 0;
                    $checked = '';
                }
                ?>
                <tr>
                    <td style="width: 120px;"><?php echo $column; ?></td>
                    <td class="center"><input type="checkbox" table-id="<?php echo $table; ?>" column-id="<?php echo $column; ?>" value="<?php echo $value; ?>"<?php echo $checked; ?> /></td>
                </tr>
            <?php endforeach; ?>

        </tbody>
    </table>
</div>


<script type="text/javascript">
    $(function() {
        $('#table-columns tbody td input').bind('click', function() {
            var t = $(this);
            $.ajax({
                type: 'POST',
                url: '<?php echo $this->createUrl('toggleColumn'); ?>',
                data: {
                    table: t.attr('table-id'),
                    column: t.attr('column-id'),
                    action: t.attr('checked') === 'checked' ? 'add' : 'remove'
                },
                success: function(response) {
                }
            });
        });
    });
</script>
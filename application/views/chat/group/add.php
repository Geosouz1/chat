<?php echo form_open('group/add') ?>
<table class="table table-bordered">
    <tr>
        <td>Nome do grupo</td>
        <td><input type="text" name="group_name" class="form-control" placeholder="Nome do grupo"></td>
    </tr>
    <tr>
        <td colspan="2" align="right"><input type="submit" name="submit" value="Salvar" /></td>
    </tr>
</table>
<?php echo form_close(); ?>
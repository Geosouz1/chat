<h2>Criar Grupo</h2>
<?php echo anchor('group/add', 'Buat Chat Group', ['class' => 'btn btn-primary btn-sm']); ?>
<br />
<br />
<table class="table table-bordered">
  <tr>
    <thead>
      <tr>
        <th>Nº</th>
        <th>Nome do grupo</th>
        <th>Feito por</th>
        <th>Número de membros</th>
        <th>Ação</th>
      </tr>
    </thead>
  </tr>
  <tbody>
  <?php
  $no = 0;
  foreach ($record->result() as $r) {
    $no++;
    echo "<tr>
      <td>$no</td>
      <td>$r->topic</td>
      <td>$r->created_by</td>
      <td>$r->total_member</td>
      <td>".
        anchor('group/check/'. $r->chat_id, 'Chat', ['class' => 'btn btn-success btn-sm'])
      ."</td>
    </tr>";
  }
  ?>
  </tbody>
</table>
</p>

<script src="<?php echo base_url(); ?>js/dashboard.js" type="text/javascript"></script>
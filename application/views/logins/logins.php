<div class="row">    
    <div class="col-sm-6 alert-success caderno" style="opacity: 0.99; ">
        <div class="caderno_text">
            <h1 style="font-family: arial"><b> O portal do conhecimento!</b></h1>
            <br>
            <h3><span class="glyphicon glyphicon-search"> Pesquisas</span> </h3>
            <h3><span class="glyphicon glyphicon-pencil"> Utilitarios</span> </h3>
        </div>
    </div>
    <div class="col-sm-2"></div>
    <div class="col-sm-4 " style="opacity: 0.99;">    
        <div class="panel panel-default">
            <div class="panel-body">
                <?php
                echo form_open('login', ['class' => 'form-signin']);
                echo $error;
                $this->session->unset_userdata('error');
                ?>
                <h2 class="form-signin-heading text-center"><b>S</b>tudy<b>B</b>ox</h2>
                <br>

                <input type="text" name="username" class="form-control" placeholder="Nome" required autofocus>
                <br>

                <input type="password" name="password" class="form-control" placeholder="Senha" required>
                <br>
                <button class="btn btn-lg btn-primary btn-block" name="submit" type="submit">Entrar</button> <br />
                <?php echo anchor('registro', 'Cadastre-se', ['class' => 'btn btn-lg btn-info btn-block']); ?>
                <?php
                echo form_close();
                ?>
            </div>
        </div>
    </div>
</div>

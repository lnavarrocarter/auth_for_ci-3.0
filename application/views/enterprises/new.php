<form id="createForm" class="form-horizontal" method="post" action="<?= base_url('enterprises/create');?>">
    <div class="form-group">
        <input type="text" name="name" class="form-control" placeholder="Nombre" required>
    </div>
    <div class="form-group">
        <input type="text" name="fakename" class="form-control" placeholder="Nombre Fantasía" required>
    </div>
    <div class="form-group">
        <input type="text" name="giro" class="form-control" placeholder="Giro de la empresa" required>
    </div>
    <div class="form-group">
        <input type="email" name="email" class="form-control" placeholder="Correo" required>
    </div>
    <div class="form-group">
        <input type="number" name="dni" class="form-control" placeholder="77898587" required>
    </div>
    <div class="form-group">
        <input type="text" name="dv" class="form-control" placeholder="Dígito Verificador" required>
    </div>
    <div class="form-group">
        <textarea rows="6" cols="5" name="description" placeholder="Descripcion de la empresa" class="form-control" required></textarea>  
    </div>
    <div class="form-group">
        <input type="number" name="max_members" class="form-control" placeholder="Cantidad de Cuentas" required>
    </div>
    <div class="form-group">
        <button id="submit" type="submit" class="btn btn-lg btn-success">Crear</button>
    </div>
</form>
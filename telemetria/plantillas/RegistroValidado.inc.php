<div class="form-group">
  <label>Nombre de usuario</label>
  <input type="text" class="form-control" name="nombre" <?php $validador -> mostrar_nombre_en_registro() ?>>
  <?php
  $validador -> mostrar_error_nombre_en_registro();
  ?>
</div>
<div class="form-group">
  <label>Email</label>
  <input type="email" class="form-control" name="email" <?php $validador -> mostrar_email_en_registro() ?>>
  <?php
  $validador -> mostrar_error_email_en_registro();
   ?>
</div>
<div class="form-group">
  <label>Contraseña</label>
  <input type="password" class="form-control" name="password1">
  <?php
  $validador -> mostrar_error_password1_en_registro();
   ?>
</div>
<div class="form-group">
  <label>Repite la contraseña</label>
  <input type="password" class="form-control" name="password2">
  <?php
  $validador -> mostrar_error_password2_en_registro();
  ?>
</div>
<br>
<div class="form-group">
  <button type="submit" class="btn btn-default btn-primary" name="enviar">Enviar</button>
  <button type="reset" class="btn btn-default btn-primary">Limpiar formulario</button>
</div>

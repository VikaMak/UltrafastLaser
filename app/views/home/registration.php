<?php if (!isset($_SESSION['regok'])) { ?>	
<div class="table-responsive">
  <form action="" method="post" class="form-horizontal">
    <table >
      <tr>
        <td>Login*</td>
        <td><input type="text" name="login" autofocus value="<?php echo $data['login']; ?>"></td>
        <td><?php echo $data['er_login']; ?></td>
      </tr>
      <tr>
        <td>Password*</td>
        <td><input type="password" name="password" value="<?php echo $data['password']; ?>"></td>
        <td><?php echo $data['er_password']; ?></td>
      </tr>
      <tr>
        <td>E-mail*</td>
        <td><input type="text" name="email" value="<?php echo $data['email'];?>"></td>
        <td><?php echo $data['er_email']; ?></td>
      </tr>
      <tr>
        <td>Age</td>
        <td><input type="text" name="age" value="<?php echo $data['age'];?>"></td>
        <td></td>
      </tr>
    </table>
    <p style="font-size: 11px">* - Поля, обязательные для заполнения</p>
    <input type="submit" name="sendreg" value="Submit">
  </form>

  <?php } else { unset($_SESSION['regok']); ?>
  <div>
      На ваш e-mail отправлена ссылка для подтверждения регистрации!
  </div>
  <?php } ?>
</div>
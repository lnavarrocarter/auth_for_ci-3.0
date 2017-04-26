<!-- You can grab this form and put it in a modal or a bootstrap panel -->
<form class="form-horizontal" method="post" action="<?= base_url('user/register')?>">
    <div class="input-group">
        <label for="name1" class="sr-only">Name</label>
        <input type="text" name="name1" class="form-control" placeholder="Name" required autofocus>
        <span class="input-group-addon"> + </span>
        <label for="lastname1" class="sr-only">Last Name</label>
        <input type="text" name="lastname1" class="form-control" placeholder="Lastname" required autofocus>
    </div>
    <br>
    <label for="username" class="sr-only">Username</label>
    <input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
    <br>
    <label for="email" class="sr-only">Email</label>
    <input type="text" name="email" class="form-control" placeholder="Email" required autofocus>
    <br>
    <label for="password" class="sr-only">Password</label>
    <input type="password" name="password" class="form-control" placeholder="Password" required>
    <br>
    <label for="password2" class="sr-only">Confirm Password</label>
    <input type="password" name="password2" class="form-control" placeholder="Confirm Password" required>
    <br>
    <div class="checkbox">
      <label>
        <input type="checkbox" name="terms" value="1"> I accept the terms and conditions
      </label>
    </div>
    <br>
    <button class="btn btn-lg btn-primary btn-block" type="submit">Register</button>
</form>
<form method="POST">
Login <input name="login" type="text" required><br>
Password <input name="password" type="password" required><br>
<input type="hidden" name="token" value="<?=$token?>"> <br/>
<input name="submit" type="submit" value="Sign in">
</form>
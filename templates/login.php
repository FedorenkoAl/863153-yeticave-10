
    <form class="form container <?=$error;?>" action="/login.php" method="post">
      <h2>Вход</h2>
       <?php if (isset($_POST['email'])) :?>
      <div class="form__item <?=$errors['email'];?>">
        <label for="email">E-mail <sup>*</sup></label>
    <input id="email" type="text" name="email" value="<?=htmlspecialchars($_POST['email']);?>" placeholder="Введите e-mail">
        <span class="form__error"><?=$dict['email'];?></span>
          </div>
          <?php else: ?>
            <div class="form__item">
        <label for="email">E-mail <sup>*</sup></label>
    <input id="email" type="text" name="email"  placeholder="Введите e-mail">
   </div>
         <?php endif; ?>
          <?php if (isset($_POST['password'])) :?>
      <div class="form__item form__item--last <?=$errors['password'];?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="text" name="password"  value="<?=htmlspecialchars($_POST['password']);?>"  placeholder="Введите пароль">
        <span class="form__error"><?=$dict['password'];?></span>
      </div>
        <?php else: ?>
             <div class="form__item form__item--last">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="text" name="password"  placeholder="Введите пароль">
       </div>
       <?php endif; ?>
      <button type="submit" class="button">Войти</button>
    </form>


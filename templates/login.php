<?php if (isset($error)) :?>
    <form class="form container <?=$error;?>" action="/login.php" method="post">
          <?php else: ?>
            <form class="form container" action="/login.php" method="post">
             <?php endif; ?>
      <h2>Вход</h2>
       <?php if (isset($_POST['email'])) :?>
        <?php if (isset($errors['email'])) :?>
      <div class="form__item <?=$errors['email'];?>">
         <?php else: ?>
              <div class="form__item">
                  <?php endif; ?>
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
            <?php if (isset($errors['password'])) :?>
      <div class="form__item form__item--last <?=$errors['password'];?>">
         <?php else: ?>
             <div class="form__item form__item--last>">
        <?php endif; ?>
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


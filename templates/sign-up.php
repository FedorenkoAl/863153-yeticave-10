
    <form class="form container <?=$error;?>" action="/sign-up.php" method="post" autocomplete="off">
      <h2>Регистрация нового аккаунта</h2>
      <?php if (isset($_POST['email'])) :?>
      <div class="form__item <?=$errors['email'];?>">
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" value="<?=htmlspecialchars($_POST['email']);?>" placeholder="Введите e-mail">
        <span class="form__error"><?=$dict['email'];?></span>
      </div>
        <?php else: ?>
         <div class="form__item">
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail">
       </div>
       <?php endif; ?>
         <?php if (isset($_POST['password'])) :?>
      <div class="form__item <?=$errors['password'];?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="text" name="password" value="<?=htmlspecialchars($_POST['password']);?>" placeholder="Введите пароль">
        <span class="form__error"><?=$dict['password'];?></span>
      </div>
        <?php else: ?>
             <div class="form__item">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="text" name="password" placeholder="Введите пароль">
      </div>
             <?php endif; ?>
               <?php if (isset($_POST['password'])) :?>
      <div class="form__item <?=$errors['name'];?>">
        <label for="name">Имя <sup>*</sup></label>
        <input id="name" type="text" name="name" value="<?=htmlspecialchars($_POST['name']);?>" placeholder="Введите имя">
        <span class="form__error"><?=$dict['name'];?></span>
      </div>
        <?php else: ?>
             <div class="form__item">
        <label for="name">Имя <sup>*</sup></label>
        <input id="name" type="text" name="name" placeholder="Введите имя">
        </div>
            <?php endif; ?>
        <?php if (isset($_POST['message'])) :?>
      <div class="form__item <?=$errors['message'];?>">
        <label for="message">Контактные данные <sup>*</sup></label>
        <textarea id="message" name="message" placeholder="Напишите как с вами связаться"><?=htmlspecialchars($_POST['message']);?></textarea>
        <span class="form__error"><?=$dict['message'];?></span>
      </div>
        <?php else: ?>
            <div class="form__item">
        <label for="message">Контактные данные <sup>*</sup></label>
        <textarea id="message" name="message" placeholder="Напишите как с вами связаться"></textarea>
        </div>
            <?php endif; ?>
      <span class="form__error form__error--bottom"><?=$dict['form'];?></span>
      <button type="submit" class="button">Зарегистрироваться</button>
      <a class="text-link" href="/login.php">Уже есть аккаунт</a>
    </form>


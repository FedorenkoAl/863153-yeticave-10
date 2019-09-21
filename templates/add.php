
        <form class="form form--add-lot container <?=$error;?>" action="/add.php" method="post" enctype="multipart/form-data">
        <h2>Добавление лота</h2>
        <div class="form__container-two">
             <?php if (isset($_POST['lot-name'])) :?>
          <div class="form__item <?=$errors['lot-name'];?>">
          <label for="lot-name">Наименование <sup>*</sup></label>
          <input id="lot-name" type="text" name="lot-name" value="<?=htmlspecialchars($_POST['lot-name']);?>"  placeholder="Введите наименование лота">
          <span class="form__error"><?=$dict['lot-name'];?></span>
        </div>
           <?php else: ?>
               <div class="form__item">
          <label for="lot-name">Наименование <sup>*</sup></label>
          <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота">
        </div>
        <?php endif; ?>
        <?php if (isset($_POST['lot-name'])) :?>
        <div class="form__item <?=$errors['category'];?>">
          <label for="category">Категория <sup>*</sup></label>
          <select id="category" name="category">
        <option><?=htmlspecialchars($_POST['category']);?></option>
        <?php foreach ($category as $value): ?>
            <option><?=htmlspecialchars($value['name']);?></option>
        <?php endforeach; ?>
          </select>
          <span class="form__error"><?=$dict['category'];?></span>
        </div>
        <?php else: ?>
             <div class="form__item">
          <label for="category">Категория <sup>*</sup></label>
          <select id="category" name="category">
<option>Выберите категорию</option>
            <?php foreach ($category as $value): ?>
            <option><?=htmlspecialchars($value['name']);?></option>
            <?php endforeach; ?>
          </select>
       </div>
        <?php endif; ?>
      </div>
       <?php if (isset($_POST['message'])) :?>
      <div class="form__item form__item--wide <?=$errors['message'];?>">
        <label for="message">Описание <sup>*</sup></label>
    <textarea id="message" name="message" placeholder="Напишите описание лота"><?=htmlspecialchars($_POST['message']);?></textarea>
        <span class="form__error"><?=$dict['message'];?></span>
      </div>
       <?php else: ?>
         <div class="form__item form__item--wide">
        <label for="message">Описание <sup>*</sup></label>
    <textarea id="message" name="message" placeholder="Напишите описание лота"></textarea>
       </div>
         <?php endif; ?>
         <div class="form__item form__item--file">
        <label>Изображение <sup>*</sup></label>
        <div class="form__input-file">
          <input class="visually-hidden" type="file" id="lot-img" name="lot-img">
          <label for="lot-img">
            Добавить
          </label>
        </div>
          <?php if (isset($errors['lot-img'])) :?>
         <span class="form__error form__error--bottom"><?=$errors['lot-img'];?></span>
           <?php endif; ?>
      </div>
<div class="form__container-three">
         <?php if (isset($_POST['lot-rate'])) :?>
        <div class="form__item form__item--small <?=$errors['lot-rate'];?>">
          <label for="lot-rate">Начальная цена <sup>*</sup></label>
          <input id="lot-rate" type="text" name="lot-rate" value="<?=htmlspecialchars($_POST['lot-rate']);?>" placeholder="0">
          <span class="form__error"><?=$dict['lot-rate'];?></span>
        </div>
          <?php else: ?>
              <div class="form__item form__item--small">
          <label for="lot-rate">Начальная цена <sup>*</sup></label>
          <input id="lot-rate" type="text" name="lot-rate" placeholder="0">
         </div>
             <?php endif; ?>
         <?php if (isset($_POST['lot-step'])) :?>
        <div class="form__item form__item--small <?=$errors['lot-step'];?>">
          <label for="lot-step">Шаг ставки <sup>*</sup></label>
          <input id="lot-step" type="text" name="lot-step" value="<?=htmlspecialchars($_POST['lot-step']);?>" placeholder="0">
          <span class="form__error"><?=$dict['lot-step'];?></span>
        </div>
  <?php else: ?> <div class="form__item form__item--small">
          <label for="lot-step">Шаг ставки <sup>*</sup></label>
          <input id="lot-step" type="text" name="lot-step" placeholder="0">
         </div>
             <?php endif; ?>
         <?php if (isset($_POST['lot-date'])) :?>
        <div class="form__item <?=$errors['lot-date'];?>">
          <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
          <input class="form__input-date" id="lot-date" value="<?=htmlspecialchars($_POST['lot-date']);?>" type="text" name="lot-date" placeholder="Введите дату в формате ГГГГ-ММ-ДД">
          <span class="form__error"><?=$dict['lot-date'];?></span>
        </div>
        <?php else: ?>
         <div class="form__item">
          <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
          <input class="form__input-date" id="lot-date"  type="text" name="lot-date" placeholder="Введите дату в формате ГГГГ-ММ-ДД">
        </div>
             <?php endif; ?>
      </div>
<span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме</span>
<button type="submit" class="button">Добавить лот</button>
    </form>


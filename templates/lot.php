
<section class="lot-item container">
      <?php if (isset($lots_id)) :?>
    <h2><?=htmlspecialchars($lots_id["name"]);?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="<?=$lots_id['image'];?>" width="730" height="548" alt="Сноуборд">
            </div>
            <p class="lot-item__category">Категория: <span><?=htmlspecialchars($lots_id['c']);?></span></p>
            <p class="lot-item__description"><?=htmlspecialchars($lots_id['description']);?></p>
        </div>


        <div class="lot-item__right">

            <?php if (isset($_SESSION['user']) && (time() < strtotime($lots_id["data_end"]))  && ($lots_id['author'] !== $_SESSION['user']['id']) &&  ($lots_id['rate_user'] !== $_SESSION['user']['id'])) :?>
            <div class="lot-item__state">
                <?php if ((strtotime($lots_id['data_end']) - time()) <= 0) :?>
                <div class="lot-item__timer timer timer--finishing">
                    00:00
                </div>
                <?php else: ?>
                <div class="lot-item__timer timer">
                    <?=time_end(time(),strtotime($lots_id['data_end']));?>
                </div>
                <?php endif; ?>
                <div class="lot-item__cost-state">
                    <div class="lot-item__rate">
                        <span class="lot-item__amount">Текущая цена</span>
                        <span class="lot-item__cost"><?=htmlspecialchars(money($lots_id['max']));?></span>
                    </div>
                    <div class="lot-item__min-cost">
                        Мин. ставка <?=money_step($lots_id['min']);?>
                    </div>
                </div>

                <form class="lot-item__form" action="/lot.php?id=<?=$_GET['id'];?>" method="post" autocomplete="off">

                     <?php if (isset($_POST['cost'])) :?>
            <?php if (isset($errors) && isset($dict)) :?>
                    <p class="lot-item__form-item <?=$errors['cost'];?>">
                        <label for="cost">Ваша ставка</label>
                        <input id="cost" type="text" name="cost" value="<?=htmlspecialchars($_POST['cost']); ?>" placeholder="<?=money_step($lots_id['min']);?>">
                        <span class="form__error"><?=$dict['cost'];?></span>
                    </p>
                     <?php endif; ?>
                       <?php else: ?>
                          <p class="lot-item__form-item">
                        <label for="cost">Ваша ставка</label>
                        <input id="cost" type="text" name="cost" placeholder="<?=money_step($lots_id['min']);?>">

                    </p>
                      <?php endif; ?>

                    <button type="submit" class="button">Сделать ставку</button>
                </form>
            </div>
            <?php endif; ?>
             <?php endif; ?>
            <div class="history">
                 <?php if (isset($count)) :?>
                <h3>История ставок (<span><?=$count;?></span>)</h3>
                 <?php endif; ?>
                <?php if (is_array($result)) :?>
                <table class="history__list">
                    <?php foreach ($result as $key => $valu): ?>
                    <tr class="history__item">
                        <td class="history__name"><?=htmlspecialchars($valu['name']);?></td>
                        <td class="history__price"><?=htmlspecialchars(money($valu['price']));?>
                            </td>
                        <?php if (((time() - strtotime($valu['date_create'])) >= 0) && (time() - strtotime($valu['date_create'])) <= 60):?>
                        <td class="history__time">только что</td>
                        <?php endif; ?>
                        <?php if ((time() - strtotime($valu['date_create'])) > 60 && (time() - strtotime($valu['date_create'])) <= 3600 ):?>
                        <td class="history__time"><?=date_interval_format(time_end2($valu['date_create']), "%i");?>
                            <?=' ' . get_noun_plural_form(date_interval_format(time_end2($valu['date_create']), "%i"), 'минута',
                            'минуты',
                            'минут'). ' ';?>назад
                        </td>
                        <?php endif; ?>
                        <?php if ((time() - strtotime($valu['date_create'])) > 3600 && (time() - strtotime($valu['date_create'])) != time()):?>
                        <td class="history__time">
                            <?=date('d.m.Y в H:i',strtotime($valu['date_create']));?>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>


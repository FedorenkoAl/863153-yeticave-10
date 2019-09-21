
    <div class="container">
      <section class="lots">
        <h2>Результаты поиска по запросу «<span>Union</span>»</h2>
          <?php if ($lots_item == null) :?>
 <p class="promo__text">Ничего не найдено по вашему запросу</p>
             <?php else: ?>
        <ul class="lots__list">
            <?php foreach ($lots_item as $key => $val): ?>
          <li class="lots__item lot">
            <div class="lot__image">
              <img src="<?=$val["image"]; ?>" width="350" height="260" alt="Сноуборд">
            </div>
            <div class="lot__info">
              <span class="lot__category"><?=htmlspecialchars($val["cat"]);?></span>
              <h3 class="lot__title"><a class="text-link" href="/lot.php?id=<?=$val['id']; ?>"><?=htmlspecialchars($val["name"]);?></a></h3>
              <div class="lot__state">
                <div class="lot__rate">
                  <span class="lot__amount">Стартовая цена</span>
                  <span class="lot__cost"><?=htmlspecialchars(money($val["price"]));?></span>
                </div>
                <?php if ((strtotime($val['data_end']) - time()) <= 0) :?>
                <div class="lot__timer timer timer--finishing">
                  00:00
                </div>
                  <?php else: ?>
                     <div class="lot__timer timer">
                        <?=time_end(time(),strtotime($val['data_end']));?>
                    </div>
                 <?php endif; ?>
              </div>
            </div>
          </li>

           <?php endforeach; ?>
        </ul>
        <?php endif; ?>
      </section>
         <?php if ($pages_count > 1): ?>
      <ul class="pagination-list">
        <?php if (isset($_GET['page'])) :?>
<li class="pagination-item pagination-item-prev"><a

        <?php if ($_GET['page'] >= 2): ?>
    href="/search.php?page=<?=($_GET['page'] - 1);?>&search=<?=$search;?>&count=<?=$pages_count;?>"
    <?php else: ?>
         <?php endif; ?>
>Назад</a></li>
 <?php else: ?>
    <li class="pagination-item pagination-item-prev"><a>Назад</a></li>

         <?php endif; ?>
         <?php foreach ($pages as $page): ?>
     <?php if ($page == $cur_page) :?>
        <li class="pagination-item pagination-item-active"><a
><?=$page;?></a></li>
         <?php else: ?>

        <li class="pagination-item"><a href="/search.php?page=<?=$page;?>&search=<?=$search;?>&count=<?=$pages_count;?>"><?=$page;?></a></li>
        <?php endif; ?>

          <?php endforeach; ?>
          <?php if (isset($_GET['page'])) :?>
<li class="pagination-item pagination-item-next"><a
 <?php if (($_GET['page'] >= 1) && ($_GET['page'] < $pages_count)) :?>
  href="/search.php?page=<?=($_GET['page'] + 1);?>&search=<?=$search;?>&count=<?=$pages_count;?>"
    <?php else: ?>
<?php endif; ?>
            >Вперед</a></li>
            <?php else: ?>
                <li class="pagination-item pagination-item-next"><a >Вперед</a></li>
                <?php endif; ?>
      </ul>
       <?php endif; ?>
    </div>


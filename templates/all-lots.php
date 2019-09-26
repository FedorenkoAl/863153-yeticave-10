
<div class="container">
      <section class="lots">

              <?php if (isset($category_name)) :?>
<h2>Все лоты в категории <span>«<?=$category_name['name'];?>»</span></h2>
<?php endif; ?>
<ul class="lots__list">
     <?php if (isset($lots_category)) :?>
             <?php foreach ($lots_category as $key => $value): ?>
          <li class="lots__item lot">
            <div class="lot__image">
              <img src="<?=$value['image'];?>" width="350" height="260" alt="Сноуборд">
            </div>
            <div class="lot__info">
              <span class="lot__category"><?=htmlspecialchars($value['c']);?></span>
              <h3 class="lot__title"><a class="text-link" href="lot.php?id=<?=$value['l'];?>"><?=htmlspecialchars($value['name']);?></a></h3>
              <div class="lot__state">
                <div class="lot__rate">
                  <span class="lot__amount">Стартовая цена</span>
                  <span class="lot__cost"><?=htmlspecialchars(money($value['price']));?></span>
                </div>

                <div class="lot__timer timer">
          <?=time_end(time(),strtotime($value['data_end']));?>
                </div>

              </div>
            </div>
          </li>
 <?php endforeach; ?>
        </ul>
<?php endif; ?>
      </section>

        <?php if (isset($pages_count) && ($pages_count > 1)): ?>
      <ul class="pagination-list">
        <?php if (isset($_GET['page'])) :?>
<li class="pagination-item pagination-item-prev"><a

        <?php if ($_GET['page'] >= 2): ?>
    href="/all-lots.php?page=<?=($_GET['page'] - 1);?>&id=<?=($_GET['id']);?>&count=<?=$pages_count;?>"
    <?php else: ?>
         <?php endif; ?>
>Назад</a></li>
 <?php else: ?>
    <li class="pagination-item pagination-item-prev"><a>Назад</a></li>

         <?php endif; ?>
          <?php if (isset($pages) && isset($cur_page)) :?>
         <?php foreach ($pages as $page): ?>
     <?php if ($page == $cur_page) :?>
        <li class="pagination-item pagination-item-active"><a
><?=$page;?></a></li>
         <?php else: ?>

        <li class="pagination-item"><a href="all-lots.php?page=<?=$page;?>&id=<?=($_GET['id']);?>&count=<?=$pages_count;?>"><?=$page;?></a></li>
        <?php endif; ?>

          <?php endforeach; ?>
            <?php endif; ?>
          <?php if (isset($_GET['page'])) :?>
<li class="pagination-item pagination-item-next"><a
 <?php if (($_GET['page'] >= 1) && ($_GET['page'] < $pages_count)) :?>
  href="/all-lots.php?page=<?=($_GET['page'] + 1);?>&id=<?=($_GET['id']);?>&count=<?=$pages_count;?>"
    <?php else: ?>
<?php endif; ?>
            >Вперед</a></li>
            <?php else: ?>
                <li class="pagination-item pagination-item-next"><a >Вперед</a></li>
                <?php endif; ?>
      </ul>
       <?php endif; ?>



    </div>

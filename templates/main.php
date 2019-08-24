<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
    <ul class="promo__list">
        <?php foreach ($category as $key => $value): ?>
        <li class="promo__item promo__item--<?=$value['symbol'];?>">
            <a class="promo__link" href="pages/all-lots.html"><?=$value['name'];?></a>
        </li>
        <?php endforeach ?>
    </ul>
</section>
<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list">
        <?php foreach ($lots as $key => $value): ?>
        <li class="lots__item lot">
            <div class="lot__image">
                <img src="<?=$value["image"];?>" width="350" height="260" alt="">
            </div>
            <div class="lot__info">
                <span class="lot__category"><?=htmlspecialchars($value["cat"]);?></span>
                <h3 class="lot__title"><a class="text-link" href="/lot.php?id=<?=$value['id'];?>"><?=htmlspecialchars($value["name"]);?></a></h3>
                <div class="lot__state">
                    <div class="lot__rate">
                        <span class="lot__amount">Стартовая цена</span>
                        <span class="lot__cost"><?=htmlspecialchars(money($value["price"]));?></span>
                    </div>
                     <?php if ((strtotime($value['data_end']) - time()) <= 0) :?>
                        <div class="lot__timer timer timer--finishing">
                      00:00
                        </div>
                           <?php else: ?>
                        <div class="lot__timer timer">
                        <?=time_end(time(),strtotime($value['data_end']));?>
                </div>
                 <?php endif; ?>
                </div>
            </div>
        </li>
        <?php endforeach; ?>
    </ul>
</section>

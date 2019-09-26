
                <section class="rates container">
                    <h2>Мои ставки</h2>
                    <table class="rates__list">
                         <?php if (isset($lot_active)) :?>
                        <?php foreach ($lot_active as $valu): ?>
                        <tr class="rates__item">
                            <td class="rates__info">
                                <div class="rates__img">
                                    <img src="<?=$valu['image'];?>" width="54" height="40" alt="Сноуборд">
                                </div>
                                <h3 class="rates__title"><a href="../lot.php?id=<?=$valu['id'];?>"><?=htmlspecialchars($valu['name']);?></a></h3>
                            </td>
                            <td class="rates__category">
                                <?=htmlspecialchars($valu['cat']);?>
                            </td>
                            <td class="rates__timer">
                                <div class="timer timer">
                                    <?=time_end(time(),strtotime($valu['data_end']));?>
                                </div>
                            </td>
                            <td class="rates__price">
                                <?=htmlspecialchars(money($valu['price']));?>
                            </td>
                            <td class="rates__time">
                                <?php if ((time() - strtotime($valu['date_create'])) >= 0 && (time() - strtotime($valu['date_create'])) <= 60):?>
                                только что
                                <?php endif; ?>
                                <?php if ((time() - strtotime($valu['date_create'])) > 60 && (time() - strtotime($valu['date_create'])) <= 3600 ):?>
                                <?=date_interval_format(time_end2($valu['date_create']), "%i");?>
                                <?=' ' . get_noun_plural_form(date_interval_format(time_end2($valu['date_create']), "%i"), 'минута',
                                'минуты',
                                'минут'). ' ';?>назад
                                <?php endif; ?>
                                <?php if ((time() - strtotime($valu['date_create'])) > 3600 && (time() - strtotime($valu['date_create'])) != time()):?>
                                <?=date('d.m.Y в H:i',strtotime($valu['date_create']));?>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                              <?php endif; ?>
                               <?php if (isset($lots_win)) :?>
                        <?php foreach ($lots_win as $valu): ?>
                        <tr class="rates__item rates__item--win">
                            <td class="rates__info">
                                <div class="rates__img">
                                    <img src="<?=$valu['image'];?>" width="54" height="40" alt="Крепления">
                                </div>
                                <div>
                                    <h3 class="rates__title"><a href="../lot.php?id=<?=$valu['id'];?>"><?=htmlspecialchars($valu['name']);?></a></h3>
                                    <p><?=htmlspecialchars($_SESSION['user']['contacts']);?></p>
                                </div>
                            </td>
                            <td class="rates__category">
                                <?=htmlspecialchars($valu['cat']);?>
                            </td>
                            <td class="rates__timer">
                                <div class="timer timer--win">Ставка выиграла</div>
                            </td>
                            <td class="rates__price">
                                <?=htmlspecialchars(money($valu['price']));?>
                            </td>
                            <td class="rates__time">
                                <?php if ((time() - strtotime($valu['data_end'])) >= 0 && (time() - strtotime($valu['data_end'])) <= 60):?>
                                только что
                                <?php endif; ?>
                                <?php if ((time() - strtotime($valu['data_end'])) > 60 && (time() - strtotime($valu['data_end'])) <= 3600 ):?>
                                <?=date_interval_format(time_end2($valu['data_end']), "%i");?>
                                <?=' ' . get_noun_plural_form(date_interval_format(time_end2($valu['data_end']), "%i"), 'минута',
                                'минуты',
                                'минут'). ' ';?>назад
                                <?php endif; ?>
                               <?php if (((time() - strtotime($valu['data_end'])) > 3600) && (time() - strtotime($valu['data_end'])) != time()):?>
                                <?=date('d.m.Y в H:i',strtotime($valu['data_end']));?><?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                         <?php else: ?>
                              <?php endif; ?>
                               <?php if (isset($lots_end)) :?>
                        <?php foreach ($lots_end as $valu): ?>
                        <tr class="rates__item rates__item--end">
                            <td class="rates__info">
                                <div class="rates__img">
                                    <img src="<?=$valu['image'];?>" width="54" height="40" alt="Куртка">
                                </div>
                                <h3 class="rates__title"><a href="../lot.php?id=<?=$valu['id'];?>"><?=htmlspecialchars($valu['name']);?></a></h3>
                            </td>
                            <td class="rates__category">
                                <?=htmlspecialchars($valu['cat']);?>
                            </td>
                            <td class="rates__timer">
                                <div class="timer timer--end">Торги окончены</div>
                            </td>
                            <td class="rates__price">
                                <?=htmlspecialchars(money($valu['price']));?>
                            </td>
                            <td class="rates__time">
                                <?php if ((time() - strtotime($valu['data_end'])) >= 0 && (time() - strtotime($valu['data_end'])) <= 60):?>
                                только что
                                <?php endif; ?>
                                <?php if ((time() - strtotime($valu['data_end'])) > 60 && (time() - strtotime($valu['data_end'])) <= 3600 ):?>
                                <?=date_interval_format(time_end2($valu['data_end']), "%i");?>
                                <?=' ' . get_noun_plural_form(date_interval_format(time_end2($valu['data_end']), "%i"), 'минута',
                                'минуты',
                                'минут'). ' ';?>назад
                                <?php endif; ?>
                                <?php if (((time() - strtotime($valu['data_end'])) > 3600) && (time() - strtotime($valu['data_end'])) != time()):?>
                                <?=date('d.m.Y в H:i',strtotime($valu['data_end']));?><?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                              <?php endif; ?>
                    </table>
                </section>


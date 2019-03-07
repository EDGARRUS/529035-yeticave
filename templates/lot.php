<main>
    <nav class="nav">
        <ul class="nav__list container">
            <?php foreach($menu as $value): ?>
                <li class="nav__item">
                    <a href="index.php?cat_id=<?= $value['id']; ?>"><?php echo $value['name']; ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <section class="lot-item container">
            <h2><?=htmlspecialchars($lot['name']);?></h2>
            <div class="lot-item__content">
                <div class="lot-item__left">
                    <div class="lot-item__image">
                        <img src="<?php echo $lot["image"];?>" width="730" height="548" alt="Сноуборд">
                    </div>
                    <p class="lot-item__category">Категория: <span><?php echo ($lot['cat']);?></span></p>
                    <p class="lot-item__description"><?=htmlspecialchars($lot['description']);?></p>
                </div>
                <div class="lot-item__right">
                    <div class="lot-item__state">
                        <div class="lot-item__timer timer">
                            <?php echo calc_time_to_end(strtotime($lot['date_end']));?>
                        </div>
                        <div class="lot-item__cost-state">
                            <div class="lot-item__rate">
                                <span class="lot-item__amount">Текущая цена</span>
                                <span class="lot-item__cost"><?php echo htmlspecialchars(format_price($lot['now_price']));?></span>
                            </div>
                            <div class="lot-item__min-cost">
                                Мин. ставка <span><?php echo htmlspecialchars(format_price($lot['future_price']));?></span>
                            </div>
                        </div>
                        <?php if(isset($_SESSION['user']) and strtotime($lot['date_end']) > strtotime('NOW') and $_SESSION['user']['id'] !== $lot['author_id'] and $_SESSION['user']['id'] !== $lot['user_id']):?>
                            <?php $classname = isset($_GET['errors']) ? "form__item--invalid" : "";?>
                        <form class="lot-item__form" action="add_bet.php?id=<?php echo $lot['id'];?>" method="post">
                            <p class="lot-item__form-item form__item <?php echo $classname;?>">
                                <label for="cost">Ваша ставка</label>
                                <input id="cost" type="number" name="amount" placeholder="<?php echo htmlspecialchars(format_price($lot['future_price']));?>">
                                <span class="form__error"></span>
                            </p>
                            <button type="submit" class="button">Сделать ставку</button>
                        </form>
                        <?php endif ?>
                    </div>
                    <div class="history">
                        <h3>
                            История ставок (<span><?php echo $all_bets;?></span>)</h3>
                        <table class="history__list">
                            <?php foreach ($bets as $value): ?>
                            <tr class="history__item">
                                <td class="history__name"><?php echo $value['name']; ?></td>
                                <td class="history__price"><?php echo $value['amount']; ?></td>
                                <td class="history__time"><?php echo calc_time_ago(strtotime($value['created_at'])); ?></td>
                                <?php endforeach;?>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </section>
</main>
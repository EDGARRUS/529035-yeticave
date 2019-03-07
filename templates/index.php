<main class="container">
    <section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и
        горнолыжное снаряжение.</p>
    <ul class="promo__list">
        <!--заполните этот список из массива категорий-->
        <?php foreach($menu as $value): ?>
            <li class="promo__item promo__item--boards">
                <a class="promo__link" href="index.php?cat_id=<?= $value['id']; ?>"><?php echo $value['name']; ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</section>
<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list">
        <!--заполните этот список из массива с товарами-->
        <?php foreach ($menu_list as $value): ?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?php echo $value["image"];?>" width="350" height="260" alt="">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?php echo $value["cat"];?></span>
                    <h3 class="lot__title"><a class="text-link" href="lot.php?id=<?= $value['id']; ?>"><?php echo htmlspecialchars($value["name"]);?></a></h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена</span>
                            <span class="lot__cost"><?php echo htmlspecialchars(format_price($value["start_price"]));?></span>
                        </div>
                        <div class="lot__timer timer">
                            <?php echo calc_time_to_end(strtotime($value['date_end']));?>
                        </div>
                    </div>
                </div>
            </li>
        <?php endforeach;?>
    </ul>
</section>
</main>
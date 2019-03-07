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
    <?php $classname = isset($errors['name']) ? "form--invalid" : "";?>
    <form class="form form--add-lot container <?=$classname;?>" action="add.php" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
        <h2>Добавление лота</h2>
        <div class="form__container-two">
            <?php $classname = isset($errors['name']) ? "form__item--invalid" : "";
            $value = isset($lot['name']) ? $lot['name'] : ""; ?>

            <div class="form__item <?=$classname;?>"> <!-- form__item--invalid -->
                <label for="lot-name">Наименование</label>
                <input id="lot-name" type="text" name="name" placeholder="Введите наименование лота" value="<?=$value;?>">
                <span class="form__error"><?=$dict['name'];?></span>
            </div>
            <?php $classname = isset($errors['category_id']) ? "form__item--invalid" : "";?>
            <div class="form__item <?=$classname;?>">
                <label for="category">Категория</label>
                <select id="category" name="category_id">
                    <option value="">Выберите категорию</option>
                    <?php foreach ($menu as $cat): ?>
                    <option value="<?=$cat['id'] ?>"><?=$cat['name']; ?></option>
                    <?php endforeach; ?>
                </select>
                <span class="form__error"><?=$dict['category'];?></span>
            </div>
        </div>
        <?php $classname = isset($errors['description']) ? "form__item--invalid" : "";
        $value = isset($lot['description']) ? $lot['description'] : ""; ?>
        <div class="form__item form__item--wide <?=$classname;?>">
            <label for="message">Описание</label>
            <textarea id="message" name="description" placeholder="Напишите описание лота"><?=$value;?></textarea>
            <span class="form__error"><?=$dict['description'];?></span>
        </div>
        <?php $classname = isset($errors['image']) ? "form__item--uploaded" : "";?>
        <div class="form__item form__item--file <?=$classname;?>"> <!-- form__item--uploaded -->
            <label>Изображение</label>
            <div class="preview">
                <button class="preview__remove" type="button">x</button>
                <div class="preview__img">
                    <img src="img/avatar.jpg" width="113" height="113" alt="Изображение лота">
                </div>
            </div>
            <div class="form__input-file">
                <input class="visually-hidden" type="file" name="image" id="photo2" value="">
                <label for="photo2">
                    <span>+ Добавить</span>
                </label>
            </div>
        </div>
        <div class="form__container-three">
            <?php $classname = isset($errors['start_price']) ? "form__item--invalid" : "";
            $value = isset($lot['start_price']) ? $lot['start_price'] : ""; ?>
            <div class="form__item form__item--small <?=$classname;?>">
                <label for="lot-rate">Начальная цена</label>
                <input id="lot-rate" type="number" name="start_price" placeholder="0" value="<?=$value;?>">
                <span class="form__error"><?=$dict['start_price'];?></span>
            </div>
            <?php $classname = isset($errors['step_price']) ? "form__item--invalid" : "";
            $value = isset($lot['step_price']) ? $lot['step_price'] : ""; ?>
            <div class="form__item form__item--small <?=$classname;?>">
                <label for="lot-step">Шаг ставки</label>
                <input id="lot-step" type="number" name="step_price" placeholder="0" value="<?=$value;?>">
                <span class="form__error"><?=$dict['step_price'];?></span>
            </div>
            <?php $classname = isset($errors['date_end']) ? "form__item--invalid" : "";
            $value = isset($lot['date_end']) ? $lot['date_end'] : ""; ?>
            <div class="form__item <?=$classname;?>">
                <label for="lot-date">Дата окончания торгов</label>
                <input class="form__input-date" id="lot-date" type="date" name="date_end" value="<?=$value;?>">
                <span class="form__error"><?=$dict['date_end'];?></span>
            </div>
        </div>
        <?php if (isset($errors)): ?>
        <span class="form__error form__error--bottom"><?=$dict['form'];?></span>
        <?php endif; ?>
        <button type="submit" class="button">Добавить лот</button>
    </form>
</main>
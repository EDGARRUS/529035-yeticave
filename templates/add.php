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
    <form class="form form--add-lot container" action="add.php" method="post"> <!-- form--invalid -->
        <h2>Добавление лота</h2>
        <div class="form__container-two">
            <?php $classname = isset($errors['name']) ? "form__item--invalid" : "";
            $value = isset($lot['name']) ? $lot['name'] : ""; ?>

            <div class="form__item <?=$classname;?>"> <!-- form__item--invalid -->
                <label for="lot-name">Наименование</label>
                <input id="lot-name" type="text" name="name" placeholder="Введите наименование лота" value="<?=$value;?>" required>
                <span class="form__error"><?=$dict['name'];?></span>
            </div>
            <?php $classname = isset($errors['categories']) ? "form__item--invalid" : "";
            $value = isset($lot['categories']) ? $lot['categories'] : ""; ?>
            <div class="form__item <?=$classname;?>">
                <label for="category">Категория</label>
                <select id="category" name="categories" required>
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
            <textarea id="message" name="description" placeholder="Напишите описание лота" value="<?=$value;?>" required></textarea>
            <span class="form__error"><?=$dict['description'];?></span>
        </div>
        <div class="form__item form__item--file"> <!-- form__item--uploaded -->
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
            <?php $classname = isset($errors['lot-rate']) ? "form__item--invalid" : "";
            $value = isset($lot['lot-rate']) ? $lot['lot-rate'] : ""; ?>
            <div class="form__item form__item--small <?=$classname;?>">
                <label for="lot-rate">Начальная цена</label>
                <input id="lot-rate" type="number" name="start_price" placeholder="0" value="<?=$value;?>" required>
                <span class="form__error"><?=$dict['lot-rate'];?></span>
            </div>
            <?php $classname = isset($errors['lot-step']) ? "form__item--invalid" : "";
            $value = isset($lot['lot-step']) ? $lot['lot-step'] : ""; ?>
            <div class="form__item form__item--small <?=$classname;?>">
                <label for="lot-step">Шаг ставки</label>
                <input id="lot-step" type="number" name="step_price" placeholder="0" value="<?=$value;?>" required>
                <span class="form__error"><?=$dict['lot-step'];?></span>
            </div>
            <?php $classname = isset($errors['lot-date']) ? "form__item--invalid" : "";
            $value = isset($lot['lot-date']) ? $lot['lot-date'] : ""; ?>
            <div class="form__item <?=$classname;?>">
                <label for="lot-date">Дата окончания торгов</label>
                <input class="form__input-date" id="lot-date" type="date" name="date_end" value="<?=$value;?>" required>
                <span class="form__error"><?=$dict['lot-date'];?></span>
            </div>
        </div>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <button type="submit" class="button">Добавить лот</button>
    </form>
</main>
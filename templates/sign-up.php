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
    <?php $classname = isset($errors) ? "form--invalid" : "";?>
<form class="form container <?=$classname;?>" action="sign-up.php" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
    <h2>Регистрация нового аккаунта</h2>
    <?php $classname = isset($errors['email']) ? "form__item--invalid" : "";
    $value = isset($form['email']) ? $form['email'] : ""; ?>
    <div class="form__item <?=$classname;?>"> <!-- form__item--invalid -->
        <label for="email">E-mail*</label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?=$value;?>">
        <span class="form__error"><?=$dict['email'];?></span>
    </div>
    <?php $classname = isset($errors['pass']) ? "form__item--invalid" : "";
    $value = isset($form['pass']) ? $form['pass'] : ""; ?>
    <div class="form__item <?=$classname;?>">
        <label for="password">Пароль*</label>
        <input id="password" type="text" name="pass" placeholder="Введите пароль" value="<?=$value;?>">
        <span class="form__error"><?=$dict['pass'];?></span>
    </div>
    <?php $classname = isset($errors['name']) ? "form__item--invalid" : "";
    $value = isset($form['name']) ? $form['name'] : ""; ?>
    <div class="form__item <?=$classname;?>">
        <label for="name">Имя*</label>
        <input id="name" type="text" name="name" placeholder="Введите имя" value="<?=$value;?>">
        <span class="form__error"><?=$dict['name'];?></span>
    </div>
    <?php $classname = isset($errors['phone']) ? "form__item--invalid" : "";
    $value = isset($form['phone']) ? $form['phone'] : ""; ?>
    <div class="form__item <?=$classname;?>">
        <label for="message">Контактные данные*</label>
        <textarea id="message" name="phone" placeholder="Напишите как с вами связаться"><?=$value;?></textarea>
        <span class="form__error"><?=$dict['phone'];?></span>
    </div>
    <div class="form__item form__item--file form__item--last">
        <label>Аватар</label>
        <div class="preview">
            <button class="preview__remove" type="button">x</button>
            <div class="preview__img">
                <img src="img/avatar.jpg" width="113" height="113" alt="Ваш аватар">
            </div>
        </div>
        <div class="form__input-file">
            <input class="visually-hidden" type="file" id="photo2" name="image" value="">
            <label for="photo2">
                <span>+ Добавить</span>
            </label>
        </div>
    </div>
    <?php if (isset($errors)): ?>
    <span class="form__error form__error--bottom"><?=$dict['form'];?></span>
    <?php endif; ?>
    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="login.php">Уже есть аккаунт</a>
</form>
</main>
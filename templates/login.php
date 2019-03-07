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
    <form class="form container <?=$classname;?>" action="login.php" method="post"> <!-- form--invalid -->
        <h2>Вход</h2>
        <?php $classname = isset($errors['email']) ? "form__item--invalid" : "";
        $value = isset($form['email']) ? $form['email'] : ""; ?>
        <div class="form__item <?=$classname;?>"> <!-- form__item--invalid -->
            <label for="email">E-mail*</label>
            <input id="email" type="text" name="email" value="<?=$value;?>" placeholder="Введите e-mail">
            <span class="form__error"><?=$errors['email'];?></span>
        </div>
        <?php $classname = isset($errors['email']) ? "form__item--invalid" : "";?>
        <div class="form__item form__item--last <?=$classname;?>">
            <label for="password">Пароль*</label>
            <input id="password" type="text" name="pass" placeholder="Введите пароль">
            <span class="form__error"><?=$errors['pass'];?></span>
        </div>
        <button type="submit" class="button">Войти</button>
    </form>
</main>
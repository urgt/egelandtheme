<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta charset="<?php bloginfo('charset'); ?>">
    <title>
        <?php echo wp_get_document_title(); ?>
    </title>
    <?php wp_head(); ?>

</head>

<body>
    <div class="wrapper">
        <header class="header">
            <div class="header__wrapper">
                <div class="header__logo container">
                    <a href="/">HEADER</a>
                </div>
                <nav class="header__nav container">
                    <ul class="header__menu">
                        <li class="header__menu-item"><a href="#">Главная</a></li>
                        <li class="header__menu-item"><a href="#">Статьи</a></li>
                        <li class="header__menu-item"><a href="#">Новости</a></li>
                    </ul>
                </nav>
            </div>
        </header>
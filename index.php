<?php
namespace Juekr\LittleLinker;

require_once "vendor/autoload.php";

$ll = new LittleLinker('config.yaml');

// $title = $ll->get_title("h1"); # $config->h1 ?? $domain;
// $meta_author = $ll->get_author(); # $config->meta_author ?? $title;

// $meta_title = $ll->get_title("meta"); #$config->meta_title ?? ($meta_author != $title ? $meta_author."'s LittleLink" : $title);

// $meta_description = $ll->get_description("meta"); #$config->meta_description ?? $meta_title;
// $tagline = $ll->get_description("tagline"); ## $config->tagline ?? $meta_description;

// $avatar = $ll->get_icon("avatar");
// $meta_favicon = $ll->get_icon("favicon"); #$config->meta_favicon ?? ($avatar ? empty($avatar) : "images/avatar.png");

// $meta_tags = $ll->get_tags(false);



?><!DOCTYPE html>
<!--
  To change the theme, change the class on the html tag below to one of:
  - theme-auto: Automatically switches based on user's system preferences
  - theme-light: Forces light theme
  - theme-dark: Forces dark theme
-->
<html class="theme-auto" lang="en"> <!-- Update`class="theme-auto" with your preference -->
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Page Title - Change this in config.yaml to your name/brand (50-60 characters recommended) -->
    <title><?= $ll->get_title("meta") ?></title>
    <link rel="icon" type="image/x-icon" href="<?= $ll->get_icon("favicon") ?>"> <!-- Update this with your own favicon -->

    <!-- Meta Description - Write a description in config.yaml (150-160 characters recommended) -->
    <meta name="description" content="<?= $ll->get_description("meta") ?>">

    <!-- Keywords (also defined in config.yaml) -->
    <meta name="keywords" content="<?= implode(", ", $ll->get_tags(false)) ?>">

    <!-- Canonical URL - Helps prevent duplicate content issues -->
    <meta rel="canonical" href="<?= $ll->get_url() ?>">

    <!-- Author Information -->
    <meta name="author" content="<?= $ll->get_author() ?>">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/brands.css">

    <link rel="stylesheet" href="fonts/font-awesome/css/all.min.css">

  </head>

<body>

    <div class="container">
        <div class="column">

            <!-- 
              By default, the Avatar is rounded. Use the following:
              - avatar--rounded: Automatically rounds the image
              - avatar--soft: Slightly rounds the image
              - avatar--none: Removes any rounding

              Be sure to replace the src with your own image path and update the alt text
            -->
            <img class="avatar avatar--rounded" src="images/avatar.png" srcset="<?= $ll->get_icon("favicon-2x") ?> 2x" alt="LittleLink">

            <!-- Replace with your name or brand -->
            <h1 tabindex="0">
              <div><?= $ll->get_title("h1") ?></div>
            </h1>

            <!-- Add a short description about yourself or your brand -->
            <p tabindex="0"><?= $ll->get_description("tagline") ?></p>

            <!-- All your buttons go here -->
            <div class="button-stack" role="navigation">

              <?php foreach ($ll->get_buttons() as $entry): 
                $button = (object)$entry; 
                $bicon = $button->icon ?? "images/icons/littlelink.svg";
                if (!file_exists($bicon)):
                  $bicon = $ll->get_icon_by_keyword($bicon);
                endif;
                ?>
                <!-- <?= $button->name ?? "untitled" ?> -->
                <a 
                  class="button <?= $ll->get_button_class($button) ?>" 
                  href="<?= $button->url ?? "#" ?>" 
                  target="<?= $button->target ?? "_blank" ?>" 
                  rel="noopener" 
                  role="button"
                  style="<?= $button->style ?? "" ?>"
                  >
                    <img 
                      class="icon " 
                      aria-hidden="true" 
                      src="<?= $bicon ?>" 
                      alt="<?= $button->name ?? "untitled" ?> Logo"
                    >
                    <?= $button->name ?? "untitled" ?>
                </a>
              <?php endforeach; ?>
              
      </div>
        
      <!-- Feel free to add your own footer information, including updating `privacy.html` to reflect how your LittleLink fork is set up -->
      <footer>
        <a href="privacy.html">Privacy Policy</a> | Build your own by forking <a href="https://littlelink.io" target="_blank" rel="noopener">LittleLink</a>
      </footer>
    
    </div>
  </div>

</body>

</html>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="format-detection" content="telephone=yes" />
    <meta name="HandheldFriendly" content="true" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Latest compiled and minified CSS -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="<?= NODE_MODULE ?>bootstrap/dist/css/bootstrap.min.css">

    <!-- style -->
    <link rel="stylesheet" href="<?= APP_STYLES ?>style.css">

    <title><?= $_ENV['APP_NAME'] ?></title>

    <!-- Favicon -->
    <meta name="msapplication-TileImage" content="<?= image('favicon.png') ?>"> <!-- Windows 8 -->
    <!--[if IE]><link rel="shortcut icon" href="<?= image('favicon.png') ?>"><![endif]-->
    <link rel="icon" type="image/png" href="<?= image('favicon.png') ?>">

</head>

<body>

<?php component('navbar'); ?>
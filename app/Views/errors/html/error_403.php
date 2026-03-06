<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>403 Forbidden</title>
    <style>
        div.logo {
            height: 200px;
            width: 155px;
            display: inline-block;
            opacity: 0.08;
            position: absolute;
            top: 2rem;
            left: 2rem;
            z-index: 0;
        }
        body {
            height: 100%;
            background: #fafafa;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            color: #777;
            font-weight: 300;
        }
        h1 {
            font-weight: lighter;
            letter-spacing: normal;
            font-size: 3rem;
            margin-top: 0;
            margin-bottom: 0;
            color: #222;
        }
        .wrap {
            max-width: 1024px;
            margin: 5rem auto;
            padding: 2rem;
            background: #fff;
            text-align: center;
            border: 1px solid #efefef;
            border-radius: 0.5rem;
            position: relative;
        }
        pre {
            white-space: normal;
            margin-top: 1.5rem;
        }
        code {
            background: #fafafa;
            border: 1px solid #efefef;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            display: block;
        }
        p {
            margin-top: 1.5rem;
        }
        .footer {
            margin-top: 2rem;
            border-top: 1px solid #efefef;
            padding: 1rem 2rem 0 2rem;
            font-size: 0.8rem;
            color: #999;
        }
        a:active,
        a:link,
        a:visited {
            color: #dd4814;
        }
    </style>
</head>
<body>
    <div class="wrap">
        <h1>403</h1>
        <p>Forbidden</p>
        <p>
            <?php if (isset($message) && ! empty($message) && $message !== '(null)') : ?>
                <?= esc($message) ?>
            <?php else : ?>
                Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.
            <?php endif ?>
        </p>
        <p><a href="<?= base_url() ?>">Kembali ke Beranda</a></p>
    </div>
</body>
</html>

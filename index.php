<!DOCTYPE html>
<html lang="pt-br">

<head>
    <!--Desenvolvido por Antonio Carlos -->
    <meta charset="UTF-8">
    <meta name="e-mail" content="">
    <meta name="keywords" content="">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="description" content="">
    <meta name="Abstract" content="">
    <meta name="author" content="antonio">
    <meta name="copyright" content="antonio">
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google-site-verification" content="colocar-o-codigo-de-verificação-google-aqui">
    <title>Line Stickers Downloader</title>
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
    <link rel="stylesheet" href="css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a href="index.php" class="navbar-brand"><span class="navbar-brand mb-0 h1"><span class="title">Line Stickers Downloader</span> // Static Mode</span></a>
            <button class="btn navbar-toggler text-dark" type="button" data-toggle="collapse" data-target="#navbar-site">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbar-site">
                <ul class="navbar-nav ml-auto">
                    <a target="_blank" href="https://store.line.me/stickershop/home/user/pt-BR" class="btn nav-link text-light btn-dark ols">Sticker Estáticos</a>
                    <a href="animado.php" class="btn nav-link text-light btn-dark">Versão para Sticker animados</a>
                </ul>
            </div>
        </nav>
        <div class="container">
            <div class="object">
                <div class="card">
                    <div class="card-body">
                        <form action="" method="POST">
                            <label for="se">Insira o URL da página aqui!</label>
                            <input type="text" name="ursite" id="se" class="form-control" placeholder="Ex: https://store.line.me/stickershop/product/9226317">
                            <div class="row text-center buto">
                                <div class="col-6">
                                    <input type="submit" name="postar" value="Procurar" class="btn btn-success btnact" onclick="loadanimation();" style="width:100%;">
                                </div>
                                <div class="col-6">
                                    <button <?php if(!isset($_GET['img'])) echo "disabled"; ?> name="baixar" class="btn btn-outline-primary btnact" style="width:100%;">Baixar tudo</button>
                                </div>
                            </div>
                        </form>
                        <div class="row text-center">
                            <div class="col-12">
                                <div id="load"></div>
                                <div id="msg" class="hide">Aguarde, carregando...</div>
                            </div>
                        </div>
                        <?php
                            if (isset($_GET['img'])) {
                                $id = $_GET['img'];
                                
                                if (isset($_GET['qt']))
                                    $qt = $_GET['qt'];
                                if (isset($_GET['id_page']))
                                    $id_page = $_GET['id_page'];

                                $pasta = 'images/'.$id_page;

                                if (isset($_POST['baixar'])) {
                                    if (!file_exists('zips/'))
                                        mkdir('zips');

                                    if (!file_exists('zips/'.$id_page.'.zip')) {
                                        $realCaminho = realpath($pasta);
                                        $zip = new ZipArchive();
                                        $zip->open('zips/'.$id_page.'.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);
                                        $files = new RecursiveIteratorIterator(
                                            new RecursiveDirectoryIterator($realCaminho),
                                            RecursiveIteratorIterator::LEAVES_ONLY
                                        );

                                        foreach ($files as $useless => $file) {
                                            if (!$file->isDir()) {
                                                $filePath = $file->getRealPath();
                                                $relativePath = substr($filePath, strlen($realCaminho) + 1);
                                                $zip->addFile($filePath, $relativePath);
                                            }
                                        }
                                        $zip->close();
                                    }
                                    header("Location: download.php?id_page=$id_page");
                                }
                            }
                            ini_set('display_errors',0);
                            if (isset($_POST['postar'])) {
                                $site = $_POST['ursite'];
                                if (!empty($site))
                                    if (copy($site, 'tempsite.txt')) {
                                        $pagina = file_get_contents('tempsite.txt');
                                        $pagina = htmlspecialchars($pagina);

                                        if (preg_match('/og:url&quot;\scontent=&quot;https:\/\/store\.line\.me\/stickershop\/product\/[0-9]+\//', $pagina, $id_page) > 0 ) {
                                            $id_pages = $id_page[0];
                                            $id_pages = str_replace("og:url&quot;", "", $id_pages);
                                            $id_pages = str_replace("content=&quot;https://store.line.me/stickershop/product/", "", $id_pages);
                                            $id_pages = str_replace("/", "", $id_pages);
                                            $id_pages = str_replace(" ", "", $id_pages);
                                        }                                        

                                        if (preg_match('/https:\/\/stickershop\.line-scdn\.net\/stickershop\/v1\/sticker\/[0-9]+\/iPhone\/sticker@2x\.png/', $pagina, $combina) > 0 ) {
                                            $imagem = $combina[0];
                                            $imagem = str_replace("https://stickershop.line-scdn.net/stickershop/v1/sticker/", "", $imagem);
                                            $imagem = str_replace("/iPhone/sticker@2x.png", "", $imagem);
                                            unlink('tempsite.txt');
                                            $qt = preg_match_all('/FnStickerPreviewItem/', $pagina, $useless);
                                            header("location: index.php?img=$imagem&qt=$qt&id_page=$id_pages");
                                        } else {
                                            unlink('tempsite.txt');
                                            echo "<div style=margin-top:15px;>Stickers não encontrados, veja se digitou um <span style=text-decoration:underline;cursor:pointer; title='Exemplo: https://store.line.me/stickershop/product/9226317' onclick='tip(2)'>link válido...</span></div>";
                                        }
                                    } else
                                        echo "<div style=margin-top:15px;>O campo precisa ser um link!</div>";
                                else
                                    echo "<div style=margin-top:15px;>Preencha o campo!</div>";
                            }

                            if (isset($_GET['img'])) {
                                for ($i = 0; $i < $qt; $i++) {
                                    $url = "https://stickershop.line-scdn.net/stickershop/v1/sticker/".$id."/iPhone/sticker@2x.png";
                                    $p = $i % 4;
                                    if ($p == 0 && $i == 0)
                                        echo "<div class='row ftr'>";
                                    if ($p == 0 && $i > 0)
                                        echo "</div><div class=row>";
                                    echo "<div class='col-3 imgc'>";
                                    echo "<img class=imgsti src=$url>";
                                    echo "</div>";
                                    $pasta = 'images/'.$id_page;
                                    if (!file_exists($pasta))
                                        mkdir($pasta);
                                    $nome = $id.".png";
                                    $arquivo = $pasta."/".$id_page."_".$nome;
                                    if (!file_exists($arquivo))
                                        copy($url, $arquivo);
                                    $id++;
                                }
                                echo "</div>";
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="functions.js"></script>
</body>
</html>
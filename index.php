

<?php
if(isset($_GET['q'])){

            // VARIABLE
            $shortcut = htmlspecialchars($_GET['q']);

            // IS A SHORTCUT

            $bdd = new PDO('mysql:host=localhost;dbname=bitly;charset=utf8','root','');
            $req = $bdd->prepare('SELECT COUNT(*) AS x FROM links WHERE shortcut = ?');
            $req->execute(array($shortcut));

            while($resul = $req->fetch()){
                if ($result["x"] !=1 ){

                    header('location:../?error=true&message=Adresse url non connu');
                }
            }
            
                // REDIRECTION
                $req = $bdd->prepare('SELECT * FROM links WHERE shortcut=?');
                $req->execute(array($shortcut));

                while($result = $req->fetch()) {
                    header('location: '.$result['url']);
                    exit();
                }

}

?>

<?php
   if(isset($_POST['url'])) {  // si url existe
        
    //VARIABLE
    $url= $_POST['url'];

    // vérifier si c'est bien une url valide

    if(!filter_var($url, FILTER_VALIDATE_URL)){
        // pas un lien
        header('location:../?error=true&message=Adresse url non valide');
        exit();
    }


    // SHORTCUT (creer un raccourci)

    $shortcut = crypt($url , rand());


    // has been already send ? vérifier si l'url a deja été proposé

    $bdd = new PDO('mysql:host=localhost;dbname=bitly;charset=utf8','root','');
    
    $req = $bdd->prepare('SELECT COUNT(*) AS x FROM links WHERE url=?');
    $req->execute(array($url));

    while($result = $req->fetch()){
        if($result['x'] != 0){
            header('location:../?error=true&message=Adresse déja raccourcie');
            exit();
        }

    }

    // SENDING ? SI TOUT EST BON ALORS ENVOYER

    $req = $bdd->prepare('INSERT INTO links(url,shortcut) VALUES (?,?)');
    $req->execute(array($url,$shortcut)) ;

    header('location:../?short='.$shortcut);

    exit();

   }
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raccourcisseur d'url express</title>
    <link rel ="stylesheet" type="text/css" href="design/default.css">
    <link rel="icon" type="image/png" href="pictures/favico.png">
</head>
<body>
    <section id="hello">
        <div class="container">
            <header>
                <img src="../pictures/logo.png" alt="logo" id="logo">
            </header>
            <h1>une url longue?Raccourcissez-là</h1>
            <h2>Largement meilleur et plus courte que les autres.</h2>
            <form method="post" action="../">
                <input type="url" name="url" placeholder="copier votre lien ici"> 
                <input type="submit" value="Raccourcir">
            </form>

            <?php if(isset($_GET["error"]) && isset($_GET["message"])) { ?>
                      <div  class="center">
                            <div id="result">
                                   <b> <?php echo htmlspecialchars($_GET["message"]); ?> </b>
                            </div>
                      </div>

            <?php } else if(isset($_GET["short"])){?>
                <div  class="center">
                            <div id="result">
                                <b>URL RACCOURCIE</b>
                                   http://localhost/?q=<?php echo htmlspecialchars($_GET["short"]); ?>
                            </div>
                      </div>
            <?php } ?>

            
                    

        </div>
    </section>
    <section id="brands">
            <div class="container">
                    <h3>Ces marques nous font confiance</h3>
                    <img src="../pictures/1.png" alt="1" class="picture">
                    <img src="../pictures/2.png" alt="2" class="picture">
                    <img src="../pictures/3.png" alt="3" class="picture">
                    <img src="../pictures/4.png" alt="4" class="picture">
            </div>
            
    </section>
    <section id="footer">
        <div class="container">
                <img src="../pictures/logo2.png" alt="logo2" class="picture">
                    <h4>2018 © Bilty</h4>
                    <a href="#">Contact -</a>
                    <a href="#">A propos</a>
                
        </div>

    </section>
    
</body>
</html>
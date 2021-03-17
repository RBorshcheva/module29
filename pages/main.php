<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="data/styles/style.css">
  </head>
 <body>
      <div class="container menu">
        <div class="row menu">
          <div class="col-12"><hr>
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
              <div class="navbar-nav">     
                <?php if($authorised):?>
                <a class="nav-item nav-link active" href="?page=1">Main<span class="sr-only">(current)</span></a>
                <a class="nav-item nav-link active" href="?page=4" ?>Log out</a>
                <?php endif;?>  
                <?php if(!$authorised):?>
                <a class="nav-item nav-link active" href="?page=1">Main<span class="sr-only">(current)</span></a>
                <a class="nav-item nav-link active" href="?page=2">Sign up</a>
                <a class="nav-item nav-link active" href="?page=3">Sign in</a>
                <?php endif;?>               
              </div>
            </div>
            </nav>
          </div>
        </div>
      </div>
        <?php if($authorised):?>
          <p><b>Here is your page:</b>
          
        <?php endif;?> 
      <div>
      <?php if($vk):?>
        <p><img src="images/user.jpg"></p>
      <?php endif;?> 
      </div>
      <div>
      </div>

    </body>
</html>
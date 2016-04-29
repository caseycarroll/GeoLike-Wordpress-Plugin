<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
    <script src="https://cdn.firebase.com/js/client/2.4.2/firebase.js"></script>
    <script language="JavaScript" src="http://www.geoplugin.net/javascript.gp" type="text/javascript"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.1.3/material.indigo-pink.min.css">
    <script defer src="https://code.getmdl.io/1.1.3/material.min.js"></script>
    <style>

    body {
      /*width: 100%;
      height: 500px;
      background: grey;*/
    }

    .wrapper {
      display: inline;
    }

    #like-bttn {
      width: 200px;
      margin-left: 32px;
    }

    #like_bttn:hover > #num-likes {
      visibility: visible;
    }

    #like_bttn:hover > #num-likes-text {
      visibility: visible;
    }

    #num-likes {
      visibility: hidden;
    }

    #num-likes-text {
      margin-left: 8px;
      visibility: hidden;
    }

    #spinning-loader {
      display: none;
    }

    </style>
  </head>
  <body>

    <?php
      global $product;
      $title = $product->get_title();
     ?>
    <div class="wrapper">
      <!-- Colored FAB button with ripple -->
      <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" id="like-bttn">
        <i class="material-icons" id="add-icon">favorite</i>
        <div class="mdl-spinner mdl-spinner--single-color mdl-js-spinner is-active" id="spinning-loader"></div>
        <i id="num-likes"></i>
      </button>
      <span id="num-likes-text">Anonymous GeoLikes</span>
    </div>


    <script type="text/javascript">
    /*
    *Set up the following variables...
    * @param heartBtn - Button that user clicks to show they liked the product.
    * @param prodID - product ID or post ID
    * @param baseRef - url to Firebase
    * @param productRef - Child that points to printer array in Firebase
    */
      var heartBtn = document.getElementById('like-bttn'),
          likeCounter = document.getElementById('num-likes'),
          likeCounterText = document.getElementById('num-likes-text'),
          spinLoader = document.getElementById('spinning-loader'),
          addIcon = document.getElementById('add-icon'),
          prodID = '<?php echo $title;?>';
          baseRef = new Firebase('https://pedijava.firebaseio.com/products');
          productRef = baseRef.child(prodID);

      document.addEventListener('DOMContentLoaded', function () {
          console.log('here');
          heartBtn = document.getElementById('like-bttn');
          heartBtn.addEventListener('click', likeHandler);
      });

          //get product name or id

      function likeHandler() {
        spinLoader.style.display = "block";
        //get location through geoPlugin
        let client_city = geoplugin_city();
        let client_long = geoplugin_longitude();
        let client_lat = geoplugin_latitude();

        //some console logs to make sure everything's in order
        console.log('Client City: ',client_city);
        console.log('ID of the Product: ', prodID);

        //save the data to firebase
        let newLike = productRef.push();
        newLike.set({
          city: client_city,
          long: client_long,
          lat: client_lat
        });

        productRef.once("value", function(snapshot) {
          let numLikes = snapshot.numChildren();
          likeCounter.innerHTML = numLikes;
        });

        //make num likes visible
        spinLoader.style.display = "none";
        likeCounter.style.visibility = "visible";
        likeCounterText.style.visibility = "visible";
        addIcon.style.display = "none";

        //disable the button
        heartBtn.disabled = true;
      }
    </script>
  </body>
</html>

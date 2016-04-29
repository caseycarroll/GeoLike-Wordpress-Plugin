<!DOCTYPE html>
<html>
  <head>
    <title>GeoLike Stats</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.1.3/material.indigo-pink.min.css">
    <script defer src="https://code.getmdl.io/1.1.3/material.min.js"></script>
    <script src="https://cdn.firebase.com/js/client/2.4.2/firebase.js"></script>
    <style>
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
        display: inline;
      }
      #map {
        height: 100vh;
        width: 100%;
        background: grey;
      }
      .demo-card-wide.mdl-card {
        width: 512px;
        position: fixed;
        top: 64px;
        right: 32px;
      }
      .demo-card-wide > .mdl-card__title {
        color: #fff;
        height: 176px;
        background: url('../wp-content/plugins/FB_Like_Bttn/templates/assets/logo_header.png') center / cover;
        /*background: url('logo_header.png') center / cover;*/
      }
      .demo-card-wide > .mdl-card__menu {
        color: #fff;
      }

      #likes-table {
        width: 100%;
        overflow: auto;
      }
    </style>
  </head>
  <body>

    <!-- map shows where each like came from -->
    <div id="map">
      <span>what</span>
    </div>
    <!-- Wide card with share menu button -->
    <div class="demo-card-wide mdl-card mdl-shadow--2dp">
      <div class="mdl-card__title">
        <h2 class="mdl-card__title-text">GeoLikes</h2>
      </div>
      <!-- show a table of likes -->
      <!-- <div class="mdl-card__supporting-text"> -->
        <table class="mdl-data-table mdl-js-data-table  mdl-shadow--2dp" id="likes-table">
          <thead>
            <tr>
              <th class="mdl-data-table__cell--non-numeric">Products</th>
              <th># of Likes</th>
              <th>Latest City</th>
            </tr>
          </thead>
          <tbody id="table-body">
          </tbody>
        </table>
      <!-- </div> -->
      <div class="mdl-card__menu">
        <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
          <i class="material-icons">share</i>
        </button>
      </div>
    </div>
    <script>
      var map;
      function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: 33.2148, lng: -97.1331},
          zoom: 8
        });
      }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCsvr7RvvFVeSkUWofCW0O-zaSt1ogD1VQ&callback=initMap"
    async defer></script>

    <script type="text/javascript">
      var FbBaseRef = new Firebase('https://pedijava.firebaseio.com/');
      var productsRef = FbBaseRef.child('products');
      var likesTable = document.getElementById("likes-table");
      var likesTableBody = document.getElementById("table-body");

      productsRef.on("child_added", function(snapshot) {
        // snapshot.forEach(function(childSnapshot) {
          let product = snapshot.val();
          var numLikes = snapshot.numChildren();
          var popCity;

          // insert row at the end of table
          var row = likesTable.insertRow(-1);

          //insert cells
          let nameCell = row.insertCell(0);
          let numLikesCell = row.insertCell(1);
          let popCityCell = row.insertCell(2);

          //insert values
          nameCell.innerHTML = snapshot.key();
          numLikesCell.innerHTML = numLikes;

          snapshot.forEach(function(likeSnapshot) {
            let likeLat = likeSnapshot.val().lat;
            let likeLong = likeSnapshot.val().long;
            console.log(likeLat);

            popCity = likeSnapshot.val().city;

            let likeLatLng = new google.maps.LatLng(likeLat, likeLong);
            let marker = new google.maps.Marker({
              position: likeLatLng,
              title: snapshot.key()
            });

            marker.setMap(map);
         });

        //  popCityCell.innerHTML = popCity;
        popCityCell.innerHTML = (popCity || "Undetermined");
      });
    </script>
  </body>
</html>

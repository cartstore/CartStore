<html>
   <head>
	  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
      <title>Chargement en cours, veuillez patienter ...</title>
      <script src="http://api.viamichelin.com/apijs/js/api.js" 
         type="text/javascript"></script>
      <script type="text/javascript">
         /* InsÃ©rez ici votre clÃ© */
         VMAPI.registerKey("JSGP20080825164743459989086949");
         VMAPI.setLanguage("fra");
         
         /* Transformer l'adresse de départ, donnée par l'utilisateur, en un point géocodage */
         function search_start_location(){
            Start_geocoder = new VMGeocoder();
            Start_myaddress = new VMAddress();
			Start_myaddress.address = '<?php echo $_GET['mag_rue']; ?>';
            Start_myaddress.zipCode = '<?php echo $_GET['mag_cp']; ?>';
            Start_myaddress.city = '<?php echo $_GET['mag_ville']; ?>';
            Start_myaddress.country = '<?php echo $_GET['mag_pays']; ?>';
            
            
            Start_geocoder.addEventHandler("onCallBack",search_stop_location);
            Start_geocoder.search(Start_myaddress);
         }
          /* Transformer l'adresse d'arrivée, donnée par l'utilisateur, en un point géocodage */
         function search_stop_location(){
            Stop_geocoder = new VMGeocoder();
            Stop_myaddress = new VMAddress();    
            Stop_myaddress.address = '<?php echo $_GET['rue']; ?>';
            Stop_myaddress.zipCode = '<?php echo $_GET['cp']; ?>';
            Stop_myaddress.city = '<?php echo $_GET['ville']; ?>';
            Stop_myaddress.country = '<?php echo $_GET['pays']; ?>';
            
            
            Stop_geocoder.addEventHandler("onCallBack",search_iti);
            Stop_geocoder.search(Stop_myaddress);
         }

         
         /* Configurer la recherche d'itinéraire */
         function search_iti(){
            myiti = new VMItinerary();
            myiti.addStopOver(Start_geocoder.result);

            myiti.addStopOver(Stop_geocoder.result);      
            
            myiti.addEventHandler("onCallBack",iti_found);
            myiti.search();
         }
       
         function iti_found(){
            strHTML = myiti.getTotalDistance();
			document.location.href="checkout_shipping.php?distance=" + strHTML;
         }

      </script>
	  <link rel="stylesheet" type="text/css" href="stylesheet.css">
   </head>
   
   <!-- Lancement automatique du script javascript après le chargement de la page -->
   <body onLoad="search_start_location();">
	Chargement en cours, veuillez patienter...
   </body> 
</html>
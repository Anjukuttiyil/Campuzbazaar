<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
       
        <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">

    <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.css">

    <link rel="stylesheet" href="assets/css/templatemo-training-studio.css">

         <style>
       
             body,html{
                
                
               background-image: url("img5.jpg");
                
                color:white;
                
            }
   
   


.pos{
    
    position:relative;
   top:150px;
  
}
.a1{color:white;
    background-color:blue;
    
   }
.button-10 {
  display: flex;
  flex-direction: column;
  padding: 6px 14px;
  font-family: -apple-system, BlinkMacSystemFont, 'Roboto', sans-serif;
  border-radius: 6px;
  border: none;
 position: relative;
  color: #fff;
   left: 130px;
  background: linear-gradient(180deg, #4B91F7 0%, #367AF6 100%);
   background-origin: border-box;
  box-shadow: 0px 0.5px 1.5px rgba(54, 122, 246, 0.25), inset 0px 0.8px 0px -0.25px rgba(255, 255, 255, 0.2);
  user-select: none;
  -webkit-user-select: none;
  touch-action: manipulation;
}

.button-10:focus {
  box-shadow: inset 0px 0.8px 0px -0.25px rgba(255, 255, 255, 0.2), 0px 0.5px 1.5px rgba(54, 122, 246, 0.25), 0px 0px 0px 3.5px rgba(58, 108, 217, 0.5);
  outline: 0;
}

  

       .register-photo {
  
;
  padding:150px 0;
   min-height: 100vh;
    position:relative;
    top:70px;
       
}



.register-photo .form-container {
  display:table;
  margin:0 auto;
  box-shadow:1px 1px 5px rgba(0,0,0,0.1);
 
}
              
                
           

.register-photo form {
  display:table-cell;
  
  
  padding:40px 60px;
  color:#505e6c;
   border: 3px solid #fff;
   max-width:500px;
   width:80%;
}



.register-photo form h2 {
  font-size:24px;
  line-height:1.5;
  margin-bottom:30px;
}


.register-photo form .form-check {
  font-size:13px;
  line-height:20px;
}

.register-photo form .btn-primary {
  background:#f4476b;
  border:none;
  border-radius:4px;
  padding:11px;
  box-shadow:none;
  margin-top:35px;
  text-shadow:none;
  outline:none !important;
}
.pos1{
    
    position:relative;
    top:80px;
    
    
}
.register-photo form .btn-primary:hover, .register-photo form .btn-primary:active {
  background:#eb3b60;
}

.register-photo form .btn-primary:active {
  transform:translateY(1px);
}

.register-photo form .already {
  display:block;
  text-align:center;
  font-size:12px;
  color:#6f7a85;
  opacity:0.9;
  text-decoration:none;
}
  
            .button {
  background-color: black;
  border: none;
  color: white;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
  border-radius: 25px; 
  
}

.but{
    
     position: absolute;
   
    left:100px;
    
    
    
}
     .pos1{
  position: relative;
  
  top:150px;
     }
        </style>
    </head><!-- comment -->
    <body>
       
     <header class="header-area header-sticky">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="main-nav">
                        <!-- ***** Logo Start ***** -->
                        <a href="index.html" class="logo">Training<em> Studio</em></a>
                        <!-- ***** Logo End ***** -->
                        <!-- ***** Menu Start ***** -->
                        <ul class="nav">
                            <li class="scroll-to-section"><a href="usermenu.php" class="active">Menu</a></li>
                            <li class="scroll-to-section"><a href="gymselection.php">Back</a></li>
                        
                        </ul>        
                        <a class='menu-trigger'>
                            <span>Menu</span>
                        </a>
                        <!-- ***** Menu End ***** -->
                    </nav>
                </div>
            </div>
        </div>
    </header>
       

           <center>
          <div class="register-photo">
               <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
        <form>
      <div class="form-group">   
          <label for="batch">select Batch</label>
    <select class="form-control" id="batch" name="batch">
        <?php include("config.php");
       
      
        $sql = "SELECT * FROM gymbatches where users='$user'";
        $result = mysqli_query($conn, $sql);

        while ($rows = $result->fetch_assoc()) {
    ?>
            
     
             <option value="<?php echo $rows['Batchname']; ?>"><?php  echo $rows['starttime'];echo"-"; echo $rows['endtime']; ?></option>
                      
            
                
              
               <?php
                  }
                ?>
     
 
     
    </select>
  <br><br></div>
   <div class="form-group">   
    <label for="wrkout"> select workout type</label>
    <select class="form-control"  name="wrkout" id="wrkout">
      <option value="Weightloss">Weightloss</option>
  <option value="Mass gain">Mass gain</option>    
    </select>
  <br><br></div>
        <div class="form-group">
           <label for="name">Last workout bodypart</label>
  <input type="textbox" class="form-control" name="bodypart" id="bodypart" placeholder="Last workout bodypart"/><br/><br/></div>  
   
    <input type="button" class="btn btn-primary btn-block" name="btn" id="btn" value="Pay Now" onclick="pay_now()"/>
      
        </form>

      </div>
 
    <script src="assets/js/jquery-2.1.0.min.js"></script>

    <!-- Bootstrap -->
    <script src="assets/js/popper.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>

    <!-- Plugins -->
    <script src="assets/js/scrollreveal.min.js"></script>
    <script src="assets/js/waypoints.min.js"></script>
    <script src="assets/js/jquery.counterup.min.js"></script>
    <script src="assets/js/imgfix.min.js"></script> 
    <script src="assets/js/mixitup.js"></script> 
    <script src="assets/js/accordions.js"></script>
    
    <!-- Global Init -->
    <script src="assets/js/custom.js"></script>

<script>
    function pay_now(){
        var wrkout= jQuery('#wrkout').val();
       var name="alan";
        var amt=100;
         var bodypart= jQuery('#bodypart').val();
            var batch= jQuery('#batch').val();
        
        alert(bodypart);
        jQuery.ajax({
               type:'post',
               url:'payment_process.php',
               data:"amt="+amt+"&name="+name+"&wrkout="+wrkout+"&bodypart="+bodypart+"&batch="+batch,
               success:function(result){
                   var options = {
                       
                        "key": "rzp_test_uGeI4kIzxIZHZA", 
                        "amount": amt*100, 
                        "currency": "INR",
                        "name": "Acme Corp",
                        "description": "Test Transaction",
                        "image": "https://image.freepik.com/free-vector/logo-sample-text_355-558.jpg",
                        "handler": function (response){
                           jQuery.ajax({
                               type:'post',
                               url:'payment_process.php',
                               data:"payment_id="+response.razorpay_payment_id,
                               success:function(result){
                                   window.location.href="thank_you.php";
                               }
                           });
                        }
                    };
                    var rzp1 = new Razorpay(options);
                    rzp1.open();
               }
           });
        
        
    }
</script>
    </body>

</html>

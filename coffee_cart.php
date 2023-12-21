<?php 
// check if the add to basket button has been pressed 
// check if a session for a shopping basket exists (if not create one)
// output the data to the webpage (for now) to prove the button works
// create little function (pre_r) just to show that we have stored something in an array

  session_start();
  $product_ids = array(); //empty array
  //session_destroy(); // make sure the session is empty

  // check if the add button has been pressed
  if(filter_input(INPUT_POST,'add_to_basket')){
    if(isset($_SESSION{'shopping_basket'})){
      // the shopping basket session does exist

      $count = count($_SESSION['shopping_basket']);
      $product_ids = array_column($_SESSION['shopping_basket'],'id');
      //pre_r($product_ids);

      if(!in_array(filter_input(INPUT_GET,'id'),$product_ids)){
        $_SESSION['shopping_basket'][$count] = array
        (
          'id' => filter_input(INPUT_GET, 'id'),
          'name' => filter_input(INPUT_POST, 'name'),
          'price' => filter_input(INPUT_POST, 'price'),
          'quantity' => filter_input(INPUT_POST, 'quantity')
        );
      }
      else{
        // if the product exists just increase the quantity
        // don't read the item everytime
        for($i = 0;$i<count($product_ids);$i++){ // loops through all the elements in the product_id array
            if($product_ids[$i] == filter_input(INPUT_GET, 'id')){ // if the ID matches
              $_SESSION['shopping_basket'][$i]['quantity'] += filter_input(INPUT_POST,'quantity'); // add onto the quantity
            }
            
        }
      }

    }
    else{
      // if the shopping basket does not exist then make one 
      $_SESSION['shopping_basket'][0] = array
      (
        'id' => filter_input(INPUT_GET, 'id'),
        'name' => filter_input(INPUT_POST, 'name'),
        'price' => filter_input(INPUT_POST, 'price'),
        'quantity' => filter_input(INPUT_POST, 'quantity')
      );
    }
  }
  if(filter_input(INPUT_GET, 'action')== 'delete'){
    // loop through all the products until it matches with GET id variable 
    foreach($_SESSION['shopping_basket'] as $key => $product){ // loop through all the items in the product array
      if($product['id']== filter_input(INPUT_GET,'id')){
        // remove the product from the shopping basket when the IDs match
        unset($_SESSION['shopping_basket'][$key]);
      }
    }
    $_SESSION['shopping_basket'] = array_values($_SESSION['shopping_basket']); // passes the temp array over to the main array
  }



  //pre_r($_SESSION);
    function pre_r($array){
    echo '<pre>';
    print_r($array);
    echo '</pre>';
  }
?>


<!doctype html>
<html lang="en">

<head>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bean & Brew</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link rel="stylesheet" href="syle.css">
</head>

<body>
  <!-- This is the Navigation BAR -->
  <nav class="navbar navbar-expand-lg bg-warning bg-body-warning fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="#"><img src="img/CoffeeLogo.png" width="37" height="37" alt="Home"></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
        aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="index.html">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Features</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Pricing</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Pre-Order
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#">Coffee</a></li>
              <li><a class="dropdown-item" href="#">Hamper</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>
   <!-- This is section 1 Finished here-->
   <section class="bg-dark text-light p-5 text-center text-sm-start">
        <div class="container">
            <div class="row text-center g-4">
                <h2 class="text-light">Order your Coffee Here</h2>
                <h5 class="text-light">We have a fantastic range of coffees using beans harvested from all around the world. We specialise in carbon neutral fair trade growers</h5>

                <?php 
                    // connecting to the database
                    $connect = mysqli_connect('localhost','root','','websitedb');

                    $query = 'SELECT * FROM products ORDER by id ASC';

                    // run the query
                    $result = mysqli_query($connect,$query);

                    // output the result
                    if($result){
                        if(mysqli_num_rows($result)>0){
                            while($product = mysqli_fetch_assoc($result)){
                                
                                // Now we put in the HTML code for the cards
                                ?>
                                    <div class = "col-md-6 col-lg-3">
                                        <form action="coffee_cart.php?action=add&id=<?php echo $product['id'];?>" method="post">
                                            <div class="products">
                                                <div class="card bh-light">
                                                    <div class="card-body text-center">
                                                        <img src="<?php echo $product['image'];?>" class="img-responsive img-fluid">
                                                        <h4 class="text-warning"><?php echo $product['name']; ?></h4>
                                                        <h4 class="text-warning">£<?php echo $product['price']; ?></h4>
                                                        <input type="number" name="quantity".class="form-control mb-2" value="1" min="1" require>
                                                        <input type="hidden" name="name" value="<?php echo $product['name']; ?>">
                                                        <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
                                                        <input type="submit" name="add_to_basket" class="btn btn-warning mt-3" value="Add to Basket">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                <?php
                            }
                        }
                    }
                ?>


            </div>
        </div>
      </section>
<!-- Building the Basket -->
<section class="p-5 bg-dark">
  <div class="container text-light">
    <div class="table-responsive">
      <table class="table text-light lead">
        <tr>
          <th colspan="5"><h2>Order Details</h2></th>
        </tr>
        <tr>
          <th width="40%">Product Name</th>
          <th width="10%">Quantity</th>
          <th width="20%">Price</th>
          <th width="15%">Total</th>
          <th width="5%">Actions</th>        
        </tr>
        
        <!-- php code goes here -->
        <?php 
          if(!empty($_SESSION['shopping_basket'])):
            $total = 0;

            foreach($_SESSION['shopping_basket'] as $key => $product):
        ?>
            <tr>
              <td><?php echo $product['name']; ?></td>
              <td><?php echo $product['quantity']; ?></td>
              <td>£<?php echo $product['price']; ?></td>
              <td>£<?php echo number_format($product['quantity'] * $product['price'],2); ?></td>
              <td>
                <a href="coffee_cart.php?action=delete&id=<?php echo $product['id'] ?>">
                  <div class="btn btn-danger">Remove</div>              
                </a>
              </td>
            </tr>
            <?php 
              $total = $total + ($product['quantity'] * $product['price']); // grand total for all products
            endforeach;            
            ?>
            <tr>
              <td colspan="3">Total</td>
              <td>£<?php echo number_format($total,2); ?></td>
              <td></td>
            </tr>
            <!-- Checkout button -->
            <tr>
              <td colspan="5" class="text-end">
                <?php 
                  if(isset($_SESSION['shopping_basket'])):
                    if(count($_SESSION['shopping_basket'])>0):
                ?>
                <a href="#" class="btn btn-success">Checkout</a>
                <?php endif; endif; ?>
              </td>

            </tr>
            <?php endif; ?>
      </table>
    </div>
  </div>
</section>
<!-- Section 8 | Copyright-->
<section class="bg-dark text-light p-4">
              <div class="container">
                <div class="text-center">

                  <div>
                    <p class="lead"> <strong> Copyright <i class="bi bi-c-circle"></i>  2023</strong></p>
                  </div>

                </div>
              </div>
      </section>
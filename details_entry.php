<?php 
    // code goes between these tags

    // variables in php
    $server_name = "localhost";
    $username = "root";
    $password = "";
    $database_name = "websitedb";

    // sql connection - it has 4 parameters
    $conn = mysqli_connect($server_name,$username,$password,$database_name);

    // now check the connection
    if(!$conn)
        {
            die("connection failed".mysqli_connect());
        }
    
    // echo 
    // echo "hello";

    // if the subscirbe button is pressed
    if(isset($_POST["subscribe"]))
        {
            // take the text out of the email box and puts it in a variable 
            $email = $_POST["email"];

            // write the sql code 
            $sql_query = "INSERT INTO subscribe (email) VALUES ('$email')";

            // comit it to the DB 
            if(mysqli_query($conn, $sql_query))
                {
                    include "index.html"
                ?> <!-- PHP ENDED (TEMP) -->
                    <script type="text/javascript">
                            Swal.fire("You Have Now Subcribed!");
                    </script>
<?php 
                }
            else
                {
                    echo "ERROR";
                }
        }

    if(isset($_POST["table_bookings"]))
    {
        $first_name = $_POST["first-name"];
        $surname = $_POST["surname"];
        $email = $_POST["email"];
        $phone_number = $_POST["phone_number"];
        $location = $_POST["cafe_choice"];
        $number_of_people = $_POST["number_ppl"];
        $time = $_POST["booking_time"];
        $date_of_collection = $_POST["booking_data"];
        

        $sql_query = "INSERT INTO table_bookings (booking_data, booking_time, cafe_choice, email, first_name, number_ppl, phone, surname) VALUES ('$date_of_collection','$time','$location','$email','$first_name','$number_of_people','$phone_number','$surname')";

     

        if(mysqli_query($conn, $sql_query))
            {
                include "index.html"
                ?> <!-- PHP ENDED (TEMP) -->
                    <script type="text/javascript">
                            Swal.fire({
                            title: "booking success",
                            text: "<?php echo "$first_name" ?> your booking has been accepted",
                            icon: "success"
                            });
                    </script>
<?php 
            }
        else
            {
                include "index.html"
                ?> <!-- PHP ENDED (TEMP) -->
                    <script type="text/javascript">
                            Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Something went wrong!",
                            footer: '<a href="#">Why do I have this issue?</a>'
                            });
                    </script>
<?php 
            }
    }
?>



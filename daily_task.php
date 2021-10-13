<?php
require_once "vendor/autoload.php";
use Twilio\Rest\Client;
$database = mysqli_connect('hcm4e9frmbwfez47.cbetxkdyhwsb.us-east-1.rds.amazonaws.com', 'i2mwrf74ip9zubek', 'p0yzpu5xyq4ybqjr', 'qt4nvdslegbdc6h6');
$phoneNumber = $_GET['phoneNumber'];
date_default_timezone_set('America/Los_Angeles');

$select_user_id = "SELECT user_id FROM user WHERE phone_number = $phoneNumber";
error_log("Executing query: " . $select_user_id);
error_log("Mysql error for query: " . mysqli_error($database));
$select_result = mysqli_query($database, $select_user_id);
$row = mysqli_fetch_assoc($select_result);
$user_id = $row['user_id'];

$info = "SELECT user.activated FROM user WHERE user.phone_number = $phoneNumber";
$info_result = mysqli_query($database, $info);
$info_row = mysqli_fetch_assoc($info_result);
$activated = $info_row['activated'];

if ($activated == 'N') {
    header('location: not_registered.html');
}

    if(isset($_POST['submit'])) {
        
        $taskname = $_POST['taskname'];
        $task_remindTime_hour = $_POST['remind_endtime_hour'];
        $task_remindTime_minute = $_POST['remind_endtime_minute'];
        $task_remindTime_AM_PM = $_POST['remind_endtime_AM_PM'];
        $date = $_POST['date'];
        
        foreach($taskname AS $key => $value) {

            $remind_endtime_str = $task_remindTime_hour[$key] . ':' . $task_remindTime_minute[$key];
            if (strcasecmp($task_remindTime_AM_PM[$key], 'AM') == 0) {
                $remind_endtime_str_after_math = $task_remindTime_hour[$key] + 0;
            } else {
                $remind_endtime_str_after_math = $task_remindTime_hour[$key] + 12;
            }

            $remind_endtime = $remind_endtime_str_after_math . ':' . $task_remindTime_minute[$key];
            $date[$key] = strtotime($date[$key]);
            error_log("This is the date converted into epoch: " . $date[$key]);

            $insert_task_query = "INSERT INTO user_task(user_id,task,reminder_time,date) VALUES ('$user_id', '$value', '$remind_endtime', '$date[$key]')";
            error_log("Executing query: " . $insert_task_query);
            error_log("Mysql error for query: " . mysqli_error($database));
            $insert_task_query_result = mysqli_query($database, $insert_task_query);
        }

        header('location: thankyou_task.html');

        // Your Account SID and Auth Token from twilio.com/console
        $account_sid = 'AC848ed57ccc93438c2fea0bce283b9a50';
        $auth_token = '6e61c24a8c1c7e6b523bfa70b3018256';

        // A Twilio number you own with SMS capabilities
        $twilio_number = "+18302545295";

        $client = new Client($account_sid, $auth_token);
        $client->messages->create(
            // Where to send a text message (your cell phone?)
            $phoneNumber,
            array(
                'from' => $twilio_number,
                'body' => "Tasks received. I will remind you about the tasks at the given time. Reply TASKS to view upcoming tasks."
            )
        );
    }
?>

<!DOCTYPE html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <title>PocketPlan - Input Tasks</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    </head>

    <body style="background-color:#24252A;">
        <h1 class="task_label">Input Tasks</h1>
        <hr>
        <div>
            <form method="post" id="container">
                <h2 align="center" class="personal_label">Task</h2>
                <center><input type="text" class="form-control" placeholder="Task" name="taskname[]"></center>
                <br><br>
                <h3 align="center" class="personal_label">Remind Time</h3>
                <center>
                    <select class="custom-select" name="remind_endtime_hour[]">
                        <option value="00">00</option>
                        <option value="01">01</option>
                        <option value="02">02</option>
                        <option value="03">03</option>
                        <option value="04">04</option>
                        <option value="05">05</option>
                        <option value="06">06</option>
                        <option value="07">07</option>
                        <option value="08">08</option>
                        <option value="09">09</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                    </select>
                    <br><br> 
                    <select class="custom-select" name="remind_endtime_minute[]">
                        <option value="00">00</option>
                        <option value="01">01</option>
                        <option value="02">02</option>
                        <option value="03">03</option>
                        <option value="04">04</option>
                        <option value="05">05</option>
                        <option value="06">06</option>
                        <option value="07">07</option>
                        <option value="08">08</option>
                        <option value="09">09</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="20">20</option>
                        <option value="21">21</option>
                        <option value="22">22</option>
                        <option value="23">23</option>
                        <option value="24">24</option>
                        <option value="25">25</option>
                        <option value="26">26</option>
                        <option value="27">27</option>
                        <option value="28">28</option>
                        <option value="29">29</option>
                        <option value="30">30</option>
                        <option value="31">31</option>
                        <option value="32">32</option>
                        <option value="33">33</option>
                        <option value="34">34</option>
                        <option value="35">35</option>
                        <option value="36">36</option>
                        <option value="37">37</option>
                        <option value="38">38</option>
                        <option value="39">39</option>
                        <option value="40">40</option>
                        <option value="41">41</option>
                        <option value="42">42</option>
                        <option value="43">43</option>
                        <option value="44">44</option>
                        <option value="45">45</option>
                        <option value="46">46</option>
                        <option value="47">47</option>
                        <option value="48">48</option>
                        <option value="49">49</option>
                        <option value="50">50</option>
                        <option value="51">51</option>
                        <option value="52">52</option>
                        <option value="53">53</option>
                        <option value="54">54</option>
                        <option value="55">55</option>
                        <option value="56">56</option>
                        <option value="57">57</option>
                        <option value="58">58</option>
                        <option value="59">59</option>
                    </select>
                    <br><br>
                    <select class="custom-select" name="remind_endtime_AM_PM[]">
                        <option value="AM">AM</option>
                        <option value="PM">PM</option>
                    </select>
                    <br><br>
                    <h3 align="center" class="personal_label">Date</h3>
                    <input type="date" class="form-control" placeholder="Date" name="date[]">
                    <br><br>
                    <h3 align="center" class="personal_label">Type</h3>
                    <select class="custom-select" name="task_type[]">
                        <option value="one_time">One time</option>
                        <option value="Daily">Daily</option>
                        <option value="Weekly">Weekly</option>
                    </select>
                    <hr>
                </center>
                <br>
            </form>
        </div>
        <center><button class ="btn btn-success"id="addMore">+ Another Task</button></center>
        <br><br>
        <center><input class="btn btn-primary" type="submit" form="container" name="submit" value="Submit" style="width: auto; height: auto; font-size: 25px; text-align: center;" required></center>
        <br>
    </body>

    <script>
        var contents = `<center><div><button class="remove" type="button">- Task</button>
                        ${jQuery("form").html()}
                        </div></center>`;

        jQuery("#addMore").click(function(){
            jQuery('form').append(contents);
        });

        jQuery('form').on('click', '.remove',function(e){
            e.preventDefault();
            $(this).parent().remove();
        });
    </script>

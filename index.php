<html>

<title>Home Shaft</title>
<body>

<?php
//Connect
$con = mysql_connect("localhost","homeshaft","homey");
if (!$con) {
    die("Failed to connect: ".mysql_error());
}
mysql_select_db("homeshaft",$con);
?>

<?php 
$failed = false;
$msg = "";

$giver = $_POST['giver'];
$receiver = $_POST['receiver'];
$amount = $_POST['amount'];
$reason = $_POST['reason'];

if(!is_null($_POST['formcheck'])) {
    //Form was submitted
    //Check giver 
    if(empty($giver)) {
        $msg = "Giver was empty<br /><br />";
        $failed = true;
    } else if(empty($receiver)) {
        $msg = "Receiver was empty<br /><br />";
        $failed = true;
    } else if(empty($amount)) {
        $msg = "Amount was empty<br /><br />";
        $failed = true;
    } else if(!is_numeric($_POST['amount'])) {
        $msg = "Amount is not valid, should be an integer<br /><br />";
        $failed = true;
    } else if(empty($reason)) {
        $msg = "Reason was empty<br /><br />";
        $failed = true;
    }
    
    if($failed) {
        echo $msg;
    } else {
        //Mysql-escape the input
        $giver = mysql_real_escape_string($giver);
        $receiver = mysql_real_escape_string($receiver);
        $amount = mysql_real_escape_string($amount);
        $reason = mysql_real_escape_string($reason);
        
        
        //We have valid input, shaft it
        $sql = 'insert into shafts (giver,receiver,amount,reason) 
                values ("'.$giver.'","'.$receiver.'","'.$amount.'","'.$reason.'")';
        mysql_query($sql);
        echo $giver." shafted ".$receiver." for ".$amount." pence for ".$reason."<br />";
    }
}
else if(empty($_POST['amount'])) {
        $msg = "Amount was empty<br /><br />";
        $failed = true;
    } 

?>
<form action="index.php" method="post">
<p>
<?php 
$result = mysql_query("SELECT * FROM users");
$usersselecthtml = "<option />";
while ($row = mysql_fetch_array($result)) {
    $id = $row['id'];
    $name = $row['name'];
    $usersselecthtml.= '<option value="'.$name.'">'.$name.'</option><br />';
}
?>
<input type="hidden" name="formcheck" />
<table>
    <tbody>
        <tr>
            <td>Giver:</td>
            <td><select name="giver"><?php echo $usersselecthtml?></select></td>
        </tr>
        <tr>
            <td>Receiver:</td>
            <td><select name="receiver"><?php echo $usersselecthtml?></select></td>
        </tr>
        <tr>
            <td>Amount:</td>
            <td><input type="text" name="amount" /> pence</td>
        </tr>
        <tr>
            <td>Reason</td>
            <td><input type="text" name="reason" /></td>
        </tr>
    </tbody>    
</table>

<p>
<input type="submit" value="Shaft!"></p>
</form>
<br />
<h2>Balance</h2>
<table>
    <thead>
        <tr>
            <td>Name</td>
            <td>Balance</td>
        </tr>
    </thead>
    <tbody>
    
<?php 
$sql = "select 
            u.name as name,
            sum(if(s.giver=u.name,s.amount,0)) as givings,
            sum(if(s.receiver=u.name,s.amount,0)) as receivings 
        from users u,shafts s 
        group by u.name";
$result = mysql_query($sql); 
while ($row = mysql_fetch_array($result)) {
    $balance = $row['givings'] - $row['receivings'];
    echo "
        <tr>
            <td>".$row['name']."</td>
            <td>".$balance."</td>
        </tr>
    ";
}
?>
    </tbody>
</table>

<br />
<h2>Previous shaftings</h2>


<table>
    <thead>
        <tr>
            <td>Giver</td>
            <td>Receiver</td>
            <td>Timestamp</td>
            <td>Amount</td>
            <td>Reason</td>
        </tr>
    </thead>
    <tbody>
<?php 
$sql = "select * from shafts order by id desc";
$result = mysql_query($sql);
while ($row = mysql_fetch_array($result)) {
    echo "
        <tr>
            <td>".$row['giver']."</td>
            <td>".$row['receiver']."</td>
            <td>".$row['timestamp']."</td>
            <td>".$row['amount']."</td>
            <td style=\"width:70%\">".htmlentities($row['reason'])."</td>
        </tr>";
}

?>
    </tbody>
</table>


</body>

</html>

<?php
if($con) {
    mysql_close($con);
}
?>

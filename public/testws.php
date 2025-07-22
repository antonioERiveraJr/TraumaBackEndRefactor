<?php
error_reporting(E_ALL);

$serverDateTime = "";
if (isset($_POST['Submit'])) {
    try {
        $client = new SoapClient('https://eclaimstest2.philhealth.gov.ph:8077/SOAP?service=PhilhealthService');
        $params = new stdClass(); // No parameters needed for GetServerDateTime
        $response = $client->GetServerDateTime($params);
        $serverDateTime = $response->Result; // Assuming the result is in the 'Result' property
    } catch (SoapFault $fault) {
        die($fault->faultstring);
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>
    <h1>Test e-Claims Web Services using PHP</h1>
    <h2>Test GetServerDateTime function</h2>
    <form name="form" method="post">
        <input type="submit" value="Submit" name="Submit" /><br />
    </form>
    Server Date/Time: <b><?php echo htmlspecialchars($serverDateTime); ?></b> <br />
</body>

</html>
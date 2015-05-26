<?php
   #PutEnv("ORACLE_SID=DBS2");
   #PutEnv("TNS_ADMIN=/u01/app/oracle/product/10.2.0/db_1/network/admin");
   #PutEnv("LD_LIBRARY_PATH=/usr/lib:/usr/X11R6/lib");
   #PutEnv("ORACLE_HOME=/u01/app/oracle/product/10.2.0/db_1");

    $db_conn = oci_connect("PAYTV", "Pu42ex","(DESCRIPTION= (ADDRESS= (PROTOCOL=TCP) (HOST=10.15.193.233) (PORT=1521) ) (CONNECT_DATA= (SERVER=dedicated) (SERVICE_NAME=tsprod) ) )");
    $cmdstr = "select * from  P_BRANCH_SUPERVISOR";
    $parsed = ociparse($db_conn, $cmdstr);
    ociexecute($parsed);
    $nrows = ocifetchstatement($parsed, $results);
    echo "Found: $nrows results<br><br>\n";

   /* echo "<table border=1 cellspacing='0' width='50%'>\n";
    echo "<tr>\n";
    echo "<td><b>Name</b></td>\n";
    echo "<td><b>Salary</b></td>\n";
    echo "</tr>\n";

    for ($i = 0; $i < $nrows; $i++ ) {
          echo "<tr>\n";
          echo "<td>" . $results["ENAME"][$i] . "</td>";
          echo "<td>$ " . number_format($results["SAL"][$i], 2). "</td>";
          echo "</tr>\n";
    }

    echo "</table>\n";*/

?>


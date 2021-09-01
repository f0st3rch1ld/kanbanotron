<?php

$qbdb_vendor_ListID;
$qbdb_vendor_isActive;
$qbdb_vendor_CompanyName;
$qbdb_vendor_FirstName;
$qbdb_vendor_MiddleName;
$qbdb_vendor_LastName;
$qbdb_vendor_VendorAddress_Addr1;
$qbdb_vendor_VendorAddress_Addr2;
$qbdb_vendor_VendorAddress_Addr3;
$qbdb_vendor_VendorAddress_Addr4;
$qbdb_vendor_VendorAddress_Addr5;
$qbdb_vendor_VendorAddress_City;
$qbdb_vendor_VendorAddress_State;
$qbdb_vendor_VendorAddress_PostalCode;
$qbdb_vendor_VendorAddress_Country;
$qbdb_vendor_VendorAddress_Note;
$qbdb_vendor_ShipAddress_Addr1;
$qbdb_vendor_ShipAddress_Addr2;
$qbdb_vendor_ShipAddress_Addr3;
$qbdb_vendor_ShipAddress_Addr4;
$qbdb_vendor_ShipAddress_Addr5;
$qbdb_vendor_ShipAddress_City;
$qbdb_vendor_ShipAddress_State;
$qbdb_vendor_ShipAddress_PostalCode;
$qbdb_vendor_ShipAddress_Country;
$qbdb_vendor_ShipAddress_Note;
$qbdb_vendor_TermsRef_ListID;
$qbdb_vendor_TermsRef_FullName;

function qbdb_vendor_request($passed_vendor_name)
{
    // Quickbooks database connection
    include 'qb_data_connection.php';

    $qbdb_data_request = "SELECT ListID, Name, isActive, FirstName, MiddleName, LastName, VendorAddress_Addr1, VendorAddress_Addr2, VendorAddress_Addr3, VendorAddress_Addr4, VendorAddress_Addr5, VendorAddress_City, VendorAddress_State, VendorAddress_PostalCode, VendorAddress_Country, VendorAddress_Note, ShipAddress_Addr1, ShipAddress_Addr2, ShipAddress_Addr3, ShipAddress_Addr4, ShipAddress_Addr5, ShipAddress_City, ShipAddress_State, ShipAddress_PostalCode, ShipAddress_Country, ShipAddress_Note, TermsRef_ListID, TermsRef_Fullname FROM vendor WHERE CompanyName='" . $passed_vendor_name . "'";
    $qbdb_data_result = $conn->query($qbdb_data_request);

    if ($qbdb_data_result->num_rows > 0) {
        while ($row = $qbdb_data_result->fetch_assoc()) {
            // globals - so we can set their values
            global $qbdb_vendor_ListID;
            global $qbdb_vendor_isActive;
            global $qbdb_vendor_CompanyName;
            global $qbdb_vendor_FirstName;
            global $qbdb_vendor_MiddleName;
            global $qbdb_vendor_LastName;
            global $qbdb_vendor_VendorAddress_Addr1;
            global $qbdb_vendor_VendorAddress_Addr2;
            global $qbdb_vendor_VendorAddress_Addr3;
            global $qbdb_vendor_VendorAddress_Addr4;
            global $qbdb_vendor_VendorAddress_Addr5;
            global $qbdb_vendor_VendorAddress_City;
            global $qbdb_vendor_VendorAddress_State;
            global $qbdb_vendor_VendorAddress_PostalCode;
            global $qbdb_vendor_VendorAddress_Country;
            global $qbdb_vendor_VendorAddress_Note;
            global $qbdb_vendor_ShipAddress_Addr1;
            global $qbdb_vendor_ShipAddress_Addr2;
            global $qbdb_vendor_ShipAddress_Addr3;
            global $qbdb_vendor_ShipAddress_Addr4;
            global $qbdb_vendor_ShipAddress_Addr5;
            global $qbdb_vendor_ShipAddress_City;
            global $qbdb_vendor_ShipAddress_State;
            global $qbdb_vendor_ShipAddress_PostalCode;
            global $qbdb_vendor_ShipAddress_Country;
            global $qbdb_vendor_ShipAddress_Note;
            global $qbdb_vendor_TermsRef_ListID;
            global $qbdb_vendor_TermsRef_FullName;

            $qbdb_vendor_ListID = $row['ListID'];
            $qbdb_vendor_isActive = $row['isActive'];
            $qbdb_vendor_CompanyName = $row['CompanyName'];
            $qbdb_vendor_FirstName = $row['FirstName'];
            $qbdb_vendor_MiddleName = $row['MiddleName'];
            $qbdb_vendor_LastName = $row['LastName'];
            $qbdb_vendor_VendorAddress_Addr1 = $row['VendorAddress_Addr1'];
            $qbdb_vendor_VendorAddress_Addr2 = $row['VendorAddress_Addr2'];
            $qbdb_vendor_VendorAddress_Addr3 = $row['VendorAddress_Addr3'];
            $qbdb_vendor_VendorAddress_Addr4 = $row['VendorAddress_Addr4'];
            $qbdb_vendor_VendorAddress_Addr5 = $row['VendorAddress_Addr5'];
            $qbdb_vendor_VendorAddress_City = $row['VendorAddress_City'];
            $qbdb_vendor_VendorAddress_State = $row['VendorAddress_State'];
            $qbdb_vendor_VendorAddress_PostalCode = $row['VendorAddress_PostalCode'];
            $qbdb_vendor_VendorAddress_Country = $row['VendorAddress_Country'];
            $qbdb_vendor_VendorAddress_Note = $row['VendorAddress_Note'];
            $qbdb_vendor_ShipAddress_Addr1 = $row['ShipAddress_Addr1'];
            $qbdb_vendor_ShipAddress_Addr2 = $row['ShipAddress_Addr2'];
            $qbdb_vendor_ShipAddress_Addr3 = $row['ShipAddress_Addr3'];
            $qbdb_vendor_ShipAddress_Addr4 = $row['ShipAddress_Addr4'];
            $qbdb_vendor_ShipAddress_Addr5 = $row['ShipAddress_Addr5'];
            $qbdb_vendor_ShipAddress_City = $row['ShipAddress_City'];
            $qbdb_vendor_ShipAddress_State = $row['ShipAddress_State'];
            $qbdb_vendor_ShipAddress_PostalCode = $row['ShipAddress_PostalCode'];
            $qbdb_vendor_ShipAddress_Country = $row['ShipAddress_Country'];
            $qbdb_vendor_ShipAddress_Note = $row['ShipAddress_Note'];
            $qbdb_vendor_TermsRef_ListID = $row['TermsRef_ListID'];
            $qbdb_vendor_TermsRef_FullName = $row['TermsRef_FullName'];
        }
    } else {
        echo "No matching records were found inside the vendor table.";
    }

    // closes Quickbooks database connection
    $conn->close();
}

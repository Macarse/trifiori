<?php
$translate[_('isEmpty')] = 'Value is empty, but a non-empty value is required';
$translate[_('stringEmpty')] = "'%value%' is an empty string";

// Alpha
$translate[_('notAlnum')] = "'%value%' has not only alphabetic and digit characters";

// Alpha
$translate[_('notAlpha')] = "'%value%' has not only alphabetic characters";

// Between
$translate[_('notBetween')] = "'%value%' is not between '%min%' and '%max%', inclusively";
$translate[_('notBetweenStrict')] = "'%value%' is not strictly between '%min%' and '%max%'";

// Ccnum
$translate[_('ccnumLength')] = "'%value%' must contain between 13 and 19 digits";
$translate[_('ccnumChecksum')] = "Luhn algorithm (mod-10 checksum) failed on '%value%'";

// Date
$translate[_('dateNotYYYY-MM-DD')] = "'%value%' is not of the format YYYY-MM-DD";
$translate[_('dateInvalid')] = "'%value%' does not appear to be a valid date";
$translate[_('dateFalseFormat')] = "'%value%' does not fit given date format";

// Digit
$translate[_('notDigits')] = "'%value%' contains not only digit characters";

// EmailAddress
$translate[_('emailAddressInvalid')] = "'%value%' is not a valid email address in the basic format local-part@hostname";
$translate[_('emailAddressInvalidHostname')] = "'%hostname%' is not a valid hostname for email address '%value%'";
$translate[_('emailAddressInvalidMxRecord')] = "'%hostname%' does not appear to have a valid MX record for the email address '%value%'";
$translate[_('emailAddressDotAtom')] = "'%localPart%' not matched against dot-atom format";
$translate[_('emailAddressQuotedString')] = "'%localPart%' not matched against quoted-string format";
$translate[_('emailAddressInvalidLocalPart')] = "'%localPart%' is not a valid local part for email address '%value%'";

// Float
$translate[_('notFloat')] = "'%value%' does not appear to be a float";

// GraterThan
$translate[_('notGreaterThan')] = "'%value%' is not greater than '%min%'";

// Hex
$translate[_('notHex')] = "'%value%' has not only hexadecimal digit characters";

// Hostname
$translate[_('hostnameIpAddressNotAllowed')] = "'%value%' appears to be an IP address, but IP addresses are not allowed";
$translate[_('hostnameUnknownTld')] = "'%value%' appears to be a DNS hostname but cannot match TLD against known list";
$translate[_('hostnameDashCharacter')] = "'%value%' appears to be a DNS hostname but contains a dash (-) in an invalid position";
$translate[_('hostnameInvalidHostnameSchema')] = "'%value%' appears to be a DNS hostname but cannot match against hostname schema for TLD '%tld%'";
$translate[_('hostnameUndecipherableTld')] = "'%value%' appears to be a DNS hostname but cannot extract TLD part";
$translate[_('hostnameInvalidHostname')] = "'%value%' does not match the expected structure for a DNS hostname";
$translate[_('hostnameInvalidLocalName')] = "'%value%' does not appear to be a valid local network name";
$translate[_('hostnameLocalNameNotAllowed')] = "'%value%' appears to be a local network name but local network names are not allowed";

// Identical
$translate[_('notSame')] = 'Tokens do not match';
$translate[_('missingToken')] = 'No token was provided to match against';

// InArray
$translate[_('notInArray')] = "'%value%' was not found in the haystack";

// Int
$translate[_('notInt')] = "'%value' does not appear to be an integer";

// Ip
$translate[_('notIpAddress')] = "'%value%' does not appear to be a valid IP address";

// LessThan
$translate[_('notLessThan')] = "'%value%' is not less than '%max%'";

// Regex
$translate[_('regexNotMatch')] = "'%value%' does not match against pattern '%pattern%'";

// StringLength
$translate[_('stringLengthTooShort')] = "'%value%' is less than %min% characters long";
$translate[_('stringLengthTooLong')] = "'%value%' is greater than %max% characters long";

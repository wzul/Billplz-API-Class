# Billplz API Class

- Billplz API v3 using Guzzle 6 <br>
- Latest Version: **3.06** <br>
- Last Update: **3 September 2017**

# Minimum System Requirement
- PHP 5.5 or newer
- Linux or Windows-Based Hosting/Server

# Installation

1. Clone this repository
2. Import **billplz.php** file to your PHP file.
3. Create object and use the provided method

# Features

- Automatically detect mode (Production/Staging) based on API Key
- Automatically create Collection ID if not created
- Automatically choose Collection ID if not set
- Option to check and Collection ID validity
- Option to send Email Notification only or SMS Notification only
- Automatic fallback to no notification if Mobile Number is invalid or vice versa
- X Signature (HMAC SHA256 Validation) for Redirect and Callback ready
- Linux & Windows Server Compatible

# API Access Ability

- Create A Collection
- Create A Bill
- Get A Bill
- Delete A Bill
- Check Collection ID Availability
- Get Collection Index

# Example: 0. Import & Create Object

```
require 'billplz.php';
$billplz = new Billplz('<api_key>');
```

# Example: 1. Create A Bill

1. **Set Required Parameter**

```
$billplz
        ->setName('Wan Zulkarnain')
        ->setAmount('10.60')
        ->setEmail('youremail@gmail.com')
        ->setDescription('Test Payment')
        ->setPassbackURL('http://callback-url.com', 'http://redirect-url.com')
        ->create_bill(true);
```

2. **Set Optional Parameter**

Optionally, before you call the **create_bill()** method, you can set optional parameter. 

```
$billplz
        ->setCollection('<collection_id>')
        ->setReference_1('ID')
        ->setReference_1_Label('A')
        ->setReference_2('Lot 100, AAA, BB')
        ->setReference_2_Label('Address')
        ->setMobile('0145356443');
        ->setDeliver('3')
        ->create_bill(true);
```

3. **Get Bill ID & URL**

```
$bill_id = $billplz->getID();
$billl_url = $billplz->getURL();
```

# Example: 2. Get A Bill

```
$billplz->check_bill('<bill_id>');
```
This method will return a string of array. Refer: https://billplz.com/api#v3-get-a-bill25

# Example: 3. Delete A Bill

```
$billplz->deleteBill('<bill_id>');
```
This method will return boolean value. **true** if succesfully deleted or **false** if the bills cannot be deleted.

# Example: 4. Create A Collection

```
$billplz->create_collection('Payment for Purchase');
```

# Example: 5. Check Collection ID Availability

```
$billplz->check_collection_id('<collection_id>');
```
This method will return boolean value. **true** if already created **false** if not created yet/invalid.

# Example: 6. Get Collection Index

Collection will be divided to several page. For getting the first page of collection, pass '1' as passing parameter.

```
$billplz->getCollectionIndex('1');
```

# Example: 7. Get Redirect & Callback Data

To use this method, you must ensure that X Signature Key is enabled for your account. No Billplz Object is required because this is a static method.

```
// Redirect
Billplz::getRedirectData('<x_signature_key>');

// Callback
Billplz::getCallbackData('<x_signature_key>');
```

# Other

Please open an issue or email to wan@billplz.com

# Billplz API Class

- Billplz API v3 using Guzzle 6 <br>
- Latest Version: **3.03** <br>
- Last Update: **19 June 2017**

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

# Example: Create A Bill

1. **Create Billplz Object**

```
$billplz = new Billplz('<api_key>');
```
2. **Set Required Parameter**

```
$billplz
        ->setName('Wan Zulkarnain')
        ->setAmount('10.60')
        ->setEmail('youremail@gmail.com')
        ->setDescription('Test Payment')
        ->setPassbackURL('http://callback-url.com', 'http://redirect-url.com');
```

3. **Set Optional Parameter**

```
$billplz
        ->setCollection('ypppifx4m')
        ->setReference_1('ID')
        ->setReference_1_Label('A')
        ->setReference_2('Lot 100, AAA, BB')
        ->setReference_2_Label('Address')
        ->setMobile('0145356443');
        ->setDeliver('3'); 
```

4. **Get ID & URL**

```
$bill_id = $billplz->getID();
$billl_url = billplz->getURL();
```

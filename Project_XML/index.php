<?php
class Contact
{
    public $name;
    public $phone;
    public $address;
    public $email;

    public function __construct($name, $phone, $address, $email)
    {
        $this->name = $name;
        $this->phone = $phone;
        $this->address = $address;
        $this->email = $email;
    }
}

class ContactsManager
{
    private $contacts = [];
    private $currentIndex = 0;

    public function loadContacts()
    {
        $contactsXml = simplexml_load_file('contacts.xml');
        foreach ($contactsXml->contact as $contactXml) {
            $this->contacts[] = new Contact(
                (string)$contactXml->name,
                (string)$contactXml->phone,
                (string)$contactXml->address,
                (string)$contactXml->email
            );
        }
    }

    public function saveContacts()
    {
        $xml = new SimpleXMLElement('<contacts></contacts>');
        foreach ($this->contacts as $contact) {
            $xmlContact = $xml->addChild('contact');
            $xmlContact->addChild('name', $contact->name);
            $xmlContact->addChild('phone', $contact->phone);
            $xmlContact->addChild('address', $contact->address);
            $xmlContact->addChild('email', $contact->email);
        }
        $xml->asXML('contacts.xml');
    }

    public function insertOrUpdateContact($name, $phone, $address, $email)
    {
        foreach ($this->contacts as $contact) {
            if ($contact->name == $name) {
                $contact->phone = $phone;
                $contact->address = $address;
                $contact->email = $email;
                $this->saveContacts();
                return;
            }
        }
        $this->contacts[] = new Contact($name, $phone, $address, $email);
        $this->saveContacts();
    }

    public function deleteContact($name)
    {
        foreach ($this->contacts as $key => $contact) {
            if ($contact->name == $name) {
                unset($this->contacts[$key]);
                $this->saveContacts();
                return;
            }
        }
    }

    public function searchContact($name)
    {
        foreach ($this->contacts as $contact) {
            if ($contact->name == $name) {
                return [
                    'name' => $contact->name,
                    'phone' => $contact->phone,
                    'address' => $contact->address,
                    'email' => $contact->email
                ];
            }
        }
        return null;
    }

    public function getCurrentContact()
    {
        return isset($this->contacts[$this->currentIndex]) ? $this->contacts[$this->currentIndex] : null;
    }

    public function nextContact()
    {
        $this->currentIndex = ($this->currentIndex + 1) % count($this->contacts);
        return $this->contacts[$this->currentIndex];
    }

    public function prevContact()
    {
        $this->currentIndex = ($this->currentIndex - 1 + count($this->contacts)) % count($this->contacts);
        return $this->contacts[$this->currentIndex];
    }
}

$contactsManager = new ContactsManager();
$contactsManager->loadContacts();

$name = '';
$phone = '';
$email = '';
$address = '';
$currentContact = $contactsManager->getCurrentContact();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['insert']) || isset($_POST['update'])) {
        $contactsManager->insertOrUpdateContact($_POST['name'], $_POST['phone'], $_POST['address'], $_POST['email']);
    } elseif (isset($_POST['delete'])) {
        $contactsManager->deleteContact($_POST['name']);
    } elseif (isset($_POST['search'])) {
        $searchResult = $contactsManager->searchContact($_POST['name']);
        if ($searchResult) {
            $name = $searchResult['name'];
            $phone = $searchResult['phone'];
            $email = $searchResult['email'];
            $address = $searchResult['address'];
        }
    } elseif (isset($_POST['next'])) {
        $currentContact = $contactsManager->nextContact();
        if ($currentContact) {
            $name = $currentContact->name;
            $phone = $currentContact->phone;
            $email = $currentContact->email;
            $address = $currentContact->address;
        }
    } elseif (isset($_POST['prev'])) {
        $currentContact = $contactsManager->prevContact();
        if ($currentContact) {
            $name = $currentContact->name;
            $phone = $currentContact->phone;
            $email = $currentContact->email;
            $address = $currentContact->address;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Management</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }


        .form-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ccc;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        input[type="email"] {
            width: calc(100% - 22px);
            padding: 10px;
            margin: 5px 0 20px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            padding: 10px 20px;
            margin-right: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .btns-container {
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <form method="post">
            <div class="inputs-container">
                <div>
                    <label for="name">Name:</label>
                    <input type="text" name="name" id="name" value="<?php echo $name ?>">
                </div>
                <div>
                    <label for="phone">Phone:</label>
                    <input type="text" name="phone" id="phone" value="<?php echo $phone ?>">
                </div>
                <div>
                    <label for="address">Address:</label>
                    <input type="text" name="address" id="address" value="<?php echo $address ?>">
                </div>
                <div>
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" value="<?php echo $email ?>">
                </div>
            </div>
            <div class="btns-container">
                <input type="submit" name="insert" value="Insert/Update">
                <input type="submit" name="delete" value="Delete">
                <input type="submit" name="search" value="Search By Name">
                <input type="submit" name="prev" value="Prev">
                <input type="submit" name="next" value="Next">
            </div>
        </form>
    </div>
</body>

</html>
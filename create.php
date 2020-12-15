<?php
require_once("lib/common.php");

$errors = [];
if (isPost()) {
    handleForm();
}

function createClient($last_name, $first_name, $middle_name, $phone, $email) {
    global $CN, $errors;
    $stmt = $CN->prepare("INSERT INTO client (last_name, first_name, middle_name, phone, email)
        VALUES (?, ?, ?, ?, ?);");
    $stmt->bind_param("sssss", $last_name, $first_name, $middle_name, $phone, $email);
    $result = $stmt->execute();
    if ($result === false) {
        $errors[] = "Unable to create a client";
        return false;
    }
    return $stmt->insert_id;
}

function createOrder($worker_id, $client_id, $device_info, $order_type, $price) {
    global $CN, $errors;
    // Referential integrity on the database side renders this check useless when it comes to the
    // pure application logic. However, as we're trying to be user friendly, we will do another DB query
    // to check if this or that entity actually exists, so that we could display a nice error message.
    if (!recordExists("worker", $worker_id)) {
        $errors[] = "This worker is not in the database. Maybe he was fired while you were filling the form?";
        http_response_code(404);
    }

    if (!recordExists("client", $client_id)) {
        $errors[] = "This client is no longer in the database. Kinda sad, I guess.";
        http_response_code(404);
    }

    if (!recordExists("order_type", $order_type)) {
        $errors[] = "We don't do that here.";
        http_response_code(404);
    }

    if (!empty($errors)) {
        return false;
    }

    $stmt = $CN->prepare("INSERT INTO `order` (worker_id, client_id, device, order_type_id, price)
        VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisii", $worker_id, $client_id, $device_info, $order_type, $price);
    $result = $stmt->execute();
    if ($result === false) {
        $errors[] = "Unable to place an order. Please, try again later.";
        return false;
    }
    return true;
}

function handleForm() {
    global $errors;
    $field_names = [
        "worker_id" => "Worker",
        "device_info" => "Device",
        "order_type" => "Order type",
        "price" => "Price",
        "client_lastname" => "Client last name",
        "client_firstname" => "Client first name",
        "client_middlename" => "Client middle name",
        "client_phone" => "Client phone",
        "client_email" => "Client email",
    ];
    $required_keys = [
        "worker_id", "device_info", "order_type", "price"
    ];
    $new_required_keys = ["client_lastname", "client_firstname"];

    // Check if all required order data is there
    $errors = reqPostVals($required_keys);
    foreach ($errors as $key) {
        $errors[] = "Value required for field " . $field_names[$key];
    }

    $client_id = null;
    if ($new_client = getPostVal("new_client")) {
        $req_errors = reqPostVals($new_required_keys);
        foreach ($req_errors as $key) {
            $errors[] = "Value required for field " . $field_names[$key];
        }
    } else if (!($client_id = getPostVal("client_id"))) {
        $errors[] = "Value required for field 'Client'";
    }

    // Additionally, validate on length and data types
    $price = getPostVal("price");
    if (!is_numeric($price)) {
        $errors[] = "Price is not a number.";
    } else {
        $price = intval($price);
    }

    if ($client_id !== null) {
        if (!is_numeric($client_id)) {
            $errors[] = "Client ID is not a number.";
        } else {
            $client_id = intval($client_id);
        }
    }

    $worker_id = getPostVal("worker_id");
    if (!is_numeric($worker_id)) {
        $errors[] = "Worker ID is not a number.";
    } else {
        $worker_id = intval($worker_id);
    }

    $order_type = getPostVal("order_type");
    if (!is_numeric($order_type)) {
        $errors[] = "Order type must be a number.";
    } else {
        $order_type = intval($order_type);
    }

    foreach ($_POST as $key => $value) {
        if (mb_strlen($value) > 255) {
            $errors[] = "Field " . array_key_exists($key, $field_names) ? $field_names[$key] : $key . " length exceeds 255 symbols";
        }
    }

    if (empty($errors)) {
        // If new client, create a new client!
        if ($new_client) {
            $client_id = createClient(
                getPostVal("client_lastname"),
                getPostVal("client_firstname"),
                getPostVal("client_middlename"),
                getPostVal("client_phone"),
                getPostVal("client_email")
            );
        } else {
            $client_id = getPostVal("client_id");
        }

        if ($client_id) {
            $result = createOrder(
                $worker_id,
                $client_id,
                getPostVal("device_info"),
                $order_type,
                $price
            );
            if ($result) {
                die(header("Location: index.php"));
            }
        }
    }
}

function namesOptions($table) {
    global $CN;
    $result = $CN->query("SELECT id, first_name, middle_name, last_name FROM $table");
    return optionsFromArray(extractNames($result));
}

function clientOptions() {
    return namesOptions("client");
}

function workerOptions() {
    return namesOptions("worker");
}

function typeOptions() {
    global $CN;
    $result = $CN->query("SELECT id, name FROM order_type ORDER BY id");
    if ($result) {
        $types = [];
        while ($line = $result->fetch_assoc()) {
            $types[$line["id"]] = $line["name"];
        }
        return optionsFromArray($types);
    }
    return "";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Place an order — Workshop Overseer</title>
    <link href="./styles/master.css" rel="stylesheet">
</head>

<body class="page">
<header class="header">
    <h1 class="header__title">Order placement</h1>
</header>
<main class="content">
    <?php
        if ($errors) {
            echo '<span class="description description__error"><h5>Errors:</h5><ul>';
            foreach ($errors as $error) {
                echo '<li>' . $error . '</li>';
            }
            echo '</ul></span>';
        }
    ?>
    <form class="ticket" method="POST">
        <section class="ticket__user">
        <div class="ticket__client-selector">
            <input type="checkbox" class="ticket__client-selector__input" id="new-client" name="new_client">
            <label for="new-client" class="ticket__client-selector__text">New client</label>
        </div>
        <select required class="ticket__input ticket__input-client" name="client_id">
            <option selected value="">Existing client</option>
            <?php echo clientOptions(); ?>
        </select>
        <input required class="ticket__input ticket__input-client ticket__input-newclient" name="client_lastname" type="text" placeholder="Last name">
        <input required class="ticket__input ticket__input-client ticket__input-newclient" name="client_firstname" type="text" placeholder="First name">
        <input required class="ticket__input ticket__input-client ticket__input-newclient" name="client_middlename" type="text" placeholder="Middle name">
        <input class="ticket__input ticket__input-client ticket__input-newclient ticket__input-newclient-noreq" name="client_phone" type="text" placeholder="Phone">
        <input class="ticket__input ticket__input-client ticket__input-newclient ticket__input-newclient-noreq" name="client_email" type="email" placeholder="Email">
        </section>
        <section class="ticket__master">
        <select required class="ticket__input" name="worker_id">
            <option selected value="">Worker</option>
            <?php echo workerOptions(); ?>
        </select>
        <input required class="ticket__input" name="device_info" type="text" placeholder="Device">
        <select required class="ticket__input" name="order_type">
            <option selected value="">Order type</option>
            <?php echo typeOptions(); ?>
        </select>
        <input required class="ticket__input" name="price" type="number" min="0" placeholder="Price">
    </section>
        <input class="btn" type="submit" value="Place an order" name="btn_self">
    </form>
</main>
<footer class="footer"></footer>
<script>
    function updateNewClient() {
        let newClientSelected = document.getElementById("new-client").checked;
        let clientFields = document.getElementsByClassName("ticket__input-client");
        for (let i = 0; i < clientFields.length; i++) {
            let el = clientFields[i];
            let isNew = el.classList.contains("ticket__input-newclient");
            if (isNew && newClientSelected || !isNew && !newClientSelected) {
                el.required = el.classList.contains("ticket__input-newclient-noreq") ? null : "required";
                el.classList.remove("hidden");
            } else {
                el.required = null;
                el.classList.add("hidden");
            }
        }
    }

    document.getElementById("new-client").addEventListener("change", updateNewClient);
    updateNewClient();
</script>
</body>
</html>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gamehub - Product Manager</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #295be2 0%, #e9e7e7 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 40px 20px;
        }

        /* ── NAV TABS ── */
        .nav-tabs {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-bottom: 28px;
            flex-wrap: wrap;
        }

        .nav-tab {
            padding: 10px 22px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.20);
            color: #fff;
            font-weight: 600;
            font-size: 0.88em;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.25s ease;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .nav-tab:hover {
            background: rgba(255, 255, 255, 0.35);
        }

        .nav-tab.active {
            background: #fff;
            color: #295be2;
            border-color: #fff;
        }

        /* ── WRAPPER ── */
        .page-wrapper {
            width: 100%;
            max-width: 520px;
        }

        /* ── SECTIONS ── */
        .section {
            display: none;
        }

        .section.active {
            display: block;
        }

        /* ── FORM CONTAINER ── */
        .form-container {
            background: #fbfbfb;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 50px 40px;
        }

        .form-icon {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-icon img {
            width: 120px;
        }

        /* Fallback logo placeholder when image is missing */
        .logo-placeholder {
            width: 90px;
            height: 90px;
            margin: 0 auto;
            background: linear-gradient(135deg, #295be2 0%, #0579ec 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2em;
            color: #fff;
            font-weight: 700;
            letter-spacing: -1px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 8px;
            font-size: 1.8em;
            text-transform: uppercase;
        }

        .section-subtitle {
            text-align: center;
            color: #888;
            font-size: 0.85em;
            margin-bottom: 28px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 600;
            font-size: 0.95em;
        }

        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1em;
            transition: all 0.3s ease;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #fff;
            color: #333;
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        select:focus {
            outline: none;
            border-color: #295be2;
            box-shadow: 0 0 0 3px rgba(41, 91, 226, 0.1);
        }

        /* ── BUTTONS ── */
        button.btn-primary {
            background: linear-gradient(135deg, #092fda 0%, #0579ec 100%);
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-size: 1.05em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            width: 100%;
        }

        button.btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(5, 121, 236, 0.3);
        }

        button.btn-danger {
            background: linear-gradient(135deg, #092fda 0%, #0579ec 100%);
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-size: 1.05em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            width: 100%;
        }

        button.btn-danger:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(231, 76, 60, 0.35);
        }

        button.btn-warning {
            background: linear-gradient(135deg, #092fda 0%, #0579ec 100%);
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-size: 1.05em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            width: 100%;
        }

        button.btn-warning:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(243, 156, 18, 0.35);
        }

        .back-link {
            text-align: center;
            margin-top: 25px;
        }

        .back-link a {
            color: #295be2;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9em;
        }

        /* ── READ TABLE ── */
        .table-wrapper {
            overflow-x: auto;
            margin-top: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.92em;
        }

        thead tr {
            background: linear-gradient(135deg, #092fda 0%, #0579ec 100%);
            color: #fff;
        }

        thead th {
            padding: 12px 14px;
            text-align: left;
            font-weight: 600;
            font-size: 0.85em;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        tbody tr {
            border-bottom: 1px solid #ebebeb;
            transition: background 0.2s;
        }

        tbody tr:hover {
            background: #f0f5ff;
        }

        tbody td {
            padding: 12px 14px;
            color: #444;
        }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.78em;
            font-weight: 700;
            text-transform: uppercase;
        }


        /* ── DELETE CONFIRM BOX ── */
        .confirm-box {
            background: #fff5f5;
            border: 2px solid #f5c6c6;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }

        .confirm-box p {
            color: #c0392b;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .confirm-box span {
            color: #888;
            font-size: 0.87em;
        }

        .btn-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 10px;
        }

        .btn-row button {
            margin-top: 0;
        }

        button.btn-secondary {
            background: #f0f0f0;
            color: #555;
            padding: 12px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        button.btn-secondary:hover {
            background: #e4e4e4;
        }

        /* ── DIVIDER ── */
        .divider {
            height: 1px;
            background: #ebebeb;
            margin: 22px 0;
        }

        .field-hint {
            font-size: 0.8em;
            color: #aaa;
            margin-top: 5px;
        }
    </style>
</head>

<body>

    <div class="page-wrapper">

        <!-- NAV TABS -->
        <div class="nav-tabs">
            <div class="nav-tab active" onclick="showTab('create')">ADD</div>
            <div class="nav-tab" onclick="showTab('read')">READ</div>
            <div class="nav-tab" onclick="showTab('update')">UPDATE</div>
            <div class="nav-tab" onclick="showTab('delete')">DELETE</div>
        </div>

        <!--CREATE-->
        <div class="section active" id="section-create">
            <div class="form-container">

                <h1>Add Product</h1>

                <form action="../CONTROLLER/EventController.php" method="POST">

                    <div class="form-group">
                        <label for="c-name">Name:</label>
                        <input type="text" id="c-name" name="name" placeholder="e.g. PlayStation 5" required>
                    </div>

                    <div class="form-group">
                        <label for="c-price">Price (€):</label>
                        <input type="number" id="c-price" name="price" placeholder="0.00" min="0" step="0.01" required>
                    </div>

                    <div class="form-group">
                        <label for="c-amount">Amount:</label>
                        <input type="number" id="c-amount" name="amount" placeholder="0" min="0" required>
                    </div>

                    <div class="form-group">
                        <label for="c-stock">Stock:</label>
                        <select name="stock" id="c-stock">
                            <option value="1">Available</option>
                            <option value="0">Not Available</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="c-image">Product Image: <span
                                style="color:#aaa; font-weight:400;">(optional)</span></label>
                        <input type="file" id="c-image" name="image" accept="image/png, image/jpeg, image/webp"
                            style="width:100%; padding:10px; border:2px dashed #c5d3f5; border-radius:8px; background:#f4f7ff; cursor:pointer;"
                            onchange="previewImage(event)">
                        <div id="image-preview-wrapper" style="display:none; margin-top:10px;">
                            <img id="image-preview" src="" alt="Preview"
                                style="width:100%; max-height:180px; object-fit:cover; border-radius:8px; border:2px solid #e0e0e0;">
                            <div id="image-name"
                                style="font-size:.78em; color:#888; margin-top:5px; text-align:center;"></div>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary">Save Product</button>
                    <input type="hidden" name="create" value="1">

                </form>

                <div class="back-link">
                    <a href="shop.php">← Go Back</a>
                </div>

            </div>
        </div>

        <!--READ-->
        <div class="section" id="section-read">
            <div class="form-container">

                <h1>Products</h1>

                <div class="form-group">
                    <label for="r-search">Search by name:</label>
                    <input type="text" id="r-search" name="search" placeholder="Search product..."
                        oninput="filterTable()">
                </div>

                <div class="table-wrapper">
                    <table id="products-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Amount</th>
                                <th>Stock</th>
                            </tr>
                        </thead>
                        <tbody id="products-body"></tbody>
                    </table>
                </div>

                <div class="back-link">
                    <a href="shop.php">← Go Back</a>
                </div>

            </div>
        </div>

        <!--UPDATE-->
        <?php

        require_once '../CONTROLLER/EventController.php';

        $controller = new EventController();

        /*
    Cogemos el ID del GET (form de búsqueda)
*/
        $id = $_GET['id'] ?? null;

        /*
    Solo llamamos a la BD si hay ID
*/
        $data = null;

        if ($id) {
            $data = $controller->readId($id);
        }
        ?>
        <div class="section" id="section-update">
            <div class="form-container">

                <h1>Update Product</h1>

                <form method="GET">
                    <div class="form-group">
                        <label for="u-search-id">Product ID:</label>
                        <input type="number" id="u-search-id" name="id" placeholder="Enter product ID" min="1" required>
                    </div>

                    <button type="submit" class="btn-warning">Search Product</button>
                </form>

                <div class="divider"></div>

                <form action="../CONTROLLER/EventController.php" method="POST">

                    <input type="hidden" name="id" value="<?= $data['id_product'] ?? '' ?>">

                    <div class="form-group">
                        <label for="u-name">Name:</label>
                        <input type="text" id="u-name" name="name"
                            value="<?= $data['name'] ?? '' ?>"
                            placeholder="Product name" required>
                    </div>

                    <div class="form-group">
                        <label for="u-price">Price (€):</label>
                        <input type="number" id="u-price" name="price"
                            value="<?= $data['price'] ?? '' ?>"
                            placeholder="0.00" min="0" step="0.01" required>
                    </div>

                    <div class="form-group">
                        <label for="u-amount">Amount:</label>
                        <input type="number" id="u-amount" name="amount"
                            value="<?= $data['amount'] ?? '' ?>"
                            placeholder="0" min="0" required>
                    </div>

                    <div class="form-group">
                        <label for="u-stock">Stock:</label>
                        <select name="stock" id="u-stock">
                            <option value="1" <?= (isset($data['stock']) && $data['stock'] == 1) ? 'selected' : '' ?>>
                                Available
                            </option>
                            <option value="0" <?= (isset($data['stock']) && $data['stock'] == 0) ? 'selected' : '' ?>>
                                Not Available
                            </option>
                        </select>
                    </div>

                    <input type="hidden" name="update" value="1">

                    <button type="submit" class="btn-warning">Update Product</button>
                </form>

                <div class="back-link">
                    <a href="shop.php">← Go Back</a>
                </div>

            </div>
        </div>

        <!--DELETE-->

        <div class="section" id="section-delete">
            <div class="form-container">

                <h1>Delete Product</h1>

                <form method="GET">
                    <div class="form-group">
                        <label for="d-search-id">Product ID:</label>
                        <input type="number" id="d-search-id" name="id" placeholder="Enter product ID" min="1" required>
                        <p class="field-hint">Make sure the ID is correct before proceeding.</p>
                    </div>
                    <button type="submit" class="btn-danger">Search Product</button>
                </form>

                <div class="divider"></div>

                <form action="../CONTROLLER/EventController.php" method="POST">

                  <input type="hidden" name="id" value="<?= $data['id_product'] ?? '' ?>">

                    <div class="form-group">
                        <label>Name:</label>
                        <input value="<?= $data['name'] ?? '' ?>" type="text" name="name_preview" placeholder="Product name will appear here" disabled>
                    </div>

                    <div class="form-group">
                        <label>Price (€):</label>
                        <input value="<?= $data['price'] ?? '' ?>" type="number" name="price_preview" placeholder="Price will appear here" disabled>
                    </div>

                    <div class="btn-row">
                        <button type="button" class="btn-secondary" onclick="showTab('read')">← Cancel</button>
                        <button type="submit" class="btn-danger">Confirm Delete</button>
                        <input type="hidden" name="delete" value="1">
                    </div>

                </form>

                <div class="back-link">
                    <a href="shop.php">← Go Back</a>
                </div>

            </div>
        </div>

    </div>

    <script>
        function showTab(tab) {

            // Guardar pestaña en la URL
            window.location.hash = tab;

            // Ocultar todas las secciones
            document.querySelectorAll('.section')
                .forEach(section => section.classList.remove('active'));

            // Quitar active de todas las pestañas
            document.querySelectorAll('.nav-tab')
                .forEach(tabBtn => tabBtn.classList.remove('active'));

            // Mostrar sección seleccionada
            document.getElementById('section-' + tab)
                .classList.add('active');

            // Activar botón correspondiente
            const tabs = ['create', 'read', 'update', 'delete'];

            document.querySelectorAll('.nav-tab')[tabs.indexOf(tab)]
                .classList.add('active');

            // Cargar productos si estamos en READ
            if (tab === 'read') {
                loadProducts();
            }

            // Scroll arriba
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }


        function loadProducts() {

            fetch('../CONTROLLER/EventController.php?action=read')
                .then(response => response.json())
                .then(data => {

                    document.getElementById('products-body').innerHTML =
                        data.map(product => `
                        <tr>
                            <td>${product.id_product}</td>
                            <td>${product.name}</td>
                            <td>${product.price}</td>
                            <td>${product.amount}</td>
                            <td>
                                ${product.stock == 1
                                ? 'Available'
                                : 'Not Available'}
                            </td>
                        </tr>
                    `).join('');

                })
                .catch(error => console.error(error));
        }


        // Al cargar la página
        window.onload = function() {

            // Obtener pestaña desde la URL
            const currentTab = window.location.hash.replace('#', '');

            // Si existe -> abrir esa
            // Si no -> create
            if (currentTab) {
                showTab(currentTab);
            } else {
                showTab('create');
            }

        };
    </script>

</body>

</html>
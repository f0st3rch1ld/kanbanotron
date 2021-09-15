// AJAX request to load new kanban info
function loadKanban(x) {
  let knbnUID = x.replace("http://internalweb/kanbanotron/?knbn_uid=", "");

  // state reset
  const state = null;
  let url = `http://internalweb/kanbanotron/?knbn_uid=${knbnUID}`;
  history.replaceState(state, "", url);

  const xhttp = new XMLHttpRequest();
  xhttp.onload = function () {
    document.getElementById("knbn-info-container").innerHTML =
      this.responseText;
    document.getElementById("knbn_uid").value = "";
    document.getElementById("knbn_uid").focus();
  };
  xhttp.open(
    "GET",
    "../../wp-content/plugins/kanbanotron/components/load_kanban.php?xhttp=1&knbn_uid=" +
      knbnUID
  );
  xhttp.send();
}

// AJAX request to load purchase order preview with new kanban information
function loadPOPreview(x) {
  const xhttp = new XMLHttpRequest();
  xhttp.onload = function () {
    document.getElementById("po-overview-container").innerHTML =
      this.responseText;
  };

  xhttp.open(
    "GET",
    `../../wp-content/plugins/kanbanotron/components/purchase_order_preview.php/?xhttp=1&active_po=${x}`
  );
  xhttp.send();
}

// AJAX request to add a kanban to the appropriate PO
function addToPO(x, y) {
  const xhttp = new XMLHttpRequest();

  xhttp.onload = function () {
    console.log(this.responseText);
    loadPOPreview(y);
    document.getElementById("knbn_uid").focus();
  };

  xhttp.open(
    "GET",
    `../../wp-content/plugins/kanbanotron/components/add_to_purchase_order.php/?knbn_uid=${x}&active_po=${y}`
  );
  xhttp.send();
}

// AJAX request to remove a kanban from a PO
function removeFromPO(x, y) {
  if (confirm("Delete this item from it's purchase order?")) {
    txt = "Item Deleted";
    const xhttp = new XMLHttpRequest();

    xhttp.onload = function () {
      console.log(this.responseText);
      loadPOPreview(x);
    };

    xhttp.open(
      "GET",
      `../../wp-content/plugins/kanbanotron/components/remove_from_purchase_order.php/?active_po=${x}&knbn_index=${y}`
    );
    xhttp.send();
  }
}

// AJAX request to remove all kanbans from a specific vendor from a PO
function removeAllFromPO(x, y) {
  if (
    confirm(
      'Are you sure you want to delete all products from this vendor? If you choose to delete them, the only way to get them back is to manually add them back to the order... If you wish to delete them, click "OK".'
    )
  ) {
    console.log(x);
    console.log(y);

    const xhttp = new XMLHttpRequest();

    xhttp.onload = function () {
      console.log(this.responseText);
      loadPOPreview(x);
    };

    xhttp.open(
      "GET",
      `../../wp-content/plugins/kanbanotron/components/remove_all_from_purchase_order.php/?active_po=${x}&vndr=${y}`
    );
    xhttp.send();
  }
}

// AJAX request to remove all kanbans from a specific vendor from a PO
function removeAllFromPOAfterSubmit(x, y) {
  console.log(x);
  console.log(y);

  const xhttp = new XMLHttpRequest();

  xhttp.onload = function () {
    console.log(this.responseText);
    loadPOPreview(x);
  };

  xhttp.open(
    "GET",
    `../../wp-content/plugins/kanbanotron/components/remove_all_from_purchase_order.php/?active_po=${x}&vndr=${y}`
  );
  xhttp.send();
}

// AJAX request to create a new purchase order
function newPO() {
  // sends request to update database with a new Purchase Order
  const xhttp = new XMLHttpRequest();
  xhttp.onload = function () {
    document.getElementById("active-order-controls-container").innerHTML =
      this.responseText;
    document.getElementById("active-order-submit").click();
  };
  xhttp.open(
    "GET",
    "../../wp-content/plugins/kanbanotron/components/new_purchase_order.php"
  );
  xhttp.send();
}

// AJAX request to submit a single purchase order
function submitPurchaseOrder(x, y) {
  if (
    confirm(
      'Are you sure you want to submit this purchase order? Make sure to double check everything looks correct before sending. If everything looks correct, continue by clicking "OK".'
    )
  ) {
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function () {
      console.log(this.responseText);
      removeAllFromPOAfterSubmit(x, y);
    };
    xhttp.open(
      "GET",
      `../../wp-content/plugins/kanbanotron/components/send_purchase_order.php/?active_po=${x}&vndr=${y}`
    );
    xhttp.send();
  }
}

// AJAX request to set default reorder quantity
function setDefaultReorderQuan(x, y) {
  const xhttp = new XMLHttpRequest();
  xhttp.onload = function () {
    console.log(this.responseText);
    loadKanban(x);
  };
  xhttp.open(
    "GET",
    `../../wp-content/plugins/kanbanotron/db/wp_db/update_default_reorder_quan.php/?knbn_uid=${x}&quan=${y}`
  );
  xhttp.send();
}

// AJAX request to set external URL
function setExternalURL(x, y) {
  const xhttp = new XMLHttpRequest();
  xhttp.onload = function () {
    console.log(this.responseText);
    loadKanban(x);
  };
  xhttp.open(
    "GET",
    `../../wp-content/plugins/kanbanotron/db/wp_db/update_external_url.php/?knbn_uid=${x}&ext_url=${y}`
  );
  xhttp.send();
}

// Updates browser cookies to reflect chosen purchase order.
function updateActiveOrder(x) {
  document.cookie = `working_purchase_order=${x}`;
  console.log(document.cookie);
}

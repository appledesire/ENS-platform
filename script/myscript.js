/* To connect using MetaMask */
var wallet_is_connected, verified;
var customer_info = [];
var customer_info_data = [];
var total_price = 0;
var total_price_for_confirm;
var customer_wallet;

var crypto_id = ["mmfinance-polygon", "ethereum", "usd-coin", "helium"];
var price;
var matic_price;
var eth_price;
var hnt_price;
var usdc_price;
var payment_method;
async function buttonActivation() {
  if (window.web3.currentProvider._eventsCount >= 4) wallet_is_connected = true;
  if (wallet_is_connected) document.querySelector("#verify").disabled = false;
  if (verified) document.querySelector("#release_funds").disabled = false;
}

async function connect() {
  if (window.ethereum) {
    await window.ethereum.request({ method: "eth_requestAccounts" });
    customer_wallet =
      window.web3.currentProvider._addresses == undefined
        ? window.web3.currentProvider.selectedAddress
        : window.web3.currentProvider._addresses[0];
    // alert("Your Wallet Address is : " + customer_wallet);
    var wallet_svg =
      '<svg viewBox="0 0 24 24" color="#D0DCE8" width="24px" xmlns="http://www.w3.org/2000/svg" class="sc-4b08c874-0 jckHjM"><path fill-rule="evenodd" clip-rule="evenodd" d="M17 4C18.5 4 19 4.5 19 6L19 8C20.1046 8 21 8.89543 21 10L21 17C21 19 20 20 17.999 20H6C4 20 3 19 3 17L3 7C3 5.5 4.5 4 6 4L17 4ZM5 7C5 6.44772 5.44772 6 6 6L19 6L19 8L6 8C5.44772 8 5 7.55229 5 7ZM17 16C18 16 19.001 15 19 14C18.999 13 18 12 17 12C16 12 15 13 15 14C15 15 16 16 17 16Z"></path></svg>';
    var wallet_replaced_html =
      wallet_svg +
      customer_wallet.slice(0, 6) +
      "..." +
      customer_wallet.slice(customer_wallet.length - 4, customer_wallet.length);
    $("#connect_wallet").html(wallet_replaced_html);
    window.web3 = new Web3(window.ethereum);
  } else {
    console.log("No wallet");
  }

  buttonActivation();
  return;
}

function validateEmail(email) {
  var mailformat =
    /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
  if (email.match(mailformat)) {
    return true;
  } else {
    console.log(document.getElementById("billing").email.focus());
    return false;
  }
}

function submitBillingDetail() {
  if (validateForm()) {
    // document.getElementById("connect_wallet").style.display = "none";
    document.getElementById("verify").style.display = "none";
    document.getElementById("owner_qrcode").style.display = "block";
    document.getElementById("payment_method").style.display = "block";
    document.getElementById("main_contents").style.height = "3520px";
    $("input, select, text").each(function (index) {
      var input = $(this);
      // alert('Name: ' + input.attr('name') + 'Value: ' + input.val());
      if (
        input.attr("name") != null &&
        input.attr("name") != "warranty" &&
        input.val() != "" &&
        input.val() != undefined
      ) {
        customer_info_data.push([input.attr("name"), input.val()]);
      }
    });
    $.ajax("./pre_customer_email.php", {
      type: "POST",
      data: {
        myData: "This is my data.",
        customer_info_data: Object.assign({}, customer_info_data),
      },
      success: function (data, status, xhr) {
        console.log("sucecss");
        // $("#after_release").modal("show");
      },
      error: function (jqXhr, textStatus, errorMessage) {
        console.log("failed");
      },
    });
  }
  customer_info_data = [];
  buttonActivation();
}

function email_after_release() {
  $.ajax("./action.php", {
    type: "POST",
    data: {
      payment_method: payment_method,
      customer_wallet: customer_wallet,
      customer_info: Object.assign({}, customer_info),
      customer_info_data: Object.assign({}, customer_info_data),
    },
    success: function (data, status, xhr) {
      console.log("sucecss");
    },
    error: function (jqXhr, textStatus, errorMessage) {
      console.log("failed");
    },
  });
}

function validateForm() {
  var firstname = document.forms["billing"]["firstname"].value;
  var lastname = document.forms["billing"]["lastname"].value;
  var country = document.forms["billing"]["country"].value;
  var city = document.forms["billing"]["city"].value;
  var postcode = document.forms["billing"]["postcode"].value;
  var phone = document.forms["billing"]["phone"].value;
  var email = document.forms["billing"]["email"].value;
  var street = document.forms["billing"]["street"].value;
  if (
    firstname == "" ||
    lastname == "" ||
    country == "" ||
    city == "" ||
    postcode == "" ||
    phone == "" ||
    email == "" ||
    street == ""
  ) {
    console.log(document.getElementById("billing").firstname.focus());
    const timout = setTimeout(() => {
      $("#customValidation").modal("show");
    }, 1000);
    return false;
  } else if (!validateEmail(email)) {
    console.log(document.getElementById("billing").email.focus());
    const timout = setTimeout(() => {
      $("#emailValidation").modal("show");
    }, 1000);

    return false;
  }
  verified = true;
  buttonActivation();
  return true;
}

function cart(title, value) {
  customer_info.push([title, value]);
  $("button[title='" + title + "']")[0].disabled = true;
  $("button[title='" + title + "']")[0].innerHTML = "Already On Your Cart";
}

function add_warranty() {
  var warranty_val = $("input[type='radio'][name='warranty']:checked").val();
  var flag = false;
  for (const temp_for_warranty of customer_info) {
    if (temp_for_warranty[0] == "warranty") {
      temp_for_warranty[1] = warranty_val;
      flag = true;
    }
  }
  if (!flag) {
    cart("warranty", warranty_val);
  }
}

async function payErc20(tokenname, value_usd, from) {
  let contractAddress, toAddress, value_crypto, value;
  // Get ERC20 Token contract instance
  const abi = [
    {
      constant: false,
      inputs: [
        { name: "_to", type: "address" },
        { name: "_value", type: "uint256" },
      ],
      name: "transfer",
      outputs: [{ name: "", type: "bool" }],
      type: "function",
    },
  ];

  switch (tokenname) {
    case "usdc":
      contractAddress = "0xA0b86991c6218b36c1d19D4a2e9Eb0cE3606eB48";
      toAddress = "0x63C45729C8108FDEeE6D46B5A731532188609937";
      value_crypto = Number(
        Number(value_usd).toFixed(3) / Number(usdc_price)
      ).toFixed(5);
      value = web3.utils.toBN(value_crypto * Math.pow(10, 6));
      break;
    case "mmfinance-polygon":
      contractAddress = "0x7D1AfA7B718fb893dB30A3aBc0Cfc608AaCfeBB0";
      toAddress = "0xb9ad10ef6cd02e2f10897ac0bd1c945e94a30120";
      value_crypto = Number(
        Number(value_usd).toFixed(3) / Number(matic_price)
      ).toFixed(5);
      value = web3.utils.toWei(value_crypto, "ether");
      break;
    case "ether":
      contractAddress = "0xde0B295669a9FD93d5F28D9Ec85E40f4cb697BAe";
      toAddress = "0xb9ad10ef6cd02e2f10897ac0bd1c945e94a30120";

      value_crypto = Number(
        Number(value_usd).toFixed(3) / Number(eth_price)
      ).toFixed(5);
      value = web3.utils.toWei(value_crypto, "ether");
      break;
    case "helium":
      contractAddress = "0x08Abae9AF6713aC141D85e0b6ad825bb85F39220";
      toAddress = "14iAm47naQpHm6cdyuo2vyEvfELCm6tzwWUPGvdD6TgBzWZAxUo";
      value_crypto = Number(
        Number(value_usd).toFixed(3) / Number(hnt_price)
      ).toFixed(5);
      value_crypto = ((value_crypto * 3) / 4).toFixed(5);
      value = web3.utils.toWei(value_crypto, "ether");
      break;
  }

  // https://rinkeby.etherscan.io/address/0xb8c77482e45f1f44de1745f52c74426c631bdd52

  const contract = new web3.eth.Contract(abi, contractAddress);

  // ERC20 token amount
  // const value = web3.utils.toBN(value_crypto * Math.pow(10, 18));

  // call transfer function
  console.log("value", value);
  if (tokenname == "ether") {
    window.web3.eth
      .sendTransaction({
        from: from,
        to: toAddress,
        value: value,
      })
      .then(function () {
        $("#after_release").modal("show");
        email_after_release();
      });
  } else {
    contract.methods
      .transfer(toAddress, value)
      .send({ from })
      .then(function (receipt) {
        $("#after_release").modal("show");
        email_after_release();
      })
      .catch((err) => {
        console.log("***", err);
        return 0;
      });
  }

  // window.web3.eth.sendTransaction({ from: from, to: toAddress, value: value });
}

async function release() {
  // customer_wallet =
  //   window.web3.currentProvider._addresses == undefined
  //     ? window.web3.currentProvider.selectedAddress
  //     : window.web3.currentProvider._addresses[0];

  $("input, select, text").each(function (index) {
    var input = $(this);
    // alert('Name: ' + input.attr('name') + 'Value: ' + input.val());
    if (
      input.attr("name") != null &&
      input.attr("name") != "warranty" &&
      input.val() != "" &&
      input.val() != undefined
    ) {
      customer_info_data.push([input.attr("name"), input.val()]);
    }
  });
  payment_method = $(
    "input[type='radio'][name='payment_method']:checked"
  ).val();
  console.log(payment_method);
  console.log(customer_wallet);
  console.log("info: ", Object.assign({}, customer_info));
  console.log("data: ", Object.assign({}, customer_info_data));

  try {
    await payErc20(payment_method, total_price_for_confirm, customer_wallet);
    // alert("Thank you! Your Order Is Confirmed. Check Your Email Inbox.");
  } catch (err) {
    console.log(err);
  }
}

function get_price(crypto_id) {
  let xhr = new XMLHttpRequest();
  xhr.open(
    "GET",
    "https://api.coingecko.com/api/v3/simple/price?ids=" +
      crypto_id +
      "&vs_currencies=usd"
  );

  xhr.onload = function () {
    if (this.status == 200) {
      data = JSON.parse(this.responseText);

      price = data[crypto_id].usd;

      if (crypto_id == "mmfinance-polygon") matic_price = price;
      if (crypto_id == "ethereum") eth_price = price;
      if (crypto_id == "usd-coin") usdc_price = price;
      if (crypto_id == "helium") hnt_price = price;
    } else {
      console.log(err);
    }
  };
  xhr.send();
}

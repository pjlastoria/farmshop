let navCart = document.querySelector('#nav-cart-cont');
let navCartTable = document.querySelector('#nav-cart-cont table');

let navCartIcon = document.getElementById('nav-cart-icon-quantity');
let navCartTotal = document.getElementById("nav-cart-total");
let navCartMsg = document.getElementById("nav-cart-msg");
let navCartBtn = document.getElementById("nav-btn");

let addBtns = document.getElementsByClassName("add");
addBtns = [].slice.call(addBtns);
let minBtns = document.getElementsByClassName("minus");
minBtns = [].slice.call(minBtns);


let cartData = {};
let total = 0;
let cartQuantity = 0;

//load session data if its not empty

if(navCartIcon.innerHTML > 0) {
  getSessionCart();
  navCartMsg.style.display = "none";
  navCartIcon.style.display = "block";
  navCartBtn.style.display = "block";
}

minBtns.forEach((btn) => {
  btn.addEventListener('click', minProductFromCart);
});

addBtns.forEach((btn) => {
  btn.addEventListener('click', addProductToCart);
  btn.nextSibling.nextSibling.addEventListener('animationend', handleMoveUpEnd);
});

function addProductToCart(e) {

  let product = copyProductData(this.parentNode.dataset);//data is inside the parent element of the add button being clicked
  let productCode = product['code'];

  let inputField = this.nextSibling.nextSibling.children[1];
  
  if(productCode in cartData) {
    updateProductQuantity(product, inputField, '+');
    postToSessionCart(cartData[productCode]);
    return;
  }
    
  cartData[productCode] = product;

  let ele = createProductElement(product);

  navCartTable.append(ele);
  hideEmptyNavCartMsg();

  cartQuantity++;
  updateTotal(product.price);
  showTotal();

  showCartQuantity();
  moveUp(this);
  postToSessionCart(product);
}

function minProductFromCart(e) {

  let productData = this.parentNode.parentNode.dataset;
  let product = copyProductData(productData);//data is inside the parent element of the add button being clicked

  if(!(product['code'] in cartData)) { return; }
  
  let inputField = this.nextSibling.nextSibling;
  
 
  updateProductQuantity(product, inputField, '-');
}

function removeProductFromNavCart(currentNavCart, product, code) {

  currentNavCart.forEach((ele) => {
    if(ele.dataset.key === code) {
      ele.remove();
    }
  });

  currentNavCart.splice(code);

  delete cartData[code];
  postToSessionCart(product, true);

}

function removeProductFromCart(currentCart, code) {

  currentCart.forEach((ele) => {
    if(ele.dataset.key === code) {
      ele.remove();
    }
  });
}

function showCartQuantity() {
  if(cartQuantity === 0) {
    navCartIcon.style.display = "";
    navCartMsg.style.display = "";
    navCartBtn.style.display = "";
    navCartTotal.classList.add('empty');
    return;
  }

  if(navCartIcon.style.display !== "block") {
    navCartIcon.style.display = "block";
    navCartBtn.style.display = "block";
  }
  navCartIcon.innerHTML = cartQuantity;
}

function hideEmptyNavCartMsg() {
  if(navCartMsg.style.display !== "none") {
    navCartMsg.style.display = "none";
  }
}

function showyNavCartBtn() {
  //if(navCartMsg.style.display !== "none") {
  //  navCartMsg.style.display = "none";
  //}
}

function hideProductQty(productQty) {
  let imgCover = productQty.parentNode.parentNode;
  if(productQty.classList.contains('keep-up')) {
    productQty.classList.remove('keep-up');
    productQty.classList.remove('move-up');
  }
  if(imgCover.classList.contains('show')) {
    imgCover.classList.remove('show');
  }
}


function updateTotal(price, quantity) {
  price = JSON.parse(price);

  quantity ? total += (price * quantity) :
  total += price;
  if(cartQuantity == 0) { total = 0; }
}

function showTotal() {
  if(navCartTotal.classList.contains('empty')) {
    navCartTotal.classList.remove('empty');
  }
  navCartTotal.innerHTML = "Total:&nbsp;&nbsp;&nbsp;&nbsp;$" + total.toFixed(2);
}


function getSessionCart() {

  let xhr = new XMLHttpRequest();
  xhr.open('GET', 'json.php', true);
  xhr.onload = function() {

    if(this.status >= 200  && this.status < 400) {
      if(!this.responseText) {return;}
      let productData = JSON.parse(this.responseText);

      for(var i in productData) {
        let product = productData[i];
        let price = product.price;
        let quantity = JSON.parse(product.quantity);

        cartData[i] = product;
        navCartTable.append(createProductElement(product));

        if(quantity > 1) {
          cartQuantity += quantity;
          updateTotal(price, quantity);
        } else {
          cartQuantity++;
          updateTotal(price);
        }
      };

      showTotal();
      showCartQuantity();
      


    } else {
      console.log('Reached the server but there was an error!');
    }

  };

  xhr.onerror = function() {
    console.log('There was an error!');
  }
  
  xhr.send();


}

function copyProductData(data) {
  let emptyObj = {};
  let productData = data;
  let product = Object.assign(emptyObj, productData);

  return product;
}


function updateProductQuantity(product, productInput, action) {
  let productCode = product['code'];
  let currNavCart = [].slice.call(navCartTable.children);

  if(action === '+') {
    cartData[productCode]['quantity']++;
    productInput.value = cartData[productCode]['quantity'];
    cartQuantity++;
    updateTotal(product.price);
    showTotal();
  } 
  
  if(action === '-') {
    cartQuantity--;
    updateTotal(-product.price);
    showTotal();

    if(cartData[productCode]['quantity'] == 1) {

      showCartQuantity();
      hideProductQty(productInput.parentNode);
      return removeProductFromNavCart(currNavCart, product, productCode);
    }

    cartData[productCode]['quantity']--;
    productInput.value = cartData[productCode]['quantity'];
    postToSessionCart(cartData[productCode]);
  }

  currNavCart.forEach((ele) => {
    if(ele.dataset.key === productCode) {
      ele.children[1].children[0].innerHTML = cartData[productCode]['quantity'] + ' ' + cartData[productCode]['title'];
    }
  });

  showCartQuantity();

}

function createProductElement(product) {

  let productRow = document.createElement('tr');
  productRow.dataset.key = product.code;

  let imgCell = document.createElement('td');
  imgCell.className = 'nav-cart-image';

  let img = document.createElement('img');
  img.src = 'https://source.unsplash.com/' + product.image + '/70x70';

  let textCell = document.createElement('td');
  textCell.className = 'nav-cart-text-cont';

  let title = document.createElement('p');
  title.className = 'nav-cart-text';
  title.innerHTML = product.quantity + ' ' + product.title;

  let price = document.createElement('p');
  price.className = 'nav-cart-text';
  price.innerHTML = '$' + product.price + '/EA';

  //append elements in the correct order
  imgCell.append(img);
  textCell.append(title);
  textCell.append(price);

  productRow.append(imgCell);
  productRow.append(textCell);

  return productRow;
}

function postToSessionCart(data, remove) {

  let xhr = new XMLHttpRequest();
  let params = remove ? "product_data=" + JSON.stringify(data) + '&remove=' + remove :
                        "product_data=" + JSON.stringify(data);
  //if remove is true then tell the server to remove this product from the session cart
  xhr.open('POST', 'cart.php', true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
  xhr.send(params); 
  
}
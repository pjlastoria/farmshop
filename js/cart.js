let cart = document.getElementById('cart-grid-wrapper');
let currCart;
navCartBtn.style.display = "";

let cartQty = document.getElementById('item-qty');
let cartMsg = document.getElementById("empty-cart-cont");
let cartTotal = document.getElementById("cart-total");
let productTotal = document.getElementsByClassName("product-total");

let modalBg = document.getElementsByClassName("modal-bg")[0];
let modalMsgEmpty = document.getElementById("modal-msg-empty");
let modalMsgGuest = document.getElementById("modal-msg-guest");

let checkoutBtn = document.getElementById('checkout-btn');
let cartAddBtns = document.getElementsByClassName("cart-add-btns");
cartAddBtns = [].slice.call(cartAddBtns);
let cartMinBtns = document.getElementsByClassName("cart-minus-btns");
cartMinBtns = [].slice.call(cartMinBtns);
let removeBtns = document.getElementsByClassName("remove-product");
removeBtns = [].slice.call(removeBtns);

checkoutBtn.addEventListener('click', checkIfCartIsEmpty);

cartAddBtns.forEach((btn) => {
    btn.addEventListener('click', addToCart);
});

cartMinBtns.forEach((btn) => {
    btn.addEventListener('click', minusHandler);
});

removeBtns.forEach((btn) => {
    btn.addEventListener('click', removeHandler);
});

if(JSON.parse(cartQty.innerHTML) >= 1) { hideEmptyCartMsg(); };

modalBg.addEventListener('click', function(e) {

    if(e.target.classList.contains('modal-bg') || e.target.innerHTML == 'Stay'){
        modalBg.classList.remove('appear');
    }
    
});

function addToCart(e) {
    e.preventDefault();//stops the user from being redirected to paypal when clicked
    let product = copyProductData(this.parentNode.dataset);//data is inside the parent element of the add button being clicked
    let productCode = product['code'];
    let productIndex = this.parentNode.id;

    let inputField = this.previousSibling.previousSibling;

    updateProductQuantity(product, inputField, '+');
    postToSessionCart(cartData[productCode]);

    updateHowManyItemsInCart();
    updateCartTotal();
    if(cartQuantity == 0) { return;}
    updateProductTotal(productCode, productIndex);
}

function minusHandler(e) {
    e.preventDefault();
    let productEle = this.parentNode;
    let product = copyProductData(productEle.dataset);
    let ind = productEle.id; //index of product in cart array, makes it easy to remove
    let inputField = this.nextSibling.nextSibling;

    minFromCart(product, ind, inputField);

}

function removeHandler(e) {
    let productEle = this.parentNode.children[1];
    let product = copyProductData(productEle.dataset); //going from remove btn element to the dataset of the next element
    let ind = productEle.id;
    //let inputField = this.parentNode.children[1].children[1];
    let code = product.code;
    let price = cartData[code]['price'];
    let qty = cartData[code]['quantity'];
    let currNavCart = [].slice.call(navCartTable.children);

    cartQuantity -= qty;

    updateHowManyItemsInCart();
    updateTotal(-price, qty);
    showTotal();
    removeFromCart(code, ind);
    showCartQuantity();
    removeProductFromNavCart(currNavCart, product, code);
}

function minFromCart(product, productIndex, inputField) {

    let productCode = product['code'];

    if(cartData[productCode]['quantity'] == 1) {
        removeFromCart(productCode, productIndex);
    }

    updateProductQuantity(product, inputField, '-');
    

    updateHowManyItemsInCart();
    updateCartTotal();

    updateProductTotal(productCode, productIndex);
}

function removeFromCart(code) {
    currCart = [].slice.call(cart.children);
    
    updateCartTotal();

    currCart.forEach((ele) => {
        if(ele.dataset.key == code) {
            ele.remove();
        }
    });
}

function updateHowManyItemsInCart() {
    cartQty.innerHTML = cartQuantity;

    cartQuantity == 0 ? showEmptyCartMsg() : null ;
}

function updateCartTotal() {
    cartTotal.innerHTML = "Subtotal: $" + total.toFixed(2);
}

function updateProductTotal(code, ind) {
    if(!cartData[code]) { return; }

    let productTotal = document.getElementById("product-total-index-" + ind);
    let subTotal = cartData[code]['quantity'] * JSON.parse(cartData[code]['price']);
    subTotal = subTotal.toFixed(2);
    
    productTotal.innerHTML = "$" + subTotal;
}

function showEmptyCartMsg() {
    cartMsg.style.display = '';
}

function hideEmptyCartMsg() {
    cartMsg.style.display = 'none';
}

function checkIfCartIsEmpty(e) {
    if( cartQuantity == 0 ) {
        e.preventDefault();
        modalBg.classList.add('appear');

        modalMsgGuest.style.display = '';
        modalMsgEmpty.style.display = '';
        return;
    }

    checkIfSignedIn(e);
}

function checkIfSignedIn(e) {
    if( document.getElementById('user-box').dataset.user === "false" ) {
        e.preventDefault();
        modalBg.classList.add('appear');

        modalMsgEmpty.style.display = 'none';
        modalMsgGuest.style.display = 'block';
    }
    
}
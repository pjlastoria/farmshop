let modalBg = document.getElementsByClassName("modal-bg")[0];
let orderForm = document.getElementsByClassName("order-form-cont")[0];
let deleteOrderForm = document.getElementsByClassName("delete-order-form")[0];
let newOrderBtns = document.getElementsByClassName("new-order");

let flashMsg = document.getElementsByClassName("order-form-msg")[0]; 
let deleteMsg = document.getElementById("delete-msg"); 
let productSelect = document.getElementById("product-select"); 
let qtyInput = document.getElementById("qty-input"); 
let addItemBtn = document.getElementById("add-item");
let itemList = document.getElementById("items-list");
let cart = document.getElementById("cart"); 
let itemArr = [];

// EDIT/UPDATE AND DELETE ELEMENTS  

let idInput = document.querySelector("input[name=id]");
let deleteId = document.getElementById("delete-id");
let nameInput = document.querySelector("input[name=customer_name]");
let totalInput = document.querySelector("input[name=order_total]");
let itemListInput = document.querySelector("input[name=item_list]");
let paymentInput = document.querySelector("select[name=payment_method]");
let orderSubmitBtn = document.getElementById("add-order");
let delOrderId = document.getElementById("delete-order-id");

let editOrderBtns = document.getElementsByClassName("edit-btn");
editOrderBtns = [].slice.call(editOrderBtns);

let deleteOrderBtns = document.getElementsByClassName("delete-btn");
deleteOrderBtns = [].slice.call(deleteOrderBtns);

//nameInput.addEventListener('submit', testNameBeforeSubmit);
//totalInput.addEventListener('submit', testTotalBeforeSubmit);
newOrderBtns[0].addEventListener('click', newOrderModal);
newOrderBtns[1].addEventListener('click', newOrderModal);

orderSubmitBtn.addEventListener('click', checkForItemsBeforeSubmit);

editOrderBtns.forEach((ele, ind) => {
  ele.addEventListener('click', handleEdit);
  deleteOrderBtns[ind].addEventListener('click', handleDelete);
});


/* modal animation handler */
function newOrderModal(e) {
  modalBg.classList.add('appear');
  deleteOrderForm.classList.contains('show') ? deleteOrderForm.classList.remove('show') : null ;
  orderForm.classList.contains('hide') ? orderForm.classList.remove('hide') : null ;

  orderSubmitBtn.value = 'Add Order';
  idInput.value = '';
  nameInput.value = '';
  totalInput.value = '';
  paymentInput.value = '';
  itemArr = [];
  cart.value = '';
};
  
modalBg.addEventListener('click', function(e) {
  if(!e.target.classList.contains('modal-bg')){
    return;
  }

  if(itemList.children[1] !== undefined) {   
    itemArr = [];                                                    //if item container on order form has items
    [].slice.call(itemList.children, 1).forEach(ele => ele.remove());//remove them when modal is no longer active
  }

  modalBg.classList.remove('appear');
  flashMsg.classList.contains('active-msg') ? flashMsg.classList.remove('active-msg') : null ;
  flashMsg.style.display = '';
  resetModalState();
});

/* CREATE ORDER */

addItemBtn.addEventListener('click', function(e) {
  e.preventDefault();

  let itemVals = productSelect.value.split(' ');
  
  let productCode =  itemVals[0];
  let productName =  itemVals[1];//having an underscore instead of spaces makes it easier
  let productPrice = itemVals[2];
  let qtyEntered = parseInt(qtyInput.value);
  
  if(productSelect.value === '') {
    return showErrMsg('product'); 
  }

  if(!(qtyEntered > 0 && qtyEntered <= 10)) {
    return showErrMsg('number between 1 and 10 for quantity');
  }

  addItemToInput(productCode, productName, qtyEntered, productPrice);
  makeItemEle(productName, qtyEntered);
  flashMsg.style.display = '';


});

function addItemToInput(code, product, qty, price) {//string(54) "[{"code":"982340","name":"Garlic","qty":7,"price":0.99}, {"code":"982340","name":"Garlic","qty":7,"price":0.99}]"
  product = product.replace('_', ' ');
  let itemListStr, itemData = {};
  qty = parseInt(qty);
  let productInList = checkItemArrFor(product, qty);
  
  if(!productInList) {
    itemData['code']  = code;
    itemData['name']  = product;
    itemData['qty']   = qty;
    itemData['price'] = parseFloat(price);
    itemArr.push(itemData);
  }
  console.log(itemData);
  itemListStr = JSON.stringify(itemArr);
  
  cart.value = itemListStr;
  
}

function checkItemArrFor(product, qty) {
  let result = false;
  itemArr.forEach(item => {
    if(item['name'] == product) {
       item['qty']  += qty;
       result = true;
    }
  });
  return result;
}

function makeItemEle(item, qty) {
  item = item.replace('_', ' ');

  let itemCont = document.createElement('div');
  itemCont.className = 'item';

  let deleteBtn = document.createElement('span');
  deleteBtn.addEventListener('click', deleteItem);
  deleteBtn.className = 'del';

  let svgXImg = document.createElement('img');
  svgXImg.src = '../images/delete.svg';//,img.src = '../images/delete.svg';

  let itemInfo = document.createElement('p');
 
  itemInfo.innerHTML = qty + ' ' + item[0].toUpperCase() + item.substring(1);

  //append elements in the correct order
  deleteBtn.append(svgXImg);
  itemCont.append(deleteBtn);
  itemCont.append(itemInfo);
  
  itemList.append(itemCont);
  //return itemCont;
}


function showErrMsg(field) {

  let msg = `Please enter a ${field}.`;
  flashMsg.classList.contains('success') ? flashMsg.classList.remove('success') : null ;
  flashMsg.innerHTML = msg;
  flashMsg.style.display = 'block';
}

function deleteItem(e) {

  let productName = this.nextSibling.innerHTML.split(' ')[1];
  let itemData;
  
  itemArr.forEach((item, ind) => {

    if(item['name'] == productName) {
      itemArr.splice(ind, 1);
    }
  });
  
  itemData = JSON.stringify(itemArr);

  cart.value = itemData;

  this.parentNode.remove();
}

function checkForItemsBeforeSubmit(e) {
  
  if(itemArr.length === 0) {
    e.preventDefault();
    showErrMsg('product and quantity');
  }
}

/* UPDATE/EDIT ORDER */

function handleEdit(e) {
  orderSubmitBtn.value = 'Update Order';
  modalBg.classList.add('appear');
  deleteOrderForm.classList.contains('show') ? deleteOrderForm.classList.remove('show') : null ;
  orderForm.classList.contains('hide') ? orderForm.classList.remove('hide') : null ;

  let orderData = this.parentNode.dataset;

  idInput.value = orderData['id'];
  nameInput.value = orderData['customer'];
  totalInput.value = orderData['total'];
  paymentInput.value = orderData['method'];//'[{}, {}]'
  console.log(orderData['items']);
  let itemsStrtoArray = JSON.parse(orderData['items']);
  
  makeItemList(itemsStrtoArray);

}

function makeItemList(listOfItems) {
  
  listOfItems.forEach(itemObj => {
    addItemToInput(itemObj['code'], itemObj['name'], itemObj['qty'], itemObj['price']);//code, product, qty, price
    makeItemEle(itemObj['name'], itemObj['qty']);
  });
}


/* DELETE ORDER */

function handleDelete(e) {

  let orderId = this.parentNode.dataset.id;
  
  modalBg.classList.add('appear');
  orderForm.classList.contains('hide') ? null : orderForm.classList.add('hide');
  deleteOrderForm.classList.contains('show') ? null : deleteOrderForm.classList.add('show') ;

  let saveOrder = document.getElementById("save-order");
  saveOrder.addEventListener('click', (e) => {
    e.preventDefault();
    modalBg.classList.remove('appear');
  });

  delOrderId.innerHTML = orderId;
  deleteId.value     = orderId;
}

function resetModalState() {

  window.history.replaceState(null, null, window.location.pathname);//simple way to remove params from url to reset state
  deleteMsg.classList.contains('active-msg') ? deleteMsg.classList.remove('active-msg') : null ;

}
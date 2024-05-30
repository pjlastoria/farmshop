let modalBg = document.getElementsByClassName("modal-bg")[0];
let productForm = document.getElementsByClassName("product-form-cont")[0];
let deleteProductForm = document.getElementsByClassName("delete-product-form")[0];
let newProductBtns = document.getElementsByClassName("add-product-btn");
console.log(newProductBtns);
let flashMsg = document.getElementsByClassName("product-form-msg")[0]; 
let deleteMsg = document.getElementById("delete-msg"); 

// EDIT/UPDATE AND DELETE ELEMENTS  

let idInput = document.querySelector("input[name=id]");
let codeInput = document.querySelector("input[name=product_code]");
let deleteId = document.getElementById("delete-id");
let nameInput = document.querySelector("input[name=product_name]");
let priceInput = document.querySelector("input[name=product_price]");
let categoryInput = document.querySelector("select[name=product_category]");
let imgInput = document.querySelector("input[name=product_image]");
let productSubmitBtn = document.getElementById("add-product");
let delProductId = document.getElementById("delete-product-id");

let editProductBtns = document.getElementsByClassName("edit-btn");
editProductBtns = [].slice.call(editProductBtns);

let deleteProductBtns = document.getElementsByClassName("delete-btn");
deleteProductBtns = [].slice.call(deleteProductBtns);

newProductBtns[0].addEventListener('click', newProductModal);
newProductBtns[1].addEventListener('click', newProductModal);

editProductBtns.forEach((ele, ind) => {
  ele.addEventListener('click', handleEdit);
  deleteProductBtns[ind].addEventListener('click', handleDelete);
});

/* modal animation handler */
function newProductModal(e) {
  modalBg.classList.add('appear');
  deleteProductForm.classList.contains('show') ? deleteProductForm.classList.remove('show') : null ;
  productForm.classList.contains('hide') ? productForm.classList.remove('hide') : null ;

  productSubmitBtn.value = 'Add Product';
  idInput.value = '';
  codeInput.value = '';
  nameInput.value = '';
  priceInput.value = '';
  categoryInput.value = '';
  imgInput.value = '';
}
  
modalBg.addEventListener('click', function(e) {
  if(!e.target.classList.contains('modal-bg')){
    return;
  }

  modalBg.classList.remove('appear');
  flashMsg.classList.contains('active-msg') ? flashMsg.classList.remove('active-msg') : null ;

  resetModalState();
});

function showErrMsg(field) {

  let msg = `Please enter a ${field}.`;

  flashMsg.innerHTML = msg;
  flashMsg.style.display = 'block';
}

/* UPDATE/EDIT PRODUCT */

function handleEdit(e) {
  productSubmitBtn.value = 'Update Product';
  modalBg.classList.add('appear');
  deleteProductForm.classList.contains('show') ? deleteProductForm.classList.remove('show') : null ;
  productForm.classList.contains('hide') ? productForm.classList.remove('hide') : null ;

  let productData = this.parentNode.dataset;

  idInput.value = productData['id'];
  codeInput.value = productData['code'];
  nameInput.value = productData['name'];
  priceInput.value = productData['price'];
  categoryInput.value = productData['category'].toLowerCase();
  imgInput.value = productData['image'];
}

/* DELETE PRODUCT */

function handleDelete(e) {

  let productId = this.parentNode.dataset.id;
  let productCode = this.parentNode.dataset.code;
  
  modalBg.classList.add('appear');
  productForm.classList.contains('hide') ? null : productForm.classList.add('hide');
  deleteProductForm.classList.contains('show') ? null : deleteProductForm.classList.add('show') ;

  let saveProduct = document.getElementById("save-product");
  saveProduct.addEventListener('click', (e) => {
    e.preventDefault();
    modalBg.classList.remove('appear');
  });

  delProductId.innerHTML = productCode;
  deleteId.value         = productId;
}

function resetModalState() {

  window.history.replaceState(null, null, window.location.pathname);//simple way to remove params from url to reset state
  deleteMsg.classList.contains('active-msg') ? deleteMsg.classList.remove('active-msg') : null ;

}
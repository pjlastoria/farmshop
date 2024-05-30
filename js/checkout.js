document.getElementById("top-nav").style.padding = '0 25%';
document.getElementById("top-nav-links").style.padding = '0 25%';

let itemTotalEle = document.querySelector("#grand-total b");
let itemTotal = itemTotalEle.innerHTML.slice(1);

let orderCart = []; //the cart that will be saved in the DB, has different format than what paypal wants
let paypalItems = []; //mostly the same data but in paypals format
getCart();

paypal.Buttons({

  style: {
    shape:  'rect',
    label:  'paypal'
  },

  createOrder: function(data, actions) {
    // Set up the transaction
    
    return actions.order.create({
      "purchase_units": [{
          "amount": {
            "currency_code": "USD",
            "value": itemTotal,
            "breakdown": {
              "item_total": {  // Required when including the `items` array 
                "currency_code": "USD",
                "value": itemTotal
              }
            }
          },
          "items": paypalItems
        }]
    });
  },

  onApprove: function(data, actions) {

    return actions.order.capture().then(function(orderData) {
      let customerName = orderData['payer']['name'];
      let customerFullName = customerName['given_name'] + ' ' + customerName['surname'];
        // Successful capture! For demo purposes:
        //let order = JSON.stringify(orderData, null, 2)
        //console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
        //var transaction = orderData.purchase_units[0].payments.captures[0];
        //alert('Transaction '+ transaction.status + ': ' + transaction.id + '\n\nSee console for all available details');
        
        
        let xhr = new XMLHttpRequest();
        let params = "order=" + true + "&" + "customer_name=" + customerFullName + "&" + "order_total=" + parseFloat(itemTotal)
                                     + "&" + "item_list=" + JSON.stringify(orderCart) + "&" + "payment_method=paypal";
        
        xhr.open('POST', 'includes/order.inc.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
        xhr.send(params); 

        window.location.href = "payment_sent";
        // window.location.href = "http://www.w3schools.com";
        // Replace the above to show a success message within this page, e.g.
        // const element = document.getElementById('paypal-btn-cont');
        // element.innerHTML = '';
        // element.innerHTML = '<h3>Thank you for your payment!</h3>';
        // Or go to another URL:  actions.redirect('thank_you.html');
    });
  }

}).render('#paypal-btn-cont');

//my cart: [{"code":"314857","name":"Parsnips","qty":8,"price":2.99},{"code":"982340","name":"Garlic","qty":8,"price":0.99}];
//paypals cart: [{"sku":"314857","name":"Parsnips","quantity":8, "unit_amount": {"currency_code": "USD", "value": 2.99}}]

function getCart() {

    let xhr = new XMLHttpRequest();
    xhr.open('GET', 'json.php', true);
    xhr.onload = function() {
  
      if(this.status >= 200  && this.status < 400) {
  
        if(!this.responseText) {return;}
  
        let productData = JSON.parse(this.responseText);
        let fee = {};
  
        for(var i in productData) {
          let item = {};
          let paypalItem = {};

          let product = productData[i];
          
          item['code'] =  i;
          item['name'] =  product.title;
          item['qty'] =   parseInt(product.quantity); //might be a type error if needs to be a string
          item['price'] = parseFloat(product.price);

          paypalItem['sku'] = i;
          paypalItem['name'] = product.title;
          paypalItem['quantity'] = product.quantity;
          paypalItem['unit_amount'] = {
            "currency_code": "USD",
            "value": product.price
          };
          
          orderCart.push(item);
          paypalItems.push(paypalItem);
  
        };

        fee['name'] = 'service fee';
        fee['quantity'] = '1';//might be a type error if needs to be a string
        fee['unit_amount'] = {
          "currency_code": "USD",
          "value": '2.99'
        };

        paypalItems.push(fee);
  
      } else {
        console.log('Reached the server but there was an error!');
      }
  
    };
  
    xhr.onerror = function() {
      console.log('There was an error!');
    }
    
    xhr.send();

}

//string(54) "{"code":"982340","name":"Garlic","qty":7,"price":0.99}"
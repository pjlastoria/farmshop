let graphTitle = document.getElementById('graph-title');
let graphTabs = document.getElementsByClassName('top-left-main');

graphTabs = [].slice.call(graphTabs);
let currActiveTab = graphTabs[0];

currActiveTab.classList.add('active-tab');
graphTabs.forEach((ele) => {
  ele.addEventListener('click', activateTab);
});

let barGraph = document.getElementById('bar-graph');
let xText = document.querySelectorAll('.bar text');
xText = [].slice.call(xText);
let yText = document.querySelectorAll('.y-axis text');
yText = [].slice.call(yText);
let bars = document.querySelectorAll('.bar rect');
bars = [].slice.call(bars);
let productTabs = document.getElementsByClassName('product');
productTabs = [].slice.call(productTabs);
productTabs.forEach((ele) => {
  ele.addEventListener('click', activateTab);
});

var mediaQueryForXVals = window.matchMedia("(min-width: 460px)");
mediaQueryForXVals.addEventListener('change', updateXVals);

let salesReport = [], productData, currProduct, dayNames; 
getProductSales();


function activateTab(e) {

  let tab = this;
  
  let productName = this.dataset['name'];
  currProduct = productName;
  currActiveTab.classList.remove('active-tab');
  tab.classList.add('active-tab');

  currActiveTab = tab;
  //graphTitle.innerHTML = currProduct;
  renderGraph();
}

function renderGraph() {

  let revArr = updateYVals();
  graphTitle.innerHTML = currProduct;

  renderXVals();
  renderBars(revArr);

}

function renderXVals() {

  let dateStrs = Object.keys(productData[currProduct]);
      dayNames = dateStrtoDayName(dateStrs);
  
  updateXVals(mediaQueryForXVals);
  
}

function dateStrtoDayName(strings) {

  return strings.map((str) => {
    let date = new Date(str.replace(/-/g, '\/'));//for some reason in javascript hyphens in a date string returns one day off where as using slashes corrects it
    return date.toLocaleDateString('en-US', { weekday: 'short' });
  });

}

function updateXVals(ev) {
  
  xText.forEach((val, ind) => {
    ev.matches ?                                  
    val.innerHTML = dayNames[ind] :               
    val.innerHTML = Array.from(dayNames[ind])[0]; 
  });

}

function updateYVals() {
  
  let revenueArray = Object.values(productData[currProduct]);
  let maxVal = Math.max(...revenueArray)
  let values = getYAxisValues(maxVal).reverse();
  
  yText.forEach((val, ind) => {
    val.innerHTML = values[ind]; 
  });
  return {"list": revenueArray, "max": Math.max(...values)};
}

function getYAxisValues(valToScaleBy) {
  
  if(valToScaleBy <= 125) { return [0, 25, 50, 75, 100, 125]; }
  
  if(valToScaleBy  > 125 && valToScaleBy <= 250) { 
    return [0, 50,  100, 150, 200, 250]; 
  }
  if(valToScaleBy > 250 && valToScaleBy <= 500) { 
    return [0, 100, 200, 300, 400, 500]; 
  }
  if(valToScaleBy  > 500 && valToScaleBy <= 1000) { 
    return [0, 200, 400, 600, 800, 1000]; 
  }
  if(valToScaleBy  > 1000 && valToScaleBy <= 2500) { 
    return [0, 500,  1000, 1500, 2000, 2500]; 
  }
  if(valToScaleBy  > 2500 && valToScaleBy <= 5000) { 
    return [0, 1000, 2000, 3000, 4000, 5000]; 
  }
}

//renderBars();
function renderBars(revenue) {
  
  let maxVal = revenue['max'];
  let barHeight, barYPos;

  bars.forEach((bar, ind) => {

    barHeight = (revenue['list'][ind] / maxVal) * 75;
    barYPos = 80 - barHeight;

    bar.setAttribute('height', barHeight.toFixed(2) + '%');
    bar.setAttribute('y', barYPos.toFixed(2) + '%');
  });
  
}

function getProductSales() {
  
  let xhr = new XMLHttpRequest();
  xhr.open('GET', 'product-json.php', true);
  xhr.onload = function() {

    if(this.status >= 200  && this.status < 400) {
      if(!this.responseText) {return;}

      productData = JSON.parse(this.responseText);
      
      currProduct = graphTitle.innerHTML;
      renderGraph();

    } else {
      console.log('Reached the server but there was an error!');
    }

  };

  xhr.onerror = function() {
    console.log('There was an error!');
  }
  
  xhr.send();

}
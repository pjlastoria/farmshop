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

var mediaQueryForXVals = window.matchMedia("(min-width: 460px)");
mediaQueryForXVals.addEventListener('change', updateXVals); // Attach listener function on state changes

let salesReport = [], data, currGraphData, dayNames; //{"day": "Mon", "revenue": 100.89},
getSalesData();

function activateTab(e) {
  
  let tab = this;
  
  currGraphData = this.dataset.heading;
  currActiveTab.classList.remove('active-tab');
  tab.classList.add('active-tab');
  
  currActiveTab = tab;
  graphTitle.innerHTML = currGraphData;
  renderGraph();
}

function renderGraph() {

  let dataArr = updateYVals();
  graphTitle.innerHTML = currGraphData;

  renderXVals();
  renderBars(dataArr);

}

function renderXVals() {

  let dateStrs = Object.keys(data[currGraphData]);
      dayNames = dateStrtoDayName(dateStrs);

  updateXVals(mediaQueryForXVals);//

}

function dateStrtoDayName(strings) {

  return strings.map((str) => {
    let date = new Date(str.replace(/-/g, '\/'));//for some reason in javascript hyphens in a date string returns one day off where as using slashes corrects it
    return date.toLocaleDateString('en-US', { weekday: 'short' });
  });

}

function updateXVals(ev) {
  
  xText.forEach((val, ind) => {
    ev.matches ?                                  //if screen is more than 460px width
    val.innerHTML = dayNames[ind] :               //xvalues on graph will be 3 letter abbr. for day names, Mon, Tue, Wed, etc.
    val.innerHTML = Array.from(dayNames[ind])[0]; //otherwise just one letter, M, T, W...
  });

}

function updateYVals() {

  let dataVals = getValuesOfDataAsInts();

  let maxVal = Math.max(...dataVals)
  let values = getYAxisValues(maxVal).reverse();
  
  yText.forEach((val, ind) => {
    val.innerHTML = values[ind]; 
  });
  return {"list": dataVals, "max": Math.max(...values)};
}

function getValuesOfDataAsInts() {
  let vals = Object.values(data[currGraphData]);
  
  if(currGraphData === 'Customers') {
    return vals.map((val) => val == 0 ? 0 : val.length);// if the array element has no customers it will just be 0 and not an array
  }
  
  return vals;
}

function getYAxisValues(valToScaleBy) {
  if(valToScaleBy <= 10) { return [0, 2, 4, 6, 8, 10]; }
  if(valToScaleBy  > 10 && valToScaleBy <= 50) { return [0, 10, 20, 30, 40, 50]; }
  if(valToScaleBy  > 50 && valToScaleBy <= 100) { return [0, 20, 40, 60, 80, 100]; }
  
  if(valToScaleBy  > 100 && valToScaleBy <= 250) { 
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

function getSalesData() {
  
  let xhr = new XMLHttpRequest();
  xhr.open('GET', 'main-json.php', true);
  xhr.onload = function() {

    if(this.status >= 200  && this.status < 400) {
      if(!this.responseText) {return;}

      data = JSON.parse(this.responseText);

      currGraphData = graphTitle.innerHTML;
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
let pathName = window.location.pathname;
let currTab = pathName.substring(pathName.lastIndexOf("/") + 1);
console.log(currTab);
let listItems, pageBtns, prevBtn, nextBtn, itemCount, lastPage, currPage = 1, itemsPerPage = 8;

if((pathName === '/farmshop/admin/')) {
    document.getElementById("overview-tab").classList.add('active-menu-tab');
} else {
    document.getElementById(currTab + "-tab").classList.add('active-menu-tab');
}

if(currTab === 'orders' || currTab === 'products') {

    listItems = document.getElementById(currTab + "-list");
    listItemsArr = [].slice.call(listItems.children); //turn collection of order eles into array skipping the header
    
    pageBtns = document.getElementsByClassName("page-btns")[0];
    pageBtnsArr = [].slice.call(pageBtns.children); 
    itemCount = document.getElementById(currTab + "-count");
    firstEleOnPage = document.getElementById("first-ele");
    lastEleOnPage = document.getElementById("last-ele");
    pageCount = Math.ceil(parseInt(itemCount.innerHTML) / itemsPerPage);

    pageBtnsArr.forEach((btn) => {
        btn.addEventListener('click', highlightBtn);
    });

    prevBtn = pageBtnsArr.shift();
    nextBtn = pageBtnsArr.pop();
    //now pageBtnsArr is just the numbered buttons

    showPage( parseInt(firstEleOnPage.innerHTML) , parseInt(lastEleOnPage.innerHTML) );//default page and items per page
}

//pagination functionality for tabs with lists

function highlightBtn(e) {
    e.preventDefault();
    currPage = updateCurrPage(this);

    showPage(currPage, itemsPerPage);

    renderPageBtns();
    
}

function updateCurrPage(btnClicked) {
    
    if(btnClicked === prevBtn) { 
        if(currPage <= 1) { return currPage; }
        return currPage - 1; 
    }

    if(btnClicked === nextBtn) { 
        if(currPage >= pageCount) { return currPage; }
        return currPage + 1; 
    }
    
    return +btnClicked.innerHTML;// + turns string to number
}

function showPage(pageNum, itemsPerPage) {
    
    let firstItem = (pageNum * itemsPerPage) - itemsPerPage;
    
    let lastItem = firstItem + itemsPerPage;
    let totalOrders = parseInt(itemCount.innerHTML);

    listItemsArr.forEach((item, ind) => {
        
        if(ind < firstItem || ind >= firstItem + itemsPerPage) {
            item.style.display = "none";
        } else {
            item.style.display = "";
        }

    });

    firstEleOnPage.innerHTML = totalOrders > 8 ? firstItem + 1 : totalOrders;

    if(lastItem < totalOrders) { 
        lastEleOnPage.innerHTML = lastItem; 
    } else {
        lastEleOnPage.innerHTML = totalOrders;
    }

}

function renderPageBtns() {
    
    let counter = 1;

    if(currPage > 3 && pageCount > 5) {
        
        counter = currPage-2;
        
        if(counter > pageCount-4) {
            counter = pageCount-4;
        }
    }


    pageBtnsArr.forEach((ele) => {
 
        ele.innerHTML = counter++;

        if(ele.classList.contains('active-page')) {
            ele.classList.remove('active-page');
        }

        if(ele.innerHTML == currPage) {
            ele.classList.add('active-page');
        }
    });
}
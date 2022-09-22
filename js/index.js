var search_ico = document.getElementsByClassName("search-wrapper");

search_ico[0].addEventListener("mouseover", function (event) {
  document.getElementsByName("search")[0].style.width = "200px";
  document.getElementsByName("search")[0].style.padding = "5px";
});
search_ico[0].addEventListener("mouseleave", function (event) {
  if (document.getElementsByName("search")[0].value == "") {
    document.getElementsByName("search")[0].style.width = "0px";
    document.getElementsByName("search")[0].style.padding = "0px";
  }
});
var loc="http://localhost/Elearn/home.php?s=";
var sea=document.getElementById("sea");
function searchinp(inp){
  if(inp.value.length>2){
    sea.href=loc+inp.value;
  }
  else{
    sea.href="#";
  }

}

function deleteCourse(id){
  var row=document.getElementById('row-'+id);
  var cid=document.getElementsByName('cid-'+id)[0].value;
  var price=document.getElementsByName('price-'+id)[0].value;
  var total=document.getElementsByClassName("price")[0];
  var totalv=total.innerHTML.substr(0, total.innerHTML.length-1);
  totalv=parseFloat(totalv)-parseFloat(price);
  var req="delete="+cid;
  var url="http://localhost/Elearn/cart.php";
  var xhr=new XMLHttpRequest();
  xhr.open("POST", url, true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8")
  xhr.send(req);
  xhr.onload = function(){
    console.log(this.responseText);
    row.style.display="none";
    total.innerHTML=totalv+"$";
  }
}
function unlock(parent, id){
  var lock=document.getElementById('lock-'+id);
  var xhr=new XMLHttpRequest();
  var url="http://localhost/Elearn/profile.php";
  xhr.open("POST", url, true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8")
  try{
    if(typeof lock != null && lock.style.display!="none"){
        var req="id="+id+"&lock=0";
        xhr.send(req);
        xhr.onload = function(){
        lock.style.display="none";
        parent.children[0].classList.replace("fa-unlock", "fa-lock");
        }
      }
      else{
        var req="id="+id+"&lock=1";
        xhr.send(req);
        xhr.onload = function(){
        lock.style.display="initial";
        parent.children[0].classList.replace("fa-lock", "fa-unlock");
        }
      }
  }
  catch{
    var req="id="+id+"&lock=1";
    xhr.send(req);
    xhr.onload = function(){
    lock.style.display="initial";
    parent.children[0].classList.replace("fa-lock", "fa-unlock");
    }
  }
  
  
    
}
function chng_display(id){
  if(id==-1){
    document.getElementsByClassName('edit-overlay')[0].style.display='none';
    document.getElementsByClassName('edit-overlay')[0].children[0].style.display='none';
    document.getElementsByClassName('edit-overlay')[0].children[1].style.display='none';
    return;
  }
  document.getElementsByClassName('edit-overlay')[0].style.display='flex';
  document.getElementsByClassName('edit-overlay')[0].children[id].style.display='block';
}

function save_payment(){
  var forms=document.getElementsByName("edit-payment")[0];
  var url="http://localhost/Elearn/profile.php";
  xhr.open("POST", url, true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
  var req="payment=&uid=";
}
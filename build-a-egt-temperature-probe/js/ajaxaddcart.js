
function addtocart(id){
   

jQuery("#adcrttw").css("display", "none");
jQuery("#loadingtw").css("display", "block");


qty = jQuery("#qtyprc").val();

console.log("qtyline1="+qty);
 if(qty<50){
jQuery.ajax({

type: "POST",

url: "https://blazeprobes.com/build-a-egt-temperature-probe/?page=insert_cart",

data: 'calid='+id,  
success: function(msg){

//jQuery("#step1").removeAttr("disabled","disabled");       
if(msg){

 p_id = msg;

console.log(msg);
// jQuery.get('/blazeprobes.com/shop/?add-to-cart=' + p_id + '&quantity=' + qty, function() {
jQuery.get('/shop/?add-to-cart=' + p_id + '&quantity=' + qty, function() {
             console.log('added');
             window.location.replace("https://blazeprobes.com/cart/");
           
          });
}

else{

  

}

}

});

}
else{
jQuery.ajax({

type: "POST",

url: "https://blazeprobes.com/build-a-egt-temperature-probe/?page=insert_carts",

data: 'calid='+id,  
success: function(msg){  

//jQuery("#step1").removeAttr("disabled","disabled");       

if(msg){
 p_id = msg;

console.log(msg);
jQuery.get('/shop/?add-to-cart=' + p_id + '&quantity=' + qty, function() {
             console.log('added');
             window.location.replace("https://blazeprobes.com/cart/");
           
          });
}

else{

  

}

}

});

}
}
function addtocartmn(id, qty){
console.log("qtyline2="+qty);
jQuery("#adcrt").css("display", "none");
jQuery("#loading").css("display", "block");
   
    
jQuery.ajax({

type: "POST",

url: "https://blazeprobes.com/build-a-egt-temperature-probe/?page=insert_cart",

data: 'calid='+id,  
success: function(msg){  

//jQuery("#step1").removeAttr("disabled","disabled");       

if(msg){
 p_id = msg;

console.log(msg);
jQuery.get('/shop/?add-to-cart=' + p_id + '&quantity=' + qty, function() {
             console.log('added');
             window.location.replace("https://blazeprobes.com/cart/");
           
          });
}

else{

  

}

}

});

}
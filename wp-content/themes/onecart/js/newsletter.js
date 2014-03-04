$(document).ready(function(){
  //hide 4email 
  $('._mCS_2>div>div>li:eq(0)').hide();

   if(getck("showdiv") == "")
   {
    var data = new Date();
    var timestr = data.getFullYear() + "-" + (Number(data.getMonth())+1) + "-" + data.getDate() + " " + data.getHours() + ":" + data.getMinutes() + ":" + data.getSeconds();
    document.cookie="showdiv=" + timestr;
   }
   else
   {
     var date_ = convertdate(getck("showdiv"));
     var data = new Date();
     var num = Number(data.getTime()) - Number(date_.getTime());
     // if(num >= 24*3600000)//已经过期重新设置
     if(num >= 24*7200000)//已经过期重新设置
     {
     //   document.cookie="showdiv=";
     }
   }  
  $("#closebtn").click(function(){document.cookie="isshow=";$("#email-all").hide();});
  $(".newsletter-submit").click(function(){document.cookie="isshow=true";$("#email-all").hide();});
  setTimeout("showdiv()",2000);
}
);
function showdiv()
{ 
   // setTimeout("showdiv()",20000);
   if(getck("isshow")  == "true")
   {
     return;
   }
   else
   {
     if(getck("showdiv") == "")
     {
        $("#email-all").width(document.body.clientWidth);
        $("#email-all").height(document.body.clientHeight);
        $("#email-all").show();
     }
     else
     {
       var date_ = convertdate(getck("showdiv"));
       var data = new Date();
       var num = Number(data.getTime()) - Number(date_.getTime());
      if(num >= 24*3600000)//已经过期重新设置
      {
        document.cookie="showdiv=";
        $("#email-all").width(document.body.clientWidth);
        $("#email-all").height(document.body.clientHeight);
        $("#email-all").show();
      } 
     }
   }
}
var acookie=document.cookie.split("; ");
function getck(sname)
{//获取单个cookies
for(var i=0;i<acookie.length;i++){
var arr=acookie[i].split("=");
if(sname==arr[0]){
if(arr.length>1)
return unescape(arr[1]);
else
return "";
}}
return "";
}

function convertdate(datestr)
{
  var date_hidden = datestr;
  date_hidden = date_hidden.replace(":","-");
  date_hidden = date_hidden.replace(":","-");
  date_hidden = date_hidden.replace(" ","-");
  var date = new Date(Number(date_hidden.split("-")[0]),Number(date_hidden.split("-")[1])-1,Number(date_hidden.split("-")[2]),Number(date_hidden.split("-")[3]),Number(date_hidden.split("-")[4]),Number(date_hidden.split("-")[5]));
  return date;  
}

function clearcookie()
{
document.cookie="showdiv=";
document.cookie="isshow=";location.href=location.href;
}
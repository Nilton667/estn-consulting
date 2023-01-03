document.addEventListener('DOMContentLoaded', function() {
    
    if (!Array.isArray) {
      Array.isArray = function(arg) {
        return Object.prototype.toString.call(arg) === '[object Array]';
      };
    }
    
    //Evento DOMContentLoaded
    setTimeout(function(){
        with(document.querySelector('.preload')){
            style.opacity       = '0';
            style.pointerEvents = 'none';
        }
        with(document.querySelector('.onload')){
            style.opacity       = '1';
            style.pointerEvents = 'auto';
        }
        with(document.querySelector('body')){
            style.overflow      = 'auto';
        }        
    }, 1000);

});

//Gestão de carregamento
if(document.querySelector('.preload')){
    function load() {
        if($('.preload').hasClass('load') == false){
            $('.preload').addClass('load');
        }
    }
    function onload() {
        if($('.preload').hasClass('load')){
            $('.preload').removeClass('load');
        }
    }
}

//Requisições ajax
function request(url = '', method = 'GET', headers = new Headers(), data = {}, isJson = false) {

    return new Promise(function(resolve, reject){

        formData    = new FormData();
        xmlHttp     = new XMLHttpRequest();

        if (xmlHttp == null) {
          return "O seu Browser não suporta Ajax!";
        }else{

        for(var obj in data){
            formData.append(obj, data[obj]);
        }

        xmlHttp.onload = async function(){
            if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete") {
                if(isJson){
                    resolve(JSON.parse(xmlHttp.responseText));
                }else{
                    resolve(xmlHttp.responseText);
                }
            }else{
                reject({
                    status: this.status,
                    statusText: xmlHttp.statusText
                });
            }
        }

        xmlHttp.onerror = async function(){
            reject({
                status: this.status,
                statusText: xmlHttp.statusText
            });
        };

        xmlHttp.open(method, url);
        xmlHttp.send(formData);

        }
    });

}
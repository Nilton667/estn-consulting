(function(window, undefined) {
  'use strict';
  if(document.querySelector('#ft-maximize')){
    document.querySelector('#ft-maximize').addEventListener('click', function(){
      if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen();
      }else{
        if (document.exitFullscreen) {
          document.exitFullscreen(); 
        }
      }
    });    
  }
  /*
  NOTE:
  ------
  PLACE HERE YOUR OWN JAVASCRIPT CODE IF NEEDED
  WE WILL RELEASE FUTURE UPDATES SO IN ORDER TO NOT OVERWRITE YOUR JAVASCRIPT CODE PLEASE CONSIDER WRITING YOUR SCRIPT HERE.  */

  //Calendario
  $('body').delegate('.cal_gregorian_day', 'click', function(e){
    if(this.classList.contains('fixed')){return;}
      if(this.classList.contains('active')){
        this.classList.remove('active');
        if(!document.querySelector('#funcionamento')){return;}
        setCalData(document.querySelector('#funcionamento'));
      }else{
        this.classList.add('active');
        if(!document.querySelector('#funcionamento')){return;}
        setCalData(document.querySelector('#funcionamento'));
      }
  });

  function setCalData(data){
    var arrayData = document.querySelectorAll('.cal_gregorian_day.active');
    if(arrayData.length > 0){
        arrayData.forEach(function(element, index) {
        if(index == 0){ data.value = ''; }
        data.value += element.getAttribute('data-day')+'/';
        data.value += element.getAttribute('data-month')+'/';
        data.value += element.getAttribute('data-year')+',';  
      });
    }else{
      data.value = '';
    }
  }

  var cal_meses   = new Array();
    cal_meses[1]  = 'Janeiro';
    cal_meses[2]  = 'Fevereiro';
    cal_meses[3]  = 'Março';
    cal_meses[4]  = 'Abril';
    cal_meses[5]  = 'Maio';
    cal_meses[6]  = 'Junho';
    cal_meses[7]  = 'Julho';
    cal_meses[8]  = 'Agosto';
    cal_meses[9]  = 'Setembro';
    cal_meses[10] = 'Outubro';
    cal_meses[11] = 'Novembro';
    cal_meses[12] = 'Dezembro';

  if(document.querySelector('.cal-next')){
    document.querySelector('.cal-next').addEventListener('click', function(e){
      if(document.querySelector('.cal_mes.active')){
        
        var next = document.querySelector('.cal_mes.active');
        
        if(next.getAttribute('data-mes')){
          var mes = parseInt(next.getAttribute('data-mes'));
        }else{ return; }

        if(next.classList.contains('active') && mes < 12){
          next.classList.remove('active');
        }

        if(next.getAttribute('data-mes')){
          if(document.querySelector('#mes_'+(mes+1))){
            document.querySelector('#mes_'+(mes+1)).classList.add('active');
            if(document.getElementById('cal_gregorian_header')){
              document.getElementById('cal_gregorian_header').innerHTML = cal_meses[(mes+1)];
            }
          }
        }

      }
    });
  }

  if(document.querySelector('.cal-previus')){
    document.querySelector('.cal-previus').addEventListener('click', function(e){
      if(document.querySelector('.cal_mes.active')){
        
        var previus = document.querySelector('.cal_mes.active');
        
        if(previus.getAttribute('data-mes')){
          var mes = parseInt(previus.getAttribute('data-mes'));
        }else{ return; }

        if(previus.classList.contains('active') && mes > 1){
          previus.classList.remove('active');
        }

        if(previus.getAttribute('data-mes')){
          if(document.querySelector('#mes_'+(mes-1))){
            document.querySelector('#mes_'+(mes-1)).classList.add('active');
            if(document.getElementById('cal_gregorian_header')){
              document.getElementById('cal_gregorian_header').innerHTML = cal_meses[(mes-1)];
            }
          }
        }

      }
    });
  }

  //Altura da div
  $('.content').css({
      'min-height': 'calc(100% - '+$('.footer').outerHeight()+'px)',
  });
  $(window).resize(function(){
    $('.content').css({
        'min-height': 'calc(100% - '+$('.footer').outerHeight()+'px)',
    });
  });

	$('.image-link').magnificPopup({
		type: 'image',
		gallery:{
			enabled: true,
		},
		mainClass: 'mfp-with-zoom',
		zoom: {
		    enabled: true,
		    duration: 300,
		    easing: 'ease-in-out',
		    opener: function(openerElement) {
		      return openerElement.is('img') ? openerElement : openerElement.find('img');
		    }
		}
    });

  //cart state
  if(document.getElementById('shopping-cart-updating-state') && document.getElementById('shopping-cart-updating-deliver-state')){
    var UpdatingStateCartRequest = 0;
    setInterval(
      function(){
        if(UpdatingStateCartRequest == 1){
          return;
        } 
        updatingStateCartRequest()
      }, 5000
    );

    function updatingStateCartRequest(){
      UpdatingStateCartRequest = 1;
      $.ajax({
        url  : "app/api/request",
        type : 'post',
        data : {
        getCartStatus : true,
        header         : 'application/json',
      },
      dataType: 'json',
      beforeSend : function(){
        console.log('Requisitando...');
      }})
      .done(function(msg){
        
        //Cart
        if(document.querySelector('#shopping-cart-updating-state i span')){
          if(msg[0]['pendentes'] == ''){
            document.querySelector('#shopping-cart-updating-state i span').remove();
          }else{
            document.querySelector('#shopping-cart-updating-state i span').textContent = msg[0]['pendentes'];
          }
        }else{
          if(msg[0]['pendentes'] != ''){
            document.querySelector('#shopping-cart-updating-state i').innerHTML = "<span class='badge badge-light'>"+msg[0]['pendentes']+"</span>";
          }
        }

        //Delver
        if(document.querySelector('#shopping-cart-updating-deliver-state i span')){
          if(msg[1]['deliver'] == ''){
            document.querySelector('#shopping-cart-updating-deliver-state i span').remove();
          }else{
            document.querySelector('#shopping-cart-updating-deliver-state i span').textContent = msg[1]['deliver'];
          }
        }else{
          if(msg[1]['deliver'] != ''){
            document.querySelector('#shopping-cart-updating-deliver-state i').innerHTML = "<span class='badge badge-light'>"+msg[1]['deliver']+"</span>";
          }
        }

        UpdatingStateCartRequest = 0;

      })
      .fail(function(jqXHR, textStatus, msg){
        console.log(msg);
        UpdatingStateCartRequest = 0;
      });
    }
  }

  //Verificar chave do e-mail
  if(document.getElementById('verify_account')){
    //Verificar email
    document.getElementById('verify_account_button').addEventListener('click', function(){
      
      let id    = document.getElementById('verify_account_id');
      let key   = document.getElementById('verify_account_key');
  
      if(key.value.trim().length < 6 || key.value.trim().length > 9){
          $.toast({
              heading: 'Alerta',
              text: 'Código inválido!',
              showHideTransition: 'fade',
              icon: 'error',
              loader: true,
          });
          key.focus();
          permitir = 0;
          return;
      }
  
      $.ajax({
          url  : "app/api/login",
          type : 'post',
          data : {
          emailConfirm : true,
          header       : 'application/json',
          id           : id.value.trim(),
          cashe        : key.value.trim()
      },
      dataType: 'json',
      beforeSend : function(){
          load();
      }})
      .done(function(msg){
          if (msg == 1) {
              $.toast({
                heading: 'Alerta',
                text: 'O seu endereço de e-mail foi verificado com sucesso!',
                icon: 'success',
                loader: true,
                loaderBg: '#0088bd'
            });
            setTimeout(function(){ location.reload(); }, 3000);
            return;  
          } else {
              $.toast({
                heading: 'Alerta',
                text: msg,
                showHideTransition: 'fade',
                icon: 'error',
                loader: true,
              });   
          }
          onload();
      })
      .fail(function(jqXHR, textStatus, msg){
          onload();
          $.toast({
            heading: 'Alerta',
            text: msg,
            showHideTransition: 'fade',
            icon: 'error',
            loader: true,
          });
      });

    });

    //Reenviar e-mail
    document.getElementById('verify_account_reenviar').addEventListener('click', function(){
      $.ajax({
        url  : "app/api/login",
        type : 'post',
        data : {
        referencing : true,
        header      : 'application/json'
      },
      dataType: 'json',
      beforeSend : function(){
        load();
      }})
      .done(function(msg){
          if (msg == 1) {
              $.toast({
                heading: 'Alerta',
                text: 'O seu e-mail de verificação foi reenviado com sucesso!',
                icon: 'success',
                loader: true,
                loaderBg: '#0088bd'
            });
          } else {
            $.toast({
                heading: 'Alerta',
                text: msg,
                showHideTransition: 'fade',
                icon: 'error',
                loader: true,
            });   
          }
          onload();
      })
      .fail(function(jqXHR, textStatus, msg){
          onload();
          $.toast({
              heading: 'Alerta',
              text: msg,
              showHideTransition: 'fade',
              icon: 'error',
              loader: true,
          });
      });
    });
  }
    
})(window);

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
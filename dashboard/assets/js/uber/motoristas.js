document.addEventListener('DOMContentLoaded', function(){

    var permitir = 0;

    $('body').delegate('#motorista-visualize', 'click', function(e){
        $('#show-motorista #modal-user-nome').html(this.getAttribute('data-nome'));
        $('#show-motorista #modal-user-identificacao').html(this.getAttribute('data-identificacao'));
        $('#show-motorista #modal-user-nacionalidade').html(this.getAttribute('data-nacionalidade'));
        $('#show-motorista #modal-user-morada').html(this.getAttribute('data-morada'));
        $('#show-motorista #modal-user-senha').html(this.getAttribute('data-senha'));
        $('#show-motorista').modal('show');
    });

    //Selecionar itens
    $(document).delegate('#motorista-id', 'click', function() {
        if (this.checked) {
        document.querySelectorAll('table input[type="checkbox"]').forEach(function(element, index){
            element.checked = true;
        });
        }else{
        document.querySelectorAll('table input[type="checkbox"]').forEach(function(element, index){
            element.checked = false;
        });
        }
    });

    //Deletar
    if (document.querySelector('#modal-delete')){
        document.querySelector('#modal-delete').addEventListener('click', function(){
    
        _seleted = 0;
        document.querySelectorAll('.table input[motorista-select]').forEach(function(element, index){
            if (element.checked) {
                _seleted++;
            }
        });
    
        if(_seleted > 0){
            document.querySelector('#remove-content').innerHTML = _seleted+' motorista(s) selecionado(s) pretende mesmo removelo(s)?';
            document.getElementById('remove-item').hidden       = false;
        }else{
            document.querySelector('#remove-content').innerHTML = 'Nenhum motorista selecionado!';
            document.getElementById('remove-item').hidden       = true;
        }
    
        $('#modal-remove-item').modal('show');
    
        });
    }

    //Evento Deletar
    document.getElementById('remove-item').addEventListener('click',function(){

    if(permitir == 0){
      permitir = 1;
    }else{
      return;
    }

    _seleted = '';
    document.querySelectorAll('.table input[motorista-select]').forEach(function(element, index){
      if (element.checked) {
        _seleted += element.getAttribute('motorista-select')+',';
      }
    });
  
    $.ajax({
      url  : "app/api/uber/motorista",
      type : 'post',
      data : {
      remove_motorista  : true,
      header            : 'application/json',
      motorista_id      : _seleted,
    },
    dataType: 'json',
    beforeSend : function(){
      load();
    }})
    .done(function(msg){
      if(msg == 1){
        location.reload();
        return;
      }else {
        $.toast({
          heading: 'Alerta',
          text: 'Não foi possível efectuar o seu pedido!',
          showHideTransition: 'fade',
          icon: 'error',
          loader: true,
        });
      }
      permitir = 0;
      onload();
    })
    .fail(function(jqXHR, textStatus, msg){
      $.toast({
          heading: 'Alerta',
          text: msg,
          showHideTransition: 'fade',
          icon: 'error',
          loader: true,
      });
      permitir = 0;
      onload();
    });
  
  });

////////////////
//cadastro
document.querySelector('#cadastrar').addEventListener('click',function(){
  cadastrar();
});

function cadastrar() {

  if(permitir == 0){
      permitir = 1;
  }else{
      return;
  }

  var set_id = document.getElementById('set_id');
  var senha  = document.getElementById('senha');

  if(set_id.value.trim() == ''){
    $.toast({
        heading: 'Alerta',
        text: 'Insira um id valido!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
    });
    set_id.focus();
    permitir = 0;
    return;
  }else if(senha.value.trim().length < 6){
    $.toast({
        heading: 'Alerta',
        text: 'A senha deve ter no minímo 6 caracteres!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
    });
    senha.focus();
    permitir = 0;
    return;
  }

  $.ajax({
      url  : "app/api/uber/motorista",
      type : 'post',
      data : {
      cadastro      : true,
      header        : 'application/json',
      set_id        : set_id.value.trim(),
      senha         : senha.value.trim(),
  },
  dataType: 'json',
  beforeSend : function(){
      load();
  }})
  .done(function(msg){
      
      if (msg == 1) {
          $.toast({
              heading: 'Alerta',
              text: 'Esta conta foi registada com sucesso!',
              icon: 'info',
              loader: true,
              loaderBg: '#0088bd'
          });
          setTimeout(function(){ location.href = "./?motoristas"; }, 3000);
          return;           
      }else{
          $.toast({
              heading: 'Alerta',
              text: msg,
              showHideTransition: 'fade',
              icon: 'error',
              loader: true,
          });  
      }

      permitir = 0;
      onload();

  })
  .fail(function(jqXHR, textStatus, msg){
      onload();
      permitir = 0;
      $.toast({
          heading: 'Alerta',
          text: msg,
          showHideTransition: 'fade',
          icon: 'error',
          loader: true,
      });
  });
}

});
document.addEventListener('DOMContentLoaded', function(){

  var permitir = 0;
  var editor;
  
  //ckeditor
  if(document.getElementById('transmicao-text')){
    ClassicEditor
    .create(document.querySelector('#transmicao-text'),{
        language: 'pt',
        toolbar: {
        items: [
            'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'outdent', '|', 'blockQuote', 'insertTable', 'mediaEmbed', 'undo', 'redo'
        ]
        },
    })
    .then(newEditor  => {
        editor = newEditor;
    })
    .catch( error => {
        console.error(error);
    });
  }

  //Reset transmicao
  if(document.querySelector('#reset-transmicao')){
    document.querySelector('#reset-transmicao').addEventListener('click',function(){
        editor.setData('');
        fileName('Escolher arquivo(s)');
    });
  }

  //Origem
  if(document.getElementById('transmicao-origem')){
    document.getElementById('transmicao-origem').addEventListener('change', function(){
      let element = document.getElementById('transmicao-video');
      if(this.value == 'file'){
        element.setAttribute('type', 'file');
        element.setAttribute('name', 'videoFile[]');
        element.setAttribute('placeholder', 'Selecione um arquivo de video');
      }else if(this.value == 'link'){
        element.setAttribute('type', 'url');
        element.setAttribute('name', 'video');
        element.setAttribute('placeholder', 'Link de video');
      }
    });
  }

  //Visualizar imagem
  $(document).delegate('.visualizar-imagem', 'click', function(){

    if($(this).attr('data-imagem') == ''){
      $.toast({
          heading: 'Alerta',
          text: 'Selecione no mínimo uma imagem para visualizar!',
          showHideTransition: 'fade',
          icon: 'error',
          loader: true,
      });
      return;
    }

    var file = $(this).attr('data-imagem');
    document.querySelector('#view-image').src = file;
    $('#image-test').modal('show');

  }); 

  //Reproduzir video
  $(document).delegate('.reproduzir-video', 'click', function(){

    if($(this).attr('data-video').trim() == ''){
      $.toast({
          heading: 'Alerta',
          text: 'Nenhum resultado encontrado!',
          showHideTransition: 'fade',
          icon: 'error',
          loader: true,
      });
      return;
    }else if($(this).attr('data-origem').trim() == ''){
      $.toast({
        heading: 'Alerta',
        text: 'Origem não identificada!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      return;
    }

    if($(this).attr('data-origem').trim() == 'link'){
      document.querySelector('#view-video').innerHTML = "<video controls style='width: 100%; border-radius: 8px;' src='"+atob($(this).attr('data-video').trim())+"'></video>"
    }else if($(this).attr('data-origem').trim() == 'file'){
      document.querySelector('#view-video').innerHTML = "<video controls style='width: 100%; border-radius: 8px;' src='../publico/transmicao/video/"+atob($(this).attr('data-video').trim())+"'></video>"
    }else{
      $.toast({
        heading: 'Alerta',
        text: 'Não foi possível carregar o player de video!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      return;
    }
    $('#video-test').modal('show');

}); 

  //Adicionar transmicao
  $('#add-transmicao').click(function(){
    if(permitir == 0){
      permitir = 1;
    }else{
      return;
    }

    var titulo    = document.querySelector('#transmicao-titulo');
    var origem    = document.getElementById('transmicao-origem');
    var video     = document.getElementById('transmicao-video');
    var descricao = document.querySelector('#transmicao-descricao');

    descricao.value = editor.getData();

    if (titulo.value.trim() == '') {
      $.toast({
        heading: 'Alerta',
        text: 'Insira um título valido!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      titulo.focus();
      permitir = 0;
      return;
    }else if(origem.value.trim() == ''){
      $.toast({
        heading: 'Alerta',
        text: 'Selecione a origem do video!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      origem.focus();
      permitir = 0;
      return;
    }else if(video.value.trim() == ''){
      $.toast({
        heading: 'Alerta',
        text: 'Insira o link ou um arquivo de video!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      video.focus();
      permitir = 0;
      return;
    }

    $('#form-transmicao').ajaxForm({
    uploadProgress: function (event, position, total, percentComplete) {
      $('#progress').modal('show');
      with(document.querySelector('#progress .progress-bar').style){
          width = percentComplete+'%';
      }
    },
    success: function(msg){
      if(msg == 1){
        $.toast({
          heading: 'Alerta',
          text: 'O seu transmição foi registada com sucesso!',
          icon: 'success',
          loader: true,
          loaderBg: '#0088bd'
        });
        setTimeout(function(){ location.href= './?transmicao'; }, 3000);
        return;
      }else if(msg == 0){
        $.toast({
        heading: 'Alerta',
        text: 'Não foi possível registar a sua transmição!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
        });
      }else {
        $.toast({
        heading: 'Alerta',
        text: msg,
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
        });
      }
      permitir = 0;
      setTimeout(function(){ 
        $('#progress').modal('hide');
      }, 1000);

      with(document.querySelector('#progress .progress-bar').style){
        width = '0%';
      }
    },
    error: function(err){
      $.toast({
        heading: 'Alerta',
        text: err,
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      permitir = 0;
      setTimeout(function(){ 
        $('#progress').modal('hide');
      }, 1000);

      with(document.querySelector('#progress .progress-bar').style){
          width = '0%';
      }
    },
    dataType: 'json',
    url : 'app/api/transmicao/transmicao',
    resetForm: false
    }).submit();
  });  

  //Selecionar itens
  $(document).delegate('#transmicao-id', 'click', function() {
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
    document.querySelectorAll('.table input[transmicao-select]').forEach(function(element, index){
      if (element.checked) {
        _seleted++;
      }
    });

    if(_seleted > 0){
      document.querySelector('#remove-content').innerHTML = _seleted+' transmisão(ções) selecionada(s) pretende mesmo removela(s)?';
      document.getElementById('remove-item').hidden       = false;
    }else{
      document.querySelector('#remove-content').innerHTML = 'Nenhum transmição selecionada!';
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
    document.querySelectorAll('.table input[transmicao-select]').forEach(function(element, index){
      if (element.checked) {
        _seleted += element.getAttribute('transmicao-select')+',';
      }
    });
  
    $.ajax({
      url  : "app/api/transmicao/transmicao",
      type : 'post',
      data : {
      remove_transmicao : true,
      header         : 'application/json',
      transmicao_id     : _seleted,
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
          text: 'Não foi possível efetuar o seu pedido!',
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

  //Evento Editar
  if(document.getElementById('edit-transmicao')){
    document.getElementById('edit-transmicao').addEventListener('click',function(){

      if(permitir == 0){
        permitir = 1;
      }else{
        return;
      }

      var titulo       = document.querySelector('#transmicao-titulo');
      var descricao    = document.querySelector('#transmicao-descricao');
      var origem       = document.getElementById('transmicao-origem');
      var video        = document.getElementById('transmicao-video');
      var descricao    = document.querySelector('#transmicao-descricao');

      descricao.value = editor.getData();

      if (titulo.value.trim() == '') {
        $.toast({
          heading: 'Alerta',
          text: 'Insira um título valido!',
          showHideTransition: 'fade',
          icon: 'error',
          loader: true,
        });
        titulo.focus();
        permitir = 0;
        return;
      }else if(origem.value.trim() == ''){
        $.toast({
          heading: 'Alerta',
          text: 'Selecione a origem do video!',
          showHideTransition: 'fade',
          icon: 'error',
          loader: true,
        });
        origem.focus();
        permitir = 0;
        return;
      }else if(video.value.trim() == '' && origem.value.trim() != 'file'){
        $.toast({
          heading: 'Alerta',
          text: 'Insira o link ou um arquivo de video!',
          showHideTransition: 'fade',
          icon: 'error',
          loader: true,
        });
        video.focus();
        permitir = 0;
        return;
      }

      $('#form-transmicao').ajaxForm({
        uploadProgress: function (event, position, total, percentComplete) {
        load();
      },
      success: function(msg){
        if(msg == 1){
          location.reload();
        }else if(msg == 0){
        $.toast({
          heading: 'Alerta',
          text: 'Não foi possível editar a transmição selecionada!',
          showHideTransition: 'fade',
          icon: 'error',
          loader: true,
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
      permitir = 0;
      onload();
      },
      error: function(msg){
        $.toast({
          heading: 'Alerta',
          text: msg,
          showHideTransition: 'fade',
          icon: 'error',
          loader: true,
        });
        permitir = 0;
        onload();
      },
      dataType: 'json',
      url : 'app/api/transmicao/transmicao',
      resetForm: false
      }).submit();

    });
  }


});

//fileName
function fileName(str){
  if(document.querySelector('#input-file-label')){
    document.querySelector('#input-file-label').textContent = str.trim();
  }
}

function editFileName(str){
  if(document.querySelector('#edit-input-file-label')){
    document.querySelector('#edit-input-file-label').textContent = str.trim();
  }
}
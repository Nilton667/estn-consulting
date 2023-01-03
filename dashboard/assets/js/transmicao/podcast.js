document.addEventListener('DOMContentLoaded', function(){

  var permitir = 0;
  var editor;
  
  //ckeditor
  if(document.getElementById('podcast-text')){
    ClassicEditor
    .create(document.querySelector('#podcast-text'),{
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

  //Reset podcast
  if(document.querySelector('#reset-podcast')){
    document.querySelector('#reset-podcast').addEventListener('click',function(){
        editor.setData('');
        fileName('Escolher arquivo(s)');
    });
  }

  //Origem
  if(document.getElementById('podcast-origem')){
    document.getElementById('podcast-origem').addEventListener('change', function(){
      let element = document.getElementById('podcast-audio');
      if(this.value == 'file'){
        element.setAttribute('type', 'file');
        element.setAttribute('name', 'audioFile[]');
        element.setAttribute('placeholder', 'Selecione um arquivo de audio');
      }else if(this.value == 'link'){
        element.setAttribute('type', 'url');
        element.setAttribute('name', 'audio');
        element.setAttribute('placeholder', 'Link de audio');
      }else{
        element.setAttribute('type', 'text');
        element.setAttribute('name', 'audio');
        element.setAttribute('placeholder', 'Iframe SoundCloud');
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

  //Reproduzir audio
  $(document).delegate('.reproduzir-audio', 'click', function(){

    if($(this).attr('data-audio').trim() == ''){
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

    if($(this).attr('data-origem').trim() == 'soundcloud'){
      
      document.querySelector('#view-audio').innerHTML = atob($(this).attr('data-audio').trim());

    }else if($(this).attr('data-origem').trim() == 'link'){
      document.querySelector('#view-audio').innerHTML = "<audio controls style='width: 100%;' src='"+atob($(this).attr('data-audio').trim())+"'></audio>"
    }else if($(this).attr('data-origem').trim() == 'file'){
      document.querySelector('#view-audio').innerHTML = "<audio controls style='width: 100%;' src='../publico/transmicao/podcast/"+atob($(this).attr('data-audio').trim())+"'></audio>"
    }else{
      $.toast({
        heading: 'Alerta',
        text: 'Não foi possível carregar o player de audio!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      return;
    }
    $('#audio-test').modal('show');

}); 

  //Adicionar podcast
  $('#add-podcast').click(function(){
    if(permitir == 0){
      permitir = 1;
    }else{
      return;
    }

    var titulo    = document.querySelector('#podcast-titulo');
    var origem    = document.getElementById('podcast-origem');
    var audio     = document.getElementById('podcast-audio');
    var descricao = document.querySelector('#podcast-descricao');

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
        text: 'Selecione a origem do audio!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      origem.focus();
      permitir = 0;
      return;
    }else if(audio.value.trim() == ''){
      $.toast({
        heading: 'Alerta',
        text: 'Insira o link ou um arquivo de audio!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      audio.focus();
      permitir = 0;
      return;
    }

    $('#form-podcast').ajaxForm({
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
          text: 'O seu podcast foi registado com sucesso!',
          icon: 'success',
          loader: true,
          loaderBg: '#0088bd'
        });
        setTimeout(function(){ location.href= './?podcast'; }, 3000);
        return;
      }else if(msg == 0){
        $.toast({
        heading: 'Alerta',
        text: 'Não foi possível registar o seu podcast!',
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
    url : 'app/api/transmicao/podcast',
    resetForm: false
    }).submit();
  });  

  //Selecionar itens
  $(document).delegate('#podcast-id', 'click', function() {
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
    document.querySelectorAll('.table input[podcast-select]').forEach(function(element, index){
      if (element.checked) {
        _seleted++;
      }
    });

    if(_seleted > 0){
      document.querySelector('#remove-content').innerHTML = _seleted+' podcast selecionado(s) pretende mesmo removelo(s)?';
      document.getElementById('remove-item').hidden       = false;
    }else{
      document.querySelector('#remove-content').innerHTML = 'Nenhum podcast selecionado!';
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
    document.querySelectorAll('.table input[podcast-select]').forEach(function(element, index){
      if (element.checked) {
        _seleted += element.getAttribute('podcast-select')+',';
      }
    });
  
    $.ajax({
      url  : "app/api/transmicao/podcast",
      type : 'post',
      data : {
      remove_podcast : true,
      header         : 'application/json',
      podcast_id     : _seleted,
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

  //Evento Editar
  if(document.getElementById('edit-podcast')){
    document.getElementById('edit-podcast').addEventListener('click',function(){

      if(permitir == 0){
        permitir = 1;
      }else{
        return;
      }

      var titulo       = document.querySelector('#podcast-titulo');
      var descricao    = document.querySelector('#podcast-descricao');
      var origem       = document.getElementById('podcast-origem');
      var audio        = document.getElementById('podcast-audio');
      var descricao    = document.querySelector('#podcast-descricao');

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
          text: 'Selecione a origem do audio!',
          showHideTransition: 'fade',
          icon: 'error',
          loader: true,
        });
        origem.focus();
        permitir = 0;
        return;
      }else if(audio.value.trim() == '' && origem.value.trim() != 'file'){
        $.toast({
          heading: 'Alerta',
          text: 'Insira o link ou um arquivo de audio!',
          showHideTransition: 'fade',
          icon: 'error',
          loader: true,
        });
        audio.focus();
        permitir = 0;
        return;
      }

      $('#form-podcast').ajaxForm({
        uploadProgress: function (event, position, total, percentComplete) {
        load();
      },
      success: function(msg){
        if(msg == 1){
          location.reload();
        }else if(msg == 0){
        $.toast({
          heading: 'Alerta',
          text: 'Não foi possível editar o podcast selecionado!',
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
      url : 'app/api/transmicao/podcast',
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
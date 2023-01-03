document.getElementById('send-email').addEventListener('click',function() {
    var nome     = document.querySelector('#recipient-name');
    var email    = document.querySelector('#recipient-email');
    var assunto  = document.querySelector('#recipient-assunto');
    var mensagem = document.querySelector('#recipient-mensagem');

    if (nome.value.trim().length < 6 || nome.value.trim().match(/\s/g) == null) {
        $.toast({
            heading: 'Alerta',
            text: 'Insira o seu nome completo!',
            showHideTransition: 'fade',
            icon: 'error',
            loader: true,
        });
        nome.focus();
        return;
    }else if (email.value.trim().length < 6 || email.value.trim().search('@') == -1 || email.value.trim().match(/\s/g) != null) {
        $.toast({
            heading: 'Alerta',
            text: 'Insira um email valido!',
            showHideTransition: 'fade',
            icon: 'error',
            loader: true,
        });
        email.focus();
        return;
    }else if(assunto.value.trim().length < 4){
        $.toast({
            heading: 'Alerta',
            text: 'Insira um assunto valido!',
            showHideTransition: 'fade',
            icon: 'error',
            loader: true,
        });
        assunto.focus();
        return;
    } else if (mensagem.value.trim() == "") {
        $.toast({
            heading: 'Alerta',
            text: 'Digite a sua mensagem!',
            showHideTransition: 'fade',
            icon: 'error',
            loader: true,
        });
        mensagem.focus();
        return;
    }

    xmlHttp = new XMLHttpRequest();

    if (xmlHttp == null) {
        $.toast({
            heading: 'Alerta',
            text: 'O seu Browser não suporta Ajax experimente actualiza-lo!',
            showHideTransition: 'fade',
            icon: 'error',
            loader: true,
        });
	}else{
    
    //load
    load();

    var link = 'app/envoyer';
    
    formData = new FormData();
    formData.append("send_email", true);
    formData.append("nome", nome.value.trim());
    formData.append("email", email.value.trim());
    formData.append("assunto", assunto.value.trim());
    formData.append("mensagem", mensagem.value.trim());

	xmlHttp.onreadystatechange = function() {
		if (xmlHttp.readyState == 4 || xmlHttp.readyState=="complete") {

            //onload
            onload();
			if(xmlHttp.responseText == 1){
                $.toast({
                    heading: 'Alerta',
                    text: 'O seu email foi enviado com sucesso',
                    showHideTransition: 'fade',
                    icon: 'success',
                    loader: true,
                });
                nome.value         = "";
                email.value        = "";
                assunto.value      = "";
                mensagem.value     = "";
            }else{
                $.toast({
                    heading: 'Alerta',
                    text: 'Não foi possível enviar o seu email!',
                    showHideTransition: 'fade',
                    icon: 'error',
                    loader: true,
                });
            }
		}
	}

	xmlHttp.open("POST",link,true);
	xmlHttp.send(formData);
  }
});
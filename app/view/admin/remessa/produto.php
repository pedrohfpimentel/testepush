<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Auto Complete</title>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" />
<script src="javascript/jquery-1.8.3.js"></script>
<script src="javascript/jquery-ui.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
         
        // Captura o retorno do returnProducts.php
        $.getJSON('retornProducts.php', function(data){
            var products = [];
             
            // Armazena no array capturando somente o nome do products
            $(data).each(function(key, value) {
                products.push(value.products);
            });
             
            // Chama o Auto complete do JQuery ui setando o id do input, array com os dados e o mínimo de caracteres para disparar o AutoComplete
            $('#id_products').autocomplete({ source: products, minLength: 1});
        });
    });
</script>
</head>
<body>
    <label>Produto:</label>
    <input type="text" id="id_products" name="id_products" size="60"/>
</body>
</html>
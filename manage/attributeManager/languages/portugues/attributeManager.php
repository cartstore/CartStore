<?php
/*
  $Id: attributeManager.php,v 1.0 21/02/06 Sam West$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Released under the GNU General Public License
  
  Tradução para Português do Brasil de AJAX-AttributeManager-V2.7
  
  por Valmy Gomes (Atualizado em 28/01/2010)
  Conheça a LEGALLOJA, uma OsCommerce integrada com Mercado Livre, Toda Oferta, Twitter e SMS
  http://www.legalloja.com.br
  valmygomes@legalzona.com.br
  */

//attributeManagerPrompts.inc.php

define('AM_AJAX_YES', 'Sim');
define('AM_AJAX_NO', 'Não');
define('AM_AJAX_UPDATE', 'Atualizar');
define('AM_AJAX_CANCEL', 'Cancelar');
define('AM_AJAX_OK', 'OK');

define('AM_AJAX_SORT', 'Ordenar:');
define('AM_AJAX_TRACK_STOCK', 'Seguir estoque?');
define('AM_AJAX_TRACK_STOCK_IMGALT', 'Seguir estoque deste atributo?');

define('AM_AJAX_ENTER_NEW_OPTION_NAME', 'Novo atributo');
define('AM_AJAX_ENTER_NEW_OPTION_VALUE_NAME', 'Novo valor');
define('AM_AJAX_ENTER_NEW_OPTION_VALUE_NAME_TO_ADD_TO', 'Novo nome do valor para adicionar a %s');

define('AM_AJAX_PROMPT_REMOVE_OPTION_AND_ALL_VALUES', 'Tem certeza que deseja apagar os atributos de %s e todos seus valores para este produto?');
define('AM_AJAX_PROMPT_REMOVE_OPTION', 'Tem certeza que deseja apagar %s deste produto?');
define('AM_AJAX_PROMPT_STOCK_COMBINATION', 'Tem certeza que deseja remover esta combinação de estoque deste produto?');

define('AM_AJAX_PROMPT_LOAD_TEMPLATE', 'Tem certeza que deseja carregar %s do gabarito? <br />Isto sobrescreverá as opções atuais do produto. Esta operação não pode ser desfeita!');
define('AM_AJAX_NEW_TEMPLATE_NAME_HEADER', 'Digite o nome do novo Gabarito. Ou...');
define('AM_AJAX_NEW_NAME', 'Novo nome:');
define('AM_AJAX_CHOOSE_EXISTING_TEMPLATE_TO_OVERWRITE', ' ...<br /> ... escolha um que já existe para sobrescrevê-lo');
define('AM_AJAX_CHOOSE_EXISTING_TEMPLATE_TITLE', 'Já existe:'); 
define('AM_AJAX_RENAME_TEMPLATE_ENTER_NEW_NAME', 'Digite o novo nome para o Gabarito %s');
define('AM_AJAX_PROMPT_DELETE_TEMPLATE', 'Tem certeza que deseja apagaro Gabarito %s?<br>Esta operação não pode ser desfeita!');

//attributeManager.php

define('AM_AJAX_ADDS_ATTRIBUTE_TO_OPTION', 'Adiciona o atributo selecionado na esquerda para a opção %s');
define('AM_AJAX_ADDS_NEW_VALUE_TO_OPTION', 'Adiciona um novo valor para a opção %s');
define('AM_AJAX_PRODUCT_REMOVES_OPTION_AND_ITS_VALUES', 'Remover a opção %1$s e o(s) valor(s) %2$d  abaixo deste produto');
define('AM_AJAX_CHANGES', 'Modificar'); 
define('AM_AJAX_LOADS_SELECTED_TEMPLATE', 'Carregar o Gabarito selecionado');
define('AM_AJAX_SAVES_ATTRIBUTES_AS_A_NEW_TEMPLATE', 'Salvar os atributos atuais como um novo Gabarito');
define('AM_AJAX_RENAMES_THE_SELECTED_TEMPLATE', 'Renomear o gabarito selecionado');
define('AM_AJAX_DELETES_THE_SELECTED_TEMPLATE', 'Apagar o gabarito selecionado');
define('AM_AJAX_NAME', 'Nome do Atributo<a class="ajuda" href="#"><img src="layout/help1.gif" width="24" height="16" border="0"><span><font color="#FF0000">Atenção:</font><BR>
            Aqui você pode criar atributos para os Produtos e dar-lhes opções e valores diferentes. Os atributos servem para que você cadastre variedade de um mesmo produto. EX:<BR> "Opção" Tamanho = <font color="#FF0000">"Valores" P, M e G</font><BR>"Opção" Cor = <font color="#FF0000">"Valores" Azul, Preta, Rosa e Amarela</font><BR>"Opção" Voltagem = <font color="#FF0000">"Valores" 110v e 220v</font><BR>Os valores dos atributos também podem ter preços diferentes, para isto utilize o prefix + ou - e preencha o campo "Diferença de Preço".<BR>Use os "GABARITOS" para salvar suas combinações de atributos e inserí-las rapidamente mais tarde. </span></a>
            ');
define('AM_AJAX_ACTION', 'Ação');
define('AM_AJAX_QT_PRO', 'Quantidades em estoque');
define('AM_AJAX_PRODUCT_REMOVES_VALUE_FROM_OPTION', 'Remover %1$s de %2$s, deste produto');
define('AM_AJAX_MOVES_OPTION_UP', 'Mover opção para cima');
define('AM_AJAX_MOVES_OPTION_DOWN', 'Mover opção para baixo');
define('AM_AJAX_MOVES_VALUE_UP', 'Mover valor para cima');
define('AM_AJAX_MOVES_VALUE_DOWN', 'Mover valor para baixo');
define('AM_AJAX_ADDS_NEW_OPTION', 'Adicionar uma nova opção na lista');
define('AM_AJAX_OPTION', 'Opção:');
define('AM_AJAX_VALUE', 'Valor:');
define('AM_AJAX_PREFIX', 'Prefixo:');
define('AM_AJAX_PRICE', 'Diferença de Preço:');
define('AM_AJAX_WEIGHT_PREFIX', 'Unidade Peso:');
define('AM_AJAX_WEIGHT', 'Peso:');
define('AM_AJAX_SORT', 'Ordem:');
define('AM_AJAX_ADDS_NEW_OPTION_VALUE', 'Adicionar uma nova opção de valor na lista');
define('AM_AJAX_ADDS_ATTRIBUTE_TO_PRODUCT', 'Adicionar este atributo ao produto atual');
define('AM_AJAX_DELETES_ATTRIBUTE_FROM_PRODUCT', 'Excluir o atributo ou combinação de atributos do produto atual');
define('AM_AJAX_QUANTITY', 'Quantidade');
define('AM_AJAX_PRODUCT_REMOVE_ATTRIBUTE_COMBINATION_AND_STOCK', 'Remover esta combinação de atributos e estoque deste produto');
define('AM_AJAX_UPDATE_OR_INSERT_ATTRIBUTE_COMBINATIONBY_QUANTITY', 'Atualizar ou inserir a combinação de atributo com a determinada quantidade');
define('AM_AJAX_UPDATE_PRODUCT_QUANTITY', 'Definir a quantidade indicada para o produto atual');

//attributeManager.class.php
define('AM_AJAX_TEMPLATES', '-- Gabaritos --');

//----------------------------
// Change: download attributes for AM
//
// author: mytool
//-----------------------------
define('AM_AJAX_FILENAME', 'Nome do Arquivo');
define('AM_AJAX_FILE_DAYS', 'Dias para baixar');
define('AM_AJAX_FILE_COUNT', 'Máximo de Downloads');
define('AM_AJAX_DOWLNOAD_EDIT', 'Editar Opções de Download');
define('AM_AJAX_DOWLNOAD_ADD_NEW', 'Adicionar Opções de Download');
define('AM_AJAX_DOWLNOAD_DELETE', 'Apagar Opções de Download');
define('AM_AJAX_HEADER_DOWLNOAD_ADD_NEW', 'Adicionar Opções de Download a \"%s\"');
define('AM_AJAX_HEADER_DOWLNOAD_EDIT', 'Editar Opções de Download de \"%s\"');
define('AM_AJAX_HEADER_DOWLNOAD_DELETE', 'Apagar Opções de Download \"%s\"');
define('AM_AJAX_FIRST_SAVE', 'Salvar produto antes de adicionar opções');

//----------------------------
// EOF Change: download attributes for AM
//-----------------------------

define('AM_AJAX_OPTION_NEW_PANEL','Nova Opção:');
?>
<?php
include_once __DIR__. '/configs.php';
require __DIR__.'/composer/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

// Abrindo as pastas
$parceiros = opendir(DIR_ARQUIVOS_PARCEIROS);
$ativmob = opendir(DIR_ARQUIVOS_ATIVMOB);

$arquivosParceiros = [];
$arquivosAtivmob = [];

// Listando os arquivos na pasta de parceiros
while ($nome_item = readdir($parceiros)) {
    if ($nome_item != "." && $nome_item != ".." && !is_dir(DIR_ARQUIVOS_PARCEIROS . '/' . $nome_item)) {
        $arquivosParceiros[] = DIR_ARQUIVOS_PARCEIROS . '/' . $nome_item;
    }
}

// Listando os arquivos na pasta de Ativmob
while ($nome_item = readdir($ativmob)) {
    if ($nome_item != "." && $nome_item != ".." && !is_dir(DIR_ARQUIVOS_ATIVMOB . '/' . $nome_item)) {
        $arquivosAtivmob[] = DIR_ARQUIVOS_ATIVMOB . '/' . $nome_item;
    }
}

// Verificando se encontramos os arquivos necessários
if (count($arquivosParceiros) == 0 || count($arquivosAtivmob) == 0) {
    die("Não foram encontrados arquivos nas pastas especificadas.");
}

// Carregando as planilhas (considerando que há apenas um arquivo por pasta)
$spreadsheet1 = IOFactory::load($arquivosParceiros[0]);
$spreadsheet2 = IOFactory::load($arquivosAtivmob[0]);

// Selecionando a aba "Base Mottu" na planilha de parceiros
$sheetParceiro = $spreadsheet1->getSheetByName('Base Mottu');
$sheetAtivmob = $spreadsheet2->getSheet(0);

// Verificando se as abas foram encontradas
if ($sheetParceiro === null) {
    die("A aba 'Base Mottu' não foi encontrada na planilha do parceiro.");
}

// Obtendo o número de linhas de ambas as planilhas
$rowCountParceiro = $sheetParceiro->getHighestRow();
$rowCountAtivmob = $sheetAtivmob->getHighestRow();

// Inicializando arrays para armazenar os valores
$colunaParceiro = [];
$colunaAtivmob = [];

// Iterando sobre as linhas e extraindo os valores das colunas específicas
for ($row = 1; $row <= $rowCountParceiro; $row++) {
    $pedido = $sheetParceiro->getCell('J' . $row)->getValue(); // Substitua 'A' pela coluna correta para 'Pedido Mottu'
    $filial = $sheetParceiro->getCell('M' . $row)->getValue();
    if ($pedido !== null) {
        $colunaParceiro[] = json_encode(array("delivery_id" => $pedido, "filial" => $filial));
    }
}

for ($row = 1; $row <= $rowCountAtivmob; $row++) {
    $solPsd = $sheetAtivmob->getCell('E' . $row)->getValue(); // Coluna 'N° Sol. PSD'
    $filial = $sheetAtivmob->getCell('A' . $row)->getValue(); // Coluna 'Filial'

    // Verificação e extração de RichText
    if ($solPsd instanceof \PhpOffice\PhpSpreadsheet\RichText\RichText) {
        $solPsd = $solPsd->getPlainText();
    }
    if ($filial instanceof \PhpOffice\PhpSpreadsheet\RichText\RichText) {
        $filial = $filial->getPlainText();
    }

    if ($solPsd !== null) {
        $colunaAtivmob[] = json_encode(array("delivery_id" => $solPsd, "filial" => $filial));
    }
}


// foreach ($colunaParceiro as $info) {
//     foreach ($colunaAtivmob as $value) {
//         var_dump($value);
//     }
    
// }

// Comparando as colunas
// $diferencas = array_diff($colunaParceiro, $colunaAtivmob);

// if (empty($diferencas)) {
//     echo "Todas as entradas coincidem.\n";
// } else {
//     echo "Diferenças encontradas:\n";
//     print_r($diferencas);
// }

// Fechar os diretórios abertos
closedir($parceiros);
closedir($ativmob);
?>
